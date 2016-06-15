<?php

class ExtensionReply
{
	private $userInfo;
	private $token;
	private $wechat_id;
	private $data;
	private $siteUrl;
	private $actModel;
	private $wxUser;

	public function __construct($token, $wechat_id, $data, $siteUrl)
	{
		$this->actModel = new ExtensionActivityModel();
		$this->token = $token;
		$this->wechat_id = $wechat_id;
		$this->data = $data;
		$this->siteUrl = $siteUrl;
		$this->userInfo = M('userinfo')->where(array('wecha_id' => $this->wechat_id, 'token' => $this->token))->find();
		$this->wxUser = M('wxuser')->where(array('token' => $token))->field('id,headerpic,wxname,winxintype')->find();
	}

	public function index()
	{
		if (empty($this->userInfo)) {
			return array('您的用户信息不完善，请关注我们的公众号', 'text');
		}

		$id = $this->data['pid'];

		if (empty($id)) {
			return array('推广海报不存在或已关闭', 'text');
		}

		$where['id'] = $id;
		$where['token'] = $this->token;
		$where['status'] = '1';
		$result = $this->actModel->where($where)->find();

		if (empty($result)) {
			return array('推广海报不存在或已关闭', 'text');
		}

		$time = time();

		if ($time < $result['start_time']) {
		}

		if ($result['end_time'] <= $time) {
		}

		if ($this->wxUser['winxintype'] == '3') {
			$url = rtrim($this->siteUrl, '/') . U('Wap/Extensionmake/index', array('wechat_id' => $this->wechat_id, 'id' => $id, 'token' => $this->token));
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 1);
			$data = curl_exec($curl);
			curl_close($curl);
			return array('请稍等,推广海报正在生成中...', 'text');
		}
		else {
			return array('该公众号暂不支持推广海报功能', 'text');
		}
	}

	private function immediately($id, $result)
	{
		$extensionUser = D('ExtensionUser');
		$data['aid'] = $id;
		$data['token'] = $this->token;
		$data['wecha_id'] = $this->wechat_id;
		$data['uid'] = $this->userInfo['id'];
		$num = $extensionUser->where($data)->count();

		if ($num <= 0) {
			$data['create_time'] = time();
			$euid = $extensionUser->add($data);

			if (!$euid) {
				return array('生成推广海报失败', 'text');
			}
		}

		$savename = sha1($this->wechat_id . '_' . $this->token . '_' . $id . '_reply') . '.png';
		$root = C('EXTENSION_PATH') . '/banner/' . $result['id'] . '/';
		if (!file_exists($root . $savename) || (defined('APP_DEBUG') && APP_DEBUG)) {
			$bannerConfig = object_to_array(json_decode(htmlspecialchars_decode($result['banner_config'])));
			$posterModel = new PosterModel();
			$wxAccount = new WxAccountModel();
			$uid = $this->userInfo['id'];
			$recognitionModel = D('Recognition');
			$recognitionData['token'] = $this->token;
			$recognitionData['title'] = '推广海报';
			$recognitionData['attention_num'] = '0';
			$recognitionData['keyword'] = $result['url'] ? $result['url'] : '';
			$recognitionData['code_url'] = '';
			$recognitionData['scene_id'] = '0';
			$recognitionData['status'] = '0';
			$recognitionData['groupid'] = '0';
			$recognitionData['fuwu_code_url'] = '';
			$recognitionData['config'] = json_encode(array('uid' => $uid, 'id' => $id));
			$recognitionData['type'] = 'extension';
			$recognitionData['type_id'] = $id;
			$rid = $recognitionModel->add($recognitionData);

			if (!$rid) {
				return array('生成推广海报失败', 'text');
			}

			$qrcodeResult = $wxAccount->localToWeixin($this->token)->createQrcode(WxAccountModel::QR_LIMIT_SCENE, NULL, $rid);

			if (!$qrcodeResult) {
				Log::write('Extension:创建渠道二维码失败:' . $wxAccount->getError());
				return array('生成推广海报失败', 'text');
			}

			$createRes = $posterModel->setVars(array('username' => short($this->userInfo['wechaname'], 5), 'sex' => $this->userInfo['sex'] == '0' ? '保密' : ($this->userInfo['sex'] == '1' ? '男' : '女'), 'company' => $result['company'], 'start_time' => date('Y-m-d', $result['start_time']), 'end_time' => date('Y-m-d', $result['end_time']), 'desc' => $result['descr'], 'descr' => $result['descr'], 'name' => $result['name']))->setConfig(array('avatar' => $this->userInfo['portrait'], 'qrcode_url' => $qrcodeResult['url'], 'logo' => $this->wxUser['headerpic']))->create($bannerConfig);

			if (!$createRes) {
				Log::write('Extension:生成推广海报失败,wechat_id:' . $this->wechat_id . ',token:' . $this->token . ',posterModel error:' . $posterModel->getError());
				Log::write('Extension:海报配置:' . json_encode($bannerConfig), Log::INFO);
				return array('生成推广海报失败', 'text');
			}

			$posterModel->save($root . $savename);
		}

		return array($root . $savename, 'image');
	}
}


?>
