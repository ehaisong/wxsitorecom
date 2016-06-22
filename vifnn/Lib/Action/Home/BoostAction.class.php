<?php


class BoostAction extends BaseAction
{
	public $token;
	public $gameConfig;
	public $uid;
	public function _initialize()
	{
		parent::_initialize();
		$this->uid = intval($_GET['uid']);
		$this->gameConfig = M('Game_config')->where(array('uid' => $this->uid))->find();
		$this->token = $this->gameConfig['token'];
	}
	public function boostarr()
	{
		if ($this->_post('uuu') == 2) {
			$jsonStrs = '{';
			if (S('boost_' . $this->_post('token') . '_' . $this->_post('id'))) {
				$comma = '';
				foreach (S('boost_' . $this->_post('token') . '_' . $this->_post('id')) as $key => $value) {
					$jsonStrs .= $comma . '"' . $key . '":"' . $value . '"';
					$comma = ',';
				}
			}
			$jsonStrs .= '}';
			echo $jsonStrs;
		}
	}
	public function tel()
	{
		$wecha_id = $this->_post('wecha_id');
		$phone = $this->_post('phone');
		$data['tel'] = $phone;
		M('Userinfo')->where(array('wecha_id' => $wecha_id))->save($data);
	}
	public function api_playuser()
	{
		$wecha_id = $_GET['openid'];
		$score = $_GET['score'];
		$boostid = intval($_GET['boostid']);
		if ($_GET['key'] == $this->gameConfig['key']) {
			$data = array('token' => $this->token, 'boostid' => $boostid, 'wecha_id' => $wecha_id, 'score' => $score, 'time' => time());
			M('game_records')->add($data);
		}
	}
	public function api_playcount()
	{
		if ($_GET['key'] == $this->gameConfig['key']) {
			M('boosts')->where(array('id' => intval($_GET['boostid'])))->setInc('playcount');
		}
	}
	public function api_user_boost()
	{
		$uid = $this->_post('uid', 'intval');
		$key = $this->_post('key', 'trim');
		$wxid = $this->_post('wxid', 'trim');
		$where = array('uid' => $uid);
		$conf = M('Game_config')->where($where)->find();
		if (empty($conf)) {
			echo '{"success":"-1","msg":"uid not exist"}';
			die;
		}
		if ($conf['key'] != $key) {
			echo '{"success":"-2","msg":"key error"}';
			die;
		}
		$list = M('Boosts')->where(array('token' => $conf['token']))->field('id as uboostid,boostid,time,intro,token')->select();
		$boost = array();
		foreach ($list as $key => $value) {
			$where = array('token' => $value['token'], 'boostid' => $value['uboostid']);
			$user = M('Boost_records')->where($where)->group('wecha_id')->getField('id');
			$value['user_count'] = count($user);
			$boost[$value['boostid']] = $value;
		}
		echo json_encode($boost);
	}
	public function api_boost_record()
	{
		$uid = $this->_post('uid', 'intval');
		$key = $this->_post('key', 'trim');
		$gid = $this->_post('gid', 'trim');
		$where = array('uid' => $uid);
		$conf = M('Game_config')->where($where)->find();
		if (empty($conf)) {
			echo '{"success":"-1","msg":"uid not exist"}';
			die;
		}
		if ($conf['key'] != $key) {
			echo '{"success":"-2","msg":"key error"}';
			die;
		}
		$data = array('token' => $conf['token'], 'boostid' => $this->_post('bid', 'intval'), 'wecha_id' => $this->_post('openid', 'trim'), 'uid' => $uid, 'u_boost_id' => $this->_post('ubid', 'intval'));
		$max_score = M('Boost_records')->field('id,score')->where($data)->order('score DESC')->find();
		if ($max_score) {
			$score = (double) $this->_post('score', 'trim');
			if ($max_score['score'] < $score) {
				M('Boost_records')->where($data)->save(array('score' => $score, 'time' => time()));
			}
			echo '{"success":"1","msg":"record ok"}';
			die;
		} else {
			$data['score'] = (double) $this->_post('score', 'trim');
			$data['time'] = time();
			if (M('Boost_records')->add($data)) {
				echo '{"success":"1","msg":"record ok"}';
				die;
			}
		}
	}
	public function getCouponCount()
	{
		$cardId = $this->_post('card_id', 'intval');
		$couponData = M('Member_card_coupon')->where(array('card_id' => $cardId))->find();
		echo json_encode(array('total' => $couponData['total']));
	}
	public function getConvertData()
	{
		$uid = $this->uid;
		$boostId = $this->_post('boost_id', 'intval');
		$uBoostId = $this->_post('u_boost_id', 'intval');
		$fansId = $this->_post('fans_id', 'intval');
		$data = array('status' => 0);
		$convertData = D('Boost_convert')->field('id, convert_code, is_use, awards_level')->where(array('uid' => $uid, 'boost_id' => $boostId, 'u_boost_id' => $uBoostId, 'fans_id' => $fansId))->find();
		if (!empty($convertData)) {
			$data = array('status' => 1, 'id' => $convertData['id'], 'convert_level' => $convertData['awards_level'], 'convert_code' => $convertData['convert_code'], 'is_use' => $convertData['is_use']);
		}
		echo json_encode($data);
	}
	public function createConvertData()
	{
		$uid = $this->uid;
		$boostId = $this->_post('boost_id', 'intval');
		$uBoostId = $this->_post('u_boost_id', 'intval');
		$fansIds = unserialize(htmlspecialchars_decode($_POST['fans_ids']));
		foreach ($fansIds as $fansInfo) {
			$convertData = D('Boost_convert')->where(array('uid' => $uid, 'boost_id' => $boostId, 'u_boost_id' => $uBoostId, 'fans_id' => $fansInfo['fans_id']))->find();
			if (empty($convertData)) {
				$convertCode = $this->createConvertCode();
				$data = array('uid' => $uid, 'boost_id' => $boostId, 'u_boost_id' => $uBoostId, 'fans_id' => $fansInfo['fans_id'], 'convert_code' => $convertCode, 'awards_level' => $fansInfo['level'], 'is_use' => 0, 'create_time' => date('Y-m-d H:i:s'));
				D('Boost_convert')->add($data);
			}
		}
		echo json_encode(array('status' => 1));
	}
	private function createConvertCode($len = 12)
	{
		$str = '';
		$randString = '0123456789';
		$max = strlen($randString) - 1;
		for ($i = 0; $i < $len; $i++) {
			$str .= $randString[rand(0, $max)];
		}
		if (D('Boost_convert')->isValideCode($str)) {
			return $str;
		} else {
			return $this->createConvertCode();
		}
	}
	public function getQrImg()
	{
		$result = array('url' => '');
		$keyword = $_POST['keyword'];
		$fromId = $_POST['from_id'];
		$expire = $_POST['expire'];
		$qrData = array('keyword' => $keyword, 'from_id' => $fromId);
		$recognitionDB = D('Recognition');
		$newQrImg = $recognitionDB->createQrcode("GameShare", "", $qrData, $expire, $this->token);

		if (!$newQrImg) {
			$result["error"] = $recognitionDB->getError();
		
		} else {
			$result['url'] = $newQrImg['show'];
		}
		echo json_encode($result);
	}
	public function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);
		if ($errorno) {
			$this->error('发生错误：curl error' . $errorno);
		} else {
			$js = json_decode($tmpInfo, true);
			if (isset($js['ticket'])) {
				return $js['ticket'];
			} else {
				$this->error('发生错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);
			}
		}
	}
}
?>
