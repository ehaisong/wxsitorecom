<?php 
/**
* 发红包接口
**/
include_once("Hongbao_common.php");
class Hongbao extends Hongbao_common{
	public $nick_name;
	public $send_name;
	public $wishing;
	public $act_name;
	public $remark;
	public $key;
	public $mchid;
	public $sub_mch_id;
	public $msgappid;
	public $wxappid;
	public $parameters;
	public $openid;
	public $money;
	public $url;
	public $fissionurl;
	public $hbinfo;
	public $curl_timeout;
	public $token;
	public $info;
	public $weixin;
	public $weixinPay;
	public $wxuser;
	public $total_num;
	public $mch_billno;
	function __construct($config) {
		$this->token     = $config['token'];
		//获取普通商户微信配置信息
		$info = M('alipay_config')->where(array('token'=>$this->token,'open'=>1))->find();
		$this->info 	 = $info;
		$this->weixin	 = unserialize($this->info['info']);
		//获取特约商户配置信息
		$this->weixinPay = M('weixin_pay')->where(array('status'=>1))->find();
		//获取子商户号
		$this->wxuser = M('Wxuser')->where(array('token'=>$this->token))->find();
		//如果是特约商户号并且配置了子商户号
		if(!empty($this->weixinPay) && $this->wxuser['merchant_id'] != "" && (!isset($config['total_num']) || $config['total_num'] == "")){ 
			$this->mchid 	 =  trim($this->weixinPay['merchant_id']);
			$this->wxappid 	 = trim($this->weixinPay['appid']);
			$this->sub_mch_id = trim($this->wxuser['merchant_id']);
			$this->msgappid = 	trim($this->wxuser['appid']);
			$this->key 		 = trim($this->weixinPay['key']);
		}else{
			$this->mchid 	 = trim($this->weixin['weixin']['mchid']);
			$this->wxappid 	 = trim($this->weixin['weixin']['new_appid']);
			$this->sub_mch_id = '';//子商户号不填写默认走普通商户号
			$this->msgappid = '';
			$this->key 		 = trim($this->weixin['weixin']['key']);
		}
		$this->openid 	 = $config['openid'];
		$this->money  	 = (float)$config['money'];
		$this->total_num = ($config['total_num']) ? (int)$config['total_num'] : 1;
		$this->nick_name = (!empty($config['nick_name'])) ? $config['nick_name'] : '合体红包';
		$this->send_name = (!empty($config['send_name'])) ? $config['send_name'] : '合体红包';
		$this->wishing = (!empty($config['wishing'])) ? $config['wishing'] : '合体红包';
		$this->act_name = (!empty($config['act_name'])) ? $config['act_name'] : '合体红包';
		$this->remark = (!empty($config['remark'])) ? $config['remark'] : '合体红包';
		$this->mch_billno = $config['mch_billno'];
		$this->url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$this->fissionurl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
		$this->hbinfo = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';
		$this->curl_timeout = 60;
	}
	public function send(){
		if(empty($this->token) || empty($this->openid) || empty($this->money)){
			return json_encode(array('status'=>'FAIL','msg'=>'Token,用户openid,金额不能为空'));
		}
		if(empty($this->info)){
			return json_encode(array('status'=>'FAIL','msg'=>'未获取到微信配置信息'));
		}
		if($this->key == ""){
			return json_encode(array('status'=>'FAIL','msg'=>'密钥不能为空'));
		}elseif($this->mchid == ""){
			return json_encode(array('status'=>'FAIL','msg'=>'商户号不能为空'));
		}elseif($this->wxappid == ""){
			return json_encode(array('status'=>'FAIL','msg'=>'公众账号ID不能为空'));
		}
		if($this->sub_mch_id != ""){
			if($this->msgappid == ""){
				return json_encode(array('status'=>'FAIL','msg'=>'子商户公众号ID不能为空'));
			}
		}
		$this->parameters = array();
		$this->setParameter('nonce_str',$this->createNoncestr());
		$this->setParameter('mch_id',$this->mchid);
		$this->setParameter('sub_mch_id',$this->sub_mch_id);
		$this->setParameter('wxappid',$this->wxappid);
		$this->setParameter('msgappid',$this->msgappid);
		$this->setParameter('nick_name',$this->nick_name);
		$this->setParameter('send_name',$this->send_name);
		$this->setParameter('total_num',1);
		$this->setParameter('wishing',$this->wishing);
		$this->setParameter('act_name',$this->act_name);
		$this->setParameter('remark',$this->remark);
		$this->setParameter('mch_billno',(string)$this->mchid .date("YmdHis",time()).rand(1000,9999));
		$this->setParameter('re_openid',(string)$this->openid);
		$this->setParameter('total_amount',($this->money*100));
		$this->setParameter('client_ip',get_client_ip());
		$this->setParameter('sign',$this->getSign($this->parameters));
		$xml = $this->createXml(1);
		$response_xml = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
		$curl = json_decode($response_xml,true);
		if($curl['status'] == 'FAIL'){
			return $response_xml;
		}else{
			$respon_array = $this->xmlToarray($response_xml);
			if($respon_array['result_code'] == 'FAIL'){
				return json_encode(array('status'=>'FAIL','msg'=>$respon_array['return_msg']));
			}else{
				return json_encode(array('status'=>'SUCCESS','msg'=>'领取成功','mch_billno'=>$this->parameters['mch_billno']));
			}
		}
	}
	public function FissionSend(){
		if(empty($this->token) || empty($this->openid) || empty($this->money)){
			return json_encode(array('status'=>'FAIL','msg'=>'Token,用户openid,金额不能为空'));
		}
		if(empty($this->info)){
			return json_encode(array('status'=>'FAIL','msg'=>'未获取到微信配置信息'));
		}
		if(empty($this->weixin['weixin']['key']) || empty($this->weixin['weixin']['mchid']) || empty($this->weixin['weixin']['new_appid'])){
			return json_encode(array('status'=>'FAIL','msg'=>'密钥或商户号或公众号不能为空'));
		}
		$this->parameters = array();
		$this->setParameter('mch_billno',(string)$this->mchid .date("YmdHis",time()).rand(1000,9999));
		$this->setParameter('mch_id',$this->mchid);
		$this->setParameter('wxappid',$this->wxappid);
		$this->setParameter('send_name',$this->send_name);
		$this->setParameter('re_openid',(string)$this->openid);
		$this->setParameter('total_amount',$this->money*100);
		$this->setParameter('amt_type','ALL_RAND');
		$this->setParameter('total_num',(string)$this->total_num);
		$this->setParameter('wishing',$this->wishing);
		$this->setParameter('act_name',$this->act_name);
		$this->setParameter('remark',$this->remark);
		$this->setParameter('nonce_str',$this->createNoncestr());
		$this->setParameter('sign',$this->getSign($this->parameters));
		$postxml = $this->createXml(2);
		if(is_array($postxml) && $postxml['status'] == 'fail'){
			return json_encode(array('status'=>'FAIL','msg'=>$postxml['msg']));
		}else{
			$response_xml = $this->postXmlSSLCurl($postxml,$this->fissionurl,$this->curl_timeout);
			$curl = json_decode($response_xml,true);
			if($curl['status'] == 'FAIL'){
				return $response_xml;
			}else{
				$respon_array = $this->xmlToarray($response_xml);
				if($respon_array['result_code'] == 'FAIL'){
					return json_encode(array('status'=>'FAIL','msg'=>$respon_array['return_msg']));
				}else{
					return json_encode(array('status'=>'SUCCESS','msg'=>'领取成功','mch_billno'=>$this->parameters['mch_billno']));
				}
			}
		}
	}
	public function hongbao_record(){
		if(empty($this->token) || empty($this->mch_billno)){
			return json_encode(array('status'=>'FAIL','msg'=>'Token,商户订单号不能为空'));
		}
		if(empty($this->info)){
			return json_encode(array('status'=>'FAIL','msg'=>'未获取到微信配置信息'));
		}
		if(empty($this->weixin['weixin']['key']) || empty($this->weixin['weixin']['mchid']) || empty($this->weixin['weixin']['new_appid'])){
			return json_encode(array('status'=>'FAIL','msg'=>'密钥或商户号或公众号不能为空'));
		}
		$this->parameters = array();
		$this->setParameter('nonce_str',$this->createNoncestr());
		$this->setParameter('mch_id',$this->mchid);
		$this->setParameter('appid',$this->wxappid);
		$this->setParameter('mch_billno',$this->mch_billno);
		$this->setParameter('bill_type','MCHT');
		$this->setParameter('sign',$this->getSign($this->parameters));
		$xml = $this->arrayToXml($this->parameters);
		$response_xml = $this->postXmlSSLCurl($xml,$this->hbinfo,$this->curl_timeout);
		$curl = json_decode($response_xml,true);
		if($curl['status'] == 'FAIL'){
			return $response_xml;
		}else{
			$respon_array = $this->xmlToarray($response_xml);
			if($respon_array['return_code'] == 'FAIL'){
				return json_encode(array('status'=>'FAIL','msg'=>$respon_array['return_msg']));
			}else{
				return json_encode(array('status'=>'SUCCESS','msg'=>$respon_array));
			}
		}
	}
	//生成xml
	public function createXml($type = 1){
		//检测参数
		$msg = '';
		if($this->money > 200 || $this->money < 1){
			$msg = '单个红包金额介于[1.00元，200.00元]之间';
			
		}elseif($this->parameters["mch_id"] == null) {
			$msg = '发红包接口中，缺少必填参数mch_id';
			
		}elseif($this->parameters["wxappid"] == null){
			$msg = '发红包接口中，缺少必填参数wxappid';
			
		}elseif($this->parameters["nick_name"] == null && $type == 1){
			$msg = '发红包接口中，缺少必填参数nick_name';
			
		}elseif($this->parameters["send_name"] == null){
			$msg = '发红包接口中，缺少必填参数send_name';
			
		}elseif($this->parameters["wishing"] == null){
			$msg = '发红包接口中，缺少必填参数wishing';
			
		}elseif($this->parameters["total_num"] == null){
			$msg = '发红包接口中，缺少必填参数total_num';
			
		}elseif($this->parameters["act_name"] == null){
			$msg = '发红包接口中，缺少必填参数act_name';
			
		}elseif($this->parameters["remark"] == null){
			$msg = '发红包接口中，缺少必填参数remark';
		}
		if($msg != ""){
			return array('status'=>'fail','msg'=>$msg);
		}
		return $this->arrayToXml($this->parameters);
	}
	//设置键值对
	public function setParameter($parameter, $parameterValue) {
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}
	
