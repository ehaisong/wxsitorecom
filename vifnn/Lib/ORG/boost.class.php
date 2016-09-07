<?php


class boost
{
	public $name;
	public $serverUrl;
	public $key;
	public $topdomain;
	public $token;
	public function __construct()
	{
		$this->serverUrl = 'http://www.meihua.com/';
		$this->key = trim(C('server_key'));
		$this->topdomain = trim(C('server_topdomain'));
		if (!$this->topdomain) {
			$this->topdomain = $this->getTopDomain();
		}
		$this->token = $token;
	}
	public function getServerUrl()
	{
		return $this->serverUrl;
	}
	public function getLink($item, $openid = '', $siteUrl = '')
	{
		if ($siteUrl == '') {
			$siteUrl = 'http://' . $_SERVER['SERVER_NAME'];
		}
		$thisUser = M('Game_config')->where(array('token' => $item['token']))->find();
		$link = $siteUrl . '/index.php?g=Wap&m=Boost&a=jump&token=' . $item['token'] . '&id=' . $item['boostid'] . '&uid=' . $thisUser['uid'] . '&uboostid=' . $item['id'] . '&wecha_id=' . $openid . '&url=' . $_SERVER['SERVER_NAME'];
		return $link;
	}
	public function config($token, $wxname, $wxid, $wxlogo, $link, $attentionText)
	{
		$data = array('username' => $this->topdomain . '_' . $token, 'wxname' => $wxname, 'domain' => $_SERVER['HTTP_HOST'], 'wxid' => $wxid, 'wxlogo' => urlencode($wxlogo), 'link' => urlencode($link), 'attentionText' => $attentionText);
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=newUser';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function boostCats()
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostCats';
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}
	public function scoredel($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=delrecord';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function boostList($catid, $what_boost, $type = '1')
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostList&catid=' . $catid . '&what_boost=' . $what_boost . '&domain=' . $this->topdomain . '&type=' . $type;
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}
	public function getBoost($id)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boost&id=' . $id;
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}
	public function boostSelfs($id, $uid, $uboostid, $key)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostSelfs&boostid=' . $id . '&uid=' . $uid . '&uboostid=' . $uboostid . '&key=' . $key;
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}
	public function boostSelfSet($uid, $boostid, $userBoostid, $boostortest = 'boost', $key = '', $data, $delete = 0)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostSelfSet&boostid=' . $boostid . '&uid=' . $uid . '&userboostid=' . $userBoostid . '&boostortest=' . $boostortest . '&key=' . $key . '&delete=' . $delete;
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function boostSet($uid, $boostid, $userBoostid, $key = '', $data = array(), $boostortest = 'boost')
	{
		if (empty($data)) {
			$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostSet&boostid=' . $boostid . '&uid=' . $uid . '&userboostid=' . $userBoostid . '&key=' . $key . '&type=get';
			$rt = $this->api_notice_increment($url);
		} else {
			$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostSet&boostid=' . $boostid . '&uid=' . $uid . '&userboostid=' . $userBoostid . '&boostortest=' . $boostortest . '&key=' . $key . '&type=set';
			$rt = $this->api_notice_increment($url, $data);
		}
		return json_decode($rt, 1);
	}
	public function fansInfo($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=user_info';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function RankingList($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=rangking';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function release($uid, $boostId, $uBoostId)
	{
		$data = array('uid' => $uid, 'boost_id' => $boostId, 'u_boost_id' => $uBoostId);
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=boostRelease';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function api_notice_increment($url, $data = '', $method = 'POST')
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);
		if ($errorno) {
			return Http::fsockopenDownload($url);
			return array('rt' => false, 'errorno' => $errorno);
		} else {
			return $tmpInfo;
		}
	}
	public function getTopDomain()
	{
		$host = $_SERVER['HTTP_HOST'];
		$host = strtolower($host);
		if (strpos($host, '/') !== false) {
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me');
		$str = '';
		foreach ($topleveldomaindb as $v) {
			$str .= ($str ? '|' : '') . $v;
		}
		$matchstr = '[^\\.]+\\.(?:(' . $str . ')|\\w{2}|((' . $str . ')\\.\\w{2}))$';
		if (preg_match('/' . $matchstr . '/ies', $host, $matchs)) {
			$domain = $matchs[0];
		} else {
			$domain = $host;
		}
		return $domain;
	}
	public function deleteBoost($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=deleteBoost';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function fans_pic_name($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=fans_pic_name';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getUserInfo($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getUserInfo';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getUserScoreData($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getUserScoreData';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getTokenFansInfo($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getTokenFansInfo';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getShareData($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getShareData';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getPrizeData($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getPrizeData';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getUserPrizeList($data)
	{
		$url = $this->serverUrl . "index.php?m=Boost&c=api&a=getUserPrizeList";
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getLastTime($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getLastTime';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getFansIdsByName($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getFansIdsByName';
		$rt = $this->api_notice_increment($url, $data);
		return $rt;
	}
	public function statistics($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=statistics';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function statisticsList($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=statisticsList';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}
	public function getTotalCoupon($data)
	{
		$url = $this->serverUrl . 'index.php?m=Boost&c=api&a=getTotalCoupon';
		$rt = $this->api_notice_increment($url, $data);
		return $rt;
	}
}