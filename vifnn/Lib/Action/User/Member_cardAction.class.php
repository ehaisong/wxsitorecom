<?php
class Member_cardAction extends UserAction{
	public $member_card_set_db;
	public $thisCard;
	public $wxuser;
	public $wxuser_db;
	public $cashierCrad;
	public $iscashierCrad = false;
	public function _initialize() {
		parent::_initialize();
		$this->assign('token',$this->token);
		//
		$this->canUseFunction('huiyuanka');
		//权限
		if ($this->token!=$_GET['token']){
			//$this->error('非法操作');
		}
		$this->wxuser_db=M("Wxuser");
		$this->member_card_set_db = M("Member_card_set");
		$cardByToken = $this->member_card_set_db->where(array("token" => $this->token))->order("id ASC")->find();
		if (empty($cardByToken)) {
			$this->checkCashierCard($this->wxuser);
		}
		$thisWxUser=$this->wxuser_db->where(array('token'=>$this->token))->find();
		$thisUser=$this->user;
		$thisGroup=$this->userGroup;
		$this->wxuser_db->where(array('token'=>$this->token))->save(array('allcardnum'=>$thisGroup['create_card_num']));
		//总数
		//if (!$thisUser['card_num']){
			$cardTotal = M('Member_card_create')->where(array('token'=>$this->token))->count(1);
			
			//$cardTotal=count($allcards);
		
			M('Users')->where(array('id'=>$thisUser['id']))->save(array('card_num'=>$cardTotal));
			M('Wxuser')->where(array('token'=>$this->token))->save(array('yetcardnum'=>$cardTotal));
		//}else {
			//$cardTotal=$thisUser['card_num'];
		//}
		//
		$can_cr_num = $thisWxUser['allcardnum'] - $cardTotal;
		if($can_cr_num > 0){
			$data['cardisok'] = 1;
		}else{
			$data['cardisok'] = 0;
		}
		$this->wxuser_db->where(array('uid'=>session('uid'),'token'=>session('token')))->save($data);
		//
		if($_GET['cardid']){
			$id=intval($_GET['cardid']);
		}else{
			$id=intval($_GET['id']);
		}
		if ($id){
			$this->thisCard=$this->member_card_set_db->where(array('id'=>$id))->find();
			if ($this->thisCard&&$this->thisCard['token']!=$this->token){
				$this->error('非法操作');
			}
			$this->assign('thisCard',$this->thisCard);
		}
		
		//transfer start
		$data=M('Member_card_create');

		if ($cardByToken){
			$data->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_exchange')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_coupon')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_vip')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
			M('Member_card_integral')->where('token=\''.$this->token.'\' AND cardid=0')->save(array('cardid'=>$cardByToken['id']));
		}
		//$thisWxUser['wx_coupons'] 	= '1';
		$this->wxuser 	= $thisWxUser;
		//transfer end
		$type 	= $this->_get('type','intval');
		$this->assign('type',$type?$type:1);
	}

	public function CashierCardSet()
	{
		$iscashiercard = $this->_post("iscashiercard", "intval");
		if (($iscashiercard == 1) || ($iscashiercard == 2)) {
			if ($this->wxuser_db->where(array("id" => $this->wxuser["id"], "token" => $this->token))->save(array("iscashiercard" => $iscashiercard))) {
				$this->success("设置成功！", U("Member_card/index", array("token" => $this->token)));
			}
			else {
				$this->error("设置失败，请重新保存设置！");
			}
		}
	}

	public function checkCashierCard($wxuserinfo)
	{
		if (ACTION_NAME == "CashierCardSet") {
			return true;
		}

		if (empty($wxuserinfo) || ($wxuserinfo["iscashiercard"] == 2)) {
			return true;
		}

		$CmscashierkeyDb = D("Cmscashierkey");
		$cashierCf = $CmscashierkeyDb->getConfig();
		if (empty($cashierCf) || !isset($cashierCf["cashierkey"])) {
			return true;
		}

		$coupontmp = M("Member_card_coupon")->where(array("token" => $this->token))->find();
		if (!empty($coupontmp) && isset($coupontmp["token"])) {
			unset($coupontmp);
			return true;
		}

		if (!empty($wxuserinfo)) {
			switch ($wxuserinfo["iscashiercard"]) {
			case "0":
				$this->display("checkCashierCard");
				exit();
				break;

			case "1":
				$TomemberCardIndex = array("index", "Index", "design", "grade", "setgrade", "privilege", "privilege_add", "gifts", "edit_gifts", "exportmembers", "recharge", "notice", "noticeSet", "exchange", "donate", "donate_set", "jfdhhb", "jfdhhb_set", "jfdhhb_record", "create", "exportCard", "custom", "focus", "focusAdd", "focusEdit");
				$LocmemberList = array("center", "Center", "center_all", "member", "members", "exportCardUseRecord");
				$wxCouponIndex = array("coupons", "Coupons", "coupons_set", "coupons_record");
				$wxCouponConsumeCard = array("consume_record", "Consume_record", "consume_use");
				$cashierAction = "";

				if (in_array(ACTION_NAME, $TomemberCardIndex)) {
					$cashierAction = "c=memberCard&a=index";
				}
				else if (in_array(ACTION_NAME, $LocmemberList)) {
					$cashierAction = "c=memberLoc&a=memberList";
				}
				else if (in_array(ACTION_NAME, $wxCouponIndex)) {
					$cashierAction = "c=wxCoupon&a=index";
				}
				else if (in_array(ACTION_NAME, $wxCouponConsumeCard)) {
					$cashierAction = "c=wxCoupon&a=consumeCard";
				}

				$this->iscashierCrad = true;

				if (ACTION_NAME != "replyInfoSet") {
					A("User/Cashierpf")->index($cashierAction);
				}
				else {
					$this->cashierCrad = $CmscashierkeyDb->getCardList($this->token);
				}

				break;

			case "2":
				return true;
				break;

			default:
				return true;
				break;
			}
		}
	}

	public function index()
	{
		$cards = $this->member_card_set_db->where(array("token" => $this->token))->order("id ASC")->select();

		if ($cards) {
			$card_create_data = M("Member_card_create");
			$i = 0;

			foreach ($cards as $card ) {
				$cards[$i]["usercount"] = $card_create_data->where("cardid=" . intval($card["id"]) . " AND token=\"" . $this->token . "\" AND wecha_id!=\"\"")->count();
				$cards[$i]["cardcount"] = $card_create_data->where("cardid=" . intval($card["id"]) . " AND token=\"" . $this->token . "\"")->count();
				$cards[$i]["grade_name"] = D("Member_card_grade")->findGrade("id=" . intval($card["gradeid"]), "grade_name");
				$i++;
			}
		}

		$exists = D("Member_card_grade")->selectGrade(array("token" => $this->token));

		if (!$exists) {
			$first = array(
				array("grade_name" => "普通会员", "status" => 1),
				array("grade_name" => "银卡会员", "status" => 1),
				array("grade_name" => "金卡会员", "status" => 1),
				array("grade_name" => "铂金卡会员", "status" => 1),
				array("grade_name" => "钻石会员", "status" => 1),
				array("grade_name" => "至尊会员", "status" => 1)
				);
			D("member_card_grade")->allinsertGrade($first, $this->token);
		}

		$this->assign("cards", $cards);
		$this->display();
	}
	public function replyInfoSet(){
		$where_member=array('token'=>$this->token,'infotype'=>'membercard');
		$reply_info_db=M('Reply_info');
		$memberConfig=$reply_info_db->where($where_member)->find();
		$where_unmember=array('token'=>$this->token,'infotype'=>'membercard_nouse');
		$unmemberConfig=$reply_info_db->where($where_unmember)->find();
		if (IS_POST){
			$memberArr=array();
			$nomemberArr=array();
			$memberArr['title']=$this->_post('title');
			$memberArr['info']=$this->_post('info');
			$memberArr['picurl']=$this->_post('picurl');
			$memberArr['token']=$this->token;
			$memberArr['apiurl']=$this->_post('apiurl', "trim");
			$memberArr['infotype']='membercard';
			
			$nomemberArr['title']=$this->_post('title1');
			$nomemberArr['info']=$this->_post('info1');
			$nomemberArr['picurl']=$this->_post('picurl1');
			$nomemberArr['token']=$this->token;
			$nomemberArr['apiurl']=$this->_post('apiurl', "trim");
			$nomemberArr['infotype']='membercard_nouse';
			//
			$where=array('token'=>$this->token);
			//
			if ($memberConfig){
				$reply_info_db->where($where_member)->save($memberArr);
			}else {
				$reply_info_db->add($memberArr);
			}
			//
			if ($unmemberConfig){
				$reply_info_db->where($where_unmember)->save($nomemberArr);
			}else {
				$reply_info_db->add($nomemberArr);
			}
			$this->success('操作成功');
		}else {
			if (!$memberConfig){
				$memberConfig['picurl']=rtrim(C('site_url'),'/').'/tpl/static/images/member.jpg';
				$memberConfig['title']='会员卡,省钱，打折,促销，优先知道,有奖励哦';
				$memberConfig['info']='尊贵vip，是您消费身份的体现,会员卡,省钱，打折,促销，优先知道,有奖励哦';
			}
			if (!$unmemberConfig){
				$unmemberConfig['picurl']=rtrim(C('site_url'),'/').'/tpl/static/images/vip.jpg';
				$unmemberConfig['title']='申请成为会员';
				$unmemberConfig['info']='申请成为会员，享受更多优惠';
			}
			$this->assign("cashierCrad", $this->cashierCrad[0]);
			$this->assign("iscashierCrad", $this->iscashierCrad);
			$this->assign("cashierCradChannel", $this->cashierCrad[0] ? $this->cashierCrad[0]["channel_list"] : array());
			$this->assign('set',$memberConfig);
			$this->assign('set2',$unmemberConfig);
			$this->display();
		}
	}
	//会员卡配置
	public function design(){
		$data=$this->thisCard;
		$grades = D("Member_card_grade")->selectGrade(array("token" => $this->token, "status" => 1));
		if(IS_POST){
			$_POST['token']=$this->token;			
			if($data==false){				
				$this->all_insert('Member_card_set');
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Member_card_set');
			}
		}else{
			if($data==false){
				$data=array (
				'token' => $this->token,
				'cardname' => C('site_name').'会员卡',
				'logo' => '/tpl/User/default/common/images/cart_info/logo-card.png',
				'bg' => './tpl/User/default/common/images/card/card_bg15.png',
				'diybg' => '/tpl/User/default/common/images/card/card_bg17.png',
				'msg' => '微信会员卡，方便携带收藏，永不挂失',
				'numbercolor' => '#000000',
				'vipnamecolor' => '#121212',
				'Lastmsg' => '/tpl/User/default/common/images/cart_info/news.jpg',
				'vip' => '/tpl/User/default/common/images/cart_info/vippower.jpg',
				'qiandao' => '/tpl/User/default/common/images/cart_info/qiandao.jpg',
				'shopping' => '/tpl/User/default/common/images/cart_info/shopping.jpg',
				'memberinfo' => '/tpl/User/default/common/images/cart_info/user.jpg',
				'membermsg' => '/tpl/User/default/common/images/cart_info/vippower.jpg',
				'contact' => '/tpl/User/default/common/images/cart_info/addr.jpg',
				'recharge' => '/tpl/User/default/common/images/cart_info/recharge.jpg',
				'payrecord' => '/tpl/User/default/common/images/cart_info/payrecord.jpg',
				);
			}
			$this->assign("grades", $grades);
			$this->assign('card',$data);
			$this->display();
		}
	}
	//生成会员卡列表
	public function create(){
		$data=M('Member_card_create');
		if (IS_POST){//删除操作
			for ($i=0;$i<50;$i++){
				$thisInfo=$data->where(array('id'=>$_POST['id_'.$i], 'token'=>$this->token))->find();
				/*
				if ($thisInfo['wecha_id']){
					M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$thisInfo['wecha_id']))->delete();
				}
				*/
				$data->where(array('id'=>$_POST['id_'.$i], 'token'=>$this->token))->delete();
				//删除的时候得删除userinfo里的信息
				
			}
			$this->success('删除成功',U('Member_card/create',array('token'=>$this->token,'id'=>$this->thisCard['id'])));
		}else {
			$where=array('token'=>$this->token,'cardid'=>$this->thisCard['id']);
			$count      = $data->where($where)->count();
			$Page       = new Page($count,15);
			$show       = $Page->show();
			$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
			//计算领取张数
			$where['wecha_id']=array('neq','');
			$usecount =M('member_card_create')->where($where)->count();
			$field = M('member_card_custom_field')->where(array('token'=>$this->token,'is_show'=>'1'))->select();
			$this->assign('field',$field);
			$this->assign("usecount",$usecount);
			$this->assign("count",$count);
			$this->assign("ucount",$count - $usecount);
			$this->assign('page',$show);
			$this->assign('data_vip',$list);
			$this->display();
		}
	}
	public function daochuCard(){
		if(IS_POST){
			header("Content-Type: text/html; charset=utf-8");
			header("Content-type:application/vnd.ms-execl");
			header("Content-Disposition:filename=card_info.xls");
			$card_list = M('member_card_create')->where(array('token'=>$this->token,'cardid'=>intval($_POST['id']),'wecha_id'=>array('neq','')))->select();
			foreach($card_list as $ck=>$cv){
				$card_list[$ck]['uid'] = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$cv['wecha_id']))->getField('id');
				$card_list[$ck]['addtime'] = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$cv['wecha_id']))->getField('getcardtime');
			}
			foreach($_POST['field'] as $fk=>$fv){
				$arr[] = array('en'=>$fk,'cn'=>$fv);
			}
			$arr[] = array('en'=>'addtime','cn'=>'添加时间');
			$i=0;
			$fieldCount=count($arr);
			$s=0;
			foreach ($arr as $f){
				if ($s<$fieldCount-1){
					echo iconv('utf-8','gbk//IGNORE',$f['cn'])."\t";
				}else {
					echo iconv('utf-8','gbk//IGNORE',$f['cn'])."\n";
				}
				$s++;
			}
			foreach($card_list as $cak=>$cav){
				$snsdata = null;
				$attach = M('userinfo_attach')->where(array('uid'=>$cav['uid']))->select();
				if($attach){
					foreach($attach as $av){
						$snsdata[$av['field_id']] = $av['field_value'];
					}
					$snsdata['addtime'] = $cav['addtime'];
					$sns[] = $snsdata;
				}
			}
			foreach ($sns as $sn){
				$j=0;
				foreach ($arr as $field){
					$fieldValue=$sn[$field['en']];
					switch ($field['en']){
						default:
							$fieldValue=iconv('utf-8','gbk//IGNORE',$fieldValue);
							break;
						case 'addtime':
							if ($fieldValue){
								$fieldValue=date('Y-m-d H:i:s',$fieldValue);
							}else {
								$fieldValue='';
							}
							break;
					}
					if ($j<$fieldCount-1){
						echo $fieldValue."\t";
					}else {
						echo $fieldValue."\n";
					}
					$j++;
				}
				$i++;
			}
		}
	}
	//创建会员卡
	public function create_add(){ 
		$card=M("Wxuser")->where(array('uid'=>session('uid'),'token'=>$_SESSION['token']))->find();
		if($card['cardisok'] == 0){ //不可以创建
		 	$this->error('您的开卡数量已经用完.请充值或者续费升级.');
		 	return;
		} 
		$markcard = M("Wxuser")->where(array('uid'=>session('uid'),'token'=>$this->token))->find();
		$can_cr_num = $markcard['allcardnum'] - $markcard['yetcardnum'];
		if(IS_POST){
			$end=(int)$_POST['end'];			
			$stat=(int)$_POST['stat'];
		if (($end - $stat) > 100) {
			$this->error('每次最多开卡100张');
		}
		 
			if($end==false||$stat==false){$this->error('卡号起始值或结束值都不能为空');}

			if($end > 65535 || $stat <= 0){
				$this->error('卡号起始值不能为0或结束值不能超过65535');
				return;
			}
			
			$num=$end - $stat;
			if($num <=0 ){
				$this->error('开始卡号不能大于结束卡号');
				return;
			}
			
			//echo $num.'-'.$group_cread_num['create_card_num'];exit;
			if(($num>$can_cr_num)){
			 $this->error('你还有'.$can_cr_num.'张卡的创建名额，请不要超过这个数字');exit;
			}	

			//------------------------------------
			// tp_wxuser 
			//------------------------------------
			$j=0;
			for($i=1;$i<=$num;$i++){
				 $data['number']=$_POST['title'].($stat+=1);
				 $data['token'] =session('token');
				 $data['cardid']=$this->thisCard['id'];
				 $check=M('member_card_create')->where(array('cardid'=>$this->thisCard['id'],'number'=>$data['number']))->find();
				 if (!$check){
				 	$rt=M('member_card_create')->data($data)->add();
				 	if ($rt){
				 		$j++;
				 	}
				 }
			}		

  			$back = M('Wxuser')->where(array('uid'=>session('uid'),'token'=>session('token')))->setInc('yetcardnum',$j);
  			M('Users')->where(array('id'=>$this->user['id']))->setInc('card_num',$j);
  			M('Wxuser')->where(array('uid'=>session('uid'),'token'=>session('token')))->setInc('totalcardnum',$j);
			
			if($back!=false){
				$this->success('恭喜您共开了'.$j.'张会员卡',U('Member_card/create',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
			}else{
				$this->error('没开成任何会员卡');
			}
		}else{
			
			$this->assign('count',$markcard['allcardnum']);
			$this->assign('cancrnum',$can_cr_num);
			$this->display();
		}
	}
	/**
	 *开卡赠送
	 *
	 */
	public function gifts(){
		$cardid = $this->_get('id','intval');
		$where	= array('token'=>$this->token,'cardid'=>$cardid);
		$count  = M('Member_card_gifts')->where($where)->count();
		$Page   = new Page($count,15);
		$list 	= M('Member_card_gifts')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key=>$value){
			if($value['type'] == '2'){
				$item_name 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$value['item_value']))->getField('title');
				$list[$key]['item_name'] = $item_name;
			}	
		}

		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add_gifts(){
		$cardid 	= $this->_get('id','intval');
		$gid 		= $this->_get('gid','intval');
		if(IS_POST){
			if(D('Member_card_gifts')->create()){
					$_POST['cardid']= $cardid;
					$_POST['token'] = $this->token;
					$_POST['start'] = strtotime($this->_post('start','trim'));
					$_POST['end'] 	= strtotime($this->_post('end','trim'));
					if(D('Member_card_gifts')->add($_POST)){
						$this->success('添加成功',U('Member_card/gifts',array('token'=>$this->token,'id'=>$cardid)));
					}
			}else{
				$this->error(D('Member_card_gifts')->getError());
			}
		}else{
			$now 	= time();
			$this->assign('startdate',date('Y-m-d',$now));
			$this->assign('enddate',date('Y-m-d',strtotime('+1 month',$now)));
			$this->display();
		}

	}
	
	public function edit_gifts(){
		$cardid 	= $this->_get('id','intval');
		$gid 		= $this->_get('gid','intval');
		$info 		= D('Member_card_gifts')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$gid))->find();

		$item_name 	= M('Member_card_coupon')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$info['item_value']))->getField('title');
		$info['item_name'] 	= $item_name;

		if(IS_POST){
			if(D('Member_card_gifts')->create()){
				$_POST['start'] = strtotime($this->_post('start','trim'));
				$_POST['end'] 	= strtotime($this->_post('end','trim'));
				$where 	= array('token'=>$this->token,'cardid'=>$cardid,'id'=>$this->_post('gid','intval'));		
				D('Member_card_gifts')->where($where)->save($_POST);
				$this->success('修改成功',U('Member_card/gifts',array('token'=>$this->token,'id'=>$cardid)));
			}else{
				$this->error(D('Member_card_gifts')->getError());
			}
		}else{
			$this->assign('set',$info);
			$this->display();
		}
	}

	public function del_gifts(){
		$id 	= $this->_get('id','intval');
		$gid 	= $this->_get('gid','intval');
		$where  = array('id'=>$gid,'token'=>$this->token,'cardid'=>$id);
		if(M('Member_card_gifts')->where($where)->delete()){
			$this->success('删除成功',U('Member_card/gifts',array('token'=>$this->token,'id'=>$id)));
		}
	}
	
	public function get_value(){	
		$cardid 	= $this->_get('cardid','intval');
		$item_attr 	= $this->_get('item_attr','intval');
		$now 		= time();
		$result 	= array();

		$where 		= array('token'=>$this->token,'cardid'=>$cardid,'is_check'=>1,'is_delete'=>0,'attr'=>'1','type'=>$item_attr,'statdate'=>array('lt',$now),'enddate'=>array('gt',$now));
		$data 		= M('Member_card_coupon')->where($where)->field('id,title')->select();

		$result['err'] 	= 0;
		$result['info'] = $data;

		echo json_encode($result);exit;
	}
	/**
	*积分设置 设置会员卡积分策略及会员卡级别
	*
	*/
	public function exchange(){
		$data=M('Member_card_exchange')->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->find();
		if(IS_POST){
			$_POST['token']=$this->token;
			$_POST['cardid']=$this->thisCard['id'];
			$_POST['create_time'] = time();		
			if($data==false){				
				$this->all_insert('Member_card_exchange','/exchange?token='.$this->token.'&id='.$this->thisCard['id']);
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Member_card_exchange','/exchange?token='.$this->token.'&id='.$this->thisCard['id']);
			}
		}else{
			$this->assign('exchange',$data);
			$this->display();
		}	
	}
	public function notice(){
		$member_card_notice_db=M('Member_card_notice');
		$where=array('cardid'=>$this->thisCard['id']);
		$count      = $member_card_notice_db->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$list = $member_card_notice_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('time desc')->select();
		
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	function noticeSet(){
		$member_card_notice_db=M('Member_card_notice');
		if (IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			$_POST['time']=time();
			$enddates=explode('-',$_POST['enddate']);
			$_POST['endtime']=mktime(23,59,59,$enddates[1],$enddates[2],$enddates[0]);
			if (!isset($_GET['noticeid'])){
				$member_card_notice_db->add($_POST);
			}else {
				$member_card_notice_db->where(array('id'=>intval($_GET['noticeid'])))->save($_POST);
			}
			$this->success('设置成功',U('Member_card/notice',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
		}else {
			if (isset($_GET['noticeid'])){
				$thisNotice=$member_card_notice_db->where(array('id'=>intval($_GET['noticeid'])))->find();
			}else {
				$thisNotice['endtime']=time()+10*24*3600;
			}
			$this->assign('thisNotice',$thisNotice);
			$this->display('noticeAdd');
		}
	}
	function noticeDelete(){
		$member_card_notice_db=M('Member_card_notice');
		$member_card_notice_db->where(array('id'=>intval($_GET['noticeid'])))->delete();
		$this->success('操作成功',U('Member_card/notice',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
	}
	public function privilege(){
		$member_card_vip=M('Member_card_vip');
		
		$data=M('Member_card_vip')->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->order('id desc')->select();
		$this->assign('data_vip',$data);
		$this->display();	
	}
	public function privilege_add(){
		$member_card_vip=M('Member_card_vip');
		if(IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			$_POST['token']=$this->thisCard['token'];
			$_POST['create_time']=time();
			$enddates=explode('-',$_POST['enddate']);
			$_POST['enddate']=0;
			if (!$_POST['type']){
				$_POST['enddate']=mktime(23,59,59,$enddates[1],$enddates[2],$enddates[0]);
			}
			//
			$startdates=explode('-',$_POST['statdate']);
			$_POST['statdate']=0;
			if (!$_POST['type']){
				$_POST['statdate']=mktime(0,0,0,$startdates[1],$startdates[2],$startdates[0]);
			}
			if (!isset($_GET['itemid'])){
				$member_card_vip->add($_POST);
			}else {
				$member_card_vip->where(array('id'=>intval($_GET['itemid'])))->save($_POST);
			}
			$this->success('操作成功',U('Member_card/privilege',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
		}else{
			if (isset($_GET['itemid'])){
				$thisItem=$member_card_vip->where(array('id'=>intval($_GET['itemid'])))->find();
			}
			$this->assign('vip',$thisItem);
			$this->display('privilege_add');
		}
	}
	public function privilege_del(){
		$this->_isUseRecordExist(1,$_GET['itemid']);
		$member_card_vip=M('Member_card_vip');
		$data=$member_card_vip->where(array('token'=>$this->token,'id'=>$this->_get('itemid')))->delete();
		if($data==false){
			$this->error('服务器繁忙请稍后再试');
		}else{
			$this->success('操作成功',U('Member_card/privilege',array('id'=>$this->thisCard['id'],'token'=>$this->token,'type'=>4)));
		}
	}

	//
	public function coupons(){
		$cardid 	= $this->_request('cardid','intval');
		$type 		= $this->_request('type','intval');
		$keyword 	= $this->_request('keyword','trim');
		$is_weixin 	= $this->_request('is_weixin','intval');

		$allcard 	= M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->select();
		$where 		= array('token'=>$this->token,'is_delete'=>0);
		
		if($cardid){
			$where['cardid'] 	= $cardid;
		}
		if(isset($type) && $type != -1){
			$where['type'] 	= $type;
		}

		if($keyword){
			$where['title'] 	= array('like','%'.$keyword.'%');
		}

		if(isset($type) && $is_weixin != -1){
			if($is_weixin == 0 || $is_weixin == 1){
				$where['is_weixin'] 	= $is_weixin;
			}else{
				$where['attr'] 	= 1;
			}	
		}

		$count	= M('Member_card_coupon')->where($where)->count();
		$Page   = new Page($count,15);
		$data 	= M('Member_card_coupon')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($data as $key => $val) {
			if($val['company_id'] == 0){
				$data[$key]['company_name']	 = '所有门店';
			}else{
				$data[$key]['company_name']	 = M('Company')->where(array('token'=>$this->token,'id'=>$val['company_id']))->getField('name');
			}
			$data[$key]['cardname'] 	= M('Member_card_set')->where(array('token'=>$this->token,'id'=>$val['cardid']))->getField('cardname');

			$data[$key]['count'] 		= M('Member_card_coupon_record')->where(array('token'=>$this->token,'coupon_id'=>$val['id']))->count();
		}

		M('Member_card_coupon')->where(array('token'=>$this->token,'is_weixin'=>0))->save(array('is_check'=>1));
		$this->assign('cardid',$cardid);
		$this->assign('is_weixin',$is_weixin);
		$this->assign('keyword',$keyword);
		$this->assign('type',$type);
		$this->assign('page',$Page->show());
		$this->assign('data_vip',$data);
        $this->assign('allcard',$allcard);
		$this->display();
	}

	public function coupons_set(){
		$itemid 	 = $this->_get('itemid','intval');
		$coupon_type = $this->_get('coupon_type','trim');
		$attr 		 = $this->_get('attr','trim');
		$type 		 = $this->_get('cardType','trim');
		$info 		 = M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$itemid))->find();
		
		if($coupon_type == 'weixin'){
			$company 	= M('Company')->where(array('token'=>$this->token,'location_id'=>array('neq',0)))->select();
			if(empty($company)){
				$this->error('添加微信卡券，请先设置并导入门店信息',U('Company/index',array('token'=>$this->token)));
			}
		}

		if(IS_POST){
			$least_cost = isset($_POST['least_cost']) ? intval($_POST['least_cost']) : 0;
			$reduce_cost = isset($_POST['reduce_cost']) ? intval($_POST['reduce_cost']) : 0;
			if ('0' == $type || '0' == $info['type']) {
				if ($least_cost <= 0) {
					$this->error('启用金额为正整数', U('Member_card/coupons'));
				} else {
					$_POST['least_cost'] = $least_cost;
				}
				if ($reduce_cost <= 0) {
					$this->error('减免金额为正整数', U('Member_card/coupons'));
				} else {
					$_POST['reduce_cost'] = $reduce_cost;
				}
			}
			if(D('Member_card_coupon')->create()){

				if($info){
					if($this->wxuser['wx_coupons'] && $info['is_weixin'] == 1){
						$this->error('非法修改，请重新操作',U('Member_card/coupons'));
					}
					
					if(D('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$this->_post('id','intval')))->save()){
						$this->success('修改成功',U('Member_card/coupons',array('token'=>$this->token,'itemid'=>$this->_post('id','intval'))));
					}else{
						$this->error('修改失败');
					}
					
				}else{

					$id 	= D('Member_card_coupon')->add();

					if($id){

						if($coupon_type == 'weixin'){
							if (empty($_POST['brand_name'])) {								
								$this->error('微信卡券商家名称不能为空');
							}
							$coupons 	= new  WechatCoupons($this->wxuser);
							$res  		= $coupons->createCard($id);
							
							if($res['errcode'] == 0){
								D('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$id))->save(array('is_weixin'=>1,'card_id'=>$res['card_id']));
								$this->success('添加成功',U('Member_card/coupons'));
							}else{
								$err 	= '添加失败：'.$res['errmsg'].'';
								D('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$id))->delete();
								$this->error($err);
							}
						
						}else{
							$this->success('添加成功',U('Member_card/coupons'));
						}

					}else{

						$this->error('添加失败');

					}
				}
			}else{

				$this->error(D('Member_card_coupon')->getError());

			}

		}else{

			$allcard 	= M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->select();
			
			$companywhere = array('token'=>$this->token);

			if($coupon_type == 'weixin'){
				$companywhere['location_id'] = array('neq',0);
			}
	
			$company 	= M('Company')->where($companywhere)->order('isbranch asc,id desc')->select();
			
			if($this->wxuser['wx_coupons']){

				$coupons 	= new  WechatCoupons($this->wxuser);

				$color  		= $coupons->getColor();
				
				$this->assign('color',$color);
			}

			$now 	= time();
			$this->assign('vip',$info);
			$this->assign('statdate',$now);
			$this->assign('enddate',strtotime('+1 month',$now));
			$this->assign('attr',$attr);
			$this->assign('type',$type);
			$this->assign('coupon_type',$coupon_type);
			$this->assign('company',$company);
	        $this->assign('allcard',$allcard);
			$this->display();
		}


	}

	public function coupons_save(){

		$itemid = $this->_get('itemid','intval');
		
		$info 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$itemid))->find();

		if(IS_POST){
			$_POST['statdate'] 	= strtotime($_POST['statdate']);
			$_POST['enddate'] 	= strtotime($_POST['enddate']);
			if($info['statdate'] < $_POST['statdate']){
				$this->error('新的开始时间不能大于旧的开始时间');
			}elseif($info['enddate'] > $_POST['enddate'] ){
				$this->error('新的结束时间不能小于旧的开始时间');
			}
			
			$coupons 	= new  WechatCoupons($this->wxuser);
			$res  		= $coupons->updateCard($_POST,$this->token);

			if($res['errcode'] == 0){
				$_POST['is_check']  = 0;
				M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$this->_post('id','intval')))->save($_POST);
				$this->success('修改成功',U('Member_card/coupons',array('token'=>$this->token,'itemid'=>$this->_post('id','intval'))));
			}else{
				$this->error($res['errmsg']);
			}

		}else{

			$companywhere = array('token'=>$this->token);

			$companywhere['location_id'] = array('neq',0);
	
			$company 	= M('Company')->where($companywhere)->order('isbranch asc,id desc')->select();
			
			if($this->wxuser['wx_coupons']){
				$coupons 	= new  WechatCoupons($this->wxuser);
/*				$list  		= $coupons->getCompany();
				if($list['errcode'] == 0){
					$company 	= $list['location_list'];
				}else{
					$this->error('请先导入门店信息');
				}*/
				$color  		= $coupons->getColor();
				$this->assign('color',$color);
			}
			
			$allcard 	= M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->select();
			
			$this->assign('allcard',$allcard);
			$this->assign('company',$company);
			$this->assign('vip',$info);
			$this->display();

		}

	}

	public function coupons_del(){
		
		$itemid 	= $this->_get('itemid','intval');
		
		$info 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$itemid))->field('is_weixin,card_id')->find();

		if($info['is_weixin']){
			$coupons 	= new  WechatCoupons($this->wxuser);
			$res  		= $coupons->delCard($info['card_id']);

			if($res['errcode']){
				$this->error($res['errmsg']);
			}
		}

		$data = M('Member_card_coupon')->where(array('token'=>session('token'),'id'=>$itemid))->save(array('is_delete'=>1));
		//$data = M('Member_card_coupon')->where(array('token'=>session('token'),'id'=>$itemid))->delete();
		if($data==false){
			$this->error('删除失败');
		}else{
			//M('Member_card_coupon_record')->where(array(''))->delete();  未完成
			M('subscribe_reply')->where(array('reply_type'=>3,'relevance_id'=>$itemid))->save(array('status'=>1));
			$this->success('操作成功',U('Member_card/coupons',array('token'=>$this->token)));
		}
	}

	public function coupons_stock(){
		
		$itemid = $this->_post('data_id','intval');
		$number = $this->_post('number','intval');

		$info 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$itemid))->field('is_weixin,card_id')->find();

		if($info['is_weixin']){
			$coupons 	= new  WechatCoupons($this->wxuser);
			$res  		= $coupons->editStock($info['card_id'],$number);
			if($res['errcode'] == 0){
				M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$itemid))->setInc('total',$number);
				echo json_encode(array('errcode'=>'0'));
			}else{
				echo json_encode($res);
			}
			
		}else{
			
			if(M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$itemid))->setInc('total',$number)){
				echo json_encode(array('errcode'=>'0'));
			}else{
				echo json_encode(array('errcode'=>'1','errmsg'=>'修改失败'));
			}

		}
	}

	//会员优惠卷
	public function coupon(){
		$member_card_coupon_db=M('Member_card_coupon');
		$data=$member_card_coupon_db->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->order('id desc')->select();
		foreach ($data as $key => $val) {
			if($val['company_id'] == 0){
				$data[$key]['company_name']	 = '所有门店';
			}else{
				$data[$key]['company_name']	 = M('Company')->where(array('token'=>$this->token,'id'=>$val['company_id']))->getField('name');
			}
		}
		
		$this->assign('data_vip',$data);
		$this->display();	
	}
	public function coupon_edit(){
		$member_card_coupon_db=M('Member_card_coupon');
		if(IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			if (!isset($_GET['itemid'])){
				$this->all_insert('Member_card_coupon','/coupon?id='.$this->thisCard['id']);
			}else {
				$this->all_save('Member_card_coupon','/coupon?id='.$this->thisCard['id']);
			}
		}else{
			$now=time();
			if (isset($_GET['itemid'])){
				$data=$member_card_coupon_db->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->find();
			}else {
				$data['statdate']=$now;
				$data['enddate']=$now+10*24*3600;;
			}
			$company 	= M('Company')->where(array('token'=>$this->token))->order('isbranch asc,id desc')->select();
			$this->assign('company',$company);
			$this->assign('vip',$data);
			$this->display('coupon_edit');
		}
		
	}	
	public function coupon_del(){
		$this->_isUseRecordExist(3,$_GET['itemid']);
			$data=M('Member_card_coupon')->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->delete();
			if($data==false){
				$this->error('没删除成功');
			}else{
				$this->success('操作成功',U('Member_card/coupon',array('id'=>$this->thisCard['id'],'token'=>$this->token,'type'=>2)));
			
			}
	}
	//会员礼卷
	public function integral(){
		$member_card_inergral_db=M('Member_card_integral');
		$data=$member_card_inergral_db->where(array('token'=>$this->token,'cardid'=>$this->thisCard['id']))->order('id desc')->select();
		foreach ($data as $key => $val) {
			if($val['company_id'] == 0){
				$data[$key]['company_name']	 = '所有门店';
			}else{
				$data[$key]['company_name']	 = M('Company')->where(array('token'=>$this->token,'id'=>$val['company_id']))->getField('name');
			}
		}
		$this->assign('data_vip',$data);
		$this->display();	
	}
	public function integral_edit(){
		$member_card_inergral_db=M('Member_card_integral');
		if(IS_POST){
			$_POST['cardid']=$this->thisCard['id'];
			if (isset($_GET['itemid'])){
				$this->all_save('Member_card_integral','/integral?id='.$this->thisCard['id']);
			}else {
				$this->all_insert('Member_card_integral','/integral?id='.$this->thisCard['id']);
			}
		}else{
			$now=time();
			if (isset($_GET['itemid'])){
				$data=$member_card_inergral_db->where(array('token'=>$this->token,'id'=>$this->_get('itemid')))->find();
			}else {
				$data['statdate']=$now;
				$data['enddate']=$now+10*24*3600;;
			}
			$company 	= M('Company')->where(array('token'=>$this->token))->order('isbranch asc,id desc')->select();
			$this->assign('company',$company);
			$this->assign('vip',$data);
			$this->display();
			
		}
		
	}	

	public function integral_del(){
		$this->_isUseRecordExist(2,$_GET['itemid']);
			$data=M('Member_card_integral')->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->delete();
			if($data==false){
				$this->error('服务器繁忙请稍后再试');
			}else{
				$this->success('操作成功',U('Member_card/integral',array('id'=>$this->thisCard['id'],'token'=>$this->token,'type'=>3)));
			
			}
	}
	public function staff(){
		$company_staff_db=M('Company_staff');
		$data = $company_staff_db->where(array('token' => $this->token, 'comefrom' => '0'))->order('id desc')->select();
		$company_db=M('Company');
		$companys = $company_db->where(array('token' => $this->token, 'comefrom' => '0'))->order('id ASC')->select();
		//
		$companysByID=array();
		if ($companys){
			foreach ($companys as $c){
				$companysByID[$c['id']]=$c;
			}
		}
		//
		if ($data){
			$i=0;
			foreach ($data as $d){
				if ((strlen($d['password']) < 32) || (0 < $d['comefrom'])) {
					continue;
				}
				$data[$i]['companyName']=$companysByID[$d['companyid']]['name'];
				$data[$i]['companyID']=$companysByID[$d['companyid']]['id'];
				$i++;
			}
		}
		$this->assign('staffs',$data);
		$this->display();	
	}
	public function staffSet(){
		$company_staff_db=M('Company_staff');
		if(IS_POST){
			if (!trim($_POST['name'])||!trim($_POST['username'])||!intval($_POST['companyid'])){
				$this->error('姓名、用户名和店铺都不能为空');
				exit();
			}
			$_POST['token']=$this->token;
			$_POST['time']=time();
			$_POST['password']=md5($_POST['password']);
			if (!isset($_GET['itemid'])){
				$company_staff_db->add($_POST);
			}else {
				if (!trim($_POST['password'])){
					unset($_POST['password']);
				}
				$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->save($_POST);
			}
			$this->success('操作成功',U('Member_card/staff',array('token'=>session('token'),'id'=>$this->thisCard['id'])));
		}else{
			$company_db=M('Company');
			$companys=$company_db->where(array('token'=>$this->token))->order('id ASC')->select();
			$this->assign('companys',$companys);
			if (isset($_GET['itemid'])){
				$thisItem=$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->find();
			}else {
				$thisItem['companyid']=0;
				//$thisNotice['endtime']=time()+10*24*3600;
			}
			$this->assign('item',$thisItem);
			$this->display('staffSet');
		}
	}
	public function staffDelete(){
		$company_staff_db=M('Company_staff');
		$thisItem=$company_staff_db->where(array('id'=>intval($_GET['itemid'])))->find();
		if ($thisItem['token']==$this->token){
		$data=$company_staff_db->where(array('token'=>session('token'),'id'=>$this->_get('itemid')))->delete();
		$this->success('操作成功',U('Member_card/staff',array('id'=>$this->thisCard['id'],'token'=>$this->token)));
		}
	}

	public function useRecords(){
		$itemid 	= intval($_GET['itemid']);
		$record_db 	= M('Member_card_use_record');
		$where		= array('token'=>$this->token,'itemid'=>$itemid);
		$count      = $record_db->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$list 		= $record_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		
		$wecha_ids=array();
		$staffids=array();
		if ($list){
			foreach ($list as $l){
				if (!in_array($l['wecha_id'],$wecha_ids)){
					array_push($wecha_ids,$l['wecha_id']);
				}
				if (!in_array($l['staffid'],$staffids)){
					array_push($staffids,$l['staffid']);
				}
			}
			//
			$userinfo_where['wecha_id']=array('in',$wecha_ids);
			$users=M('Userinfo')->where($userinfo_where)->select();
			$usersArr=array();
			if ($users){
				foreach ($users as $u){
					$usersArr[$u['wecha_id']]=$u;
				}
			}
			$cards=M('Member_card_create')->where($userinfo_where)->select();
			$cardsArr=array();
			if ($cards){
				foreach ($cards as $u){
					$cardsArr[$u['wecha_id']]=$u;
				}
			}
			$staffWhere=array('in',$staffids);
			$staffs=M('Company_staff')->where($staffWhere)->select();
			$staffsArr=array();
			if ($staffs){
				foreach ($staffs as $s){
					$staffsArr[$s['id']]=$s;
				}
			}
		}
		//
		if ($list){
			$i=0;
			foreach ($list as $l){
				$list[$i]['userName']=$usersArr[$l['wecha_id']]['truename'];
				$list[$i]['userTel']=$usersArr[$l['wecha_id']]['tel'];
				$list[$i]['cardNum']=$cardsArr[$l['wecha_id']]['number'];
				$list[$i]['operName']=$staffsArr[$l['staffid']]['name'];
				$i++;
			}
		}
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->assign('types',$types[$type]);
		$this->display();
	}

	public function useRecord_del(){
		$record_db=M('Member_card_use_record');
		$thisRecord=$record_db->where(array('id'=>intval($_GET['itemid'])))->find();

		if ($thisRecord['token']!=$this->token){
			exit('error');
		}

		if ($thisRecord['cat']){
			switch ($thisRecord['cat']){
				case 1:
					$type='vip';
					break;
				case 2:
					$type='integral';
					break;
				case 3:
					$type='coupon';
					break;
			}
			
			if ($type){
				$db=M('Member_card_'.$type);
				$thisItem=$db->where(array('id'=>$thisRecord['itemid']))->find();
			}
		}
		$record_db->where(array('id'=>intval($_GET['itemid'])))->delete();

		$userinfo_db=M('Userinfo');
    	$thisUser = $userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$thisRecord['wecha_id']))->find();
		$userArr=array();
		$userArr['total_score']=$thisUser['total_score']-$thisRecord['score'];
		$userArr['expensetotal']=$thisUser['expensetotal']-$thisRecord['expense'];
		$userinfo_db->where(array('token'=>$this->token,'wecha_id'=>$thisRecord['wecha_id']))->save($userArr);

		if ($thisRecord['itemid']){
			$useCount=$thisItem['usetime'];
			$useCount=intval($useCount)-$thisRecord['usecount'];
			$db->where(array('id'=>$thisRecord['itemid']))->save(array('usetime'=>$useCount));
		}
		$this->success('操作成功');

    }

	
	
	function _isUseRecordExist($cat,$itemid){
		$record_db=M('Member_card_use_record');
		$thisRecord=$record_db->where(array('itemid'=>intval($itemid),'cat'=>intval($cat)))->find();
		if ($thisRecord){
			$this->error('请先删除该信息下的所有使用记录，然后再删除本信息');
		}
	}

	public function members()
	{
		$get=array_merge(array(
		),I('get.'));
		$get['token']=$this->token;
		$cardCreateModel=D('MemberCardCreate');
		$isSearch = ($_GET["searchkey"] != "") || ($_GET["total_score"] != "") || ($_GET["expensetotal"] != "") || ($_GET["balance"] != "");
		$count = ($isSearch ? $cardCreateModel->getTotal($get) : $cardCreateModel->getTotalNoSearch($get));
		$Page       = new Page($count,30);
		$show       = $Page->show();
		$members = ($isSearch ? $cardCreateModel->getList($get, $Page->firstRow, $Page->listRows) : $cardCreateModel->getListNoSearch($get, $Page->firstRow, $Page->listRows));
		$this->assign('members',$members);
		$this->assign('page',$show);
		$this->display();
	}

	public function member(){
		$type 		= empty($_GET['type'])?1:$this->_get('type','intval');
		//$company_id = $this->_request('company_id','intval');
		$cardid 	= $this->_request('cardid','intval');
		$number 	= $this->_request('number','trim');
		$where 	= array('token'=>$this->token);

		if($number){
			$num 	= explode(',',$number);
			if(!empty($num)){
				$wecha_id 			= M('Member_card_create')->where(array('token'=>$this->token,'number'=>array('in',$num)))->getField('wecha_id',true);
				if(empty($wecha_id)){
					$this->error('请输入有效的卡号');
				}else{	
					$where['wecha_id'] 	= array('in',$wecha_id);
				}
			}
		}
/*
		if($company_id){
			$where['company_id'] 	= $company_id;
		}
*/
		if($cardid){
			$where['cardid'] 		= $cardid;
		}

		if($type == 1){
			$record_db 	= M('Member_card_use_record');

			$count      = $record_db->where($where)->count();
			$Page       = new Page($count,15);
			$list 		= $record_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
			$show       = $Page->show();


		}else{
			$pay_record = M('Member_card_pay_record');

			$count      = $pay_record->where($where)->count();
			$Page       = new Page($count,15);
			$list 		= $pay_record->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('createtime DESC')->select();
			$show       = $Page->show();

		}

       // $company    = M('Company')->where(array('token'=>$this->token))->order('isbranch asc,id desc')->select();
        $allcard 	= M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->select();

       // $this->assign('company_id',$company_id);
        $this->assign('number',$number);
        $this->assign('cardid',$cardid);
      //  $this->assign('company',$company);
        $this->assign('allcard',$allcard);
		$this->assign('list',$list);
		$this->assign('type',$type);
		$this->assign('page',$show);
		$this->display();
	}

	public function recharge(){

		$db = M('Userinfo');
		$uid = (int)$_GET['uid'];
		$cardid = $this->_get('cardid','intval');
		$price = $this->_post('price');
		$uinfo 		= $db->where(array('token'=>$this->token,'id'=>$uid))->field('wechaname,wecha_id,truename,id,balance')->find();
		$cardinfo   = M('Member_card_set')->where(array('token'=>$this->token,'cardid'=>$cardid))->find();
        $mycard   	= M('Member_card_create')->where(array('token'=>$this->token,'cardid'=>$cardid,'wecha_id'=>$uinfo['wecha_id']))->find();

		if(IS_POST){
			if (!is_numeric($price)) {
				$this->error('请填写正确的充值金额');
			}
			if (empty($price)) {
				$this->error('请填写正确的充值金额');
			}
			if($db->create() === false) $this->error($db->getError());
			$id = (int)$_POST['uid'];
			if($db->where(array('token'=>$this->token,'id'=>$id))->setInc('balance',$_POST['price'])){
				$orderid = date('YmdHis',time()).mt_rand(1000,9999);
				M('Member_card_pay_record')->add(array('orderid' => $orderid , 'ordername' => '后台手动充值' , 'createtime' => time() , 'token' => $this->token , 'wecha_id' => $uinfo['wecha_id'] , 'price' => $_POST['price'] , 'type' => 1 , 'paid' => 1 , 'module' => 'Card' , 'paytime' => time() , 'paytype' => 'recharge', 'cardid'=>$cardid));

                /*模板消息*/
				 $model      = new templateNews();
				$href = $this->siteUrl . "/index.php?" . http_build_query(array("g" => "Wap", "m" => "Card", "a" => "card", "token" => $this->token, "wecha_id" => $uinfo["wecha_id"], "cardid" => $cardid));
				$dataKey = "TM00009";
				$dataArr = array("href" => $href, "wecha_id" => $uinfo["wecha_id"], "first" => "您好，你已经成功充值。", "accountType" => $cardinfo["cardname"], "account" => $cardinfo["number"], "amount" => $_POST["price"] . "元", "result" => "充值成功", "remark" => "后台管理员手动充值");
                 $model->sendTempMsg($dataKey,$dataArr);
				
				$this->success('充值成功');

			}else{
				$this->error('充值失败，请稍后再试~');
			}

		}else{

			$this->assign('cardid',$cardid);
			$this->assign('uinfo',$uinfo);
			$this->display();
		}
		
	}
	
	public function signrecords(){
		$card_create_db=M('Member_card_create');
		$where='cardid='.intval($_GET['id']).' AND token=\''.$this->token.'\' AND wecha_id!=\'\'';
		$thisMember=$card_create_db->where(array('id'=>intval($_GET['itemid'])))->find();
		//
		$thisUser=M('Userinfo')->where(array('token'=>$thisMember['token'],'wecha_id'=>$thisMember['wecha_id']))->find();
		$this->assign('thisUser',$thisUser);
		$members[0]=$thisMember;
		$members[0]['truename']=$thisUser['truename'];
		$members[0]['wechaname']=$thisUser['wechaname'];
		$members[0]['qq']=$thisUser['qq'];
		$members[0]['tel']=$thisUser['tel'];
		$members[0]['getcardtime']=$thisUser['getcardtime'];
		$members[0]['expensetotal']=$thisUser['expensetotal'];
		$members[0]['total_score']=$thisUser['total_score'];
		$this->assign('members',$members);
		//
		$record_db=M('Member_card_sign');
		$where=array('wecha_id'=>$thisUser['wecha_id'],'token'=>$this->token);
		$count      = $record_db->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$list = $record_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('sign_time desc')->select();
		$this->assign('records',$list);
		$this->assign('page',$show);
		$this->display();
	}
	public function memberExpense(){
		//score
		if (IS_POST){
			$arr=array();
			$arr['itemid']	= intval($this->_post('itemid'));
			$arr['wecha_id']= $this->_post('wecha_id');
			$arr['expense']	= intval($this->_post('money'));
			$arr['time']	= time();
			$arr['token']	= $this->token;
			$arr['cat']	 	= 3;
			$arr['staffid']	= intval($this->_post('staffid'));
			$arr['usecount']= 0;
			$arr['cardid']	= $this->_get('id');
			$set_exchange = M('Member_card_exchange')->where(array('cardid'=>$this->_get('id')))->find();
			if (0 < $arr["expense"]) {
			$arr["score"] = ($set_exchange["expense"] <= $arr["expense"] ? floor($arr["expense"] * ($set_exchange["reward"] / $set_exchange["expense"])) : 0);
			}
			else {
				$arr["score"] = ceil($arr["expense"] * ($set_exchange["reward"] / $set_exchange["expense"]));
			}
			M("Member_card_use_record")->add($arr);
			$userArr=array();
			$thisUser=M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$arr['wecha_id']))->order('id ASC')->find();
			$userArr['total_score']=$thisUser['total_score']+$arr['score'];
			$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
			M('Userinfo')->where(array('id'=>$thisUser['id'],'token'=>$this->token,'wecha_id'=>$arr['wecha_id']))->save($userArr);
		}
		$this->success('操作成功');
	}
	public function member_del(){
		$card_create_db=M('Member_card_create');
		$thisMember=$card_create_db->where(array('id'=>intval($_GET['itemid']), 'token'=>$this->token))->find();
		//
		$thisUser=M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$thisMember['wecha_id']))->find();
		//
		$where=array('wecha_id'=>$thisUser['wecha_id'],'token'=>$this->token);
		M('Member_card_sign')->where($where)->delete();
		M('Member_card_use_record')->where($where)->delete();
		M('Member_card_coupon_record')->where($where)->delete(); //删除优惠券记录
		M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $thisMember['wecha_id']))->setField('getcardtime', 0);
		S('fans_'.$this->token.'_'.$thisUser['wecha_id'], null);
		$card_create_db->where(array('id'=>intval($_GET['itemid'])))->save(array('wecha_id'=>''));
		$this->success('操作成功');
	}
	public function delete(){
		$tokenWhere=array('token'=>$this->token,'cardid'=>$_GET['id']);
		if (M('Member_card_create')->where($tokenWhere)->find()){
			$this->error('请先删除创建的卡号');
		}
		if(M('Member_card_set')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->delete()){
			M('Member_card_pay_record')->where($tokenWhere)->delete();
			M('Member_card_use_record')->where($tokenWhere)->delete();
			M('Member_card_coupon')->where($tokenWhere)->delete();
		}
		$this->success('操作成功');
	}
	

	
	public function getuserinfo(){
		$wecha_id = $this->_get("id");

		$uinfo = M('Userinfo')->where(array('wecha_id'=>$wecha_id ,'token'=>$_SESSION['token']))->order('id DESC')->find();
		$this->assign('list',$uinfo);
	
		$this->display();	
	}

	
	
	
	
	//会员详情
	public function info(){
		$data=M('Member_card_info')->where(array('token'=>$_SESSION['token']))->find();
		if(IS_POST){
			//dump($_POST);EXIT;
			$_POST['token']=$_SESSION['token'];			
			if($data==false){				
				$this->all_insert('Member_card_info','/info');
			}else{
				$_POST['id']=$data['id'];
				$this->all_save('Member_card_info','/info');
			}
		}else{
			$this->assign('info',$data);
			$contact=M('Member_card_contact')->where(array('token'=>$_SESSION['token']))->order('sort desc')->select();
			$this->assign('contact',$contact);
			$this->display();
		}	
	}
	public function contact(){
		if(IS_POST){			
			$this->all_insert('Member_card_contact','/info');
		}else{
			$this->error('非法操作');	
		}
	}
	public function contact_edit(){
		if(IS_POST){			
			$this->all_save('Member_card_contact','/info');
		}else{
			$this->error('非法操作');			
		}		
	}
	
