<?php
class ServiceReturnAction extends Action
{
	public $token;
	public $wecha_id;

	public function _initialize()
	{
		$this->m_service = M('service_setup');
		$this->m_my = M('service_my');
		$this->m_preferential = M('service_preferential');
		$this->m_wechat = M('wechat_group_list');
		$this->m_wxuser = M('wxuser');
		$this->m_mywxuser = M('service_wxuser');
		$this->token = $_GET['token'];
		$this->wecha_id = $_GET['openid'];
		$this->siteUrl = 'http://' . $_SERVER['HTTP_HOST'];
	}

	public function activity()
	{
		$preferential = $this->m_preferential->where(array('token' => $this->token))->select();

		foreach ($preferential as $k => $v) {
			$predata[$k]['title'] = $v['name'];
			$predata[$k]['intro'] = $v['info'];
			$predata[$k]['url'] = $this->getLink($v['url']);

			if (substr($predata[$k]['url'], 0, 1) == '/') {
				$predata[$k]['url'] = $this->siteUrl . $predata[$k]['url'];
			}

			$predata[$k]['image'] = $v['img'];
		}

		$data['err_code'] = 0;
		$data['data'] = $predata;
		exit($_GET['callback'] . '(' . json_encode($data) . ')');
	}

	public function my()
	{
		$my = $this->m_my->where(array('token' => $this->token, 'display' => 1))->select();

		foreach ($my as $k => $v) {
			$mydata[$k]['title'] = $v['title'];
			$mydata[$k]['intro'] = '';

			switch ($v['type']) {
			case 'cy':
				$mydata[$k]['url'] = $this->siteUrl . '/index.php?g=Wap&m=Repast&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
				break;

			case 'sc':
				$mydata[$k]['url'] = $this->siteUrl . '/index.php?g=Wap&m=Store&a=select&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
				break;

			case 'tg':
				$mydata[$k]['url'] = $this->siteUrl . '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
				break;

			case 'wm':
				$mydata[$k]['url'] = $this->siteUrl . '/index.php?g=Wap&m=DishOut&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
				break;
			}

			$mydata[$k]['image'] = $v['img'];

			if (substr($mydata[$k]['image'], 0, 1) == '/') {
				$mydata[$k]['image'] = $this->siteUrl . $mydata[$k]['image'];
			}
		}

		$data['err_code'] = 0;
		$data['data'] = $mydata;
		exit($_GET['callback'] . '(' . json_encode($data) . ')');
	}

	public function set_key($data, $app_key)
	{
		$new_arr = array();
		ksort($data);

		foreach ($data as $k => $v) {
			$new_arr[] = $k . '=' . $v;
		}

		$new_arr[] = 'app_key=' . $app_key;
		$str = implode('&', $new_arr);
		return md5($str);
	}

	public function msgtip()
	{
		$this->servicesay($_POST['to_openid'], $_POST['from_nickname'], $_POST['url']);
	}

	public function ceshicall()
	{
		$this->servicesay('onfo6t7qd4eRLRS3y60oI80DzWLc', 'fwy', 'http://www.baidu.com');
	}

