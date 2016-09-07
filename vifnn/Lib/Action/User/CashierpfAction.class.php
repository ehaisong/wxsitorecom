<?php

/* * **代理版收银台*** */

class CashierpfAction extends UserAction {

    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction("Cashierpf");
    }

    public function index($cashierAction='') {
        $siteurl = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $siteurl = strtolower($siteurl);
        if (strpos($siteurl, "http:") === false && strpos($siteurl, "https:") === false)
            $siteurl = 'http://' . $siteurl;
        $siteurl = rtrim($siteurl, '/');
        $cashierkeyArr = M('cmscashierkey')->where(array('ctype' => '0'))->find();
        $cashierkey = $cashierkeyArr ? $cashierkeyArr['cashierkey'] : '';
        $kstatus = $cashierkeyArr ? $cashierkeyArr['kstatus'] : 0;
        if (!$kstatus || empty($cashierkey)) {
            $this->success('网站管理员没启用平台版收银台！');
            exit();
        }
        $cashierkeytmp = base64_decode($cashierkey);
        $keyInfo = $this->Encryptioncode($cashierkeytmp);
        $keyInfo = json_decode($keyInfo, true);
        if (!empty($keyInfo) && is_array($keyInfo) && isset($keyInfo['yxdomain'])) {
            $tmpflag = $this->setAuthkey($cashierkey, $keyInfo, $siteurl);
            if (!$tmpflag) {
                $this->success('平台版收银台验证失败，不合法！');
                exit();
            }
        } else {
            $this->success('网站管理员还没启用平台版收银台！');
            exit();
        }

        $postUrl = 'http://' . $keyInfo['domain'];

        $postdata = array('user_name' => $this->token, 'userid' => $this->token, 'wx_name' => $this->wxuser['wxname'], 'appid' => $this->wxuser['appid'], 'appsecret' => $this->wxuser['appsecret'], 'wx_id' => $this->wxuser['weixin'], 'header_pic' => $this->wxuser['headerpic'], 'domain' => $siteurl, 'source' => 1, 'email' => $this->user['email'], 'aesKey' => $this->wxuser['aeskey'], 'secret' => $this->wxuser['pigsecret'], 'source_id' => '', 'phone' => '', 'verinfo' => 2);
        /*         * **verinfo 1独立安装收银台2代理版收银台***** */
        $postdata['sign'] = $this->getSign($postdata);
        $postdataStr = json_encode($postdata);
        $postdataStr = $this->Encryptioncode($postdataStr, 'ENCODE');
        $postdataStr = base64_encode($postdataStr);

        //$request_url=$postUrl.'/merchants.php?m=Index&c=auth&a=getIdentifier';
        $headerAUTH = array(
            'auth' => 'Auth ' . $cashierkey . ' Basic ' . $this->token,
        );

        $headerAUTH = json_encode($headerAUTH);
        $headerAUTH = $this->Encryptioncode($headerAUTH, 'ENCODE');
        $headerAUTH = base64_encode($headerAUTH);


        $request_url = $postUrl . '/merchants.php?m=Api&c=auth&a=signIn';
        $responsearr = $this->httpRequest($request_url, 'POST', $postdataStr, array('auth: ' . $headerAUTH));
        $tmpdata = trim($responsearr['1']);
        if (empty($tmpdata)) {
            $responsearr = $this->httpRequest($request_url, 'POST', $postdataStr, array('auth: ' . $headerAUTH));
            $tmpdata = trim($responsearr['1']);
        }
        $tmpdata = json_decode($tmpdata, true);

        if (isset($tmpdata['code'])) {
			if(!empty($cashierAction)){
			   $cashierAction=base64_encode($cashierAction);
			}
            header('Location: ' . $postUrl . '/merchants.php?m=Api&c=auth&a=signIn&cfrom=1&code=' . $tmpdata['code'].'&jaction='.$cashierAction);
        } else {

            $this->error($tmpdata['message']);
        }
    }

	public function wxCashierCard()
	{
		$this->index("c=wxCoupon&a=index");
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

                $this->success('KEY验证失败，' . $tmpdata['message']);
                exit();
            }
        } else {
            //验证失败 返回失败消息
            $this->success('授权的KEY值不合法，请重现联系微风CMS获取！');
            exit();
        }
        return false;
    }

    private function getSign($data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $validate[$key] = $this->getSign($value);
            } else {
                $validate[$key] = $value;
            }
        }
        $validate['salt'] = 'vifnno2oCashier'; //salt
        sort($validate, SORT_STRING);
        return sha1(implode($validate));
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

?>