//导出积分，线下消费记录
	public function exportCardUseRecord(){
		set_time_limit(300); 
	    header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=cardUseRecord.xls");
		
		$arr = array(
			array('en'=>'number','cn'=>'会员卡号'),
			array('en'=>'itemid','cn'=>'消费产品ID'),
			array('en'=>'cat','cn'=>'类型'),
			array('en'=>'expense','cn'=>'消费金额'),
			array('en'=>'score','cn'=>'积分数'),
			array('en'=>'usecount','cn'=>'使用次数'),
			array('en'=>'time','cn'=>'时间'),
			array('en'=>'notes','cn'=>'备注'),
			array('en'=>'cardid','cn'=>'卡ID'),
			array('en'=>'company_id','cn'=>'店铺ID'),
			array('en'=>'staffid','cn'=>'店员ID'),
			array('en'=>'card','cn'=>'卡'),
			array('en'=>'company','cn'=>'店铺'),
			array('en'=>'staff','cn'=>'店员'),
		);
		
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}

		$company_id 	= $this->_get('company_id','intval');
		$cardid 		= $this->_get('cardid','intval');
		$number 		= $this->_get('number','trim');
		
		$db = M('Member_card_use_record');
		$where = array('token'=>$this->token);
		
		if($company_id){
			$where['company_id'] 	= $company_id;
		}

		if($cardid){
			$where['cardid'] 		= $cardid;
		}

		if($number){
			$num 	= explode(',',$number);
			if(!empty($num)){
				$wecha_id 			= M('Member_card_create')->where(array('token'=>$this->token,'number'=>array('in',$num)))->getField('wecha_id',true);
				$where['wecha_id'] 	= array('in',$wecha_id);
			}
		}

		$count		= $db->where($where)->count();
		$page_size 	= 500;
		$page_num 	= ceil($count / $page_size);

		for($i=0; $i<$page_num; $i++){
		    $start 		= $i*$page_size;
		    $limit 		= $start.','.$page_size;
		    $sns 		= $db->where($where)->order('time DESC')->limit($limit)->select();

			if($sns){
				foreach ($sns as $sn){
					$number 		= M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>$sn['wecha_id']))->getField('number');
					$sn['number']	= $number;
					$thiscardid = M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>$sn['wecha_id']))->getField('cardid');
					$sn['cardid'] = $thiscardid;
					$sn['card'] = M('member_card_set')->where(array('token'=>$this->token,'id'=>$thiscardid))->getField('cardname');
					if($sn['company_id'] > 0){
						$company = M('company')->where(array('token'=>$this->token,'id'=>$sn['company_id'],'isbranch'=>1))->getField('name');
					}else{
						$company = M('company')->where(array('token'=>$this->token,'isbranch'=>0))->getField('name');
					}
					$sn['company'] = $company?$company:'无';
					if($sn['staffid'] > 0){
						$sn['staff'] = M('company_staff')->where(array('token'=>$this->token,'id'=>$sn['staffid']))->getField('name');
					}else{
						$sn['staff'] = "无";
					}
					$j = 0;
					foreach ($arr as $field){			
						$fieldValue = $sn[$field['en']];
						switch($field['en']){		
							case 'time':
								if ($fieldValue){
									$fieldValue=iconv('utf-8','gbk',date('Y/m/d',$fieldValue));
									
								}else {
									$fieldValue='';
								}
								break;
							switch($fieldValue){
									case 2:
										$fieldValue = iconv('utf-8','gbk','积分换券');
										break;
									case 3:
										$fieldValue = iconv('utf-8','gbk','后台赠送');
										break;
									case 4:
										$fieldValue = iconv('utf-8','gbk','使用礼品券');
										break;
									case 5:
										$fieldValue = iconv('utf-8','gbk','签到积分');
										break;
									case 6:
										$fieldValue = iconv('utf-8','gbk','会员充值');
										break;
									case 7:
										$fieldValue = iconv('utf-8','gbk','线下获取积分');
										break;
									case 8:
										$fieldValue = iconv('utf-8','gbk','线下消费积分');
										break;
									case 98:
										$fieldValue = iconv('utf-8','gbk','分享');
										break;
									default:
										$fieldValue = iconv('utf-8','gbk','消费');
										
								}
								break;
							case 'notes':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;	
							case 'card':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;	
							case 'company':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;	
							case 'staff':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;	
							default:
								break;
						}
						
						if ($j<$fieldCount-1){
							echo $fieldValue."\t";
						}else {
							echo $fieldValue."\n";
						}
						$j++;
					}
				}
			
			}

			usleep(100); //导出停顿
		}

		exit();
	}
	
