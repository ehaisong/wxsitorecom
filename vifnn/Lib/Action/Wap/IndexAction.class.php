<?php

function strExists($haystack, $needle)
{
	return !(strpos($haystack, $needle) === false);
}

class IndexAction extends WapAction
{
	private $tpl;
	private $info;
	public $wecha_id;
	public $copyright;
	public $company;
	public $weixinUser;
	public $homeInfo;
	private $randstr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	public $R = '';

	public function _initialize()
	{
		parent::_initialize();
		$where['token'] = $this->token;
		$tpl = $this->wxuser;
		$this->weixinUser = $tpl;
		if (isset($_GET['wecha_id']) && $_GET['wecha_id']) {
			$_SESSION['wecha_id'] = $_GET['wecha_id'];
		}

		$allClasses = M('Classify')->where(array('token' => $this->_get('token'), 'status' => 1))->order('sorts desc')->select();
		$allClasses = $this->convertLinks($allClasses);
		$info = array();

		if ($allClasses) {
			$classByID = array();
			$firstGradeCatCount = 0;

			foreach ($allClasses as $c) {
				$classByID[$c['id']] = $c;

				if ($c['fid'] == 0) {
					$c['sub'] = array();
					$info[$c['id']] = $c;
					$firstGradeCatCount++;
				}
			}

			foreach ($allClasses as $c) {
				if ((0 < $c['fid']) && $info[$c['fid']]) {
					array_push($info[$c['fid']]['sub'], $c);
				}
			}

			if ($info) {
				foreach ($info as $c) {
					$info[$c['id']]['key'] = $firstGradeCatCount--;
				}
			}
		}

		$homeInfo = $this->homeInfo;
		$homeInfo['info'] = str_replace(array("\r\n","\"","&quot;"),array(' ','',''),$homeInfo['info']);
		$this->homeInfo['info'] = $homeInfo['info'];
		$this->info = $info;
		$tpl['color_id'] = intval($tpl['color_id']);
		$this->tpl = $tpl;
		if (($_SERVER['HTTP_REFERER'] != '') && (strpos($_SERVER['HTTP_REFERER'], 'memberReg') === false) && (strpos($_SERVER['HTTP_REFERER'], 'memberLogin') === false) && (strpos($_SERVER['HTTP_REFERER'], 'PhoneVerify') === false) && (strpos($_SERVER['HTTP_REFERER'], 'commonVerifyPhone') === false)) {
			session('R', $_SERVER['HTTP_REFERER']);
		}

		$this->R = session('R');
	}

	public function memberLogin()
	{
		if (IS_POST) {
			$username = (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '');
			$password = (isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '');
			$userInfo = M('Userinfo')->where(array('username' => $username, 'token' => $this->token))->find();

			if (empty($userInfo)) {
				$this->error('用户不存在');
			}
			else if ($userInfo['password'] != md5($password)) {
				$this->error('密码不正确');
			}
			else {
				$session_openid_name = 'token_openid_' . $this->token;
				session($session_openid_name, $userInfo['wecha_id']);
				$this->success('登录成功', $this->R);
			}
		}
		else {
			$this->assign('R', $this->R);
			$this->display();
		}
	}

	public function memberReg()
	{
		$f = 0;

		if ($this->wecha_id) {
			$f = 1;
		}

		if (IS_POST) {
			$tele = (isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '');
			$password = (isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '');
			$password2 = (isset($_POST['password2']) ? htmlspecialchars($_POST['password2']) : '');
			$username = (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '');

			if (empty($username)) {
				$this->error('账号不能为空!');
			}

			if (empty($tele)) {
				$this->error('电话号码不能为空!');
			}

			if (empty($password)) {
				$this->error('密码不能为空!');
			}

			if ($password != $password2) {
				$this->error('密码不正确');
			}
			else {
				$_POST['password'] = md5($_POST['password']);
			}

			if (!empty($_POST['paypass'])) {
				$_POST['paypass'] = md5($_POST['paypass']);
			}

			$_POST['token'] = $this->token;
			$userInfo = M('Userinfo')->where(array('username' => $username, 'token' => $this->token))->find();
			$tel = M('Userinfo')->where(array('token' => $this->token, 'tel' => $_POST['tel']))->find();
			$flag = 1;
			if ($userInfo && ($tel['username'] != $username)) {
				$this->error('账号已存在');
			}
			else {
				if ($tel && ($userInfo['tel'] != $tele)) {
					$this->error('手机已被使用，请输入您之前用该手机号注册的账号，系统将为您自动合并。');
				}
				else {
					if ($userInfo && ($tel['username'] == $username)) {
						M('Userinfo')->where(array('username' => $username, 'token' => $this->token, 'tel' => $_POST['tel']))->save($_POST);
						$flag = NULL;
					}
				}
			}

			if ($flag) {
				$uid = M('Userinfo')->add($_POST);
				if ($uid && ($f == 0)) {
					$wecha_id = $this->randstr[rand(0, 51)] . $this->randstr[rand(0, 51)] . $this->randstr[rand(0, 51)] . $uid;
					M('Userinfo')->where(array('id' => $uid, 'token' => $this->token))->save(array('wecha_id' => $wecha_id));
				}
			}

			session('token_openid_' . $this->token, $wecha_id);
			S('fans_' . $this->token . '_' . $this->wecha_id, NULL);
			$this->success('注册成功', $this->R);
		}
		else {
			$custom = M('Member_card_custom')->where(array('token' => $this->token))->find();

			if (!$custom) {
				$customArr = array(
					array('wechaname', 0, '微信名', 'text', ''),
					array('truename', 0, '真实姓名', 'text', ''),
					array('qq', 0, 'QQ', 'text', ''),
					array('paypass', 0, '支付密码', 'password', ''),
					array('portrait', 0, '头像', 'text', ''),
					array('sex', 0, '性别', 'number', ''),
					array('bornyear', 0, '出生年', 'number', ''),
					array('bornmonth', 0, '出生月', 'number', ''),
					array('bornday', 0, '出生日', 'number', '')
					);
			}
			else {
				if ($this->wecha_id) {
					$UserInfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
				}

				foreach ($custom as $key => $value) {
					switch ($key) {
					case 'wechaname':
						$name = '微信名';
						$Uvalue = $UserInfo[$key];
						break;

					case 'truename':
						$name = '真实姓名';
						$Uvalue = $UserInfo[$key];
						break;

					case 'qq':
						$name = 'QQ';
						$Uvalue = $UserInfo[$key];
						break;

					case 'paypass':
						$name = '支付密码';
						$Uvalue = $UserInfo[$key];
						break;

					case 'portrait':
						$name = '头像';
						$Uvalue = $UserInfo[$key];
						break;

					case 'sex':
						$name = '性别';
						$Uvalue = $UserInfo[$key];
						break;

					case 'bornyear':
						$name = '出生年';
						$Uvalue = $UserInfo[$key];
						break;

					case 'bornmonth':
						$name = '出生月';
						$Uvalue = $UserInfo[$key];
						break;

					case 'bornday':
						$name = '出生日';
						$Uvalue = $UserInfo[$key];
						break;
					}

					if (($key == 'id') || ($key == 'token') || ($key == 'tel')) {
					}
					else if ($key == 'paypass') {
						$type = 'password';
						$customArr[] = array($key, $value, $name, $type, '');
					}
					else {
						if (($key == 'sex') || ($key == 'bornmonth') || ($key == 'bornyear') || ($key == 'bornday')) {
							$type = 'number';
							$customArr[] = array($key, $value, $name, $type, $Uvalue);
						}
						else if (!strstr($key, 'is_')) {
							$type = 'text';
							$customArr[] = array($key, $value, $name, $type, $Uvalue);
						}
					}
				}
			}

			$this->assign('UserInfo', $UserInfo);
			$this->assign('custom', $customArr);
			$this->assign('R', $this->R);
			$this->display('memberLogin');
		}
	}

