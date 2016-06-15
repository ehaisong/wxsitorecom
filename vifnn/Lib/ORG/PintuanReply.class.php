<?php

class PintuanReply
{
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;

	public function __construct($token, $wechat_id, $data, $siteUrl)
	{
		$this->item = M('pintuan')->where(array('id' => $data['pid']))->find();
		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
	}

	public function index()
	{
		if (strpos($this->item['facepic'], 'http') === false) {
			$this->item['facepic'] = C('site_url') . $this->item['facepic'];
		}

		$thisItem = $this->item;
		return array(
	array(
		array($thisItem['title'], $thisItem['info'], $thisItem['facepic'], $this->siteUrl . U('Wap/Pintuan/index', array('token' => $this->token, 'wecha_id' => $this->wechat_id, 'tid' => $thisItem['id'])))
		),
	'news'
	);
	}
}

echo "\r\n";

?>
