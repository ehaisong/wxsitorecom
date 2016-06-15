<?php

class ExtensionActivityModel extends XModel
{
	protected $token;

	public function __construct($name = '', $tablePrefix = '', $connection = '')
	{
		parent::__construct($name, $tablePrefix, $connection);
		$this->_rules = array(
	'all'    => array(
		'deny'     => 'status,token,consume_score,consume_cash,create_time,update_time,max_score',
		'validate' => array(
			array('name', 'regex', 'require', '海报名称不能为空'),
			array('keyword', 'regex', 'require', '关键词不能为空'),
			array('company', 'regex', 'require', '企业昵称不能为空'),
			array('nickname_len', 'regex', 'number', '用户昵称长度请输入数字类型的值'),
			array('nickname_len', 'compare', '>0', '用户昵称长度必须大于0'),
			array('start_time,end_time', 'c:validateTime', '', '开始时间不能小于或等于结束时间'),
			array('reward_lv1_cash', 'c:validateCash', '1,200', '推广一级奖励红包金额1-200元'),
			array('reward_lv2_cash', 'c:validateCash', '1,200', '推广二级奖励红包金额1-200元'),
			array('max_cash', 'c:validateMaxCash', '', '发放红包总金额不正确'),
			array('reward_lv1_score', 'c:validateScore', '', '推广一级奖励积分数值不正确'),
			array('reward_lv2_score', 'c:validateScore', '', '推广二级奖励积分数值不正确'),
			array('banner_config', 'regex', 'require', '请设计海报样式')
			),
		'assign'   => array(
			array('start_time', 'f:strtotime', NULL, self::VALUE_NOT_EMPTY),
			array('end_time', 'f:strtotime', NULL, self::VALUE_NOT_EMPTY),
			array('reward_lv1_cash', 'c:autoCash', '', self::FIELD_EXIST),
			array('reward_lv2_cash', 'c:autoCash', '', self::FIELD_EXIST),
			array('max_cash', 'c:autoCash', '', self::FIELD_EXIST),
			array('reward_type', 'c:autoRewardType', '')
			)
		),
	'add'    => array(
		'deny'   => 'id',
		'must'   => 'name,start_time,end_time,banner_config',
		'assign' => array(
			array('nickname_len', 'string', '9', self::VALUE_EMPTY),
			array('start_time', 'f:time', NULL, self::VALUE_EMPTY),
			array('end_time', 'c:assignEndTime', NULL, self::VALUE_EMPTY),
			array('token', 'c:assignToken', NULL, self::FIELD_BOTH),
			array('create_time', 'f:time', NULL, self::FIELD_BOTH),
			array('max_score', 'string', '100000000', self::FIELD_BOTH)
			)
		),
	'update' => array(
		'must'   => 'id',
		'assign' => array(
			array('update_time', 'f:time', NULL, self::FIELD_BOTH)
			)
		)
	);
	}

	protected function validateTime($value, $field, $formData, $param)
	{
		$start_time = (!empty($formData['start_time']) ? strtotime($formData['start_time']) : time());
		$end_time = (!empty($formData['end_time']) ? strtotime($formData['end_time']) : $start_time + (3600 * 24 * 30));
		return $start_time < $end_time;
	}

	protected function validateCash($value, $field, $formData, $param)
	{
		if ($formData['reward_type'] == 'score') {
			return true;
		}

		return $this->betweenCallback($value, $field, $formData, $param);
	}

	protected function validateMaxCash($value, $field, $formData, $param)
	{
		if ($formData['reward_type'] == 'score') {
			return true;
		}

		return validate_regex($value, 'currency');
	}

	protected function validateScore($value, $field, $formData, $param)
	{
		if ($formData['reward_type'] == 'cash') {
			return true;
		}

		return validate_regex($value, 'pos_0_int');
	}

	protected function assignEndTime($value, $field, $formData, $param)
	{
		return $formData['start_time'] + (3600 * 24 * 30);
	}

	protected function assignToken()
	{
		return $this->token;
	}

	protected function autoCash($value, $field, $formData, $param)
	{
		return (string) ($value * 100);
	}

	protected function autoRewardType($value, $field, $formData, $param)
	{
		if ($value == 'score') {
			if (isset($formData['reward_lv1_cash'])) {
				$formData['reward_lv1_cash'] = 0;
			}

			if (isset($formData['reward_lv2_cash'])) {
				$formData['reward_lv2_cash'] = 0;
			}

			if (isset($formData['max_cash'])) {
				$formData['max_cash'] = 0;
			}
		}
		else {
			if (isset($formData['reward_lv1_score'])) {
				$formData['reward_lv1_score'] = 0;
			}

			if (isset($formData['reward_lv2_score'])) {
				$formData['reward_lv2_score'] = 0;
			}
		}

		return $formData;
	}

	public function setToken($token)
	{
		$this->token = $token;
		return $this;
	}

	public function getTotal($get)
	{
		return $this->setWhere($get)->count();
	}

	public function getList($get, $offset, $length)
	{
		$result = $this->limit($offset, $length)->setWhere($get)->setOrder($get)->select();
		$time = time();
		$extensionUser = D('ExtensionUser');

		for ($i = 0; $i < count($result); $i++) {
			$result[$i]['time_status'] = '2';

			if ($time < $result[$i]['start_time']) {
				$result[$i]['time_status'] = '0';
			}
			else {
				if (($result[$i]['start_time'] <= $time) && ($time < $result[$i]['end_time'])) {
					$result[$i]['time_status'] = '1';
				}
			}

			$result[$i]['promoter_num'] = $extensionUser->where(array('aid' => $result[$i]['id']))->count();
		}

		return $result;
	}

	private function setWhere($get)
	{
		$where = array();

		if ($get['status'] != '') {
			$where['status'] = $get['status'];
		}

		if ($get['keyword'] != '') {
			$where['name'] = array('like', '%' . $get['keyword'] . '%');
		}

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

	public function delete()
	{
		$tmp = $this->options;
		$result = $this->select();
		$ids = array();
		$keywordModel = new KeywordModel();
		$root = C('EXTENSION_PATH') . '/banner/';

		foreach ($result as $key => $value) {
			$ids[] = $value['id'];
			del_dir($root . $value['id']);
			$keywordModel->clearKeyword($value['id'], 'Extension');
		}

		$extensionUser = D('ExtensionUser');
		$extensionRelation = D('ExtensionRelation');
		$where = array(
			'aid' => array('in', $ids)
			);
		$extensionUser->where($where)->delete();
		$extensionRelation->where($where)->delete();
		$recognitionModel = D('Recognition');
		$where2 = array(
			'type'    => 'extension',
			'type_id' => array('in', $ids)
			);
		$recognitionModel->where($where2)->delete();
		$this->options = $tmp;
		return parent::delete();
	}
}

?>
