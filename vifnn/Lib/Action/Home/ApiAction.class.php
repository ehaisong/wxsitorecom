<?php

class ApiAction extends Action
{
	protected function _initialize()
	{
		$token = C('DATA_TOKEN');
		$data = array_merge(array("time" => $_GET["time"], "act" => $_GET["act"], "code" => $_GET["code"]), $_POST ? $_POST : array());
		//file_put_contents('./apii.txt'.$_GET['sign']);
		//file_put_contents('./apid.txt'.$data);
		if (!isSign($_GET['sign'], $data, $token)) {   //!
		  //  file_put_contents('./apin.txt'.'NO');
			//echo json_encode(array('status' => 2, 'info' => '签名错误'));
			//die;
		}
	}
	public function index()
	{
		$act = I('get.act');
		if ($act == 'get_user') {
			$this->getUser();
		}
		echo json_encode(array('status' => 1, 'info' => '接口不存在'));
		die;
	}
	private function getUser()
	{
		if (empty($_POST['users'])) {
			echo json_encode(array('status' => 1, 'info' => '用户标识不能为空'));
			die;
		}
		$users = explode(',', I('post.users'));
		$where['mark'] = array('in', $users);
		$tongjiUser = M('tongji_user');
		$userinfo = M('userinfo');
		$list = array();
		$result = $tongjiUser->where($where)->select();
		foreach ($result as $item) {
			$list[$item['mark']] = $item;
		}
		$queryRes = array();
		foreach ($users as $user) {
			$uTmp = "-1";
			if (!empty($list[$user]) && !empty($list[$user]['wecha_id']) && !empty($list[$user]['token'])) {
				$fields = 'portrait,wechaname,sex,birthday,issub,city,province,tel';
				$tmp = $userinfo->where(array('wecha_id' => $list[$user]['wecha_id'], 'token' => $list[$user]['token']))->field($fields)->find();
				if (!empty($tmp)) {
				    $uTmp = array('avatar' => $tmp['portrait'], 'nickname' => $tmp['wechaname']);
				    $uTmp['gender'] = $tmp['sex'] ? $tmp['sex'] : 0;
				    $uTmp['birthday'] = isset($tmp['birthday']) ? $tmp['birthday'] : NULL;
				    $uTmp['subscribe'] = $tmp['issub'];
				    $uTmp['city'] = $tmp['city'];
				    $uTmp['province'] = $tmp['province'];
				    $uTmp['country'] = '中国';
				    $uTmp['mobile'] = $tmp['tel'];
				}
			}
			$queryRes[$user] = $uTmp;
		}
		$json['status'] = 0;
		$json['data'] = $queryRes;
		//file_put_contents('./json.txt',$json);
		echo json_encode($json);
		die;
	}
}
?>
