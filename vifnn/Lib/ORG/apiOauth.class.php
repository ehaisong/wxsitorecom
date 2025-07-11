<?php 

class apiOauth
{
	public $appId;
	
	public $appSecret;
	
	public $encodingAesKey;
	
	public $verify_ticket;
	
	public $component_verify_ticket;
	
	public $error;
	
	public $agentid = 0;
	
	function __construct(){
		
		$this->weixin_account();
	}
		
	public function weixin_account(){

		$this->agentid = 0;
		if (C('agent_version')) {
			$thisAgent = M('agent')->where(array('siteurl'=>'http://'.$_SERVER['HTTP_HOST']))->find();
			$this->agentid = isset($thisAgent['id']) ? intval($thisAgent['id']) : 0;
		}
		
		$account 	= M('Weixin_account')->where(array('type'=>1, 'agentid' => $this->agentid))->find();
		
		$this->appId 			= $account['appId'];
		
		$this->appSecret 		= $account['appSecret'];
		
		$this->encodingAesKey 	= $account['encodingAesKey'];
		
		$this->component_verify_ticket 	= $account['component_verify_ticket'];
		
	}
	
	public function webOauth($info,$scope='',$fansInfo=''){
		
		$now 	= time();
								
		//if((empty($info['web_access_token']) && empty($info['web_refresh_token']) && empty($info['web_expires'])) || $info['web_expires'] < $now ){
			
			$redirect_uri 	= getSelfUrl();
			
			$codeUrl 		= $this->get_code_url($info,$redirect_uri,$scope,'',$fansInfo);

			if(empty($_GET['code']) && empty($_GET['state'])){

				header("Location: $codeUrl"); 
				
				exit;
				
			}else{
				
				$code 	= $_GET['code'];

				if(!empty($code)){
					
					$res 	= $this->get_web_access_token($info,$code);

					if(empty($res['errcode'])){
						/*
						$save 	= array(
								'web_access_token' 	=> $res['access_token'],
								'web_refresh_token'	=> $res['refresh_token'],
								'web_expires'		=> $res['expires_in']+$now
							);
							
						M('Wxuser')->where("id={$info['id']}")->save($save);
						*/

						$data 	= array(
							'access_token'	=> $res['access_token'],
							'openid'		=> $res['openid'],
						);
						
						return $data;

					}else{

						//$this->error['-4'] = ' 网页授权错误:'.$res['errcode'];
						exit('授权错误，请检查公众号权限和设置');
					}
				}		
			}
		/*	
		}else{

			$res	= $this->get_refresh_web_access_token($info);
			
			if(empty($res['errcode'])){
			
				$save 	= array(
								'web_access_token' 	=> $res['access_token'],
								'web_refresh_token'	=> $res['refresh_token'],
								'web_expires'		=> $res['expires_in']+$now
							);
							
				M('Wxuser')->where("id={$info['id']}")->save($save);
				
				$data 	= array(
							'access_token'	=> $res['access_token'],
							'openid'		=> $res['openid'],
						);
				return $data;

			}else{

				$this->error['-4'] = ' 刷新授权错误:'.$res['errcode'];

			}
				
		}
		*/
			
	}
	
	public function get_fans_info($access_token,$openid){
		
		$url 	= 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		
		$res 	= $this->https_request($url);
		
		return $res;
		
	} 
	
	
	public function get_refresh_web_access_token($info){
		
		if (($info["type"] == 1) && (($info["winxintype"] == 3) || (($info["winxintype"] == 4) && ($info["type_of_media_or_gov"] == 1))) && ($info["oauth"] == 1)) {
			
			$component_access_token 	= $this->get_component_access_token();

			$tokenurl 	= 'https://api.weixin.qq.com/sns/oauth2/component/refresh_token?appid='.$info['appid'].'&grant_type=refresh_token&component_appid='.$this->appId.'&component_access_token='.$component_access_token.'&refresh_token='.$info['web_refresh_token'];
			
		}else{
			
			$tokenurl 	= 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$info['appid'].'&grant_type=refresh_token&refresh_token='.$info['web_refresh_token'];
			
		}	

		$res 	= $this->https_request($tokenurl);
		
		return $res;
		
	}
	
