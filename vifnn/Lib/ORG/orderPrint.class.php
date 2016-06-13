<?php
class orderPrint {

	public $serverUrl;
	public $key;
	public $topdomain;
	public $token;
	public function __construct($token){
		$this->serverUrl='http://up.vifnn.com/';
		$this->key=trim(C('server_key'));
		$this->topdomain=trim(C('server_topdomain'));
		if (!$this->topdomain){
			$this->topdomain=$this->getTopDomain();
		}
		$this->token=$token;
	}
	public function printit($token, $companyid=0, $ordertype='', $content = '', $paid=0, $companyname_arr = array(), $companyname_other_arr=array()){
            
		$UYIN_SERVER = "printer.showutech.com";
		
		$companyid=intval($companyid);
		if ($companyid){
			$printers=M('Orderprinter')->where(array('token'=>$token,'companyid'=>$companyid))->select();
		}else {
			$printers=M('Orderprinter')->where(array('token'=>$token))->select();
		}
		$usePrinters=array();
		if ($printers){
			foreach ($printers as $p){
				$ms=explode(',',$p['modules']);
				if (in_array($ordertype,$ms)){
					array_push($usePrinters,$p);
				}
			}
		}
		
		// echo $usePrinters['orderid'];
		if ($usePrinters){
			foreach ($usePrinters as $p){
				if (!$p['paid']||($p['paid']&&$paid)){
				
					$print_total = $p['count'];
					if( $print_total < 1 )
							$print_total = 1;
			
					if(strlen($p['mkey'])>0&&strlen($p['mkey'])<49){
						$qrlength=chr(strlen($p['mkey']));
						$qrcode ="\n";
						$qrcode.="^Q";
						$qrcode.=$qrlength;
						$qrcode.=$p['mkey'];
					}
					
					$message = "^N".$print_total."\n".$qrcode."\n".$content;
					if(strlen($p['mcode'])>0)
						$result =$this -> add_order($p['mcode'],1, $message,$UYIN_SERVER);
					if(strlen($p['mp'])>0)
						$result =$this -> add_order($p['mp'], 1, $message,$UYIN_SERVER);
				}
			}
		}
	}
	public function add_order($device_no, $order_id, $msg,$UYIN_SERVER) {
		$url = "http://$UYIN_SERVER/api/1/service/add_order/$device_no/$order_id/";
		$data = array('data' => $msg);

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		
		// var_dump($result);
		
		// return $result;
	} 
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return $errorno;
		}else{
			return $tmpInfo;
		}
	}
	function getTopDomain(){
		$host=$_SERVER['HTTP_HOST'];
		$host=strtolower($host);
		if(strpos($host,'/')!==false){
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str='';
		foreach($topleveldomaindb as $v){
			$str.=($str ? '|' : '').$v;
		}
		$matchstr="[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if(preg_match("/".$matchstr."/ies",$host,$matchs)){
			$domain=$matchs['0'];
		}else{
			$domain=$host;
		}
		return $domain;
	}
}
