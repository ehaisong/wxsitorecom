<?php

class XModel extends Model
{
	const FIELD_BOTH = 'both';
	const FIELD_NOT_EXIST = 'not_exist';
	const FIELD_EXIST = 'exist';
	const VALUE_EMPTY = 'empty';
	const VALUE_NOT_EMPTY = 'not_empty';
	const ACTION_INSERT = 'add';
	const ACTION_UPDATE = 'update';
	const ACTION_ALL = 'all';

	protected $actName;
	protected $actDesc;
	protected $_desc = array();
	protected $_rules = array();
	protected $_step = array('allow', 'deny', 'must', 'validate', 'ignore', 'assign');
	protected $denyInfo = '非法输入{$desc}';
	protected $mustInfo = '必须输入{$desc}';
	protected $errorCode;

	public function processing($formData = NULL, $actRules = NULL)
	{
		return $this->processingBase($formData, $actRules, true);
	}

	public function processingAlone($formData = NULL, $actRules = NULL)
	{
		return $this->processingBase($formData, $actRules, false);
	}

	private function processingBase($formData = NULL, $actRules = NULL, $includeAll)
	{
		$formData = (isset($formData) ? $formData : (IS_GET ? I('get.') : I('post.')));
		$formData = $this->processingMap($formData);
		$actRules = (isset($actRules) ? $actRules : $this->getActionName($formData));
		$proRules = $this->getRules($actRules);

		if (!empty($proRules)) {
			for ($i = 0; $i < count($this->_step); $i++) {
				$formData = $this->processingRules($formData, $this->groupByHandler($proRules, $this->_step[$i]), $includeAll);

				if ($formData === false) {
					return false;
				}
			}
		}

		$this->data($this->getExistPart($formData, false));
		return true;
	}

	public function proAllow($actRules, $formData = NULL)
	{
		return $this->proBase('allow', $actRules, $formData);
	}

	public function proDeny($actRules, $formData = NULL)
	{
		return $this->proBase('deny', $actRules, $formData);
	}

	public function proValidate($actRules, $formData = NULL)
	{
		return $this->proBase('validate', $actRules, $formData);
	}

	public function proIgnore($actRules, $formData = NULL)
	{
		return $this->proBase('ignore', $actRules, $formData);
	}

	public function proAssign($actRules, $formData = NULL)
	{
		return $this->proBase('assign', $actRules, $formData);
	}

	public function proBase($type, $actRules, $formData = NULL)
	{
		$formData = (isset($formData) ? $formData : (IS_GET ? I('get.') : I('post.')));
		$formData = $this->processingMap($formData);
		$proRules = call_user_func_array(array($this, 'get' . ucfirst($type) . 'Rules'), array($actRules));
		$formData = $this->processingRules($formData, $proRules, false);

		if ($formData === false) {
			return false;
		}

		$this->data($this->getExistPart($formData, false));
		return true;
	}

	public function processingMap($formData, $real = true)
	{
		$newForm = array();

		foreach ($formData as $key => $value) {
			$newKey = $this->getMapField($key, $real);
			$newForm[empty($newKey) ? $key : $newKey] = $value;
		}

		return $newForm;
	}

	public function getFieldDesc($field)
	{
		$realName = $this->getMapField($field);
		$field = (empty($realName) ? $field : $realName);
		$tmp = $this->_desc[$field];
		$tmp = (isset($tmp) ? (is_array($tmp) ? $tmp : explode(',', $tmp)) : array());
		return isset($tmp[0]) ? $tmp[0] : '';
	}

	public function getMapField($field, $real = true)
	{
		if ($real) {
			if (isset($this->_map) && isset($this->_map[$field])) {
				return $this->_map[$field];
			}

			if (isset($this->_desc)) {
				foreach ($this->_desc as $key => $value) {
					$value = (is_string($value) ? explode(',', $value) : $value);
					if (isset($value[1]) && ($value[1] == $field)) {
						return $key;
					}
				}
			}
		}
		else {
			if (isset($this->_map)) {
				foreach ($this->_map as $key => $value) {
					if ($value == $field) {
						return $key;
					}
				}
			}

			if (isset($this->_desc) && isset($this->_desc[$field])) {
				$arr = (is_string($this->_desc[$field]) ? explode(',', $this->_desc[$field]) : $this->_desc[$field]);

				if (isset($arr[1])) {
					return $arr[1];
				}
			}
		}

		return '';
	}

