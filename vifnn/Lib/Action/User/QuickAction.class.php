<?php
class QuickAction extends UserAction{
	public function index(){
		$db=M('Wxuser');
		$dbs=M('Home');
		$where['token']=$this->token;
		if(IS_POST){
			$data1['qr']=$this->_POST('qr');
			$data1['hurl']=htmlspecialchars_decode($this->_POST('hurl'));
			$save1 = $db->where($where)->save($data1);
			$dbs->where($where)->save(array('gzhurl'=>$data1['hurl']));
			if($save1){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else{
			$list=$db->where($where)->find();
			if($list['hurl'] == ''){
				$hurl=$dbs->where($where)->getField('gzhurl');
				if($hurl != ''){
					$data2['hurl']=$hurl;
					$save2=$db->where($where)->save($data2);
				}
			}
			$this->assign('list',$list);
			$this->assign('hurl',$hurl);
			$this->display();
		}
	}

	public function set(){
		$this->display();
	}
	public function cpshortlink(){ //copyshortlink不能过云锁，修改过cpshortlink
		if(IS_POST){
			$longurl = trim($_POST['longurl']);
			$longurl = $this->convertLink($longurl);
			$apiOauth 	= new apiOauth();
			$where=array('token'=>$_POST['token']);
			$thisWxUser=M('Wxuser')->where($where)->find();
			$access_token 	= $apiOauth->update_authorizer_access_token($thisWxUser['appid']);
			$postUrl = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token='.$access_token;
			$postData = array('action'=>'long2short','long_url'=>$longurl);
			$res = $this->curlPost($postUrl,json_encode($postData));
			if($res['rt'] && $res['errorno'] == 0){
				echo json_encode(array('status'=>'success','short_url'=>$res['short_url']));
				exit;
				//exit($res['short_url']);
			}else{
				echo json_encode(array('status'=>'error','longurl'=>$longurl));
				exit;
				//exit('fail');
			}
		}
		$this->assign('token',$this->token);
		$this->display();
	}

	function curlPost($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$res_arr=json_decode($result,true);
			if (intval($res_arr['errcode']==0)){
				return array('rt'=>true,'errorno'=>0,'short_url'=>$res_arr['short_url']);
			}else {
				return array('rt'=>false,'errorno'=>$res_arr['errcode'],'errmsg'=>$res_arr['errmsg']);
			}
		}
	}
}
?>