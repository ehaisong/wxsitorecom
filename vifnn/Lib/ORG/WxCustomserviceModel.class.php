<?php

class WxCustomserviceModel extends WeixinModel
{
	private $openId = '';

	public function __construct(array $config = array())
	{
		parent::__construct($config);
	}

	public function setUser($openId)
	{
		$this->openId = $openId;
		return $this;
	}

	public function sendImageMsg($img)
	{
		$img = str_replace(C('site_url'), '.', $img);
		$material = new WxMaterialModel();
		$result = $material->setToken($this->accessToken)->uploadImage($img);

		if (!$result) {
			$this->error = '上传素材错误:' . $material->getError();
			return false;
		}

		$data['touser'] = $this->openId;
		$data['msgtype'] = 'image';
		$data['image'] = array('media_id' => $result['media_id']);
		return $this->post('custom_send', $this->jsonEncode($data))->getJson();
	}

	public function sendTextMsg($content)
	{
		$data['touser'] = $this->openId;
		$data['msgtype'] = 'text';
		$data['text'] = array('content' => $content);
		return $this->post('custom_send', $this->jsonEncode($data))->getJson();
	}
}

?>
