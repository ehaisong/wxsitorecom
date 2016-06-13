<?php


class RecognitionAction extends UserAction
{
	public $thisWxUser;
	public $isgostr;
	public function _initialize()
	{
		parent::_initialize();
		if (ALI_FUWU_GROUP) {
			$isgostr = '只有认证的服务号或者服务窗才可以使用！';
		} else {
			$isgostr = '只有认证的服务号才可以使用！';
		}
		$this->isgostr = $isgostr;
		$this->assign('isgostr', $this->isgostr);
		if (intval($this->wxuser['winxintype']) != 3 && $this->wxuser['fuwuappid'] == '') {
			$this->error($isgostr);
			die;
		}
		$where = array('token' => $this->token);
		$this->thisWxUser = M('Wxuser')->where($where)->find();
	}
	public function index()
	{
		if (IS_POST) {
			if (intval($this->wxuser['winxintype']) == 3 || $this->wxuser['fuwuappid'] != '') {
				$this->all_insert('Recognition');
			} else {
				$this->error('只有认证服务号才能使用此项功能');
				die;
			}
		} else {
			$db = D('Recognition');
			$where = array('token' => session('token'));
			$where['_string'] = 'type="" OR type is NULL';
			$count = $db->where($where)->count();
			$page = new Page($count, 25);
			$wechat_group_db = M('Wechat_group');
			$list = $db->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
			foreach ($list as $key => $value) {
				$list[$key]['group'] = $wechat_group_db->where(array('token' => $this->token, 'wechatgroupid' => $value['groupid']))->getField('name');
			}
			$groups = $wechat_group_db->where(array('token' => $this->token))->order('id ASC')->select();
			$this->assign('groups', $groups);
			$this->assign('page', $page->show());
			$this->assign('list', $list);
			if (ALI_FUWU_GROUP) {
				$fuwu = 'yes';
			} else {
				$fuwu = 'no';
			}
			$this->assign('fuwu', $fuwu);
			$this->display();
		}
	}
	public function showqr()
	{
		$qrurl = urldecode($_GET['qrurl']);
		$this->show('<img src=\'' . $qrurl . '\' />');
	}
	public function get_code()
	{
		if (intval($this->wxuser['winxintype']) != 3) {
			$this->error('只有认证的服务号才可以使用！');
			die;
		}
		$where = array('id' => $this->_get('id', 'intval'), 'token' => session('token'));
		$GetDb = M('Recognition');
		$recognition = $GetDb->where($where)->field('id')->find();
		if ($recognition == false) {
			$this->error('非法操作');
		}
		$wxAccount = new WxAccountModel();
		$result = $wxAccount->localToWeixin($this->token)->createQrcode(WxAccountModel::QR_LIMIT_STR_SCENE, '', '', $recognition['id']);
		if (!$result) {
			$this->error('渠道二维码创建失败！' . $wxAccount->getError());
		}
		$update['code_url'] = $result['ticket'];
		$num = $GetDb->where(array('id' => $recognition['id']))->save($update);
		if (!$num) {
			$this->error('获取失败');
		}
		$this->success('获取成功');
	}
	public function fuwu_code()
	{
		if (intval($this->wxuser['winxintype']) != 3 && $this->wxuser['fuwuappid'] == '') {
			$this->error($this->isgostr);
			die;
		}
		require './vifnn/Lib/ORG/Fuwu/HttpRequst.php';
		require './vifnn/Lib/ORG/Fuwu/aop/AopClient.php';
		require './vifnn/Lib/ORG/Fuwu/AlipaySign.php';
		$sceneId = (int) $_GET['id'];
		$biz_content = '{"codeInfo": {"scene": {"sceneId": "' . $sceneId . '"}},"codeType": "PERM","expireSecond": "","showLogo": "N"}';
		$app_id = M('Wxuser')->where(array('token' => $this->token))->getField('fuwuappid');
		$url = 'https://openapi.alipay.com/gateway.do';
		$data = array('app_id' => $app_id, 'method' => 'alipay.mobile.public.qrcode.create', 'charset' => 'UTF-8', 'sign_type' => 'RSA', 'timestamp' => date('Y-m-d H:i:s', time()), 'biz_content' => $biz_content, 'version' => '1.0');
		require './vifnn/Lib/ORG/Fuwu/config.php';
		$AlipaySign = new AlipaySign();
		$data['sign'] = $AlipaySign->rsa_sign($this->buildQuery($data), $config['merchant_private_key_file']);
		$re = new HttpRequest();
		$result = $re->sendPostRequst($url, $data);
		$return = json_decode(iconv('GBK', 'UTF-8', $result), true);
		if ($return['alipay_mobile_public_qrcode_create_response']['code'] == 200) {
			$GetDb = M('Recognition');
			$where_GetDb['id'] = $sceneId;
			$save_GetDb['fuwu_code_url'] = $return['alipay_mobile_public_qrcode_create_response']['code_img'];
			$update_GetDb = $GetDb->where($where_GetDb)->save($save_GetDb);
			if ($update_GetDb != false) {
				$this->success('获取成功');
			} else {
				$this->error('操作失败');
			}
		} else {
			$this->error('appid不正确');
		}
	}
	public function buildQuery($query)
	{
		if (!$query) {
			return NULL;
		}
		ksort($query);
		$params = array();
		foreach ($query as $key => $value) {
			$params[] = $key . '=' . $value;
		}
		$data = implode('&', $params);
		return $data;
	}
	public function get_Wxticket()
	{
		$rid = $this->_get('rid') ? $this->_get('rid', 'intval') : 0;
		$tid = $this->_get('tid') ? $this->_get('tid', 'intval') : 0;
		$db_dish_reply = M('Dish_reply');
		$tmp = $db_dish_reply->where(array('id' => $rid, 'token' => session('token')))->find();
		$reg_id = $tmp['reg_id'];
		$recognitionModel = D('Recognition');
		$recRes = $recognitionModel->createQrcode('keyword', '', $tmp['keyword'], 0, $this->token);
		if (empty($recRes)) {
			$this->error('操作失败');
		}
		if ($reg_id != $recRes['id']) {
			$reg_id = $recRes['id'];
			$num = $db_dish_reply->where(array('id' => $tmp['id']))->save(array('reg_id' => $reg_id));
			if (!$num) {
				$this->error('操作失败');
			}
		}
		$url = 0 < $tid ? U('Repast/tableEwm', array('token' => $this->token, 'tid' => $tid)) : U('Repast/company', array('token' => $this->token));
		$this->success('获取成功', $url);
	}
	public function del()
	{
		$data = D('Recognition');
		$where['id'] = $this->_get('id', 'intval');
		if ($where['id'] == false) {
			$this->error('非法操作');
		}
		$where['token'] = $this->token;
		$back = $data->where($where)->delete();
		if ($back == false) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
	public function status()
	{
		$data = D('Recognition');
		$where['id'] = $this->_get('id', 'intval');
		if ($where['id'] == false) {
			$this->error('非法操作');
		}
		$where['token'] = session('token');
		$type = $this->_get('type', 'intval');
		if ($type == 0) {
			$back = $data->where($where)->setInc('status');
		} else {
			$back = $data->where($where)->setDec('status');
		}
		if ($back == false) {
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
}
?>