	public function getActionName($formData)
	{
		$pks = $this->getActionPks($formData);
		return empty($pks) ? self::ACTION_INSERT : self::ACTION_UPDATE;
	}

	public function getActionPks($formData)
	{
		$arr = array();
		$pks = (is_string($this->pk) ? array($this->pk) : $this->pk);

		for ($i = 0; $i < count($pks); $i++) {
			if (isset($formData[$pks[$i]]) && !empty($formData[$pks[$i]])) {
				$arr[$pks[$i]] = $formData[$pks[$i]];
			}
		}

		return $arr;
	}

	protected function getRules($actRules, $includeAll = true)
	{
		$this->actName = is_string($actRules) ? $actRules : '';
		$actRules = (is_array($actRules) ? $actRules : (isset($this->_rules[$actRules]) ? $this->_rules[$actRules] : array()));

		if (!empty($actRules)) {
			if (empty($this->actName) && isset($actRules['name'])) {
				$this->actName = $actRules['name'];
			}

			$tmpName = $this->actName;
			$this->actDesc = isset($actRules['desc']) ? $actRules['desc'] : ($tmpName == self::ACTION_INSERT ? '新增' : ($tmpName == self::ACTION_UPDATE ? '更新' : '全部'));
		}

		$arr = array();

		foreach ($this->_rules as $key => $value) {
			$tmp = array($value);

			if (is_string($key)) {
				if (!$this->getRulesCondition($key, $includeAll)) {
					continue;
				}

				$tmp = $this->getActionRules($value);
			}

			for ($i = 0; $i < count($tmp); $i++) {
				$tmp[$i][5] = isset($tmp[$i][5]) ? $tmp[$i][5] : self::FIELD_BOTH;
				$tmp[$i][6] = isset($tmp[$i][6]) ? $tmp[$i][6] : self::ACTION_ALL;

				if ($this->getRulesCondition($tmp[$i][6], $includeAll)) {
					$arr[] = $tmp[$i];
				}
			}
		}

		return $arr;
	}

	private function getRulesCondition($key, $includeAll)
	{
		if (($this->actName == $key) || (($key == self::ACTION_ALL) && $includeAll)) {
			return true;
		}

		if (strpos($key, '^') === 0) {
			if (!in_array($this->actName, explode(',', substr($key, 1)))) {
				return true;
			}
		}
		else if (strpos($key, ',') !== false) {
			if (in_array($this->actName, explode(',', $key))) {
				return true;
			}
		}

		return false;
	}

	protected function getActionRules($actRules)
	{
		$arr = array();

		foreach ($actRules as $key => $value) {
			$tmp = (is_string($key) ? call_user_func_array(array($this, 'get' . ucfirst($key) . 'Rules'), array($value)) : array($value));

			for ($i = 0; $i < count($tmp); $i++) {
				$tmp[$i][6] = $this->actName;
				$arr[] = $tmp[$i];
			}
		}

		return $arr;
	}

	public function processingRules($formData, $proRules, $includeAll = true)
	{
		for ($i = 0; $i < count($proRules); $i++) {
			list($field, $callback, $callParam, $handler, $handlerParam, $condition, $action) = $proRules[$i];

			if ($this->getRulesCondition($action, $includeAll)) {
				$field = ($field == '*' ? array_keys($formData) : $field);
				$field = (is_array($field) ? $field : (strpos($field, ',') !== false ? explode(',', $field) : $field));
				$value = $this->getFieldsVal($formData, $field);

				if ($this->getConditionResult($formData, $field, $condition)) {
					$result = NULL;

					if (!empty($callback)) {
						$callback = $this->getFunction($callback);
						$params = array($value);

						if (in_array($callback[0], array('c', 'u'))) {
							array_push($params, $field, $formData);
						}

						if (isset($callParam)) {
							$params[] = $callParam;
						}

						$result = call_user_func_array($callback[1], $params);
					}

					if (!empty($handler)) {
						$handler = $this->getFunction($handler, 'c', 'handler');
						$params = array($result, $field, $value, $formData);

						if (isset($handlerParam)) {
							$params[] = $handlerParam;
						}

						$hanRes = call_user_func_array($handler[1], $params);
					}

					if (($hanRes === false) || is_null($hanRes)) {
						return false;
					}

					$formData = $hanRes;
				}
			}
		}

		return $formData;
	}

