<?php
class LivingCircleReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('livingcircle')->where(array('vifnn_id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['wxtitle'],$thisItem['wxinfo'],$thisItem['wxpic'],$this->siteUrl.U('Wap/LivingCircle/index',array('token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}
?>

