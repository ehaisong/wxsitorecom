<?php
class IndexAction extends UserAction{
	//公众帐号列表
	public function index(){
		$this->assign('topdomain',C('server_topdomain'));
		$oauthUrl	= '';

		// proxy
		$agentid = 0;
		if (C(agent_version)) {
			$agent = M('Agent')->where(array('siteurl' => 'http://' . $_SERVER["HTTP_HOST"]))->find();
			$agentid = isset($agent['id']) ? intval($agent['id']) : 0;
		}

		$account = M('Weixin_account')->where('type=1 AND agentid=' . $agentid)->find();
// 		$siteUrl = parse_url(C('site_url'));
// 		if ($siteUrl['host'] == $_SERVER["HTTP_HOST"]) {
			if($account && !empty($account['appId']) && !empty($account['appSecret']) && !empty($account['token']) && !empty($account['encodingAesKey'])){
				$apiOauth 	= new apiOauth();
				$redirect_uri 	= U('Index/oauth_back','','','',true);
				$oauthUrl  	= $apiOauth->start_authorization($redirect_uri);
				//echo $oauthUrl;die;

				$data=M('User_group')->field('wechat_card_num')->where(array('id'=>session('gid')))->find();
				$count=M('wxuser')->where(array('uid'=>session('uid')))->count();
				//
				if($count < $data['wechat_card_num']) {
					$this->assign('oauthUrl',$oauthUrl);
				}else{
					$this->assign('oauthUrl', U('Index/createError'));
				}
			}
// 		}

		if (class_exists('demoImport')&&!$oauthUrl){
			$this->assign('demo',1);
			//
			$token=$this->get_token();
			$pigSecret=$this->get_token(20,0,1);
			$wxinfo=M('wxuser')->where(array('uid'=>intval(session('uid'))))->find();
			if (!$wxinfo){
				$demoImport=new demoImport(session('uid'),$token,$pigSecret);
			}
			$wxinfo=M('wxuser')->where(array('uid'=>intval(session('uid'))))->find();
			$this->assign('wxinfo',$wxinfo);
			//
			$this->assign('token',$token);
		}
		//
		$where['uid']=session('uid');
		$group=D('User_group')->select();
		foreach($group as $key=>$val){
			$groups[$val['id']]['did']=$val['diynum'];
			$groups[$val['id']]['cid']=$val['connectnum'];
		}
		$this->assign(array('hasFuwu'=>$this->check_allow_Function('Fuwu'),'hasWeixin'=>$this->check_allow_Function('Weixin')));

		unset($group);
		$db=M('Wxuser');
		$count=$db->where($where)->count();
		$page=new Page($count,5);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		if ($info){
			foreach ($info as $item){
				if (!$item['appid']&&$apiinfo['appid']&&$apiinfo['appsecret']){
					$apiinfo=M('Diymen_set')->where(array('token'=>$item['token']))->find();
					$db->where(array('id'=>$item['id']))->save(array('appid'=>$apiinfo['appid'],'appsecret'=>$apiinfo['appsecret']));
				}else {
					$diymen=M('Diymen_set')->where(array('token'=>$item['token']))->find();
					if (!$diymen&&$item['appid']&&$item['appsecret']){
					M('Diymen_set')->add(array('token'=>$item['token'],'appid'=>$item['appid'],'appsecret'=>$item['appsecret']));
					}
				}
				//
			}
			/*foreach($info as $k=>$v){
				if($v['ifbiz'] != 1 && $v['type'] != 1){
					if($v['appid'] != '' || $v['appsecret'] != ''){
						$appidarray = explode('_',$v['appid']);
						if($appidarray[1] == 'no'){
							$info[$k]['error'] = 1;
						}else{
							$appid = $v['appid'];
							$secret = $v['appsecret'];
							$token = $v['token'];
							$renzhengfuwuhaodata = S('renzhengfuwuhaodata'.$token);
							if($appid != $renzhengfuwuhaodata['appid'] || $secret != $renzhengfuwuhaodata['appsecret']){
								S($appid ,null);
								$apiOauth 	= new apiOauth();
								$access_token = $apiOauth->update_authorizer_access_token($appid,$v);	
								usleep(10000);						
								if($v['winxintype'] == 3){
									$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
									$yzdata = "{\"action\":\"long2short\",\"long_url\":\"".$this->siteUrl."\"}";
									$wxdata = json_decode($this->https_request($url,$yzdata), true);
									if($wxdata['errcode'] == 48001 || $access_token == false){
										$info[$k]['error'] = 2;
										$info[$k]['error_msg'] = "不是认证后的服务号";
									}else{
										$renzhengfuwuhaodata['appid'] = $appid;
										$renzhengfuwuhaodata['appsecret'] = $secret;
										S('renzhengfuwuhaodata'.$token,$renzhengfuwuhaodata);
									}
								}
							}
						}
					}
				}
			}*/
		}

		$this->assign('thisGroup',$this->userGroup);
		$this->assign('info',$info);
		$this->assign('group',$groups);
		$this->assign('page',$page->show());
		//
		if (!isset($_SESSION['closeAD'])){
			$ads=M('Renew')->where('id>0')->order('id DESC')->limit(0,5)->select();
			$thisAD=$ads[rand(0,4)];
			if ($thisAD['imgs']){
			$this->assign('thisAD',$thisAD);
			}
		}
		if (C('server_topdomain')=='vifnn.cn'){
			$this->assign("pid_zw",1);
		}
		//
		if (count($info)==1&&$info[0]['wxid']=='demo'&&class_exists('demoImport')&&!$oauthUrl){
			$this->assign('info',$info[0]);
			$this->display('bindTip');
		}else {
			$this->display();
		}
	}

	public function createError() {
		$this->error('您的VIP等级所能创建的公众号数量已经到达上线，请购买后再创建',U('User/Index/index'));exit();
	}

