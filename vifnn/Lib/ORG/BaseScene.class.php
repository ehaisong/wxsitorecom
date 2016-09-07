<?php


class BaseScene
{
	protected $token;
	protected $wecha_id;
	protected $fans;
	protected $wxuser;
	protected $siteUrl;
	protected $data;
	protected $param;
	protected $eventData;
	protected $fun;
	public function __construct($token, $wecha_id, $thisWxUser, $siteUrl, $eventData, $param, $fun)
	{
		$this->token = $token;
		$this->wecha_id = $wecha_id;
		$this->wxuser = $thisWxUser;
		$this->siteUrl = $siteUrl;
		$this->eventData = $eventData;
		$this->fans = M('Userinfo')->where(array('token' => $token, 'wecha_id' => $wecha_id))->find();
		$this->data = json_decode($param['data'], true);
		$this->param = $param;
		if (empty($fun)) {
			$open = M('Token_open')->where(array('token' => $this->token))->find();
			$this->fun = $open['queryname'];
		} else {
			$this->fun = $fun;
		}
	}
	public function parseKeyword($keyword)
	{
		$keywordModel = new KeywordModel($this->token, $this->eventData, $this->fun, $this->sitUrl);
		return $keywordModel->handler($keyword);
	}
}


?>
