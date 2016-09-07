<?php

class Member_card_gradeModel extends Model
{
	protected $_validate = array(
		array("gradename", "require", "等级名称不能为空", 1)
		);

	public function selectGrade($where = array())
	{
		if (empty($where) || !is_array($where)) {
			return false;
		}

		$result = $this->where($where)->select();

		if (!empty($result)) {
			return $result;
		}
		else {
			return false;
		}
	}

	public function findGrade($where = "", $field = "")
	{
		if (!$field) {
			$result = $this->where($where)->find();
		}
		else {
			$result = $this->where($where)->getField($field);
		}

		if (!empty($result)) {
			return $result;
		}
		else {
			return false;
		}
	}

	public function allinsertGrade($data = array(), $token = "")
	{
		if (empty($data) || ($token == "")) {
			return false;
		}

		if (is_array($data)) {
			$insert = array();
			$i = 0;

			foreach ($data as $key => $value ) {
				$insert[$i]["grade_name"] = $value["grade_name"];
				$insert[$i]["token"] = $token;
				$insert[$i]["addtime"] = $_SERVER["REQUEST_TIME"];
				$insert[$i]["status"] = ($value["status"] ? $value["status"] : 1);
				$i++;
			}

			return $this->addAll($insert);
		}
		else {
			return false;
		}
	}

	public function insertGrade($data = array(), $token = "")
	{
		if (empty($data) || ($token == "")) {
			return false;
		}

		if (is_array($data)) {
			$insert = array();
			$insert["grade_name"] = $data["grade_name"];
			$insert["token"] = $token;
			$insert["addtime"] = $_SERVER["REQUEST_TIME"];
			$insert["status"] = $data["status"];
			return $this->add($insert);
		}
		else {
			return false;
		}
	}

	public function saveGrade($set, $where)
	{
		if (count($set) == 1) {
			$v = $set[1];
			$k = $set[0];
			$update = $this->where($where)->setField($k, $v);
		}
		else {
			$update = $this->where($where)->save($set);
		}

		return $update;
	}

	public function delGrade($where = array())
	{
		if (empty($where)) {
			return false;
		}

		return $this->where($where)->delete();
	}
}


?>
