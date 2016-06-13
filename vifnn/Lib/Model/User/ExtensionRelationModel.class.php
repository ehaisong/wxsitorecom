<?php

class ExtensionRelationModel extends XModel
{
	public function __construct($name = '', $tablePrefix = '', $connection = '')
	{
		parent::__construct($name, $tablePrefix, $connection);
	}

	public function getTotal($get)
	{
		return $this->setWhere($get)->count();
	}

	public function getList($get, $offset, $length)
	{
		$result = $this->limit($offset, $length)->setWhere($get)->setOrder($get)->select();
		$userInfoModel = D('Userinfo');

		for ($i = 0; $i < count($result); $i++) {
			$result[$i]['user_info'] = $userInfoModel->where(array('id' => $result[$i]['follower']))->find();
			$tmpWhere = array('aid' => $get['aid'], 'promoter' => $result[$i]['follower']);
			$lv1 = $this->where($tmpWhere)->field('follower')->select();
			$result[$i]['follower1_num'] = count($lv1);
		}

		return $result;
	}

	private function setWhere($get)
	{
		$where = array();
		$where['aid'] = $get['aid'];
		$where['token'] = $get['token'];
		$where['promoter'] = $get['uid'];
		return $this->where($where);
	}

	private function setOrder($get)
	{
		$order = array();

		if ($get['_follow_time'] != '') {
			$order['follow_time'] = $get['_follow_time'];
		}

		return $this->order($order);
	}

	public function getParents($pid, $aid, $uid)
	{
		$result = array();
		$userInfoModel = D('Userinfo');
		$info = $userInfoModel->where(array('id' => $uid))->field('id,wechaname,portrait')->find();
		$result[] = $info;

		if ($pid != $uid) {
			$relation = $this->where(array('follower' => $uid, 'aid' => $aid))->field('promoter,follower')->find();
			$result = array_merge($result, array_reverse($this->getParents($pid, $aid, $relation['promoter'])));
		}

		return array_reverse($result);
	}
}

?>