	public function closeAD(){
		$_SESSION['closeAD']=1;
	}
	
public function frame(){
		$id=$this->_get('id','intval');
		if (!$id){
			$token=$this->token;
			$info=M('Wxuser')->find(array('token'=>$this->token));
		}else {
			$info=M('Wxuser')->find($id);
		}
		session('token',$token);
		session('wxid',$info['id']);
		

	/* $token=$this->token; */
		$token=$this->_get('token','trim');	
		session('token',$token);
		$this->assign('token',session('token'));
		$this->display();
	}	
	public function info(){
		$where['uid']=session('uid');
		$group=D('User_group')->select();
		foreach($group as $key=>$val){
			$groups[$val['id']]['did']=$val['diynum'];
			$groups[$val['id']]['cid']=$val['connectnum'];
		}
		unset($group);
		$db=M('Wxuser');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		if ($info){
			foreach ($info as $item){
				if (!$item['appid']){
					$apiinfo=M('Diymen_set')->where(array('token'=>$item['token']))->find();
					$db->where(array('id'=>$item['id']))->save(array('appid'=>$apiinfo['appid'],'appsecret'=>$apiinfo['appsecret']));
				}else {
					$diymen=M('Diymen_set')->where(array('token'=>$item['token']))->find();
					if (!$diymen){
					M('Diymen_set')->add(array('token'=>$item['token'],'appid'=>$item['appid'],'appsecret'=>$item['appsecret']));
					}
				}
				//
			}
		}
		$this->assign('thisGroup',$this->userGroup);
		$this->assign('info',$info);
		$this->assign('group',$groups);
		$this->assign('page',$page->show());
		$this->display();
	}			
	
	public function get_token($randLength=6,$attatime=1,$includenumber=0){
		if ($includenumber){
			$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
		}else {
			$chars='abcdefghijklmnopqrstuvwxyz';
		}
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		$tokenvalue=$randStr;
		if ($attatime){
			$tokenvalue=$randStr.time();
		}
		return $tokenvalue;
	}
	//添加公众帐号
	public function add(){

		$data=M('User_group')->field('wechat_card_num')->where(array('id'=>session('gid')))->find();
		$count=M('wxuser')->where(array('uid'=>session('uid')))->count();
		//
		if($count >= $data['wechat_card_num']) {
			$this->redirect(U('Index/createError'));
		}
		$randLength=6;
		$chars='abcdefghijklmnopqrstuvwxyz';
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		$tokenvalue=$randStr.time();
		$this->assign('tokenvalue',$tokenvalue);
		$this->assign('email',time().'@yourdomain.com');
		$comefromsyttoken=isset($_GET['comefromsyttoken']) ? trim($_GET['comefromsyttoken']) :'';
		$this->assign('comefromsyttoken',$comefromsyttoken);
		//地理信息
		if (C('baidu_map_api')){
			//$locationInfo=json_decode(file_get_contents('http://api.map.baidu.com/location/ip?ip='.$_SERVER['REMOTE_ADDR'].'&coor=bd09ll&ak='.C('baidu_map_api')),1);
			///$this->assign('province',$locationInfo['content']['address_detail']['province']);
			//$this->assign('city',$locationInfo['content']['address_detail']['city']);
			//var_export($locationInfo);
		}
		$this->display();
	}
	
	//企秀同步
	public function eqx(){
		        $users=M('Users')->where(array('id'=>session('uid')))->find();//获得当前用户的密码写入易企秀
	    	    $eqx_User = M(C('eqxname').'.users','cj_','mysql://'.C('eqxuser').':'.C('eqxpassword').'@'.C('eqxdburl').'/'.C('eqxname').''); 
				$eqx['email_varchar']=$_GET['token'];

				$eqx['password_varchar'] = md5($users['password']);
				//$eqx['create_time']=time();
				$eqxuser=$eqx_User->where(array('email_varchar'=>$_GET['token']))->find();//查询当前token是否在易企秀用户表存在
				if (empty($eqxuser)){
				   $b=$eqx_User->add($eqx);
				   if ($b){
				     $db=M('Wxuser');
				     $date['id']=$_GET['id'];
				     $date['eqx']=1;
				     $date['eqxpassword']=$users['password'];
				     $a=$db->save($date);
				     if($a){$this->success('帐号同步成功',U('Eqx/index'));}else{$this->error('帐号同步失败,请检测VIFNNcms表字段是否正常',U('Eqx/keywordlist'));}
				   }else{
                      $this->error('帐号同步失败,请检测VIFNNcms总后台场景相关信息是否填写正确',U('Eqx/keywordlist'));
				   }
				}else{
					$db=M('Wxuser');
				     $date['id']=$_GET['id'];
				     $date['eqx']=1;
				     $date['eqxpassword']=$users['password'];
				     $a=$db->save($date);
				     if($a){$this->success('帐号同步成功',U('Eqx/index'));}else{$this->error('帐号同步失败,请检测VIFNNcms表字段是否正常',U('Eqx/keywordlist'));}
				}
				
	}	
	//秀重新同步
	public function reeqx(){
		        $users=M('Users')->where(array('id'=>session('uid')))->find();//获得当前用户的密码写入易企秀
	    	    $eqx_User = M(C('eqxname').'.users','cj_','mysql://'.C('eqxuser').':'.C('eqxpassword').'@'.C('eqxdburl').'/'.C('eqxname').''); 
				$eqx['email_varchar']=$_GET['token'];
				$eqx['password_varchar'] = md5($users['password']);
				$eqxuser=$eqx_User->where(array('email_varchar'=>$eqx['email_varchar']))->find();
				if ($eqxuser){
					$condition['email_varchar'] = $eqx['email_varchar'];
				    $b=$eqx_User->where($condition)->save($eqx);
				}else{
				    $b=$eqx_User->add($eqx);
				}
			    if ($b !== false){
				      $db=M('Wxuser');
				      $date['id']=$_GET['id'];
					  $date['eqx']=1;
				      $date['eqxpassword']='';
					  $a=$db->save($date);
					  $date['eqxpassword']=$users['password'];
					  $aa=$db->save($date);
				     if($aa){$this->success('帐号同步成功',U('Eqx/index'));}else{$this->error('帐号同步失败,请联系网站管理员检测表字段是否正常',U('Eqx/keywordlist'));}
				 }else{
                      $this->error('帐号同步失败,请联系网站管理员检测总后台场景相关信息是否填写正确',U('Eqx/keywordlist'));
				 }
	}	
	