//导出人民币在线消费记录

	public function exportrmb(){
		set_time_limit(300); 
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=cardPayRecord.xls");
		
		$arr = array(
			array('en'=>'orderid','cn'=>'订单号'),
			array('en'=>'ordername','cn'=>'订单名称'),
			array('en'=>'transactionid','cn'=>'第三方订单号'),
			array('en'=>'paytype','cn'=>'支付类型'),
			array('en'=>'createtime','cn'=>'订单创建时间'),
			array('en'=>'price','cn'=>'金额'),
			array('en'=>'paytime','cn'=>'支付时间'),
			array('en'=>'paid','cn'=>'支付状态'),
			array('en'=>'number','cn'=>'会员卡号'),
			array('en'=>'module','cn'=>'来源模块'),
			array('en'=>'type','cn'=>'类型'),
			array('en'=>'company_id','cn'=>'店铺ID'),
			array('en'=>'cardid','cn'=>'卡ID'),
			array('en'=>'company','cn'=>'店铺'),
			array('en'=>'card','cn'=>'卡'),
		);
		
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}
		//data
		$db = M('Member_card_pay_record');

		$company_id 	= $this->_get('company_id','intval');
		$cardid 		= $this->_get('cardid','intval');
		$number 		= $this->_get('number','trim');
		
		$where = array('token'=>$this->token);
		
		if($company_id){
			$where['company_id'] 	= $company_id;
		}

		if($cardid){
			$where['cardid'] 		= $cardid;
		}

		if($number){
			$num 	= explode(',',$number);
			if(!empty($num)){
				$wecha_id 			= M('Member_card_create')->where(array('token'=>$this->token,'number'=>array('in',$num)))->getField('wecha_id',true);
				$where['wecha_id'] 	= array('in',$wecha_id);
			}
		}

		$count		= $db->where($where)->count();
		$page_size 	= 500;
		$page_num 	= ceil($count / $page_size);

		for($i=0; $i<$page_num; $i++){
		    $start 		= $i*$page_size;
		    $limit 		= $start.','.$page_size;
		    $sns 		= $db->where($where)->order('id DESC')->limit($limit)->select();

			if($sns){
				foreach ($sns as $sn){
					$number 	= M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>$sn['wecha_id']))->getField('number');
					$sn['number']	= $number;
					if($sn['company_id'] > 0){
						$company = M('company')->where(array('token'=>$this->token,'id'=>$sn['company_id'],'isbranch'=>1))->getField('name');
					}else{
						$company = M('company')->where(array('token'=>$this->token,'isbranch'=>0))->getField('name');
					}
					$sn['card'] = M('member_card_set')->where(array('token'=>$this->token,'id'=>$sn['cardid']))->getField('cardname');
					$sn['company'] = $company?$company:'无';
					$j = 0;
					foreach ($arr as $field){
						$fieldValue = $sn[$field['en']];
						switch($field['en']){
							case 'orderid':
								$fieldValue = iconv('utf-8','gbk',"单号".$fieldValue);
								break;
								
							case 'transactionid':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',"单号".$fieldValue);
								}
								
								break;
								
							case 'createtime':
								if ($fieldValue){
									$fieldValue=iconv('utf-8','gbk',date('Y年m月d日 H时i分s秒',$fieldValue));
									
								}else {
									$fieldValue='';
								}
								break;
							case 'paytime':
								if ($fieldValue){
									$fieldValue=iconv('utf-8','gbk',date('Y年m月d日 H时i分s秒',$fieldValue));
									
								}else {
									$fieldValue='';
								}
								break;
								
							case 'type':
								switch($fieldValue){
									case 1:
										$fieldValue = iconv('utf-8','gbk','充值');
										break;
									case 0:
										$fieldValue = iconv('utf-8','gbk','消费');
										break;	
								}
								break;
								
							case 'paid':
							        if ($sn["paytype"] == "donate") {
								        $fieldValue = iconv("utf-8", "gbk", "充值赠送");
							        }else if ($fieldValue == 1) {
									$fieldValue = iconv('utf-8','gbk','交易成功');
								}else{
									$fieldValue = iconv('utf-8','gbk','未付款');
								}
								break;
							case 'ordername':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;
							case 'card':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;	
							case 'company':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;	
							default:
								break;
						}
						
						if ($j<$fieldCount-1){
							echo $fieldValue."\t";
						}else {
							echo $fieldValue."\n";
						}
						$j++;
					}
					$i++;
				}
					
			}
			usleep(100); //导出停顿
		}
		exit();
	}
	
	
