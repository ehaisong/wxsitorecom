<?php

class ExtensionRelationModel extends XModel
{
	public function follow($uid, $aid, $token, $myWechaId)
	{
		$session_token = session('token');

		if (empty($session_token)) {
			session('token', $token);
		}

		$actInfo = (is_array($aid) ? $aid : array());
		$aid = (is_array($aid) ? $aid['id'] : $aid);
		$myInfo = M('Userinfo')->where(array('token' => $token, 'wecha_id' => $myWechaId))->find();
		$myUid = $myInfo['id'];

		if ($uid == $myUid) {
			return true;
		}

		$extensionUser = D('ExtensionUser');
		$where['uid'] = $uid;
		$where['aid'] = $aid;
		$where['token'] = $token;
		$userInfo = $extensionUser->where($where)->find();

		if (empty($userInfo)) {
			$this->error = '推广用户不存在';
			return false;
		}

		$wecha_id = $userInfo['wecha_id'];
		$promoter = M('Userinfo')->where(array('token' => $token, 'wecha_id' => $wecha_id))->find();

		if (empty($promoter)) {
			$this->error = '推广用户不存在';
			return false;
		}

		if (empty($actInfo)) {
			$extensionModel = new ExtensionActivityModel();
			$actInfo = $extensionModel->where(array('id' => $aid, 'token' => $token, 'status' => '1'))->find();
		}

		if (empty($actInfo)) {
			$this->error = '推广海报不存在或者已关闭';
			return false;
		}

		$this->startTrans();
		$time = time();
		$data['promoter'] = $uid;
		$data['follower'] = $myUid;
		$data['aid'] = $aid;
		$num = $this->where($data)->count();

		if ($num <= 0) {
			$data['follow_time'] = $time;
			$data['token'] = $token;
			$relationRes = $this->add($data);

			if (!$relationRes) {
				$this->rollback();
				return false;
			}

			if (($actInfo['start_time'] <= $time) && ($time < $actInfo['end_time'])) {
				if (!$this->reward($promoter, $actInfo, $myInfo)) {
					$this->rollback();
					return false;
				}
			}
		}

		$this->commit();
		return true;
	}

	private function reward($promoter, $actInfo, $myInfo)
	{
		Log::write('Extension:一级推广人,id:' . $promoter['id'] . ',aid:' . $actInfo['id'] . ',getcardtime:' . $promoter['getcardtime'], Log::INFO);
		$promoter2 = array();
		$where['follower'] = $promoter['id'];
		$where['aid'] = $actInfo['id'];
		$relationInfo = $this->where($where)->field('promoter')->find();
		$token = $actInfo['token'];

		if (!empty($relationInfo)) {
			$extensionUser = D('ExtensionUser');
			$userInfo = $extensionUser->where(array('aid' => $actInfo['id'], 'uid' => $relationInfo['promoter'], 'token' => $token))->find();

			if (!empty($userInfo)) {
				$promoter2 = M('Userinfo')->where(array('token' => $token, 'wecha_id' => $userInfo['wecha_id']))->find();
			}
		}

		$reward_lv1_score = $actInfo['reward_lv1_score'];
		$reward_lv2_score = $actInfo['reward_lv2_score'];
		$reward_lv1_cash = $actInfo['reward_lv1_cash'];
		$reward_lv2_cash = $actInfo['reward_lv2_cash'];
		$consume_score = $actInfo['consume_score'];
		$consume_cash = $actInfo['consume_cash'];
		$max_score = $actInfo['max_score'];
		$max_cash = $actInfo['max_cash'];
		if ((0 < $reward_lv1_score) && (($consume_score + $reward_lv1_score) <= $max_score) && $promoter['getcardtime']) {
			if (!$this->rewardScore($actInfo, $promoter, $reward_lv1_score, $myInfo)) {
				return false;
			}

			$consume_score += $reward_lv1_score;
		}

		if ((0 < $reward_lv1_cash) && (($consume_cash + $reward_lv1_cash) <= $max_cash)) {
			if (!$this->rewardCash($actInfo, $promoter, $reward_lv1_cash, $myInfo)) {
				return false;
			}

			$consume_cash += $reward_lv1_cash;
		}

		if (!empty($promoter2)) {
			Log::write('Extension:二级推广人,id:' . $promoter2['id'] . ',aid:' . $actInfo['id'] . ',getcardtime:' . $promoter2['getcardtime'], Log::INFO);
			if ((0 < $reward_lv2_score) && (($consume_score + $reward_lv2_score) <= $max_score) && $promoter2['getcardtime']) {
				if (!$this->rewardScore($actInfo, $promoter2, $reward_lv2_score, $myInfo)) {
					return false;
				}

				$consume_score += $reward_lv2_score;
			}

			if ((0 < $reward_lv2_cash) && (($consume_cash + $reward_lv2_cash) <= $max_cash)) {
				if (!$this->rewardCash($actInfo, $promoter2, $reward_lv2_cash, $myInfo)) {
					return false;
				}

				$consume_cash += $reward_lv2_cash;
			}
		}

		if (($consume_cash != $actInfo['consume_cash']) || ($consume_score != $actInfo['consume_score'])) {
			$extensionModel = new ExtensionActivityModel();
			$saveConsume = $extensionModel->where(array('id' => $actInfo['id']))->save(array('consume_cash' => $consume_cash, 'consume_score' => $consume_score));

			if (!$saveConsume) {
				return false;
			}
		}

		return true;
	}

