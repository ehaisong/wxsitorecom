<?php 
class HelpingAction extends WapAction{
	public $helping;
	public $isamap;
	public $help_rank;
	public $share_key_openid = "";
	public function _initialize(){
		parent::_initialize();
		$id = $this->_get('id','intval');
		$this->checkTongji($this->token, "helping", $id);
		$this->assign($this->token, "helping", $id);
		$helping = S($id.'helping'.$this->token);
		if($helping == ''){
			$helping = M("Helping")->where(array("id" => $id))->find();
			if($helping == ''){
				$this->error('活动不存在');
			}
			else if ($helping["is_open"] == 2) {
				$this->error("活动已关闭");
			}
			else{
				S($id.'helping'.$this->token,$helping);
			}
		}
		$this->helping = $helping;
	}

	public function index(){
		$id = $this->_get('id','intval');
		if($this->helping['is_newtp'] == 1){
			$news_list = S($id.'helping'.$this->token.'news');
			if($news_list == ''){
				$news_list = M('helping_news')->where(array('token'=>$this->token,'pid'=>$id))->order('id')->select();
				foreach($news_list as $nk=>$nv){
					$news_list[$nk]['url'] = $this->getLink($nv['url']);
				}
				S($id.'helping'.$this->token.'news',$news_list);
			}
			$prize_list = S($id.'helping'.$this->token.'prize');
			if($prize_list == ''){
				$prize_list = M('helping_prize')->where(array('token'=>$this->token,'pid'=>$id))->order('id')->select();
				S($id.'helping'.$this->token.'prize',$prize_list);
			}
			$this->assign('news_list',$news_list);
			$this->assign('prize_list',$prize_list);
		}
		//D('Userinfo')->convertFake(D('HelpingRecord'), array('token'=>$this->token, 'fakeopenid'=>$this->fakeopenid, 'wecha_id'=>$this->wecha_id));
		//D('Userinfo')->convertFake(D('HelpingUser'), array('token'=>$this->token, 'fakeopenid'=>$this->fakeopenid, 'wecha_id'=>$this->wecha_id));
		if($this->helping['rank_num'] == '' || $this->helping['rank_num'] == 0){
			if($this->helping['is_newtp'] == 1){
				$this->helping['rank_num'] = 10;
			}else{
				$this->helping['rank_num'] = 30;
			}
		}
		$reply_pic = explode("http",$this->helping['reply_pic']);
		if(count($reply_pic) <= 1){
			$this->helping["reply_pic"] = $this->siteUrl . $this->helping["reply_pic"];
		}
		$this->assign('info',$this->helping);
		$fansinfo = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
		$id 		= $this->_get('id','intval');
		$share_key 	= $this->_get('share_key','trim');
		$now 		= time();
		
		M("helping")->where(array("id" => $id))->setInc("pv", 1);
		
		if($_GET['tel'] != '' && $this->wecha_id != ''){
			$userinfo_tel = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->save(array('tel'=>$_GET['tel'],'isverify'=>1));
			$helping_user_tel = M('helping_user')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'pid'=>$id))->save(array('tel'=>$_GET['tel']));
			$fansinfo['tel'] = $_GET['tel'];
			S('fans_'.$this->token.'_'.$this->wecha_id,$fansinfo);
		}

		$myhelp     = M('helping_user')->where(array('pid'=>$id,'token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
		if($myhelp['is_join2'] == 1){
			$backtext = "返回我的";
		}else{
			$backtext = "我也参与";
		}
		$this->assign('backtext',$backtext);
		if($myhelp['add_time'] == '' || $myhelp['add_time'] == 0){
			M('helping_user')->where(array('pid'=>$id,'token'=>$this->token,'wecha_id'=>$this->wecha_id))->save(array('add_time'=>time()));
		}
		$autoClick='0';$autoJoin='1';
		if($share_key != ''){
			$is_my = M('helping_user')->where(array('token'=>$this->token,'pid'=>$id,'wecha_id'=>$this->wecha_id,'share_key'=>$share_key))->find();
			if($is_my != ''){
				$this->redirect('Helping/index',array('token'=>$this->token,'id'=>$id));
			}
			//是否已经助力过了 @VIFNN
			$is_share 	= M('Helping_record')->where(array('pid'=>$id,'wecha_id'=>$this->wecha_id,'share_key'=>$share_key))->count();
			$this->assign('is_share',$is_share>0?'1':'0');
			if($this->helping['is_help']==1||($this->helping['is_help']==2&&$this->isSubscribe()))
			{
				$autoClick='1';
			}
			if($this->helping['is_help']==2&&!$this->isSubscribe())
			{
				$this->memberNotice('',1,'','',true);
			}
		}else{
			if($this->helping['is_attention'] == 2 && !$this->isSubscribe())
			{
				$noticeData['title']=$this->helping['title'];
				$noticeData['reply_pic']=$this->helping['reply_pic'];
				$noticeData['intro']=$this->helping['intro'];
				$noticeData['url']=__SELF__;
				$autoJoin='0';
				$this->memberNotice('',1,'','','replyNews',$noticeData);
			}
			else if(($this->helping['is_reg'] == 1 || $this->helping['is_sms'] == 1) && empty($fansinfo['tel']))
			{
				if($this->helping['is_sms'] == 0 && $this->helping['is_reg'] == 1){
					$this->memberNotice();
				}elseif($this->helping['is_sms'] == 1 && $this->helping['is_newtp'] == 1){
					$this->assign('sms',1);
					$this->assign('memberNotice','<div style="display:none"></div>');
				}
				$autoJoin='0';
			}else if($this->helping['is_sms'] == 1 && empty($fansinfo['tel']) && $fansinfo['isverify'] != 1 && $this->helping['is_newtp'] == 1){
				$this->assign('sms',1);
				$this->assign('memberNotice','<div style="display:none"></div>');
				$autoJoin='0';
			}
			if($this->helping['is_help'] == 1 && $share_key != ""){
				$this->assign('memberNotice','');
			}
			if($autoJoin=='1'&&(($myhelp['is_join2'] == 0 && $myhelp != '' && $myhelp['help_count'] > 1) || (intval($_GET['is_join2']) == 1 && $myhelp['is_join2'] == 0))){
				$join = M("helping_user")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $id))->save(array("is_join2" => 1, "add_time" => time(), "help_count" => $myhelp["help_count"] + 1));
				$this->redirect('Helping/index',array('token'=>$this->token,'id'=>$id));
			}
		}
		
		if($this->helping['start']>$now){
			$is_over 	= 1;
		}else if($this->helping['end']<$now){
			$is_over 	= 2;
		}else{
			$is_over 	= 0;
		}

		if($fansinfo){
			
			$us 		= M('Helping_user')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'pid'=>$this->helping['id']))->find();
			
			if(!empty($this->wecha_id) && empty($us)) {
				$share_key2 = $this->getKey($this->wecha_id);
				$data = array("pid" => $this->helping["id"], "wecha_id" => $this->wecha_id, "token" => $this->token, "add_time" => 0, "help_count" => 0, "share_key" => $share_key2, "is_join2" => 0);
				$uid = M('Helping_user')->add($data);
			}
			
			if($share_key!=''){
				$user 	= M('Helping_user')->where(array('token'=>$this->token,'share_key'=>$share_key,'pid'=>$this->helping['id']))->find();
				$this->share_key_openid = $user["wecha_id"];
			}
			else {
				$user = M("Helping_user")->where(array("token" => $this->token, "wecha_id" => $fansinfo["wecha_id"], "pid" => $this->helping["id"]))->find();
			}

			$rank = M("Helping_user")->where(array(
	"token"      => $this->token,
	"pid"        => $this->helping["id"],
	"is_join2"   => 1,
	"help_count" => array("gt", $myhelp["help_count"])
	))->count();
			$this->help_rank = 0 < $rank ? $rank + 1 : "";
		}

		$count = M("Helping_user")->where(array(
	"token"      => $this->token,
	"pid"        => $this->helping["id"],
	"is_join2"   => 1,
	"help_count" => array("gt", 0)
	))->count();
		$this->assign("autoClick", $autoClick);
		$userinfodata = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $user["wecha_id"]))->getField("wecha_id,wechaname,portrait");
		$user["wechaname"] = $userinfodata[$user["wecha_id"]]["wechaname"];
		$user["portrait"] = $userinfodata[$user["wecha_id"]]["portrait"];
		$this->assign("share_key", $share_key);
		$this->assign("rank", $this->get_rank($this->helping));
		$user["help_rank"] = (0 < $this->help_rank ? $this->help_rank : "");
		$this->assign("user", $user);
		M("helping_user")->where(array("token" => $this->token, "pid" => $id, "wecha_id" => $user["wecha_id"]))->setInc("pv", 1);
		$this->assign("fans", $fansinfo);
		$this->assign("count", $count);
		$this->assign("is_over", $is_over);
		$this->assign("my", $fansinfo);



		if($this->helping['is_newtp'] == 1){
			if($_GET['helps'] == 1){
				$helps_list = M("helping_record")->where(array("token" => $this->token, "pid" => $id, "share_key" => $user["share_key"]))->order("addtime desc")->limit(99)->select();
				$helps_count = M("helping_record")->where(array("token" => $this->token, "pid" => $id, "share_key" => $user["share_key"]))->count();

				foreach ($helps_list as $hk => $hv ) {
					$wechaname = "";
					$portrait = "";

					if (!empty($hv["wecha_id"])) {
						$tmparr = $this->getwxinfobywecha_id($hv["wecha_id"]);
						$wechaname = $tmparr["wechaname"];
						$portrait = $tmparr["portrait"];
					}
					$helps_list[$hk]["wechaname"] = $wechaname;
					$helps_list[$hk]["portrait"] = $portrait;
				}
				$this->assign('helps_count',$helps_count);
				$this->assign('helps_list',$helps_list);
				$this->display('helps');
			}else{
				$this->display('index_new');
			}
		}else{
			$this->display();
		}
	}
	public function getwxinfobywecha_id($wecha_id)
	{
		$wxnamearr = S("wecha_11id_" . $this->token);
		$wxnamearr = json_decode($wxnamearr, 1);
		$tt = count($wxnamearr);
		if (!empty($wxnamearr) && ($tt < 10000)) {
			if (isset($wxnamearr[$wecha_id])) {
				return $wxnamearr[$wecha_id];
			}
		}

		$userinfoDb = M("userinfo");
		$wxuinfo = $userinfoDb->where(array("token" => $this->token, "wecha_id" => $wecha_id))->field("wecha_id,wechaname,portrait")->find();

		if (!empty($wxuinfo)) {
			if ($tt < 10000) {
				$wxnamearr[$wxuinfo["wecha_id"]] = $wxuinfo;
			}
			else {
				$wkey = $wxuinfo["wecha_id"];
				$wxnamearr = array($wkey => $wxuinfo);
			}

			S("wecha_11id_" . $this->token, json_encode($wxnamearr));
			return $wxuinfo;
		}

		return array("wechaname" => "", "portrait" => "");
	}
	public function sms(){
		if($_POST['tel'] != ''){
			$is_tel = M('userinfo')->where(array('token'=>$_POST['token'],'tel'=>$_POST['tel'],'isverify'=>1))->find();
			if($is_tel == ''){
				$params = array();
				$session_sms = session($_POST['wecha_id'].'code'.$_POST['token'].$_POST['id']);
				if($session_sms['time'] > time() && $session_sms['tel'] == $_POST['tel']){
					$code = $session_sms['code'];
				}else{
					session($_POST['wecha_id'].'code'.$_POST['token'].$_POST['id'],null);
					$code = rand(100000,999999);
					$session_sms['tel'] = $_POST['tel'];
					$session_sms['code'] = $code;
					$session_sms['time'] = time()+(60*30);
					session($_POST['wecha_id'].'code'.$_POST['token'].$_POST['id'],$session_sms);
				}
				$params['sms'] = array('token'=>$this->token, 'mobile'=>$_POST['tel'],'content'=>'您的验证码是：'.$code.'。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
				$data['error'] = MessageFactory::method($params,'SmsMessage');
				$this->ajaxReturn($data,'JSON');
			}else{
				$data['error'] = 'tel';
				$this->ajaxReturn($data,'JSON');
			}
		}
	}
	public function smsyz(){
		$session_sms = session($_POST['wecha_id'].'code'.$_POST['token'].$_POST['id']);
		if($_POST['code'] != $session_sms['code']){
			$data['error'] = 1;
		}else if($_POST['tel'] != $session_sms['tel']){
			$data['error'] = 2;
		}else if($session_sms['time'] < time()){
			$data['error'] = 3;
		}else{
			$data['error'] = 0;
		}
		$this->ajaxReturn($data,'JSON');
	}

	public function add_share(){
		$now 		= time();
		$share_key 	= $this->_get('share_key','trim');
		$cookie 	= cookie('helping_share');			
		$cookie_arr = json_decode( json_encode( $cookie),true);
		$share 		= M('Helping_user')->where(array('token'=>$this->token,'share_key'=>$share_key))->find();
		$record = array("token" => $this->token, "pid" => $share["pid"], "share_key" => $share_key, "addtime" => time(), "wecha_id" => $this->wecha_id);

		if(empty($share)) {
			//echo json_encode(array('err'=>2,'info'=>'分享参数错误'));
			exit;
		}

		if($this->helping['start']>$now){
			echo json_encode(array('err'=>3,'info'=>'活动还没开始'));
			exit;
		}
		
		if($this->helping['end']<$now){
			echo json_encode(array('err'=>4,'info'=>'活动已结束'));
			exit;
		}

		if($share['wecha_id'] == $this->wecha_id){
			//echo json_encode(array('err'=>5,'info'=>'不能给自己增加助力值'));
			exit;
		}
		if($this->helping['is_help'] == 2 && !$this->isSubscribe()){
			echo json_encode(array('err'=>5,'info'=>'未关注不能给好友助力'));
			exit;
		}

		$is_share 	= M('Helping_record')->where(array('token'=>$this->token,'pid'=>$share['pid'],'wecha_id'=>$this->wecha_id,'share_key'=>$share_key))->count();

		if(in_array($share_key, $cookie_arr[$this->helping['id']]) || $is_share) {
			//echo json_encode(array('err'=>6,'info'=>'请不要重复给好友加助力值'));
			exit;
		}else{
			if(M('Helping_record')->add($record)){
				M('Helping_user')->where(array('token'=>$this->token,'pid'=>$this->helping['id'],'share_key'=>$share_key))->setInc('help_count',1);
				if($share['add_time'] == 0 || $share['add_time'] == ''){
					M('Helping_user')->where(array('token'=>$this->token,'pid'=>$this->helping['id'],'share_key'=>$share_key))->save(array('add_time'=>time()));
				}
				//记录cookie
				//$cookie_arr[$this->helping['id']][] = $share_key;
				if(empty($cookie_arr[$this->helping['id']])){
					$cookie_arr[$this->helping['id']] 	= array();
				}
				array_push($cookie_arr[$this->helping['id']],$share_key);
				cookie('helping_share',$cookie_arr,time()+3600*24*30);
				echo json_encode(array('err'=>0,'info'=>'你的好友成功增加了1点助力值'));
				exit;
			}
		}	

	}
	public function get_rank($params)
	{
		$pmwecha_id = (!empty($this->share_key_openid) ? $this->share_key_openid : $this->wecha_id);
		$where = array(
			"a.token"      => $this->token,
			"a.pid"        => $params["id"],
			"a.is_join2"   => 1,
			"a.help_count" => array("gt", 0)
			);
		$limit = $params['rank_num'];
		$rank = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel,b.portrait')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->order('a.help_count desc,a.share_num desc')->limit($limit)->select();
		//$rank 	= M('Helping_user')->where($where)->order('help_count desc,share_num desc')->limit($limit)->select();
		foreach($rank as $key=>$val){
			//$user_info = M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->find();
			$rank[$key]['username'] 	= $val['wechaname']?$val['wechaname']:'匿名';
			$rank[$key]['tel'] 			= $val['tel']?substr_replace($val['tel'],'****',3,4):'无';
			$rank[$key]['portrait'] 	= $val['portrait'];
			if ($val["wecha_id"] == $pmwecha_id) {
				$this->help_rank = $key + 1;
			}
		}
		return $rank;
	}

	

	//分享key  最长32
	public function getKey($wecha_id)
	{
		$str = md5(time() . mt_rand(1000, 9999) . $wecha_id);
		return $str;
	}


}

?>