	private function getFieldsVal($formData, $field)
	{
		$value = (is_array($field) ? array() : $formData[$field]);

		if (is_array($field)) {
			for ($j = 0; $j < count($field); $j++) {
				if (array_key_exists($field[$j], $formData)) {
					$value[$field[$j]] = $formData[$field[$j]];
				}
			}
		}

		return $value;
	}

	private function getConditionResult($formData, $field, $condition)
	{
		if ($condition == self::FIELD_BOTH) {
			return true;
		}

		$exist = (is_array($field) ? true : array_key_exists($field, $formData));
		$empty = (is_array($field) ? true : empty($formData[$field]));

		if (is_array($field)) {
			for ($i = 0; $i < count($field); $i++) {
				if (!array_key_exists($field[$i], $formData)) {
					$exist = false;
				}

				if (!empty($formData[$field[$i]])) {
					$empty = false;
				}
			}
		}

		$result = (($condition == self::FIELD_EXIST) && $exist) || (($condition == self::FIELD_NOT_EXIST) && !$exist) || (($condition == self::VALUE_EMPTY) && $exist && $empty) || (($condition == self::VALUE_NOT_EMPTY) && !$empty);
		return $result;
	}

	private function getFunction($name, $default = 'c', $suffix = 'callback')
	{
		$type = $default;
		$fun = $name;
		if (is_string($name) && (strpos($name, ':') !== false)) {
			list($type, $fun) = explode(':', $name);
		}

		if (is_string($fun) && (strpos($fun, ',') !== false)) {
			$type = 'c';
			$fun = explode(',', $fun);
		}

		if (is_array($fun)) {
			$type = 'c';

			if (count($fun) == 1) {
				array_unshift($fun, $this);
			}

			if (empty($fun[0])) {
				$fun[0] = $this;
			}
		}

		if (($type == 'c') && is_string($fun)) {
			$funArr = explode('_', $fun);
			$str = '';

			for ($i = 0; $i < count($funArr); $i++) {
				$str .= ($i == 0 ? strtolower($funArr[$i]) : ucfirst($funArr[$i]));
			}

			$fun = (method_exists($this, $str . ucfirst($suffix)) ? array($this, $str . ucfirst($suffix)) : (method_exists($this, $str) ? array($this, $str) : array($this, $fun)));
		}

		if (($type == 'c') && !method_exists($fun[0], $fun[1])) {
			E($fun[1] . ' undefined');
		}

		if ((($type == 'f') || ($type == 'u')) && !function_exists($fun)) {
			E($fun . ' undefined');
		}

		return array($type, $fun);
	}

	private function groupByHandler($proRules, $handler)
	{
		$group = array();

		for ($i = 0; $i < count($proRules); $i++) {
			if ($proRules[$i][3] == $handler) {
				$group[] = $proRules[$i];
			}
		}

		return $group;
	}

	public function getExistPart($formData, $map = true)
	{
		$formData = ($map ? $this->processingMap($formData) : $formData);
		$data = array();
		$fields = $this->getDbFields();

		for ($i = 0; $i < count($fields); $i++) {
			if (array_key_exists($fields[$i], $formData)) {
				$data[$fields[$i]] = $formData[$fields[$i]];
			}
		}

		return $data;
	}

	public function setMap($map)
	{
		$this->_map = array_merge($this->_map, $map);
	}

	public function setStep($step = '')
	{
		$this->_step = is_string($step) ? explode(',', $step) : $step;
		return $this;
	}

	public function getErrorCode()
	{
		return $this->errorCode;
	}

	public function getError()
	{
		return parent::getError();
	}

	protected function getAllowRules($allow)
	{
		$arr = array();

		if (!empty($allow)) {
			$allow = (is_array($allow) ? $allow : explode(',', $allow));
			$fields = $this->getDbFields();
			$deny = array();

			for ($i = 0; $i < count($fields); $i++) {
				if (!in_array($fields[$i], $allow)) {
					$deny[] = $fields[$i];
				}
			}

			$arr = $this->getDenyRules($deny);
		}

		return $arr;
	}

	protected function getDenyRules($deny)
	{
		$arr = array();

		if (!empty($deny)) {
			$deny = (is_array($deny) ? $deny : explode(',', $deny));

			for ($i = 0; $i < count($deny); $i++) {
				$arr[] = array($deny[$i], NULL, NULL, $this->denyInfo, self::FIELD_EXIST);
			}

			$arr = $this->getValidateRules($arr);
		}

		return $arr;
	}

