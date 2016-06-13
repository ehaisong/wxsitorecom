<?php

class WxAccountModel extends WeixinModel
{
	const QR_SCENE = 'QR_SCENE';
	const QR_LIMIT_SCENE = 'QR_LIMIT_SCENE';
	const QR_LIMIT_STR_SCENE = 'QR_LIMIT_STR_SCENE';

	public function createQrcode($action_name, $expire_seconds = 30, $scene_id = '', $scene_str = '')
	{
		$data = array('action_name' => $action_name);
		$scene = array();

		if ($action_name == self::QR_SCENE) {
			$data['expire_seconds'] = $expire_seconds;
		}

		if ($action_name == self::QR_LIMIT_STR_SCENE) {
			if (!empty($scene_str)) {
				$scene['scene_str'] = $scene_str;
			}
		}
		else if (!empty($scene_id)) {
			$scene['scene_id'] = $scene_id;
		}

		if (!empty($scene)) {
			$data['action_info'] = array('scene' => $scene);
		}

		return $this->post('create_qrcode', $this->jsonEncode($data))->getJson();
	}

	public function saveQrcodeImg($savePath, $action_name, $expire_seconds = 30, $scene_id = '', $scene_str = '')
	{
		$ticketData = $this->createQrcode($action_name, $expire_seconds, $scene_id, $scene_str);

		if (!$ticketData) {
			return false;
		}

		$data = $this->get('show_qrcode', array('ticket' => $ticketData['ticket']))->getResult();

		if (empty($savePath)) {
			return $data;
		}

		$info = getimagesizefromstring($data);

		if (!file_exists(dirname($savePath))) {
			mkdir($savePath, 511, true);
		}

		list($mime, $type) = explode('/', $info['mime']);
		$path = rtrim($savePath, '/') . '/' . uniqid() . '.' . $type;

		if (!file_put_contents($path, $data)) {
			$this->error = $savePath . '不可写';
			return false;
		}

		return $path;
	}
}

?>
