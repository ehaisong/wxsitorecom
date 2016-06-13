<?php

class WxMaterialModel extends WeixinModel
{
	public function uploadImage($path)
	{
		if (!file_exists($path)) {
			$this->error = '文件不存在';
			return false;
		}

		$info = getimagesize($path);
		$data['content-type'] = $info['mime'];
		$data['filelength'] = filesize($path);
		$data['filename'] = ltrim($path, '.');
		$result = $this->setParams(array('type' => 'image'))->post('media_upload', $this->getMediaData($data))->getJson();
		return $result;
	}

	private function getMediaData($file_info)
	{
		$real_path = $_SERVER['DOCUMENT_ROOT'] . $file_info['filename'];
		$data = array('media' => '@' . $real_path, 'form-data' => $file_info);
		return $data;
	}
}

?>