	protected function getMustRules($must)
	{
		$arr = array();

		if (!empty($must)) {
			$must = (is_array($must) ? $must : explode(',', $must));

			for ($i = 0; $i < count($must); $i++) {
				$arr[] = array($must[$i], NULL, NULL, $this->mustInfo, self::FIELD_NOT_EXIST);
			}

			$arr = $this->getValidateRules($arr);
		}

		return $arr;
	}

	protected function getValidateRules($validate)
	{
		$arr = array();

		if (!empty($validate)) {
			for ($i = 0; $i < count($validate); $i++) {
				$validate[$i][4] = isset($validate[$i][4]) ? $validate[$i][4] : self::FIELD_EXIST;
				$arr[] = array($validate[$i][0], $validate[$i][1], $validate[$i][2], 'validate', $validate[$i][3], $validate[$i][4]);
			}
		}

		return $arr;
	}

	protected function getIgnoreRules($ignore)
	{
		$arr = array();

		if (!empty($ignore)) {
			$ignore = (is_array($ignore) ? $ignore : explode(',', $ignore));

			for ($i = 0; $i < count($ignore); $i++) {
				$arr[] = array($ignore[$i], NULL, NULL, 'ignore', NULL, self::VALUE_EMPTY);
			}
		}

		return $arr;
	}

	protected function getAssignRules($assign)
	{
		$arr = array();

		if (!empty($assign)) {
			for ($i = 0; $i < count($assign); $i++) {
				$assign[$i][3] = isset($assign[$i][3]) ? $assign[$i][3] : self::FIELD_EXIST;
				$arr[] = array($assign[$i][0], $assign[$i][1], $assign[$i][2], 'assign', NULL, $assign[$i][3]);
			}
		}

		return $arr;
	}

	protected function validateHandler($result, $field, $value, $formData, $param)
	{
		if ($result) {
			return $formData;
		}

		if (is_array($field)) {
			$desc = array();
			$tmp = array();

			for ($i = 0; $i < count($field); $i++) {
				$falseName = $this->getMapField($field[$i], false);
				$tmpDesc = $this->getFieldDesc($field[$i]);
				$desc[] = $tmpDesc ? $tmpDesc : ($falseName ? $falseName : $field[$i]);
				$tmp[] = $value[$field[$i]];
			}

			$value = $tmp;
		}
		else {
			$falseName = $this->getMapField($field, false);
			$tmpDesc = $this->getFieldDesc($field);
			$desc = ($tmpDesc ? $tmpDesc : ($falseName ? $falseName : $field));
		}

		$param = $this->parseValidateError($param, '$desc', $desc);
		$param = $this->parseValidateError($param, '$field', $field);
		$param = $this->parseValidateError($param, '$value', $value);
		$this->error = str_replace(array('{$actName}', '{$actDesc}'), array($this->actName, $this->actDesc), $param);
		return false;
	}

	private function parseValidateError($error, $search, $replace)
	{
		if (is_array($replace)) {
			$error = str_replace('{' . $search . '}', implode('、', $replace), $error);

			for ($i = 0; $i < count($replace); $i++) {
				$error = str_replace('{' . $search . '[' . $i . ']}', $replace[$i], $error);
			}
		}
		else {
			$error = str_replace('{' . $search . '}', $replace, $error);
		}

		return $error;
	}

	protected function ignoreHandler($result, $field, $value, $formData, $param)
	{
		$field = (is_array($field) ? $field : array($field));

		for ($i = 0; $i < count($field); $i++) {
			unset($formData[$field[$i]]);
		}

		return $formData;
	}

	protected function assignHandler($result, $field, $value, $formData, $param)
	{
		$merge = array();
		$arr = array();
		$result = (is_array($result) ? $result : array($result));

		foreach ($result as $key => $val) {
			if (is_string($key)) {
				$merge[$key] = $val;
			}

			if (is_int($key)) {
				$arr[] = $val;
			}
		}

		$field = (is_array($field) ? $field : array($field));

		for ($i = 0; $i < count($field); $i++) {
			if (isset($arr[$i])) {
				$formData[$field[$i]] = $arr[$i];
			}
		}

		return array_merge($formData, $merge);
	}

	protected function regexCallback($value, $field, $formData, $param)
	{
		$list = C('REGEX_PATTERN');
		$param = (!empty($list) && isset($list[$param]) ? $list[$param] : $param);
		return preg_match($param, $value);
	}

