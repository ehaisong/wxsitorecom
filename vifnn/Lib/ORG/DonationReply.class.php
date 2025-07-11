<?php

class DonationReply
{
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;

	public function __construct($token, $wechat_id, $data, $siteUrl)
	{
		$this->item = M('Donation')->where(array('id' => $data['pid']))->find();
		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
	}

	public function index()
	{
		$thisItem = $this->item;

		if (strpos($thisItem['reply_pic'], 'http') === false) {
			$thisItem['reply_pic'] = $this->siteUrl . $thisItem['reply_pic'];
		}

		return array(
	array(
		array($thisItem['reply_title'], $thisItem['reply_content'], $thisItem['reply_pic'], $this->siteUrl . U('Wap/Donation/index', array('id' => $thisItem['id'], 'token' => $this->token, 'wecha_id' => $this->wechat_id)))
		),
	'news'
	);
	}
}


?>