	public function get_web_access_token($info,$code){

		if (($info["type"] == 1) && (($info["winxintype"] == 3) || (($info["winxintype"] == 4) && ($info["type_of_media_or_gov"] == 1))) && ($info["oauth"] == 1)) {
			
			$component_access_token 	= $this->get_component_access_token();
			
			$tokenurl = 'https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$info['appid'].'&code='.$code.'&grant_type=authorization_code&component_appid='.$this->appId.'&component_access_token='.$component_access_token;
			
		}else{
			
			$tokenurl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$info['appid']."&secret=".$info['appsecret']."&code=".$code."&grant_type=authorization_code";
			
		}

		$res 	= $this->https_request($tokenurl);

		return $res;
		
	}
	
	public function get_code_url($info,$redirect_uri = '', $scope = '', $state = 'oauth',$fansInfo)
	{
		if(empty($scope)){
			if($info['oauthinfo'] && (( GROUP_NAME == 'Wap' && MODULE_NAME!='Index') || (MODULE_NAME == 'Index' && ACTION_NAME == 'content'))){
				$scope = 'snsapi_userinfo';
			}else{
				$scope = 'snsapi_base';
			}
		}
		
		$redirect_uri = urlencode($redirect_uri);
		
		$url 	= '';
		
		$url 	=  "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$info['appid']}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}";
		if (($info["type"] == 1) && (($info["winxintype"] == 3) || (($info["winxintype"] == 4) && ($info["type_of_media_or_gov"] == 1))) && ($info["oauth"] == 1)) {
			$url 	.= '&component_appid='.$this->appId;
			
		}
		
		$url .= '#wechat_redirect';

		return $url;
		
	}
	