	protected function notRegexCallback($value, $field, $formData, $param)
	{
		return !$this->regexCallback($value, $field, $formData, $param);
	}

	protected function confirmCallback($value, $field, $formData, $param)
	{
		return $value === $formData[$param];
	}

	protected function compareCallback($value, $field, $formData, $param)
	{
		$result = false;
		preg_match('/^([\\>|\\<|\\!]?\\={0,2})(.*)$/', $param, $arr);
		$arr[1] = empty($arr[1]) ? '==' : $arr[1];
		eval ('$result=($value' . $arr[1] . '$arr[2]);');
		return $result;
	}

	protected function inCallback($value, $field, $formData, $param)
	{
		$param = (is_array($param) ? $param : explode(',', $param));
		return in_array($value, $param);
	}

	protected function notInCallback($value, $field, $formData, $param)
	{
		$param = (is_array($param) ? $param : explode(',', $param));
		return !in_array($value, $param);
	}

	protected function lengthCallback($value, $field, $formData, $param)
	{
		return $this->getRangeResult(mb_strlen($value, 'utf-8'), $param);
	}

	protected function strlenCallback($value, $field, $formData, $param)
	{
		return $this->getRangeResult(strlen($value), $param);
	}

	protected function betweenCallback($value, $field, $formData, $param)
	{
		return $this->getRangeResult($value, $param);
	}

	protected function notBetweenCallback($value, $field, $formData, $param)
	{
		return $this->getRangeResult($value, $param, true);
	}

	protected function expireCallback($value, $field, $formData, $param)
	{
		return $this->getRangeResult(NOW_TIME, $param);
	}

	protected function timeRangeCallback($value, $field, $formData, $param)
	{
		$value = (is_numeric($value) ? $value : strtotime($value));
		return $this->getRangeResult($value, $param);
	}

	protected function ipAllowCallback($value, $field, $formData, $param)
	{
		$param = (is_array($param) ? $param : explode(',', $param));
		return in_array(get_client_ip(), $param);
	}

	protected function ipDenyCallback($value, $field, $formData, $param)
	{
		$param = (is_array($param) ? $param : explode(',', $param));
		return !in_array(get_client_ip(), $param);
	}

	protected function uniqueCallback($value, $field, $formData, $param)
	{
		$value = (is_array($value) ? $value : array($field => $value));
		$field = (is_array($field) ? $field : array($field));

		for ($i = 0; $i < count($field); $i++) {
			$where[$field[$i]] = $value[$field[$i]];
		}

		$num = $this->where($where)->count();
		return $num == 0;
	}

	protected function excUniqueCallback($value, $field, $formData, $param)
	{
		$value = (is_array($value) ? $value : array($field => $value));
		$field = (is_array($field) ? $field : array($field));

		for ($i = 0; $i < count($field); $i++) {
			$where[$field[$i]] = $value[$field[$i]];
		}

		$pks = $this->getActionPks($formData);

		if (!empty($pks)) {
			$tmp = array();

			foreach ($pks as $key => $value) {
				$tmp[$key] = array('neq', $value);
			}

			$tmp['_logic'] = 'or';
			$where[] = $tmp;
		}

		$num = $this->where($where)->count();
		return $num == 0;
	}

	protected function leastCallback($value, $filed, $formData, $param)
	{
		$result = false;

		foreach ($value as $key => $val) {
			if (!empty($val)) {
				$result = true;
			}
		}

		return $result;
	}

	protected function fieldCallback($value, $field, $formData, $param)
	{
		return $formData[$param];
	}

	protected function stringCallback($value, $field, $formData, $param)
	{
		return $param;
	}

	protected function configCallback($value, $field, $formData, $param)
	{
		return C($param);
	}

	private function getRangeResult($value, $range, $not = false)
	{
		if (is_array($range) || (strpos($range, ',') !== false)) {
			list($min, $max) = is_array($range) ? $range : explode(',', $range);

			if ($not) {
				$result = false;
				if (($min !== '') && !is_null($min) && ($value < $min)) {
					$result = true;
				}

				if (($max !== '') && !is_null($max) && ($max < $value)) {
					$result = true;
				}
			}
			else {
				$result = true;
				if (($min !== '') && !is_null($min) && ($value < $min)) {
					$result = false;
				}

				if (($max !== '') && !is_null($max) && ($max < $value)) {
					$result = false;
				}
			}
		}
		else {
			$result = ($not ? $value != $range : $value == $range);
		}

		return $result;
	}
}

?>
