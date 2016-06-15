<?php

class ExtensionUserModel extends XModel
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
		$extensionRelation = D('ExtensionRelation');
		$userInfoModel = D('Userinfo');

		for ($i = 0; $i < count($result); $i++) {
			$result[$i]['user_info'] = $userInfoModel->where(array('id' => $result[$i]['uid']))->find();
			$tmpWhere = array('aid' => $get['id'], 'promoter' => $result[$i]['user_info']['id']);
			$lv1 = $extensionRelation->where($tmpWhere)->field('follower')->select();
			$result[$i]['follower1_num'] = count($lv1);
			$result[$i]['follower2_num'] = $this->countLv2($lv1, $get['id']);
		}

		return $result;
	}

	private function countLv2($list, $aid)
	{
		$ids = array();

		for ($i = 0; $i < count($list); $i++) {
			$ids[] = $list[$i]['follower'];
		}

		if (empty($ids)) {
			return 0;
		}

		$extensionRelation = D('ExtensionRelation');
		$tmpWhere = array(
			'aid'      => $aid,
			'promoter' => array('in', $ids)
			);
		return $extensionRelation->where($tmpWhere)->count();
	}

	private function setWhere($get)
	{
		$where = array();
		$where['aid'] = $get['id'];
		$where['token'] = $get['token'];
		return $this->where($where);
	}

	private function setOrder($get)
	{
		$order = array();

		if ($get['_create_time'] != '') {
			$order['create_time'] = $get['_create_time'];
		}

		return $this->order($order);
	}
}

?>
