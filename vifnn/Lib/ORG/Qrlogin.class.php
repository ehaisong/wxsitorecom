<?php


class Qrlogin extends BaseScene
{
	public function wxautobus()
	{
		$user_openid = $this->wecha_id;
		$db = D('Users');
		$atmp_mod = M('User_tmp_access');
		$ckusers = $db->where(array('openid' => $user_openid))->find();
		if (!empty($ckusers)) {
			$atmp_mod->where('keyvalue = ' . $this->data['keyvalue'])->save(array('openid' => $user_openid, 'utime' => time(), 'type' => 1));
			$msg = '登录成功!';
		} else {
			$ranuid = 'u_' . time();
			$newdata['openid'] = $user_openid;
			$newdata['inviter'] = 0;
			$newdata['usertplid'] = 2;
			$newdata['username'] = $ranuid;
			$newdata['password'] = md5($user_openid) . rand(1000, 9999);
			$newdata['status'] = 1;
			$newdata['gid'] = 1;
			$newdata['form'] = 1;
			$newdata['usertplid'] = 2;
			$newdata['bindnum'] = 0;
			$validDays = intval(C('reg_validdays'));
			if ($validDays) {
				$newdata['viptime'] = time() + 90 * 24 * 3600;
			} else {
				$newdata['viptime'] = time() + $validDays * 24 * 3600;
			}
			$resid = $db->add($newdata);
			if ($resid) {
				$now = time();
				$month = date('m', $now);
				$db->where(array('id' => $resid))->save(array('lasttime' => $now, 'lastloginmonth' => $month, 'lastip' => htmlspecialchars(trim(get_client_ip()))));
				$this->init_wxuser($user_openid, $resid, $ranuid);
				$atmp_mod->where('keyvalue = ' . $this->data['keyvalue'])->save(array('openid' => $user_openid, 'utime' => time(), 'type' => 2));
			}
			$msg = '注册成功!';
		}
		return array('恭喜您扫码' . $msg, 'text');
	}
	private function init_wxuser($comparm, $uid, $ranuid)
	{
		$itdata = array('appid' => $comparm, 'appsecret' => $comparm, 'wxname' => $ranuid, 'wxid' => $comparm, 'weixin' => $ranuid, 'winxintype' => 1, 'uid' => $uid, 'createtime' => time(), 'token' => $this->get_token(), 'qr' => C('site_twm'), 'headerpic' => './tpl/User/default/common/images/portrait.jpg');
		$db = D('Wxuser');
		$randLength = 43;
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$len = strlen($chars);
		$randStr = '';
		for ($i = 0; $i < $randLength; $i++) {
			$randStr .= $chars[rand(0, $len - 1)];
		}
		$itdata['aeskey'] = $randStr;
		$itdata['encode'] = 0;
		$pigSecret = $this->get_token(20, 0, 1);
		$itdata['pigsecret'] = $pigSecret;
		$id = $db->add($itdata);
		if ($id) {
			M('Users')->field('wechat_card_num')->where(array('id' => $uid))->setInc('wechat_card_num');
			$this->addfc($uid, $itdata['token'], 1);
			M('Diymen_set')->add(array('appid' => trim($itdata['appid']), 'token' => $itdata['token'], 'appsecret' => trim($itdata['appsecret'])));
			if ($itdata['appid']) {
				M('Wxuser')->where(array('appid' => $itdata['appid'], 'id' => array('neq', $id)))->save(array('appid' => $itdata['appid'] . '_no'));
			}
			return ture;
		} else {
			$map['mp|openid'] = $comparm;
			M('Users')->where($map)->delete();
			return false;
		}
	}
	public function get_token($randLength = 6, $attatime = 1, $includenumber = 0)
	{
		if ($includenumber) {
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
		} else {
			$chars = 'abcdefghijklmnopqrstuvwxyz';
		}
		$len = strlen($chars);
		$randStr = '';
		for ($i = 0; $i < $randLength; $i++) {
			$randStr .= $chars[rand(0, $len - 1)];
		}
		$tokenvalue = $randStr;
		if ($attatime) {
			$tokenvalue = $randStr . time();
		}
		return $tokenvalue;
	}
	public function addfc($token, $uid, $gid)
	{
		$token_open = M('Token_open');
		$open['uid'] = $uid;
		$open['token'] = $token;
		$fun = M('User_group')->field('func')->where('`id` = ' . $gid)->select();
		foreach ($fun as $key => $vo) {
			$queryname .= $vo['func'] . ',';
		}
		$open['queryname'] = rtrim($queryname, ',');
		$token_open->data($open)->add();
	}
}


?>
