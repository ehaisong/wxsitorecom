<?php
class CurlClient
{
	private $config;
	private $result;
	private $reqError;
	private $error;
	private $happenError;
	private $reqUrl;
	public function __construct()
	{
		$this->config = array('timeout' => 0, 'retries' => 0, 'params' => array());
		$this->config['user_agent'] = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36';
		$this->config['cookie'] = '';
		$this->happenError = false;
	}
	public function setConfig($name)
	{
		$name = is_array($name) ? $name : C($name);
		$this->config = array_merge($this->config, $name);
		return $this;
	}
	public function setGetParams($params)
	{
		$params = is_array($params) ? $params : C($params);
		$this->config['params'] = array_merge($this->config['params'], $params);
		return $this;
	}
	public function setUserAgent($userAgent = '')
	{
		$this->config['user_agent'] = $userAgent;
		return $this;
	}
	public function setCookie($cookie = '')
	{
		$this->config['cookie'] = $cookie;
		return $this;
	}
	public function get($url, $timeout = NULL, $retries = NULL)
	{
		return $this->curl($url, 'get', NULL, $timeout, $retries);
	}
	public function post($url, $data = '', $timeout = NULL, $retries = NULL)
	{
		return $this->curl($url, 'post', $data, $timeout, $retries);
	}
	public function curl($url = '', $type = 'get', $data = '', $timeout = NULL, $retries = NULL)
	{
		$this->happenError = false;
		if ($type == 'post') {
			if (is_string($data)) {
				$tmp = array();
				parse_str($data, $tmp);
				$data = $tmp;
			}
			$data = $this->signAuth($data);
		}
		$url = $this->generateUrl($url, $type);
		$this->reqUrl = $url;
		$timeout = isset($timeout) ? $timeout : $this->config['timeout'];
		$retries = isset($retries) ? $retries : $this->config['retries'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		if (!empty($this->config['user_agent'])) {
			curl_setopt($curl, CURLOPT_USERAGENT, $this->config['user_agent']);
		}
		if (!empty($this->config['cookie'])) {
			curl_setopt($curl, CURLOPT_COOKIE, $this->config['cookie']);
		}
		if (strtolower($type) == 'post') {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
		}
		if (0 < $timeout) {
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		}
		$retries += 1;
		do {
			$output = curl_exec($curl);
			$curlInfo = curl_getinfo($curl);
			$retries--;
		} while (0 < $retries && (curl_errno($curl) || $curlInfo['http_code'] != '200'));
		if (curl_errno($curl) || $curlInfo['http_code'] != '200') {
			$this->reqError = $curlInfo['http_code'] != '200' ? 'http_code:' . $curlInfo['http_code'] : curl_error($curl);
			$this->happenError = true;
		} else {
			$this->reqError = '';
			$this->happenError = false;
			$this->result = $output;
		}
		curl_close($curl);
		return $this;
	}
	private function generateUrl($url, $type)
	{
		$param = array();
		if (is_array($url)) {
			$param = $url[1];
			$url = $url[0];
		}
		$url = isset($this->config['base']) ? $this->config['base'] . $url : $url;
		foreach ($this->config['params'] as $key => $value) {
			$url = preg_replace('/\\{\\$' . $key . '\\}/', $value, $url);
		}
		$url = preg_replace('/\\{\\$\\w+\\}/', '', $url);
		if ($type == 'get') {
			if (is_string($param)) {
				$tmp = array();
				parse_str($param, $tmp);
				$param = $tmp;
			}
			$param = $this->signAuth($param);
		}
		if (!empty($param)) {
			$query = is_array($param) ? http_build_query($param) : $param;
			$url = strpos($url, '?') === false ? $url . '?' . $query : rtrim($url, '&') . '&' . $query;
		}
		return $url;
	}
	private function signAuth($data)
	{
		$token = $this->config['is_sign'] ? isset($this->config['token']) ? $this->config['token'] : C('DATA_TOKEN') : false;
		$auth = $this->config['is_auth'] ? isset($this->config['auth']) ? $this->config['auth'] : C('DATA_AUTH') : false;
		if ($token !== false) {
			$data = $data ? $data : array();
			$data['uniqid'] = uniqid();
			$data['sign'] = generate_sign($data, $token);
		}
		if ($auth !== false && !empty($data)) {
			$data = array('auth_data' => sys_encrypt(json_encode($data), $auth));
		}
		return $data;
	}
	public function getData($type = 'json')
	{
		if ($this->happenError) {
			$this->error = 'http request error';
			return false;
		}
		if ($type == 'json') {
			$data = json_decode($this->result, true);
			if (!$data) {
				$this->error = '数据格式错误';
				return false;
			}
			if ($data['status'] != '0') {
				$this->error = $data['info'];
				return false;
			}
			return $data['data'];
		}
		return $this->result;
	}
	public function getError()
	{
		return $this->error;
	}
	public function getReqError()
	{
		return $this->reqError;
	}
	public function getUrl()
	{
		return $this->reqUrl;
	}
}