<?php

class MASSSENDJOBFINISHEventReply{
	public $token;
	public $FromUserName;
	public $data;
	public $siteUrl;

	public function __construct($token,$FromUserName,$data,$siteUrl){
		$this->token 	= $token;
		$this->FromUserName = $FromUserName;
		$this->data 	= $data;
		$this->siteUrl 	= $siteUrl;
	}

	public function index(){
		if($this->data['Status'] == 'send success'){
			return M('Send_message')->where(array('msg_id'=>$this->data['MsgID']))->save(array('reachcount'=>$this->data['SentCount'],'errorcount'=>$this->data['ErrorCount'],'status'=>2));
		}else{
			return M('Send_message')->where(array('msg_id'=>$this->data['MsgID']))->save(array('status'=>3));
		}
		
	}
}


?>