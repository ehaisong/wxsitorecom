<?php
class PintuanReturnAction extends Action{
	public $wecha_id;
	public $token;
	public $site_url;
	public $wxuser;
	public $sign_data;
	public function _initialize(){
		$this->token = $_GET['token'];
		if($this->token == null){
			$data['err_code'] = 1;
			$data['err_msg'] = "获取token失败";
			$this->ajaxReturn($data,'JSON');exit;
		}
		$this->wecha_id = $_SESSION['pintuan_'.$this->token.'_wecha_id'] ? $_SESSION['pintuan_'.$this->token.'_wecha_id'] : $_COOKIE['pintuan_'.$this->token.'_wecha_id'];
		if($this->wecha_id == null){
			$data['err_code'] = 2;
			$data['err_msg'] = "获取wecha_id失败";
			$this->ajaxReturn($data,'JSON');exit;
		}
		$this->wxuser = M('wxuser')->where(array('token'=>$this->token))->find();
		if($this->wxuser['winxintype']!=3){
			$this->wxuser['appid'] = C("appid") ;
			$this->wxuser['appsecret'] = C("secret") ;
		}
		$this->site_url = $_SESSION['pintuan_'.$this->token.'_siteurl'] ? $_SESSION['pintuan_'.$this->token.'_siteurl'] : $_COOKIE['pintuan_'.$this->token.'_siteurl'];
		$this->site_url = trim($this->site_url,'"');
		//删除超5分钟未支付订单并返回库存
		$timeout_order = M('pintuan_order')->where(array('token'=>$this->token,'paid'=>0,'addtime'=>array('lt',(time() - 5*60))))->select();
		foreach($timeout_order as $time){
			M('pintuan')->where(array('token'=>$this->token,'id'=>$time['tid']))->setInc('quantity',$time['num']);
			M('pintuan_team')->where(array('token'=>$this->token,'head_id'=>$time['id'],'tid'=>$time['tid']))->delete();
			M('pintuan_order')->where(array('token'=>$this->token,'id'=>$time['id']))->delete();
		}
		if (empty($this->wxuser['is_syn'])) {
			$apiOauth 		= new apiOauth();
			$access_token  	= $apiOauth->update_authorizer_access_token($this->wxuser['appid'],$this->wxuser);
			$ticket 		= $apiOauth->getAuthorizerTicket($this->wxuser['appid'],$access_token);
		} else {
			$domain = M('Users')->where(array('id'=>$this->wxuser['uid']))->getField('source_domain');
			$url = $domain.A('Home/Auth')->getCallbackUrl($this->wxuser['is_syn'], 'share');
			$json = HttpClient::getInstance()->get($url);
			$json = json_decode($json, true);
			$ticket = $json['ticket'];
			$this->wxuser['appid'] = $json['appid'];
		}
		//$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$url = urldecode($_GET['url']);
		$this->sign_data = $this->addSign($ticket,$url);
	}
	public function mainReturn(){
		$data['err_code'] = 0;
		/*$classify = M('pintuan_classify')->where(array('token'=>$this->token))->order('sort desc')->select();
		foreach($classify as $ck=>$cv){
			$data['err_msg']['product_group_list'][$ck]['group_id'] = $cv['id'];
			$data['err_msg']['product_group_list'][$ck]['group_name'] = $cv['name'];
		}*/
		$pagecount = 10;
		$where['token'] = $this->token;
		/*if($_GET['group_id']){
			$where['classify'] = intval($_GET['group_id']);
		}*/
		$page = intval($_GET['page']);
		$page = $page?($page-1):$page;
		$where['display'] = 2;
		$where['enddate'] = array('gt',time());
		$where['startdate'] = array('lt',time());
		$count = M('pintuan')->where($where)->count();
		$pintuan_list = M('pintuan')->where($where)->order('sort desc,addtime desc')->limit(($pagecount*$page),$pagecount)->select();
		$data['err_msg']['tuan_list'] = array();
		foreach($pintuan_list as $pk=>$pv){
			$config = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$pv['id']))->order('discount')->find();
			$data['err_msg']['tuan_list'][$pk] = array(
				'tuan_id' => $pv['id'],
				'name' => $pv['title'],
				'store_id' => $this->token,
				'start_time' => $pv['startdate'],
				'end_time' => $pv['enddate'],
				'price' => $pv['price']/100,
				'image' => $pv['facepic'],
				'config_1' => array(
					'number' => $config['number'],
					'discount' => $pv['price']*$config['discount']/10000
				),
				'config_2' => array(
					'number' => $config['number'],
					'discount' => $config['discount']/10
				)
			);
		}
		$pintuan_reply = M('pintuan')->where(array('token'=>$this->token))->find();
		$data['err_msg']['title'] = $pintuan_reply['title'];
		if(intval($_GET['page']?$_GET['page']:1) >= ceil(($count/$pagecount))){
			$data['err_msg']['next_page'] = false;
		}else{
			$data['err_msg']['next_page'] = true;
		}
		$sign_data = $this->sign_data;
		$data['err_msg']['share_data']['appId'] = $sign_data['appId'];
		$data['err_msg']['share_data']['timestamp'] = $sign_data['timestamp'];
		$data['err_msg']['share_data']['nonceStr'] = $sign_data['nonceStr'];
		$data['err_msg']['share_data']['signature'] = $sign_data['signature'];
		$this->ajaxReturn($data,'JSON');exit;
	}
	public function infoReturn(){
		$data['err_code'] = 0;
		$pintuan = M('pintuan')->where(array('token'=>$this->token,'id'=>intval($_GET['tuan_id'])))->find();
		if($pintuan){
			$data['err_msg']['product']['name'] = $pintuan['title'];
			$data['err_msg']['product']['price'] = $pintuan['price']/100;
			$data['err_msg']['product']['image'] = $pintuan['facepic'];
			$tuan_count = M('pintuan_order')->where(array('token'=>$this->token,'tid'=>$pintuan['id'],'paid'=>1))->count();
			$data['err_msg']['product']['sales'] = $tuan_count;
			$data['err_msg']['product']['info'] = htmlspecialchars_decode($pintuan['goods_info']);
			$image_list = M('pintuan_changepic')->where(array('token'=>$this->token,'tid'=>intval($_GET['tuan_id'])))->select();
			foreach($image_list as $ik=>$iv){
				$data['err_msg']['product_image_list'][$ik]['image'] = $iv['pic'];
			}
			if($pintuan['startdate'] > $_SERVER['REQUEST_TIME']){
				$status =  1; //未开始
			}elseif($pintuan['enddate'] < $_SERVER['REQUEST_TIME'] || $pintuan['display'] == 1){
				$status = 3; //结束
			}else{
				$status = 2; //进行中
			}
			$data['err_msg']['tuan']['tuan_id'] = intval($_GET['tuan_id']);
			$data['err_msg']['tuan']['name'] = $pintuan['title'];
			$data['err_msg']['tuan']['store_id'] = $this->token;
			$data['err_msg']['tuan']['start_time'] = $pintuan['startdate'];
			$data['err_msg']['tuan']['end_time'] = $pintuan['enddate'];
			$data['err_msg']['tuan']['status'] =  $status;
			$data['err_msg']['tuan']['present_time'] = $_SERVER['REQUEST_TIME'];
			$data['err_msg']['tuan']['description'] = htmlspecialchars_decode($pintuan['tuan_info']);
			$config_list = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>intval($_GET['tuan_id'])))->order('discount desc')->select();
			foreach($config_list as $ck=>$cv){
				$data['err_msg']['tuan_config_list'][$ck]['item_id'] = $cv['id'];
				$data['err_msg']['tuan_config_list'][$ck]['number'] = $cv['number'];
				$data['err_msg']['tuan_config_list'][$ck]['discount'] = $cv['discount']/10;
				$data['err_msg']['tuan_config_list'][$ck]['price'] = $pintuan['price']*$cv['discount']/10000;
			}
			$data['err_msg']['title'] = $pintuan['title'];
		}else{
			$data['err_msg']['product'] = array();
			$data['err_msg']['product_image_list'] = array();
			$data['err_msg']['tuan'] = array();
			$data['err_msg']['tuan_config_list'] = array();
			$data['err_msg']['title'] = '';
		}
		$team_id = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'tid'=>intval($_GET['tuan_id']),'ishead'=>1,'paid'=>1))->getField('team_id');
		$team_id = $team_id ? (int)$team_id : 0;
		$sign_data = $this->sign_data;
		$pintuan['custom_sharetitle'] = ($pintuan['custom_sharetitle'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharetitle']) : '我正在参加“'.$pintuan['title'].'”拼团购活动，快来参与吧！';
		$pintuan['custom_sharedsc'] = ($pintuan['custom_sharedsc'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharedsc']) : $pintuan['info'];
		$data['err_msg']['share_data']['custom_sharetitle'] = shareFilter($pintuan['custom_sharetitle']);
		$data['err_msg']['share_data']['custom_sharedsc'] = shareFilter($pintuan['custom_sharedsc']);
		$data['err_msg']['share_data']['share_img'] = $image_list[0]['pic'];
		$data['err_msg']['share_data']['appId'] = $sign_data['appId'];
		$data['err_msg']['share_data']['timestamp'] = $sign_data['timestamp'];
		$data['err_msg']['share_data']['nonceStr'] = $sign_data['nonceStr'];
		$data['err_msg']['share_data']['url'] = $this->site_url.U('Wap/Pintuan/share',array('token'=>$this->token,'team'=>$team_id,'tuan_id'=>intval($_GET['tuan_id'])));
		$data['err_msg']['share_data']['signature'] = $sign_data['signature'];
		$this->ajaxReturn($data,'JSON');exit;
	}
	public function buyReturn(){
		$data['err_code'] = 0;
		$tuan_id = intval($_GET['tuan_id']);
		$type = intval($_GET['type']);
		$item_id = intval($_GET['item_id']);
		$team_id = intval($_GET['team_id']);
		$pintuan = M('pintuan')->where(array('token'=>$this->token,'id'=>$tuan_id))->find();
		$data['err_msg']['tuan']['tuan_id'] = $tuan_id;
		$data['err_msg']['tuan']['name'] = $pintuan['title'];
		$data['err_msg']['tuan']['store_id'] = $this->token;
		$config_list = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>intval($_GET['tuan_id'])))->order('discount desc')->select();
		$team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$team_id,'tid'=>$tuan_id))->find();
		$jilu_guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$tuan_id,'id'=>$team['guize_id']))->find();
		$team_owner = M('pintuan_order')->where(array('id'=>$team['head_id']))->find();
		if($pintuan['startdate'] > time()){
			$data['err_msg']['tuan']['status'] = 1; //未开始
		}elseif($pintuan['enddate'] < time()){ //结束后
			if($team['count'] >= $jilu_guize['number']){
				$data['err_msg']['tuan']['status'] = 3;
			}elseif($pintuan['display'] == 1){
				$data['err_msg']['tuan']['status'] = 3;
			}else{
				$data['err_msg']['tuan']['status'] = 4;
			}
		}else{ //进行中
			$data['err_msg']['tuan']['status'] = 2;
		}
		$data['err_msg']['tuan']['start_time'] = $pintuan['startdate'];
		$data['err_msg']['tuan']['end_time'] = $pintuan['enddate'];
		$data['err_msg']['tuan']['present_time'] = $_SERVER['REQUEST_TIME'];
		$data['err_msg']['tuan']['description'] = htmlspecialchars_decode($pintuan['tuan_info']);
		
		if($team_id > 0){
			$this_guize = array();
			foreach($config_list as $con){
				if($con['discount'] <= $jilu_guize['discount'] && $team['count'] >= $con['number']){
					$this_guize[] = $con;
				}
			}
			if(count($this_guize) > 0){
				$guize = $this_guize[0];
			}else{
				$guize = $jilu_guize;
			}
			$data['err_msg']['tuan_config']['number'] = $guize['number'];
			$data['err_msg']['tuan_config']['count'] = $team['count'];
		}else{
			$team_id = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'tid'=>intval($_GET['tuan_id']),'ishead'=>1,'paid'=>1))->getField('team_id');
			if($type > 0){
				$guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$tuan_id,'id'=>$item_id))->find();
			}else{
				$guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$tuan_id))->order('discount desc')->find();
			}
			$data['err_msg']['tuan_config']['number'] = $guize['number'];
			$data['err_msg']['tuan_config']['count'] = 0;
		}
		//是否是团长
		$data['err_msg']['tuan_config']['iscolonel'] = (!empty($team_owner) && $team_owner['wecha_id'] == $this->wecha_id) ? true : false;
		$data['err_msg']['product']['name'] = $pintuan['title'];
		$data['err_msg']['product']['price'] = $pintuan['price']/100;
		$data['err_msg']['product']['image'] = $pintuan['facepic'];
		$data['err_msg']['product']['quantity'] = $pintuan['quantity'];
		$data['err_msg']['product']['has_property'] = 1;
		$data['err_msg']['product']['min_price'] = $pintuan['price']*$guize['discount']/10000;
		$data['err_msg']['product']['max_price'] = $pintuan['price']*$guize['discount']/10000;
		$data['err_msg']['product']['info'] = htmlspecialchars_decode($pintuan['goods_info']);
		$data['err_msg']['title'] = $pintuan['title'];
		$image_list = M('pintuan_changepic')->where(array('token'=>$this->token,'tid'=>$tuan_id))->select();
		foreach($image_list as $ik=>$iv){
			$data['err_msg']['product_image_list'][$ik]['image'] = $iv['pic'];
		}
		
		foreach($config_list as $ck=>$cv){
			$data['err_msg']['tuan_config_list'][$ck]['item_id'] = $cv['id'];
			$data['err_msg']['tuan_config_list'][$ck]['number'] = $cv['number'];
			$data['err_msg']['tuan_config_list'][$ck]['discount'] = $cv['discount']/10;
			$data['err_msg']['tuan_config_list'][$ck]['price'] = $pintuan['price']*$cv['discount']/10000;
		}
			$sign_data = $this->sign_data;
			$pintuan['custom_sharetitle'] = ($pintuan['custom_sharetitle'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharetitle']) : '我正在参加“'.$pintuan['title'].'”拼团购活动，快来参与吧！';
			$pintuan['custom_sharedsc'] = ($pintuan['custom_sharedsc'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharedsc']) : $pintuan['info'];
			$data['err_msg']['share_data']['custom_sharetitle'] = shareFilter($pintuan['custom_sharetitle']);
			$data['err_msg']['share_data']['custom_sharedsc'] = shareFilter($pintuan['custom_sharedsc']);
			$data['err_msg']['share_data']['share_img'] = $pintuan['facepic'];
			$data['err_msg']['share_data']['appId'] = $sign_data['appId'];
			$data['err_msg']['share_data']['timestamp'] = $sign_data['timestamp'];
			$data['err_msg']['share_data']['nonceStr'] = $sign_data['nonceStr'];
			$data['err_msg']['share_data']['url'] = $this->site_url.U('Wap/Pintuan/share',array('token'=>$this->token,'team'=>$team_id,'tuan_id'=>$tuan_id));
			$data['err_msg']['share_data']['signature'] = $sign_data['signature'];
		$this->ajaxReturn($data,'JSON');exit;
		
	}
	public function addSign($ticket,$url){
		$timestamp = time();
		$nonceStr  = rand(100000,999999);
		$array 	= array(
			"noncestr"		=> $nonceStr,		
			"jsapi_ticket"	=> $ticket,
			"timestamp"		=> $timestamp,
			"url"			=> $url,
		);

		ksort($array);
		$signPars	= '';
	
		foreach($array as $k => $v) {
			if("" != $v && "sign" != $k) {
				if($signPars == ''){
					$signPars .= $k . "=" . $v;
				}else{
					$signPars .=  "&". $k . "=" . $v;
				}
			}
		}
		
		$result = array(
			'appId' 	=> $this->wxuser['appid'],
			'timestamp' => $timestamp,
			'nonceStr'  => $nonceStr,
			'url' 		=> $url,
			'signature' => SHA1($signPars),
		);
		
		return $result;
	}
	public function buylistReturn(){
		$data['err_code'] = 0;
		$tuan_id = intval($_GET['tuan_id']);
		$team_id = intval($_GET['team_id']);
		$pagecount = 10;
		$page = intval($_GET['page']);
		$page = $page?($page-1):$page;
		if($team_id > 0){
			$count = M('pintuan_order')->where(array('token'=>$this->token,'tid'=>$tuan_id,'paid'=>1,'team_id'=>$team_id))->count();
			$order_list = M('pintuan_order')->where(array('token'=>$this->token,'tid'=>$tuan_id,'paid'=>1,'team_id'=>$team_id))->order('addtime desc')->limit(($pagecount*$page),$pagecount)->select();
			
		}else{
			$count = M('pintuan_order')->where(array('token'=>$this->token,'tid'=>$tuan_id,'paid'=>1))->count();
			$order_list = M('pintuan_order')->where(array('token'=>$this->token,'tid'=>$tuan_id,'paid'=>1))->order('addtime desc')->limit(($pagecount*$page),$pagecount)->select();
		}
		$pintuan = M('pintuan')->where(array('token'=>$this->token,'id'=>$tuan_id))->find();
		foreach($order_list as $ok=>$ov){
			$data['err_msg']['order_list'][$ok]['order_id'] = $ov['id'];
			$data['err_msg']['order_list'][$ok]['uid'] = $ov['wecha_id'];
			$data['err_msg']['order_list'][$ok]['pro_num'] = $ov['num'];
			$this_team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$ov['team_id']))->find();
			$data['err_msg']['order_list'][$ok]['data_type'] = $this_team['type'];
			$data['err_msg']['order_list'][$ok]['add_time'] = $ov['addtime'];
			$data['err_msg']['order_list'][$ok]['is_leader'] = $ov['ishead']?true:false;
			$this_userinfo = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$ov['wecha_id']))->find();
			$data['err_msg']['order_list'][$ok]['avatar'] = $this_userinfo['portrait'];
			$data['err_msg']['order_list'][$ok]['nickname'] = $this_userinfo['wechaname'];
			$data['err_msg']['user_list'][$ov['wecha_id']]['avatar'] = $this_userinfo['portrait'];
			$data['err_msg']['user_list'][$ov['wecha_id']]['nickname'] = $this_userinfo['wechaname'];
		}
		if(intval($_GET['page']?$_GET['page']:1) >= ceil(($count/$pagecount))){
			$data['err_msg']['next_page'] = false;
		}else{
			$data['err_msg']['next_page'] = true;
		}
		$sign_data = $this->sign_data;
		$pintuan['custom_sharetitle'] = ($pintuan['custom_sharetitle'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharetitle']) : '我正在参加“'.$pintuan['title'].'”拼团购活动，快来参与吧！';
		$pintuan['custom_sharedsc'] = ($pintuan['custom_sharedsc'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharedsc']) : $pintuan['info'];
		$data['err_msg']['share_data']['custom_sharetitle'] = shareFilter($pintuan['custom_sharetitle']);
		$data['err_msg']['share_data']['custom_sharedsc'] = shareFilter($pintuan['custom_sharedsc']);
		$data['err_msg']['share_data']['share_img'] = $pintuan['facepic'];
		$data['err_msg']['share_data']['appId'] = $sign_data['appId'];
		$data['err_msg']['share_data']['timestamp'] = $sign_data['timestamp'];
		$data['err_msg']['share_data']['nonceStr'] = $sign_data['nonceStr'];
		$data['err_msg']['share_data']['signature'] = $sign_data['signature'];
		$this->ajaxReturn($data,'JSON');exit;
	}
	public function payReturn(){
		if(IS_POST){
			$tuan_id = intval($_GET['tuan_id']);
			$num = intval($_GET['quantity']);
			$type = intval($_GET['type']);
			$team_id = intval($_GET['team_id']);
			$pintuan = M('pintuan')->where(array('token'=>$this->token,'id'=>$tuan_id))->find();
			if($pintuan['startdate'] > time()){
				$data['err_code'] = 0;
				$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'start','tid'=>$tuan_id));
				$data['err_msg']['url'] = $url;
			}elseif($pintuan['enddate'] < time()){
				$data['err_code'] = 0;
				$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'end','tid'=>$tuan_id));
				$data['err_msg']['url'] = $url;
			}elseif($pintuan['display'] == 1){
				$data['err_code'] = 0;
				$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'close','tid'=>$tuan_id));
				$data['err_msg']['url'] = $url;
			}elseif($num > $pintuan['quantity']){
				$data['err_code'] = 0;
				$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'nonum','tid'=>$tuan_id));
				$data['err_msg']['url'] = $url;
			}else{
				if($type > 0){
					$item_id = intval($_GET['item_id']);
					$guize = M('pintuan_guize')->where(array('token'=>$this->token,'id'=>$item_id))->find();
				}else{
					$guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$tuan_id))->order('discount desc')->find();
					$item_id = $guize['id'];
				}
				if($team_id > 0){ //参团
					$team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$team_id))->find();
					$head_order = M('pintuan_order')->where(array('id'=>$team['head_id']))->find();
					$myorder = M('pintuan_order')->where(array('team_id'=>$team_id,'wecha_id'=>$this->wecha_id))->find();
					//获取该团规则允许的最多人数
					$maxnumber = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$tuan_id))->max('number');
					//获取已下单人数
					$orderNum = M('pintuan_order')->where(array('team_id'=>$team_id))->count();
					if($this->wecha_id == $head_order['wecha_id']){
						$data['err_code'] = 0;
						$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'head','tid'=>$tuan_id));
						$data['err_msg']['url'] = $url;
					}elseif($myorder != '' && $myorder['paid'] == 1){ // 支付成功才算参加此团
						$data['err_code'] = 0;
						$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'myorder','tid'=>$tuan_id));
						$data['err_msg']['url'] = $url;
					}elseif($orderNum >= $maxnumber && $pintuan['isopenlimit'] == 2){
						$data['err_code'] = 0;
						$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'overnumer','tid'=>$tuan_id));
						$data['err_msg']['url'] = $url;
					}else{
						$jilu_guize = M('pintuan_guize')->where(array('token'=>$this->token,'id'=>$team['guize_id']))->find();
						$order_add = array(
							'token' => $this->token,
							'wecha_id' => $this->wecha_id,
							'team_id' => $team_id,
							'tid' => $tuan_id,
							'num' => $num,
							'ishead' => 0,
							'price' => ($pintuan['price']*$jilu_guize['discount']/10000)*$num,
							'addtime' => time(),
							'goods_name' => $pintuan['title'],
							'goods_pic' => $pintuan['facepic'],
							'jilu_number' => $jilu_guize['number'],
							'jilu_enddate' => $pintuan['enddate']
						);
						$order_id = M('pintuan_order')->add($order_add);
						$orderid = $order_id.'PINGTUAN'.time();
						M('pintuan_order')->where(array('id'=>$order_id))->save(array('orderid'=>$orderid));
						M('pintuan')->where(array('token'=>$this->token,'id'=>$tuan_id))->setDec('quantity',$num);
						$data['err_code'] = 0;
						$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>$order_id,'tid'=>$tuan_id));
						$data['err_msg']['url'] = $url;
					}
				}else{ //团长开团
					$ishead_count = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'tid'=>$tuan_id,'ishead'=>1,'paid'=>1))->count();
					if($ishead_count > 0){
						$data['err_code'] = 0;
						$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>'ishead','tid'=>$tuan_id));
						$data['err_msg']['url'] = $url;
					}else{
						$team_data = array(
							'token' => $this->token,
							'tid' => $tuan_id,
							'count' => 1,
							'type' => $type,
							'guize_id' => $item_id,
							'addtime' => time()
						);
						$team_id = M('pintuan_team')->add($team_data);
						
						$order_add = array(
							'token' => $this->token,
							'wecha_id' => $this->wecha_id,
							'team_id' => $team_id,
							'tid' => $tuan_id,
							'num' => $num,
							'ishead' => 1,
							'price' => ($pintuan['price']*$guize['discount']/10000)*$num,
							'addtime' => time(),
							'goods_name' => $pintuan['title'],
							'goods_pic' => $pintuan['facepic'],
							'jilu_number' => $guize['number'],
							'jilu_enddate' => $pintuan['enddate']
						);
						$order_id = M('pintuan_order')->add($order_add);
						
						M('pintuan_team')->where(array('id'=>$team_id))->save(array('head_id'=>$order_id));
						$orderid = $order_id.'PINGTUAN'.time();
						M('pintuan_order')->where(array('id'=>$order_id))->save(array('orderid'=>$orderid));
						M('pintuan')->where(array('token'=>$this->token,'id'=>$tuan_id))->setDec('quantity',$num);
						$data['err_code'] = 0;
						$url = $this->site_url.U('Wap/Pintuan/buyinfo',array('token'=>$this->token,'orderid'=>$order_id,'tid'=>$tuan_id));
						$data['err_msg']['url'] = $url;
					}
				}
			}
		}else{
			$data['err_code'] = 666;
			$data['err_msg'] = "非法操作";
		}
		$this->ajaxReturn($data,'JSON');exit;
	}
	public function myorderReturn(){
		$data['err_code'] = 0;
		$type = intval($_GET['type']);
		$pagecount = 10;
		$page = intval($_GET['page']);
		$page = $page?($page-1):$page;
		if($type == 0){
			$count = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paid'=>1))->count;
			$order_list = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paid'=>1))->order('addtime desc')->limit(($pagecount*$page),$pagecount)->select();
		}elseif($type == 1){ //进行中
			$count = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paid'=>1,'jilu_enddate'=>array('gt',time())))->count;
			$order_list = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paid'=>1,'jilu_enddate'=>array('gt',time())))->order('addtime desc')->limit(($pagecount*$page),$pagecount)->select();
		}elseif($type == 2){  //成功的拼团
			$count = 0;
			$order_list = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paid'=>1,'jilu_enddate'=>array('lt',time())))->order('addtime desc')->limit(($pagecount*$page),$pagecount)->select();
			foreach($order_list as $lk=>$lv){
				$this_team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$lv['team_id']))->find();
				if($this_team['type'] > 0){
					$this_guize = M('pintuan_guize')->where(array('token'=>$this->token,'id'=>$this_team['guize_id']))->find();
				}else{
					//人缘开团取最小人数
					$this_guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$this_team['tid']))->order('number asc')->find();
				}
				if($this_team['count'] < $this_guize['number']){
					unset($order_list[$lk]);
				}
			}
		}elseif($type == 3){   //失败的拼团
			$count = 0;
			$order_list = M('pintuan_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'paid'=>1,'jilu_enddate'=>array('lt',time())))->order('addtime desc')->limit(($pagecount*$page),$pagecount)->select();
			foreach($order_list as $lk=>$lv){
				$this_team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$lv['team_id']))->find();
				if($this_team['type'] > 0){
					$this_guize = M('pintuan_guize')->where(array('token'=>$this->token,'id'=>$this_team['guize_id']))->find();
				}else{
					//人缘开团取最小人数
					$this_guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$this_team['tid']))->order('number asc')->find();
				}
				if($this_team['count'] >= $this_guize['number']){
					unset($order_list[$lk]);
				}
			}
		}
		$ii = 0;
		foreach($order_list as $ov){
			$data['err_msg']['order_list'][$ii]['order_id'] = $ov['id'];
			$data['err_msg']['order_list'][$ii]['order_no'] = $ov['orderid'];
			$data['err_msg']['order_list'][$ii]['total'] = $ov['price'];
			$data['err_msg']['order_list'][$ii]['pro_num'] = $ov['num'];
			$data['err_msg']['order_list'][$ii]['team_id'] = $ov['team_id'];
			$data['err_msg']['order_list'][$ii]['tuan_id'] = $ov['tid'];
			$team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$ov['team_id']))->find();
			$data['err_msg']['order_list'][$ii]['uid'] = $team['head_id'];
			$data['err_msg']['order_list'][$ii]['is_leader'] = $ov['ishead']?true:false;
			$data['err_msg']['order_list'][$ii]['type'] = $team['type'];
			$data['err_msg']['order_list'][$ii]['item_id'] = $team['guize_id'];
			$pintuan = M('pintuan')->where(array('token'=>$this->token,'id'=>$ov['tid']))->find();
			if($pintuan['enddate'] > time()){
				$data['err_msg']['order_list'][$ii]['status'] = 0;
			}else{
				if($team['type'] > 0){  
					//最优开团获取选取的规则
					$guize = M('pintuan_guize')->where(array('token'=>$this->token,'id'=>$team['guize_id']))->find();
				}else{
					//人缘开团满足最小达标人数
					$guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$team['tid']))->order('discount DESC')->find();
				}
				$allguize = M('pintuan_guize')->where(array('tid'=>$team['tid'],'token'=>$this->token))->order('number DESC')->select();
				//如果成功
				if($team['count'] >= $guize['number']){
					$data['err_msg']['order_list'][$ii]['status'] = 1;
					if($team['type'] > 0){
						foreach ($allguize as $k => $v) {
							$discount = 0;
							if($team['count'] >= $v['number']){
								$discount = $v['discount'];//最优折扣退款
								break;
							}
						}
						$refund_fee = $ov['price'] - $pintuan['price']*$discount/10000;
					}else{
						$refund_fee = 0;//不退
					}
				}else{
					$data['err_msg']['order_list'][$ii]['status'] = 2;
					$refund_fee = $ov['price'];//原价退款
				}
			}
			$data['err_msg']['order_list'][$ii]['refund_fee'] =  $refund_fee;
			$data['err_msg']['order_list'][$ii]['is_refund'] = ($team['isrefund'] == 1) ? 1 : 0;
			$data['err_msg']['order_list'][$ii]['name'] = $pintuan['title'];
			$data['err_msg']['order_list'][$ii]['image'] = $pintuan['facepic'];
			$ii++;
		}
		if(intval($_GET['page']?$_GET['page']:1) >= ceil(($count/$pagecount))){
			$data['err_msg']['next_page'] = false;
		}else{
			$data['err_msg']['next_page'] = true;
		}
		$sign_data = $this->sign_data;
		$pintuan['custom_sharetitle'] = ($pintuan['custom_sharetitle'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharetitle']) : '我正在参加“'.$pintuan['title'].'”拼团购活动，快来参与吧！';
		$pintuan['custom_sharedsc'] = ($pintuan['custom_sharedsc'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharedsc']) : $pintuan['info'];
		$data['err_msg']['share_data']['custom_sharetitle'] = shareFilter($pintuan['custom_sharetitle']);
		$data['err_msg']['share_data']['custom_sharedsc'] = shareFilter($pintuan['custom_sharedsc']);
		$data['err_msg']['share_data']['share_img'] = $pintuan['facepic'];
		$data['err_msg']['share_data']['appId'] = $sign_data['appId'];
		$data['err_msg']['share_data']['timestamp'] = $sign_data['timestamp'];
		$data['err_msg']['share_data']['nonceStr'] = $sign_data['nonceStr'];
		$data['err_msg']['share_data']['signature'] = $sign_data['signature'];
		$this->ajaxReturn($data,'JSON');exit;
		
	}
	public function orderinfoReturn(){
		$data['err_code'] = 0;
		$order = M('pintuan_order')->where(array('token'=>$this->token,'id'=>intval($_GET['order_id'])))->find();
		
		$pintuan = M('pintuan')->where(array('token'=>$this->token,'id'=>$order['tid']))->find();
		$data['err_msg']['order']['title'] = $pintuan['title'];
		$data['err_msg']['order']['name'] = $pintuan['title'];
		$data['err_msg']['order']['image'] = $pintuan['facepic'];
		$config_list = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$order['tid']))->order('discount desc')->select();
		$team = M('pintuan_team')->where(array('token'=>$this->token,'id'=>$order['team_id'],'tid'=>$order['tid']))->find();
		$jilu_guize = M('pintuan_guize')->where(array('token'=>$this->token,'tid'=>$order['tid'],'id'=>$team['guize_id']))->find();
		$this_guize = array();
		foreach($config_list as $con){
			if($con['discount'] <= $jilu_guize['discount'] && $team['count'] >= $con['number']){
				$this_guize[] = $con;
			}
		}
		if(count($this_guize) > 0){
			$guize = $this_guize[0];
		}else{
			$guize = $jilu_guize;
		}
		$data['err_msg']['order']['price'] = $pintuan['price']*$guize['discount']/10000;
		$data['err_msg']['order']['count'] = $order['num'];
		$data['err_msg']['order']['total'] = ($data['err_msg']['order']['price']*$order['num']).' ---- 实付：￥'.$order['price'];
		$data['err_msg']['order']['message'] = $order['message'];
		$data['err_msg']['order']['user_name'] = $order['user_name'];
		$data['err_msg']['order']['user_tel'] = $order['user_tel'];
		$data['err_msg']['order']['user_address'] = $order['user_address'];
		$paytype = strtolower($order['paytype']);
		$paytype_name = array(
			'cardpay' => '会员卡支付',
			'alipay' => '支付宝',
			'weixin' => '微信支付',
			'tenpay' => '财付通[wap手机]',
			'tenpayComputer' => '财付通[即时到帐]',
			'yeepay' => '易宝支付',
			'allinpay' => '通联支付',
			'daofu' => '货到付款',
			'dianfu' => '到店付款',
			'chinabank' => '网银在线'
		);
		$data['err_msg']['order']['pay_type'] = $paytype_name[$paytype];
		$data['err_msg']['order']['orderid'] = $order['orderid'];
		$data['err_msg']['order']['express_name'] = $order['express_name'];
		$data['err_msg']['order']['express_num'] = $order['express_num'];
		//$pintuan_reply = M('pintuan_reply')->where(array('token'=>$this->token))->find();
		$data['err_msg']['order']['tel'] = $pintuan['tel'];
		$data['err_msg']['order']['addtime'] = $order['addtime'];
		$shifu = $order['price'];
		$yingfu = $data['err_msg']['order']['price']*$order['num'];
		if($pintuan['enddate'] > time()){
			$data['err_msg']['order']['pay_state'] = '完成付款';
		}else{
			if($team['count'] >= $jilu_guize['number']){
				if($shifu == $yingfu){
					$data['err_msg']['order']['pay_state'] = '完成付款';
				}else{
					$data['err_msg']['order']['pay_state'] = '完成付款---应退'.($shifu-$yingfu).'元---'.($order['isbucha']?'已退款':'未退款');
				}
				if($order['state'] == 1){
					$data['err_msg']['order']['status'] = '等待卖家发货';
				}elseif($order['state'] == 2){
					$data['err_msg']['order']['status'] = '卖家已发货';
				}
			}else{
				if($team['type']){
					$data['err_msg']['order']['pay_state'] = '完成付款---拼团失败应退'.$shifu.'元---'.($order['isbucha']?'已退款':'未退款');
					$data['err_msg']['order']['status'] = '拼团失败';
				}else{
					if($order['state'] == 1){
						$data['err_msg']['order']['status'] = '等待卖家发货';
					}elseif($order['state'] == 2){
						$data['err_msg']['order']['status'] = '卖家已发货';
					}
				}
			}
		}
		
		$sign_data = $this->sign_data;
		$pintuan['custom_sharetitle'] = ($pintuan['custom_sharetitle'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharetitle']) : '我正在参加“'.$pintuan['title'].'”拼团购活动，快来参与吧！';
		$pintuan['custom_sharedsc'] = ($pintuan['custom_sharedsc'] != "") ? str_replace('{{活动名称}}',$pintuan['title'],$pintuan['custom_sharedsc']) : $pintuan['info'];
		$data['err_msg']['share_data']['custom_sharetitle'] = shareFilter($pintuan['custom_sharetitle']);
		$data['err_msg']['share_data']['custom_sharedsc'] = shareFilter($pintuan['custom_sharedsc']);
		$data['err_msg']['share_data']['share_img'] = $pintuan['facepic'];
		$data['err_msg']['share_data']['appId'] = $sign_data['appId'];
		$data['err_msg']['share_data']['timestamp'] = $sign_data['timestamp'];
		$data['err_msg']['share_data']['nonceStr'] = $sign_data['nonceStr'];
		$data['err_msg']['share_data']['url'] = $this->site_url.U('Wap/Pintuan/share',array('token'=>$this->token,'team'=>$order['team_id']));
		$data['err_msg']['share_data']['signature'] = $sign_data['signature'];
		$data['err_msg']['tuan']['tuan_id'] = $order['tid'];
		$data['err_msg']['tuan']['store_id'] = $this->token;
		$data['err_msg']['tuan']['team_id'] = $order['team_id'];
		$data['err_msg']['tuan']['type'] = $team['type'];
		$this->ajaxReturn($data,'JSON');exit;
	}
}
?>