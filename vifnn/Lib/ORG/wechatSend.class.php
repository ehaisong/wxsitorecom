<?php

class wechatSend
{
	public function sendRedPacket($token, $openId, $options, $sendName = '微游戏奖励', $actName = '微游戏奖励', $remark = '微游戏奖励', $wishing = '恭喜发财,大吉大利', $hbType = '1', $groupNums = '5')
	{
		$data = array('status' => false, 'msg' => '');
		if (isset($token) && isset($openId) && isset($options)) {
			$fansInfo = array('openid' => $openId);
			$options = explode('#', $options);
			$params = array('send_name' => $sendName, 'act_name' => $actName, 'remark' => $remark, 'wishing' => $wishing, 'min_money' => round($options[0]), 'max_money' => round($options[1]), 'hb_type' => $hbType, 'group_nums' => $groupNums);
			$sendStatus = $this->_sendRedPacket($fansInfo, $params, $token);

			if ($sendStatus['status']) {
				$data['status'] = true;
				$data['money'] = $sendStatus['money'];
			}
			else {
				$data['msg'] = $sendStatus['msg'];
			}
		}

		return $data;
	}

	private function _sendRedPacket($fansInfo = array(), $params = array(), $token)
	{
		$data = array('status' => false, 'msg' => '');
		if (($fansInfo['openid'] == '') || ($token == '')) {
			return $data;
		}

		if (($params['min_money'] == '') || ($params['max_money'] == '')) {
			$data['msg'] = '红包金额不可为空！';
			return $data;
		}

		$config = array('send_name' => $params['send_name'], 'wishing' => $params['wishing'], 'act_name' => $params['act_name'], 'remark' => $params['remark'], 'token' => $token, 'openid' => $fansInfo['openid'], 'money' => rand_money($params['min_money'], $params['max_money']));
		$res = array('status' => 'FALSE');

		if ($params['hb_type'] == 1) {
			$config['nick_name'] = $params['send_name'];
			$hb = new Hongbao($config);
			$res = json_decode($hb->send(), true);
		}
		else if ($params['hb_type'] == 2) {
			$config['total_num'] = $params['group_nums'];
			$hb = new Hongbao($config);
			$res = json_decode($hb->FissionSend(), true);
		}

		if ($res['status'] == 'SUCCESS') {
			$data['status'] = true;
			$data['money'] = $config['money'];
		}
		else {
			$data['msg'] = $res['msg'];
		}

		return $data;
	}

	public function sendCard($token, $wechaId, $cardId)
	{
		if ($this->_sendCard($cardId, $wechaId, $token)) {
			return true;
		}

		return false;
	}

	private function _sendCard($card_id = '', $wecha_id = '', $token = '')
	{
		if (($token == '') || ($wecha_id == '') || ($card_id == '')) {
			return false;
		}

		$thisWxUser = M('Wxuser')->field('appid,appsecret,winxintype')->where(array('token' => $token))->find();
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($thisWxUser['appid']);

		if ($access_token == '') {
			return false;
		}

		$msgtype = 'wxcard';
		$postData = '{"touser":"' . $wecha_id . '","msgtype":"' . $msgtype . '","' . $msgtype . '":{"card_id":"' . $card_id . '"}}';
		$extraUrl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
		$result_json = $this->curlGet($extraUrl, 'POST', $postData);
		$result_array = json_decode($result_json, true);

		if ($result_array['errcode'] == 0) {
			return true;
		}
		else {
			return false;
		}
	}

	public function sendScore($token, $openId, $options, $scoreType = '微游戏获赠积分')
	{
		if (isset($options) && isset($openId) && isset($token)) {
			if ($this->_sendScore($token, $openId, $options, $scoreType)) {
				return true;
			}
		}

		return false;
	}

	private function _sendScore($token, $wecha_id, $score, $scoreType)
	{
		$info = M('Userinfo')->where(array('token' => $token, 'wecha_id' => $wecha_id))->order('id DESC')->find();
		if ($info && $info['getcardtime']) {
			if ($score < 0) {
				$sharescore = M('Userinfo')->where(array('id' => $info['id']))->setDec('total_score', abs($score));
			}
			else {
				$sharescore = M('Userinfo')->where(array('id' => $info['id']))->setInc('total_score', $score);
			}

			$total_score = $info['total_score'] + $score;
			$model = new templateNews();
			$tempKey = 'TM00335';
			$dataArr = array('href' => '', 'wecha_id' => $wecha_id, 'first' => '您有新积分到账，详情如下。', 'account' => $info['wechaname'], 'time' => date('Y年m月d日H时i分s秒'), 'type' => $scoreType, 'creditChange' => '到账', 'number' => $score, 'creditName' => '账户积分', 'amount' => $total_score, 'remark' => '您可以进入会员卡中心，随时查询账户余额。');
			$model->sendTempMsg($tempKey, $dataArr);
			return true;
		}

		return false;
	}

	public function curlGet($url, $method = 'get', $data = '')
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}


?>
