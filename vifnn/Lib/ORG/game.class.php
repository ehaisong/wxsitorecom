<?php

class game
{
	public $name;
	public $serverUrl;
	public $key;
	public $topdomain;
	public $token;
	public $verifyCode;

	public function __construct()
	{
		$this->serverUrl = "http://www.meihua.com/";
		$this->key = '111111111111111111111';
		$this->topdomain = 'pigcms.cn';

		if (!$this->topdomain) {
			$this->topdomain = _getTopDomain();
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
		$link = $siteUrl . '/index.php?g=Wap&m=Game&a=jump&token=' . $item['token'] . '&id=' . $item['gameid'] . '&uid=' . $thisUser['uid'] . '&ugameid=' . $item['id'] . '&wecha_id=' . $openid . '&url=' . $_SERVER['SERVER_NAME'];
		return $link;
	}

	public function getTotalUse()
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=totalUse";
		$rt = $this->api_notice_increment($url);
		return $rt;
	}
	public function config($token, $wxname, $wxid, $wxlogo, $link, $attentionText, $userType = 0)
	{
		$data = array('username' => $this->topdomain . '_' . $token, 'wxname' => $wxname, 'domain' => $_SERVER['HTTP_HOST'], 'wxid' => $wxid, 'wxlogo' => urlencode($wxlogo), 'link' => urlencode($link), 'attentionText' => $attentionText, 'type' => $userType);
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=newUser';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function gameCats()
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameCats';
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}

	public function gameIndustyCats()
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameIndustyCats';
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}

	public function gameIndustyCatInfo()
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=gameIndustyCatInfo";
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}

	public function scoredel($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=delrecord';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function gameList($catid, $what_game, $type = '1', $gameType = '0')
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=gameList&industy_cat_id=" . $catid . "&what_game=" . $what_game . "&type=" . $type . "&game_type=" . $gameType;
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}

	public function getGame($id)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=game&id=' . $id;
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}

	public function gameSelfs($id, $uid, $ugameid, $key)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameSelfs&gameid=' . $id . '&uid=' . $uid . '&ugameid=' . $ugameid . '&key=' . $key;
		$rt = $this->api_notice_increment($url);
		return json_decode($rt, 1);
	}

	public function gameSelfSet($uid, $gameid, $userGameid, $gameortest = 'game', $key = '', $data, $delete = 0)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameSelfSet&gameid=' . $gameid . '&uid=' . $uid . '&usergameid=' . $userGameid . '&gameortest=' . $gameortest . '&key=' . $key . '&delete=' . $delete;
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function gameSet($uid, $gameid, $userGameid, $key = '', $data = array(), $gameortest = 'game')
	{
		if (empty($data)) {
			$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameSet&gameid=' . $gameid . '&uid=' . $uid . '&usergameid=' . $userGameid . '&key=' . $key . '&type=get';
			$rt = $this->api_notice_increment($url);
		}
		else {
			$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameSet&gameid=' . $gameid . '&uid=' . $uid . '&usergameid=' . $userGameid . '&gameortest=' . $gameortest . '&key=' . $key . '&type=set';
			$rt = $this->api_notice_increment($url, $data);
		}

		return json_decode($rt, 1);
	}

	public function gamePlayingSet($uid, $gameId, $uGameId, $data = array())
	{
		if (empty($data)) {
			$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gamePlayingSet&game_id=' . $gameId . '&uid=' . $uid . '&u_game_id=' . $uGameId . '&type=get';
			$rt = $this->api_notice_increment($url);
		}
		else {
			$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gamePlayingSet&game_id=' . $gameId . '&uid=' . $uid . '&u_game_id=' . $uGameId . '&type=set';
			$rt = $this->api_notice_increment($url, $data);
		}

		return json_decode($rt, 1);
	}

	public function gameOverSet($uid, $gameId, $uGameId, $data = array())
	{
		if (empty($data)) {
			$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameOverSet&game_id=' . $gameId . '&uid=' . $uid . '&u_game_id=' . $uGameId . '&type=get';
			$rt = $this->api_notice_increment($url);
		}
		else {
			$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameOverSet&game_id=' . $gameId . '&uid=' . $uid . '&u_game_id=' . $uGameId . '&type=set';
			$rt = $this->api_notice_increment($url, $data);
		}

		return json_decode($rt, 1);
	}

	public function fansInfo($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=user_info';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function RankingList($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=rangking';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function blackList($data)
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=blackList";
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function addToBlacklist($data)
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=addToBlacklist";
		$rt = $this->api_notice_increment($url, $data);
		return $rt;
	}

	public function deleteFromBlacklist($data)
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=deleteFromBlacklist";
		$rt = $this->api_notice_increment($url, $data);
		return $rt;
	}

	public function release($uid, $gameId, $uGameId)
	{
		$data = array('uid' => $uid, 'game_id' => $gameId, 'u_game_id' => $uGameId);
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=gameRelease';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function api_notice_increment($url, $data = '', $method = 'POST')
	{
		$verifyCode = D("Domain_verify_code")->getVerifyCode($this->topdomain, $_SERVER["SERVER_NAME"]);
		$url .= "&domain=" . $this->topdomain . "&host_name=" . $_SERVER["SERVER_NAME"] . "&verify_code=" . $verifyCode;
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
		}
		else {
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
		}
		else {
			$domain = $host;
		}

		return $domain;
	}

	public function deleteGame($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=deleteGame';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function fans_pic_name($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=fans_pic_name';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getUserInfo($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getUserInfo';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getConvertDataFromCms($data)
	{
		$url = $this->serverUrl . "index.php?m=Game&c=api&a=getConvertDataFromCms";
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getUserScoreData($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getUserScoreData';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getTokenFansInfo($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getTokenFansInfo';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getShareData($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getShareData';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getPrizeData($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getPrizeData';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getUserPrizeList($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getUserPrizeList';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function convertFansPrize($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=convertFansPrize';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getGamePlayingPrize($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getGamePlayingPrize';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getGameOverPrize($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getGameOverPrize';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getLastTime($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getLastTime';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getFansIdsByName($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getFansIdsByName';
		$rt = $this->api_notice_increment($url, $data);
		return $rt;
	}

	public function getOtherPrize($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getOtherPrize';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function statistics($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=statistics';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function statisticsList($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=statisticsList';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function getTotalCoupon($data)
	{
		$url = $this->serverUrl . 'index.php?m=Game&c=api&a=getTotalCoupon';
		$rt = $this->api_notice_increment($url, $data);
		return $rt;
	}
}


?>
