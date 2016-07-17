<?php


class WapAction extends BaseAction
{
	public $token;
	public $wecha_id;
	public $fakeopenid;
	public $fans;
	public $homeInfo;
	public $bottomeMenus;
	public $wxuser;
	public $user;
	public $group;
	public $company;
	public $shareScript;
	public $sign;
	private $_appid;
	private $_secret;
	private $_redirect_uri;
	private $_auth;
	public $owxuser;
	protected function _initialize()
	{
		parent::_initialize();
		$this->_appid = C('appid');
		$this->_secret = C('secret');
		$this->time = 0;
		$this->token = $this->_get('token');
		if (ALI_FUWU_GROUP && date('Y') == 2015) {
			$fuwuuserlist = M('fuwuuser')->where(array('token' => $this->token))->select();
			foreach ($fuwuuserlist as $fuwuvo) {
				$fuwuuserinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $fuwuvo['wecha_id']))->find();
				if ($fuwuuserinfo == '') {
					if ($fuwuvo['gender'] == 'M') {
						$fuwuvo['gender'] = 1;
					} else {
						if ($fuwuvo['gender'] == 'F') {
							$fuwuvo['gender'] = 2;
						} else {
							$fuwuvo['gender'] = 0;
						}
					}
					$add_userinfo_hb = array('token' => $this->token, 'wecha_id' => $fuwuvo['wecha_id'], 'issub' => 1, 'portrait' => $fuwuvo['avatar'], 'truename' => $fuwuvo['real_name'], 'nickname' => $fuwuvo['real_name'], 'wechaname' => $fuwuvo['real_name'], 'sex' => $fuwuvo['gender']);
					$add_userinfo_hb = array_map('nulltoblank', $add_userinfo_hb);
					$id_userinfo = M('userinfo')->add($add_userinfo_hb);
					D('Userfollow')->setsubscribe($this->token, $fuwuvo['wecha_id'], 1);
					S('sub_' . $this->token . '_' . $fuwuvo['wecha_id'], NULL);
				} else {
					if ($fuwuuserinfo['portrait'] == '' || $fuwuuserinfo['wechaname'] == '') {
						if ($fuwuvo['gender'] == 'M') {
							$fuwuvo['gender'] = 1;
						} else {
							if ($fuwuvo['gender'] == 'F') {
								$fuwuvo['gender'] = 2;
							} else {
								$fuwuvo['gender'] = 0;
							}
						}
						$save_userinfo_hb = array('token' => $this->token, 'wecha_id' => $fuwuvo['wecha_id'], 'issub' => 1, 'portrait' => $fuwuvo['avatar'], 'truename' => $fuwuvo['real_name'], 'nickname' => $fuwuvo['real_name'], 'wechaname' => $fuwuvo['real_name'], 'sex' => $fuwuvo['gender']);
						$save_userinfo_hb = array_map('nulltoblank', $save_userinfo_hb);
						$up_userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $fuwuvo['wecha_id']))->save($save_userinfo_hb);
						D('Userfollow')->setsubscribe($this->token, $fuwuvo['wecha_id'], 1);
						S('sub_' . $this->token . '_' . $fuwuvo['wecha_id'], NULL);
					}
				}
			}
		}
		if (strlen($this->token)) {
			$_SESSION['wap_token'] = $this->token;
		}
		if (!$this->token) {
			$this->token = $_SESSION['wap_token'];
		}
		if (!empty($_SESSION['wap_token'])) {
			$this->token = $_SESSION['wap_token'];
		}
		if (!$this->token && !(strpos(MODULE_NAME, 'Drp') === false)) {
			$id = $this->_get('id');
			if ($id) {
				$did = M('Distributor_store')->where(array('id' => $id))->getField('did');
				$this->token = M('Distributor')->where(array('id' => $did))->getField('token');
			}
		}
		$this->assign('token', $this->token);
		if (!$this->token) {
			die('no token');
		}
		if ($this->token && !preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)) {
			die('error token');
		}
		$this->wxuser = S('wxuser_' . $this->token);
		if (!$this->wxuser || 1) {
			$this->wxuser = D('Wxuser')->where(array('token' => $this->token))->find();
			S('wxuser_' . $this->token, $this->wxuser);
		}
		$this->owxuser = $this->wxuser;
		$this->assign('wxuser', $this->wxuser);
		$this->_checkVipTime($this->wxuser);
		$this->checkBlacklist();
		$fake = 0;
		if ($this->wxuser['winxintype'] != 3 && $this->wxuser['type_of_media_or_gov'] != 1 && $this->_appid && $this->_secret) {
			if ($this->wxuser['oauth'] == 1) {
				if (!$this->isAgent) {
					$this->wxuser['appid'] = trim($this->_appid);
					$this->wxuser['appsecret'] = trim($this->_secret);
				} else {
					$this->wxuser['appid'] = $this->thisAgent['appid'];
					$this->wxuser['appsecret'] = $this->thisAgent['appsecret'];
				}
			}
			$fake = 1;
		}
		$toAuth = 0;
		if (C('server_topdomain') == 'vifnn.cn' && C('site_url') != 'http://demo2.vifnn.cn') {
			$toAuth = 1;
		} else {
			$toAuth = $this->wxuser['oauth'];
		}
		if (C('server_topdomain') == 'vifnn.cn' && $this->wxuser['type_of_media_or_gov'] != 1 && $this->wxuser['winxintype'] != 3) {
			$this->wxuser['appid'] = $this->_appid;
			$this->wxuser['appsecret'] = $this->_secret;
			$this->wxuser['oauth'] = 0;
			$this->wxuser['is_domain'] = 1;
			$fake = 1;
		}
		$wexintype = $this->wxuser['winxintype'];
		$session_openid_name = 'token_openid_' . $this->token;
		$session_fakeopenid_name = 'token_fakeopenid_' . $this->token;
		$session_reopenid_name = 'token_reopenid_' . $this->token;
		$session_oauthed_name = 'token_oauthed_' . $this->token;
		$getUserInfoModules = getUserInfoModule::index();
		$getUserinfo = 0;
		if (isset($_GET['rget']) || intval($_GET['ali'])) {
			$_SESSION['otherSource'] = 1;
			$toAuth = 0;
			$this->wxuser['oauthinfo'] = 0;
		}
		if (isset($_SESSION['otherSource'])) {
			$toAuth = 0;
		}
		if ($this->wxuser['oauthinfo'] && !$_SESSION[$session_oauthed_name]) {
			if ($_SESSION[$session_openid_name]) {
				$fansInfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $_SESSION[$session_openid_name]))->find();
				if ($toAuth) {
					if (!$fansInfo || !$fansInfo['wechaname'] || !$fansInfo['portrait']) {
						unset($_SESSION[$session_openid_name]);
						$getUserinfo = 1;
					}
				}
			} else {
				if (isset($_SESSION[$session_reopenid_name]) && $_SESSION[$session_reopenid_name]) {
					$fansInfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $_SESSION[$session_reopenid_name]))->find();
					if (!$fansInfo || !$fansInfo['wechaname'] || !$fansInfo['portrait']) {
						unset($_SESSION[$session_openid_name]);
						unset($_SESSION[$session_reopenid_name]);
						$getUserinfo = 1;
					}
				} else {
					$getUserinfo = 1;
				}
			}
		}
		$this->isFuwu = 0;
		$this->isWechat = 0;
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (strpos($userAgent, 'alipayclient')) {
			$this->isFuwu = 1;
		} else {
			if (strpos($userAgent, 'micromessenger')) {
				$this->isWechat = 1;
			}
		}
		if (!M('Weixin_account')->where(array('type' => 1))->find()) {
			M('Wxuser')->where('1')->save(array('type' => 0));
		}
		$this->cookieToSession();
		$getwecha_id = $_GET['wecha_id'] ? urldecode($_GET['wecha_id']) : '';
		if ($getwecha_id && $getwecha_id != $_SESSION[$session_openid_name] && 3 != $wexintype && $getwecha_id != '{wechat_id}') {
			unset($_SESSION[$session_openid_name]);
			unset($_SESSION[$session_oauthed_name]);
			cookie('oauth2_' . $this->token, NULL);
		}
		if ($this->isFuwu) {
			if ($_GET['wecha_id'] != '') {
				$this->wecha_id = $_GET['wecha_id'];
			} else {
				$this->wecha_id = session('wecha_id');
			}
			session('wecha_id', $this->wecha_id);
			$thisfuwuuser = M('fuwuuser')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
			$isFuwuoauth = session('isFuwuoauth');
		}
		if ($this->isFuwu && $isFuwuoauth != 'yes' && !in_array(MODULE_NAME, array('Alipaytype'))) {
			$fw_wecha_id = FuwuOAuth::index($this->token);
			if ($_GET['auth_code'] == '') {
				die;
			}
			if ($fw_wecha_id != 'no') {
				$this->wecha_id = $fw_wecha_id;
				session('wecha_id', $this->wecha_id);
				session('isFuwuoauth', 'yes');
			} else {
				if ($this->wecha_id == '') {
					$this->error('服务窗没有获取粉丝信息权限<br/>请在服务窗开发者中确认开通');
					die;
				}
			}
			$_SESSION[$session_openid_name] = $this->wecha_id;
			$_SESSION[$session_oauthed_name] = 1;
		}
		$users = M('Users')->where(array('id' => $this->wxuser['uid']))->find();
		if ('payReturn' == ACTION_NAME && 0 < $users['is_syn']) {
			$_SESSION[$session_openid_name] = $_GET['wecha_id'];
		}
		if (empty($_SESSION[$session_openid_name]) && 0 < $users['is_syn']) {
			$_SESSION['auth_callback_' . $this->token] = array('url' => getSelfUrl(array('wecha_id')), 'token' => $this->token);
			$url = $users['source_domain'] . A('Home/Auth')->getCallbackUrl($users['is_syn'], 'auth') . 'token=' . $this->token . '&source=vifnn';
			header('Location:' . $url);
			die;
		}
		if (2 == $users['is_syn']) {
			$url = $users['source_domain'] . A('Home/Auth')->getCallbackUrl($users['is_syn'], 'follow') . 'wecha_id=' . $_SESSION[$session_openid_name];
			$this->_auth = json_decode(HttpClient::getInstance()->get($url));
			if (1 == $this->_auth->code) {
				M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $_SESSION[$session_openid_name]))->setField('issub', 1);
				D('Userfollow')->setsubscribe($this->token, $_SESSION[$session_openid_name], 1);
				S('sub_' . $this->token . '_' . $_SESSION[$session_openid_name], NULL);
			} else {
				M('Home')->where(array('token' => $this->token))->setField('gzhurl', $this->_auth->follow->url);
				$this->wxuser['qr'] = $this->_auth->follow->qrcode;
				$this->wxuser['wxname'] = $this->_auth->follow->wechat;
			}
		}
		if (!isset($_SESSION[$session_openid_name]) || !$_SESSION[$session_openid_name]) {
			if ($this->isFuwu) {
			} else {
				$apiOauth = new apiOauth();
				if ((!$_GET['wecha_id'] || urldecode($_GET['wecha_id']) == '{wechat_id}') && $_GET['wecha_id'] != 'no' && $this->wxuser['appid'] && ($this->wxuser['type'] == 0 && $this->wxuser['appsecret'] != '' || $this->wxuser['type'] == 1) && $toAuth == 1) {
					$token_info = $apiOauth->webOauth($this->wxuser, '', $fansInfo);
				}
				if (!empty($token_info)) {
					$this->wecha_id = $token_info['openid'];
					if (3 == $wexintype) {
						$fake = 0;
					}
					if ($fake) {
						if (isset($_SESSION[$session_fakeopenid_name])) {
							$datainfo['issub'] = 1;
							$this->wecha_id = $_SESSION[$session_fakeopenid_name];
							$userinfoModel = M('Userinfo');
							$userinfoData = $userinfoModel->where(array('token' => $this->token, 'wecha_id' => $_SESSION[$session_fakeopenid_name]))->find();
							if ($userinfoData) {
								$fakeUserinfoData = $userinfoModel->where(array('token' => $this->token, 'wecha_id' => $token_info['openid'], 'fakeopenid' => $token_info['openid']))->find();
								if ($fakeUserinfoData) {
									if ($userinfoModel->where(array('id' => $fakeUserinfoData['id']))->save($this->_mergeUserinfo($userinfoData, $fakeUserinfoData))) {
										$userinfoModel->where(array('id' => $userinfoData['id']))->delete();
									}
								} else {
									$userinfoModel->where(array('id' => $userinfoData['id']))->setField('fakeopenid', $token_info['openid']);
								}
							} else {
								$fakeUserinfoData = $userinfoModel->where(array('token' => $this->token, 'wecha_id' => $token_info['openid'], 'fakeopenid' => $token_info['openid']))->find();
								if ($fakeUserinfoData) {
									$userinfoModel->where(array('id' => $fakeUserinfoData['id']))->setField('wecha_id', $_SESSION[$session_fakeopenid_name]);
								}
							}
							$this->wecha_id = $_SESSION[$session_fakeopenid_name];
						} else {
							$fansInfo = M('Userinfo')->where(array('token' => $this->token, 'fakeopenid' => $token_info['openid']))->find();
							if ($fansInfo) {
								$this->wecha_id = $fansInfo['wecha_id'];
							}
						}
					}
					if ($this->wxuser['oauthinfo'] && (MODULE_NAME != 'Index' || MODULE_NAME == 'Index' && ACTION_NAME == 'content')) {
						$jsonui = $apiOauth->get_fans_info($token_info['access_token'], $token_info['openid']);
						if (isset($jsonui['openid']) && $jsonui['openid']) {
							if ($fansInfo) {
								$exist = $fansInfo['id'];
								$issub = $fansInfo['issub'];
							} else {
								$existInfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
								$issub = $existInfo['issub'];
								$exist = $existInfo['id'];
							}
							$datainfo['wechaname'] = str_replace(array('\'', '\\'), array(''), $jsonui['nickname']);
							$datainfo['sex'] = $jsonui['sex'];
							$datainfo['portrait'] = $jsonui['headimgurl'];
							$datainfo['token'] = $this->token;
							$datainfo['wecha_id'] = $jsonui['openid'];
							$datainfo['city'] = $jsonui['city'];
							$datainfo['province'] = $jsonui['province'];
							$datainfo['truename'] = $datainfo['wechaname'];
							$datainfo['wecha_id'] = $this->wecha_id;
							if ($fake) {
								$datainfo['fakeopenid'] = $jsonui['openid'];
							}
							if (3 == $wexintype) {
								$datainfo['fakeopenid'] = '';
								$datainfo['issub'] = $this->_issubService($datainfo['wecha_id']);
							}
							if ($exist) {
								D('Userinfo')->where(array('id' => $exist))->save($datainfo);
							} else {
								D('Userinfo')->add($datainfo);
							}
						} else {
							$this->error('授权不对哦，请重置您的appsecret！<br>' . $this->wxuser['appid'] . '<br>' . $this->wxuser['appsecret'] . '<br>' . $jsonui['errcode'], '#');
							die;
						}
					}
					if (empty($this->wxuser['oauthinfo']) && MODULE_NAME != 'Index') {
						if ($fansInfo) {
							$exist = $fansInfo['id'];
						} else {
							$existInfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
							$exist = $existInfo['id'];
						}
						$datainfo['token'] = $this->token;
						$datainfo['wecha_id'] = $this->wecha_id;
						if ($fake) {
							$datainfo['fakeopenid'] = $token_info['openid'];
						}
						if (3 == $wexintype) {
							$datainfo['fakeopenid'] = '';
							$datainfo['issub'] = $this->_issubService($datainfo['wecha_id']);
						}
						if ($exist) {
							D('Userinfo')->where(array('id' => $exist))->save($datainfo);
						} else {
							D('Userinfo')->add($datainfo);
						}
					}
					$_SESSION[$session_openid_name] = $this->wecha_id;
					$_SESSION[$session_oauthed_name] = 1;
					cookie('oauth2_' . $this->token, array('wap_token' => $this->token, 'wecha_id' => $this->wecha_id, 'appid' => $this->wxuser['appid']), 311040000);
				} else {
					if ($this->is_from_news_response()) {
						$this->wecha_id = $this->_get('wecha_id');
						if ($fake && $toAuth && !isset($_GET['isappinstalled'])) {
							$_SESSION[$session_fakeopenid_name] = $this->wecha_id;
						}
						if (!$toAuth) {
							$_SESSION[$session_openid_name] = $this->wecha_id;
						}
						if ($this->fans['issub'] == 0) {
							D('Userinfo')->setsubscribe($this->token, $this->wecha_id);
							D('Userfollow')->setsubscribe($this->token, $this->wecha_id, 1);
						}
					}
					if (isset($_GET['wecha_id']) && strlen($_GET['wecha_id']) && $toAuth) {
						$get_parms = $_GET;
						unset($get_parms['wecha_id']);
						unset($get_parms['news_response']);
						$get_parm_str = '';
						if ($get_parms) {
							$comma = '';
							foreach ($get_parms as $gpk => $gpv) {
								$get_parm_str .= $comma . $gpk . '=' . $gpv;
								$comma = '&';
							}
						}
						$get_parm_str .= '&g=' . GROUP_NAME . '&m=' . MODULE_NAME . '&a=' . ACTION_NAME;
						$_SESSION[$session_reopenid_name] = $this->wecha_id;
						header('Location:' . $this->siteUrl . '/index.php?' . $get_parm_str);
						die;
					}
				}
			}
		} else {
			$this->wecha_id = $_SESSION[$session_openid_name];
		}
		if ($this->is_from_news_response() && $this->fans['issub'] == 0) {
			D('Userinfo')->setsubscribe($this->token, $this->wecha_id);
			D('Userfollow')->setsubscribe($this->token, $this->wecha_id, 1);
		}
		if ($_GET['yundabao'] == 1) {
			cookie('yundabao', '1', 31536000);
		}
		$yundabao = cookie('yundabao');
		if ($yundabao != '1') {
			if ($this->wecha_id && !preg_match('/^[0-9a-zA-Z_\\-\\s]{3,82}$/', $this->wecha_id)) {
				die('error openid:' . $this->wecha_id);
			}
		}
		if (!$this->wecha_id) {
			if ($this->is_from_news_response()) {
				$this->wecha_id = $this->_get('wecha_id');
			} else {
				$this->wecha_id = '0';
			}
		}
		if (defined('APP_DEBUG') && APP_DEBUG && $_GET['rget'] == '1' && (!empty($_GET['rwid']) || !empty($_GET['rwname']))) {
			$rwid = $_GET['rwid'];
			if (!empty($_GET['rwname'])) {
				$rUser = M('userinfo')->where(array('token' => $this->token, 'wechaname' => array('like', '%' . $_GET['rwname'] . '%')))->field('wecha_id')->find();
				$rwid = $rUser['wecha_id'];
			}
			if (!empty($rwid)) {
				$this->wecha_id = $rwid;
				$_SESSION[$session_openid_name] = $this->wecha_id;
				session('wecha_id', $this->wecha_id);
			}
		}
		$this->assign('wecha_id', $this->wecha_id);
		if (!$fansInfo) {
			$fansInfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		}
		$advanceInfo = M('Wechat_group_list')->where(array('token' => $this->token, 'openid' => $this->wecha_id))->find();
		if ($advanceInfo) {
			$fansInfo['nickname'] = $advanceInfo['nickname'];
			if (!$fansInfo['wechaname']) {
				$fansInfo['wechaname'] = $advanceInfo['nickname'];
			}
			$fansInfo['sex'] = $advanceInfo['sex'];
			$fansInfo['province'] = $advanceInfo['province'];
			$fansInfo['city'] = $advanceInfo['city'];
		}
		if ($this->isFuwu) {
			$ali_userinfo_list = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => array('like', '%z_%')))->select();
			$ali_wecha_id_array = array();
			foreach ($ali_userinfo_list as $alivo) {
				if (in_array($alivo['wecha_id'], $ali_wecha_id_array)) {
					$del_ali_userinfo = M('Userinfo')->where(array('token' => $this->token, 'id' => $alivo['id'], 'wecha_id' => $alivo['wecha_id']))->delete();
				} else {
					$ali_wecha_id_array[] = $alivo['wecha_id'];
				}
			}
			$FuwuUserInfo = M('fuwuuser')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
			if ($FuwuUserInfo['gender'] == 'M') {
				$FuwuUserInfo['gender'] = 1;
			} else {
				if ($FuwuUserInfo['gender'] == 'F') {
					$FuwuUserInfo['gender'] = 2;
				} else {
					$FuwuUserInfo['gender'] = 0;
				}
			}
			if ($FuwuUserInfo['real_name'] != '') {
				$is_userinfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
				if ($is_userinfo == '') {
					$add_userinfo = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'issub' => 1, 'portrait' => $FuwuUserInfo['avatar'], 'truename' => $FuwuUserInfo['real_name'], 'nickname' => $FuwuUserInfo['real_name'], 'wechaname' => $FuwuUserInfo['real_name'], 'sex' => $FuwuUserInfo['gender']);
					$add_userinfo = array_map('nulltoblank', $add_userinfo);
					$id_Userinfo = M('Userinfo')->add($add_userinfo);
					D('Userfollow')->setsubscribe($this->token, $this->wecha_id, 1);
					S('sub_' . $this->token . '_' . $this->wecha_id, NULL);
				} else {
					if ($fansInfo['issub'] == 0) {
						$up_Userinfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('issub' => 1));
						D('Userfollow')->setsubscribe($this->token, $this->wecha_id, 1);
						S('sub_' . $this->token . '_' . $this->wecha_id, NULL);
					}
				}
				if ($fansInfo['wechaname'] == '') {
					$fansInfo['wechaname'] = $FuwuUserInfo['real_name'];
				}
				if ($fansInfo['portrait'] == '') {
					$fansInfo['portrait'] = $FuwuUserInfo['avatar'];
				}
				if ($fansInfo['nickname'] == '') {
					$fansInfo['nickname'] = $FuwuUserInfo['real_name'];
				}
				if ($fansInfo['truename'] == '') {
					$fansInfo['truename'] = $FuwuUserInfo['real_name'];
				}
				if ($fansInfo['tel'] == '') {
					$fansInfo['tel'] = $FuwuUserInfo['mobile'];
				}
			}
		}
		S('fans_' . $this->token . '_' . $this->wecha_id, $fansInfo);
		$this->fans = $fansInfo;
		$this->assign('fans', $fansInfo);
		$homeInfo = S('homeinfo_' . $this->token);
		if (!$homeInfo || 1) {
			$homeInfo = M('home')->where(array('token' => $this->token))->find();
			S('homeinfo_' . $this->token, $homeInfo);
		}
		$this->homeInfo = $homeInfo;
		$this->assign('homeInfo', $this->homeInfo);
		$catemenu = S('bottomMenus_' . $this->token);
		if (!$catemenu || 1) {
			$catemenu_db = M('catemenu');
			$catemenu = $catemenu_db->where(array('token' => $this->token, 'status' => 1))->order('orderss desc')->select();
			S('bottomMenus_' . $this->token, $catemenu);
		}
		$menures = array();
		if ($catemenu) {
			$res = array();
			$rescount = 0;
			foreach ($catemenu as $val) {
				$val['url'] = $this->getLink($val['url']);
				$res[$val['id']] = $val;
				if ($val['fid'] == 0) {
					$val['vo'] = array();
					$menures[$val['id']] = $val;
					$menures[$val['id']]['k'] = $rescount;
					$rescount++;
				}
			}
			foreach ($catemenu as $val) {
				$val['url'] = $this->getLink($val['url']);
				if (0 < $val['fid']) {
					array_push($menures[$val['fid']]['vo'], $val);
				}
			}
		}
		$catemenu = $menures;
		$this->bottomeMenus = $catemenu;
		$this->assign('catemenu', $this->bottomeMenus);
		$radiogroup = $homeInfo['radiogroup'];
		if ($radiogroup == false) {
			$radiogroup = 0;
		}
		$cateMenuFileName = 'tpl/Wap/default/Index_menuStyle' . $radiogroup . '.html';
		$this->assign('cateMenuFileName', $cateMenuFileName);
		$this->assign('radiogroup', $radiogroup);
		$this->user = S('user_' . $this->wxuser['uid']);
		if (!$this->user || 1) {
			$this->user = D('Users')->find(intval($this->wxuser['uid']));
			S('user_' . $this->wxuser['uid'], $this->user);
		}
		$this->assign('user', $this->user);
		$this->group = S('group_' . $this->user['gid']);
		if (!$this->group || 1) {
			$this->group = M('User_group')->where(array('id' => intval($this->user['gid'])))->find();
			S('group_' . $this->user['gid'], $this->group);
		}
		$this->assign('group', $this->group);
		$this->company = S('company_' . $this->token);
		if (!$this->company || 1) {
			$company_db = M('company');
			$this->company = $company_db->where(array('token' => $this->token, 'isbranch' => 0))->find();
			S('company_' . $this->token, $this->company);
		}
		$this->assign('company', $this->company);
		$this->copyright = $this->group['iscopyright'];
		$this->assign('iscopyright', $this->copyright);
		$this->assign('siteCopyright', C('copyright'));
		$this->assign('copyright', $this->copyright);
		$share = new WechatShare($this->wxuser, $this->wecha_id);
		$this->shareScript = $share->getSgin();
		$this->assign('shareScript', $this->shareScript);
		$this->fakeopenid = D('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->getField('fakeopenid');
		$allowpassArr = array('Share');
		if ($_SESSION['token_openid_' . $this->token] && $this->_get('wecha_id') && IS_GET && !IS_AJAX && !isset($_GET['state']) && !strpos($_SERVER['HTTP_HOST'], 'weixin.qq.com') && !in_array(MODULE_NAME, $allowpassArr) && !(0 < $users['is_syn'])) {
			$url_params = $_GET;
			unset($url_params['wecha_id']);
			unset($url_params['news_response']);
			header('Location:' . $this->siteUrl . '/index.php?g=' . GROUP_NAME . '&m=' . MODULE_NAME . '&a=' . ACTION_NAME . '&' . http_build_query($url_params));
			die;
		}
	}
	private function cookieToSession()
	{
		$cookie = cookie('oauth2_' . $this->token);
		$session_openid_name = 'token_openid_' . $this->token;
		if ($cookie && empty($_SESSION[$session_openid_name])) {
			if ($cookie->wecha_id == $_GET['wecha_id']) {
				$userinfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id|fakeopenid' => $_GET['wecha_id']))->order('id ASC')->find();
				if (empty($userinfo)) {
					return false;
				}
				if ($this->wxuser['oauthinfo']) {
					if (empty($userinfo['portrait']) || empty($userinfo['wechaname'])) {
						return false;
					}
				}
				if ($this->wxuser['appid'] == $cookie->appid) {
					$_SESSION[$session_openid_name] = $cookie->wecha_id;
				}
			}
		}
	}
	public function clearAuthCookie()
	{
		cookie('oauth2_' . $this->token, NULL);
	}
	private function _checkVipTime($wxuser)
	{
		if (S('checkVipTime_' . $wxuser['uid'])) {
			return true;
		}
		$users = M('Users')->where(array('id' => $wxuser['uid']))->find();
		$userGroup = M('UserGroup')->where(array('id' => $users['gid']))->find();
		if ($users['status'] == 0) {
			$this->error('服务商账号已经被停用');
		}
		if ($users['viptime'] < time()) {
			$this->error('服务商账号已经到期');
		}
		if ('-1' != $users['access_count']) {
			if ('0' < $users['access_count']) {
				$userGroup['access_count'] = $users['access_count'];
			}
			$userGroup['access_count_notice'] = empty($users['access_count_notice']) ? $userGroup['access_count_notice'] : $users['access_count_notice'];
			$wxusers = M('wxuser')->where(array('uid' => $wxuser['uid']))->select();
			foreach ($wxusers as $wxuser) {
				$tokens[] = $wxuser['token'];
			}
			$count = D('AccessCount')->getTokenCount($tokens);
			if ($userGroup['access_count'] <= $count && !empty($userGroup['access_count'])) {
				$notice = $userGroup['access_count_notice'] ? $userGroup['access_count_notice'] : '服务商本周访问数次超出限制';
				$this->error($notice . '【账号：' . $users['username'] . '】');
			}
		}
		S('checkVipTime_' . $wxuser['uid'], true, 900);
	}
	private function _mergeUserinfo($userinfo, $fakeUserinfo)
	{
		$fakeUserinfo['wecha_id'] = $userinfo['wecha_id'];
		foreach ($fakeUserinfo as $key => $value) {
			if (empty($fakeUserinfo[$key])) {
				$fakeUserinfo[$key] = $userinfo[$key];
			} else {
				if ('fakeopenid' == $key) {
					continue;
				}
				$fakeUserinfo[$key] = empty($userinfo[$key]) ? $fakeUserinfo[$key] : $userinfo[$key];
			}
		}
		unset($fakeUserinfo['id']);
		$fakeUserinfo['wecha_id'] = $userinfo['wecha_id'];
		return $fakeUserinfo;
	}
	public function getLink($url)
	{
		$url = $url ? $url : 'javascript:void(0)';
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
  	       } elseif ($this->strExists($url, '大转盘')) {
	          	$link = '/index.php?g=Wap&m=Lottery&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '优惠券')) {
			$link = '/index.php?g=Wap&m=Coupon&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '刮刮卡')) {
			$link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '商家订单')) {
			if ($itemid) {
				$link = $link = '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&hid=' . $itemid;
			} else {
				$link = '/index.php?g=Wap&m=Host&a=Detail&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			}
		} elseif ($this->strExists($url, '万能表单')) {
			if ($itemid) {
				$link = $link = '/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '相册')) {
			$link = '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Photo&a=plist&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '全景')) {
			$link = '/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Panorama&a=item&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '会员卡')) {
			$link = '/index.php?g=Wap&m=Card&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		} elseif ($this->strExists($url, '商城')) {
			$link = '/index.php?g=Wap&m=Product&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		} elseif ($this->strExists($url, '订餐')) {
			$link = '/index.php?g=Wap&m=Product&a=dining&dining=1&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		} elseif ($this->strExists($url, '团购')) {
			$link = '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		} elseif ($this->strExists($url, '首页')) {
			$link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		} elseif ($this->strExists($url, '网站分类')) {
			$link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&classid=' . $itemid;
			}
		} elseif ($this->strExists($url, '图文回复')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, 'LBS信息')) {
			$link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&companyid=' . $itemid;
			}
		} elseif ($this->strExists($url, 'DIY宣传页')) {
			$link = '/index.php/show/' . $this->token;
		} elseif ($this->strExists($url, '婚庆喜帖')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		} elseif ($this->strExists($url, '投票')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Vote&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		} else {

			$link = str_replace(array('{wechat_id}', '{siteUrl}', '&amp;', '{changjingUrl}'), array($this->wecha_id, $this->siteUrl, '&', 'http://www.meihua.com'), $url);
			if (!!(strpos($url, 'tel') === false) && $url != 'javascript:void(0)' && !strpos($url, 'wecha_id=')) {
				if (strpos($url, '?')) {
					$link = $link . '&wecha_id=' . $this->wecha_id;
				} else {
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
	public function curlGet($url)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
	private function curl_post($url, $post)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	public function getWdQrcode($modelId, $modules)
	{
		$modules = strtolower($modules);
		$actModel = ucwords($modules);
		if ($modules == 'bargain') {
			$actRow = M($actModel)->where(array('vifnn_id' => $modelId))->find();
		}
		$users = M('Users')->where(array('id' => $this->wxuser['uid']))->find();
		$micrstore_url = $users['source_domain'] ? $users['source_domain'] : 'http://www.11jie.cn';
		$salt = C('encryption') ? C('encryption') : '11jie.cn';
		$post_data = array('title' => $actRow['name'], 'info' => $actRow['info'], 'image' => $actRow['wxpic'], 'token' => $actRow['token'], 'keyword' => $actRow['keyword'], 'model' => $modules, 'modelId' => $modelId);
		$sort_data = $post_data;
		$sort_data['salt'] = $salt;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$post_data['sign_key'] = $sign_key;
		$url = $micrstore_url . '/api/activity/act_shop_qrcode.php';
		$curlResult = $this->curl_post($url, $post_data);
		$return = json_decode($curlResult, true);
		$data = $return['data'];
		return $data;
	}
	public function memberNotice($message = '', $style = 0, $custom_url = '', $custom_btn_msg = '', $is_scene = false, $scene_data = NULL)
	{
		S('fans_' . $this->token . '_' . $this->wecha_id, NULL);
		$this->wxuser['sub_notice_btn'] = $this->wxuser['sub_notice_btn'] ? $this->wxuser['sub_notice_btn'] : '立即获得权限';
		$this->wxuser['need_phone_notice'] = $this->wxuser['need_phone_notice'] ? $this->wxuser['need_phone_notice'] : '您好，请完善您的个人信息';
		$this->wxuser['sub_notice'] = $this->wxuser['sub_notice'] ? $this->wxuser['sub_notice'] : '您好，您还没有权限参加活动。';
		if (C('STATICS_PATH')) {
			$staticPath = '';
		} else {
			$staticPath = 'http://s.404.cn';
		}
		if ($style) {
			$message = empty($message) ? $this->wxuser['sub_notice'] : $message;
			$wxname = $this->wxuser['wxname'];
			$subBtn = '';
			$notice_btn = !empty($custom_btn_msg) ? $custom_btn_msg : $this->wxuser['sub_notice_btn'];
			if (!empty($custom_url)) {
				$subBtn = '<a href="' . $custom_url . '" class="flatbtn" style="display:block;color: #fff;">' . $notice_btn . '</a>';
				$subBtn2 = '<div class="wxname" id="wxsub"><a href="' . $custom_url . '" style="display:block;color: #fff;font-weight:normal">' . $notice_btn . '</a></div>';
			} else {
				$gzhurl = $this->wxuser['hurl'];
				if ($gzhurl == '') {
					$gzhurl = M('Home')->where(array('token' => $this->token))->getField('gzhurl');
				}
				if (!empty($gzhurl)) {
					$subBtn = '<a href="' . $gzhurl . '" class="flatbtn" style="display:block;color: #fff;">' . $notice_btn . '</a>';
					$subBtn2 = '<div class="wxname" id="wxsub"><a href="' . $gzhurl . '" style="display:block;color: #fff;font-weight:normal">' . $notice_btn . '</a></div>';
				}
			}
			$wxintype = $this->wxuser['winxintype'];
			$cid = $this->_GET('id', 'intval');
			$mods = MODULE_NAME;
			if ($mods == 'Autumns') {
				$modules = 'Activity';
				$ldb = M('Lottery');
				$lid = $ldb->where(array('id' => $cid))->getField('zjpic');
			} else {
				$moarr = array('Jiugong', 'Guajiang', 'GoldenEgg', 'LuckyFruit', 'AppleGame', 'Lovers', 'Autumn');
				$modules = in_array($mods, $moarr) ? 'Lottery' : $mods;
				$lid = $cid;
			}
			$keydb = M('Keyword');
			$where['pid'] = $lid;
			$where['token'] = $this->token;
			$where['module'] = $modules;
			$kword = $keydb->where($where)->getField('keyword');
			if ($wxintype == 3) {
				$disp = 'none';
				$ztext = '长按二维码并识别';
				if ($this->wxuser['is_syn'] == 1) {
					$apiQrcode = $this->getWdQrcode($cid, $modules);
					$wxqr = $apiQrcode['qcode'];
					$subBtn2 = '<div class="wxname" id="wxsub"><a href="' . $apiQrcode['hurl'] . '" style="display:block;color: #fff;font-weight:normal">' . $notice_btn . '</a></div>';
				} else {
					$recognitionModel = D('Recognition');
					if ($is_scene) {
						$recData = $scene_data;
						if (!isset($scene_data)) {
							$recData['url'] = __SELF__;
							$recData['post'] = $_POST;
							$recData['get'] = $_GET;
							$recData['token'] = $this->token;
							$recData['cookie'] = $_COOKIE;
						}
						$recType = is_string($is_scene) ? $is_scene : strtolower($modules);
						$recRes = $recognitionModel->createQrcode($recType, '', $recData, 600, $this->token);
					} else {
						$recRes = $recognitionModel->createQrcode('keyword', '', $kword, 600, $this->token);
					}
					$wxqr = $recRes['show'];
				}
			} else {
				$ztext = '第一步：长按二维码并识别';
				$wxqr = $this->wxuser['qr'];
				$disp = 'block';
			}
			if ($this->wxuser['is_syn'] == 2) {
				$disp = 'none';
				$ztext = '长按二维码并识别';
				$dis = 'none';
			}
			$memberNotice = '	<link rel="stylesheet" type="text/css" href="' . $staticPath . "/tpl/static/Plugin/fans.css\" />\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"/tpl/static/Plugin/guanz.css\" />\r\n\t\t<a href=\"javascript:;\" style=\"display:block\" class=\"closed\"><div class=\"_fly\" id=\"fly_page\" style=\"overflow:hidden\"></div></a>\r\n\t\t<div class=\"_flys\" id=\"fly_pages\" style=\"overflow:hidden\">\r\n\t\t\t<div class=\"pwpage\" style=\"z-index: 9999;\">\r\n\t\t\t    <div class=\"pwd\">\r\n\t\t\t    \t<h1>" . $ztext . "</h1>\r\n\t\t\t        <p class=\"title\" style=\"margin:0px\">请长按下图并选择识别图中二维码参与活动</p>\r\n\t\t\t        <img class=\"erweima\" src=\"" . $wxqr . "\">\r\n\t\t\t        <div style=\"display:" . $dis . "\">\r\n\t\t\t\t\t\t<p class=\"title\" style=\"margin:0px\">无法识别二维码请点击下面按钮参与活动</p>\r\n\t\t\t\t\t\t" . $subBtn2 . "\r\n\t\t\t        </div>\r\n\t\t\t        <div style=\"display:" . $disp . "\">\r\n\t\t\t        <h1>第二步：进入公众号聊天框</h1>\r\n\t\t\t        <p class=\"title\" style=\"margin:0px\">请输入关键词验证</p>\r\n\t\t\t        <p class=\"title\" style=\"margin:0px\">【<span class=\"key\">" . $kword . "</span>】</p>\r\n\t\t\t        </div>\r\n\t\t\t    </div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t<script type=\"text/javascript\" src=\"" . $staticPath . "/tpl/static/Plugin/topNotice.js\"></script>\r\n\t<script src=\"" . $staticPath . "/tpl/static/Plugin/jquery.leanModal.min.js\"></script>\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $staticPath . "/tpl/static/Plugin/leanModal.css\" />\r\n\t<div id=\"memberNoticeBox\" style=\"display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -170px; top: 110px;\">\r\n\t\t<h1>提醒</h1>\r\n\t\t<div class=\"txtfield\">" . $message . ' &nbsp; ( 公众号：' . $wxname . " )</div>\r\n\t\t" . $subBtn . "\r\n\t\t<a class=\"flatbtn btnC hidemodal\">关闭</a>\r\n\t</div>\r\n\t <div id=\"lean_overlay\" style=\"display: none; opacity: 0.45;\"></div>\r\n\t<script type=\"text/javascript\">\r\n\t\$(function(){\r\n\t\t\$(\"a.closed\").click(function(){\r\n\t\t\t\$(\".closed\").hide();\r\n        \t\$(\"._flys\").hide();\r\n\t\t});\r\n\t\t\$('#modaltrigger_notice').leanModal({\r\n\t\t\ttop:110,\r\n\t\t\toverlay:0.45,\r\n\t\t\tcloseButton:\".hidemodal\"\r\n\t\t});\r\n\t});\r\n\t</script>";
		} else {
			if ($this->wecha_id) {
				$href = U('Index/memberReg', array('token' => $this->token));
			} else {
				$href = U('Index/memberLogin', array('token' => $this->token));
			}
			if ($custom_url['is_voteimg'] == 1 && $custom_url['wecha_id'] != '' && $custom_btn_msg != '') {
				$href = U('Index/PhoneVerify', array('token' => $this->token, 'wecha_id' => $custom_url['wecha_id'], 'action_id' => $custom_btn_msg));
			}
			if ($custom_url['common_verify'] == 1 && $custom_url['wecha_id'] != '') {
				$href = U('Index/commonVerifyPhone', array('token' => $this->token, 'wecha_id' => $custom_url['wecha_id']));
			}
			$message = empty($message) ? $this->wxuser['need_phone_notice'] : $message;
			$memberNotice = '	<link rel="stylesheet" type="text/css" href="' . $staticPath . "/tpl/static/Plugin/fans.css\" />\r\n\t<link href=\"/tpl/static/Plugin/index.css\"  type=\"text/css\" rel=\"stylesheet\"/>\r\n\t<div class=\"zhezhao_zf\" style=\"display:block;position: fixed;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:999999999998;\"></div>\r\n<style>\r\n  .midTip_zf{position: fixed;top: 50%;margin-top: -45px;left: 0;right: 0;z-index:999999999999;overflow: initial;}\r\n  .midTip_zf .tipThis {background: #ffbf0f;width: 90%;margin: 0 auto;position: relative;padding: 15px 0;border-radius: 10px;overflow: initial;}\r\n  .midTip_zf .tipThis p{text-align: center;font-size: 12px;color: #fff;margin: 0;}\r\n  .midTip_zf .tipThis p i{display: inline-block;width:21px;height: 19px;background: url(\"" . $staticPath . "/tpl/static/Plugin/userIcon.png\") no-repeat;background-size: cover;vertical-align: middle;margin-right: 5px; margin-top: -4px;}\r\n  .midTip_zf .tipThis .goBtn{text-align: center;margin-top: 5px}\r\n  .midTip_zf .tipThis .goBtn a{color: #ffbf0f;background: #fafafa;border-radius: 20px;font-size: 10px;padding: 2px 10px;display: inline-block;}\r\n  .midTip_zf .tipThis .goBtn a i{display: inline-block;width: 12px;height: 12px;background: url(\"" . $staticPath . "/tpl/static/Plugin/yellowPen.png\") no-repeat;background-size: cover;vertical-align: middle;margin-right: 2px}\r\n  .midTip_zf .xxClosed{position: absolute;top: -30px;right: 0;background: url(\"" . $staticPath . "/tpl/static/Plugin/xxclosed.png\") no-repeat;background-size:cover;width: 20px;height: 20px;display: block}\r\n</style>\r\n<div class=\"midTip_zf\">\r\n  <div class=\"tipThis\">\r\n    <a href=\"javascript:;\" class=\"xxClosed\"></a>\r\n    <div class=\"tipInfo\">\r\n      <p><i></i>您好，你需要先填写个人信息才能参加活动。</p>\r\n      <div class=\"goBtn\">\r\n        <a href=\"" . $href . "\"><i></i>去填写</a>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n\t<article class=\"peplo_content clearfix\" id=\"TipContent\" style=\"display:none\"><div class=\"peplo_content_left\"><img src=\"/tpl/static/Plugin/us.png\"></div> <div style=\"float:left;line-height: 36px; width:80%;font-size:12px;\"><a href=\"" . $href . '" data-ajax="false" style="color: #FFF;font-weight: normal;text-decoration: none;">' . $message . "</a></div> <div class=\"peplo_content_right\" style=\"background:url(/tpl/static/Plugin/gb.png);background-size:15px 15px;\"> </div></article>\r\n\t<script>\r\n\t\$(function(){\r\n\t    \$(\".xxClosed\").click(function() {\r\n\t\t\$(\".midTip_zf,.zhezhao_zf\").fadeOut(1000);\r\n\t})\r\n\r\n\t})\r\n\t</script>\r\n\t<script type=\"text/javascript\" src=\"" . $staticPath . "/tpl/static/Plugin/topNotice.js\"></script>\r\n\t<script src=\"" . $staticPath . "/tpl/static/Plugin/jquery.leanModal.min.js\"></script>\r\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $staticPath . "/tpl/static/Plugin/leanModal.css\" />\r\n\t<div id=\"memberNoticeBox\" style=\"display: none; position: fixed; opacity: 1; z-index: 11000; left: 50%; margin-left: -170px; top: 110px;\">\r\n\t\t<h1>提醒</h1>\r\n\t\t<div class=\"txtfield\" style=\"width:92%\"><a href=\"" . $href . '" style="color:#800D8C">' . $message . "</a></div>\r\n\t\t<a href=\"" . $href . "\" class=\"flatbtn\">去填写</a>\r\n\t\t<a class=\"flatbtn btnC hidemodal\">取消</a>\r\n\t</div>\r\n\t <div id=\"lean_overlay\" style=\"display: none; opacity: 0.45;\"></div>\r\n\t<script type=\"text/javascript\">\r\n\t\$(function(){\r\n\t\t\$('#modaltrigger_notice').leanModal({\r\n\t\t\ttop:110,\r\n\t\t\toverlay:0.45,\r\n\t\t\tcloseButton:\".hidemodal\"\r\n\t\t});\r\n\t});\r\n\t</script>";
		}
		$this->assign('memberNotice', $memberNotice);
	}
	private function redirect_uri()
	{
		return urlencode($this->_redirect_uri);
	}
	public function getQrcode($kword)
	{
		$recdb = M('Recognition');
		$recognis = $recdb->where(array('keyword' => $kword, 'token' => $this->token))->find();
		$this->thisWxUser = M('Wxuser')->where(array('token' => $this->token))->find();
		$apiOauth = new apiOauth();
		$this->access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
		if ($recognis != '') {
			if ($recognis['code_url'] == '') {
				$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->access_token;
				if ($recognis['id'] < 100000) {
					$data['action_name'] = 'QR_LIMIT_SCENE';
					$data['action_info']['scene']['scene_id'] = $recognis['id'];
					$post = $this->api_notice_increment($qrcode_url, json_encode($data));
					$recdb->where(array_merge(array('id' => $recognis['id'])))->save(array('code_url' => $post));
				} else {
					$data['action_name'] = 'QR_SCENE';
					$data['action_info']['scene']['scene_id'] = $recognis['id'];
					$post = $this->api_notice_increment($qrcode_url, json_encode($data));
				}
				$wxqr = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $post;
			} else {
				$wxqr = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $recognis['code_url'];
			}
		} else {
			$dataz['keyword'] = $kword;
			$dataz['title'] = $kword;
			$dataz['token'] = $this->token;
			$xid = $recdb->add($dataz);
			$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->access_token;
			if ($xid < 100000) {
				$data['action_name'] = 'QR_LIMIT_SCENE';
				$data['action_info']['scene']['scene_id'] = $xid;
				$post = $this->api_notice_increment($qrcode_url, json_encode($data));
				$recdb->where(array_merge(array('id' => $xid)))->save(array('code_url' => $post));
			} else {
				$data['action_name'] = 'QR_SCENE';
				$data['action_info']['scene']['scene_id'] = $xid;
				$post = $this->api_notice_increment($qrcode_url, json_encode($data));
			}
			$wxqr = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $post;
		}
		return $wxqr;
	}
	protected function getCardInfo($token = '', $wecha_id = '')
	{
		$wecha_id = $wecha_id ? $wecha_id : $this->wecha_id;
		$token = $token ? $token : $this->token;
		$where = array('token' => $token, 'wecha_id' => $wecha_id);
		$number = M('Member_card_create')->where($where)->getField('number');
		if (!$number) {
			return NULL;
		}
		$cardInfo = M('Userinfo')->where($where)->field('balance,total_score')->find();
		return array('number' => $number, 'balance' => $cardInfo['balance'], 'score' => $cardInfo['total_score']);
	}
	private function _issubService($wecha_id)
	{
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($this->owxuser['appid'], $this->owxuser);
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?openid=' . $wecha_id . '&access_token=' . $access_token;
		$classData = json_decode($this->curlGet($url));
		if ($classData->subscribe == 0) {
			return 0;
		} else {
			return 1;
		}
	}
	protected function isSubscribe()
	{
		if ($this->wxuser['is_syn'] == 1) {
			return $this->wdIsSubscribe();
		}
		S('fans_' . $this->token . '_' . $this->wecha_id, NULL);
		return D('Userfollow')->isSub($this->token, $this->wecha_id);
		$wecha_id = $this->wecha_id;
		if ($this->owxuser['appid'] == '' || $this->owxuser['type'] == 0 && $this->owxuser['appsecret'] == '') {
			if ($this->owxuser['winxintype'] == 1 || $this->owxuser['winxintype'] == 2) {
				if ($wecha_id) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			if ($this->owxuser['winxintype'] == 3 || $this->owxuser['winxintype'] == 4) {
				$apiOauth = new apiOauth();
				$access_token = $apiOauth->update_authorizer_access_token($this->owxuser['appid'], $this->owxuser);
				$url = 'https://api.weixin.qq.com/cgi-bin/user/info?openid=' . $wecha_id . '&access_token=' . $access_token;
				$classData = json_decode($this->curlGet($url));
				if ($classData->subscribe == 0) {
					return false;
				} else {
					return true;
				}
			} else {
				if ($this->owxuser['winxintype'] == 1 || $this->owxuser['winxintype'] == 2) {
					if ($wecha_id) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
	}
	protected function wdIsSubscribe()
	{
		$users = M('Users')->where(array('id' => $this->wxuser['uid']))->find();
		$micrstore_url = $users['source_domain'] ? $users['source_domain'] : 'http://www.11jie.cn';
		$salt = C('encryption') ? C('encryption') : '11jie.cn';
		$post_data = array('token' => $this->token, 'openid' => $this->wecha_id);
		$sort_data = $post_data;
		$sort_data['salt'] = $salt;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$post_data['sign_key'] = $sign_key;
		$url = $micrstore_url . '/api/activity/act_isbind.php';
		$curlResult = $this->curl_post($url, $post_data);
		$return = json_decode($curlResult, true);
		$isBind = $return['is_bind'];
		return $isBind == 1 ? true : false;
	}
	public function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);
		if ($errorno) {
			$this->error('发生错误：curl error' . $errorno);
		} else {
			$js = json_decode($tmpInfo, true);
			if (isset($js['ticket'])) {
				return $js['ticket'];
			} else {
				$this->error('发生错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);
			}
		}
	}
	public function is_from_news_response()
	{
		return isset($_GET['news_response']) && $_GET['news_response'] === '1';
	}
	protected function checkBlacklist()
	{
		$blacklistStatus = $this->wxuser['blacklist_status'];
		if ($blacklistStatus == '1') {
			$blacklistModel = D('Blacklist');
			if (!$blacklistModel->checkList($this->wecha_id, $this->token)) {
				$this->error('您的IP：' . get_client_ip() . '已被限制访问此请求,如有疑问请联系管理员解除限制');
				die;
			}
		}
	}
}