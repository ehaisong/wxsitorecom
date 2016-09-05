<?php


class SCANEventReply
{
	public $token;
	public $wecha_id;
	public $id;
	public $siteUrl;
	public $weixin;
	public $ali;
	public $data;
	public $fun;
	private $thisWxUser;
	public function __construct($token, $wecha_id, $data, $siteUrl, $ali = 0)
	{
		$this->token = $token;
		$this->wecha_id = $wecha_id;
		$this->data = $data;
		$this->id = $data['EventKey'];
		$this->siteUrl = $siteUrl;
		$this->ali = $ali;
		$this->weixin = A('Home/Weixin');
		$this->thisWxUser = M('Wxuser')->field('appid,appsecret,winxintype')->where(array('token' => $token))->find();
		$open = M('Token_open')->where(array('token' => $this->token))->find();
		$this->fun = $open['queryname'];
	}
	public function index()
	{
		// 回复插件拦截代码开始
		import("@.Model.ResponsePlugin.ResponsePluginModel");
		$model = new ResponsePluginModel($this->thisWxUser, $this->data);
		$rs = $model->run();
		if (!empty($rs)) {
			return $rs;
		}
		// 回复插件拦截代码结束

		$id = $this->id;
		$GetDb = D('Recognition');
		$data = $GetDb->where(array('id' => $id, 'token' => $this->token))->find();
		$keyword = '';
		if (!empty($data)) {
			if (!empty($data['type'])) {
				$updateData['scans'] = $data['scans'] + 1;
				if ($this->data['Event'] == 'subscribe') {
					$updateData['hits'] = $data['hits'] + 1;
				}
				$GetDb->where(array('id' => $id))->save($updateData);
				$reg_scenes = C('REG_SCENES');
				$handlers = array();
				if (!empty($reg_scenes[$data['type']])) {
					$handlers = is_array($reg_scenes[$data['type']]) ? $reg_scenes[$data['type']] : explode(',', $reg_scenes[$data['type']]);
				} else {
					$handlers[] = 'CommonScene@' . strtolower($data['type']);
				}
				for ($i = 0; $i < count($handlers); $i++) {
					$obj = is_string($handlers[$i]) ? explode('@', $handlers[$i]) : $handlers[$i];
					$className = $obj[0] ? $obj[0] : 'CommonScene';
					$fun = $obj[1];
					if (strpos($obj[0], '.') !== false) {
						$path = strpos($obj[0], '.php') === false ? $obj[0] . '.class.php' : $obj[0];
						$className = preg_replace('/\\.class\\.php$/', '', basename($path));
						if (!class_exists($className)) {
							include $path;
						}
					}
					$myClass = new $className($this->token, $this->wecha_id, $this->thisWxUser, $this->siteUrl, $this->data, $data, $this->fun);
					$result = call_user_func_array(array($myClass, $fun), array());
					if (!empty($result)) {
						return $result;
					}
				}
			} else {
				if ($data['status'] == 0) {
					$id_Recognition_data = M('recognition_data')->add(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'rid' => $id, 'time' => time(), 'year' => intval(date('Y')), 'month' => intval(date('m')), 'day' => intval(date('d')), 'is_ali' => $this->ali));
					$GetDb->where(array('id' => $id))->setInc('attention_num');
					if ($data['keyword'] == 'CashierCoupon') {
						if ($coupon = M('Cashier_wxcoupon')->where(array('id' => $data['scene_id'], 'token' => $this->token))->find()) {
							$thisWxUser = $this->thisWxUser;
							if (empty($thisWxUser)) {
								return false;
							}
							$apiOauth = new apiOauth();
							$access_token = $apiOauth->update_authorizer_access_token($thisWxUser['appid']);
							$postData = '{"touser":"' . $this->wecha_id . '","msgtype":"wxcard","wxcard":{"card_id":"' . $coupon['card_id'] . '"}}';
							$extraUrl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
							$result_json = $this->api_notice_increment($extraUrl, $postData, 0, 0);
							return false;
						}
					}
					$wecha_id = $this->wecha_id;
					$group_list = M('wechat_group_list');
					$fid = $group_list->where(array('token' => $this->token, 'openid' => $wecha_id))->getField('id');
					if ($fid) {
						$group_list->where('id=' . $fid)->setField('g_id', $data['groupid']);
					} else {
						$group_list->add(array('token' => $this->token, 'openid' => $wecha_id, 'g_id' => $data['groupid']));
					}
					$access_token = $this->weixin->api('_getAccessToken');
					$url = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=' . $access_token;
					$json = json_decode($this->weixin->api('curlGet', $url, 'post', '{"openid":"' . $wecha_id . '","to_groupid":' . $data['groupid'] . '}'));
					$keyword = $data['keyword'];
					$this->data['Content'] = $data['keyword'];
				}
			}
		}
		$keywordModel = new KeywordModel($this->token, $this->data, $this->fun, $this->sitUrl);
		return $keywordModel->handler($keyword);
	}
}