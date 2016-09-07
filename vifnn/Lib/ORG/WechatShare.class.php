<?php
class WechatShare
{
	public $wxuser;
	public $wecha_id;
	public $error = array();
	public function __construct($wxuser, $wecha_id)
	{
		$this->wxuser = $wxuser;
		$this->wecha_id = $wecha_id;
	}
	public function getSgin()
	{
		if (empty($this->wxuser['is_syn'])) {
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($this->wxuser['appid'], $this->wxuser);
			$ticket = $apiOauth->getAuthorizerTicket($this->wxuser['appid'], $access_token);
		} else {
			$domain = M('Users')->where(array('id' => $this->wxuser['uid']))->getField('source_domain');
			$url = $domain . A('Home/Auth')->getCallbackUrl($this->wxuser['is_syn'], 'share');
			$json = HttpClient::getInstance()->get($url);
			$json = json_decode($json, true);
			$ticket = $json['ticket'];
			$this->wxuser['appid'] = $json['appid'];
		}
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$sign_data = $this->addSign($ticket, $url);
		$share_html = $this->createHtml($sign_data);
		return $share_html;
	}
	public function getError()
	{
		dump($this->error);
	}
	public function addSign($ticket, $url)
	{
		$timestamp = time();
		$nonceStr = rand(100000, 999999);
		$array = array('noncestr' => $nonceStr, 'jsapi_ticket' => $ticket, 'timestamp' => $timestamp, 'url' => $url);
		ksort($array);
		$signPars = '';
		foreach ($array as $k => $v) {
			if ('' != $v && 'sign' != $k) {
				if ($signPars == '') {
					$signPars .= $k . '=' . $v;
				} else {
					$signPars .= '&' . $k . '=' . $v;
				}
			}
		}
		$result = array('appId' => $this->wxuser['appid'], 'timestamp' => $timestamp, 'nonceStr' => $nonceStr, 'url' => $url, 'signature' => SHA1($signPars));
		return $result;
	}
	public function getUrl()
	{
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 'oauth') {
			$url = $this->clearUrl($url);
			if (isset($_GET['wecha_id'])) {
				$url .= '&wecha_id=' . $this->wecha_id;
			}
			return $url;
		} else {
			return $url;
		}
	}
	public function clearUrl($url)
	{
		$param = explode('&', $url);
		$i = 0;
		for ($count = count($param); $i < $count; $i++) {
			if (preg_match('/^(code=|state=|wecha_id=).*/', $param[$i])) {
				unset($param[$i]);
			}
		}
		return join('&', $param);
	}
	public function getToken()
	{
	}
	public function getTicket($token)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $token . '&type=jsapi';
		return $this->https_request($url);
	}
	public function createHtml($sign_data)
	{
		$tongjiScript = '';
		$tongjiStatus = TONGJI_STATUS;
		$shareMark = defined('SHARE_MARK') ? SHARE_MARK : '';
		$selfMark = defined('SELF_MARK') ? SELF_MARK : '';
		$createShareUrl = U('Home/Index/createShare', array('token' => $this->wxuser['token']));
		if (TONGJI_STATUS == '1') {
			$tongjiScript = "\r\n\t\t\tfunction shareTongji(channel,url){\r\n\t\t\t\t\$.post('" . $createShareUrl . "',{},function(result){\r\n                \ttry {\r\n                    \tresult=typeof result=='string'?JSON.parse(result):result;\r\n                \t}catch (e){result={}};\r\n                if(result&&result['status']=='1'&&result['data'])\r\n                {\r\n                    var tmp=typeof MY_ACT=='undefined'?{act_name:'',act_id:'',act_token:''}:MY_ACT;\r\n                    var shareData={share_time:result['data']['share_time'],self_mark:SELF_MARK,share_url:url,page_param:tmp,share_channel:channel,share_mark:SHARE_MARK};\r\n                    if(typeof tongji!='undefined'){\r\n\t\t\t\t\t\ttongji.emit('share',shareData);\r\n\t\t\t\t\t}\r\n\t\t\t\t\t//刷新分享链接\r\n\t\t\t\t\tSHARE_MARK=result['data']['new_mark'];\r\n\t\t\t\t\tif(typeof shareAppMessageOpts!='undefined'){\r\n\t\t\t\t\t\tshareAppMessageOpts['link']=getShareLink(window.shareData.sendFriendLink,'friend');\r\n                    \tshareQQOpts['link']=getShareLink(window.shareData.sendFriendLink,'qq');\r\n                    \tshareQZoneOpts['link']=getShareLink(window.shareData.sendFriendLink,'qzone');\r\n                    \tshareTimelineOpts['link']=getShareLink(window.shareData.sendFriendLink,'timeline');\r\n                    \tshareWeiboOpts['link']=getShareLink(window.shareData.sendFriendLink,'weibo');\r\n\t\t\t\t\t}\r\n                }\r\n            })}";
		} else {
			$tongjiScript = 'function shareTongji(channel,url){}';
		}
		$html = "\t<script type=\"text/javascript\" src=\"http://res.wx.qq.com/open/js/jweixin-1.0.0.js\"></script>\t\r\n\t<style type=\"text/css\">.share-callback {color:#fff;display:none;background:#666;width:200px;line-height:50px;height:50px;font-size:16PX;font-weight:bold;text-align:center;position:absolute;top:40%;left:20%;-moz-border-radius: 25px;-webkit-border-radius: 25px;border-radius:25px;z-index:999;}</style>\r\n\t<span class=\"share-callback\">分享成功</span>\r\n\t<script type=\"text/javascript\">\r\n\tvar SHARE_MARK='" . $shareMark . "';//分享标识\r\n\tvar SELF_MARK='" . $selfMark . "';//用户标识\r\n\tvar shareAppMessageOpts,shareQQOpts,shareQZoneOpts,shareTimelineOpts,shareWeiboOpts;\r\n\t\twx.config({\r\n\t\t  debug: false,\r\n\t\t  appId: \t'" . $sign_data['appId'] . "',\r\n\t\t  timestamp: " . $sign_data['timestamp'] . ",\r\n\t\t  nonceStr: '" . $sign_data['nonceStr'] . "',\r\n\t\t  signature: '" . $sign_data['signature'] . "',\r\n\t\t  jsApiList: [\r\n\t    \t'checkJsApi',\r\n\t\t    'onMenuShareTimeline',\r\n\t\t    'onMenuShareAppMessage',\r\n\t\t    'onMenuShareQQ',\r\n\t\t    'onMenuShareWeibo',\r\n\t\t\t'openLocation',\r\n\t\t\t'getLocation',\r\n\t\t\t'addCard',\r\n\t\t\t'chooseCard',\r\n\t\t\t'openCard',\r\n\t\t\t'hideMenuItems',\r\n\t\t\t'previewImage',\r\n            \"startRecord\", \r\n            \"stopRecord\", \r\n            \"onVoiceRecordEnd\", \r\n            \"playVoice\", \r\n            \"pauseVoice\",\r\n            \"stopVoice\", \r\n            \"onVoicePlayEnd\", \r\n            \"uploadVoice\", \r\n            \"downloadVoice\",\r\n             \"closeWindow\",\r\n             \"getNetworkType\"\r\n\t\t  ]\r\n\t\t});\r\n\t</script>\r\n\t<script type=\"text/javascript\">\r\n\twx.ready(function () {\r\n\t  // 1 判断当前版本是否支持指定 JS 接口，支持批量判断\r\n\t  /*document.querySelector('#checkJsApi').onclick = function () {\r\n\t    wx.checkJsApi({\r\n\t      jsApiList: [\r\n\t        'getNetworkType',\r\n\t        'previewImage'\r\n\t      ],\r\n\t      success: function (res) {\r\n\t        //alert(JSON.stringify(res));\r\n\t      }\r\n\t    });\r\n\t  };*/\r\n\t  // 2. 分享接口\r\n\t  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口\r\n\t  shareAppMessageOpts={\r\n\t\t\ttitle: window.shareData.tTitle,\r\n\t\t\tdesc: window.shareData.tContent,\r\n\t\t\tlink: getShareLink(window.shareData.sendFriendLink,'friend'),\r\n\t\t\timgUrl: window.shareData.imgUrl,\r\n\t\t    type: '', // 分享类型,music、video或link，不填默认为link\r\n\t\t    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空\r\n\t\t    success: function () { \r\n\t\t    \tshareTongji('friend',shareAppMessageOpts.link);\r\n\t\t\t\tshareHandle('frined');\r\n\t\t    },\r\n\t\t    cancel: function () { \r\n\t\t        //alert('分享朋友失败');\r\n\t\t    }\r\n\t\t};\r\n\t    wx.onMenuShareAppMessage(shareAppMessageOpts);\r\n\t\t\r\n\t\t//获取“分享到QQ”按钮点击状态及自定义分享内容接口\r\n\t\tshareQQOpts={\r\n\t\t\ttitle: window.shareData.tTitle,\r\n\t\t\tdesc: window.shareData.tContent,\r\n\t\t\tlink: getShareLink(window.shareData.sendFriendLink,'qq'),\r\n\t\t\timgUrl: window.shareData.imgUrl,\r\n\t\t    success: function () { \r\n\t\t    \tshareTongji('qq',shareQQOpts.link);\r\n\t\t\t\tshareHandle('qq');\r\n\t\t    },\r\n\t\t    cancel: function () { \r\n\t\t        //alert('分享到QQ失败');\r\n\t\t    }\r\n\t\t};\r\n\t\twx.onMenuShareQQ(shareQQOpts);\r\n\t\t\r\n\t\t//获取“分享到QQ空间”按钮点击状态及自定义分享内容接口\r\n\t\tshareQZoneOpts={\r\n\t\t\ttitle: window.shareData.tTitle,\r\n\t\t\tdesc: window.shareData.tContent,\r\n\t\t\tlink: getShareLink(window.shareData.sendFriendLink,'qzone'),\r\n\t\t\timgUrl: window.shareData.imgUrl,\r\n\t\t    success: function () { \r\n\t\t    \tshareTongji('qzone',shareQZoneOpts.link);\r\n\t\t\t\tshareHandle('qzone');\r\n\t\t    },\r\n\t\t    cancel: function () { \r\n\t\t        //alert('分享到QQ空间失败');\r\n\t\t    }\r\n\t\t};\r\n\t\twx.onMenuShareQZone(shareQZoneOpts);\r\n\t\t\r\n\t\t\r\n\t  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口\r\n\t  shareTimelineOpts={\r\n\t\t\ttitle: window.shareData.fTitle?window.shareData.fTitle:window.shareData.tTitle,\r\n\t\t\tlink: getShareLink(window.shareData.sendFriendLink,'timeline'),\r\n\t\t\timgUrl: window.shareData.imgUrl,\r\n\t\t    success: function () { \r\n\t\t    \tshareTongji('timeline',shareTimelineOpts.link);\r\n\t\t\t\tshareHandle('frineds');\r\n\t\t        //alert('分享朋友圈成功');\r\n\t\t    },\r\n\t\t    cancel: function () { \r\n\t\t        //alert('分享朋友圈失败');\r\n\t\t    }\r\n\t\t};\r\n\t\twx.onMenuShareTimeline(shareTimelineOpts);\t\r\n\r\n\t  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口\r\n\t  shareWeiboOpts={\r\n\t\t\ttitle: window.shareData.tTitle,\r\n\t\t\tdesc: window.shareData.tContent,\r\n\t\t\tlink: getShareLink(window.shareData.sendFriendLink,'weibo'),\r\n\t\t\timgUrl: window.shareData.imgUrl,\r\n\t\t    success: function () { \r\n\t\t    \tshareTongji('weibo',shareWeiboOpts.link);\r\n\t\t\t\tshareHandle('weibo');\r\n\t\t       \t//alert('分享微博成功');\r\n\t\t    },\r\n\t\t    cancel: function () { \r\n\t\t        //alert('分享微博失败');\r\n\t\t    }\r\n\t\t};\r\n\t\twx.onMenuShareWeibo(shareWeiboOpts);\r\n\t\tif(window.shareData.timeline_hide == '1'){\r\n\t\t\twx.hideMenuItems({\r\n\t\t\t  menuList: [\r\n\t\t\t\t'menuItem:share:timeline', //隐藏分享到朋友圈\r\n\t\t\t  ],\r\n\t\t\t});\r\n\t\t}\r\n\t\twx.error(function (res) {\r\n\t\t\t/*if(res.errMsg == 'config:invalid signature'){\r\n\t\t\t\twx.hideOptionMenu();\r\n\t\t\t}else if(res.errMsg == 'config:invalid url domain'){\r\n\t\t\t\twx.hideOptionMenu();\r\n\t\t\t}else{\r\n\t\t\t\twx.hideOptionMenu();\r\n\t\t\t\t//alert(res.errMsg);\r\n\t\t\t}*/\r\n\t\t\tif(res.errMsg){\r\n\t\t\t\twx.hideOptionMenu();\r\n\t\t\t}\r\n\t\t});\r\n\t});\r\n\t\t\r\n\tfunction shareHandle(to) {\r\n\t\tvar submitData = {\r\n\t\t\tmodule: window.shareData.moduleName,\r\n\t\t\tmoduleid: window.shareData.moduleID,\r\n\t\t\ttoken:'" . $this->wxuser['token'] . "',\r\n\t\t\twecha_id:'" . $this->wecha_id . "',\r\n\t\t\turl: window.shareData.sendFriendLink,\r\n\t\t\tto:to\r\n\t\t};\r\n\r\n\t\t\$.post('index.php?g=Wap&m=Share&a=shareData&token=" . $this->wxuser['token'] . '&wecha_id=' . $this->wecha_id . "',submitData,function (data) {},'json');\r\n\t\tif(window.shareData.isShareNum == 1){\r\n\t\t\tvar ShareNum = {\r\n\t\t\t\ttoken:'" . $this->wxuser['token'] . "',\r\n\t\t\t\tShareNumData:window.shareData.ShareNumData\r\n\t\t\t}\r\n\t\t\t\$.post('index.php?g=Wap&m=Share&a=ShareNum&token=" . $this->wxuser['token'] . '&wecha_id=' . $this->wecha_id . "',ShareNum,function (data) {if(window.shareData.isShareNumReload == 1){location.reload();}},'json');\r\n\t\t}\r\n\t\tif ('function' == typeof shareCallback) {\r\n\t\t\tshareCallback(to);\t\t\t\t\t\r\n\t\t} else {\r\n\t\t\t\$('.share-callback').css('left', (\$(document).width() - 200) / 2).fadeIn(1000);\r\n\t\t\t\$('.share-callback').fadeOut(3000);\r\n\t\t}\r\n\t}\r\n\t\r\n\t//获取分享链接\r\n\tfunction getShareLink(link,channel){\r\n\t\tif('" . $tongjiStatus . "'!='1'){\r\n\t\t\treturn link;\r\n\t\t}\r\n\t\treturn link.indexOf('?')<0?(link+'?sk='+SHARE_MARK+'&mk='+SELF_MARK):(link+'&sk='+SHARE_MARK+'&mk='+SELF_MARK);\r\n\t}\r\n\t" . $tongjiScript . "\r\n</script>";
		return $html;
	}
	protected function https_request($url, $data = NULL)
	{
		$curl = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		$errorno = curl_errno($curl);
		if ($errorno) {
			return array('curl' => false, 'errorno' => $errorno);
		} else {
			$res = json_decode($output, 1);
			if ($res['errcode']) {
				return array('errcode' => $res['errcode'], 'errmsg' => $res['errmsg']);
			} else {
				return $res;
			}
		}
		curl_close($curl);
	}
	public function getShareData($params = array())
	{
		$params['moduleName'] = empty($params['moduleName']) ? MODULE_NAME : $params['moduleName'];
		$params['moduleID'] = empty($params['moduleID']) ? 0 : $params['moduleID'];
		$params['imgUrl'] = empty($params['imgUrl']) ? '' : $params['imgUrl'];
		if (empty($params['sendFriendLink'])) {
			$params['sendFriendLink'] = stripslashes(getSelfUrl(array('wecha_id')));
		} else {
			$params['sendFriendLink'] = stripslashes(getSelfUrl(array('wecha_id'), $params['sendFriendLink']));
		}
		$params['tTitle'] = empty($params['tTitle']) ? '' : shareFilter($params['tTitle']);
		$params['tContent'] = empty($params['tContent']) ? $params['tTitle'] : shareFilter($params['tContent']);
		$shareData = str_replace('\\/', '/', json_encode($params));
		$html = "\t\t<script type=\"text/javascript\">\r\n\t\t\t\twindow.shareData = " . $shareData . ";\r\n\t\t</script>";
		return $html;
	}
}