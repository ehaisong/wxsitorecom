<?php

class WeixinModel
{
	protected $config;
	protected $apiConfig;
	protected $reqUrl;
	protected $reqData;
	protected $reqResult;
	protected $error;
	protected $happenError;
	protected $accessToken;
	protected $params = array();

	public function __construct($config = array())
	{
		$this->config = $config;
		$this->apiConfig = C('WEIXIN_API_CONFIG');
	}

	public function localToWeixin($token)
	{
		$wxuserModel = M('Wxuser');
		$apiOauth = new apiOauth();
		$wxuser = $wxuserModel->where(array('token' => $token))->field('appid')->find();
		$this->accessToken = $apiOauth->update_authorizer_access_token($wxuser['appid']);
		return $this;
	}

	public function setToken($token)
	{
		$this->accessToken = $token;
		return $this;
	}

	public function getToken()
	{
		return $this->accessToken;
	}

	public function setParams($params)
	{
		$this->params = isset($this->params) ? array_merge($this->params, $params) : $params;
		return $this;
	}

	public function post($api, $data = array(), $headers = NULL)
	{
		return $this->curl($api, $data, 'post', $headers);
	}

	public function get($api, $data = array(), $headers = NULL)
	{
		return $this->curl($api, $data, 'get', $headers);
	}

	public function curl($api, $data = array(), $type = 'post', $headers = NULL, $retries = 1, $timeout = 0, $ca = false)
	{
		$this->happenError = false;
		$this->reqResult = NULL;
		$url = (isset($this->apiConfig[$api]) ? $this->apiConfig[$api]['url'] : $api);
		$url = str_replace('{$token}', $this->accessToken, $url);

		foreach ($this->params as $key => $value) {
			$url = str_replace('{$' . $key . '}', $value, $url);
		}

		if (($type == 'get') && !empty($data)) {
			$query = http_build_query($data);
			$url = (stripos($url, '?') === false ? $url . '?' . $query : $url . '&' . $query);
		}

		$this->reqUrl = $url;
		$this->reqData = $data;
		$curl = curl_init($url);

		if (is_resource($curl) === true) {
			$cacert = getcwd() . '/cacert.pem';
			$ssl = (strtolower(substr($url, 0, 8)) == 'https://' ? true : false);
			curl_setopt($curl, CURLOPT_FAILONERROR, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			if ($ssl && $ca) {
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($curl, CURLOPT_CAINFO, $cacert);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			}
			else {
				if ($ssl && !$ca) {
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
				}
			}

			if (isset($timeout) && (0 < $timeout)) {
				curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			}

			if ($type == 'post') {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}

			if (isset($headers) && !empty($headers)) {
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			}

			$result = false;
			$num = 0;

			while ($num < $retries) {
				$result = curl_exec($curl);

				if ($result !== false) {
					break;
				}

				$num++;
			}

			$info = curl_getinfo($curl);
			curl_close($curl);
			if (empty($result) || empty($info)) {
				$this->error = '发送请求失败';
				$this->happenError = true;
			}

			if ($info['http_code'] != '200') {
				$this->error = '发送请求失败,HTTP_CODE:' . $info['http_code'];
				$this->happenError = true;
			}

			$this->reqResult = $result;
		}
		else {
			$this->error = 'CURL初始化失败';
			$this->happenError = true;
		}

		return $this;
	}

	static public function apiAllow($type, $api)
	{
		$apiConfig = C('WEIXIN_API_CONFIG');
		$apiConfig = $apiConfig[$api];
	}

	public function getError()
	{
		return $this->error;
	}

	public function getReqUrl()
	{
		return $this->reqUrl;
	}

	public function getReqData()
	{
		return $this->reqData;
	}

	public function getJson()
	{
		if ($this->happenError) {
			return false;
		}

		$resultData = object_to_array(json_decode($this->reqResult));

		if (is_null($resultData)) {
			$this->error = '返回数据无效';
			return false;
		}

		if (isset($resultData['errcode']) && ($resultData['errcode'] != 0)) {
			$this->error = $resultData['errmsg'];
			return false;
		}

		return $resultData;
	}

	public function jsonEncode($data)
	{
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function getResult()
	{
		if ($this->happenError) {
			return false;
		}

		return $this->reqResult;
	}
}


?>
