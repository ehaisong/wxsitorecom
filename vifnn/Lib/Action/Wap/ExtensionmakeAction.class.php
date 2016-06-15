<?php

class ExtensionmakeAction extends Action
{
	private $actModel;
	private $token;
	private $wechat_id;
	private $userInfo;
	private $wxUser;

	public function _initialize()
	{
		ignore_user_abort(true);
		$this->actModel = new ExtensionActivityModel();
		$this->token = I('get.token');
		$this->wechat_id = I('get.wechat_id');
		$this->userInfo = M('userinfo')->where(array('wecha_id' => $this->wechat_id, 'token' => $this->token))->find();
		$this->wxUser = M('wxuser')->where(array('token' => $this->token))->field('id,headerpic,wxname,winxintype')->find();
		session('token', $this->token);
		Log::write('Extension:生成图片：token:' . $this->token . ',wecha_id:' . $this->wechat_id, Log::INFO);
	}

	public function index()
	{
		$customService = new WxCustomserviceModel();

		if (empty($this->userInfo)) {
			return $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendTextMsg('您的用户信息不完善，请关注我们的公众号');
		}

		$id = I('get.id');

		if (empty($id)) {
			return $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendTextMsg('推广海报不存在或已关闭');
		}

		$where['id'] = $id;
		$where['token'] = $this->token;
		$where['status'] = '1';
		$result = $this->actModel->where($where)->find();

		if (empty($result)) {
			return $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendTextMsg('推广海报不存在或已关闭');
		}

		$time = time();

		if ($time < $result['start_time']) {
		}

		if ($result['end_time'] <= $time) {
		}

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
				return $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendTextMsg('生成推广海报失败');
			}
		}

		$savename = sha1($this->wechat_id . '_' . $this->token . '_' . $id . '_reply') . '.png';
		$root = C('EXTENSION_PATH') . '/banner/' . $result['id'] . '/';
		if (!file_exists($root . $savename) || (defined('APP_DEBUG') && APP_DEBUG)) {
			$bannerConfig = object_to_array(json_decode(htmlspecialchars_decode($result['banner_config'])));
			$posterModel = new PosterModel();
			$uid = $this->userInfo['id'];
			$recognitionModel = D('Recognition');
			$qrcodeResult = $recognitionModel->createQrcode('extension', $id, array('uid' => $uid, 'id' => $id), 0, $this->token);

			if (!$qrcodeResult) {
				Log::write('Extension:创建渠道二维码失败:' . $recognitionModel->getError());
				return $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendTextMsg('生成推广海报失败');
			}

			$createRes = $posterModel->setVars(array('username' => short($this->userInfo['wechaname'], $result['nickname_len'] ? $result['nickname_len'] : 9), 'sex' => $this->userInfo['sex'] == '0' ? '保密' : ($this->userInfo['sex'] == '1' ? '男' : '女'), 'company' => $result['company'], 'start_time' => date('Y-m-d', $result['start_time']), 'end_time' => date('Y-m-d', $result['end_time']), 'desc' => $result['descr'], 'descr' => $result['descr'], 'name' => $result['name']))->setConfig(array('avatar' => $this->userInfo['portrait'], 'qrcode_url' => $qrcodeResult['url'], 'logo' => $this->wxUser['headerpic']))->create($bannerConfig);

			if (!$createRes) {
				Log::write('Extension:生成推广海报失败,wechat_id:' . $this->wechat_id . ',token:' . $this->token . ',posterModel error:' . $posterModel->getError());
				Log::write('Extension:海报配置:' . json_encode($bannerConfig, JSON_UNESCAPED_UNICODE), Log::INFO);
				return $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendTextMsg('生成推广海报失败');
			}

			$posterModel->save($root . $savename);
		}

		$cus = $customService->localToWeixin($this->token)->setUser($this->wechat_id)->sendImageMsg($root . $savename);
		Log::write('Extension:发送客服图片消息:' . json_encode($cus, JSON_UNESCAPED_UNICODE) . ',wechat_id：' . $this->wechat_id . ',错误消息:' . $customService->getError());
		exit();
	}
}

?>