//导出会员卡

	public function exportCard(){
		$page 	= $_GET['page']?intval($_GET['page']):1;
		$id 	= $this->_get('id','intval');
		$token  = $this->token;
//  		$page_size 	= 500;
//  		$listRow 	= ($page-1)*$page_size;

//  		$create  	= M('Member_card_create');
//  		$userinfo  	= M('Userinfo');
//  		$where 		= "token = '$token' AND wecha_id != '' AND cardid = $id";
//  		$count		= $create->where($where)->count();
//  		$page_num 	= ceil($count / $page_size);
//  		$limit 		= $listRow.','.$page_size;
		
		//$createInfo = $create->field('number,token,wecha_id')->where($where)->order('id DESC')->limit($limit)->select();
		//分页导出 header不能实现
		//$createInfo = M('Member_card_create')->alias('card')->field('card.*, user.truename, user.wechaname, user.tel, user.total_score, user.sex, user.bornyear, user.bornmonth, user.bornday, user.portrait, user.qq, user.getcardtime, user.expensetotal, user.balance')->join('LEFT JOIN '.C('DB_PREFIX').'Userinfo user  ON card.wecha_id = user.wecha_id and card.token=user.token' )->where("card.token = '$token' and card.wecha_id !='' and card.cardid=$id")->order('card.id DESC')->limit($limit)->select();
		$createInfo = M('Member_card_create')->alias('card')->field('card.number, user.id, user.truename, user.wecha_id, user.wechaname, user.tel, user.total_score, user.sex, user.bornyear, user.bornmonth, user.bornday, user.portrait, user.qq, user.getcardtime, user.expensetotal, user.balance')->join('LEFT JOIN '.C('DB_PREFIX').'userinfo user  ON card.wecha_id = user.wecha_id and card.token=user.token' )->where("card.token = '$token' and card.wecha_id !='' and card.cardid=$id")->order('card.id ASC')->select();

		//if($page < $page_num){
//  			$fileName 	= "card"."1_".$count.".xls";
			//if($this->exeExportCard($createInfo,$fileName)){
//  				$this->exeExportCard($createInfo,$fileName,$id,$page);
//  				exit();
				//redirect(U('Member_card/exportCard',array('token'=>$this->token,'id'=>$id,'page'=>($page+1))), 3,'在在导出......');
			//}
		//}
		


		$result = $uids = array();	
		foreach ($createInfo as $row) {
			$result[$row['id']] = $row;
			$uids[] = $row['id'];
		}
		
		//每列的名字
		$column_name = array('O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
		//每列对应的字段
		$column_field = array('A' => 'number', 'B' => 'truename', 'C' => 'tel', 'D' => 'total_score', 'E' => 'sex', 'F' => 'bornyear', 'G' => 'bornmonth', 'H' => 'bornday', 'I' => 'portrait', 'G' => 'qq', 'K' => 'getcardtime', 'L' => 'expensetotal', 'M' => 'balance', 'N' => 'wecha_id');		
		
		
		import('@.ORG.phpexcel.PHPExcel');
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator('会员卡的会员信息');
		$objProps->setTitle('会员卡的会员信息');
		$objProps->setSubject('会员卡的会员信息');
		$objProps->setDescription('会员卡的会员信息');
		// 设置当前的sheet
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();
		
		// 开始填充头部
		$objActSheet->setCellValue('A1', '卡号');
		$objActSheet->setCellValue('B1', '姓名');
		$objActSheet->setCellValue('C1', '手机号');
		$objActSheet->setCellValue('D1', '积分');
		$objActSheet->setCellValue('E1', '性别 (男； 女； 其他)');
		$objActSheet->setCellValue('F1', '出生年');
		$objActSheet->setCellValue('G1', '出生月');
		$objActSheet->setCellValue('H1', '出生日');
		$objActSheet->setCellValue('I1', '头像地址');
		$objActSheet->setCellValue('J1', 'QQ号');
		$objActSheet->setCellValue('K1', '领卡时间');
		$objActSheet->setCellValue('L1', '消费总额 (单位：元)');
		$objActSheet->setCellValue('M1', '余额 (单位：元)');
		$objActSheet->setCellValue('N1', '微信OpenID');
				
		$fields = M('Member_card_custom_field')->field('field_id, field_name, item_name')->where(array('token' => $token, 'is_show' => '1'))->select();

		$tarray = $column_field;
		$id_field = array(); // field_id => 字段名(truename)
		$index_id = array(); //字段名(truename) => field_id
		foreach ($fields as $field) {
			$id_field[$field['field_id']] = $field['item_name'];
			if ($field['item_name']) $index_id[$field['item_name']] = $field['field_id'];
			 
			if (empty($field['item_name']) && !in_array($field['item_name'], $tarray)) {
				if (empty($column_name)) break;
				$letter = array_shift($column_name);
				$objActSheet->setCellValue($letter . '1', $field['field_name']);
				$column_field[$letter] = $field['field_id'];
			}
		}
		
		$list = array();
		$attachs = M('Userinfo_attach')->where(array('uid' => array('in', $uids)))->select();
		foreach ($attachs as $use) {
			$list[$use['uid']][$use['field_id']] = $use['field_value'];
		}
		
		$key_id = array_flip($column_field);
		
		$index = 2;
		foreach ($result as $uid => $res) {
			$attachs = $list[$uid];
			
			$objActSheet->setCellValueExplicit('A' . $index, $res['number']);
			if (empty($res['truename']) && isset($index_id['truename'])) {
				$res['truename'] = $attachs[$index_id['truename']];
			}
			$objActSheet->setCellValueExplicit('B' . $index, $res['truename']);
			
			if (empty($res['tel']) && isset($index_id['tel'])) {
				$res['tel'] = $attachs[$index_id['tel']];
			}
			$objActSheet->setCellValueExplicit('C' . $index, $res['tel'] . ' ');
			
			$objActSheet->setCellValueExplicit('D' . $index, $res['total_score']);
			
			
			if (empty($res['sex']) && isset($index_id['sex'])) {
				$res['sex'] = $attachs[$index_id['sex']];
			}
			$res['sex'] = $res['sex'] == 1 ? '男' : ($res['sex'] == 2 ? '女' : '其他');
			$objActSheet->setCellValueExplicit('E' . $index, $res['sex']);
			
			if (empty($res['bornyear']) && isset($index_id['bornyear'])) {
				$res['bornyear'] = $attachs[$index_id['bornyear']];
			}
			$objActSheet->setCellValueExplicit('F' . $index, $res['bornyear']);
			if (empty($res['bornmonth']) && isset($index_id['bornmonth'])) {
				$res['bornmonth'] = $attachs[$index_id['bornmonth']];
			}
			$objActSheet->setCellValueExplicit('G' . $index, $res['bornmonth']);
			
			if (empty($res['bornday']) && isset($index_id['bornday'])) {
				$res['bornday'] = $attachs[$index_id['bornday']];
			}
			$objActSheet->setCellValueExplicit('H' . $index, $res['bornday']);
			
			if (empty($res['portrait']) && isset($index_id['portrait'])) {
				$res['portrait'] = $attachs[$index_id['portrait']];
			}
			$objActSheet->setCellValueExplicit('I' . $index, $res['portrait']);
			
			if (empty($res['qq']) && isset($index_id['qq'])) {
				$res['qq'] = $attachs[$index_id['qq']];
			}
			$objActSheet->setCellValueExplicit('J' . $index, $res['qq']);
			$res['getcardtime'] = $res['getcardtime'] ? date('Y-m-d', $res['getcardtime']) : '暂无';
			$objActSheet->setCellValueExplicit('K' . $index, $res['getcardtime']);
			$objActSheet->setCellValueExplicit('L' . $index, $res['expensetotal']);
			$objActSheet->setCellValueExplicit('M' . $index, $res['balance']);
			$objActSheet->setCellValueExplicit('N' . $index, $res['wecha_id']);
			
			foreach ($attachs as $fid => $fvalue) {
				if ($id_field[$fid]) continue;
				if (isset($key_id[$fid])) {
					$objActSheet->setCellValueExplicit($key_id[$fid] . $index, $fvalue . ' ');
				}
			}
			$index ++;
		}
		
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="card_info.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}


	public function exeExportCard($createInfo,$name,$id,$page){
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=".$name);

		$arr = array(
			array('en'=>'number','cn'=>'卡号'),
			array('en'=>'truename','cn'=>'姓名'),
			array('en'=>'tel','cn'=>'手机号'),
			array('en'=>'total_score','cn'=>'积分'),
			array('en'=>'sex','cn'=>'性别 ( 男； 女； 其他）'),
			array('en'=>'bornyear','cn'=>'出生年'),
			array('en'=>'bornmonth','cn'=>'出生月'),
			array('en'=>'bornday','cn'=>'出生日'),
			array('en'=>'portrait','cn'=>'头像地址'),
			array('en'=>'qq','cn'=>'QQ号'),
			array('en'=>'getcardtime','cn'=>'领卡时间'),
			array('en'=>'expensetotal','cn'=>'消费总额'),
			array('en'=>'balance','cn'=>'余额 单位：元'),
			array('en'=>'wecha_id','cn'=>'微信OpenID')
		);

		//$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
				
			}
			$s++;
		}

		if($createInfo){
			$listCount 	= count($createInfo);
			for ($i=0; $i < $listCount; $i++) {
				for($k=0;$k<count($arr);$k++){
					$fieldValue = $createInfo[$i][$arr[$k]['en']];
					switch($arr[$k]['en']){
						case 'truename':
							$fieldValue = iconv('utf-8','gbk',$fieldValue);
							break;
						case 'wechaname':
							$fieldValue = iconv('utf-8','gbk',$fieldValue);
							break;
						case 'sex':
							if($fieldValue == 1){
								$fieldValue = iconv('utf-8','gbk','男');
							}elseif($fieldValue == 2){
								$fieldValue = iconv('utf-8','gbk','女');
							}else{
								$fieldValue = iconv('utf-8','gbk','其他');
							}
							break;
						case 'getcardtime':
							$fieldValue = date('Y-m-d',$fieldValue);
							break;
					}

					if ($k<$fieldCount-1){
						echo $fieldValue."\t";
					}else{
						echo $fieldValue."\n";
					}
				}
			}	
		}
		
		
	}
	
