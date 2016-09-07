<?php

/**
 * ***平台板收银台对接处理
 * ***
 * *** */
class CmscashierkeyModel extends Model {

    private $pDomain = '';
    private $pBaseUrl = '';
    private $pUrl = '/merchants.php?m=Api&';
    private $cashierkey = '';
	private $cashierConfig = array();
	private $headerAUTH = '';

    public function intMe($yxtoken='') {
        $cashierkeyArr = $this->where(array('ctype' => '0'))->find();
        $this->cashierkey = $cashierkeyArr ? $cashierkeyArr['cashierkey'] : '';
        $kstatus = $cashierkeyArr ? $cashierkeyArr['kstatus'] : 0;
        if (!$kstatus || empty($this->cashierkey)) {
             return false;
            /* $this->success('网站管理员没启用平台版收银台！');
              exit(); */
        }
        $cashierkeytmp = base64_decode($this->cashierkey);
        $keyInfo = $this->Encryptioncode($cashierkeytmp);
        $keyInfo = json_decode($keyInfo, true);
		if(!empty($keyInfo) && is_array($keyInfo)){
			$this->pDomain = 'http://' . $keyInfo['domain'];
			$this->pBaseUrl = $this->pDomain . $this->pUrl;
			$this->cashierConfig=$keyInfo;
			$this->cashierConfig['cashierkey']=$this->cashierkey;

		   $headerAUTH = array(
             'auth' => 'Auth ' . $this->cashierkey . ' Basic ' . $yxtoken,
           );
          $headerAUTH = json_encode($headerAUTH);
          $headerAUTH = $this->Encryptioncode($headerAUTH, 'ENCODE');
          $this->headerAUTH = base64_encode($headerAUTH);
		}
    }

    public function getConfig() {
		  $this->intMe();
          return $this->cashierConfig;
    }

    /*     * **用户绑定信息***** */

    public function CashierSynByToken($datas, $isadd) {
        $this->intMe();
        $actinUrl = $this->pBaseUrl . 'c=auth&a=editMerchant';
        $headerAUTH = array(
            'auth' => 'Auth ' . $this->cashierkey . ' Basic ' . $datas['ctoken'],
            'type' => 1
        );
        /* if(!$isadd){
          $headerAUTH=array(
          'auth' => 'Auth '.$this->cashierkey.' Basic '.$datas['source_token'],
          );
          } */
		$datas['source'] = 1;
        $datas['verinfo'] = 2;
        $postdataStr = json_encode($datas);
        $postdataStr = $this->Encryptioncode($postdataStr, 'ENCODE');
        $postdataStr = base64_encode($postdataStr);
        $headerAUTH = json_encode($headerAUTH);
        $headerAUTH = $this->Encryptioncode($headerAUTH, 'ENCODE');
        $headerAUTH = base64_encode($headerAUTH);

        $responsearr = $this->httpRequest($actinUrl, 'POST', $postdataStr, array('auth: ' . $headerAUTH));
        $tmpdata = trim($responsearr['1']);
        if (empty($tmpdata)) {
            $responsearr = $this->httpRequest($actinUrl, 'POST', $postdataStr, array('auth: ' . $headerAUTH));
            $tmpdata = trim($responsearr['1']);
        }
        $tmpdata = json_decode($tmpdata, true);
        if (!empty($tmpdata) && ($tmpdata['status'] == 200) && ($tmpdata['message'] == 'SUCCESS')) {
            return '';
        } else {
            return $tmpdata['message'];
            exit();
        }
    }

       /****获取会员卡*******/
	public function getCardList($yxtoken){
		$this->intMe($yxtoken);
		$actinUrl = $this->pBaseUrl . 'c=auth&a=getCardList';
        $responsearr = $this->httpRequest($actinUrl, 'GET', null, array('auth: ' . $this->headerAUTH));
        $tmpmdata = trim($responsearr['1']);
        $tmpmdata = json_decode($tmpmdata, true);
        if (!empty($tmpmdata) && ($tmpmdata['status'] == 200) && ($tmpmdata['message'] == 'SUCCESS')) {
            return $tmpmdata['data'];
        } else {
            return false;
        }
	}

    public function getCashierMerchant($yxtoken, $isout = true) {
        if ($isout) {
            $this->intMe($yxtoken);
        }
        $actinUrl = $this->pBaseUrl . 'c=auth&a=merchantInfo';
        $headerAUTH = array(
            'auth' => 'Auth ' . $this->cashierkey . ' Basic ' . $yxtoken,
        );
        $headerAUTH = json_encode($headerAUTH);
        $headerAUTH = $this->Encryptioncode($headerAUTH, 'ENCODE');
        $headerAUTH = base64_encode($headerAUTH);

        $responsearr = $this->httpRequest($actinUrl, 'GET', null, array('auth: ' . $headerAUTH));
        $tmpmdata = trim($responsearr['1']);
        $tmpmdata = json_decode($tmpmdata, true);
        if (!empty($tmpmdata) && ($tmpmdata['status'] == 200) && ($tmpmdata['message'] == 'SUCCESS')) {
            return $tmpmdata['data'];
        } else {
            return false;
        }
    }

    public function setAuthkey($oldKey, $data, $siteurl) {
        $siteurl = strtolower($siteurl);
        if (strpos($siteurl . '/', $data['yxdomain']) !== false) {
            //验证成功 反向验证
            $request_url = 'http://' . $data['domain'] . '/merchants.php?m=Api&c=auth&a=authVerify';
            $responsearr = $this->httpRequest($request_url, 'POST', $oldKey);

            $tmpdata = trim($responsearr['1']);
            $tmpdata = json_decode($tmpdata, true);
            if (!empty($tmpdata) && ($tmpdata['status'] == 200) && ($tmpdata['message'] == 'SUCCESS')) {
                //验证成功 存入数据库
                return true;
            } else {
                //验证失败 返回失败消息
                return false;
                //$this->success('KEY验证失败，'.$tmpdata['message']);
                exit();
            }
        } else {
            return false;
            //验证失败 返回失败消息
            //$this->success('授权的KEY值不合法，请重现联系VIFNN.COM获取！');
            exit();
        }
        return false;
    }

    public function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false) {
        /* $Cookiestr = "";  * cUrl COOKIE处理* 
          if (!empty($_COOKIE)) {
          foreach ($_COOKIE as $vk => $vv) {
          $tmp[] = $vk . "=" . $vv;
          }
          $Cookiestr = implode(";", $tmp);
          } */
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /* 指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的 */
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /* curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);

            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response, $requestinfo);
    }

    /**
     * 加密和解密函数
     * @access public
     * @param  string  $string    需要加密或解密的字符串
     * @param  string  $operation 默认是DECODE即解密 ENCODE是加密
     * @param  string  $key       加密或解密的密钥 参数为空的情况下取全局配置encryption_key
     * @param  integer $expiry    加密的有效期(秒)0是永久有效 注意这个参数不需要传时间戳
     * @return string
     */
    public function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : 'lhs_simple_encryption_code_87063');
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

}
