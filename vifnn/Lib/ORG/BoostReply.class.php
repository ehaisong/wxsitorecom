<?php


class BoostReply
{
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token, $wechat_id, $data, $siteUrl)
	{
		$this->item = M('Boosts')->where(array('id' => $data['pid']))->find();
		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
	}
	public function index()
	{
		$thisItem = $this->item;
		$boost = new boost();
		$link = $boost->getLink($thisItem, $this->wechat_id, $this->siteUrl);
		return array(array(array($thisItem['title'], $thisItem['intro'], $thisItem['picurl'], $link)), 'news');
	}
}
echo "\r\n";