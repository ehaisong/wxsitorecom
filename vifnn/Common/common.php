<?php
$enumConfig=require APP_PATH.'Common/enum.php';
foreach ($enumConfig as $key=>$value)
{
	C($key,$value);
}

function isAndroid(){
	if(strstr($_SERVER['HTTP_USER_AGENT'],'Android')) {
		return 1;
	}
	return 0;
}

function arr_htmlspecialchars($value){
	return $value;
	return is_array($value) ? array_map('arr_htmlspecialchars', $value) : htmlspecialchars($value);
}

function urlGetTpl($action){
	$url_content = curl_get_tpl($action);
	return $url_content;
}

/**
 * 2015-05-21	兼容老版本 为  staticPath == 0 || 1 || true 都为本地资源
 * 获取静态资源的链接	调用方法 $staticPath = getStaticPath();
 * @return String 
 */
function getStaticPath() {
	$staticPath = C('STATICS_PATH');
	if ('1' == $staticPath) {
		$staticPath = C('SITE_URL');
	} else {
		$staticPath =  C('STATICS_PATH') ? C('STATICS_PATH') : 'http://s.404.cn';
	}
	return $staticPath;
}

/**
 * 转换支付类型
 */
function getPayType($type) {
	$payType = array(
		'alipay' => '支付宝',
		'weixin' => '微信',
		'tenpay' => '财付通',
		'tenpaycomputer' => '财付通',
		'yeepay' => '易宝支付',
		'allinpay' => '通联支付',
		'daofu' => '货到付款',
		'dianfu' => '到店付款',
		'chinabank' => '网银在线',
		'cardpay' => '会员卡',
	);
	$type = strtolower($type);
	return $payType[$type]; 
}
/**
 * 获取当前URL
 * @return string
 */
function getSelfUrl($params = array(), $url = '') {
	$secure = isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'],'on')===0 || $_SERVER['HTTPS']==1)	|| isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'],'https')===0;
	$hostInfo = '';
	if($secure) {
		$http='https';
	} else {
		$http='http';
	}
	if(isset($_SERVER['HTTP_HOST'])) {
		$hostInfo=$http.'://'.$_SERVER['HTTP_HOST'];
	} else {
		$hostInfo=$http.'://'.$_SERVER['SERVER_NAME'];
		if ($secure) {
			$port = isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
		} else {
			$port = isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 80;
		}
		if(($port!==80 && !$secure) || ($port!==443 && $secure)) {
			$hostInfo.=':'.$port;
		}
	}
	$requestUri = '';
	if(isset($_SERVER['HTTP_X_REWRITE_URL'])) {// IIS
		$requestUri=$_SERVER['HTTP_X_REWRITE_URL'];
	} elseif (isset($_SERVER['REQUEST_URI'])) {
		$requestUri=$_SERVER['REQUEST_URI'];
		if(!empty($_SERVER['HTTP_HOST'])) {
			if(strpos($requestUri,$_SERVER['HTTP_HOST'])!==false) {
				$requestUri=preg_replace('/^\w+:\/\/[^\/]+/','',$requestUri);
			}
		}
		else {
			$requestUri=preg_replace('/^(http|https):\/\/[^\/]+/i','',$requestUri);
		}
	} elseif(isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 CGI
		$requestUri=$_SERVER['ORIG_PATH_INFO'];
		if(!empty($_SERVER['QUERY_STRING'])) {
			$requestUri.='?'.$_SERVER['QUERY_STRING'];
		}
	} else {
		exit('没有获取到当前的url');
	}
	if (empty($url)) {		
		$url =  $hostInfo.$requestUri;
	}
	$parseUrl = parse_url($url);
	parse_str(htmlspecialchars_decode($parseUrl['query']), $query);
	foreach ($params as $key => $param) {
		$value = isset($query[$param]) ? $query[$param] : '';
		if (1 == count($query)) {
			$value = '\?'.$param.'='.$value;
		} else {
			$value = '&'.$param.'='.$value.'|'.$param.'='.$value.'&';
		}
		$url = preg_replace("/$value/i", '', $url);
	}
	return $url;
}

/**
 * 去除BOM
 */
function removeUTF8Bom($string)
{
    if(substr($string, 0, 3) == pack('CCC', 239, 187, 191)) return substr($string, 3);
    return $string;
}

