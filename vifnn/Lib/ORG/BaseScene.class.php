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

	public function __construct($token, $wecha_id, $thisWxUser, $siteUrl, $eventData, $param)
	{
		$this->token = $token;
		$this->wecha_id = $wecha_id;
		$this->wxuser = $thisWxUser;
		$this->siteUrl = $siteUrl;
		$this->eventData = $eventData;
		$this->fans = M('Userinfo')->where(array('token' => $token, 'wecha_id' => $wecha_id))->find();
		$this->data = json_decode($param['data'], true);
		$this->param = $param;
	}

	public function parseKeyword($keyword)
	{
		return A('Home/Weixin')->api('keyword', $keyword, $this->token, $this->eventData, $this->siteUrl);
	}
}


?>
