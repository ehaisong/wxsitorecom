<?php

class UsersAction extends BaseAction {

    public function index() {
        header("Location: /");
    }

    public function companylogin() {
        $dbcom = D('Company');
        $where['username'] = $this->_post('username', 'trim');
        $cid = $where['id'] = $this->_post('cid', 'intval');
        $k = $this->_post('k', 'trim, htmlspecialchars');
        if (empty($k) || $k != md5($where['id'] . $where['username'])) {
            $this->error('帐号密码错误', U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
        }

        $pwd = $this->_post('password', 'trim,md5');
        $company = $dbcom->where($where)->find();
        if ($company && ($pwd === $company['password'])) {
            if ($wxuser = D('Wxuser')->where(array('token' => $company['token']))->find()) {
                $uid = $wxuser['uid'];
                $db = D('Users');
                $res = $db->where(array('id' => $uid))->find();
            } else {
                $this->error('帐号密码错误', U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
            }
            session('companyk', $k);
            session('companyLogin', 1);
            session('companyid', $company['id']);
            session('token', $company['token']);
            session('company_business_type', explode(',', $company['business_type']));
            session('uid', $res['id']);
            session('gid', $res['gid']);
            session('uname', $res['username']);
            $info = M('user_group')->find($res['gid']);
            session('diynum', $res['diynum']);
            session('connectnum', $res['connectnum']);
            session('activitynum', $res['activitynum']);
            session('viptime', $res['viptime']);
            session('gname', $info['name']);
            //每个月第一次登陆数据清零
            $now = time();
            $month = date('m', $now);
            if ($month != $res['lastloginmonth'] && $res['lastloginmonth'] != 0) {
                $data['id'] = $res['id'];
                $data['imgcount'] = 0;
                $data['diynum'] = 0;
                $data['textcount'] = 0;
                $data['musiccount'] = 0;
                $data['connectnum'] = 0;
                $data['activitynum'] = 0;
                $db->save($data);
                //
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
            }
            //登陆成功，记录本月的值到数据库
            //
			$db->where(array('id' => $res['id']))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => $_SERVER['REMOTE_ADDR'])); //最后登录时间

            $m_seller = M("livingcircle_seller");
            $where_seller['token'] = $company['token'];
            $where_seller['cid'] = $this->_post('cid', 'intval');
            $find_seller = $m_seller->where($where_seller)->find();
            if (empty($find_seller)) {
                if (strpos($company['business_type'], ',') === false) {
                    $jump = $company['business_type'];
                } else {
                    $business_types = explode(",", $company['business_type']);
                    $jump = $business_types[0];
                }
                $this->success('登录成功', U('User/' . $jump . '/index', array('cid' => $cid)));
            } else {
                $this->success('登录成功', U('User/LivingCircle/myseller', array('token' => $company['token'])));
            }
        } else {
            $this->error('帐号密码错误', U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
        }
    }

    public function companyLogout() {
        $cid = session('companyid');
        $k = session('companyk');
        session(null);
        session_destroy();
        unset($_SESSION);
        if (session('?' . C('USER_AUTH_KEY'))) {
            session(C('USER_AUTH_KEY'), null);

            redirect(U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
        } else {
            $this->success('已经登出！', U('Home/Index/clogin', array('cid' => $cid, 'k' => $k)));
        }
    }

    public function checklogin() {
        $verifycode = $this->_post('verifycode2', 'md5', 0);
		if (isset($_POST['verifycode2'])){   //判断验证码是否存在传递
			if($verifycode != $_SESSION['loginverify']){
				$this->error('验证码错误',U('Index/login'));
			}
		}
		$_SESSION['codejm'] = $verifycode;
        $db = D('Users');
        $uname = $this->_post('username');
        $viptime = $db->where(array('username' => $uname))->getfield('viptime');
        $where['username'] = $this->_post('username', 'trim');

        // if($db->create()==false)
        // $this->error($db->getError());
        $pwd = $this->_post('password', 'trim,md5');
        $res = $db->where($where)->find();
        if (!$res && strlen($where['username']) == 11) {

            $res = $db->where(array('mp' => $this->_post('username', 'trim')))->find();
        }
        if ($res && ($pwd === $res['password'])) {

            if ($res['status'] == 0) {
                $this->error('请联系在线客户，为你人工审核帐号');
                exit;
            }

            if (C('agent_version')) {
                if ((int) $this->thisAgent['id'] != $res['agentid']) {
                    $this->error('您使用的网址不对');
                    exit;
                }

// 				$account = D('Weixin_account')->where(array('agentid' => $res['agentid']))->find();
// 				if ($account && $account['type'] == 1 && (empty($account['appId']) || empty($account['appSecret']) || empty($account['token']) || empty($account['encodingAesKey']))) {
// 					$k = $this->authcode("{$uname}\t{$pwd}", 'ENCODE');
// 					header('Location:' . C('site_url') . '/index.php?m=Users&a=gologin&k=' . base64_encode($k));
// 					exit();
// 				}
            }
            session('uid', $res['id']);
            session('gid', $res['gid']);
            session('uname', $res['username']);
            $info = M('user_group')->find($res['gid']);
            session('diynum', $res['diynum']);
            session('connectnum', $res['connectnum']);
            session('activitynum', $res['activitynum']);
            session('viptime', $res['viptime']);
            session('gname', $info['name']);
            //每个月第一次登陆数据清零
            $now = time();
            $month = date('m', $now);
            if ($month != $res['lastloginmonth'] && $res['lastloginmonth'] != 0) {
                $data['id'] = $res['id'];
                $data['imgcount'] = 0;
                $data['diynum'] = 0;
                $data['textcount'] = 0;
                $data['musiccount'] = 0;
                $data['connectnum'] = 0;
                $data['activitynum'] = 0;
                $db->save($data);
                //
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
            }
            //登陆成功，记录本月的值到数据库
            //
			session('role_name', null);
            session('staff_id', null);
            session('first_func', null);
            $_SESSION['role_name'] = '';
            $_SESSION['staff_id'] = '';
            $_SESSION['first_func'] = '';
            unset($_SESSION['role_name']);
            unset($_SESSION['staff_id']);
            unset($_SESSION['first_func']);
            $db->where(array('id' => $res['id']))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间



            $this->success('登录成功', U('User/Index/info'));
        } else {
            $this->error('帐号密码错误', U('Index/login'));
        }
    }

    /*     * ****对接收银台登录，根据token来登陆****** */

    public function cashierBack($pflgcode) {
        $token = trim($_GET['token']);
        $lgcode = isset($_GET['lgcode']) && !empty($_GET['lgcode']) ? base64_decode(trim($_GET['lgcode'])) : false;
		if (!empty($pflgcode)) {
			$lgcode = base64_decode($pflgcode);
		}
        if ($lgcode) {
            $lgcode = $this->authcode($lgcode, 'DECODE', 'lhs_simple_encryption_code_87063');
            $lgcodeArr = json_decode($lgcode, TRUE);
            $nowtime = time() - 5;
            if (!empty($lgcodeArr) && ($lgcodeArr['ptoken'] == $token) && ($lgcodeArr['mktime'] > $nowtime) && ($lgcodeArr['mid'] > 0)) {
                $towelcome = true;
                $token = $lgcodeArr['ptoken'];
                $uid = session('uid');
                $gid = session('gid');
                if (empty($uid) || empty($gid)) {
                    $wxuser = D('Wxuser')->where(array('token' => $token))->find();
                    if (!empty($wxuser)) {
                        session('token', $token);
                        session('uid', $wxuser['uid']);
                        $res = D('Users')->where(array('id' => $wxuser['uid']))->find();
                        session('gid', $res['gid']);
                        session('uname', $res['username']);
                        $info = M('user_group')->find($res['gid']);
                        session('diynum', $res['diynum']);
                        session('connectnum', $res['connectnum']);
                        session('activitynum', $res['activitynum']);
                        session('viptime', $res['viptime']);
                        session('gname', $info['name']);
                        unset($info, $res);
                    } else {
                        $towelcome = false;
                    }
                }
                if ($towelcome) {
                    /*                     * *跳转到相应token功能管理页** */
                                        if (!empty($pflgcode)) {
						$redirectUrl = GROUP_NAME . "/" . MODULE_NAME . "/" . ACTION_NAME;
						redirect(U($redirectUrl, array("token" => $token)));
					}
					else {
                                            redirect(U('User/Function/welcome', array('token' => $token)));
                                        }
                    exit();
                }
            }
        }

        $this->error('信息错误，无法登录');
    }

    /*     * ****对接收银台绑定微信账号****** */

    public function cashierBindAppid() {
        $token = trim($_GET['token']);
        $lgcode = isset($_GET['lgcode']) && !empty($_GET['lgcode']) ? base64_decode(trim($_GET['lgcode'])) : false;
        if ($lgcode) {
            $lgcode = $this->authcode($lgcode, 'DECODE', 'lhs_simple_encryption_code_87063');
            $lgcodeArr = json_decode($lgcode, TRUE);
            if (!empty($lgcodeArr) && isset($lgcodeArr['username']) && isset($lgcodeArr['uid'])) {
                $towelcome = true;
                $res = D('Users')->where(array('id' => $lgcodeArr['uid']))->find();
                $comefromid = $lgcodeArr['ctoken'] . '-' . $lgcodeArr['cusername'];
                if (!empty($res) && ($res['username'] == $lgcodeArr['username']) && !empty($res['comefromid']) && ($comefromid == $res['comefromid'])) {
                    session('token', '');
                    session('comefromsyttoken', $lgcodeArr['ctoken']);
                    session('uid', $res['id']);
                    session('gid', $res['gid']);
                    session('uname', $res['username']);
                    session('diynum', $res['diynum']);
                    session('connectnum', $res['connectnum']);
                    session('activitynum', $res['activitynum']);
                    session('viptime', $res['viptime']);
                    $info = M('user_group')->find($res['gid']);
                    if ($info) {
                        session('gname', $info['name']);
                    }
                    unset($info, $res);
                } else {
                    $towelcome = false;
                }

                if ($towelcome) {
                    /*                     * *跳转到相应token功能管理页** */
                    redirect(U('User/Index/add', array('comefromsyttoken' => $lgcodeArr['ctoken'])));
                    exit();
                }
            }
        }

        $this->error('信息错误，无法登录');
    }

    /*     * ***收银台过来的注册******** */

    public function cashierReg() {
        $nowtime = time();
        if (IS_POST) {
            $ctoken = trim($_GET['ctoken']);
            $postStr = file_get_contents("php://input");
            $postStr = trim($postStr);
            $postStr = $this->authcode(base64_decode($postStr), 'DECODE', 'lhs_simple_encryption_code_87063');
            $postdata = json_decode($postStr, true);
            $postdata['ctoken'] = trim($postdata['ctoken']);
            if (!empty($postdata) && ($postdata['ctoken'] == $ctoken)) {
                $postdata['username'] = trim($postdata['username']);
                $md5str = md5($postdata['regtime'] . 'cf$cashier$Reg' . $postdata['ctoken']);
                if ($md5str == $postdata['verifycode']) {
                    $username = $postdata['username'] . '1cashier';
                    $Usersdb = M('Users');
                    $comefromid = $postdata['ctoken'] . '-' . $postdata['username'];
                    $userArr = array('username' => $username);
                    $userdbArr = $Usersdb->where($userArr)->find();
                    if (!empty($userdbArr)) {
                        if ($userdbArr['comefromid'] == $comefromid) {
                            $codeStr = json_encode(array('uid' => $userdbArr['id'], 'uname' => $userdbArr['username'], 'pwd' => '', 'isadd' => 0));
                            $codeStr = $this->authcode($codeStr, 'ENCODE', 'lhs_simple_encryption_code_87063');
                            $codeStr = base64_encode($codeStr);
                            echo json_encode(array('error' => 0, 'msg' => '账号已经存在了！', 'lgcode' => $codeStr));
                            exit();
                        } else {
                            echo json_encode(array('error' => 1, 'msg' => '用户名已经被注册了'));
                            exit();
                        }
                    } else {

                        $inserData = array('username' => $username, 'comefromid' => $comefromid,
                            'gid' => $this->minGroupid, 'mp' => $postdata['tel'],
                            'email' => $postdata['email'], 'createtime' => $nowtime,
                            'lasttime' => $nowtime, 'viptime' => time() + 7 * 24 * 3600);
                        if (strlen($postdata['ctoken']) > 7) {
                            $pwd = substr($postdata['ctoken'], 0, 7);
                        } else {
                            $pwd = $postdata['ctoken'];
                        }
                        $inserData['password'] = md5($pwd);
                        if ($this->reg_needCheck) {
                            $inserData['status'] = 1;
                            //$inserData['viptime'] = time();
                        } else {
                            $reg_validDays = intval($this->reg_validDays);
                            $this->reg_validDays > 0 && $inserData['viptime'] = time() + $reg_validDays * 24 * 3600;
                            if ($this->reg_groupid) {
                                $gid = intval($this->reg_groupid);
                            }
                            $inserData['status'] = 1;

                            $inserData['gid'] = $gid;
                        }
                        $inserData['lastip'] = htmlspecialchars(trim(get_client_ip()));
                        $inserData['invitecode'] = $this->randStr(6);
                        $inserData['usertplid'] = get_default_tplid();
                        $inserData['lastloginmonth'] = date('m', $nowtime);
                        $id = $Usersdb->add($inserData);
                        if ($id > 0) {
                            $codeStr = json_encode(array('uid' => $id, 'uname' => $username, 'pwd' => $pwd, 'isadd' => 1));
                            $codeStr = $this->authcode($codeStr, 'ENCODE', 'lhs_simple_encryption_code_87063');
                            $codeStr = base64_encode($codeStr);
                            echo json_encode(array('error' => 0, 'msg' => '账号创建成功！', 'lgcode' => $codeStr));
                            exit();
                        }
                    }
                }
            }
            echo json_encode(array('error' => 1, 'msg' => '注册失败！'));
            exit();
        }

        echo json_encode(array('error' => 1, 'msg' => '拔剑吧，你不该来此地'));
        exit();
    }

    /**
     * 加密和解密函数
     */
    private function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : 'vifnn');
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

    public function gologin() {
        $db = D('Users');
        $k = base64_decode($this->_get('k'));
        $k = explode("\t", $this->authcode($k, 'DECODE'));
        list($uname, $pwd) = empty($k) || count($k) < 2 ? array('', '') : $k;

        $viptime = $db->where(array('username' => $uname))->getfield('viptime');
        $where['username'] = $uname;

        $res = $db->where($where)->find();
        if ($pwd === $res['password']) {
            session('uid', $res['id']);
            session('gid', $res['gid']);
            session('uname', $res['username']);
            $info = M('user_group')->find($res['gid']);
            session('diynum', $res['diynum']);
            session('connectnum', $res['connectnum']);
            session('activitynum', $res['activitynum']);
            session('viptime', $res['viptime']);
            session('gname', $info['name']);
            //每个月第一次登陆数据清零
            $now = time();
            $month = date('m', $now);
            if ($month != $res['lastloginmonth'] && $res['lastloginmonth'] != 0) {
                $data['id'] = $res['id'];
                $data['imgcount'] = 0;
                $data['diynum'] = 0;
                $data['textcount'] = 0;
                $data['musiccount'] = 0;
                $data['connectnum'] = 0;
                $data['activitynum'] = 0;
                $db->save($data);
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
            }
            //登陆成功，记录本月的值到数据库
            session('role_name', null);
            session('staff_id', null);
            session('first_func', null);
            $_SESSION['role_name'] = '';
            $_SESSION['staff_id'] = '';
            $_SESSION['first_func'] = '';
            unset($_SESSION['role_name']);
            unset($_SESSION['staff_id']);
            unset($_SESSION['first_func']);
            $db->where(array('id' => $res['id']))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间
            $this->success('登录成功', U('User/Index/info'));
        } else {
            $this->error('帐号密码错误', U('Index/login'));
        }
    }

    function randStr($randLength) {
        $randLength = intval($randLength);
        $chars = 'abcdefghjkmnpqrstuvwxyz';
        $len = strlen($chars);
        $randStr = '';
        for ($i = 0; $i < $randLength; $i++) {
            $randStr.=$chars[rand(0, $len - 1)];
        }
        return $randStr;
    }

    public function checkreg() {
         if(C('isclosekuser')=='true'){
			$this->error('系统已经关闭注册！',U('Index/info'));
         }
        $db = D('Users');
        //
        if (C('server_topdomain') != 'vifnn.cn') {
            $usercount = $db->where(array('lastip' => htmlspecialchars(trim(get_client_ip()))))->count();
            if ($usercount > 50) {
                $this->error('注册太频繁', U('Index/login'));
            }
        }
        //

        $info = M('User_group')->find(1);
        $verifycode = $this->_post('verifycode', 'md5', 0);
		if (isset($_POST['verifycode'])){
			if($verifycode != $_SESSION['verify']){
				$this->error('验证码错误',U('Index/login'));
			}
		}
        if($_POST['username'] == 'admin'){
            $this->error('用户名不能为admin');
        }

        if (empty($_POST['username'])) {
            $this->error('用户名不能为空');
        }

        if (strpos($_POST['username'], '<') !== false || strpos($_POST['username'], '>') !== false) {
            $this->error('用户名中不允许出现 < 或 > 符号');
        }

        $_POST['username'] = htmlspecialchars(trim($_POST['username']));
        if (C('reg_mp_verify') == 1) {
            if (session('reg_mp') != md5($this->_post('mp'))) {
                $this->error('请输入刚接收验证码的手机号');
            }
        }
        if (isset($_POST['mp'])) {
            if (!preg_match('/^1[0-9]{10}$/', trim($_POST['mp']))) {
                $this->error('手机号填写不正确', U('Index/login'));
            }
        }
	if (C('server_topdomain') != 'vifnn.cn') {
            $mp = $this->_post('mp');
            $countMP = M('Users')->where(array('mp' => $mp))->count();
            if ($countMP > 0)
                $this->error('手机号已被注册');
        }

        if ($this->isAgent) {
            $_POST['agentid'] = $this->thisAgent['id'];
        }
        if (isset($_POST['invitecode'])) {
            //$_POST['invitecode']=$this->_get('invitecode');
            $inviteCode = $this->_post('invitecode');
            if ($inviteCode && !ctype_alpha($inviteCode)) {
                exit('invitecode colud not include other letter');
            }
            $inviter = $db->where(array('invitecode' => $inviteCode))->find();
            $_POST['inviter'] = intval($inviter['id']);
        } else {
            $_POST['inviter'] = 0;
        }
        $_POST['invitecode'] = $this->randStr(6);
        $_POST['usertplid'] = get_default_tplid();
        if ($db->create()) {
            $id = $db->add();
            if ($id) {
                $now = time();
                $db->where(array('id' => $id))->save(array('lasttime' => $now, 'lastloginmonth' => date('m', $now), 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间
                //
				Sms::sendSms('admin', '有新用户注册了', $this->adminMp);
                if ($this->isAgent) {
                    $usercount = M('Users')->where(array('agentid' => $this->thisAgent['id']))->count();
                    M('Agent')->where(array('id' => $this->thisAgent['id']))->save(array('usercount' => $usercount));
                }
                if ($this->reg_needCheck) {
                    $gid = $this->minGroupid;
                    if (C('demo')) {
                        session('preuid', $id);
                        $this->success('注册成功,请关注我们公众号获取使用权限', U('Index/qrcode'));
                        exit;
                    } else {
                        $this->success('注册成功,请联系在线客服审核帐号', U('User/Index/info'));
                        exit;
                    }
                } else {
                    $viptime = time() + intval($this->reg_validDays) * 24 * 3600;
                    $gid = $this->minGroupid;
                    if ($this->reg_groupid) {
                        $gid = intval($this->reg_groupid);
                    }
                    $db->where(array('id' => $id))->save(array('viptime' => $viptime, 'status' => 1, 'gid' => $gid));
                }

                session('uid', $id);
                session('gid', $gid);
                session('uname', $_POST['username']);
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
                session('gname', $info['name']);
                // $smtpserver = C('email_server'); 
                // $port = C('email_port');
                // $smtpuser = C('email_user');
                // $smtppwd = C('email_pwd');
                // $mailtype = "TXT";
                // $sender = C('email_user');
                // $smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
                // $to = $list['email']; 
                // $subject = C('reg_email_title');
                // $code = C('site_url').U('User/Index/checkFetchPass?uid='.$list['id'].'&code='.md5($list['id'].$list['password'].$list['email']));
                // $fetchcontent = C('reg_email_content');
                // $fetchcontent = str_replace('{username}',$where['username'],$fetchcontent);
                // $fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
                // $fetchcontent = str_replace('{code}',$code,$fetchcontent);
                // $body=$fetchcontent;
                //$body = iconv('UTF-8','gb2312',$fetchcontent);
                // $send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);

                $this->success('注册成功', U('User/Index/info'));
            } else {
                $this->error('注册失败', U('Index/login'));
            }
        } else {
            $this->error($db->getError(), U('Index/login'));
        }
    }

    public function checkpwd() {

        $where['username'] = $this->_post('username');
        $where['email'] = $this->_post('email');
        $db = D('Users');
        $list = $db->where($where)->find();
        if ($list == false)
            $this->error('邮箱和帐号不正确', U('Index/regpwd'));

        $smtpserver = C('email_server');
        $port = C('email_port');
        $smtpuser = C('email_user');
        $smtppwd = C('email_pwd');
        $mailtype = "TXT";
        $sender = C('email_user');
        $smtp = new Smtp($smtpserver, $port, true, $smtpuser, $smtppwd, $sender);
        $to = $list['email'];
        $subject = C('pwd_email_title');
        $code = C('site_url') . U('Index/resetpwd', array('uid' => $list['id'], 'code' => md5($list['id'] . $list['password'] . $list['email']), 'resettime' => time()));
        $fetchcontent = C('pwd_email_content');
        $fetchcontent = str_replace('{username}', $where['username'], $fetchcontent);
        $fetchcontent = str_replace('{time}', date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), $fetchcontent);
        $fetchcontent = str_replace('{code}', $code, $fetchcontent);
        $body = $fetchcontent;
        //$body = iconv('UTF-8','gb2312',$fetchcontent);inv
        $send = $smtp->sendmail($to, $sender, $subject, $body, $mailtype);
        $this->success('请访问你的邮箱 ' . $list['email'] . ' 验证邮箱后登录!<br/>');
    }

    public function resetpwd() {
        $where['id'] = $this->_post('uid', 'intval');
        $where['password'] = $this->_post('password', 'md5');
        if (M('Users')->save($where)) {
            $this->success('修改成功，请登录！', U('Index/login'));
        } else {
            $this->error('密码修改失败！', U('Index/info'));
        }
    }

    public function sendMsg() {

        if (IS_POST) {
            if (strlen($this->_post('mp')) != 11) {
                exit('Error Phone Number!');
            }
            for ($i = 0; $i < 6; $i++) {
                $code .= rand(0, 9);
            }

            session('verify', md5($code));
            session('reg_mp', md5($this->_post('mp')));
            //Sms::sendSms('admin','尊敬的客户，注册验证码是：'.$code.',我们的工作人员不会向您索取本条消息内容，切勿向任何人透漏',$this->_post('mp'));

            require('./vifnn/Lib/ORG/RestSMS.php');

            sendTempSMS($this->_post('mp'), array($code), "4764"); //手机号码，替换内容数组，模板ID 4764
        } else {
            exit("Error!");
        }
    }

    public function checkMP() {
        $mp = $this->_post('mp');
        $count = M('Users')->where(array('mp' => $mp))->count();
        if ($count) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function rlogin() {

        $this->rloginck();
        $db = D('Users');
        $where['username'] = $this->_get('username', 'trim');

        // if($db->create()==false)
        // $this->error($db->getError());
        $pwd = $this->_get('password', 'trim,md5');
        $res = $db->where($where)->find();
        if (!$res && strlen($where['username']) == 11) {

            $res = $db->where(array('mp' => $this->_post('username', 'trim')))->find();
        }
        if ($res && ($pwd === $res['password'])) {

            if ($res['status'] == 0) {
                if (isset($_GET['api'])) {
                    echo -2;
                    exit;
                } else {
                    $this->error('请联系在线客户，为你人工审核帐号');
                    exit;
                }
            }

            if (C('agent_version')) {
                if ((int) $this->thisAgent['id'] != $res['agentid']) {
                    if (isset($_GET['api'])) {
                        echo -3;
                        exit;
                    } else {
                        $this->error('您使用的网址不对');
                        exit;
                    }
                }
            }
            session('uid', $res['id']);
            session('gid', $res['gid']);
            session('uname', $res['username']);
            $info = M('user_group')->find($res['gid']);
            session('diynum', $res['diynum']);
            session('connectnum', $res['connectnum']);
            session('activitynum', $res['activitynum']);
            session('viptime', $res['viptime']);
            session('gname', $info['name']);
            //每个月第一次登陆数据清零
            $now = time();
            $month = date('m', $now);
            if ($month != $res['lastloginmonth'] && $res['lastloginmonth'] != 0) {
                $data['id'] = $res['id'];
                $data['imgcount'] = 0;
                $data['diynum'] = 0;
                $data['textcount'] = 0;
                $data['musiccount'] = 0;
                $data['connectnum'] = 0;
                $data['activitynum'] = 0;
                $db->save($data);
                //
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
            }
            //登陆成功，记录本月的值到数据库
            //
			$db->where(array('id' => $res['id']))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间
            if (isset($_GET['api'])) {
                echo 1;
            } else {
                $this->success('登录成功', U('User/Index/info'));
            }
        } else {
            if (isset($_GET['api'])) {
                echo -1;
            } else {
                $this->error('帐号密码错误', U('Index/login'));
            }
        }
    }

    //店员登陆
    public function staff_checklogin() {
        $token = $this->_post('token', 'trim');
        if (empty($token)) {
            $this->error('登陆地址错误');
        }
        $verifycode = $this->_post('verifycode2', 'md5', 0);
        if (isset($_POST['verifycode2'])) {
            if ($verifycode != $_SESSION['loginverify']) {
                $this->error('验证码错误', U('Index/staff_login', array('token' => $token)));
            }
        }
        $company_staff = M('company_staff');
        $where = array();
        $where['token'] = $token;
        $where['username'] = $this->_post('username', 'trim');
        $where['comefrom'] = 1;
        $staff = $company_staff->where($where)->find();
        $func = explode(",", $staff['func']);
        $first_func = $func[0];
        if (empty($staff)) {
            $this->error('用户名不正确', U('Index/staff_login', array('token' => $token)));
            exit;
        }
        $password = $this->_post('password', 'trim');
        $password = md5($password . 'assistente');
        if ($password != $staff['password']) {
            $this->error('密码不正确', U('Index/staff_login', array('token' => $token)));
            exit;
        } elseif ($staff['pcorwap'] == 2) {
            $this->error('您的权限类型只适用于手机端', U('Index/staff_login', array('token' => $token)));
            exit;
        } else {
            if ($wxuser = D('Wxuser')->where(array('token' => $staff['token']))->find()) {
                $uid = $wxuser['uid'];
                $db = D('Users');
                $res = $db->where(array('id' => $uid))->find();
            }
            $_SESSION = array();
            session('uid', $res['id']);
            session('wid', $wxuser['id']);
            session('gid', $res['gid']);
            session('role_name', 'staff');
            session('token', $staff['token']);
            session('staff_id', $staff['id']);
            session('staff_username', $staff['username']);
            session('first_func', $first_func);
        }
        $this->success('登录成功', U('User/Function/welcome', array('id' => $wxuser['id'], 'token' => $staff['token'])));
    }

    //退出
    public function staff_logout() {
        session_start();
        session(null);
        session_destroy();
        unset($_SESSION);
        session_unset();
        $_SESSION = array();
        $_SESSION['role_name'] = '';
        $_SESSION['token'] = '';
        $_SESSION['staff_id'] = '';
        $_SESSION['first_func'] = '';
        $this->error('已经登出！', U('Home/Index/staff_login', array('token' => $_GET['token'])));
    }
/*
    public function currentUpdateFileID() {
        $updateRecord = M('System_info')->order('lastsqlupdate DESC')->find();
        echo $updateRecord['currentfileid'];
    }

    public function currentUpdateSqlID() {
        $updateRecord = M('System_info')->order('lastsqlupdate DESC')->find();
        echo $updateRecord['currentsqlid'];
    }

    public function sqlUpdate() {
        $rt = $_POST;
        $now = time();
        //服务器id和本地正在更新id的对比
        $updateRecord = M('System_info')->order('lastsqlupdate DESC')->find();
        if (!$updateRecord['currentsqlid'] || intval($updateRecord['currentsqlid']) != intval($rt['sqlid'])) {
            echo '-16'; //本地验证失败
            exit;
        }
        //核对key
        $key = trim(C('server_key'));
        $keyExist = 0;
        $serverKey = $rt['key'];
        $keys = explode(',', $key);
        $serverKeys = explode(',', $serverKey);
        if ($keys) {
            foreach ($keys as $k) {
                if (in_array($k, $serverKeys)) {
                    $keyExist = 1;
                }
            }
        }
        if (!$keyExist) {
            echo '-17';
            exit;
        }
        $Model = new Model();
        error_reporting(0);
        $lowsql = strtolower($rt['sql']);
        $replacesql = str_replace('delete', '', $lowsql);
        if ($replacesql != $lowsql) {
            if (strpos($lowsql, 'delete') == 0) {
                exit;
            }
        }
        @mysql_query(str_replace('{tableprefix}', C('DB_PREFIX'), $rt['sql']));
        echo 1;
    }

    public function fileUpdate() {
        $rt = $_POST;
        $now = time();
        //服务器id和本地正在更新id的对比
        $updateRecord = M('System_info')->order('lastsqlupdate DESC')->find();
        if (!$updateRecord['currentfileid'] || intval($updateRecord['currentfileid']) != intval($rt['fileid'])) {
            echo '-16'; //本地验证失败
            exit;
        }
        //核对key
        $key = trim(C('server_key'));
        $keyExist = 0;
        $serverKey = $rt['key'];
        $keys = explode(',', $key);
        $serverKeys = explode(',', $serverKey);
        if ($keys) {
            foreach ($keys as $k) {
                if (in_array($k, $serverKeys)) {
                    $keyExist = 1;
                }
            }
        }
        if (!$keyExist) {
            echo '-17';
            exit;
        }


        $locationZipPath = CONF_PATH . $rt['fileid'] . '_' . $now . '.zip';
        move_uploaded_file($_FILES['file']['tmp_name'], $locationZipPath);
        //
        if (!class_exists('ZipArchive')) {
            $notSupportZip = 1;
        }
        $cacheUpdateDirName2 = 'caches_upgrade' . date('Ym', time());
        $cacheUpdateDirName = 'caches_upgrade' . date('Ymd', time()) . time();
        if ($notSupportZip) {
            $archive = new PclZip($locationZipPath);
            if ($archive->extract(PCLZIP_OPT_PATH, CONF_PATH . $cacheUpdateDirName, PCLZIP_OPT_REPLACE_NEWER) == 0) {
                echo '-14'; //解压失败
                exit;
            }
        } else {
            $zip = new ZipArchive();
            $rs = $zip->open($locationZipPath);
            if ($rs !== TRUE) {
                echo '-15'; //解压失败
                exit;
            }
        }
        //

        if (!file_exists(CONF_PATH . $cacheUpdateDirName)) {
            @mkdir(CONF_PATH . $cacheUpdateDirName, 0777);
        }
        //
        if (!$notSupportZip) {
            $zip->extractTo(CONF_PATH . $cacheUpdateDirName);
            $zip->close();
        }

        recurse_copy_files(CONF_PATH . $cacheUpdateDirName, $_SERVER['DOCUMENT_ROOT']);

        //delete
        deletedirectory(CONF_PATH . $cacheUpdateDirName);
        @unlink($locationZipPath);
        echo 1;
    }
*/
    //@lindu wu 20160509
    public function ajax_wxcheck() {
        if (!C('IS_MEIHUA')) {
            echo 'error';
            exit;
        }
        $key = $this->_get('key');
        $user_mod = M('Users');
        $utemp_mod = M('User_tmp_access');
        $cktem = $utemp_mod->where("keyvalue='$key'")->find();
        if (!empty($cktem)) {
            if (empty($cktem['openid'])) {
                $redata['status'] = '10000';
                $redata['msg'] = "等待扫码";
            } else {
                $res = $user_mod->where(array('openid' => $cktem['openid']))->find();
                session('uid', $res['id']);
                session('gid', $res['gid']);
                session('bindnum', $res['bindnum']);
                session('uname', $res['username']);
                $info = M('user_group')->find($res['gid']);
                session('diynum', $res['diynum']);
                session('connectnum', $res['connectnum']);
                session('activitynum', $res['activitynum']);
                session('viptime', $res['viptime']);
                session('gname', $info['name']);
                $wxinfo = M("Wxuser")->where("uid = $res[id]")->field('id,token')->find();
                $now = time();
                $month = date('m', $now);
                $user_mod->where(array('id' => $res['id']))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间
                $redata['status'] = '0';
                if ($cktem['type'] == 2) {
                    $redata['msg'] = "注册成功!";
                    $redata['btn'] = "reg_status";
                } else {
                    $redata['msg'] = "登录成功!";
                    $redata['btn'] = "login_status";
                }
                $redata['url'] = U('User/Game/gameLibrary', array('id' => $wxinfo['id'], 'token' => $wxinfo['token']));
            }
        } else {
            $redata['status'] = "10001";
            $redata['msg'] = "扫描出错,请重试!";
        }
        $this->ajaxReturn($redata);
    }

    public function mhcheckreg() {
        if (!C('IS_MEIHUA')) {
            echo 'error';
            exit;
        }
        C("TOKEN_ON", false);
        $db = D('Users');
        if (C('server_topdomain') != 'vifnn.cn') {
            $usercount = $db->where(array('lastip' => htmlspecialchars(trim(get_client_ip()))))->count();
            if ($usercount > 50) {
                $this->error('注册太频繁', U('Index/login'));
            }
        }
        $vailpwd = mb_strlen($this->_post('password', 'trim'), 'utf8');
        if ($vailpwd < 4 || $vailpwd > 20) {
            $this->error('密码格式不正确', U('Index/mhlogin'));
        }
        if (!preg_match('/^1[0-9]{10}$/', trim($_POST['mp']))) {
            $this->error('手机号填写不正确', U('Index/mhlogin'));
        }
        $info = M('User_group')->find(1);
        $verifycode = $this->_post('verifycode', 'md5', 0);
        if (isset($_POST['verifycode'])) {
            if ($verifycode != $_SESSION['verify']) {
                $this->error('验证码错误', U('Index/mhlogin'));
            }
        }
        if (isset($_POST['mp'])) {
            if (!preg_match('/^1[0-9]{10}$/', trim($_POST['mp']))) {
                $this->error('手机号填写不正确', U('Index/mhlogin'));
            }
        }
        if (C('reg_mp_verify') == 1) {
            if (session('reg_mp') != md5($this->_post('mp'))) {
                $this->error('请输入刚接收验证码的手机号');
            }
        }
        $mp = $this->_post('mp');
        $countMP = M('Users')->where(array('mp' => $mp))->count();
        if ($countMP > 0)
            $this->error('手机号已被注册');

        if ($this->isAgent) {
            $_POST['agentid'] = $this->thisAgent['id'];
        }
        if (isset($_POST['invitecode'])) {
            //$_POST['invitecode']=$this->_get('invitecode');
            $inviteCode = $this->_post('invitecode');
            if ($inviteCode && !ctype_alpha($inviteCode)) {
                exit('invitecode colud not include other letter');
            }
            $inviter = $db->where(array('invitecode' => $inviteCode))->find();
            $_POST['inviter'] = intval($inviter['id']);
        } else {
            $_POST['inviter'] = 0;
        }
        $_POST['invitecode'] = $this->randStr(6);
        $_POST['usertplid'] = 2;
        $_POST['username'] = $this->_post('mp');
        if ($db->create()) {
            $id = $db->add();
            if ($id) {
                if (!$this->init_wxuser($mp, $id)) {
                    $this->error('注册失败', U('Index/mhlogin'));
                    exit;
                };
                $wxinfo = M("Wxuser")->where("uid = $id")->field('id,token')->find();
                $now = time();
                $db->where(array('id' => $id))->save(array('lasttime' => $now, 'lastloginmonth' => date('m', $now), 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间
                //
                Sms::sendSms('admin', '有新用户注册了', $this->adminMp);
                if ($this->isAgent) {
                    $usercount = M('Users')->where(array('agentid' => $this->thisAgent['id']))->count();
                    M('Agent')->where(array('id' => $this->thisAgent['id']))->save(array('usercount' => $usercount));
                }
                if (!(int) $this->reg_validDays) {
                    $viptime = time() + 90 * 24 * 3600;
                } else {
                    $viptime = time() + intval($this->reg_validDays) * 24 * 3600;
                }
                $gid = $this->minGroupid;
                if ($this->reg_groupid) {
                    $gid = intval($this->reg_groupid);
                }
                $db->where(array('id' => $id))->save(array('viptime' => $viptime, 'status' => 1, 'gid' => $gid));
                session('uid', $id);
                session('gid', $gid);
                session('uname', $_POST['username']);
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
                session('gname', $info['name']);
                // $smtpserver = C('email_server');
                // $port = C('email_port');
                // $smtpuser = C('email_user');
                // $smtppwd = C('email_pwd');
                // $mailtype = "TXT";
                // $sender = C('email_user');
                // $smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender);
                // $to = $list['email'];
                // $subject = C('reg_email_title');
                // $code = C('site_url').U('User/Index/checkFetchPass?uid='.$list['id'].'&code='.md5($list['id'].$list['password'].$list['email']));
                // $fetchcontent = C('reg_email_content');
                // $fetchcontent = str_replace('{username}',$where['username'],$fetchcontent);
                // $fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
                // $fetchcontent = str_replace('{code}',$code,$fetchcontent);
                // $body=$fetchcontent;
                //$body = iconv('UTF-8','gb2312',$fetchcontent);
                // $send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);

                $this->success('注册成功', U('User/Game/gameLibrary', array('id' => $wxinfo['id'], 'token' => $wxinfo['token'])));
            } else {
                $this->error('注册失败', U('Index/mhlogin'));
            }
        } else {
            $this->error($db->getError(), U('Index/mhlogin'));
        }
    }

    public function mhresetpwd() {
        if (!C('IS_MEIHUA')) {
            echo 'error';
            exit;
        }
        C("TOKEN_ON", false);
        $Us_mod = M('Users');
        $ckmap['mp'] = $this->_post('mp');
        $resck = $Us_mod->where($ckmap)->find();
        if (!empty($resck)) {
            $rules = array(
                array('verify', 'require', '请填写您收到的验证码！'),
                array('mp', 'require', '请填写手机号！'),
                array('password', '4,20', '密码格式不正确', 1, 'length'),
                array('password', 'require', '请设置新密码'),
            );
            $mp = $this->_post('mp', 'md5');
            $verify = $this->_post('verifycode', 'md5');
            if ($Us_mod->validate($rules)->create()) {
                $_POST['password'] = $this->_post('password', 'md5');
                if ($mp != $_SESSION['reg_mp']) {
                    $this->error("请填写正确的手机号", U('Index/mhgetpass'));
                }
                if ($verify != $_SESSION['verify']) {
                    $this->error("验证码不正确", U('Index/mhgetpass'));
                }
                $Us_mod->where("mp = $this->_post('mp')")->save();
                $this->success('修改成功，请登录！', U('Index/mhlogin'));
            } else {
                $this->error($Us_mod->getError(), U('Index/mhgetpass'));
            }
        } else {
            $this->error("您的用户名不存在!", U('Index/mhgetpass'));
        }
    }

    public function mhchecklogin() {
        if (!C('IS_MEIHUA')) {
            echo 'error';
            exit;
        }
        C("TOKEN_ON", false);
        $verifycode = $this->_post('verifycode2', 'md5', 0);
        if (isset($_POST['verifycode2'])) {
            if ($verifycode != $_SESSION['loginverify']) {
                $this->error('验证码错误', U('Index/mhlogin'));
            }
        }
        $db = D('Users');
        $mp = $this->_post('mp');
        $map['mp|username'] - $mp;
        $viptime = $db->where(array('mp' => $mp))->getfield('viptime');
        $where['mp'] = $this->_post('mp', 'trim');

        // if($db->create()==false)
        // $this->error($db->getError());
        $pwd = $this->_post('password', 'trim,md5');
        $res = $db->where($where)->find();
        if ($res && ($pwd === $res['password'])) {
            $wxinfo = M("Wxuser")->where("uid = $res[id]")->field('id,token')->find();
            session('uid', $res['id']);
            session('gid', $res['gid']);
            session('uname', $res['username']);
            session('bindnum', $res['bindnum']);
            $info = M('user_group')->find($res['gid']);
            session('diynum', $res['diynum']);
            session('connectnum', $res['connectnum']);
            session('activitynum', $res['activitynum']);
            session('viptime', $res['viptime']);
            session('gname', $info['name']);
            //每个月第一次登陆数据清零
            $now = time();
            $month = date('m', $now);
            if ($month != $res['lastloginmonth'] && $res['lastloginmonth'] != 0) {
                $data['id'] = $res['id'];
                $data['imgcount'] = 0;
                $data['diynum'] = 0;
                $data['textcount'] = 0;
                $data['musiccount'] = 0;
                $data['connectnum'] = 0;
                $data['activitynum'] = 0;
                $db->save($data);
                //
                session('diynum', 0);
                session('connectnum', 0);
                session('activitynum', 0);
            }
            //登陆成功，记录本月的值到数据库
            //
            session('role_name', null);
            session('staff_id', null);
            session('first_func', null);
            $_SESSION['role_name'] = '';
            $_SESSION['staff_id'] = '';
            $_SESSION['first_func'] = '';
            unset($_SESSION['role_name']);
            unset($_SESSION['staff_id']);
            unset($_SESSION['first_func']);
            $db->where(array('id' => $res['id']))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => htmlspecialchars(trim(get_client_ip())))); //最后登录时间
            $this->success('登录成功', U('User/Game/gameLibrary', array('id' => $wxinfo['id'], 'token' => $wxinfo['token'])));
        } else {
            $this->error('帐号密码错误', U('Index/mhlogin'));
        }
    }

    //自动模拟生成微信公众帐号绑定
    /**
     * @param $comparm
     * @return bool
     */
    private function init_wxuser($comparm, $uid) {
        $itdata = array(
            'appid' => $comparm,
            'appsecret' => $comparm,
            'wxname' => $comparm,
            'wxid' => $comparm,
            'weixin' => $comparm,
            'winxintype' => 1,
            'uid' => $uid,
            'createtime' => time(),
            'token' => $this->get_token(),
            'qr' => C('site_twm'),
            'headerpic' => './tpl/User/default/common/images/portrait.jpg',
        );
        $db = D('Wxuser');
        if ($this->isAgent) {
            $itdata['agentid'] = $this->thisAgent['id'];
        }
        $randLength = 43;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($chars);
        $randStr = '';
        for ($i = 0; $i < $randLength; $i++) {
            $randStr .= $chars[rand(0, $len - 1)];
        }
        $itdata['aeskey'] = $randStr;
        $itdata['encode'] = 0;
        //
        $pigSecret = $this->get_token(20, 0, 1);
        $itdata['pigsecret'] = $pigSecret;
        $id = $db->add($itdata);
        if ($id) {
            M('Users')->field('wechat_card_num')->where(array('id' => session('uid')))->setInc('wechat_card_num');
            $this->addfc($uid, $itdata['token'], 1);
            M('Diymen_set')->add(array('appid' => trim($itdata['appid']), 'token' => $itdata['token'], 'appsecret' => trim($itdata['appsecret'])));
            if ($itdata['appid']) {
                M('Wxuser')->where(array('appid' => $itdata['appid'], 'id' => array('neq', $id)))->save(array('appid' => $itdata['appid'] . '_no'));
            }
            return ture;
        } else {
            $map['mp|openid'] = $comparm;
            M("Users")->where($map)->delete();
            return false;
        }
    }

    /**
     * @param int $randLength
     * @param int $attatime
     * @param int $includenumber
     * @return string
     */
    public function get_token($randLength = 6, $attatime = 1, $includenumber = 0) {
        if ($includenumber) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
        } else {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        }
        $len = strlen($chars);
        $randStr = '';
        for ($i = 0; $i < $randLength; $i++) {
            $randStr.=$chars[rand(0, $len - 1)];
        }
        $tokenvalue = $randStr;
        if ($attatime) {
            $tokenvalue = $randStr . time();
        }
        return $tokenvalue;
    }

    public function addfc($token, $uid, $gid) {
        $token_open = M('Token_open');
        $open['uid'] = $uid;
        $open['token'] = $token;
        if (C('agent_version') && $this->agentid) {
            $fun = M('User_group')->field('func')->where('`id` = ' . $gid . ' AND agentid=' . $this->agentid)->select();
        } else {
            $fun = M('User_group')->field('func')->where('`id` = ' . $gid)->select();
        }
        foreach ($fun as $key => $vo) {
            $queryname.=$vo['func'] . ',';
        }
        $open['queryname'] = rtrim($queryname, ',');
        $token_open->data($open)->add();
    }

    public function mhsendMsg() {
        if (IS_POST) {
            $auth = "梅花互动";
            if (strlen($this->_post('mp')) != 11) {
                exit('Error Phone Number!');
            }
            for ($i = 0; $i < 6; $i++) {
                $code .= rand(0, 9);
            }
            session('verify', md5($code));
            session('reg_mp', md5($this->_post('mp')));
            echo Sms::sendSms('admin', "您的验证码是：【" . $code . "】。请不要把验证码泄露给其他人。如非本人操作，可不用理会！【" . $auth . "】", $this->_post('mp'));
        } else {
            exit("Error!");
        }
    }

}
/*
function deletedirectory($dirname) {
    $result = false;
    if (!is_dir($dirname)) {
        echo " $dirname is not a dir!";
        exit(0);
    }
    $handle = opendir($dirname); //打开目录
    while (($file = readdir($handle)) !== false) {
        if ($file != '.' && $file != '..') { //排除"."和"."
            $dir = $dirname . DIRECTORY_SEPARATOR . $file;
            //$dir是目录时递归调用deletedir,是文件则直接删除
            is_dir($dir) ? deletedirectory($dir) : unlink($dir);
        }
    }
    closedir($handle);
    $result = rmdir($dirname) ? true : false;
    return $result;
}

function recurse_copy_files($src, $dst) {  // 原目录，复制到的目录
    $now = time();
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy_files($src . '/' . $file, $dst . '/' . $file);
            } else {

                if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                    if (!is_writeable($dst . DIRECTORY_SEPARATOR . $file)) {
                        exit($dst . DIRECTORY_SEPARATOR . $file . '不可写');
                    }
                    @unlink($dst . DIRECTORY_SEPARATOR . $file);
                }
                if (file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                    @unlink($dst . DIRECTORY_SEPARATOR . $file);
                }
                $copyrt = copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);

                if (!$copyrt) {
                    echo 'copy ' . $dst . DIRECTORY_SEPARATOR . $file . ' failed<br>';
                }
            }
        }
    }
    closedir($dir);
}
*/
?>