	public function servicesay($to_openid, $from_nickname, $url)
	{
		$isurl = strstr($url, '&amp;');

		if ($isurl != '') {
			$url = str_replace('&amp;', '&', $url);
		}

		$where_wxuser['token'] = $this->token;
		$appid = $this->m_wxuser->where($where_wxuser)->getField('appid');
		$secret = $this->m_wxuser->where($where_wxuser)->getField('appsecret');
		$wxuser = $this->m_wxuser->where($where_wxuser)->find();
		if (($appid == '') || ($secret == '')) {
			$data['err_code'] = 1;
			$data['err_msg'] = '公众号没有设置appid和appsecret！';
			echo json_encode($data);
			exit();
		}

		$apiOauth = new apiOauth();
		$data_access_token['access_token'] = $apiOauth->update_authorizer_access_token($appid, $wxuser);

		if ($data_access_token['access_token'] != '') {
			$access_token = $data_access_token['access_token'];
			$url_kf_list = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=' . $access_token;
			$kf_list = json_decode($this->https_request($url_kf_list), true);

			if ($kf_list['kf_list'] == '') {
				if ($kf_list['errcode'] == -1) {
					$this->servicesay($to_openid, $from_nickname, $url);
					exit();
				}
				else {
					$data['err_code'] = $kf_list['errcode'];
					$data['err_msg'] = '客服列表获取失败';
					echo json_encode($data);
					exit();
				}
			}

			foreach ($kf_list['kf_list'] as $vo) {
				if ($vo['kf_account'] == ('kefu@' . $wxuser['weixin'])) {
					$kefu = $vo['kf_account'];
					$img = $vo['kf_headimgurl'];
				}
			}

			if ($kefu != '') {
				if ($img == '') {
					$kf_headimgurl_url = 'https://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=' . $access_token . '&kf_account=' . $kefu;
					$imgurl = str_replace($this->siteUrl, '', $wxuser['headerpic']);
					$imgurl = str_replace('./', '/', $imgurl);
					$data_kf_headimgurl['media'] = '@' . $_SERVER['DOCUMENT_ROOT'] . $imgurl . ';type=image/jpg';
					$uploadheadimg = json_decode($this->https_request($kf_headimgurl_url, $data_kf_headimgurl), true);

					if ($uploadheadimg['errcode'] != 0) {
						$data['err_code'] = $uploadheadimg['errcode'];
						$data['err_msg'] = '头像设置失败';
						echo json_encode($data);
						exit();
					}
				}

				$fasongdata = '{"touser":"' . $to_openid . '","msgtype":"text","text":{"content":"您的好友' . $from_nickname . '给您发来了一条新的消息,\\n\\n<a href=\'' . $url . '\'>点击查看</a>"},"customservice":{"kf_account":"' . $kefu . '"}}';
				$url_send = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
				$send = json_decode($this->https_request($url_send, $fasongdata), true);

				if ($send['errcode'] == 0) {
					$data['err_code'] = 0;
					$data['err_msg'] = '发送成功';
					echo json_encode($data);
				}
				else {
					$data['err_code'] = $send['errcode'];
					$data['err_msg'] = '发送失败';
					echo json_encode($data);
					exit();
				}
			}
			else {
				$url_kfadd = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token=' . $access_token;
				$kf_account = 'kefu@' . $wxuser['weixin'];
				$data_kfadd = '{"kf_account":"' . $kf_account . '","nickname":"' . mb_substr($wxuser['wxname'], 0, 6, 'utf-8') . '","password":"' . md5($this->token) . '"}';
				$add = json_decode($this->https_request($url_kfadd, $data_kfadd), true);

				if ($add['errcode'] == 0) {
					$fasongdata = '{"touser":"' . $to_openid . '","msgtype":"text","text":{"content":"您的好友' . $from_nickname . '给您发来了一条新的消息,<a href=\'' . $url . '\'>点击查看</a>"},"customservice":{"kf_account":"' . $kf_account . '"}}';
					$url_send = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
					$send = json_decode($this->https_request($url_send, $fasongdata), true);

					if ($send['errcode'] == 0) {
						$data['err_code'] = 0;
						$data['err_msg'] = '发送成功';
						echo json_encode($data);
					}
					else {
						$data['err_code'] = $send['errcode'];
						$data['err_msg'] = '发送失败';
						echo json_encode($data);
						exit();
					}
				}
				else {
					$data['err_code'] = $add['errcode'];
					$data['err_msg'] = '添加客服失败' . $data_kfadd;
					echo json_encode($data);
					exit();
				}
			}
		}
		else {
			$data['err_code'] = $data_access_token['errcode'];
			$data['err_msg'] = '未获取access_token';
			echo json_encode($data);
			exit();
		}
	}

	protected function https_request($url, $data = NULL)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

	public function getLink($url)
	{
		$url = ($url ? $url : 'javascript:void(0)');
		$urlArr = explode(' ', $url);
		$urlInfoCount = count($urlArr);

		if (1 < $urlInfoCount) {
			$itemid = intval($urlArr[1]);
		}

		if ($this->strExists($url, '刮刮卡')) {
			$link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '大转盘')) {
			$link = '/index.php?g=Wap&m=Lottery&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '优惠券')) {
			$link = '/index.php?g=Wap&m=Coupon&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '刮刮卡')) {
			$link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '商家订单')) {
			if ($itemid) {
				$link = $link = '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&hid=' . $itemid;
			}
			else {
				$link = '/index.php?g=Wap&m=Host&a=Detail&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			}
		}
		else if ($this->strExists($url, '万能表单')) {
			if ($itemid) {
				$link = $link = '/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '相册')) {
			$link = '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Photo&a=plist&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '全景')) {
			$link = '/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Panorama&a=item&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '会员卡')) {
			$link = '/index.php?g=Wap&m=Card&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if ($this->strExists($url, '商城')) {
			$link = '/index.php?g=Wap&m=Product&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if ($this->strExists($url, '订餐')) {
			$link = '/index.php?g=Wap&m=Product&a=dining&dining=1&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if ($this->strExists($url, '团购')) {
			$link = '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if ($this->strExists($url, '首页')) {
			$link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if ($this->strExists($url, '网站分类')) {
			$link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&classid=' . $itemid;
			}
		}
		else if ($this->strExists($url, '图文回复')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, 'LBS信息')) {
			$link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&companyid=' . $itemid;
			}
		}
		else if ($this->strExists($url, 'DIY宣传页')) {
			$link = '/index.php/show/' . $this->token;
		}
		else if ($this->strExists($url, '婚庆喜帖')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if ($this->strExists($url, '投票')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Vote&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else {
			$link = str_replace(array('{wechat_id}', '{siteUrl}', '&amp;', '{changjingUrl}'), array($this->wecha_id, $this->siteUrl, '&', 'http://www.meihua.com'), $url);
			if (!!(strpos($url, 'tel') === false) && ($url != 'javascript:void(0)') && !strpos($url, 'wecha_id=')) {
				if (strpos($url, '?')) {
					$link = $link . '&wecha_id=' . $this->wecha_id;
				}
				else {
					$link = $link . '?wecha_id=' . $this->wecha_id;
				}
			}
		}

		return $link;
	}

	public function strExists($haystack, $needle)
	{
		return !(strpos($haystack, $needle) === false);
	}

	public function logResult($word = '')
	{
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/Conf/logs/wxlog.txt', 'a');
		flock($fp, LOCK_EX);
		fwrite($fp, '执行日期：' . strftime('%Y%m%d%H%M%S', time()) . "\n" . $word . "\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}

?>
