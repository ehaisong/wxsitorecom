<?php
class CommonScene extends BaseScene
{
	public function extension()
	{
		$config = $this->data;
		$extensionModel = new ExtensionActivityModel();
		$actInfo = $extensionModel->where(array('id' => $config['id'], 'token' => $this->token, 'status' => '1'))->find();
		Log::write('Extension:扫描事件,wecha_id:' . $this->wecha_id . ',id:' . $config['id'] . ',token:' . $this->token . ',status:1', Log::INFO);

		if (!empty($actInfo)) {
			$extensionRelation = new ExtensionRelationModel();
			$extensionRelation->follow($config['uid'], $actInfo, $this->token, $this->wecha_id);

			if (!empty($actInfo['url'])) {
				return $this->parseKeyword($actInfo['url'] ? $actInfo['url'] : '首页');
			}
		}
	}

	public function replyNews()
	{
		$title = $this->data['title'];
		$intro = $this->data['intro'];
		$reply_pic = img_url($this->data['reply_pic'], '', '', $this->siteUrl);
		$url = (strpos($this->data['url'], $this->siteUrl) !== 0 ? $this->siteUrl . $this->data['url'] : $this->data['url']);
		$obj[0] = array(
	array($title, $intro, $reply_pic, $url)
	);
		$obj[1] = 'news';
		return $obj;
	}

	public function keyword()
	{
		return $this->parseKeyword($this->data);
	}
}

?>