	private function rewardScore($actInfo, $info, $score, $myInfo)
	{
		$result = M('Userinfo')->where(array('id' => $info['id']))->setInc('total_score', abs($score));

		if (!$result) {
			return false;
		}

		$card = M('Member_card_create')->where(array('token' => $actInfo['token'], 'wecha_id' => $info['wecha_id']))->field('cardid')->find();

		if (empty($card)) {
			return false;
		}

		$recordArr['token'] = $actInfo['token'];
		$recordArr['wecha_id'] = $info['wecha_id'];
		$recordArr['cardid'] = $card['cardid'];
		$recordArr['expense'] = 0;
		$recordArr['time'] = time();
		$recordArr['cat'] = 9;
		$recordArr['staff_id'] = 0;
		$recordArr['score'] = abs($score);
		$recordArr['notes'] = '推广海报奖励';
		$record = M('Member_card_use_record')->add($recordArr);

		if (!$record) {
			return false;
		}

		$extensionUser = D('ExtensionUser');
		$result2 = $extensionUser->where(array('uid' => $info['id'], 'aid' => $actInfo['id']))->setInc('score', abs($score));

		if (!$result2) {
			return false;
		}

		$model = new templateNews();
		$tempKey = 'TM00335';
		$site_url = C('site_url');
		$dataArr = array('href' => $site_url . U('Wap/Card/expense', array('token' => $actInfo['token'], 'cardid' => $card['cardid'], 'month' => (int) date('m'))), 'wecha_id' => $info['wecha_id'], 'first' => '您有新积分到账，详情如下：', 'account' => $info['wechaname'], 'time' => date('Y年m月d日H时i分s秒'), 'type' => '您的好友' . short($myInfo['wechaname'], 9) . '关注了您的推广海报！', 'creditChange' => '到账', 'number' => abs($score), 'creditName' => '账户积分', 'amount' => $info['total_score'] + abs($score), 'remark' => '您可以进入会员卡中心，随时查询账户余额。');
		$sendRes = $model->sendTempMsg($tempKey, $dataArr);
		return true;
	}

	private function rewardCash($actInfo, $info, $cash, $myInfo)
	{
		$config = array();
		$config['send_name'] = short($actInfo['company'], 10);
		$config['wishing'] = '您的好友:' . short($myInfo['wechaname'], 9) . '关注了您的推广海报！';
		$config['act_name'] = short($actInfo['name'], 10);
		$config['remark'] = '每增加一个人扫描海报您将会收到一个红包奖励';
		$config['token'] = $actInfo['token'];
		$config['openid'] = $info['wecha_id'];
		$config['money'] = $cash / 100;
		$config['nick_name'] = '推广奖励红包';
		$hb = new Hongbao($config);
		$res = json_decode($hb->send(), true);
		Log::write('Extension:发送红包,token:' . $actInfo['token'] . ',aid:' . $actInfo['id'] . ',wecha_id:' . $info['wecha_id'] . ',cash:' . $cash . ',result:' . var_export($res, true), Log::INFO);

		if ($res['status'] != 'SUCCESS') {
			return false;
		}

		$extensionUser = D('ExtensionUser');
		$result2 = $extensionUser->where(array('uid' => $info['id'], 'aid' => $actInfo['id']))->setInc('cash', abs($cash));

		if (!$result2) {
			return false;
		}

		return true;
	}
}

?>