	public function edit(){

		$id=$this->_get('id','intval');
		$where['uid']=session('uid');
		$where['id']=$id;
		$res=M('Wxuser')->where($where)->find();
		$queryname = M('token_open')->where(array('token'=>$this->token))->getField('queryname');
		$queryname = explode(',',$queryname);
		if(in_array('liaotian',$queryname)){
			$res['wx_liaotian'] = 1;
		}else{
			$res['wx_liaotian'] = 0;
		}
		if(in_array('lbsNews',$queryname)){
			$res['wx_lbsNews'] = 1;
		}else{
			$res['wx_lbsNews'] = 0;
		}
		if($res['winxintype'] == 3 && $res['type'] != 1){
			$appid = $res['appid'];
			$secret = $res['appsecret'];
			$this->token = $res['token'];
			$renzhengfuwuhaodata = S('renzhengfuwuhaodata'.$this->token);
			$res['error'] = 0;
			if($appid != $renzhengfuwuhaodata['appid'] || $secret != $renzhengfuwuhaodata['appsecret']){
				S($appid ,null);
				$apiOauth 	= new apiOauth();
				$access_token = $apiOauth->update_authorizer_access_token($appid,$res);
				
				if($res['winxintype'] == 3){
					$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
					$yzdata = "{\"action\":\"long2short\",\"long_url\":\"".$this->siteUrl."\"}";
					$wxdata = json_decode($this->https_request($url,$yzdata), true);
					if($wxdata['errcode'] == 48001 || $access_token == false){
						$res['error'] = 2;
					}else{
						$renzhengfuwuhaodata['appid'] = $appid;
						$renzhengfuwuhaodata['appsecret'] = $secret;
						S('renzhengfuwuhaodata'.$this->token,$renzhengfuwuhaodata);
					}
				}
			}
		}
		$appsecret = $res['appsecret'];
		$res['appsecret'] = substr($appsecret, 0, 2);
		$res['appsecret'] .= '*****************************'.substr($appsecret, -2);
		$this->assign('info',$res);
		$this->assign('appsecret',$appsecret);
		$this->assign('fuwu',$this->check_allow_Function('Fuwu'));
		$tongjiModel=M('tongji');
		$tongji=$tongjiModel->where(array('token'=>$res['token']))->find();
		$this->assign('tongji',$tongji?$tongji:array('status'=>'0'));
		$this->display();
	}
	public function fwajax(){
		$appid = $_POST['appid'];
		$secret = $_POST['appsecret'];
		$this->token = $_POST['token'];
		$renzhengfuwuhaodata = S('renzhengfuwuhaodata'.$this->token);
		if($appid != $renzhengfuwuhaodata['appid'] || $secret != $renzhengfuwuhaodata['appsecret']){
			S($appid ,null);
			$apiOauth 	= new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($appid,$_POST);

			$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
			$yzdata = "{\"action\":\"long2short\",\"long_url\":\"".$this->siteUrl."\"}";
			$wxdata = json_decode($this->https_request($url,$yzdata), true);
			if($wxdata['errcode'] == 48001 || $access_token == false){
				$data['error'] = 2;
			}else{
				$data['error'] = 0;
				$renzhengfuwuhaodata['appid'] = $appid;
				$renzhengfuwuhaodata['appsecret'] = $secret;
				S('renzhengfuwuhaodata'.$this->token,$renzhengfuwuhaodata);
			}
		}else{
			$data['error'] = 0;
		}
		$this->ajaxReturn($data,'JSON');
	}
	//https请求（支持GET和POST）
    protected function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
	public function bindEdit(){
		$where['uid']=session('uid');
		$res=M('Wxuser')->where($where)->find();
		$this->assign('info',$res);
		$this->display();
	}

	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$thisWxUser=M('Wxuser')->where(array('id'=>$where['id']))->find();
		$users=M('Users')->where(array('id'=>$thisWxUser['uid']))->find();
		// 删除统计数据
		D('AccessCount')->where(array('token'=>$thisWxUser['token']))->delete();
		session('token', null);
		if(D('Wxuser')->where($where)->delete()){
			M('Users')->field('wechat_card_num')->where(array('id'=>session('uid')))->setDec('wechat_card_num');
			if ($this->isAgent){
				$wxuserCount=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id']))->count();
				M('Agent')->where(array('id'=>$this->thisAgent['id']))->save(array('wxusercount'=>$wxuserCount));
				if ($this->thisAgent['wxacountprice']&&time()-$thisWxUser['createtime']<5*24*3600){

					$pricebyMonth=intval($this->thisAgent['wxacountprice'])/12;
					$month=($users['viptime']-time())/(30*24*3600);
					$month=intval($month);
					$price=$month*$pricebyMonth;
					//
					M('Agent')->where(array('id'=>$this->thisAgent['id']))->setInc('moneybalance',$price);
					M('Agent_expenserecords')->add(array('agentid'=>$this->thisAgent['id'],'amount'=>$price,'des'=>$this->user['username'].'(uid:'.$this->user['id'].')删除公众号'.$thisWxUser['wxname'].'_'.$month.'个月','status'=>1,'time'=>time()));
				}
			}
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	public function apiInfo(){
		$thisWx=apiInfo::info($this->user['id'],intval($_GET['id']));
		$this->assign('info',$thisWx);




		if($this->check_allow_Function('Fuwu') && ALI_FUWU_GROUP && $thisWx['fuwuappid'])
		{
			$this->assign('fuwu',true);
		}
		else
		{
			$this->assign('fuwu',false);
		}
		$this->display();
	}
	public $token;
	public function upsave(){
		if($_POST['biz'] != 1 && $_POST['qr'] == ''){
			$this->error('请填写二维码');
		}
		$res = M("wxuser")->where(array('id'=>$_POST['id']))->find();
		$this->token = $res['token'];
		session('token',$res['token']);
		$_POST['appid'] = trim($_POST['appid']);
		$_POST['appsecret'] = trim($_POST['appsecret']);
		if (empty($_POST['appsecret'])) $_POST['appsecret'] = $res['appsecret'];
		if($res['type'] != 1 && $_POST['winxintype'] == 3){
			$renzhengfuwuhaodata = S('renzhengfuwuhaodata'.$this->token);
			$appid = $_POST['appid'];
			$secret = $_POST['appsecret'];
			
			if($appid != $renzhengfuwuhaodata['appid'] || $secret != $renzhengfuwuhaodata['appsecret']){
				
				S($appid ,null);
				$apiOauth 	= new apiOauth();
				$access_token = $apiOauth->update_authorizer_access_token($appid,$_POST);
				if(!$access_token){
					$this->error('您填的appid和appsecret不正确！请修改您的appid和appsecret或者微信号类型');
				}
				if($_POST['winxintype'] == 3){
					$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$access_token}";
					$yzdata = "{\"action\":\"long2short\",\"long_url\":\"".$this->siteUrl."\"}";
					$wxdata = json_decode($this->https_request($url,$yzdata), true);
					if($wxdata['errcode'] == 48001){
						$this->error('您填的appid和appsecret不是认证后的服务号！请修改您的appid和appsecret或者微信号类型');
					}else{
						$renzhengfuwuhaodata['appid'] = $appid;
						$renzhengfuwuhaodata['appsecret'] = $secret;
						S('renzhengfuwuhaodata'.$this->token,$renzhengfuwuhaodata);
					}
				}
			}
		}
			if($_POST['wx_liaotian']*1 == 1){
				$queryname = M('token_open')->where(array('token'=>$this->token))->getField('queryname');
				$queryname = explode(',',$queryname);
				if(!in_array('liaotian',$queryname)){
					$openwhere=array('uid'=>session('uid'),'token'=>session('token'));
					//删除掉重复的token
					$deleteWhere=array();
					$deleteWhere['uid']=array('neq',session('uid'));
					$deleteWhere['token']=session('token');
					M('Token_open')->where($deleteWhere)->delete();
					//
					$open=M('Token_open')->where($openwhere)->find();
					$str['queryname']=str_replace(',,',',',$open['queryname'].',liaotian');
					//
					if (!$open){
						M('Token_open')->add(array('uid'=>session('uid'),'token'=>session('token')));
					}
					//
					$backback=M('Token_open')->where($openwhere)->save($str);
				}
			}else{
				$queryname = M('token_open')->where(array('token'=>$this->token))->getField('queryname');
				$queryname = explode(',',$queryname);
				if(in_array('liaotian',$queryname)){
					$openwhere=array('uid'=>session('uid'),'token'=>session('token'));
					$open=M('Token_open')->where($openwhere)->find();
					//删除掉重复的token
					$deleteWhere=array();
					$deleteWhere['uid']=array('neq',session('uid'));
					$deleteWhere['token']=session('token');
					M('Token_open')->where($deleteWhere)->delete();
					//
					$str['queryname']=ltrim(str_replace(',,',',',str_replace('liaotian','',$open['queryname'])),',');
					$backback=M('Token_open')->where($openwhere)->save($str);
				}
			}
			if($_POST['wx_lbsNews']*1 == 1){
				$queryname = M('token_open')->where(array('token'=>$this->token))->getField('queryname');
				$queryname = explode(',',$queryname);
				if(!in_array('lbsNews',$queryname)){
					$openwhere=array('uid'=>session('uid'),'token'=>session('token'));
					//删除掉重复的token
					$deleteWhere=array();
					$deleteWhere['uid']=array('neq',session('uid'));
					$deleteWhere['token']=session('token');
					M('Token_open')->where($deleteWhere)->delete();
					//
					$open=M('Token_open')->where($openwhere)->find();
					$str['queryname']=str_replace(',,',',',$open['queryname'].',lbsNews');
					//
					if (!$open){
						M('Token_open')->add(array('uid'=>session('uid'),'token'=>session('token')));
					}
					//
					$backback=M('Token_open')->where($openwhere)->save($str);
				}
			}else{
				$queryname = M('token_open')->where(array('token'=>$this->token))->getField('queryname');
				$queryname = explode(',',$queryname);
				if(in_array('lbsNews',$queryname)){
					$openwhere=array('uid'=>session('uid'),'token'=>session('token'));
					$open=M('Token_open')->where($openwhere)->find();
					//删除掉重复的token
					$deleteWhere=array();
					$deleteWhere['uid']=array('neq',session('uid'));
					$deleteWhere['token']=session('token');
					M('Token_open')->where($deleteWhere)->delete();
					//
					$str['queryname']=ltrim(str_replace(',,',',',str_replace('lbsNews','',$open['queryname'])),',');
					$backback=M('Token_open')->where($openwhere)->save($str);
				}
			}


		if (isset($_POST['zweixin'])){
			$_POST['wxid']=$_POST['zwxid'];
			$_POST['weixin']=$_POST['zweixin'];
		}
		S('wxuser_'.$this->token,NULL);
		M('Diymen_set')->where(array('token'=>$this->token))->save(array('appid'=>trim($this->_post('appid')),'appsecret'=>trim($this->_post('appsecret'))));
		//
		$db=D('Wxuser');
		if (isset($_POST['demoStep'])){
			$back='/bindHelp?id='.intval($_POST['id']);
		}else {
			$back='';
		}
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->save();
			if($id){
				if (isset($_POST['demoStep'])){
					header('Location:'.$this->siteUrl.U('Index/bindHelp',array('id'=>intval($_POST['id']))));
				}else {
					$appid 	= $this->_post('appid','trim');
					$appid 	= str_replace("_no", "", $appid);
					if ($appid) {
						M('Wxuser')->where(array('appid'=>$appid,'id'=>array('neq',$this->_post('id','intval'))))->save(array('appid'=>$appid.'_no'));
					}

					$users=M('Users')->where(array('id'=>session('uid')))->find();
					if(!empty($users['comefromid']) && strpos($users['comefromid'],'-')){
						/****对接平台版收银台的*****/
						$CmscashierkeyDb=D('Cmscashierkey');
						$mercashier=$CmscashierkeyDb->getCashierMerchant($this->token);
						if(!empty($mercashier) && ($mercashier['third_user_id']==$this->token)){
							$tmpArr=explode('-',$users['comefromid']);
							$Dockingdatas=array(
								'ctoken'=>$tmpArr['0'],'username'=>$users['username'],
								'user_name'=>trim($_POST['wxname']),'wx_name'=>trim($_POST['wxname']),
								'wx_id'=>trim($_POST['weixin']),'appid'=>trim($_POST['appid']),
								'appsecret'=>trim($_POST['appsecret']),'header_pic'=>trim($_POST['qr']),
								'winxintype'=>intval($_POST['winxintype']),'source_token'=>$this->token,
								'aesKey'=>trim($_POST['aeskey']),'email'=>trim($_POST['qq']),'phone'=>'',
								'source_id'=>'','secret'=>$res['pigsecret']
							);
							$CmscashierkeyDb->CashierSynByToken($Dockingdatas,0);
						}
					}
					$this->success('操作成功',U('index'));
				}

			}else{
				$this->error('操作失败',U('index'));
			}
		}

	}

	public function insert(){
		if($_POST['qr'] == '' && $_POST['ifbiz'] != 1 && $_POST['goldbuy'] != 1){
			$this->error('请填写二维码');
		}
		if ($this->_post('biz','intval') && C('open_biz') == 1) {
			$_POST['wxid'] = uniqid('id_');
			$_POST['weixin'] = uniqid('wx_');
			$_POST['ifbiz'] = 1;

		}else if($this->_post('goldbuy','intval')){
			$_POST['wxid'] = uniqid('id_');
			$_POST['weixin'] = uniqid('wx_');
		}
		$data=M('User_group')->field('wechat_card_num')->where(array('id'=>session('gid')))->find();
		$users=M('Users')->where(array('id'=>session('uid')))->find();
		//
		if ($this->isAgent){
			$needPay=0;
			if (($users['viptime']-$users['createtime']-20)>$this->reg_validDays*24*3600||$this->reg_validDays>30){
				$needPay=1;
			}
			if ($needPay){
				$pricebyMonth=intval($this->thisAgent['wxacountprice'])/12;
				$month=($users['viptime']-time())/(30*24*3600);
				$month=intval($month);
				$price=$month*$pricebyMonth;
				$price=intval($price);
				if ($price>$this->thisAgent['moneybalance']){
					$this->error('请联系您的站长处理');
				}
			}
		}
		//
		if($users['wechat_card_num']<$data['wechat_card_num']){

		}else{
			$this->error('您的VIP等级所能创建的公众号数量已经到达上线，请购买后再创建',U('User/Index/index'));exit();
		}
		//$this->alli_nsert('Wxuser');
		//
		$db=D('Wxuser');
		if ($this->isAgent){
			$_POST['agentid']=$this->thisAgent['id'];
		}
		//
		$randLength=43;
		$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		$aeskey=$randStr;
		$_POST['aeskey']=$aeskey;
		$_POST['encode']=0;
		//
		$pigSecret=$this->get_token(20,0,1);
		$_POST['pigsecret']=$pigSecret;
		if($db->create()===false){
			$this->error($db->getError());
		}else{
			$id=$db->add();
			if($id){
				if ($this->isAgent){
					$wxuserCount=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id']))->count();
					M('Agent')->where(array('id'=>$this->thisAgent['id']))->save(array('wxusercount'=>$wxuserCount));
					if ($this->thisAgent['wxacountprice']){
						//试用期内不扣费
						if ($needPay){
							$pricebyMonth=intval($this->thisAgent['wxacountprice'])/12;
							$month=($users['viptime']-time())/(30*24*3600);
							$month=intval($month);
							$price=$month*$pricebyMonth;
							M('Agent')->where(array('id'=>$this->thisAgent['id']))->setDec('moneybalance',$price);
							M('Agent_expenserecords')->add(array('agentid'=>$this->thisAgent['id'],'amount'=>(0-$price),'des'=>$this->user['username'].'(uid:'.$this->user['id'].')添加公众号'.$_POST['wxname'].'('.$month.'个月)','status'=>1,'time'=>time()));
						}
					}
				}
				M('Users')->field('wechat_card_num')->where(array('id'=>session('uid')))->setInc('wechat_card_num');
				$this->addfc();
				M('Diymen_set')->add(array('appid'=>trim($this->_post('appid')),'token'=>$this->_post('token'),'appsecret'=>trim($this->_post('appsecret'))));
				//
				$appid 	= $this->_post('appid','trim');
				if ($appid) {
					M('Wxuser')->where(array('appid'=>$appid,'id'=>array('neq',$id)))->save(array('appid'=>$appid.'_no'));
				}
				$comefromsyttoken=isset($_GET['comefromsyttoken']) ? trim($_GET['comefromsyttoken']) :'';
				if(!empty($users['comefromid']) && strpos($users['comefromid'],'-') && !empty($comefromsyttoken)){
					/****对接平台版收银台的*****/
					$tmpArr=explode('-',$users['comefromid']);
					$Dockingdatas=array(
							'ctoken'=>$tmpArr['0'],'username'=>$users['username'],
							'user_name'=>trim($_POST['wxname']),'wx_name'=>trim($_POST['wxname']),
							'wx_id'=>trim($_POST['weixin']),'appid'=>trim($_POST['appid']),
							'appsecret'=>trim($_POST['appsecret']),'header_pic'=>trim($_POST['qr']),
							'winxintype'=>intval($_POST['winxintype']),'source_token'=>$_POST['token'],
							'aesKey'=>$aeskey,'email'=>trim($_POST['qq']),'phone'=>'',
						    'source_id'=>'','secret'=>$pigSecret
					);
					D('Cmscashierkey')->CashierSynByToken($Dockingdatas,1);
				}

				$this->success('操作成功',U('Index/index'));
			}else{
				$this->error('操作失败',U('Index/index'));
			}
		}

	}

	//功能
	public function autos(){
		$this->display();
	}

	public function addfc(){
		$token_open=M('Token_open');
		$open['uid']=session('uid');
		$open['token']=$_POST['token'];
		$gid=session('gid');
		if (C('agent_version')&&$this->agentid){
			$agentid=$this->thisAgent['is_package']=='0'?'0':$this->agentid;
			//$fun=M('Agent_function')->field('funname,gid,isserve')->where('`gid` <= '.$gid.' AND agentid='.$this->agentid)->select();
			$fun=M('User_group')->field('func')->where('`id` = '.$gid.' AND agentid='.$agentid)->select();
		}else {
			//$fun=M('Function')->field('funname,gid,isserve')->where('`gid` <= '.$gid)->select();
			$fun=M('User_group')->field('func')->where('`id` = '.$gid)->select();
		}
		foreach($fun as $key=>$vo){
			$queryname.=$vo['func'].',';
		}
		$open['queryname']=rtrim($queryname,',');
		$token_open->data($open)->add();
	}

	public function usersave(){
		$pwd=$this->_post('password');
		if($pwd!=false){
			$data['password']=md5($pwd);
			$data['id']=$_SESSION['uid'];
			if(M('Users')->save($data)){
				$this->success('密码修改成功！',U('Index/index'));
			}else{
				$this->error('密码修改失败！',U('Index/index'));
			}
		}else{
			$this->error('密码不能为空!',U('Index/useredit'));
		}
	}
	//处理关键词
	public function handleKeywords(){
		$Model = new Model();
		//检查system表是否存在
		$keyword_db=M('Keyword');
		$count = $keyword_db->where('pid>0')->count();
		//
		$i=intval($_GET['i']);
		//
		if ($i<$count){
			$img_db=M($data['module']);
			$back=$img_db->field('id,text,pic,url,title')->limit(9)->order('id desc')->where($like)->select();
			//
			$rt=$Model->query("CREATE TABLE IF NOT EXISTS `tp_system_info` (`lastsqlupdate` INT( 10 ) NOT NULL ,`version` VARCHAR( 10 ) NOT NULL) ENGINE = MYISAM CHARACTER SET utf8");
			$this->success('关键词处理中:'.$row['des'],'?g=User&m=Create&a=index');
		}else {
			exit('更新完成，请测试关键词回复');
		}
	}
	public function bindHelp(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		$thisWx=apiInfo::info($this->user['id'],intval($_GET['id']));
		$this->assign('wxuser',$thisWx);
		$this->display();
	}
	public function bindTip(){
		if (class_exists('demoImport')){
			$this->assign('demo',1);
			//
			$token=$this->get_token();
			$pigSecret=$this->get_token(20,0,1);
			$wxinfo=M('wxuser')->where(array('uid'=>intval(session('uid'))))->find();
			if (!$wxinfo){
				$demoImport=new demoImport(session('uid'),$token,$pigSecret);
			}
			$wxinfo=M('wxuser')->where(array('uid'=>intval(session('uid'))))->find();
			$this->assign('wxinfo',$wxinfo);
			//
			$this->assign('token',$token);
		}
		$this->display();
	}
	
	public function switchTPl()
	{
		if(IS_AJAX){
			$id = (int)$this->_post('value');

			if($id == 1){
				if(M("Users")->where(array("id"=>(int)session('uid')))->setField('usertplid',1)){
					echo 200;
				}else{
					echo 500;
				}

			}
			if($id==0){
				if(M("Users")->where(array("id"=>(int)session('uid')))->setField('usertplid',0)){
					echo 200;
				}else{
					echo 500;
				}
			}
			if($id==2){
				if(M("Users")->where(array("id"=>(int)session('uid')))->setField('usertplid',2)){
					echo 200;
				}else{
					echo 500;
				}
			}

		}else{
			$this->display();
		}
	}


	public function qiye(){
		$where = array('uid' => intval(session('uid')), 'id' => intval($_GET['id']));
		$wxinfo=M('Wxuser')->where($where)->find();
		if(empty($wxinfo)){
			$this->error('参数错误,请稍后再试~');
		}
		$oa = new oa($wxinfo);
		$url = $oa->url();

		$this->assign("oaurl",$url);
		$this->display();
	}
	public function help(){
		$this->assign('helpParm',$_GET['helpParm']);
			$this->assign('server',getUpdateServer());  //自行增加
		$this->display();
	}

	//@lindu wu 20160509
	//绑定公众号
	public function bind(){
		if(!C('IS_MEIHUA')){
			echo 'error';exit;
		}
		$agentid = 0;
		if (C(agent_version)) {
			$agent = M('Agent')->where(array('siteurl' => 'http://' . $_SERVER["HTTP_HOST"]))->find();
			$agentid = isset($agent['id']) ? intval($agent['id']) : 0;
		}

		$account = M('Weixin_account')->where('type=1 AND agentid=' . $agentid)->find();
		if($account && !empty($account['appId']) && !empty($account['appSecret']) && !empty($account['token']) && !empty($account['encodingAesKey'])){
			$apiOauth 	= new apiOauth();
			$redirect_uri 	= U('Index/oauth_back','','','',true);
			$oauthUrl  	= $apiOauth->start_authorization($redirect_uri);
			//echo $oauthUrl;die;

			$data=M('User_group')->field('wechat_card_num')->where(array('id'=>session('gid')))->find();
			$count=M('wxuser')->where(array('uid'=>session('uid')))->count();

			if($count < $data['wechat_card_num']) {
				$this->assign('oauthUrl',$oauthUrl);
			}else{
				$this->assign('oauthUrl', U('Index/createError'));
			}
			if($count == 1) {
				$ac_id = M('wxuser')->where(array('uid'=>session('uid')))->getField('id');
				redirect($oauthUrl.urlencode("&ac_id=$ac_id"));exit;
			}else{
				echo "error";
			}

		}else{
			echo "请在后台配置微信托管信息";
		}


	}

	public function oauth(){ //发起授权
		$apiOauth 	= new apiOauth();
		$redirect_uri 	= U('Index/oauth_back','','','',true);
		$oauthUrl  	= $apiOauth->start_authorization($redirect_uri);
		//echo $oauthUrl;die;
		$this->assign('oauthUrl',$oauthUrl);
		$this->display();
	}

	public function oauth_back(){
		$ac_id 		= intval($_GET['ac_id']);
		$auth_code 	= $_GET['auth_code'];
		$expires_in = $_GET['expires_in'];
		if(!empty($auth_code) && !empty($expires_in)){

			$apiOauth 	= new apiOauth();

			$authorization_info = $apiOauth->get_authorization_info($auth_code);

			$authorizer_info 	= $apiOauth->get_authorizer_info($authorization_info['authorizer_appid']);

			$appid 				= $authorization_info['authorizer_appid'];

			$where 	= array('uid'=>session('uid'));

			if(!empty($ac_id)){
				$where['id'] 	= $ac_id;
			}else{
				$where['appid'] = array('like', $appid.'%');
			}

			//file_put_contents('authorization_info.txt',json_encode($authorization_info));
			//file_put_contents('authorizer_info.txt',json_encode($authorizer_info));
			$wxinfo 	= M('Wxuser')->where($where)->find();
			if($wxinfo){
				$save 	= array();
				$save['type'] 			= 1;
				$save['encode'] 		= 2;
				$save['wxid'] 			= $authorizer_info['user_name'];
				$save['wxname'] 		= $authorizer_info['nick_name'];
				$save['weixin'] 		= $authorizer_info['alias'];
				$save['headerpic'] 		= empty($authorizer_info['head_img'])?'':$authorizer_info['head_img'];

				$service_type 		= $authorizer_info['service_type_info']['id'];
				$verify_type		= $authorizer_info['verify_type_info']['id'];

				if(($service_type == 0 || $service_type == 1) && $verify_type == 0){
					$save['winxintype'] = 4;
				}else if($service_type == 2 && $verify_type == 0){
					$save['winxintype'] = 3;
				}else if($service_type == 2 && $verify_type == -1){
					$save['winxintype'] = 2;
				}else if(($service_type == 0 || $service_type == 1) && $verify_type == -1){
					$save['winxintype'] = 1;
				}

				$save['appid'] 						= $authorization_info['authorizer_appid'];
				$save['authorizer_access_token'] 	= $authorization_info['authorizer_access_token'];
				$save['authorizer_refresh_token'] 	= $authorization_info['authorizer_refresh_token'];
				$save['authorizer_expires'] 		= $authorization_info['expires_in']+time();

				if(M('Wxuser')->where($where)->save($save)){

					$update 	= array(
						'appid'	=> $save['appid'].'_no',
						'authorizer_access_token'=>'',
						'authorizer_refresh_token'=>'',
						'authorizer_expires'=>0
					);
					M('Wxuser')->where("appid = '{$save['appid']}' AND id != {$wxinfo['id']}")->save($update);
					$status 	= true;
				}

			}else{
				$status 	= $this->add_authorizer($authorizer_info,$authorization_info);
			}
			if(C('IS_MEIHUA')) {
				if ($status) {
					$clnum = M('Users')->where(array('id' => session('uid')))->setInc('bindnum');
					session('bindnum', $clnum);
					$this->success('公众号授权成功', U('Function/show', array('id' => $wxinfo['id'], 'token' => $wxinfo['token'])));
				} else {
					$this->error('公众号授权失败', U('Index/bind'));
				}
			}else {
				if ($status) {
					$this->success('公众号授权成功', U('Index/index'));
				} else {
					$this->error('公众号授权失败', U('Index/index'));
				}
			}
		}else{
			$this->error('授权错误',U('Index/index'));
		}
	}


	public function add_authorizer($authorizer_info,$authorization_info){
		$res 	= array();
		$res['type'] 		= 1;//公众号管理类型0 手动添加，1 第三方授权
		$res['uid'] 		= session('uid');
		$res['wxid'] 		= $authorizer_info['user_name'];
		$res['wxname'] 		= $authorizer_info['nick_name'];
		$res['weixin'] 		= $authorizer_info['alias'];
		$res['headerpic'] 	= empty($authorizer_info['head_img'])?'':$authorizer_info['head_img'];
		$service_type 		= $authorizer_info['service_type_info']['id'];
		$verify_type		= $authorizer_info['verify_type_info']['id'];

		if(($service_type == 0 || $service_type == 1) && $verify_type == 0){
			$res['winxintype'] = 4;
		}else if($service_type == 2 && $verify_type == 0){
			$res['winxintype'] = 3;
		}else if($service_type == 2 && $verify_type == -1){
			$res['winxintype'] = 2;
		}else if(($service_type == 0 || $service_type == 1) && $verify_type == -1){
			$res['winxintype'] = 1;
		}

		$res['appid'] 						= $authorization_info['authorizer_appid'];
		$res['authorizer_access_token'] 	= $authorization_info['authorizer_access_token'];
		$res['authorizer_refresh_token'] 	= $authorization_info['authorizer_refresh_token'];
		$res['authorizer_expires'] 			= $authorization_info['expires_in']+time();


		$data 	= M('User_group')->field('wechat_card_num')->where(array('id'=>session('gid')))->find();
		$users 	= M('Users')->where(array('id'=>session('uid')))->find();

		if ($this->isAgent){
			$needPay=0;
			if (($users['viptime']-$users['createtime']-20)>$this->reg_validDays*24*3600||$this->reg_validDays>30){
				$needPay=1;
			}
			if ($needPay){
				$pricebyMonth=intval($this->thisAgent['wxacountprice'])/12;
				$month=($users['viptime']-time())/(30*24*3600);
				$month=intval($month);
				$price=$month*$pricebyMonth;
				$price=intval($price);
				if ($price>$this->thisAgent['moneybalance']){
					$this->error('请联系您的站长处理');
				}
			}
		}

		if($users['wechat_card_num']>=$data['wechat_card_num']){
			$this->error('您的VIP等级所能创建的公众号数量已经到达上线，请购买后再创建',U('User/Index/index'));exit();
		}

		$db=D('Wxuser');

		if ($this->isAgent){
			$res['agentid']=$this->thisAgent['id'];
		}

		$randLength=43;
		$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		$aeskey=$randStr;
		$res['aeskey']=$aeskey;
		$res['encode']=2;

		$randLength=6;
		$chars='abcdefghijklmnopqrstuvwxyz';
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		$tokenvalue=$randStr.time();
		$res['token'] 		= $tokenvalue;

		$pigSecret=$this->get_token(20,0,1);
		$res['pigsecret'] 	= $pigSecret;

		$id=$db->add($res);
		if (C('server_topdomain')=='vifnn.cn'){
			$demoImport=new demoImport(session('uid'),$res['token'],$pigSecret,$id);
		}

		if($id){
			if ($this->isAgent){
				$wxuserCount=M('Wxuser')->where(array('agentid'=>$this->thisAgent['id']))->count();
				M('Agent')->where(array('id'=>$this->thisAgent['id']))->save(array('wxusercount'=>$wxuserCount));
				if ($this->thisAgent['wxacountprice']){
					//试用期内不扣费
					if ($needPay){
						$pricebyMonth=intval($this->thisAgent['wxacountprice'])/12;
						$month=($users['viptime']-time())/(30*24*3600);
						$month=intval($month);
						$price=$month*$pricebyMonth;
						M('Agent')->where(array('id'=>$this->thisAgent['id']))->setDec('moneybalance',$price);
						M('Agent_expenserecords')->add(array('agentid'=>$this->thisAgent['id'],'amount'=>(0-$price),'des'=>$this->user['username'].'(uid:'.$this->user['id'].')添加公众号'.$res['wxname'].'('.$month.'个月)','status'=>1,'time'=>time()));
					}
				}
			}
			$save 	= array(
				'appid'	=> $res['appid'].'_no',
				'authorizer_access_token'=>'',
				'authorizer_refresh_token'=>'',
				'authorizer_expires'=>0
			);
			M('Wxuser')->where("appid='{$res['appid']}' AND id != $id")->save($save);
			return true;
		}else{
			return false;
		}
	}
	function bomb_ajax(){
		if($_POST['id']){
			$where['id']=$_POST['id'];
			$token=$_POST['token'];
		}else{
			$where['token']=$_POST['token'];
			$token=$_POST['token'];
		}
		if(!empty($_POST['set_id'])){
			if(setcookie($token.'set_id',1,time()+24*3600*7,"/")){
				$this->ajaxReturn('1','JSON');
			};
		}else{
			$user_arr=M('Wxuser')->where($where)->field('winxintype')->find();
			if($user_arr['winxintype']=='3' && C('server_topdomain') == 'vifnn.cn' && empty($_COOKIE[$token.'set_id'])){
				$this->ajaxReturn('0','JSON');
			}else{
				$this->ajaxReturn('1','JSON');
			}
		}

	}
	
	public function ajax_help($group, $module, $action) {
		$data_g=$group;		
		$server = getUpdateServer();
		$users=M('Users')->where(array('id'=>$_SESSION['uid']))->find();
		if(C('close_help') == 1 && $users['sysuser'] == 0){
			$data_g='notingh';
		}else{
			$textHelp=0;
		//	if (C('server_topdomain')=='vifnn.cn' || $users['sysuser']){
		//		$textHelp=0;
		//	}
		}		
		$data = array(
				'key' => C('server_key'),
				'domain' => C('server_topdomain'),
				'is_text' => $textHelp,
				'data_g' => $data_g,
				'data_m' => $module,
				'data_a' => $action
		);
		if(!C('emergent_mode')):
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			curl_setopt($ch, CURLOPT_URL, $server . 'oa/admin.php?m=help&c=view&a=get_list&'.http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$content = curl_exec($ch);curl_close($ch);
		}else{
			$opts = array(
					'http'=>array(
							'method'=>'GET',
							'timeout'=>4,
					)
			);
			$fp= stream_context_create($opts);
			$content = file_get_contents( $server . 'oa/admin.php?m=help&c=view&a=get_list&'.http_build_query($data), false, $fp);
			fpassthru($fp);
		}
		endif;
		$content = json_decode($content,true);
		$html='';
		foreach ($content as $value) {
			if($value['id']==392){
			   continue;/***风驰的打印机已经不用了，去掉白盒包装打印机操作说明**/
			}
			$class = $value['is_video'] == 1 ? 'class="video"' : 'class="writing"';
			$html .= '<p class="lianjie zuoce_clear "><a '.$class.' href="javascript:openwin('."'".'/index.php?g=User&m=Index&a=help&helpParm=/oa/admin_help_'.$value['id'].'.html'."'".',768,960)">'.$value['title'].'</a></p>';
		}
		if (empty($html)) {
			$html = '<p class="lianjie zuoce_clear">没有帮助教程！</a>';
		}
		echo $html;
	}

	//开启统计
	public function openTongji()
	{
		if($this->userGroup['tj_status']!='1'){
			$this->error('没有操作权限');
		}
		if(empty($_POST['act_id'])||empty($_POST['act_name'])||empty($_POST['act_token'])){
			$this->error('活动ID和活动名称不能为空');
		}
		$wxuser=M('wxuser')->where(array('token'=>I('post.act_token'),'uid'=>$this->user['id']))->field('id,token')->find();
		if(empty($wxuser))
			$this->error('公众号不存在');
		$config=json_decode($this->user['tongji_config'],true);
		//创建账号或站点
		if(!$config||empty($config['site_id']))
		{
			$curl=new CurlClient();
			$data=array();
			if($config&&!empty($config['uid'])) {
				$data['uid']=$config['uid'];
				$time=time();
				$data['token']=sha1($config['password'].$time).$time;
			} else {
				//$data["username"] = $this->user["username"];
				//$data["email"] = $this->user["email"];
				//$data["mobile"] = $this->user["mp"];
				$data["username"] = 'vifnn';
				$data["email"] = 'vifnn@162.com';
				$data["mobile"] = '13221121211';
			}
		//	$data['name']=$this->user['agentid']>0?$this->thisAgent['sitename']:C('site_name');
			$data['domain']=$this->user['agentid']>0?$this->thisAgent['siteurl']:C('site_url');
			$data["name"] = '微风官网';
			$data['api']=$data['domain'].U('Home/Api/index');
			$data['token']=C('DATA_TOKEN');
			$result=$curl->setConfig('TONGJI_SERVICE')->post('/create',$data)->getData();
			if($result===false)
				$this->error($curl->getError());
			$config=array_merge(($config?$config:array()),$result);
			M('users')->where(array('id'=>$this->user['id']))->save(array('tongji_config'=>json_encode($config)));
			$key='tongji_config_'.$this->user['id'];
			S($key,null);
		}
		$tData['uid']=$this->user['id'];
		$tData['act_id']=I('post.act_id');
		$tData['act_name']=I('post.act_name');
		$tData['act_token']=$wxuser['token'];
		$tongjiModel=M('tongji');
		$tid=$tongjiModel->where($tData)->getField('id');
		if(!$tid) {
			$tData['status']='1';
			$tid=$tongjiModel->add($tData);
			if(!$tid)
				$this->error('开启失败');
		} else {
			$num=$tongjiModel->where(array('id'=>$tid))->save(array('status'=>'1'));
			if(!$num)
				$this->error('开启失败');
		}
		S('tongji_'.$tData['act_token'].'_'.$tData['act_name'].'_'.$tData['act_id'],'1');
		$this->ajaxReturn(array('status'=>1,'data'=>$this->makeGoUrl($tData['act_name'],$tData['act_id'],$tData['act_token'])));
	}

	//关闭统计
	public function closeTongji()
	{
		if($this->userGroup['tj_status']!='1'){
			$this->error('没有操作权限');
		}
		$act_token='';
		$act_name='';
		$act_id='';
		S('tongji_'.$act_token.'_'.$act_name.'_'.$act_id,'0');
	}

	//跳转到统计上
	public function goTongji()
	{
		if($this->userGroup['tj_status']!='1'){
			$this->error('没有操作权限');
		}
		$where['uid']=$this->user['id'];
		$where['act_id']=I('get.act_id');
		$where['act_token']=I('get.act_token');
		$where['act_name']=I('get.act_name');
		$tongji=D('Tongji');
		$result=$tongji->where($where)->find();
		if($result['status']=='1'){
			redirect($this->makeGoUrl($where['act_name'],$where['act_id'],$where['act_token']));
			exit();
		}
		$this->display();
	}

	//生成跳转到统计上的URL
	private function makeGoUrl($act_name,$act_id,$act_token)
	{
		$config=json_decode($this->user['tongji_config'],true);
		$time=time();
		$data['acc_uid']=$config['uid'];
		$data['acc_token']=sha1(md5($config['password']).$time).$time;
		$data['app_key']=$config['app_key'];
		$data['act_name']=$act_name;
		$data['act_id']=$act_id;
		$data['act_token']=$act_token;
		$url='http://'.C('TONGJI_DOMAIN').'/user?'.http_build_query($data);
		return $url;
	}


}
?>