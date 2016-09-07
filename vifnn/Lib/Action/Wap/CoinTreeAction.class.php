<?php 
/**
* 摇钱树
**/
class CoinTreeAction extends CoinBaseAction{
	//首页
	public function index(){
		if($this->action_id == "" || $this->wecha_id == "" || $this->token == ""){
			$this->error('非法参数');
		}
		$this->checkTongji($this->token, "cointree", $this->action_id);
		$this->assign($this->token, "cointree", $this->action_id);
		$cointree_db = M('cointree');
 		if(S($this->token.'_'.$this->action_id.'_cointree') != ""){
			$cointree = S($this->token.'_'.$this->action_id.'_cointree');
		}else{
			$cointree = $cointree_db->where(array(C('DB_PREFIX').'cointree.id'=>$this->action_id))->join(C('DB_PREFIX').'cointree_prize on '.C('DB_PREFIX').'cointree.id = '.C('DB_PREFIX').'cointree_prize.cid')->find();
			S($this->token.'_'.$this->action_id.'_cointree',$cointree);
		}  
		if(empty($cointree)){ $this->error('活动不存在');}elseif($cointree['status'] == 0){ $this->error('活动已结束');}
		$stat = $this->public_notice($cointree);
		//访问者信息
		$conins=$cointree['is_once']=='1'?$cointree['usedup_conins']:0;
		$cointree_users = $this->addUser(array('sms_verify'=>$cointree['sms_verify'],'usedup_conins'=>$conins,'stat'=>$stat));
		session('sharekey_'.$this->wecha_id,$cointree_users['share_key']);
		//分享提示语和描述
		$cointree['custom_sharetitle'] = ($cointree['custom_sharetitle'] != "") ? str_replace(array('{{活动名称}}','{{金币数}}'),array($cointree['action_name'],$cointree_users['coins_count']),$cointree['custom_sharetitle']) : '我正在参加“'.$cointree['action_name'].'”活动，快来帮我集金币吧！';
		$cointree['custom_sharedsc'] = ($cointree['custom_sharedsc'] != "") ? str_replace('{{活动名称}}',$cointree['action_name'],$cointree['custom_sharedsc']) : $cointree['reply_content'];
		$cointree['id'] = $this->action_id;
		$this->assign('cointree',$cointree);
		$this->assign($cointree_users);
		//每日摇奖数清0
		$cache_data = array('token'=>$this->token,'action_id'=>$this->action_id,'wecha_id'=>$this->wecha_id);
		$this->clear_shake_day($cache_data);
		$this->display();
	}
	//分享页
	public function share_page(){
		$share_key = $this->_get('share_key','trim');
		if(S($this->token.'_'.$this->action_id.'_cointree') != ""){
			$cointree = S($this->token.'_'.$this->action_id.'_cointree');
		}else{
			$cointree = M('cointree')->where(array('id'=>$this->action_id))->find();
		}
		$stat = $this->public_notice($cointree);
		$this->checkTongji($this->token, "cointree", $this->action_id);
		$cointree_users = M('cointree_users')->where(array('cid'=>$this->action_id,'share_key'=>$share_key))->find();
		if(empty($cointree)){
			$this->error('该活动不存在');exit;
		}
		if(empty($cointree_users)){
			$this->error('非法参数');exit;
		}
		//进入分享时增加参与者
		$userofmine = M('cointree_users')->where(array('cid'=>$this->action_id,'wecha_id'=>$this->wecha_id))->find();
		if(($cointree['sms_verify'] == 1 && $cointree_users['isverify'] == 1) || ($cointree['sms_verify'] != 1)){
			if($share_key != "" && $this->wecha_id != "" && $userofmine['share_key'] != $share_key){
			$already_helper = M('cointree_shares')->where(array('share_key'=>$share_key,'share_wechaid'=>$this->wecha_id))->find();
			if(!$already_helper){
				$addcoins = $cointree['gain_conins'] ? (int)$cointree['gain_conins'] : 1;
				//添加分享用户
				$share = array(
					"share_wechaid" => $this->wecha_id,
					"share_wechaname" =>  !empty($this->fans['wechaname']) ? $this->fans['wechaname'] : "匿名用户" ,
					"share_wechapic" => !empty($this->fans['portrait']) ? $this->fans['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg',
					"share_key"=>$share_key,
					"addcoins"=> '+'.$addcoins,
					"opentime"=>$_SERVER['REQUEST_TIME'],
				);
				M('cointree_shares')->add($share);
				M('cointree_users')->where(array('cid'=>$this->action_id,'share_key'=>$share_key))->save(array('coins_count'=>array('exp','coins_count+'.$addcoins)));
				$this->assign('share_status','success');
			}else{
				$this->assign('share_status','error');
			}
			}else{
				$this->assign('share_status','error');
			}
		}else{
			$this->assign('share_status','error');
		}
		if($cointree['sms_verify'] == 1 && $this->fans['isverify'] != 1){
			$this->assign('needverify',1);
		}else{
			$this->assign('needverify',2);
		}
		if(!empty($userofmine)){
			$this->assign('already_join',1);
		}else{
			$this->assign('already_join',2);
		}
		$cointree['custom_sharetitle'] = ($cointree['custom_sharetitle'] != "") ? str_replace(array('我','{{活动名称}}','{{金币数}}'),array($cointree_users['wecha_name'],$cointree['action_name'],$cointree_users['coins_count']),$cointree['custom_sharetitle']) : $cointree_users.'正在参加“'.$cointree['action_name'].'”活动，快来帮我集金币吧！';
		$cointree['custom_sharedsc'] = ($cointree['custom_sharedsc'] != "") ? str_replace(array('我','{{活动名称}}'),array($cointree_users['wecha_name'],$cointree['action_name']),$cointree['custom_sharedsc']) : $cointree['reply_content'];
		$cointree['id'] = $this->action_id;
		$this->assign('share_key',$share_key);
		$this->assign('cointree',$cointree);
		$this->assign('addcoins',($cointree['gain_conins'] ? (int)$cointree['gain_conins'] : 1));
		$this->assign('cointree_users',$cointree_users);
		$this->assign('user_id',$userofmine['id']);//进入者的身份
		$this->display();
	}
	//ajax获取摇奖结果
	public function ajaxGetPrize(){
		$action_id = (int)$_POST['action_id'];
		$token = (string)$_POST['token'];
		if($action_id){
			$return_result = $this->getPrize($action_id,$token);
			if($return_result['code'] == 8){
				echo '{"status":"success","code":8,"msg":"'.$return_result['msg'].'","serialnumber":"'.$return_result['serialnumber'].'","leftcoin":"'.$return_result['leftcoin'].'"}';exit;
			}elseif($return_result['code'] == 9){
				echo '{"status":"error","code":9,"msg":"'.$return_result['msg'].'","leftcoin":"'.$return_result['leftcoin'].'"}';exit;
			}else{
				echo '{"status":"error","code":'.$return_result['code'].',"msg":"'.$return_result['msg'].'"}';exit;
			}
		}else{
			$msg = '摇奖失败';
			echo '{"status":"error","msg":"'.$msg.'"}';exit;
		}
	}
	//摇奖
	private function getPrize($action_id = '',$token = ''){
		$prizetype = '';
		$cointree_db = M('cointree');
		//不缓存,因为奖品数量和实际参与人数时刻在变
		$cointree = $cointree_db->where(array(C('DB_PREFIX').'cointree.id'=>$action_id))->join(C('DB_PREFIX').'cointree_prize on '.C('DB_PREFIX').'cointree.id = '.C('DB_PREFIX').'cointree_prize.cid')->find();
		if(empty($cointree)){
			return array('code'=>11,'msg'=>'活动不存在');
		}elseif($cointree['status'] == 0){
			return array('code'=>12,'msg'=>'活动已结束');
		}
		$cointree_users = M('cointree_users')->where(array('cid'=>$action_id,'wecha_id'=>$this->wecha_id))->find();
		if(empty($cointree) || empty($cointree_users)){
			return array('code'=>0,'msg'=>'摇奖失败');
		}
		if($cointree['sms_verify'] == 1 && $cointree_users['isverify'] != 1){
			return array('code'=>11,'msg'=>'您还未进行短信验证,请点击“我要参与”按钮验证手机号');
		}
		$todaytime = strtotime(date("Y-m-d 00:00:00",$_SERVER['REQUEST_TIME']));
		$win_record = M('cointree_record')->where(array('user_id'=>$cointree_users['id'],'iswin'=>1,'shaketime'=>array('gt',$todaytime)))->order('shaketime desc')->find();
		$lastshaketime = M('cointree_record')->where(array('user_id'=>$cointree_users['id']))->order('shaketime desc')->getField('shaketime');
		if(time() - $lastshaketime < 2){
			return array('code'=>22,'msg'=>'');
		}
		if($cointree['starttime'] > $_SERVER['REQUEST_TIME']){
			return array('code'=>3,'msg'=>'摇奖活动还未开始');
		}
		if($cointree['endtime'] < $_SERVER['REQUEST_TIME']){
			return array('code'=>4,'msg'=>'摇奖活动已经结束');
		}
		if(!empty($win_record) && $cointree['is_limitwin'] == 0){
			return array('code'=>6,'msg'=>'您在'.date("Y年m月d日H点i分",$win_record['shaketime']).'已经摇到过奖,请明天再来吧');
		}
		if($cointree['everydaytimes'] > 0 && $cointree_users['today_shakes'] >= $cointree['everydaytimes']){
			return array('code'=>1,'msg'=>'您今天的摇奖次数已经超限,请明天再来吧');
		}
		if($cointree_users['total_shakes'] >= $cointree['totaltimes']){
			return array('code'=>2,'msg'=>'您的摇奖次数已经用完');
		}
		if($cointree_users['coins_count'] - $cointree['usedup_conins'] < 0){
			return array('code'=>5,'msg'=>'您的金币不足,还差'.($cointree['usedup_conins'] - $cointree_users['coins_count']).'个');
		}
		//上次中奖时间在规定时间区间内
		if($cointree['timespan'] > 0 && (time() - $win_record['shaketime'] < $cointree['timespan']*60 )){
			$prizetype = 7;
		}else{
			$prizetype = $this->shakePrize($cointree);
		}
		switch($prizetype){ //摇到后将奖品数量减1
			case 1: 
				$cointree_db->where(array('id'=>$action_id))->setInc('fistlucknums');
				$iswin = 1;
				break;
			case 2: 
				$cointree_db->where(array('id'=>$action_id))->setInc('secondlucknums');
				$iswin = 1;
				break;
			case 3: 
				$cointree_db->where(array('id'=>$action_id))->setInc('thirdlucknums');
				$iswin = 1;
				break;
			case 4: 
				$cointree_db->where(array('id'=>$action_id))->setInc('fourlucknums');
				$iswin = 1;
				break;
			case 5: 
				$cointree_db->where(array('id'=>$action_id))->setInc('fivelucknums');
				$iswin = 1;
				break;
			case 6: 
				$cointree_db->where(array('id'=>$action_id))->setInc('sixlucknums');
				$iswin = 1;
				break;
			case 7:
				$iswin = 0;
		}
		if($prizetype){
			//增加实际参与人数
			$cointree_db->where(array('id'=>$action_id))->setInc('actual_join_number');
		}
		$record_data = array();
		$record_data['cid'] = $action_id;
		$record_data['user_id'] = $cointree_users['id'];
		if($prizetype != 7 && $iswin == 1){
			$record_data['serialnumber'] = uniqid();
		}
		$record_data['prize'] = $prizetype;
		$record_data['iswin'] = $iswin;
		$record_data['shaketime'] = $_SERVER['REQUEST_TIME'];
		$record_data['sendstutas'] = 0;
		$record_data['sendtime'] = 0;
		$record_data['phone'] = !empty($this->fans['tel']) ? $this->fans['tel'] : "no";
		$record_data['wecha_name'] = !empty($this->fans['wechaname']) ? $this->fans['wechaname'] : "匿名用户";
		$record_data['wecha_pic'] = !empty($this->fans['portrait']) ? $this->fans['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg';
		$record_data['token'] = $this->token;
		$record_add = M('cointree_record')->add($record_data);
		//消耗的金币，记录到分享表
		$share = array(
			"share_wechaid" => $this->wecha_id,
			"share_wechaname" =>  !empty($this->fans['wechaname']) ? $this->fans['wechaname'] : "匿名用户" ,
			"share_wechapic" => !empty($this->fans['portrait']) ? $this->fans['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg',
			"share_key"=>$cointree_users['share_key'],
			"addcoins"=> '-'.$cointree['usedup_conins'],
			"opentime"=>$_SERVER['REQUEST_TIME'],
		);
		M('cointree_shares')->add($share);
		$share_update = M('cointree_users')->where(array('cid'=>$action_id,'wecha_id'=>$this->wecha_id))->save(array('total_shakes'=>array('exp','total_shakes+1'),'today_shakes'=>array('exp','today_shakes+1'),'coins_count'=>array('exp','coins_count-'.$cointree['usedup_conins'])));
		if($record_add && $share_update){
			if($prizetype == 7){
				return array('code'=>9,'msg'=>'谢谢参与','leftcoin'=>($cointree_users['coins_count'] - $cointree['usedup_conins']));
			}else{
				$prize = $this->getPrizeName($cointree,$prizetype);
				return array('code'=>8,'msg'=>'恭喜你,摇到了'.$prizetype.'等奖,'.$prize['prizename'],'serialnumber'=>$record_data['serialnumber'],'leftcoin'=>($cointree_users['coins_count'] - $cointree['usedup_conins']));
			}
		}else{
			return array('code'=>7,'msg'=>'摇奖失败');
		}
	}
	//公共提示语
	public function public_notice($action_info = ''){
		$stat = true;
		//需要关注
		if($action_info['is_follow'] == 0 && $this->isSubscribe() == false) {
			$follow_msg = (!empty($action_info['follow_msg'])) ? $action_info['follow_msg'] : '';
			$custom_url = (!empty($action_info['custom_follow_url'])) ? $action_info['custom_follow_url'] : '';
			$custom_btn_msg = (!empty($action_info['follow_btn_msg'])) ? $action_info['follow_btn_msg'] : '';
			$this->memberNotice($follow_msg,1,$custom_url,$custom_btn_msg);
			$this->assign('notice_content','no_follow');
			$stat = false;
		//需要注册
		}elseif($action_info['is_register'] == 1 && empty($this->fans['tel']) && $action_info['sms_verify'] != 1){
			$custom_register_msg = (!empty($action_info['register_msg'])) ? $action_info['register_msg'] : '';
			$this->assign('notice_content','no_register');
			$this->memberNotice($custom_register_msg);
			$stat = false;
		}else{
			$this->assign('notice_content','');
		}
		//头部提示语
		$this->assign('remind_link',$this->getLink($action_info['remind_link']));
		return $stat;
	}
	//金币记录--主页和分享页
	public function ajaxCoinRecord(){
		$type = $_GET['type'] ? (string)$_GET['type'] : '';
		$i = 0;
		if($type== 'share_page'){
			$share_key = $this->_get('share_key','trim');
			if(empty($share_key)){ echo json_encode(array('status'=>'fail','data'=>''));}
			$coin_record = M('cointree_shares')->where(array('share_key'=>$share_key))->order('opentime desc')->limit(0,100)->select();
		}else{
			$coin_record = M('cointree_shares')->where(array('share_key'=>session('sharekey_'.$this->wecha_id)))->limit(0,100)->order('opentime desc')->select();
		}
		if(!empty($coin_record)){
			foreach($coin_record as $key=>$val){
				$coin_record[$key]['opentime'] = date('Y-m-d H:i:s',$val['opentime']);
				$i++;
			}
			echo json_encode(array('status'=>'ok','data'=>$coin_record,'count'=>$i));
		}else{
			echo json_encode(array('status'=>'fail','data'=>'','count'=>''));
		}
		return false;
	}
	//摇奖记录
	public function ajaxPrizeRecord(){
		$record_nums = $this->_post('record_nums','intval');
		$cid = $this->_post('id','intval');
		$token = $this->_post('token','trim');
		$user_id = $this->_post('user_id','intval');
		$record_nums = (intval($record_nums) > 0) ? (int)$record_nums : 20;
		$my_record = M('cointree_record')->where(array('cid'=>$cid,'token'=>$token,'iswin'=>1,'user_id'=>$user_id))->order('prize asc,shaketime desc')->select();
		$other_record = M('cointree_record')->where(array('cid'=>$cid,'token'=>$token,'iswin'=>1,'user_id'=>array('neq',$user_id)))->limit(0,$record_nums)->order('prize asc,shaketime desc')->select();
		if(!empty($my_record)){
			foreach($my_record as $key=>$val){
				$my_record[$key]['shaketime'] = date('Y-m-d H:i:s',$val['shaketime']);
			}
		}else{
			$my_record = '';
		}
		if(!empty($other_record)){
			foreach($other_record as $k=>$v){
				$other_record[$k]['shaketime'] = date('Y-m-d H:i:s',$v['shaketime']);
			}
		}else{
			$other_record = '';
		}
		if(empty($my_record) && empty($other_record)){
			echo json_encode(array('status'=>'fail','mydata'=>'','otherdata'=>''));
		}else{
			echo json_encode(array('status'=>'ok','mydata'=>$my_record,'otherdata'=>$other_record));
		}
		exit;
	}
	//短信验证
	public function MessageVerify(){
		if(IS_POST){
			if(empty($_POST['tel'])){
				 $this->error("手机号不能为空");
			}elseif(empty($_POST['verification_code'])) {
				$this->error("短信验证码不能为空");
			}elseif($_SESSION['coin_rand_num'][$_POST['tel']] == ""){
				$this->error("该手机号未被验证");
			}elseif($_SERVER['REQUEST_TIME'] - $_SESSION['coin_send_time'][$_POST['tel']] > 1800){
				$this->error("短信验证码过期失效,请重新获取");
			}elseif($_POST['verification_code'] != $_SESSION['coin_rand_num'][$_POST['tel']]){
				$this->error("短信验证码错误");
			}else{
				$post_array = array();
				$post_array['id'] = (int)$_POST['id'];
				$post_array['token'] = $_POST['token'];
				$post_array['usedup_conins'] = $_POST['usedup_conins'];
				if($post_array['id'] == "" || $post_array['token'] == ""){
					$this->error('参数错误');
				}
				$join = $this->JoinCoinTree($_POST['tel'],$post_array);
				if($join){
					$this->success('手机验证成功,',U('CoinTree/index',array('id'=>$post_array['id'],'token'=>$post_array['token'])));exit;
				}else{
					$this->error('手机号验证失败');exit;
				}
			}
		}
		$this->display();
	}
	public function SmsSend(){
		$mobile = $this->_post('mobile');
		 if(empty($mobile)){
			 exit('手机号不能为空');
		}
		$_SESSION['coin_rand_num'][$mobile]='';
		$_SESSION['coin_send_time'][$mobile] = '';
		$rand_num = rand(100000,999999);
		$_SESSION['coin_rand_num'][$mobile] = $rand_num;
		$_SESSION['coin_send_time'][$mobile] = $_SERVER['REQUEST_TIME'];
		$params = array();
		$params['sms'] = array('token'=>$this->token, 'mobile'=>$mobile,'content'=>'您的验证码是：'.$rand_num.'。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
		$return_status = MessageFactory::method($params, 'SmsMessage');
		if($return_status == 0 && strlen($return_status) == 1){exit('done');}elseif($return_status == null){exit('not_buy');}else{exit('短信发送失败,请稍后再试');}
	}
	//增加摇奖者
	private function addUser($arguments = ''){
		if($this->wecha_id != "" && $arguments['stat'] == true){
			$userinfo = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
			$cointree_users = M('cointree_users')->where(array('cid'=>$this->action_id,'wecha_id'=>$this->wecha_id))->find();
			if($arguments['usedup_conins'] != ""){
				//关闭短信验证并且关注和注册了加金币
				if($arguments['sms_verify'] == 0){
					$coins_count = $arguments['usedup_conins'];
				//开启了短信验证并且验证通过了并且关注和注册了加金币
				}elseif($arguments['sms_verify'] == 1 && $userinfo['isverify'] == 1){
					$coins_count = $arguments['usedup_conins'];
				}else{
					$coins_count = 0;
				}
			}else{
				$coins_count = 0;
			}
			if(empty($cointree_users)){
				$data = array(
					'cid' => (int) $this->action_id ,
					'total_shakes'=>0,
					'today_shakes'=>0,
					'wecha_id' => $this->wecha_id,
					'wecha_name'=> !empty($userinfo['wechaname']) ? $userinfo['wechaname'] : "匿名用户" ,
					'wecha_pic' => !empty($userinfo['portrait']) ? $userinfo['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg',
					'phone'=> !empty($userinfo['tel']) ? $userinfo['tel'] : "no" ,
					'share_key'=>substr(md5(time().mt_rand(1000,9999).$this->wecha_id),0,32),
					'addtime' => $_SERVER['REQUEST_TIME'],
					'token' => $this->token,
					'coins_count'=> $coins_count,
					'isverify'=>$userinfo['isverify'],
				);
				$user_id = M('cointree_users')->add($data);
				//如果有金币，记录到日志
				if($user_id && $coins_count > 0){	
					$coins_add = array(
					"share_wechaid" => $this->wecha_id,
					"share_wechaname" =>  $data['wecha_name'],
					"share_wechapic" => $data['wecha_pic'],
					"share_key"=> $data['share_key'],
					"addcoins"=> '+'.$coins_count,
					"opentime"=>$_SERVER['REQUEST_TIME'],
					);
					M('cointree_shares')->add($coins_add);
				}
				$data['id'] = $user_id;
				return $data;
			}else{//首页的我要参与
				if(!empty($userinfo)){
					$update_data = array();
					$update_data['phone'] = $userinfo['tel'];
					$update_data['wecha_name'] = $userinfo['wechaname'];
					$update_data['wecha_pic'] = $userinfo['portrait'];
					$update_data['isverify'] = $userinfo['isverify'];
					M('cointree_users')->where(array('cid'=>$this->action_id,'token'=>$this->token,'wecha_id'=>$this->wecha_id))->save($update_data);
					$cointree_users['phone'] = $userinfo['tel'];
					$cointree_users['wecha_name'] = $userinfo['wechaname'];
					$cointree_users['wecha_pic'] = $userinfo['portrait'];
					$cointree_users['isverify'] = $userinfo['isverify'];
					$cointree_users['coins_count'] = $cointree_users['coins_count'];
				}
				return $cointree_users;
			}
		}else{
			return false;
		}
	}
	//开启验证时
	private function JoinCoinTree($phone = '',$post_array =''){
		if($this->wecha_id != ""){
			S($post_array['token'].'_'.$post_array['id'].'_cointree',null);
			$userinfoWhere = array('token'=>$post_array['token'],'wecha_id'=>$this->wecha_id);
			$update_phone = M('userinfo')->where($userinfoWhere)->save(array('tel'=>$phone,'isverify'=>1));
			if($update_phone){
				$user =  M('cointree_users')->where(array('cid'=>$post_array['id'],'wecha_id'=>$this->wecha_id))->find();
				if($user){
					//首页的我要参与，已经存在用户直接更新金币和增加金币记录
					$update_user = M('cointree_users')->where(array('cid'=>$post_array['id'],'wecha_id'=>$this->wecha_id))->save(array('coins_count'=>$post_array['usedup_conins']));
					if($update_user && $post_array['usedup_conins'] > 0){
						$coins_add = array(
						"share_wechaid" => $this->wecha_id,
						"share_wechaname" =>  !empty($this->fans['wechaname']) ? $this->fans['wechaname'] : "匿名用户" ,
						"share_wechapic" => !empty($this->fans['portrait']) ? $this->fans['portrait'] : $this->siteUrl.'/tpl/User/default/common/images/portrait.jpg',
						"share_key"=> $user['share_key'],
						"addcoins"=> '+'.$post_array['usedup_conins'],
						"opentime"=>$_SERVER['REQUEST_TIME'],
						);
						M('cointree_shares')->add($coins_add);
					}
				}
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//每日摇奖数清0
	private function clear_shake_day($cache_parameter = ''){
		$token = $cache_parameter['token'];
		$action_id = $cache_parameter['action_id'];
		$wecha_id = $cache_parameter['wecha_id'];
		if($token != "" && $action_id != "" && $wecha_id != ""){
			if(S($token.'_'.$action_id.'_'.$wecha_id.'_shakeday') == ""){
				$today_time = strtotime(date('Y-m-d 00:00:00',$_SERVER['REQUEST_TIME']));
				$evening_time = strtotime(date('Y-m-d 23:59:59',$_SERVER['REQUEST_TIME']));
				$cache_time = $evening_time - $_SERVER['REQUEST_TIME'];
				$where = "cid = {$action_id} and token = '{$token}' and wecha_id = '{$wecha_id}'";
				$cointree_users = M('cointree_users')->where($where)->find();
				if(!empty($cointree_users)){
					M('cointree_users')->where($where)->save(array('today_shakes'=>0));
				}
				S($token.'_'.$action_id.'_'.$wecha_id.'_shakeday',1,$cache_time);
			}
		}
	}
	public function test(){
		dump($_SESSION['coin_rand_num']);
	}
}
?>