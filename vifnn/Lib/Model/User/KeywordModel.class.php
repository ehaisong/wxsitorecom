<?php

class KeywordModel extends XModel
{
	public function addKeyword($id, $module, $keyword = '', $precisions = 0, $token = NULL)
	{
		$token = (isset($token) ? $token : session('token'));
		$keyword = trim(trim($keyword), ',');
		$this->where(array('pid' => $id, 'token' => $token, 'module' => $module))->delete();
		$data['pid'] = $id;
		$data['module'] = $module;
		$data['token'] = $token;
		$flag1 = strpos($keyword, ',');
		$flag2 = strpos($keyword, ' ');
		if (($flag1 === false) && ($flag2 === false)) {
			$pk = explode('|', $keyword);

			if (count($pk) == 2) {
				$data['precisions'] = $pk[1];
				$data['keyword'] = $pk[0];
			}
			else {
				$data['precisions'] = $precisions;
				$data['keyword'] = $keyword;
			}

			$this->add($data);
		}
		else if ($flag1 === false) {
			$keyword = explode(' ', $keyword);

			foreach ($keyword as $k => $v) {
				$pk = explode('|', $v);

				if (count($pk) == 2) {
					$data['precisions'] = $pk[1];
					$data['keyword'] = $pk[0];
				}
				else {
					$data['precisions'] = $precisions;
					$data['keyword'] = $v;
				}

				$this->add($data);
			}
		}
		else {
			$keyword = explode(',', $keyword);

			foreach ($keyword as $k => $v) {
				$pk = explode('|', $v);

				if (count($pk) == 2) {
					$data['precisions'] = $pk[1];
					$data['keyword'] = $pk[0];
				}
				else {
					$data['precisions'] = $precisions;
					$data['keyword'] = $v;
				}

				$this->add($data);
			}
		}
	}

	public function clearKeyword($id, $module, $token = NULL)
	{
		$token = (isset($token) ? $token : session('token'));
		$this->where(array('pid' => $id, 'token' => $token, 'module' => $module))->delete();
	}
}

?>