//删除记录
	public function payRecord_del(){
		$id = $this->_get('pid');
		$token = $this->token;
		$pay_record = M('Member_card_pay_record');
		if($pay_record->where(array('token'=>$token,'id'=>(int)$id))->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}

	}
	
	public function focus(){
		$focusDb = M('Member_card_focus');
		$list = $focusDb->where(array('token'=>$this->token))->select();
		
		$this->assign('info',$list);
		$this->display();
	}
	
	public function focusEdit(){
	
		$where['id']=$this->_get('fid','intval');
		$where['token']=$this->token;
		
		$data = M('Member_card_focus')->where($where)->find();
		
		$this->assign('info',$data);
		$this->display();
	}
	
	public function upsave(){
		$where['id'] = (int)$_POST['fid'];
		$where['token'] = $this->token;
		if(M('Member_card_focus')->where($where)->save($_POST)){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	public function focusDel(){
		$where['token'] = $this->token;
		$where['id'] = (int)$_GET['fid'];
		if(M('Member_card_focus')->where($where)->delete()){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
		
	}
	
	public function focusAdd(){
		if(IS_POST){
			$_POST['token']=$this->token;						
			$this->all_insert('Member_card_focus','/focus');
			}else{
				$this->display();
		}
	}
	
	
	public function custom()
	{
		$fields = M('Member_card_custom_field')->where(array('token' => $this->token))->order('sort DESC, field_id ASC')->select();
		if (empty($fields)) {
			$conf = M('Member_card_custom')->where(array('token' => $this->token))->find();
			if (empty($conf)) {
				$conf = array('wechaname' => 1, 'is_wechaname' => 1, 'tel' => 1, 'is_tel' => 0, 'portrait' => 1, 'is_portrait' => 0, 'truename' => 1, 'is_truename' => 0, 'qq' => 1, 'is_qq' => 0, 'paypass' => 1, 'is_paypass' => 1, 'sex' => 1, 'is_sex' => 0, 
						'bornyear' => 1, 'is_bornyear' => 0, 'bornmonth' => 1, 'is_bornmonth' => 0, 'bornday' => 1, 'is_bornday' => 0, 'address' => 1, 'is_address' => 0, 'origin' => 1, 'is_origin' => 0
				);
			}
			$fields[] = array('field_id' => 0, 'item_name' => 'wechaname', 'field_name' => '微信昵称', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['wechaname'], 'is_empty' => $conf['is_wechaname']);
			$fields[] = array('field_id' => 0, 'item_name' => 'tel', 'field_name' => '手机号', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^\d{6,}$'), 'field_match' => '^\d{6,}$', 'is_show' => $conf['tel'], 'is_empty' => $conf['is_tel']);
			$fields[] = array('field_id' => 0, 'item_name' => 'portrait', 'field_name' => '头像', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['portrait'], 'is_empty' => $conf['is_portrait']);
			$fields[] = array('field_id' => 0, 'item_name' => 'truename', 'field_name' => '真实姓名', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['truename'], 'is_empty' => $conf['is_truename']);
			$fields[] = array('field_id' => 0, 'item_name' => 'qq', 'field_name' => 'QQ号码', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['qq'], 'is_empty' => $conf['is_qq']);
			//$fields[] = array('field_id' => 0, 'item_name' => 'paypass', 'field_name' => '支付密码', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'password'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['paypass'], 'is_empty' => $conf['is_paypass']);
			$fields[] = array('field_id' => 0, 'item_name' => 'sex', 'field_name' => '性别', 'field_option' => '男|女|其他', 'field_type_html' => $this->_formConf('field_type', 'select'), 'field_type' => 'select', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['sex'], 'is_empty' => $conf['is_sex']);
			$fields[] = array('field_id' => 0, 'item_name' => 'bornyear', 'field_name' => '出生年', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornyear'], 'is_empty' => $conf['is_bornyear']);
			$fields[] = array('field_id' => 0, 'item_name' => 'bornmonth', 'field_name' => '出生月', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornmonth'], 'is_empty' => $conf['is_bornmonth']);
			$fields[] = array('field_id' => 0, 'item_name' => 'bornday', 'field_name' => '出生日', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornday'], 'is_empty' => $conf['is_bornday']);
			$fields[] = array('field_id' => 0, 'item_name' => 'address', 'field_name' => '地址', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['address'], 'is_empty' => $conf['is_address']);
			$fields[] = array('field_id' => 0, 'item_name' => 'origin', 'field_name' => '来源渠道', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['origin'], 'is_empty' => $conf['is_origin']);
		} else {
			if ($conf = M('Member_card_custom')->where(array('token' => $this->token))->find()) {
				$old_fields[] = array('field_id' => 0, 'item_name' => 'wechaname', 'field_name' => '微信昵称', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['wechaname'], 'is_empty' => $conf['is_wechaname']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'tel', 'field_name' => '手机号', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^\d{6,}$'), 'field_match' => '^\d{6,}$', 'is_show' => $conf['tel'], 'is_empty' => $conf['is_tel']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'portrait', 'field_name' => '头像', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['portrait'], 'is_empty' => $conf['is_portrait']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'truename', 'field_name' => '真实姓名', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['truename'], 'is_empty' => $conf['is_truename']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'qq', 'field_name' => 'QQ号码', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['qq'], 'is_empty' => $conf['is_qq']);
				//$old_fields[] = array('field_id' => 0, 'item_name' => 'paypass', 'field_name' => '支付密码', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'password'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['paypass'], 'is_empty' => $conf['is_paypass']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'sex', 'field_name' => '性别', 'field_option' => '男|女|其他', 'field_type_html' => $this->_formConf('field_type', 'select'), 'field_type' => 'select', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['sex'], 'is_empty' => $conf['is_sex']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'bornyear', 'field_name' => '出生年', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornyear'], 'is_empty' => $conf['is_bornyear']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'bornmonth', 'field_name' => '出生月', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornmonth'], 'is_empty' => $conf['is_bornmonth']);
				$old_fields[] = array('field_id' => 0, 'item_name' => 'bornday', 'field_name' => '出生日', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => $conf['bornday'], 'is_empty' => $conf['is_bornday']);
			}
			foreach ($fields as &$f) {
				$f['field_type_html'] = $this->_formConf('field_type', $f['field_type']);
				$f['field_match_html'] = $this->_formConf('field_match', $f['field_match']);
			}
			$old_fields && $fields = array_merge($old_fields, $fields);
		
		}

		//确保三个固定项不被删除
		$no_delete_fields = array();
		$no_delete_fields['wechaname'] = array('field_id' => 0, 'item_name' => 'wechaname', 'field_name' => '微信昵称', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => 1, 'is_empty' => 1);
		$no_delete_fields['tel'] = array('field_id' => 0, 'item_name' => 'tel', 'field_name' => '手机号', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^\d{6,}$'), 'field_match' => '^\d{6,}$', 'is_show' => 1, 'is_empty' => 1);
		$no_delete_fields['portrait'] = array('field_id' => 0, 'item_name' => 'portrait', 'field_name' => '头像', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => 1, 'is_empty' => 1);
		$no_delete_fields['truename'] = array('field_id' => 0, 'item_name' => 'truename', 'field_name' => '真实姓名', 'field_option' => '', 'field_type_html' => $this->_formConf('field_type', 'text'), 'field_type' => 'text', 'field_match_html' => $this->_formConf('field_match', '^[\u4e00-\u9fa5\a-zA-Z0-9]+$'), 'field_match' => '^[\u4e00-\u9fa5\a-zA-Z0-9]+$', 'is_show' => 1, 'is_empty' => 1);
		foreach ($fields as $ff) unset($no_delete_fields[$ff['item_name']]);
		$no_delete_array = array();
		foreach ($no_delete_fields as $nf) {
			$no_delete_array[] = $nf;
		}
		$no_delete_array && $fields = array_merge($no_delete_array, $fields);
		$this->assign('tel', '^\d{6,}$');
		$this->assign('fields', $fields);
		$this->display();
	}
	
	
	public function customSave()
	{
		$field_name = $_POST['field_name'];
		$field_id = $_POST['field_id'];
		$is_show = $_POST['is_show'];
		$is_empty = $_POST['is_empty'];
		$field_type = $_POST['field_type'];
		$field_option = $_POST['field_option'];
		$field_match = $_POST['field_match'];
		$sort = $_POST['sort'];
		$item_name = $_POST['item_name'];
		
		$db_field = M('Member_card_custom_field');
		$ids = array();
		$t_list = $db_field->field('field_id')->where(array('token' => $this->token))->select();
		foreach ($t_list as $v) $ids[] = $v['field_id'];
		foreach ($field_name as $k => $val) {
			if (empty($val)) continue;
			$f_data = array('token' => $this->token, 'set_id' => 0, 'item_name' => $item_name[$k], 'sort' => intval($sort[$k]), 'field_name' => $val, 'field_option' => $field_option[$k], 'field_type' => $field_type[$k], 'field_match' => $field_match[$k], 'is_show' => $is_show[$k], 'is_empty' => $is_empty[$k]);
			if ($field_id[$k] && in_array($field_id[$k], $ids)) {
				$db_field->where(array('field_id' => $field_id[$k]))->save($f_data);
				$ids = array_diff($ids, array($field_id[$k]));
			} else {
				$db_field->add($f_data);
			}
		}
		if ($ids) {
			foreach ($ids as $id) {
				$db_field->where(array('token' => $this->token, 'field_id' => $id))->delete();
			}
		}
		D('Member_card_custom')->where(array('token' => $this->token))->delete();
		$this->success('操作成功');

	}
	

	public function center()
	{
		$where = array('token' => $this->token, 'wecha_id' => array('neq',''));
		$cardid = $this->_request('cardid', 'intval');
		$number = $this->_request('number', 'trim');
		if (!empty($cardid)) $where['cardid'] = $cardid;
		if (!empty($number)) $where['number'] = $number;			
		$count = M('Member_card_create')->where($where)->count();
		$Page = new Page($count, 15);
		$show = $Page->show();
		$allcard = M('Member_card_create')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$wecha_ids = $cardids = array();
		foreach ($allcard as $value) {
			$wecha_ids[] = $value['wecha_id'];
			$cardids[] = $value['cardid'];
		}

		$wecha_ids && $user_infos = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => array('in', $wecha_ids)))->select();
		$users = array();
		foreach ($user_infos as $u) $users[$u['wecha_id']] = $u;
		
		$cardids && $sets = M('Member_card_set')->where(array('token' => $this->token, 'id' => array('in', $cardids)))->select();
		$slist = array();
		foreach ($sets as $s) $slist[$s['id']] = $s;
		
		foreach($allcard as &$value){
			if (isset($users[$value['wecha_id']]) && (empty($users[$value['wecha_id']]['truename']) || empty($users[$value['wecha_id']]['wechaname']) || empty($users[$value['wecha_id']]['tel']))) {
				if ($new = D('Userinfo')->updateInfo($users[$value['wecha_id']]['id'])) {
					$users[$value['wecha_id']]['truename'] = $new['truename'];
					$users[$value['wecha_id']]['wechaname'] = $new['wechaname'];
					$users[$value['wecha_id']]['tel'] = $new['tel'];
				}
			}
			$value['score'] = isset($users[$value['wecha_id']]['total_score']) ? $users[$value['wecha_id']]['total_score'] : 0;
			$value['expense'] = isset($users[$value['wecha_id']]['expensetotal']) ? $users[$value['wecha_id']]['expensetotal'] : 0;
			$value['balance'] = isset($users[$value['wecha_id']]['balance']) ? $users[$value['wecha_id']]['balance'] : 0;
			$value['createtime'] = isset($users[$value['wecha_id']]['getcardtime']) ? $users[$value['wecha_id']]['getcardtime'] : 0;
			$value['username'] = isset($users[$value['wecha_id']]['truename']) && $users[$value['wecha_id']]['truename'] ? $users[$value['wecha_id']]['truename'] : (isset($users[$value['wecha_id']]['wechaname']) ? $users[$value['wecha_id']]['wechaname'] : '');
			$value['tel'] = isset($users[$value['wecha_id']]['tel']) ? $users[$value['wecha_id']]['tel'] : '';
			$value['card_name'] = isset($slist[$value['cardid']]['cardname']) ? $slist[$value['cardid']]['cardname'] : '';
		}
		
		
		$cards 	= M('Member_card_set')->where(array('token'=>$this->token))->select();
		

		$this->assign('cardid',$cardid);
		$this->assign('number',$number);
		$this->assign('cards',$cards);
		$this->assign('allCard',$allcard);
		$this->assign('page',$Page->show());


		$this->display();
	}

	public function center_all(){
		$where 		= array('token'=>$this->token);
		$search 	= $this->_post('search','trim');
		if(!empty($search)){
			$where['wechaname|truename|tel']	= array('like','%'.$search.'%');
		}

		$count		= M('Userinfo')->where($where)->count();
		$Page       = new Page($count,15);
		$show       = $Page->show();
		$user 		= M('Userinfo')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();

		$this->assign('user',$user);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	//同步门店信息
	public function import_company(){
		$company 		= M('Company')->where('token="'.$this->token.'"')->order('isbranch desc')->field('id,name,shortname,tel,province,city,district,address,cat_name,longitude,latitude')->select();

		$coupons 	= new WechatCoupons($this->wxuser);
		$res  		= $coupons->unifyCompany($company);

		if($res['res']['errcode']==0){
			$location_id_list 	= $res['res']['location_id_list'];
			$company_id 		= $res['company_id'];
			
			for($i=0,$count=count($location_id_list);$i<$count;$i++){
				if($location_id_list[$i] != -1){
					M('Company')->where(array('token'=>$this->token,'id'=>$company_id[$i]))->save(array('location_id'=>$location_id_list[$i]));
				}
			}
			
			$this->success('门店同步成功');
		}else{
			$this->error('门店同步失败');
		}
	}
	
	//充值赠送
	public function donate(){
		$cardid 	= $this->_get('id','intval');

		$list 		= M('Member_card_donate')->where(array('token'=>$this->token,'cardid'=>$cardid))->select();

		foreach ($list as $key => $value ) {
			$list[$key]["grade_name"] = D("Member_card_grade")->findGrade(array("id" => $value["gradeid"]), "grade_name");
		}
		$this->assign('list',$list);
		$this->assign('cardid',$cardid);
		$this->display();
	}
	//设置充值赠送
	public function donate_set(){
		$id 		= $this->_get('doid','intval');
		$cardid 	= $this->_get('id','intval');

		$info 		= M('Member_card_donate')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$id))->find();

		$gradeid = M("Member_card_set")->where(array("id" => $cardid))->getField("gradeid");
		$grades = D("Member_card_grade")->selectGrade(array(
	"token"  => $this->token,
	"status" => 1,
	"id"     => array("gt", $gradeid)
	));
		
		if(IS_POST){
			$min_price 		= $this->_post('min_price','floatval');
			$max_price 		= $this->_post('max_price','floatval');
			$donate_price 	= $this->_post('donate_price','floatval');
			$is_open 		= $this->_post('is_open','intval');
			$intro 			= $this->_post('donate_intro','trim');
			$gradeid = $this->_post("gradeid", "intval");
			if($min_price == 0 && $max_price == 0){
				$this->error('最小冲值和最大冲值不能同时不设限制');
			}elseif($min_price >= $max_price && $max_price != 0){
				$this->error('充值区间下限不能大于上限');
			}
			
			if($donate_price == 0 || $donate_price == ''){
				$this->error('赠送金额不能为空');
			}
			
			$if_has_set = M("Member_card_donate")->where(array("token" => $this->token, "cardid" => $cardid, "gradeid" => $gradeid, "is_open" => 1))->find();
			if (!empty($if_has_set) && ($gradeid != 0) && ($_POST["id"] != $if_has_set["id"])) {
				$this->error("该升级级别已被设置,您可以选择【级别无改动】");
			}

			$data = array("cardid" => $this->_post("cardid", "intval"), "token" => $this->token, "min_price" => $min_price, "max_price" => $max_price, "donate_price" => $donate_price, "is_open" => $is_open, "donate_intro" => $intro, "gradeid" => $gradeid);

			if($info){
				M('Member_card_donate')->where(array('token'=>$this->token,'cardid'=>$cardid,'id'=>$id))->save($data);
				$this->success('修改成功',U('Member_card/donate',array('token'=>$this->token,'id'=>$data['cardid'])));
			}
			else if (M("Member_card_donate")->add($data)) {
				$this->success("添加成功", U("Member_card/donate", array("token" => $this->token, "id" => $data["cardid"])));
			}
		}else{
		
			$this->assign('set',$info);
			$this->assign('cardid',$cardid);
			$now 	= time();
			$this->assign('startdate',date('Y-m-d',$now));
			$this->assign('enddate',date('Y-m-d',strtotime('+1 month',$now)));
			$this->assign('donate_intro',$info['donate_intro']);
			$this->assign("grades", $grades);
			$this->display();
		}
	
	}
	
	//删除充值赠送
	public function donate_del(){
		$id 		= $this->_get('doid','intval');
		$cardid 	= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'cardid'=>$cardid,'id'=>$id);
		
		if(M('Member_card_donate')->where($where)->delete()){
			$this->success('删除成功',U('Member_card/donate',array('token'=>$this->token,'id'=>$cardid)));
		}
	}
	
	//领卡记录
	public function consume_record(){
		$cardid 	= $this->_request('cardid','intval');
		$keyword 	= $this->_request('keyword','trim');
		
		$where 	= "record.token='".$this->token."' AND record.is_use='1'";

		if($cardid){
			$where 	.= " AND record.cardid=$cardid";
		}

		if($keyword){
			$code 	= str_replace("-","",$keyword);
			$where 	.= " AND (record.cancel_code LIKE '%$code%' OR coupon.title LIKE '%$keyword%')";
		}

		$count 	= M('Member_card_coupon_record')->alias('record')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'member_card_coupon coupon ON coupon.id=record.coupon_id')
            ->where($where)->count();
		$Page     	= new Page($count,15);

        $list 	= M('Member_card_coupon_record')->alias('record')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'member_card_coupon coupon ON coupon.id=record.coupon_id')
            ->where($where)
            ->field('record.id,record.wecha_id,record.staff_id,record.company_id,record.cancel_code,record.use_time,record.is_use,record.coupon_type,coupon.title,coupon.is_weixin')
            ->order('record.use_time desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();
			
        foreach ($list as $key => $val) {
        	if($val['company_id']){
        		$list[$key]['company_name'] 	= M('Company')->where(array('token'=>$this->token,'id'=>$val['company_id']))->getField('name');
        	}else{
        		$list[$key]['company_name'] 	= '所有门店';
        	}
        	if($val['staff_id']>0){
        		$list[$key]['staff_name'] 	= M('Company_staff')->where(array('token'=>$this->token,'id'=>$val['staff_id']))->getField('name');
        	}else if($val['staff_id'] == -1){
        		$list[$key]['staff_name'] 	= '用户核销';
        	}else if($val['staff_id'] == -2){
        		$list[$key]['staff_name'] 	= '后台核销';
        	}else{
        		$list[$key]['staff_name'] 	= '历史数据';
        	}
			$list[$key]['wechaname'] 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->getField('wechaname');
        }
        $allcard 	= M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->select();
        $this->assign('keyword',$keyword);
        $this->assign('cardid',$cardid);
        $this->assign('allcard',$allcard);
		$this->assign('list',$list);
 		$this->assign('page',$Page->show());
		$this->display();

	}
	//核销接口
	public function consume_use(){
		$code 	= $this->_get('code','trim');
		/*$where 	= array('token'=>$this->token,'id'=>$cid);
		if(!empty($cid)){
			$info 	= M('Member_card_coupon_record')->where($where)->find();
		}
		$this->assign('info',$info);*/
		$code = join('-',str_split($code,4));
		$this->assign('code',$code);
		$this->display();
	}
	//查询卡券
	public function find_coupons(){
		$code 	= $this->_post('code','trim');
		$code 	= str_replace("-","",$code);
		$where 	= array('token'=>$this->token,'cancel_code'=>$code);

		$info 	= M('Member_card_coupon_record')->where($where)->find();
		
		$coupons= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$info['coupon_id']))->field('pic,title,statdate,enddate,is_weixin,brand_name,logourl,is_weixin,info')->find();
		
		$companywhere = array('token'=>$this->token);
		
		if($coupons['is_weixin']){
			$companywhere['location_id'] = array('neq',0);
		}

		if($info['company_id']){

			$companywhere['id'] = $info['company_id'];
			
			$company = M('Company')->where($companywhere)->order('isbranch asc,id desc')->select();
		}else{
			$company = M('Company')->where($companywhere)->select();
		}

		
		$data 	= array_merge($info,$coupons);

		if($data){
			$data['statdate'] 	= date('Y-m-d',$coupons['statdate']);
			$data['enddate'] 	= date('Y-m-d',$coupons['enddate']);
			$data['cancel_code']= join('-',str_split($data['cancel_code'],4));		
			$data['logourl'] 	= $data['pic'];	//修复图片不显示 
		}
		
		$res 	= array('company'=>$company,'info'=>$data);
		
		echo json_encode($res);

	}

	//核销卡券
	public function consume_coupons(){
		$code 	= $this->_post('code','trim');
		$code 	= str_replace("-","",$code);
		$where 	= array('token'=>$this->token,'cancel_code'=>$code);

		$info 	= M('Member_card_coupon_record')->where($where)->find();
		$info['is_wx'] 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$info['coupon_id']))->getField('is_weixin');
		$result = array();

		if($info['is_use'] == 1){
			$result['err'] 	= 1;
			$result['msg'] 	= '此券已经核销，请不要重新核销';
		}else{
			if($info['is_wx']){
				$coupons 	= new  WechatCoupons($this->wxuser);
				$res 	 	= $coupons->consumeCoupons($info['card_id'],$info['cancel_code']);
				if($res['errcode'] > 0){
					$result['err'] 	= 2;
					$result['msg'] 	= $res['errmsg'];
				}

			}

			$rwhere 	= array('token'=>$this->token,'cancel_code'=>$code,'is_use'=>'0');
			
			if(empty($result) && M('Member_card_coupon_record')->where($rwhere)->save(array('use_time'=>time(),'is_use'=>'1','staff_id'=>-2))) {
				$result['err'] 	= 0;
				$result['msg'] 	= '核销成功';
				M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$info['coupon_id']))->setInc('usetime',1);	
			}

		}

		echo json_encode($result);

	}
	//卡券领取记录
	public function coupons_record(){
		$cardid 	= $this->_post('cardid','intval');
		$itemid 	= intval($_GET['itemid']);
		$keyword 	= $this->_post('keyword','trim');
		
		$where 	= "record.token='$this->token' AND record.coupon_id=$itemid";

		if($cardid){
			$where 	.= " AND record.cardid=$cardid";
		}

		if($keyword){
			$code 	= str_replace("-","",$keyword);
			$where 	.= " AND (record.cancel_code LIKE '%$code%' OR user.wechaname LIKE '%$keyword%' OR user.truename LIKE '%$keyword%')";
		}

		$count 	= M('Member_card_coupon_record')->alias('record')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'member_card_coupon coupon ON coupon.id=record.coupon_id')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'userinfo user ON record.wecha_id=user.wecha_id')
            ->where($where)
            ->count();

		$Page     	= new Page($count,15);

        $list 	= M('Member_card_coupon_record')->alias('record')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'member_card_coupon coupon ON coupon.id=record.coupon_id')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'userinfo user ON record.wecha_id=user.wecha_id AND record.token= user.token')
            ->where($where)
            ->field('record.id,record.add_time,record.staff_id,record.company_id,record.cancel_code,record.use_time,record.is_use,record.coupon_type,record.iswhere,record.whereid,coupon.title,coupon.is_weixin,user.wechaname,user.tel')
            ->order('record.add_time desc')
            ->limit($Page->firstRow.','.$Page->listRows)
            ->select();

		foreach($list as $k=>$v){
			$list[$k]['wherename'] = M('lottery')->where(array('token'=>$this->token,'id'=>$v['whereid']))->getField('title');
		}
        $allcard 	= M('Member_card_set')->where(array('token'=>$this->token))->order('id ASC')->select();
        $this->assign('allcard',$allcard);
		$this->assign('list',$list);
 		$this->assign('page',$Page->show());
		$this->display();
	}
	//删除领取卡券
	public function coupons_record_del(){
		$itemid = intval($_GET['itemid']);
		$where 	= array('token'=>$this->token,'id'=>$itemid);

		$info 	= M('Member_card_coupon_record')->where($where)->find();
/*		if($info['is_use'] == 1){
			$this->error('已核销卡券无法删除');
			exit;
		}*/
		$is_wx 	= M('Member_card_coupon')->where(array('token'=>$this->token,'id'=>$info['coupon_id']))->getField('is_weixin');

		if($is_wx && $info['is_use'] == 0){
			$coupons 	= new  WechatCoupons($this->wxuser);
			$res 	 	= $coupons->invalid_code($info['card_id'],$info['cancel_code']);
			
			if($res['errcode'] > 0){
				$this->error($res['errmsg']);
				exit;
			}

		}

		if(M('Member_card_coupon_record')->where($where)->delete()){
			$this->success('删除成功');
		}
	}
	
	public function change_card_set()
	{
		$cardid = intval($_POST['cardid']);
		$where = array('token' => $this->token, 'id' => $cardid);
		//if (M('Member_card_set')->where($where)->find()) {
			M('Member_card_set')->where(array('token' => $this->token))->save(array('sub_give' => 0));
			M('Member_card_set')->where($where)->save(array('sub_give' => 1));
			exit(json_encode(array('error' => false, 'msg' => 'ok')));
		//}
		exit(json_encode(array('error' => true, 'msg' => 'no')));
	}
	
	
	/*字段类型和匹配项*/
	public function _formConf($type = '', $select = ''){
		$conf = array(
			'field_type' => array(
				array('value'=>'','text'=>'请选择类型'),
				array('value'=>'text','text'=>'单行文本框'),
				array('value'=>'textarea','text'=>'多行文本框'),
				array('value'=>'checkbox','text'=>'多选选框'),
				array('value'=>'radio','text'=>'单选按钮'),
				array('value'=>'select','text'=>'下拉框'),
				array('value'=>'date','text'=>'日期选择'),
				array('value'=>'password','text'=>'密码'),
			),
			'field_match' => array(
				array('value'=>'','text'=>'请选择验证方式'),
				array('value'=>'^[\u4e00-\u9fa5\a-zA-Z0-9]+$','text'=>'英文数字汉字'),
				array('value'=>'^[A-Za-z]+$','text'=>'英文大小写字符'),			
				array('value'=>'^[1-9]\d*|0$','text'=>'0或正整数'),
				array('value'=>'^[A-Za-z0-9_]+$','text'=>'字母数字下滑线'),
				array('value'=>'^[0-9]{1,30}$','text'=>'正整数'),
				array('value'=>'^[-\+]?\d+(\.\d+)?$','text'=>'小数'),
				array('value'=>'\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*','text'=>'邮箱'),
				array('value'=>'^\d{6,}$','text'=>'手机'),
			)

		);

		$str = '';
		foreach($conf[$type] as $key=>$value){
			if($select == $value['value']){
				$selected	= 'selected';
			}else{
				$selected	= '';
			}

			$str 	.='<option value="'.$value['value'].'" '.$selected.'>'.$value['text'].'</option>';
		}

		return $str;
	}

	
	public function test_check()
	{

		$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
		
		$star = ($page - 1) * 200;
		$where = array('token' => $this->token);
		
		$fields = M('Member_card_custom_field')->where($where)->select();
		$data = array();
		foreach ($fields as $field) {
			if (in_array($field['item_name'], array('tel', 'wechaname', 'truename'))) {
				$data[$field['field_id']] = $field['item_name'];
			}
		}
// 		$count = M('Userinfo')->where($where)->count();
// 		$Page = new Page($count, 200);
// 		$show = $Page->show();
		$users = M('Userinfo')->where($where)->limit($star.',200')->select();
		if (empty($users)) {
			die;
		}
		$uids = array();
		foreach ($users as $user) {
			if (empty($user['wechaname']) || empty($user['truename']) || empty($user['tel'])) {
				if (!in_array($user['id'], $uids)) $uids[] = $user['id'];
			}
		}
		if ($uids) {
			$attachs = M('Userinfo_attach')->where(array('uid' => array('in', $uids)))->select();
			$temp = array();
			foreach ($attachs as $a) {
				if (isset($data[$a['field_id']])) {
					$temp[$a['uid']][$data[$a['field_id']]] = $a['field_value'];
				}
			}
			//dump($data);
			//dump($temp);die;
			foreach ($uids as $id) {
				
				D('Userinfo')->where(array('id' => $id))->save($temp[$id]);
			}
		}
		$this->success('校验中', U('Member_card/test_check', array('p' => $page + 1, 'token' => $this->token)));
	}
	
	//积分兑换红包
	public function jfdhhb(){
		$card = M('member_card_set')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();
		$this->assign('card',$card);
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;
		$where['cardid'] = intval($_GET['id']);
		$where_page['id'] = intval($_GET['id']);
		if(!empty($_GET['search'])){
			$map['hongbao'] = (intval($_GET['search']))*100;
			$map['integral'] = intval($_GET['search']);
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
			$where_page['search'] = $_GET['search'];
		}
		$count = M('member_card_jfdhhb')->where($where)->count();
		$page = new Page($count,15);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$list = M('member_card_jfdhhb')->where($where)->order("addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		foreach($list as $lk=>$lv){
			$list[$lk]['dhcount'] = M('member_card_jfdhhb_record')->where(array('token'=>$this->token,'jid'=>$lv['id']))->count();
			$list[$lk]['dhcount'] = $list[$lk]['dhcount']?$list[$lk]['dhcount']:0;
		}
		$this->assign('list',$list);
		$this->display();
	}
	public function jfdhhb_set(){
		$card = M('member_card_set')->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
		$this->assign('card',$card);
		$id = $this->_get('id','intval');
		$where = array('token'=>$this->token,'id'=>$id,'cardid'=>intval($_GET['cardid']));
		$jfdhhb = M('member_card_jfdhhb')->where($where)->find();
		if(IS_POST){
			$send_name = strlen($_POST['send_name']);
			if($send_name > 30){
				$this->error('兑换红包商户名称不超过30个字母，相当于10个汉字。');exit;
			}
			$act_name = strlen($_POST['act_name']);
			if($act_name > 30){
				$this->error('兑换红包活动名称不超过30个字母，相当于10个汉字。');exit;
			}
			$wishing = strlen($_POST['wishing']);
			if($wishing > 120){
				$this->error('兑换红包祝福语不超过120个字母，相当于40个汉字。');exit;
			}
			$remark = strlen($_POST['remark']);
			if($remark > 240){
				$this->error('兑换红包备注不超过240个字母，相当于80个汉字。');exit;
			}
			
			if($jfdhhb){
				$_POST['hongbao'] = $_POST['hongbao']*100;
				$_POST['starttime'] = strtotime($_POST['starttime']);
				$_POST['endtime'] = strtotime($_POST['endtime']);
				$update_jfdhhb = M('member_card_jfdhhb')->where($where)->save($_POST);
				$this->success('修改成功',U('User/Member_card/jfdhhb',array('token'=>$this->token,'id'=>$_GET['cardid'])));
			}else{
				$_POST['token'] = $this->token;
				$_POST['cardid'] = intval($_GET['cardid']);
				$_POST['hongbao'] = $_POST['hongbao']*100;
				$_POST['addtime'] = time();
				$_POST['starttime'] = strtotime($_POST['starttime']);
				$_POST['endtime'] = strtotime($_POST['endtime']);
				$id = M('member_card_jfdhhb')->add($_POST);
				if($id > 0){
					$this->success('添加成功',U('User/Member_card/jfdhhb',array('token'=>$this->token,'id'=>$_GET['cardid'])));
				}
			}
		}else{
			if($jfdhhb){
				$this->assign('set',$jfdhhb);
			}
			$this->display();
		}
	}
	public function jfdhhb_del(){
		$del = M('member_card_jfdhhb')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->delete();
		if($del > 0){
			$this->success('删除成功',U('User/Member_card/jfdhhb',array('token'=>$this->token,'id'=>$_GET['cardid'])));
		}
	}
	public function jfdhhb_record(){
		$card = M('member_card_set')->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
		$this->assign('card',$card);
		$id = $this->_get('id','intval');
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;
		if($id){
			$jfdhhb = M('member_card_jfdhhb')->where(array('token'=>$this->token,'id'=>$id))->find();
			$this->assign('jfdhhb',$jfdhhb);
			$where['jid'] = $id;
			$where_page['id'] = $id;
		}
		$where['cardid'] = intval($_GET['cardid']);
		$where_page['cardid'] = intval($_GET['cardid']);
		if(!empty($_GET['search'])){
			$map['hongbao'] = (intval($_GET['search']))*100;
			$map['integral'] = intval($_GET['search']);
			$map['wechaname'] = array('like','%'.$_GET['search'].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
			$where_page['search'] = $_GET['search'];
		}
		$count = M('member_card_jfdhhb_record')->where($where)->count();
		$page = new Page($count,15);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$list = M('member_card_jfdhhb_record')->where($where)->order("addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign('list',$list);
		$this->display();
	}
	//导出核销记录
	public function exExcel(){
		$cardid 	= $this->_request('cardid','intval');
		$keyword 	= $this->_request('keyword','trim');
		
		$where 	= "record.token='".$this->token."' AND record.is_use='1'";

		if($cardid){
			$where 	.= " AND record.cardid=$cardid";
		}

		if($keyword){
			$code 	= str_replace("-","",$keyword);
			$where 	.= " AND (record.cancel_code LIKE '%$code%' OR coupon.title LIKE '%$keyword%')";
		}
        $list 	= M('Member_card_coupon_record')->alias('record')
	            ->join('LEFT JOIN '.C('DB_PREFIX').'member_card_coupon coupon ON coupon.id=record.coupon_id')
            ->where($where)
            ->field('record.id,record.wecha_id,record.staff_id,record.company_id,record.cancel_code,record.use_time,record.is_use,record.coupon_type,coupon.title,coupon.is_weixin')
            ->order('record.use_time desc')
            ->select();
			
        foreach ($list as $key => $val) {
        	if($val['company_id']){
        		$list[$key]['company_name'] 	= M('Company')->where(array('token'=>$this->token,'id'=>$val['company_id']))->getField('name');
        	}else{
        		$list[$key]['company_name'] 	= '所有门店';
        	}
        	if($val['staff_id']>0){
        		$list[$key]['staff_name'] 	= M('Company_staff')->where(array('token'=>$this->token,'id'=>$val['staff_id']))->getField('name');
        	}else if($val['staff_id'] == -1){
        		$list[$key]['staff_name'] 	= '用户核销';
        	}else if($val['staff_id'] == -2){
        		$list[$key]['staff_name'] 	= '后台核销';
        	}else{
        		$list[$key]['staff_name'] 	= '历史数据';
        	}
			$list[$key]['wechaname'] 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$val['wecha_id']))->getField('wechaname');
        }
		if(!empty($list)){
			$export = array();
			foreach($list as $key=>$val){
				$export[$key]['use_time'] = date('Y-m-d H:i:s',$val['use_time']);
				$export[$key]['cancel_code'] = "卡券".$val['cancel_code'];
				$export[$key]['title'] = $val['title'];
				if($val['coupon_type'] == 2){
					$coupon_type = '代金券';
				}elseif($val['coupon_type'] == 1){
					$coupon_type = '优惠券';
				}else{
					$coupon_type = '礼品券';
				}
				if($val['is_weixin'] == 1){
					$is_weixin = '(微)';
				}elseif($val['attr'] == 1){
					$is_weixin = '(赠)';
				}else{
					$is_weixin = '(普)';
				}
				$export[$key]['coupon_type'] = $coupon_type.$is_weixin;
				$export[$key]['company_name'] = $val['company_name'];
				$export[$key]['staff_name'] = $val['staff_name'];
			}
			$title = array('时间','卡券号','卡券名称','卡券类型','门店','核销员');
			$this->exportexcel($export,$title,'核销记录统计数据_'.date('YmdHis'));
		}else{
			$this->error('导出错误,没有获取到要导出的数据');
		}
	}

	function exportexcel($data=array(),$title=array(),$filename='report'){
    	header("Content-type:application/octet-stream");
    	header("Accept-Ranges:bytes");
    	header("Content-type:application/vnd.ms-excel");
    	header("Content-Disposition:attachment;filename=".$filename.".xls");
    	header("Pragma: no-cache");
    	header("Expires: 0");
    	//导出xls 开始
    	if (!empty($title)){
    		foreach ($title as $k => $v) {
    			$title[$k]=iconv("UTF-8", "GBK//IGNORE",$v);
    		}
    		$title= implode("\t", $title);
    		echo "$title\n";
    	}
    	if (!empty($data)){
    		foreach($data as $key=>$val){
    			foreach ($val as $ck => $cv) {
    				$data[$key][$ck]=iconv("UTF-8", "GBK//IGNORE", $cv);
    			}
    			
    			$data[$key]=implode("\t", $data[$key]);
    		}
    		echo implode("\n",$data);
    	}
    }
	public function grade()
	{
		$exists = D("Member_card_grade")->selectGrade(array("token" => $this->token));
		$this->assign("list", $exists);
		$this->display();
	}

	public function setgrade()
	{
		if (IS_POST) {
			$data = array();
			$data["grade_name"] = trim($_POST["grade_name"]);
			$data["status"] = intval($_POST["status"]);

			if (intval($_POST["id"])) {
				if (D("Member_card_grade")->saveGrade($data, array("id" => intval($_POST["id"])))) {
					$this->success("修改成功", U("Member_card/grade", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("修改失败");
				}
			}
			else if (D("Member_card_grade")->insertGrade($data, $this->token)) {
				$this->success("添加成功", U("Member_card/grade", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("添加失败");
			}
		}
		else if ($_GET["gradeid"]) {
			$set = D("Member_card_grade")->findGrade("id = " . intval($_GET["gradeid"]));

			if (!$set) {
				$this->error("非法操作");
			}

			$this->assign("set", $set);
		}

		$this->display();
	}

	public function del()
	{
		$id = (int) $_GET["gradeid"];

		if (D("Member_card_grade")->delGrade(array("id" => $id))) {
			$this->success("删除成功", U("Member_card/grade", array("token" => $this->token)));
			exit();
		}
		else {
			$this->error("删除失败");
		}
	}

	public function exportmembers()
	{
		$get = array_merge(array(), I("get."));
		$get["token"] = $this->token;
		$cardCreateModel = D("MemberCardCreate");
		$members = $cardCreateModel->getListNoSearch($get);

		if (!empty($members)) {
			$export = array();

			foreach ($members as $key => $value ) {
				$export[$key]["number"] = $value["number"];
				$export[$key]["truename"] = $value["truename"];
				$export[$key]["tel"] = ($value["tel"] != "" ? "tel:" . $value["tel"] : "");
				$export[$key]["getcardtime"] = (0 < $value["getcardtime"] ? date("Y-m-d H:i:s", $value["getcardtime"]) : "无时间记录");
				$export[$key]["total_score"] = $value["total_score"];
				$export[$key]["expensetotal"] = $value["expensetotal"];
				$export[$key]["balance"] = $value["balance"];
			}

			$title = array("卡号", "姓名", "联系电话", "领卡时间", "积分", "消费总额(元)", "余额(元)");
			$this->exportexcel($export, $title, "会员记录统计数据_" . date("YmdHis"));
		}
		else {
			$this->error("没有需要导出的记录");
		}
	}
}
?>