	public function authorizerToken($appid, $info=null) {
		$now 	= time();

		if(empty($info)){
			$info 	= M('Wxuser')->where("appid='$appid'")->field('id,appid,appsecret,type,authorizer_access_token,authorizer_refresh_token,authorizer_expires,winxintype')->find();
		}

		$info['appid'] = trim($info['appid']);
		$info['appsecret'] = trim($info['appsecret']);
		$cache_token 	= 	S('authorizerToken_'.$info['appid']);
		if(!$cache_token['authorizer_access_token']){
			if($info['type'] == 1){
				$access_token 	= $this->get_component_access_token();
				$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.$access_token;
				$data 	= '{
							"component_appid":"'.$this->appId.'" ,
							"authorizer_appid": "'.$info['appid'].'",
							"authorizer_refresh_token":"'.$info['authorizer_refresh_token'].'"
						}';						
				$res 	= $this->https_request($url,$data);
				if($res['authorizer_access_token'] && $res['authorizer_refresh_token']){
					
					$cache 	= array(
						'authorizer_access_token' 	=> $res['authorizer_access_token'],
						'authorizer_expires'		=> $res['expires_in']
					);
					
					//M('Wxuser')->where("id={$info['id']}")->save($save);
					// $res['expires_in']
					S('authorizerToken_'.$info['appid'],$cache,5400);
					
					$cache_token 	= $cache['authorizer_access_token'];
				
				}else{

					S('authorizerToken_'.$info['appid'],NULL);

					$this->error['-3'] = '获取authorizer_access_token错误:'.$res['errcode'];
					
				}
				return $cache_token;
			} else {
				$this->error['-4'] = '未授权:'.$res['errcode'];
			}
		} else {
			return $cache_token['authorizer_access_token'];
		}
	}

	public function update_authorizer_access_token($appid,$info=null){
		
		$now 	= time();

		if(empty($info)){
			$info 	= M('Wxuser')->where("appid='$appid'")->field('id,appid,appsecret,type,authorizer_access_token,authorizer_refresh_token,authorizer_expires,winxintype')->find();
		}
		$info['appid'] = trim($info['appid']);
		$info['appsecret'] = trim($info['appsecret']);
		// 不重复获取
		$access_token_max = S('access_token_max_'.$info['appid']);
		if ($access_token_max) {
			if ($now >= $access_token_max) {
				S('access_token_max_'.$info['appid'], null);
			} else {
				$this->error['-3'] = '获取authorizer_access_token错误: access_token 超限！！';
			}
		}
		$this->checkTokenCache($info['appid']);

		$cache_token 	= 	S($info['appid']);

		if(!$cache_token['authorizer_access_token']){
			
			if (($info["type"] == 1) && (($info["winxintype"] == 3) || (($info["winxintype"] == 4) && ($info["type_of_media_or_gov"] == 1))) && empty($info["is_domain"]) && false) {
			
				$access_token 	= $this->get_component_access_token();

				$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.$access_token;
				
				$data 	= '{
							"component_appid":"'.$this->appId.'" ,
							"authorizer_appid": "'.$info['appid'].'",
							"authorizer_refresh_token":"'.$info['authorizer_refresh_token'].'"
						}';
						
				$res 	= $this->https_request($url,$data);
				
				if($res['authorizer_access_token'] && $res['authorizer_refresh_token']){
					
					$cache 	= array(
						'authorizer_access_token' 	=> $res['authorizer_access_token'],
						'authorizer_expires'		=> $res['expires_in']
					);
					
					//M('Wxuser')->where("id={$info['id']}")->save($save);
					//$res['expires_in']
					S($info['appid'],$cache,5400);
					
					$cache_token 	= $cache['authorizer_access_token'];
				
				}else{

					S($info['appid'],NULL);

					$this->error['-3'] = '获取authorizer_access_token错误:'.$res['errcode'];
					
				}
			
			}else{
			
				$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$info['appid'].'&secret='.$info['appsecret'];
				
				$res 	= $this->https_request($url_get);

				if($res['errcode']){
					
					S($info['appid'],NULL);
					// access_token 超限
					if (45009 == $res['errcode']) {
						S('access_token_max_'.$info['appid'], strtotime(date('Y-m-d', time()+84600)));
					}
					
					$this->error['-2'] = '获取access_token错误:'.$res['errcode'];
					
				}else{
					
					$cache 	= array(
								'authorizer_access_token' 	=> $res['access_token'],
								'authorizer_expires'		=> $res['expires_in']
							);
	
					//M('Wxuser')->where("id={$info['id']}")->save($save);
					//$res['expires_in']
					S($info['appid'],$cache, 5400);
					
					$cache_token 	= $res['access_token'];
				}
			}
			
			return $cache_token;
		}else{
			return $cache_token['authorizer_access_token'];
		}
	}
	//js_api签名凭证
	public function getAuthorizerTicket($appid,$access_token){
		
		$api_ticket 	= 	S($appid.'ticket');

		if(!$api_ticket['ticket']){

			$url 	= "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi";
			
			$res 	= $this->https_request($url);

			if($res['errcode']>0){
				$this->error['-10'] 	= array('errcode'=>$res['errcode'],'errmsg'=>$res['errmsg']);
			}else{
				$cache 	= array(
								'ticket' 		=> $res['ticket'],
								'expires_in'	=> $res['expires_in']
						);

				S($appid.'ticket',$cache,$res['expires_in']);
				
				$ticket 	= $res['ticket'];
			}

			return $ticket;
			
		}else{

			return $api_ticket['ticket'];

		}
	}
	
	public function checkTokenCache($appid) {
		$appidCheck = S($appid.'_check');
		if (!empty($appidCheck['ip_list'])) {
			return true;
		}		
		$cache 	= 	S($appid);
		
		$url_get= 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$cache['authorizer_access_token'];
		
		$res 	= $this->https_request($url_get);

		if($res['errcode'] == '40013' || $res['errcode'] == '40001'){
			S($appid,NULL);
			$this->error['-5'] = '获取access_token时AppSecret错误，或者access_token无效';
		} else {
			S($appid.'_check', $res, 900);
		}
	}

	public function get_authorizer_info($auth_appid){
		
		$access_token 	= $this->get_component_access_token();
		
		$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token='.$access_token;
		
		$data 	= '{
					"component_appid":"'.$this->appId.'",
					"authorizer_appid": "'.$auth_appid.'" 
				}';
				
		$res 	= $this->https_request($url,$data);
		
		return $res['authorizer_info'];
	}
	

	public function get_authorization_info($auth_code){
		
		$access_token 	= $this->get_component_access_token();
		
		$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='.$access_token;
		
		$data 	= '{
					"component_appid":"'.$this->appId.'" ,
					"authorization_code": "'.$auth_code.'"
				}';

		$res 	= $this->https_request($url,$data);
		
		return $res['authorization_info'];
	}
	
	public function start_authorization($redirect_uri){
		
		$access_token 	= $this->get_component_access_token();

		$pre_auth_code 	= $this->get_pre_auth_code($access_token);
		
		//$redirect_uri 	= urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

		$url 			= 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->appId.'&pre_auth_code='.$pre_auth_code.'&redirect_uri='.
		urlencode($redirect_uri);
		
		return $url;
	}
	
	public function get_pre_auth_code($access_token){
		
		$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$access_token;
		
		$data 	= '{
					"component_appid":"'.$this->appId.'" 
				}';
				
		$res 	= $this->https_request($url,$data);

		return $res['pre_auth_code'];
	}

  /*public function get_pre_auth_code($access_token){
		$now 	 	= time();
		$account 	= M('Weixin_account')->field('pre_auth_code,pre_code_expires')->where('type=1 AND agentid=' . $this->agentid)->find();
		
		if($account['pre_auth_code'] == '' || $account['pre_code_expires'] == '' || $account['pre_code_expires'] < $now){
			$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$access_token;
			$data 	= '{
					"component_appid":"'.$this->appId.'" 
				}';		
			$res 	= $this->https_request($url,$data);		
			if($res['errcode'] > 0){
				$this->error['-1'] = '获取pre_auth_code错误:'.$res['errcode'];				
			}else{
				M('Weixin_account')->where('type=1 AND agentid=' . $this->agentid)->save(array('pre_auth_code'=>$res['pre_auth_code'],'pre_code_expires'=>$now+$res['expires_in']));				
				$pre_auth_code 	= $res['pre_auth_code'];

			}		
		}else{			
			$pre_auth_code 	= $account['pre_auth_code'];			
		}	
		return $pre_auth_code;	
	}*/
	
	public function get_component_access_token(){
		
		$now 	 	= time();
		
		$account 	= M('Weixin_account')->field('component_access_token,token_expires')->where('type=1 AND agentid=' . $this->agentid)->find();

		if($account['component_access_token'] == '' || $account['token_expires'] == '' || $account['token_expires'] < $now){
			
			$url 	= 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
			
			$data 	= '{
						"component_appid":"'.$this->appId.'" ,
						"component_appsecret": "'.$this->appSecret.'", 
						"component_verify_ticket": "'.$this->component_verify_ticket.'"
					}';

			$res 	= $this->https_request($url,$data);

			if($res['errcode'] > 0){
				
				$this->error['-1'] = '获取component_access_token错误:'.$res['errcode'];
				
			}else{
				
				M('Weixin_account')->where('type=1 AND agentid=' . $this->agentid)->save(array('component_access_token'=>$res['component_access_token'],'token_expires'=>$now+$res['expires_in']));
				
				$token 	= $res['component_access_token'];
				
			}
			
		}else{
			
			$token 	= $account['component_access_token'];
			
		}

		return $token;
	}
	
	//更新微信会员卡信息
	public function update_user_card($wxAccessToken, $jsonData)
	{
		$url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token=" . $wxAccessToken;
	    $result = $this->https_request($url, $jsonData);
	    return $result;
	}
	
	
	public function get_error(){
		dump($this->error);
	}
	
	public function https_request($url, $data = null)
	{
		$curl = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($curl, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		F('1', $output);
		
		$errorno= curl_errno($curl);
		if ($errorno) {
			return array('curl'=>false,'errorno'=>$errorno);
		}else{
			$res = json_decode($output,1);

			if ($res['errcode']){
				return array('errcode'=>$res['errcode'],'errmsg'=>$res['errmsg']);
			}else{
				return $res;
			}
		}
		curl_close($curl);
	}
}

?>