	public function profile()
	{
		if (empty($_POST['tel'])) {
			$this->error('电话号码不能为空!');
		}

		if (empty($_POST['password'])) {
			unset($_POST['password']);
			unset($_POST['password2']);
		}
		else if ($_POST['password'] != $_POST['password2']) {
			$this->error('两次密码不一致');
		}
		else {
			$_POST['password'] = md5($_POST['password']);
		}

		if (!empty($_POST['paypass'])) {
			$_POST['paypass'] = md5($_POST['paypass']);
		}
		else {
			unset($_POST['paypass']);
		}

		$_POST['wecha_id'] = $this->wecha_id;
		$_POST['token'] = $this->token;
		$where = array('token' => $this->token, 'wecha_id' => $this->wecha_id);

		if (M('Userinfo')->where($where)->field('id')->find()) {
			$sql = M('Userinfo')->where($where)->save($_POST);
		}
		else {
			$sql = M('Userinfo')->add($_POST);
		}

		if ($sql) {
			S('fans_' . $this->token . '_' . $this->wecha_id, NULL);
			$this->success('保存成功', $this->R);
		}
		else {
			$this->error('保存失败，请重新尝试', $this->R);
		}
	}

	public function debug()
	{
	}

	public function classify()
	{
		$this->assign('info', $this->info);
		$this->display($this->tpl['tpltypename']);
	}

	public function index()
	{
		if ($this->homeInfo['advancetpl']) {
			echo '<script>window.location.href="/cms/index.php?token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&news_response=1";</script>';
			exit();
		}

		if ($this->wxuser['dynamicTmpls']) {
			echo '<script>window.location.href="?g=Wap&m=Tmpls&a=show&id=' . $this->wxuser['dynamicTmpls'] . '&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&news_response=1";</script>';
			exit();
		}

		$Mywxuser = M('service_wxuser');
		$state = $Mywxuser->where(array('token' => $this->token))->getField('state');
		$this->assign('state', $state);

		if ($this->token == 'yicms') {
			$token = $this->token;
			$where1['token'] = $token;
			$allflash = M('Flash')->where($where1)->order('id DESC')->select();
			$allflash = $this->convertLinks($allflash);
			$flash = array();

			foreach ($allflash as $af) {
				if ($af['url'] == '') {
					$af['url'] = 'javascript:void(0)';
				}

				if ($af['tip'] == 1) {
					$af['img'] = preg_replace('/default\\/\\.\\.\\//i', '', $af['img']);
					array_push($flash, $af);
				}
			}

			$this->assign('flash', $flash);
			$db = D('Classify');
			$dbs = D('Img');
			$list = $db->where(array('token' => $token, 'fid' => 0))->order('sorts DESC')->limit(9)->field('name,id,info,img')->select();

			foreach ($list as $k => $v) {
				$id[] = $v['id'];
			}

			$caseID = $id[4];
			$functionID = $id[0];
			$afterSaleID = $id[3];
			$this->assign('afterSaleID', $afterSaleID);
			$advantageID = $id[5];
			$this->assign('advantageID', $advantageID);
			$newsID = $id[6];
			$this->assign('newsID', $newsID);
			$aboutID = $id[7];
			$this->assign('aboutID', $aboutID);
			$contactID = $id[8];
			$this->assign('contactID', $contactID);
			$industryFunctionID = 523692;
			$subCatsWhere['token'] = $token;
			$subCatsWhere['fid'] = array(
	'in',
	array($caseID, $functionID)
	);
			$subCats = $db->where($subCatsWhere)->order('sorts DESC')->select();
			$functionCats = array();
			$caseCats = array();
			$functionCatIDs = array();

			foreach ($subCats as $sc) {
				if ($sc['fid'] == $functionID) {
					array_push($functionCats, $sc);
					array_push($functionCatIDs, $sc['id']);
				}
				else if ($sc['fid'] == $caseID) {
					array_push($caseCats, $sc);
				}
			}

			$product = $functionCats;
			$product = $this->convertLinks($product);

			if (!empty($functionCatIDs)) {
				$where['classid'] = array('in', $functionCatIDs);
				$where['token'] = $token;
				$function = $dbs->where($where)->field('id,title,classid,text,pic,url')->order('usort DESC')->select();
			}

			$industryInfos = array();
			$function = $this->convertLinks($function);

			if ($function) {
				foreach ($function as $f) {
					if (intval($f['classid']) == intval($industryFunctionID)) {
						array_push($industryInfos, $f);
					}
				}
			}

			$industryInfos = $this->convertLinks($industryInfos);
			$this->assign('industryInfos', $industryInfos);
			$info_id = array($afterSaleID, $caseID, $advantageID, $newsID, $aboutID, $contactID);
			$infoWhere['classid'] = array('in', $info_id);
			$infoWhere['token'] = $token;
			$info = $dbs->where($infoWhere)->field('id,title,classid,text,pic,url')->order('usort DESC')->select();
			$info = $this->convertLinks($info);
			$caseCats = $this->convertLinks($caseCats);
			$case = $caseCats;
			$time = date('Y', time());
			$this->assign('time', $time);
			$class_z = M('Funclass');
			$class_list = $class_z->order('id desc')->field('id,name')->select();
			$this->assign('class_list', $class_list);
			$fun_list_new = M('Funintro')->where(array('is_new' => 1))->order('id desc')->field('id,title,logo,classid,menu_link,is_new,img_havecolor,wgwshow')->select();
			$count = count($fun_list_new);
			$this->assign('fun_list_new', $fun_list_new);
			$this->assign('tpl', $this->tpl);
			$this->assign('id', $id);
			$this->assign('list', $list);
			$this->assign('info_id', $info_id);
			$this->assign('product', $product);
			$this->assign('function', $function);
			$this->assign('case', $case);
			$this->assign('info', $info);
			$this->assign('name', $name);
			$this->assign('currentTab', $this->_get('current'));
			$this->display('099_index');
		}
		else {
			$where['token'] = $this->token;
			$allflash = M('Flash')->where($where)->order('id DESC')->select();
			$allflash = $this->convertLinks($allflash);
			$flash = array();
			$flashbg = array();

			foreach ($allflash as $af) {
				if ($af['url'] == '') {
					$af['url'] = 'javascript:void(0)';
				}

				if ($af['tip'] == 1) {
					$af['img'] = preg_replace('/default\\/\\.\\.\\//i', '', $af['img']);
					array_push($flash, $af);
				}
				else if ($af['tip'] == 2) {
					array_push($flashbg, $af);
				}
			}

			$this->assign('flashbg', $flashbg);
			if (!$flashbg && $this->homeInfo['homeurl']) {
				$flash_db = M('Flash');
				$arr = array();
				$arr['token'] = $this->token;
				$arr['img'] = $this->homeInfo['homeurl'];
				$arr['url'] = '';
				$arr['info'] = '';
				$arr['tip'] = 2;

				if ($arr['img']) {
					$flash_db->add($arr);
				}
			}

			$info = $this->info;
			$tpldata = $this->wxuser;
			$tpldata['color_id'] = intval($tpldata['color_id']);
			include './vifnn/Lib/ORG/index.Tpl.php';

			foreach ($tpl as $k => $v) {
				if ($v['tpltypeid'] == $tpldata['tpltypeid']) {
					$tplinfo = $v;
				}
			}

			$tpldata['tpltypeid'] = $tplinfo['tpltypeid'];
			$tpldata['tpltypename'] = $tplinfo['tpltypename'];

			foreach ($info as $k => $v) {
				if ($info[$k]['url'] == '') {
					$info[$k]['url'] = U('Index/lists', array('classid' => $v['id'], 'token' => $where['token'], 'wecha_id' => $this->wecha_id));
				}

				if ($v['sub'] != NULL) {
					foreach ($v['sub'] as $ke => $va) {
						if ($v['sub'][$ke]['url'] == '') {
							$info[$k]['sub'][$ke]['url'] = U('Index/lists', array('classid' => $v['sub'][$ke]['id'], 'token' => $where['token'], 'wecha_id' => $this->wecha_id));
						}
					}
				}
			}

			if (($tpldata['tpltypename'] == 'ktv_list') || ($tpldata['tpltypename'] == 'yl_list')) {
				foreach ($info as $key => $val) {
					$info[$key]['title'] = $val['name'];
					$info[$key]['pic'] = $val['img'];

					if ($info[$key]['url'] == '') {
						$info[$key]['url'] = U('Index/lists', array('classid' => $val['id'], 'token' => $where['token'], 'wecha_id' => $this->wecha_id));
					}

					$info[$key]['info'] = strip_tags(htmlspecialchars_decode($val['info']));
				}
			}

			$this->assign('home', $this->homeInfo);
			$zd = M('Zhida')->where(array('token' => $this->token))->find();
			$zd['code'] = htmlspecialchars_decode(base64_decode($zd['code']), ENT_QUOTES);
			$this->assign('zd', $zd);
			$count = count($flash);
			$this->assign('tplinfo', $tplinfo);
			$this->assign('flash', $flash);
			$this->assign('homeInfo', $this->homeInfo);
			$this->assign('info', $info);
			$this->assign('num', $count);
			$this->assign('flashbgcount', count($flashbg));
			$this->assign('tpl', $this->tpl);
			$this->assign('copyright', $this->copyright);
			$this->display($this->tpl['tpltypename']);
		}
	}

