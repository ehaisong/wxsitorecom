<?php

class UserfollowModel extends Model
{
	public function isSub($token, $openid)
	{
		$where['token'] = $token;
		$where['wecha_id'] = $openid;
		$issub = S('sub_' . $token . '_' . $openid);

		if (empty($issub)) {
			$issubData = $this->field('`issub`')->where('`followid` = \'' . $token . '_' . $openid . '\'')->find();

			if (empty($issubData)) {
				$issubData = M('Userinfo')->field('`issub`')->where('`wecha_id` = \'' . $openid . '\' AND `token` = \'' . $token . '\'')->find();

				if (empty($issubData)) {
					$issubData = M('Userinfo')->field('`issub`')->where('`fakeopenid` = \'' . $openid . '\' AND `token` = \'' . $token . '\'')->find();
				}

				if (!empty($issubData)) {
					$issub = $issubData['issub'];
					$this->add(array('issub' => $issub, 'followid' => $token . '_' . $openid));
				}
			}
			else {
				$issub = $issubData['issub'];
			}

			$issub = ($issub == 1 ? 2 : 1);
			S('sub_' . $token . '_' . $openid, $issub, 1200);
		}

		return $issub == 2 ? true : false;
	}

	public function setsubscribe($token, $wechaId, $issub = 1, $userFollow = 1)
	{
		if (!empty($token) && $wechaId) {
			if ($userFollow == 1) {
				$userFollow = $this->where('`followid`=\'' . $token . '_' . $wechaId . '\'')->find();
			}

			if ($userFollow) {
				$res = $this->where(array('followid' => $token . '_' . $wechaId))->setField('issub', $issub);
			}
			else {
				$dataFollow['issub'] = $issub;
				$dataFollow['followid'] = $token . '_' . $wechaId;
				$res = $this->add($dataFollow);
			}

			S('sub_' . $token . '_' . $wechaId, NULL);
			return $res;
		}
	}
}

?>