	//根据参数生成签名
	public function getSign($array){
		$parames = array();
		foreach((array)$array as $k => $v){
			//将非空参数组成集合
			if(!empty($v)){
				$parames[$k] = $v;
			}
		}
		//降序排序数组
		ksort($parames);
		$temp_s = '';
		foreach((array)$parames as $key => $val){
			$temp_s .= $key.'='.$val.'&';
		}
		$reqPar;
		if (strlen($temp_s) > 0) {
			$reqPar = substr($temp_s, 0, strlen($temp_s)-1);
		}
		//参数集合组合商户密钥
		$string = $reqPar.'&key='.$this->key ;
		//MD5加密后转换为大写
		$signValue = strtoupper(md5($string));
		return $signValue;
	}
	
	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url
	 */
	function postXmlSSLCurl($xml,$url,$second=30)
	{
		if($this->sub_mch_id == "" && $this->msgappid == ""){
			$cert = M('wxcert')->where(array('token'=>$this->token))->find();
			$msg = '商户未上传证书文件';
		}else{
			$cert = $this->weixinPay;
			$msg = '特约商户未上传证书文件';
		}
		if(empty($cert['apiclient_cert']) || empty($cert['apiclient_key']) || empty($cert['rootca'])){
			return json_encode(array('status'=>'FAIL','msg'=>$msg));
		}else{
			$apiclient_cert = str_replace(array('http://',$_SERVER['HTTP_HOST']),'',$cert['apiclient_cert']);
			$apiclient_key = str_replace(array('http://',$_SERVER['HTTP_HOST']),'',$cert['apiclient_key']);
			$rootca =  str_replace(array('http://',$_SERVER['HTTP_HOST']),'',$cert['rootca']);
		}
		/* echo getcwd().$apiclient_cert;
		echo '<br />';
		echo getcwd().$apiclient_key;
		echo '<br />';
		echo getcwd().$rootca;
		exit; */
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,getcwd().$apiclient_cert);
 		curl_setopt($ch,CURLOPT_SSLKEY,getcwd().$apiclient_key);
 		curl_setopt($ch,CURLOPT_CAINFO,getcwd().$rootca);
		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_error($ch);
			curl_close($ch);
			return json_encode(array('status'=>'FAIL','msg'=>$error));
		}
	}
}