	public function lists()
	{
		$lvs = $this->_get('lvs', 'intval');
		$token = $this->token;
		$classid = $this->_get('classid', 'intval');
		$where['token'] = $this->_get('token', 'trim');
		$classify = M('classify');
		$this->assign('homes', $this->homeInfo['gzhurl']);
		$classid = (0 < $classid ? $classid : 0);
		$info = $classify->where('id = ' . $classid . ' AND token = \'' . $token . '\'')->find();
		$sub = $classify->where('fid = ' . $classid . ' AND token = \'' . $token . '\' AND status = 1')->order('sorts desc')->select();
		$sub = $this->convertLinks($sub);
		$tpldata = D('Wxuser')->where($where)->find();
		$tpldata['color_id'] = intval($tpldata['color_id']);
		include './vifnn/Lib/ORG/index.Tpl.php';

		foreach ($tpl as $k => $v) {
			if ($v['tpltypeid'] == $info['tpid']) {
				$tplinfo = $v;
			}
		}

		$tpldata['tpltypeid'] = $tplinfo['tpltypeid'];
		$tpldata['tpltypename'] = $tplinfo['tpltypename'];
		$imgdata = M('Img')->field('id')->where('classid = ' . $classid)->find();
		$allflash = M('Flash')->where($where)->order('id DESC')->select();
		$allflash = $this->convertLinks($allflash);
		$flash = array();
		$flashbg = array();
		$flashs = array();
		$flashsbg = array();
		if (!empty($sub) && (empty($imgdata) || !empty($lvs))) {
			foreach ($allflash as $af) {
				if ($af['url'] == '') {
					$af['url'] = 'javascript:void(0)';
				}

				if ($af['tip'] == 1) {
					array_push($flashs, $af);
				}

				if ($af['tip'] == 2) {
					array_push($flashsbg, $af);
				}

				if (($af['tip'] == 3) && ($af['did'] == $classid)) {
					$af['img'] = preg_replace('/default\\/\\.\\.\\//i', '', $af['img']);
					array_push($flash, $af);
				}
				else {
					if (($af['tip'] == 4) && ($af['did'] == $classid)) {
						array_push($flashbg, $af);
					}
				}
			}

			if (empty($flash)) {
				$flash = $flashs;
			}

			if (empty($flashbg)) {
				$flashbg = $flashsbg;
			}

			$this->assign('flashbg', $flashbg);
			if (!$flashbg && $this->homeInfo['homeurl']) {
				$flash_db = M('Flash');
				$arr = array();
				$arr['token'] = $this->token;
				$arr['img'] = $this->homeInfo['homeurl'];
				$arr['url'] = '';
				$arr['info'] = '';
				$arr['tip'] = 2;

				if ($arr['img']) {
					$flash_db->add($arr);
				}
			}

			if (($tpldata['tpltypename'] == 'ktv_list') || ($tpldata['tpltypename'] == 'yl_list')) {
				foreach ($sub as $key => $val) {
					$sub[$key]['title'] = $val['name'];
					$sub[$key]['pic'] = $val['img'];

					if ($sub[$key]['url'] == '') {
						$sub[$key]['url'] = U('Index/lists', array('classid' => $val['id'], 'token' => $where['token'], 'wecha_id' => $this->wecha_id));
					}

					$sub[$key]['info'] = strip_tags(htmlspecialchars_decode($val['info']));
				}
			}

			$j = count($sub);

			foreach ($sub as $ke => $va) {
				$subpid = $va['id'];
				$sub[$ke]['sub'] = M('Classify')->where('fid = ' . $subpid)->order('sorts DESC')->select();
				$sub[$ke]['sub'] = $this->convertLinks($sub[$ke]['sub']);

				if ($sub[$ke]['url'] == '') {
					$sub[$ke]['url'] = U('Index/lists', array('classid' => $va['id'], 'token' => $where['token'], 'wecha_id' => $this->wecha_id));
					$sub[$ke]['sub'] = $this->convertLinks($sub[$ke]['sub']);
				}

				$sub[$ke]['key'] = $j--;
			}

			$this->assign('info', $sub);
			$this->assign('pageid', 1);
		}
		else {
			$db = D('Img');
			$where['classid'] = $classid;
			include './vifnn/Lib/ORG/Page.class.php';
			$count = $db->where($where)->count();
			$Page = new Page($count, 20);
			$Page->setConfig('prev', '<');
			$Page->setConfig('next', '>');
			$Page->setConfig('theme', '%nowPage%/%totalPage% 页 %upPage% %linkPage% %downPage%');
			if (($tpldata['tpltypename'] != '1359_index_xcve') && ($tpldata['tpltypename'] != '1360_index_xcve') && ($tpldata['tpltypename'] != '1361_index_xcve')) {
				$res = $db->where($where)->order('usort DESC')->select();
			}
			else {
				$res = $db->where($where)->order('usort DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
				$show = $Page->show();
				$this->assign('page', $show);
			}

			$res = $this->convertLinks($res);

			foreach ($res as $key => $val) {
				$res[$key]['name'] = $val['title'];
				$res[$key]['img'] = $val['pic'];

				if ($res[$key]['url'] == '') {
					$res[$key]['url'] = U('Index/content', array('id' => $val['id'], 'classid' => $val['classid'], 'token' => $where['token'], 'wecha_id' => $this->wecha_id));
				}

				$res[$key]['info'] = strip_tags(htmlspecialchars_decode($val['text']));
			}

			if (($tpldata['tpltypename'] != '1359_index_xcve') && ($tpldata['tpltypename'] != '1360_index_xcve') && ($tpldata['tpltypename'] != '1361_index_xcve')) {
				$listNum = count($res);

				if ($listNum == 1) {
					$contid = $res[0]['id'];
					$cid = $res[0]['classid'];
					$this->content($contid, $cid);
					exit();
				}
			}

			foreach ($allflash as $af) {
				if ($af['url'] == '') {
					$af['url'] = 'javascript:void(0)';
				}

				if ($af['tip'] == 1) {
					array_push($flashs, $af);
				}

				if ($af['tip'] == 2) {
					array_push($flashsbg, $af);
				}

				if (($af['tip'] == 3) && ($af['did'] == $classid)) {
					array_push($flash, $af);
				}
				else {
					if (($af['tip'] == 4) && ($af['did'] == $classid)) {
						array_push($flashbg, $af);
					}
				}
			}

			if (empty($flash)) {
				$flash = $flashs;
			}

			if (empty($flashbg)) {
				$flashbg = $flashsbg;
			}

			$this->assign('flashbg', $flashbg);
			if (!$flashbg && $this->homeInfo['homeurl']) {
				$flash_db = M('Flash');
				$arr = array();
				$arr['token'] = $this->token;
				$arr['img'] = $this->homeInfo['homeurl'];
				$arr['url'] = '';
				$arr['info'] = '';
				$arr['tip'] = 2;

				if ($arr['img']) {
					$flash_db->add($arr);
				}
			}

			$this->assign('info', $res);
			$this->assign('pageid', 0);
		}

		$iswx = $this->isWechat;
		$this->assign('iswx', $iswx);
		$count = count($flash);
		$this->assign('flash', $flash);
		$this->assign('num', $count);
		$this->assign('flashbgcount', count($flashbg));
		$this->assign('tpl', $tpldata);
		$this->assign('copyright', $this->copyright);
		$this->assign('thisClassInfo', $info);
		$this->display($tpldata['tpltypename']);
	}

	public function content($contid = '', $cid = '')
	{
		$token = $this->token;
		$class = M('Classify');
		$img = M('Img');
		$wecha_id = $this->wecha_id;
		if (($contid == '') && ($cid == '')) {
			$id = $this->_get('id', 'intval');
			$classid = $this->_get('classid', 'intval');
			$id = intval($id);
			$classid = intval($classid);
		}
		else {
			$id = intval($contid);
			$classid = intval($cid);
		}

		$this->assign("imgid", $id);
		$this->assign('homes', $this->homeInfo['gzhurl']);
		$res = $img->where('id = ' . intval($id) . ' AND token = \'' . $token . '\'')->find();
		$cardid = M("member_card_create")->where(array("token" => $token, "wecha_id" => $wecha_id))->getField("cardid");
		$mygradeid = M("member_card_set")->where(array("id" => $cardid))->getField("gradeid");
		if (($mygradeid < $res["read_grade"]) && ($res["read_grade"] != 0)) {
			$this->error("您的会员级别不够");
		}

		if ($classid == '') {
			$classid = $res['classid'];
		}

		$img->where('token = \'' . $token . '\' AND id = ' . intval($id))->setInc('click');
		$classinfo = $class->where('id = ' . intval($classid) . ' AND token = \'' . $token . '\'')->field('conttpid')->find();
		$tplinfo = D('Wxuser')->where('token = \'' . $token . '\'')->find();
		include './vifnn/Lib/ORG/cont.Tpl.php';

		foreach ($contTpl as $k => $v) {
			if ($v['tpltypeid'] == $classinfo['conttpid']) {
				$tpldata = $v;
			}
		}

		$tplinfo['tpltypeid'] = $tpldata['tpltypeid'];
		$tplinfo['tpltypename'] = $tpldata['tpltypename'];
		$lists = $img->where('classid = ' . intval($classid) . ' AND token = \'' . $token . '\' AND id != ' . intval($id))->limit(5)->order('uptatetime')->select();
		$lists = $this->convertLinks($lists);
		$this->assign('info', $this->info);
		$this->assign('copyright', $this->copyright);

		if (!$res['is_focus']) {
			$res['info'] = '<style>.is_hidden{display:none}</style>' . $res['info'];
		}

		if (($res['dashang'] == 1) && ($res['dashang_type'] == 2)) {
			$mydashang = M('img_dashang_order')->where(array('token' => $this->token, 'imgid' => $id, 'paid' => 1, 'wecha_id' => $this->wecha_id))->find();

			if ($mydashang) {
				$res['info'] = str_replace('//dashang//', '', $res['info']);
			}
			else {
				$info_array = explode('//dashang//', $res['info']);
				$res['info'] = $info_array[0] . '<span style=\'font-size:13px;color:#888\'>……更多精彩，打赏一下就能全部呈上哦！</span>';
			}
		}

		$iswx = $this->isWechat;
		$this->assign('iswx', $iswx);
		$this->assign('res', $res);
		$this->assign('lists', $lists);
		$this->assign('tpl', $tplinfo);
		$this->assign('wxuser', $this->wxuser);
		$dashang_wecha_id = M('img_dashang_order')->where(array('token' => $this->token, 'imgid' => $id, 'paid' => 1))->Distinct(true)->field('wecha_id')->select();
		$dashang_count = count($dashang_wecha_id);
		$this->assign('dashang_count', $dashang_count);

		if ($res['dashang_rank_type'] == 1) {
			$noworder = 'addtime desc';
		}
		else if ($res['dashang_rank_type'] == 2) {
			$noworder = 'sum desc';
		}

		$dashang_list = M('img_dashang_order')->where(array('token' => $this->token, 'imgid' => $id, 'paid' => 1))->Distinct(true)->field('wecha_id')->order($noworder)->limit($res['dashang_rank_num'] ? $res['dashang_rank_num'] : 5)->select();

		foreach ($dashang_list as $dk => $dv) {
			$dashang_list[$dk]['userinfo'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $dv['wecha_id']))->find();
			$dashang_list[$dk]['price'] = M('img_dashang_order')->where(array('token' => $this->token, 'imgid' => $id, 'paid' => 1, 'wecha_id' => $dv['wecha_id']))->sum('price_s') / 100;
			$dashang_list[$dk]['addtime'] = M('img_dashang_order')->where(array('token' => $this->token, 'imgid' => $id, 'paid' => 1, 'wecha_id' => $dv['wecha_id']))->order('addtime desc')->limit(1)->getField('addtime');
		}

		$this->assign('dashang_list', $dashang_list);
		$this->assign('wecha_id', $wecha_id);

		if ($res['iscomment']) {
			$imgcmt_mod = M('Img_comment');
			$imgspt_mod = M('Img_comment_record');
			$comment_list = $imgcmt_mod->where(array('token' => $this->token, 'imgid' => $id, 'status' => 1))->Distinct(true)->order('id DESC')->limit(15)->select();

			foreach ($comment_list as $ck => $cv) {
				$comment_list[$ck]['wstatus'] = $imgspt_mod->where(array('wecha_id' => $this->wecha_id, 'imgcmt_id' => $cv['id']))->count();
			}

			$this->assign('comment_list', $comment_list);
		}

		$this->display($tplinfo['tpltypename']);
	}

