<?php

class UsersAction extends AgentAction
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function index()
	{
		$users_db = M('Users');
		$where = $this->agentWhere;

		if (isset($_GET['keyword'])) {
			$where['username'] = $this->_get('keyword');
		}

		$count = $users_db->where($where)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$list = $users_db->where($where)->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->agentWhere['agentid'] = $this->thisAgent['is_package'] == '1' ? $this->agentWhere['agentid'] : '0';
		$groups = M('User_group')->where($this->agentWhere)->select();
		$groupsByID = array();

		if ($groups) {
			foreach ($groups as $g) {
				$groupsByID[$g['id']] = $g;
			}
		}

		if ($list) {
			$i = 0;

			foreach ($list as $item) {
				$list[$i]['groupName'] = $groupsByID[$item['gid']]['name'];
				$i++;
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function addUser()
	{
		if (IS_POST) {
			$users_db = M('Users');

			if (trim($_POST['password'])) {
				$password = $this->_post('password', 'trim', 0);
				$repassword = $this->_post('repassword', 'trim', 0);

				if ($password != $repassword) {
					$this->error('两次输入密码不一致！');
				}

				$_POST['password'] = md5($password);
			}
			else {
				unset($_POST['password']);
				unset($_POST['repassword']);
			}

			$_POST['agentid'] = $this->agentid;
			$_POST['status'] = 1;
			$_POST['viptime'] = strtotime($_POST['viptime']);

			if (isset($_POST['gid'])) {
				$this->validateGid($_POST['gid'], $this->agentWhere['agentid']);
			}

			if (!isset($_POST["usertplid"])) {
				$_POST["usertplid"] = get_default_tplid();
			}

			if ($users_db->create()) {
				$user_id = $users_db->add();

				if ($user_id) {
					$this->success('添加成功！', U('Users/index'));
				}
				else {
					$this->error('添加失败!');
				}
			}
			else {
				$this->error($users_db->getError());
			}
		}
		else {
			$this->assign('actionUrl', '?g=Agent&m=Users&a=addUser');
			$this->assign('pageName', '添加用户');
			$this->agentWhere['agentid'] = $this->thisAgent['is_package'] == '1' ? $this->agentWhere['agentid'] : '0';
			$groups = M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups', $groups);
			$thisUser = array('viptime' => time());
			$this->assign('info', $thisUser);
			$this->display('updateUser');
		}
	}

	public function updateUser()
	{
		if (IS_POST) {
			if (isset($_POST['gid'])) {
				$this->validateGid($_POST['gid'], $this->agentWhere['agentid']);
			}

			$uid['uid'] = intval($_POST['id']);
			$users = M('Users')->where(array('id' => intval($_POST['id'])))->find();
			$token = M('Wxuser')->field('token')->where($uid)->select();
			$pricebyMonth = intval($this->thisAgent['wxacountprice']) / 12;
			$pricebyDay = intval($this->thisAgent['wxacountprice']) / 365;
			$_POST['viptime'] = strtotime($_POST['viptime']);

			if ($_POST['viptime'] < time()) {
				$this->error('到期日期不能小于当前');
			}

			if (((intval($_POST['viptime']) - $users['viptime']) < (30 * 24 * 3600)) && ((intval($_POST['viptime']) - strtotime(date('Y-m-d', $users['viptime']))) != 0)) {
				$this->error('延长期限不能小于一个月');
			}

			$month = (intval($_POST['viptime']) - $users['viptime']) / (30 * 24 * 3600);
			$day = (intval($_POST['viptime']) - $users['viptime']) / (24 * 3600);
			$month = intval($month);
			$price = $pricebyDay * count($token) * $day;
			$price = intval($price);

			if ($this->thisAgent['moneybalance'] < $price) {
				$this->error('余额不足，共需要' . $price . '元，而您的余额为' . $this->thisAgent['moneybalance']);
			}

			$users_db = M('Users');

			if (trim($_POST['password'])) {
				$password = $this->_post('password', 'trim', 0);
				$repassword = $this->_post('repassword', 'trim', 0);

				if ($password != $repassword) {
					$this->error('两次输入密码不一致！');
				}

				$_POST['password'] = md5($password);
			}
			else {
				unset($_POST['password']);
				unset($_POST['repassword']);
			}

			unset($_POST['dosubmit']);
			unset($_POST['__hash__']);
			$_POST['status'] = 1;
			unset($_POST['username']);

			if ($users_db->save($_POST)) {
				if ($price) {
					M('Agent')->where(array('id' => $this->thisAgent['id']))->setDec('moneybalance', $price);
					M('Agent_expenserecords')->add(array('agentid' => $this->thisAgent['id'], 'amount' => 0 - $price, 'des' => $users['username'] . '(uid:' . intval($_POST['id']) . ')延期' . $day . '天，共' . count($token) . '个公众号', 'status' => 1, 'time' => time()));
				}

				$this->success('编辑成功！', U('Users/index'));
			}
			else {
				$this->error('编辑失败!');
			}
		}
		else {
			$id = intval($_GET['id']);
			$thisUser = M('Users')->where(array('agentid' => $this->thisAgent['id'], 'id' => $id))->find();

			if (!$thisUser) {
				$this->error('没有此用户');
			}

			$this->assign('actionUrl', '?g=Agent&m=Users&a=updateUser');
			$this->assign('pageName', '修改用户');
			$this->assign('isUpdate', 1);
			$this->assign('info', $thisUser);
			$this->agentWhere['agentid'] = $this->thisAgent['is_package'] == '1' ? $this->agentWhere['agentid'] : '0';
			$groups = M('User_group')->where($this->agentWhere)->order('id ASC')->select();
			$this->assign('groups', $groups);
			$this->display();
		}
	}

	private function validateGid($gid, $agentid)
	{
		$agentid = ($this->thisAgent['is_package'] == '1' ? $agentid : '0');
		$num = M('User_group')->where(array('agentid' => $agentid, 'id' => $gid))->count();

		if (!$num) {
			$this->error('用户组不存在');
		}
	}

	public function deleteUser()
	{
		$id = intval($_GET['id']);
		$thisUser = M('Users')->where(array('agentid' => $this->thisAgent['id'], 'id' => $id))->find();

		if (!$thisUser) {
			$this->error('没有此用户');
		}

		$rt = M('Users')->where(array('id' => $id))->delete();

		if ($rt) {
			$userCount = M('Users')->where(array('agentid' => $this->thisAgent['id']))->count();
			M('Agent')->where($this->agentWhere)->save(array('usercount' => $userCount));
			M('Wxuser')->where(array('uid' => $id))->delete();
			$wxuserCount = M('Wxuser')->where(array('agentid' => $this->thisAgent['id']))->count();
			M('Agent')->where($this->agentWhere)->save(array('wxusercount' => $wxuserCount));
		}

		$this->success('删除成功！', U('Users/index'));
	}

	public function groups()
	{
		$db = M('User_group');
		$count = $db->where($this->agentWhere)->count();
		$Page = new Page($count, 200);
		$show = $Page->show();
		$this->agentWhere['agentid'] = $this->thisAgent['is_package'] == '1' ? $this->agentWhere['agentid'] : '0';
		$list = $db->where($this->agentWhere)->order('id ASC')->select();

		if ($list) {
			$i = 1;

			foreach ($list as $item) {
				$db->where(array('id' => $item['id']))->save(array('taxisid' => $i));
				$i++;
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->assign('is_package', $this->thisAgent['is_package']);
		$this->display();
	}

	public function wxusers()
	{
		$db = M('Wxuser');
		$count = $db->where($this->agentWhere)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$list = $db->where($this->agentWhere)->order('id ASC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$uids = array();

		if ($list) {
			foreach ($list as $item) {
				if (!in_array($item['uid'], $uids)) {
					array_push($uids, $item['uid']);
				}
			}
		}

		if ($uids) {
			$users = M('Users')->where(array(
	'id' => array('in', $uids)
	))->select();
			$usersByID = array();

			if ($users) {
				foreach ($users as $u) {
					$usersByID[$u['id']] = $u;
				}
			}

			if ($list) {
				$i = 0;

				foreach ($list as $item) {
					$list[$i]['username'] = $usersByID[$item['uid']]['username'];
					$i++;
				}
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function deleteWxUser()
	{
		$id = intval($_GET['id']);
		$thisUser = M('Wxuser')->where(array('agentid' => $this->thisAgent['id'], 'id' => $id))->find();

		if (!$thisUser) {
			$this->error('没有此公众号');
		}

		$rt = M('Wxuser')->where(array('id' => $id))->delete();
		$wxuserCount = M('Wxuser')->where(array('agentid' => $this->thisAgent['id']))->count();
		M('Agent')->where($this->agentWhere)->save(array('wxusercount' => $wxuserCount));
		$this->success('删除成功！', U('Users/wxusers'));
	}

	public function groupSet()
	{
		if ($this->thisAgent['is_package'] != '1') {
			$this->error('您没有权限自定义套餐');
		}

		$is_tongji = ($this->thisAgent ? $this->thisAgent["is_tongji"] : "1");
		$user_group_db = M('User_group');
		$agent_function_db = M('Function');
		$where = array_merge($this->agentWhere, array('status' => 1));
		$functions = $agent_function_db->where($where)->order('id ASC')->select();
		$this->assign('func', $functions);

		if (IS_POST) {
			if (isset($_POST['id'])) {
				if ($is_tongji != "1") {
					unset($_POST["tj_status"]);
				}
				else {
					$info = $user_group_db->where(array("id" => $_POST["id"]))->find();
					if (($info["tj_status"] == "1") && ($_POST["tj_status"] == "0")) {
						$ids = array();
						$tmp = M("users")->where(array("gid" => $info["id"], "_string" => "tongji_config !=''"))->field("id")->select();

						foreach ($tmp as $item ) {
							$ids[] = $item["id"];
						}

						if (!empty($ids)) {
							$tongjiList = M("tongji")->where(array(
	"uid"    => array("in", $ids),
	"status" => "1"
	))->field("id,act_token,act_name,act_id")->select();
							$tjIds = array();

							foreach ($tongjiList as $value ) {
								$tjIds[] = $value["id"];
								S("tongji_" . $value["act_token"] . "_" . $value["act_name"] . "_" . $value["act_id"], NULL);
							}

							if (!empty($tjIds)) {
								M("tongji")->where(array(
	"id" => array("in", $tjIds)
	))->save(array("status" => "0"));
							}
						}
					}
				}
				$_POST['func'] = join(',', $_REQUEST['func']);

				if ($user_group_db->create()) {
					$user_group_db->where(array('agentid' => $this->thisAgent['id'], 'id' => intval($_POST['id'])))->save($_POST);
					$this->success('修改成功！', U('Users/groups'));
				}
			}
			else {
				if ($is_tongji != "1") {
					$_POST["tj_status"] = "0";
				}

				if ($user_group_db->create()) {
					$_POST["func"] = join(",", $_REQUEST["func"]);
					$_POST["agentid"] = intval($this->thisAgent["id"]);
					$user_group_db->add($_POST);
					$this->success("添加成功！", U("Users/groups"));
				}
			}
		}
		else {
			if (isset($_GET['id'])) {
				$thisGroup = $user_group_db->where(array('agentid' => $this->thisAgent['id'], 'id' => intval($_GET['id'])))->find();
				$this->assign('info', $thisGroup);
			}

			$this->assign("is_tongji", $is_tongji);
			$this->display();
		}
	}

	public function groupCheck()
	{
		if (empty($_GET['id'])) {
			$this->error('套餐不存在');
		}

		$user_group_db = M('User_group');
		$agent_function_db = M('Function');
		$this->agentWhere['agentid'] = $this->thisAgent['is_package'] == '1' ? $this->agentWhere['agentid'] : '0';
		$where = array_merge($this->agentWhere, array('status' => 1));
		$functions = $agent_function_db->where($where)->order('id ASC')->select();
		$this->assign('func', $functions);

		if (isset($_GET['id'])) {
			$agentid = ($this->thisAgent['is_package'] == '1' ? $this->thisAgent['id'] : '0');
			$thisGroup = $user_group_db->where(array('agentid' => $agentid, 'id' => intval($_GET['id'])))->find();
			$this->assign('info', $thisGroup);
		}

		$this->display();
	}

	public function delGroup()
	{
		$id = $this->_get('id', 'intval', 0);

		if ($id == 0) {
			$this->error('非法操作');
		}

		$info = D('User_group')->where(array('agentid' => $this->thisAgent['id'], 'id' => $id))->delete();

		if (!$info) {
			$this->error('操作失败');
		}

		$this->success('操作成功');
	}
}

?>
