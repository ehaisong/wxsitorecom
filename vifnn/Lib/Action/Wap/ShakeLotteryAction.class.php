<?php


class ShakeLotteryAction extends LotteryPrizeBaseAction
{
	public function index()
	{
		if ($this->action_id == '' || $this->token == '') {
			$this->error('非法操作');
		}
		$actioninfo = S('shakelottery_' . $this->token . '_' . $this->action_id);
		if (empty($actioninfo)) {
			$actioninfo = M('shakelottery')->where(array('id' => $this->action_id))->find();
			S('shakelottery_' . $this->token . '_' . $this->action_id, $actioninfo);
		}
		if (empty($actioninfo)) {
			$this->error('活动不存在');
		} else {
			if ($actioninfo['status'] == 0) {
				$this->error('活动已经关闭');
			}
		}
		$userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		if (S($this->token . '_prizelist_' . $this->action_id) != '') {
			$prize = S($this->token . '_prizelist_' . $this->action_id);
		} else {
			$prize = M('shakelottery_prize')->where(array('aid' => $this->action_id, 'token' => $this->token))->order('prizenum asc')->select();
			if (!empty($prize)) {
				S($this->token . '_prizelist_' . $this->action_id, $prize);
			}
		}
		$stat = $this->public_notice($actioninfo, $userinfo['tel']);
		$user_id = $this->AddPlayer($stat, $userinfo);
		$this->clear_shake_day(array('token' => $this->token, 'action_id' => $this->action_id, 'wecha_id' => $this->wecha_id));
		$this->assign('prize', $prize);
		$this->assign('user_id', $user_id);
		$actioninfo['remind_link'] = $this->getLink($actioninfo['remind_link']);
		$actioninfo['custom_sharetitle'] = $actioninfo['custom_sharetitle'] != '' ? str_replace('{{活动名称}}', $actioninfo['action_name'], $actioninfo['custom_sharetitle']) : '我正在参加“' . $actioninfo['action_name'] . '”活动，快来参加轻松赢取丰厚奖品吧！';
		$actioninfo['custom_sharedsc'] = $actioninfo['custom_sharedsc'] != '' ? str_replace('{{活动名称}}', $actioninfo['action_name'], $actioninfo['custom_sharedsc']) : $actioninfo['reply_content'];
		$this->assign('actioninfo', $actioninfo);
		$this->display();
	}
	private function AddPlayer($stat = false, $userinfo = array())
	{
		if ($this->wecha_id != '') {
			$shakelottery_user_name = 'shakelottery_user_' . $this->action_id . '_' . $this->token . '_' . $this->wecha_id;
			$player = S($shakelottery_user_name);
			if (is_null($player) || $player === false) {
				$player = M('shakelottery_users')->where(array('aid' => $this->action_id, 'token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
				S($shakelottery_user_name, $player ? $player : array());
			}
			if (empty($player) && $stat == true) {
				$data = array();
				$data['aid'] = $this->action_id;
				$data['total_shakes'] = 0;
				$data['today_shakes'] = 0;
				$data['wecha_id'] = $this->wecha_id;
				$data['wecha_name'] = !empty($userinfo['wechaname']) ? $userinfo['wechaname'] : '匿名用户';
				$data['wecha_pic'] = !empty($userinfo['portrait']) ? $userinfo['portrait'] : $this->siteUrl . '/tpl/User/default/common/images/portrait.jpg';
				$data['phone'] = !empty($userinfo['tel']) ? $userinfo['tel'] : 'no';
				$data['addtime'] = $_SERVER['REQUEST_TIME'];
				$data['token'] = $this->token;
				$addid = M('shakelottery_users')->add($data);
				S($shakelottery_user_name, NULL);
				return $addid;
			} else {
				if (!empty($player) && $stat == true) {
					$savedata = array('phone' => $userinfo['tel'], 'wecha_pic' => $userinfo['portrait'], 'wecha_name' => $userinfo['wechaname']);
					if ($savedata['phone'] != $player['phone'] || $savedata['wecha_pic'] != $player['wecha_pic'] || $savedata['wecha_name'] != $player['wecha_name']) {
						$saveRes = M('shakelottery_users')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'aid' => $this->action_id))->save($savedata);
						if ($saveRes) {
							S($shakelottery_user_name, array_merge($player, $savedata));
						}
					}
					return $player['id'];
				}
			}
		}
	}
	public function AjaxReturnPrize()
	{
		$id = (int) $_GET['id'];
		$token = trim($_GET['token']);
		if (empty($id) || empty($token)) {
			echo '{"status":"errormsg","msg":"抽奖失败"}';
			die;
		}
		$actioninfo = S('shakelottery_' . $token . '_' . $id);
		if (empty($actioninfo)) {
			$actioninfo = M('shakelottery')->where(array('id' => $id, 'token' => $token))->find();
			S('shakelottery_' . $token . '_' . $id, $actioninfo);
		}
		if (empty($actioninfo)) {
			echo '{"status":"errormsg","msg":"抽奖活动不存在"}';
			die;
		}
		if ($actioninfo['other_source'] == 'scene') {
			if ($actioninfo['scene_state'] == 0) {
				echo '{"status":"errormsg","msg":"抽奖活动未开始"}';
				die;
			}
		} else {
			if ($actioninfo['status'] == 0) {
				echo '{"status":"errormsg","msg":"抽奖活动未开启"}';
				die;
			}
		}
		if ($_SERVER['REQUEST_TIME'] < $actioninfo['starttime']) {
			echo '{"status":"errormsg","msg":"抽奖活动未开始,请注意页面的倒计时"}';
			die;
		} else {
			if ($actioninfo['endtime'] < $_SERVER['REQUEST_TIME']) {
				echo '{"status":"errormsg","msg":"抽奖活动已结束"}';
				die;
			}
		}
		$shakelottery_user_name = 'shakelottery_user_' . $id . '_' . $token . '_' . $this->wecha_id;
		$player = S($shakelottery_user_name);
		if (is_null($player) || $player === false) {
			$player = M('shakelottery_users')->where(array('aid' => $id, 'token' => $token, 'wecha_id' => $this->wecha_id))->find();
			S($shakelottery_user_name, $player ? $player : array());
		}
		if (empty($player)) {
			echo '{"status":"errormsg","msg":"抽奖失败"}';
			die;
		}
		$todaytime = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
		if (0 < $actioninfo['is_limitwin']) {
			$totay_lottery_count = M('shakelottery_record')->where(array('user_id' => $player['id'], 'iswin' => 1, 'shaketime' => array('gt', $todaytime)))->count();
			if ($actioninfo['is_limitwin'] <= $totay_lottery_count) {
				echo '{"status":"errormsg","msg":"您今天的中奖次数超限，请明天再来吧"}';
				die;
			}
		}
		if (0 < $actioninfo['everydaytimes'] && $actioninfo['everydaytimes'] <= $player['today_shakes']) {
			echo '{"status":"errormsg","msg":"您今天的摇奖次数已经超限,请明天再来吧"}';
			die;
		}
		if ($actioninfo['totaltimes'] <= $player['total_shakes']) {
			echo '{"status":"errormsg","msg":"您的摇奖次数已经用完"}';
			die;
		}
		if (0 < $actioninfo['timespan']) {
			$lottery_record = M('shakelottery_record')->where(array('user_id' => $player['id'], 'iswin' => 1, 'shaketime' => array('gt', $todaytime)))->order('shaketime desc')->find();
			if (time() - $lottery_record['shaketime'] < $actioninfo['timespan'] * 60) {
				$prize = $this->LotteryPrize(true);
			} else {
				$prize = $this->LotteryPrize(false);
			}
		} else {
			$prize = $this->LotteryPrize(false);
		}
		$iswin = $prize['status'] == 'success' ? 1 : 0;
		$data = array();
		if ($iswin == 1) {
			$data['prizeid'] = $prize['msg']['id'];
			$data['prize_type'] = $prize['msg']['prize_type'];
			$data['prizename'] = $prize['msg']['prizename'];
		}
		$data['iswin'] = $iswin;
		$data['aid'] = $id;
		$data['user_id'] = $player['id'];
		$data['shaketime'] = $_SERVER['REQUEST_TIME'];
		$data['isaccept'] = 0;
		$data['accepttime'] = 0;
		$data['phone'] = $player['phone'];
		$data['wecha_name'] = $player['wecha_name'];
		$data['token'] = $token;
		$record_add = M('shakelottery_record')->add($data);
		$updateData = array('total_shakes' => array('exp', 'total_shakes+1'), 'today_shakes' => array('exp', 'today_shakes+1'));
		$player_update = M('shakelottery_users')->where(array('id' => $player['id']))->save($updateData);
		if ($player_update) {
			S($shakelottery_user_name, NULL);
		}
		M('shakelottery')->where(array('id' => $id))->setInc('actual_join_number');
		if ($record_add && $player_update) {
			if ($iswin == 1) {
				if ($prize['msg']['prize_type'] == 2) {
					$status = $this->SendHongbao($prize['msg'], $record_add, $player['wecha_name']);
					if ($status['status'] == 'SUCCESS' && $status) {
						echo '{"status":"success","prizename":"' . $prize['msg']['prizename'] . '","prizeimg":"' . $prize['msg']['prizeimg'] . '"}';
						die;
					} else {
						M('shakelottery_record')->where(array('id' => $record_add))->save(array('iswin' => 0, 'prize_type' => 0, 'prizename' => '', 'prizeid' => 0));
						M('shakelottery_prize')->where(array('id' => $data['prizeid']))->save(array('expendnum' => array('exp', 'expendnum-1')));
						echo '{"status":"error","msg":"摇奖失败"}';
						die;
					}
				} else {
					echo '{"status":"success","prizename":"' . $prize['msg']['prizename'] . '","prizeimg":"' . $prize['msg']['prizeimg'] . '"}';
					die;
				}
			} else {
				echo '{"status":"error","msg":"' . $prize['msg'] . '"}';
				die;
			}
		} else {
			echo '{"status":"errormsg","msg":"摇奖失败"}';
			die;
		}
	}
	public function LotteryPrize($setting = false)
	{
		if ($setting) {
			return array('status' => 'fail', 'msg' => '继续努力哦');
		}
		$shakePrize = array();
		$shakePrize = M('shakelottery')->where(array('id' => $this->action_id))->find();
		$prize = M('shakelottery_prize')->where(array('aid' => $this->action_id, 'token' => $this->token))->select();
		foreach ($prize as $k => $v) {
			$prizenum[$k] = $v['prizenum'];
			$prizeArr[$v['id']] = $v;
		}
		array_multisort($prizenum, SORT_ASC, $prize);
		$shakePrize['prizelist'] = $prize;
		$prizeid = $this->shakePrize($shakePrize);
		if ($prizeid == 0) {
			$res = array('status' => 'fail', 'msg' => '继续努力哦');
		} else {
			M('shakelottery_prize')->where(array('id' => $prizeid))->setInc('expendnum');
			$res = array('status' => 'success', 'msg' => $prizeArr[$prizeid]);
		}
		return $res;
	}
	public function LotteryMyRecord()
	{
		$aid = (int) $_POST['aid'];
		$user_id = (int) $_POST['user_id'];
		$token = trim($_POST['token']);
		$myRecord = M('shakelottery_record')->where(array('user_id' => $user_id, 'iswin' => 1))->order('shaketime desc')->select();
		if (!empty($myRecord)) {
			$html = '';
			foreach ($myRecord as $key => $value) {
				$value['isaccept'] = $value['prize_type'] == 2 ? 1 : $value['isaccept'];
				$isaccept = $value['isaccept'] == 1 ? '<i style="color:#44b549;">已领取</i>' : '<i style="color:#C63535;">未领取</i>';
				$html .= '<li>&nbsp;' . mb_substr($value['prizename'], 0, 10, 'UTF-8') . '&nbsp;' . $isaccept . '<span class="font-c63535">' . date('Y-m-d H:i', $value['shaketime']) . '</span></li>';
			}
			echo $html;
			die;
		} else {
			echo 'fail';
			die;
		}
	}
	public function LotteryRecord()
	{
		$aid = (int) $_POST['aid'];
		$user_id = (int) $_POST['user_id'];
		$token = trim($_POST['token']);
		$record_nums = $_GET['record_nums'] ? (int) $_GET['record_nums'] : 20;
		$Records = M('shakelottery_record')->where(array('aid' => $aid, 'iswin' => 1))->limit(0, $record_nums)->order('shaketime desc')->select();
		if (!empty($Records)) {
			$html = '';
			foreach ($Records as $key => $value) {
				if ($value['user_id'] != $user_id) {
					$str = '';
					if ($value['phone'] == '' || $value['phone'] == 'no') {
						$str = '<li>恭喜<label style="color:#c63535;">' . $value['wecha_name'] . '</label>&nbsp;&nbsp;&nbsp;摇中' . mb_substr($value['prizename'], 0, 10, 'UTF-8') . '</li>';
					} else {
						$str = '<li>恭喜<label style="color:#c63535;">' . substr_replace($value['phone'], '****', 3, 4) . '</label>&nbsp;&nbsp;&nbsp;摇中' . mb_substr($value['prizename'], 0, 10, 'UTF-8') . '</li>';
					}
					$html .= $str;
				}
			}
			echo $html;
			die;
		} else {
			echo 'fail';
			die;
		}
	}
	public function public_notice($action_info = '', $tel = '')
	{
		$stat = true;
		if ($action_info['is_follow'] == 0 && $this->isSubscribe() == false) {
			$follow_msg = !empty($action_info['follow_msg']) ? $action_info['follow_msg'] : '';
			$custom_url = !empty($action_info['custom_follow_url']) ? $action_info['custom_follow_url'] : '';
			$custom_btn_msg = !empty($action_info['follow_btn_msg']) ? $action_info['follow_btn_msg'] : '';
			$this->assign('notice_content', 'no_follow');
			$this->memberNotice($follow_msg, 1, $custom_url, $custom_btn_msg);
			$stat = false;
		} else {
			if ($action_info['is_register'] == 1 && $tel == '') {
				$custom_register_msg = !empty($action_info['register_msg']) ? $action_info['register_msg'] : '';
				$this->assign('notice_content', 'no_register');
				$this->memberNotice($custom_register_msg);
				$stat = false;
			} else {
				$this->assign('notice_content', '');
			}
		}
		return $stat;
	}
	private function clear_shake_day($cache_parameter = '')
	{
		$token = $cache_parameter['token'];
		$action_id = $cache_parameter['action_id'];
		$wecha_id = $cache_parameter['wecha_id'];
		if ($token != '' && $action_id != '' && $wecha_id != '') {
			if (S($token . '_' . $action_id . '_' . $wecha_id . '_shakelottery_day') == '') {
				$today_time = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
				$evening_time = strtotime(date('Y-m-d 23:59:59', $_SERVER['REQUEST_TIME']));
				$cache_time = $evening_time - $_SERVER['REQUEST_TIME'];
				$shakelottery_user_name = 'shakelottery_user_' . $action_id . '_' . $token . '_' . $wecha_id;
				$where = 'aid = ' . $action_id . ' and token = \'' . $token . '\' and wecha_id = \'' . $wecha_id . '\'';
				$num = M('shakelottery_users')->where($where)->save(array('today_shakes' => 0));
				if ($num) {
					S($shakelottery_user_name, NULL);
				}
				S($token . '_' . $action_id . '_' . $wecha_id . '_shakelottery_day', 1, $cache_time);
			}
		}
	}
	private function SendHongbao($prize = array(), $aid = '', $wecha_name = '')
	{
		$hongbao_configure = unserialize($prize['hongbao_configure']);
		$config = array();
		$config['send_name'] = $prize['provider'];
		$config['wishing'] = $hongbao_configure['wishing'];
		$config['act_name'] = $prize['prizename'];
		$config['remark'] = $prize['prizename'];
		$config['token'] = $this->token;
		$config['openid'] = $this->wecha_id;
		$config['money'] = $hongbao_configure['money'];
		if ($hongbao_configure['hb_type'] == 1) {
			$config['nick_name'] = $prize['provider'];
			$hb = new Hongbao($config);
			$res = json_decode($hb->send(), true);
		} else {
			if ($hongbao_configure['hb_type'] == 2) {
				$config['total_num'] = $hongbao_configure['group_nums'];
				$hb = new Hongbao($config);
				$res = json_decode($hb->FissionSend(), true);
			} else {
				return false;
			}
		}
		if ($res['status'] == 'SUCCESS') {
			$record = array();
			$record['aid'] = $aid;
			$record['mch_billno'] = $res['mch_billno'];
			$record['fans_id'] = $this->wecha_id;
			$record['fans_nickname'] = $wecha_name;
			$record['money'] = $hongbao_configure['money'];
			$record['hb_type'] = $hongbao_configure['hb_type'];
			$record['token'] = $config['token'];
			M('shakelottery_hbrecord')->add($record);
		}
		return $res;
	}
	private function scene_start($id)
	{
		$scene_id = M('Wechat_scene')->where(array('is_open' => '1', 'token' => $this->token, 'red_packet_id' => $id))->getField('id');
		$cache = S('scene_' . $this->token . '_' . $scene_id);
		return $cache['type'] == 'red_packet';
	}
}