	public function dashang()
	{
		$add['token'] = $this->token;
		$add['wecha_id'] = $this->wecha_id;
		$add['price'] = $_GET['price'];
		$add['price_s'] = $_GET['price'] * 100;
		$add['imgid'] = intval($_GET['imgid']);
		$add['addtime'] = time();
		if ($add["imgid"] == "") {
			$this->error("未获取到需要打赏的图文");
		}
		$id = M('img_dashang_order')->add($add);
		$save['orderid'] = $id . 'DASHANG' . time();
		M('img_dashang_order')->where(array('token' => $this->token, 'id' => $id))->save($save);
		$mysum = M('img_dashang_order')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'imgid' => intval($_GET['imgid'])))->sum('price_s');
		M('img_dashang_order')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'imgid' => intval($_GET['imgid'])))->save(array('sum' => $mysum));
		$this->redirect('Alipay/pay', array('token' => $this->token, 'price' => $_GET['price'], 'wecha_id' => $this->wecha_id, 'from' => 'Index', 'orderid' => $save['orderid'], 'single_orderid' => $save['orderid'], 'notOffline' => 1));
	}

	public function payReturn()
	{
		$dashang = M('img_dashang_order')->where(array('token' => $this->token, 'orderid' => $_GET['orderid']))->find();

		if ($dashang['paid'] == 1) {
			$this->success('支付成功', U('Wap/Index/content', array('token' => $this->token, 'id' => $dashang['imgid'])));
		}
		else {
			$this->error('支付失败');
		}
	}

	public function content_comment_add()
	{
		$imgcmt_mod = M('Img_comment');
		if (($this->wecha_id == '0') || empty($this->wecha_id)) {
			$this->error('您不是该公众号的粉丝,无法进行相关操作!');
		}

		if (IS_POST) {
			$winfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->field('portrait,wechaname')->find();
			$data = array('content' => $_POST['content'], 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'ctime' => time(), 'imgid' => $this->_post('imgid', 'intval'), 'wname' => $winfo['wechaname'], 'wheaderimg' => $winfo['portrait']);
			$result = $imgcmt_mod->data($data)->add();

			if ($result) {
				$res['status'] = 1;
				$res['msg'] = '评论成功!';
			}
			else {
				$res['status'] = -1;
				$res['msg'] = '评论失败!';
			}

			$this->ajaxReturn($res);
		}
		else {
			$map['token'] = $this->token;
			$map['wecha_id'] = $this->wecha_id;
			$map['imgid'] = intval($_GET['imgid']);
			$imgtitle = M('Img')->where(array('id' => $map['imgid']))->getField('title');
			$list = $imgcmt_mod->where($map)->limit(20)->order('id DESC')->select();
			$this->assign('list', $list);
			$this->assign('imgtitle', $imgtitle);
			$this->display();
		}
	}

	public function content_comment_delete()
	{
		$imgcmt_mod = M('Img_comment');

		if (IS_POST) {
			$map = array('wecha_id' => $this->wecha_id, 'id' => $_POST['cmtid']);
			$result = $imgcmt_mod->where($map)->delete();

			if ($result) {
				$res['status'] = 1;
				$res['msg'] = '删除成功!';
			}
			else {
				$res['status'] = -1;
				$res['msg'] = '删除失败!';
			}

			$this->ajaxReturn($res);
		}
	}

	public function content_comment_support()
	{
		$imgcmt_mod = M('Img_comment');

		if (IS_POST) {
			$map = array('id' => $this->_post('id', 'intval'));
			$imgspt_mod = M('Img_comment_record');
			$cksupport = $imgspt_mod->where(array('wecha_id' => $this->wecha_id, 'imgcmt_id' => $this->_post('id', 'intval')))->count();

			if ($cksupport == 1) {
				$result = $imgcmt_mod->where($map)->setDec('support');
				$res['type'] = 0;
				$imgspt_mod->where(array('wecha_id' => $this->wecha_id, 'imgcmt_id' => $this->_post('id', 'intval')))->delete();
			}
			else {
				$result = $imgcmt_mod->where($map)->setInc('support');
				$res['type'] = 1;
				$imgspt_mod->add(array('wecha_id' => $this->wecha_id, 'imgcmt_id' => $this->_post('id', 'intval')));
			}

			$res['count'] = $imgcmt_mod->where($map)->getField('support');

			if ($result) {
				$res['status'] = 1;
				$res['msg'] = '操作成功!';
			}
			else {
				$res['status'] = -1;
				$res['msg'] = '操作失败!';
			}

			$this->ajaxReturn($res);
		}
	}

	public function flash()
	{
		$where['token'] = $this->_get('token', 'trim');
		$flash = M('Flash')->where($where)->select();
		$count = count($flash);
		$this->assign('flash', $flash);
		$this->assign('info', $this->info);
		$this->assign('num', $count);
		$this->display('ty_index');
	}

	public function getLink($url)
	{
		$url = ($url ? $url : 'javascript:void(0)');
		$urlArr = explode(' ', $url);
		$urlInfoCount = count($urlArr);

		if (1 < $urlInfoCount) {
			$itemid = intval($urlArr[1]);
		}

		if (strexists($url, '刮刮卡')) {
			$link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if (strexists($url, '大转盘')) {
			$link = '/index.php?g=Wap&m=Lottery&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if (strexists($url, '优惠券')) {
			$link = '/index.php?g=Wap&m=Coupon&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if (strexists($url, '刮刮卡')) {
			$link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link .= '&id=' . $itemid;
			}
		}
		else if (strexists($url, '商家订单')) {
			if ($itemid) {
				$link = $link = '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&hid=' . $itemid;
			}
			else {
				$link = '/index.php?g=Wap&m=Host&a=Detail&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
			}
		}
		else if (strexists($url, '万能表单')) {
			if ($itemid) {
				$link = $link = '/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if (strexists($url, '相册')) {
			$link = '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Photo&a=plist&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if (strexists($url, '全景')) {
			$link = '/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Panorama&a=item&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if (strexists($url, '会员卡')) {
			$link = '/index.php?g=Wap&m=Card&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if (strexists($url, '商城')) {
			$link = '/index.php?g=Wap&m=Product&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if (strexists($url, '订餐')) {
			$link = '/index.php?g=Wap&m=Product&a=dining&dining=1&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if (strexists($url, '团购')) {
			$link = '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if (strexists($url, '首页')) {
			$link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
		}
		else if (strexists($url, '网站分类')) {
			$link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&classid=' . $itemid;
			}
		}
		else if (strexists($url, '图文回复')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if (strexists($url, 'LBS信息')) {
			$link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id;

			if ($itemid) {
				$link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&companyid=' . $itemid;
			}
		}
		else if (strexists($url, 'DIY宣传页')) {
			$link = '/index.php/show/' . $this->token;
		}
		else if (strexists($url, '婚庆喜帖')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else if (strexists($url, '投票')) {
			if ($itemid) {
				$link = '/index.php?g=Wap&m=Vote&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
			}
		}
		else {
			$link = str_replace(array('{wechat_id}', '{siteUrl}', '&amp;', '{changjingUrl}'), array($this->wecha_id, $this->siteUrl, '&', 'http://www.weihubao.com'), $url);
			$iswxUrl = strpos($url, "mp.weixin.qq.com");
			if (($iswxUrl == 7) || ($iswxUrl == 8)) {
				$link = str_replace("#rd", "#wechat_redirect", $url);
			}
			else {
			    if (!!(strpos($url, 'tel') === false) && ($url != 'javascript:void(0)') && !strpos($url, 'wecha_id=')) {
				if (strpos($url, '?')) {
					$link = $link . '&wecha_id=' . $this->wecha_id;
				}
				else {
					$link = $link . '?wecha_id=' . $this->wecha_id;
				}
			    }
			}
		}

		return $link;
	}

	public function convertLinks($arr)
	{
		$i = 0;

		foreach ($arr as $a) {
			if ($a['url']) {
				$arr[$i]['url'] = $this->getLink($a['url']);
			}

			$i++;
		}

		return $arr;
	}

	public function _getPlugMenu()
	{
		$company_db = M('company');
		$this->company = $company_db->where(array('token' => $this->token, 'isbranch' => 0))->find();
		$plugmenu_db = M('site_plugmenu');
		$plugmenus = $plugmenu_db->where(array('token' => $this->token, 'display' => 1))->order('taxis ASC')->limit('0,4')->select();

		if ($plugmenus) {
			$i = 0;

			foreach ($plugmenus as $pm) {
				switch ($pm['name']) {
				case 'tel':
					if (!$pm['url']) {
						$pm['url'] = 'tel:/' . $this->company['tel'];
					}
					else {
						$pm['url'] = 'tel:/' . $pm['url'];
					}

					break;

				case 'memberinfo':
					if (!$pm['url']) {
						$pm['url'] = '/index.php?g=Wap&m=Userinfo&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
					}

					break;

				case 'nav':
					if (!$pm['url']) {
						$pm['url'] = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
					}

					break;

				case 'message':
					break;

				case 'share':
					break;

				case 'home':
					if (!$pm['url']) {
						$pm['url'] = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
					}

					break;

				case 'album':
					if (!$pm['url']) {
						$pm['url'] = '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
					}

					break;

				case 'email':
					$pm['url'] = 'mailto:' . $pm['url'];
					break;

				case 'shopping':
					if (!$pm['url']) {
						$pm['url'] = '/index.php?g=Wap&m=Product&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
					}

					break;

				case 'membercard':
					$card = M('member_card_create')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();

					if (!$pm['url']) {
						if ($card == false) {
							$pm['url'] = rtrim($this->siteUrl, '/') . U('Wap/Card/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id));
						}
						else {
							$pm['url'] = rtrim($this->siteUrl, '/') . U('Wap/Card/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id));
						}
					}

					break;

				case 'activity':
					$pm['url'] = $this->getLink($pm['url']);
					break;

				case 'weibo':
					break;

				case 'tencentweibo':
					break;

				case 'qqzone':
					break;

				case 'wechat':
					$pm['url'] = 'weixin://addfriend/' . $this->weixinUser['wxid'];
					break;

				case 'music':
					break;

				case 'video':
					break;

				case 'recommend':
					$pm['url'] = $this->getLink($pm['url']);
					break;

				case 'other':
					$pm['url'] = $this->getLink($pm['url']);
					break;
				}

				$plugmenus[$i] = $pm;
				$i++;
			}
		}
		else {
			$plugmenus = array();
		}

		return $plugmenus;
	}

	public function tel($tel = '')
	{
		$this->assign('tel', $tel);
		$this->display();
	}

	public function map()
	{
		if (C('baidu_map')) {
			$nav = explode(',', $_GET['nav']);
			$_GET['nav'] = $nav[1] . ',' . $nav[0];
			$url = 'http://api.map.baidu.com/marker?location=' . $_GET['nav'] . '&title=' . $_GET['name'] . '&content=' . $_GET['name'] . '&output=html';
		}
		else {
			$url = 'http://mo.amap.com/share/index/lnglat=' . $_GET['nav'] . '&name=' . $_GET['name'];
		}

		redirect($url);
	}

	public function PhoneVerify()
	{
		if (IS_POST) {
			if (empty($_POST['token']) || empty($_POST['action_id']) || empty($_POST['wecha_id'])) {
				$this->error('参数错误!');
				exit();
			}

			if (empty($_POST['tel'])) {
				$this->error('手机号不能为空');
			}
			else if (empty($_POST['verification_code'])) {
				$this->error('短信验证码不能为空');
			}
			else if ($_SESSION['rand_num'][$_POST['tel']] == '') {
				$this->error('该手机号未被验证');
			}
			else if (1800 < ($_SERVER['REQUEST_TIME'] - $_SESSION['send_time'][$_POST['tel']])) {
				$this->error('短信验证码过期失效,请重新获取');
			}
			else if ($_POST['verification_code'] != $_SESSION['rand_num'][$_POST['tel']]) {
				$this->error('短信验证码错误');
			}
			else {
				$voter = M('voteimg_users')->where(array('vote_id' => $_POST['action_id'], 'token' => $_POST['token'], 'wecha_id' => $_POST['wecha_id']))->find();

				if (!empty($voter)) {
					$update_phone = M('voteimg_users')->where(array('vote_id' => $_POST['action_id'], 'token' => $_POST['token'], 'wecha_id' => $_POST['wecha_id']))->save(array('phone' => $_POST['tel']));
					$userinfoWhere = array('token' => $_POST['token'], 'wecha_id' => $_POST['wecha_id']);

					if (M('Userinfo')->where($userinfoWhere)->find()) {
						M('Userinfo')->where($userinfoWhere)->save(array('tel' => $_POST['tel'], 'isverify' => 1));
					}

					if ($update_phone) {
						$this->success('手机验证成功,您可以参与投票了', $this->R);
					}
					else {
						$this->error('手机号验证失败');
					}
				}
				else {
					$this->error('手机号验证失败');
				}
			}
		}

		$this->assign('action_id', $_GET['action_id']);
		$voteimg = M('voteimg')->where(array('id' => $_GET['action_id'], 'display' => 1))->find();

		if ($voteimg['territory_limit'] != 1) {
			header('Location:' . $this->R);
		}

		$this->assign('token', $_GET['token']);
		$this->assign('wecha_id', $this->wecha_id);
		$this->assign('R', $this->R);
		$this->display();
	}

	public function sendSms()
	{
		$mobile = $this->_post('mobile');
		$token = $this->_post('token', 'trim');
		$voteimg_id = $this->_post('voteimg_id', 'intval');
		if (empty($mobile) || empty($token) || empty($voteimg_id)) {
			exit('参数错误');
		}

		$voteimg = M('voteimg')->where(array('id' => $voteimg_id, 'display' => 1))->find();

		if (empty($voteimg)) {
			exit('投票活动不存在');
		}

		$exists = M('voteimg_users')->where(array('vote_id' => $voteimg_id, 'token' => $token, 'phone' => $mobile))->find();

		if ($exists) {
			exit('该手机号已经在该活动中注册验证过,请直接去活动中参与投票');
		}

		if ($voteimg['territory_limit'] == 1) {
			$check_mobile = $this->CheckMobile($voteimg['pro_city'], $mobile);

			if (!$check_mobile) {
				exit('您输入的手机号不在允许投票的区域内');
			}

			$_SESSION['rand_num'][$mobile] = '';
			$_SESSION['send_time'][$mobile] = '';
			$rand_num = rand(100000, 999999);
			$_SESSION['rand_num'][$mobile] = $rand_num;
			$_SESSION['send_time'][$mobile] = $_SERVER['REQUEST_TIME'];
			$params = array();
			$params['sms'] = array('token' => $this->token, 'mobile' => $mobile, 'content' => '您的验证码是：' . $rand_num . '。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
			$return_status = MessageFactory::method($params, 'SmsMessage');
			if (($return_status == 0) && (strlen($return_status) == 1)) {
				exit('done');
			}
			else if ($return_status == NULL) {
				exit('not_buy');
			}
			else {
				exit('短信发送失败,请稍后再试');
			}
		}
		else {
			exit('该活动没开启地区限制');
		}
	}

	public function CheckMobile($pro_city = '', $mobile = '')
	{
		if (empty($pro_city) && (strpos($pro_city, '_') === false)) {
			return false;
		}

		if (empty($mobile)) {
			return false;
		}

		$MobileAttribution = $this->MobileAttribution($mobile);
		list($limit_pro, $limit_city) = explode('=', $MobileAttribution);

		if (strpos($pro_city, '|') !== false) {
			$pro_citys = explode('|', $pro_city);

			foreach ($pro_citys as $key => $val) {
				list($p, $c) = explode('_', $val);
				if ((strpos($p, $limit_pro) !== false) && empty($c)) {
					return true;
				}
				else {
					if ((strpos($p, $limit_pro) !== false) && (strpos($c, $limit_city) !== false)) {
						return true;
					}
					else {
						continue;
					}
				}
			}
		}
		else {
			list($province, $city) = explode('_', $pro_city);
			if ((strpos($province, $limit_pro) !== false) && empty($city)) {
				return true;
			}
			else {
				if ((strpos($province, $limit_pro) !== false) && (strpos($city, $limit_city) !== false)) {
					return true;
				}
			}
		}

		return false;
	}

	private function MobileAttribution($mobile = '')
	{
		if ($mobile == '') {
			return false;
		}

		if (S('cache_' . $mobile) != '') {
			return S('cache_' . $mobile);
		}

		$request_url = 'http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile=' . $mobile;
		$repost_string = file_get_contents($request_url);
		$array_data = json_decode(json_encode(simplexml_load_string($repost_string, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		if (!empty($array_data['province']) && !empty($array_data['city'])) {
			S('cache_' . $mobile, trim($array_data['province']) . '=' . trim($array_data['city']));
		}

		return trim($array_data['province']) . '=' . trim($array_data['city']);
	}

	public function commonVerifyPhone()
	{
		if (IS_POST) {
			if (empty($_POST['token']) || empty($_POST['wecha_id'])) {
				$this->error('参数错误!');
				exit();
			}

			if (empty($_POST['tel'])) {
				$this->error('手机号不能为空');
			}
			else if (empty($_POST['verification_code'])) {
				$this->error('短信验证码不能为空');
			}
			else if ($_SESSION['c_rand_num'][$_POST['tel']] == '') {
				$this->error('该手机号未被验证');
			}
			else if (1800 < ($_SERVER['REQUEST_TIME'] - $_SESSION['c_send_time'][$_POST['tel']])) {
				$this->error('短信验证码过期失效,请重新获取');
			}
			else if ($_POST['verification_code'] != $_SESSION['c_rand_num'][$_POST['tel']]) {
				$this->error('短信验证码错误');
			}
			else {
				$userinfoWhere = array('token' => $_POST['token'], 'wecha_id' => $_POST['wecha_id']);

				if (M('Userinfo')->where($userinfoWhere)->find()) {
					$update_phone = M('Userinfo')->where($userinfoWhere)->save(array('tel' => $_POST['tel'], 'isverify' => 1));

					if ($update_phone) {
						$this->success('手机验证成功,', $this->R);
					}
					else {
						$this->error('手机号验证失败');
					}
				}
			}
		}

		$this->assign('token', $_GET['token']);
		$this->assign('wecha_id', $this->wecha_id);
		$this->assign('R', $this->R);
		$this->display();
	}

	public function commonSmsVerify()
	{
		$mobile = $this->_post('mobile');
		$token = $this->_post('token', 'trim');
		$wecha_id = $this->_post('wecha_id', 'trim');
		if (empty($mobile) || empty($token)) {
			exit('参数错误');
		}

		$userinfoWhere = array('token' => $token, 'wecha_id' => $wecha_id);
		$userinfo = M('Userinfo')->where($userinfoWhere)->find();
		if (!empty($userinfo) && ($userinfo['isverify'] == 1)) {
			exit('您已经验证过手机号了,请勿重复验证');
		}

		$_SESSION['c_rand_num'][$mobile] = '';
		$_SESSION['c_send_time'][$mobile] = '';
		$rand_num = rand(100000, 999999);
		$_SESSION['c_rand_num'][$mobile] = $rand_num;
		$_SESSION['c_send_time'][$mobile] = $_SERVER['REQUEST_TIME'];
		$params = array();
		$params['sms'] = array('token' => $this->token, 'mobile' => $mobile, 'content' => '您的验证码是：' . $rand_num . '。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
		$return_status = MessageFactory::method($params, 'SmsMessage');
		if (($return_status == 0) && (strlen($return_status) == 1)) {
			exit('done');
		}
		else if ($return_status == NULL) {
			exit('not_buy');
		}
		else {
			exit('短信发送失败,请稍后再试');
		}
	}

	public function wap_ajax()
	{
		$info = htmlspecialchars($_POST['info']);

		if (is_numeric($info)) {
			$res = M('Funintro')->where(array('classid' => $info))->field('id,title,menu_link,img_havecolor,wgwshow')->order('id desc')->select();

			if (!empty($res)) {
				foreach ($res as $k => $v) {
					if (($v["wgwshow"] != 1) || empty($v["img_havecolor"])) {
						continue;
					}
					$str .= '<a href=\'' . $v['menu_link'] . '\' class=\'item_one\' style=\'display:block;\'><img class=\'gntub\' src=\'' . $v['img_havecolor'] . '\'> <div class=\'tet2\'><span>' . $v['title'] . '</span></div></a>';
				}
			}
		}
		else if ($info == 'new') {
			$res = M('Funintro')->where(array('is_new' => 1))->field('id,title,menu_link,img_havecolor,wgwshow')->order('id desc')->select();

			if (!empty($res)) {
				foreach ($res as $k => $v) {
					if (($v["wgwshow"] != 1) || empty($v["img_havecolor"])) {
						continue;
					}
					$str .= '<a href=\'' . $v['menu_link'] . '\' class=\'item_one\' style=\'display:block;\'><img class=\'gntub\' src=\'' . $v['img_havecolor'] . '\'> <div class=\'tet2\'><span>' . $v['title'] . '</span></div></a>';
				}
			}
		}
		else {
			$res = M('Funintro')->where(array('type' => 0))->field('id,title,menu_link,img_havecolor,wgwshow')->order('id desc')->select();

			if (!empty($res)) {
				foreach ($res as $k => $v) {
					if (($v["wgwshow"] != 1) || empty($v["img_havecolor"])) {
						continue;
					}
					$str .= '<a href=\'' . $v['menu_link'] . '\' class=\'item_one\' style=\'display:block;\'><img class=\'gntub\' src=\'' . $v['img_havecolor'] . '\'> <div class=\'tet2\'><span>' . $v['title'] . '</span></div></a>';
				}
			}
		}

		$this->ajaxReturn($str, 'JSON');
		unset($str);
	}

	public function Replycontent()
	{
		$id = (int) $_GET['id'];
		$token = (string) $_GET['token'];
		$content = M('img')->where(array('id' => $id))->find();

		if (!empty($content)) {
			$homes = M('home')->where(array('token' => $token))->getField('gzhurl');
			$this->assign('res', $content);
			$this->assign('homes', $homes);
			$this->assign('tpl', $this->tpl);
			$this->display();
		}
		else {
			$this->error('您所请求的页面不存在');
		}
	}

	public function curlPost($url, $data, $showError = 0)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);

		if ($errorno) {
			return array('rt' => false, 'errorno' => $errorno);
		}
		else {
			$js = json_decode($tmpInfo, 1);

			if (intval($js['errcode'] == 0)) {
				return array('rt' => true, 'errorno' => 0, 'media_id' => $js['media_id'], 'msg_id' => $js['msg_id']);
			}
			else if ($showError) {
				if ($js['errcode'] == '40130') {
					$this->error('抱歉，群发消息至少要选择两个人以上。');
				}
				else {
					$this->error('发生了Post错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);
				}
			}
			else {
				return array('rt' => false, 'errorno' => $js['errcode'], 'media_id' => $js['media_id'], 'msg_id' => $js['msg_id']);
			}
		}
	}

	public function CheckMessage()
	{
		$media_id = trim($_GET['mid']);

		if ($media_id == '') {
			$this->assign('error', '审核失败，参数错误');
			$this->display();
			exit();
		}

		$check_status = ($_GET['check_status'] ? intval($_GET['check_status']) : 1);
		$where = array('token' => $this->token);
		$thisWxUser = M('Wxuser')->where($where)->find();
		$notice = ($check_status == 1 ? '审核通过' : '审核不通过');
		$info = M('send_message')->where(array('mediaid' => $media_id))->find();

		if (!empty($info)) {
			if ($info['status'] != 0) {
				$this->assign('error', '群发已经发送,此刻审核无效');
				$this->display();
				exit();
			}

			$set = M('send_message')->where(array('mediaid' => $media_id))->save(array('check_status' => $check_status));

			if ($set !== false) {
				if ($check_status == 1) {
					if ($info['schedule_type'] == 2) {
						$apiOauth = new apiOauth();
						$access_token = $apiOauth->update_authorizer_access_token($thisWxUser['appid']);

						if ($access_token) {
							$openid = explode(',', $info['openid']);

							if ($info['send_type'] == 1) {
								$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token, '{"filter":{"group_id":"' . $info['groupid'] . '"},"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
							}
							else if ($info['send_type'] == 2) {
								$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $access_token, '{"touser":' . json_encode($openid) . ',"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
							}
							else {
								$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token, '{"filter":{"is_to_all":true},"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
							}

							if ($sendrt['rt'] == false) {
								$this->assign('error', '群发发送失败,curl_error:' . $sendrt['errorno']);
								$this->display();
								exit();
							}
							else {
								M('Send_message')->where(array('id' => $info['id']))->save(array('msg_id' => $sendrt['msg_id'], 'status' => 1, 'time' => time()));
								$this->assign('message', '审核通过成功,群发消息已经发送');
								$this->display();
								exit();
							}
						}
						else {
							$this->assign('error', '获取access_token发生错误');
							$this->display();
							exit();
						}
					}
					else {
						$this->assign('message', '审核通过成功,群发定时任务已经启动');
						$this->display();
						exit();
					}
				}
				else {
					$this->assign('message', '审核不通过成功,群发消息将不会被发送');
					$this->display();
					exit();
				}
			}
			else {
				$this->assign('error', $notice . '失败,请稍后再试');
				$this->display();
				exit();
			}
		}
		else {
			$this->assign('error', '未找到审核对象,审核失败');
			$this->display();
			exit();
		}
	}

	public function AutoSendMessage()
	{
		if ($this->token == '') {
			echo json_encode(array('status' => 'fail', 'msg' => 'no token'));
			exit();
		}

		$info = M('send_message')->where(array(
	'token'         => $this->token,
	'schedule_type' => 1,
	'check_status'  => 1,
	'schedule_time' => array(
		'between',
		array($_SERVER['REQUEST_TIME'] - 5, $_SERVER['REQUEST_TIME'] + 5)
		)
	))->find();

		if (!empty($info)) {
			$where = array('token' => $this->token);
			$thisWxUser = M('Wxuser')->where($where)->find();
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($thisWxUser['appid']);

			if ($access_token) {
				$openid = explode(',', $info['openid']);

				if ($info['send_type'] == 1) {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token, '{"filter":{"group_id":"' . $info['groupid'] . '"},"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
				}
				else if ($info['send_type'] == 2) {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $access_token, '{"touser":' . json_encode($openid) . ',"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
				}
				else {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token, '{"filter":{"is_to_all":true},"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
				}

				if ($sendrt['rt'] == false) {
					exit($sendrt['errorno']);
				}
				else {
					M('Send_message')->where(array('id' => $info['id']))->save(array('msg_id' => $sendrt['msg_id'], 'status' => 1, 'time' => time()));
					exit('success');
				}
			}
			else {
				exit('access_token获取失败');
			}
		}
		else {
			exit('没有找到合适的图文');
		}
	}

	public function AutoSendTemplate()
	{
		set_time_limit(0);

		if ($this->token == "") {
			echo json_encode(array("status" => "fail", "msg" => "no token"));
			exit();
		}

		$send_prepare = M("send_template")->where(array(
	"token"         => $this->token,
	"schedule_type" => 1,
	"schedule_time" => array(
		"between",
		array($_SERVER["REQUEST_TIME"] - 5, $_SERVER["REQUEST_TIME"] + 5)
		)
	))->find();

		if (empty($send_prepare)) {
			exit("发送失败，未找到要发送的群发");
		}
		else if ($send_prepare["send_count"] <= 0) {
			exit("未找到接收消息的粉丝");
		}

		$openids = explode(",", $send_prepare["openid"]);
		$model = new templateNews();
		$send_values = unserialize($send_prepare["send_values"]);

		if (strpos($send_prepare["openid"], ",") === false) {
			$send_values["href"] = ($send_prepare["jump_url"] ? $this->getLink($send_prepare["jump_url"]) : "");
			$send_values["wecha_id"] = $send_prepare["openid"];
			$res = $model->sendTempMsg($send_prepare["tempkey"], $send_values);

			if ($res["rt"]) {
				$send_prepare["msgid"] = $res["errmsg"];
				$send_prepare["status"] = 1;
			}
			else {
				$send_prepare["msgid"] = "";
				$send_prepare["status"] = 0;
			}

			$send_prepare["wecha_id"] = $send_prepare["openid"];
			$this->sendlog($send_prepare);
		}
		else {
			$openids = explode(",", $send_prepare["openid"]);

			foreach ($openids as $key => $value ) {
				$send_values["href"] = ($send_prepare["jump_url"] ? $this->getLink($send_prepare["jump_url"]) : "");
				$send_values["wecha_id"] = $value;
				$res = $model->sendTempMsg($send_prepare["tempkey"], $send_values);

				if ($res["rt"]) {
					$send_prepare["msgid"] = $res["errmsg"];
					$send_prepare["status"] = 1;
				}
				else {
					$send_prepare["msgid"] = "";
					$send_prepare["status"] = 0;
				}

				$send_prepare["wecha_id"] = $value;
				$this->sendlog($send_prepare);
			}
		}

		M("send_template")->where(array("id" => $send_prepare["id"]))->save(array("status" => 1));
		exit("success");
	}

	private function sendlog($data)
	{
		$insert = array();
		$insert["token"] = $data["token"];
		$insert["templateid"] = $data["id"];
		$insert["wecha_id"] = $data["wecha_id"];
		$insert["sendname"] = $data["sendname"];
		$insert["msgid"] = $data["msgid"];
		$insert["status"] = $data["status"];
		$insert["addtime"] = time();
		M("send_template_log")->add($insert);
	}
	public function tongji()
	{
		$this->display();
	}
}

?>