function shareFilter($subject) {
	$subject = str_replace("'", "", $subject);
	$subject = str_replace("\"", "", $subject);
	$subject = str_replace("\r", "", $subject);
	$subject = str_replace("\n", "", $subject);
	$subject = str_replace("\t", " ", $subject);
	return trim($subject);
}
/**
 * 转换微信内容
 * @param String $subject
 * @return mixed
 */
function filterWeiXinContent($subject) {
	$subject = str_replace('\'','&apos;',$subject);
	$subject = str_replace("\r\n", '', $subject);
	$subject = str_replace("\n\r", '', $subject);
	$subject = str_replace("\r", '', $subject);
	$subject = str_replace("\n", '', $subject);
	$subject = str_replace("\t", '', $subject);
	return $subject;
}
function nulltoblank($v){
	if($v === NULL){
		return $v = '';
	}else{
		return $v;
	}
}




function dstrlen($str)
{
	$count = 0;
	for ($i = 0; $i < strlen($str); $i++) {
		$value = ord($str[$i]);
		if ($value > 127) {
			if ($value >= 192 && $value <= 223) {
				$i++;
			} elseif ($value >= 224 && $value <= 239) {
				$i = $i + 2;
			} elseif ($value >= 240 && $value <= 247) {
				$i = $i + 3;
			}
		}
		$count++;
	}
	return $count;
}

function filterPost(&$post, $params = array()) {
	foreach ($post as $key => $value) {
		if ('up_password' == $key) {
			$value = base64_encode($value);
			$value = "'.base64_decode('".$value."').'";
			$post[$key] = $value;
			continue;
		}
		if ('VAR_FILTERS' == $key) {
			if (!function_exists($value)) {
				$value = C('VAR_FILTERS');
				$post[$key] = $value;
				continue;
			}
		}
		$value = trim($value);
		$value = str_replace("'", "", $value);
		$value = str_replace('"', "", $value);
		$value = str_replace('\\', "", $value);
		$value = str_replace("&quot;", "", $value);
		$post[$key] = $value;
	}
	return $post;
}

/**
 * 获取升级服务器地址
 */
function getUpdateServer()
{
	return C("update_server") ? C("update_server") : "http://gongdan.weihubao.com/";
}

/**
 * 高效取出两个数组的差集
 */
function array_diff_fast($array_1, $array_2) {
    $array_2 = array_flip($array_2);
    foreach ($array_1 as $key => $item) {
        if (isset($array_2[$item])) {
            unset($array_1[$key]);
        }
     }

    return $array_1;
}
/**
 * 获取指定范围的随机金额
 */
function rand_money($min,$max){
	$rand =  $min + mt_rand() / mt_getrandmax() * ($max - $min);
	return round($rand,2);
}

/**
 * 把HTMl代码转换为实体,支持多维数组递归转换
 *
 * @param  mixed  $value
 * @return string
 */
	function e($value)
	{
		return is_array($value) ? array_map('e', $value) :  htmlentities($value, ENT_QUOTES, 'UTF-8', false);
	}


	function _getTopDomain()
	{

		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT']=='80' ? '' : ':'.$_SERVER['SERVER_PORT']));
		
		$host=strtolower($host);
		if(strpos($host,'/')!==false){
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb=array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me','co','tech');
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

		$domainList = array('weihubao.com', 'dazhongbanben.com', 'webcms.cn');

		if (in_array($domain, $domainList)) return $host;

		return $domain;
	}


	/**
 * 备案号外链,工业和信息化部（工信部）网站地址
 */
	function icp_link()
	{
		return 'http://www.miitbeian.gov.cn';
	}
	/**
	 * 计划任务服务器地址
	 */
	function task_link(){
		return 'http://cron.weihubao.com';
	}

function time2string($second)
{
	$day = (floor($second / (3600 * 24)) != 0 ? floor($second / (3600 * 24)) . "天" : "");
	$second = $second % (3600 * 24);
	$hour = (floor($second / 3600) != 0 ? floor($second / 3600) . "小时" : "");
	$second = $second % 3600;
	$minute = (floor($second / 60) != 0 ? floor($second / 60) . "分" : "");
	$second = $second % 60;
	$second = $second;
	return $day . $hour . $minute . $second . "秒";
}
require APP_PATH.'Common/business.php';//@V-I-F-N-N.C-O-M
?>
