<?php


class CardPayAction extends BaseAction
{
	public $token;
	public $wecha_id;
	public function __construct()
	{
		$this->token = $this->_request('token');
		$this->wecha_id = $this->_request('wecha_id');
	}
	public function pay()
	{
		$from = $this->_request('from');
		$single_orderid = $this->_request('single_orderid');
		$orderName = $this->_request('orderName');
		$redirect = $this->_request('redirect');
		$payHandel = new payHandle($this->token, $from, 'CardPay');
		$orderInfo = $payHandel->beforePay($single_orderid);
		$price = $orderInfo['price'];
		if ($price <= 0) {
			$this->error('请输入有效金额');
		}
		if ($price < 0.01) {
			$this->error('价格不能小于0.01元');
		}
		$this->assign('price', $price);
		$userinfoDb = M('Userinfo');
		$paypassArr = $userinfoDb->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->field('paypass,wecha_id,id,fakeopenid')->find();
		if (empty($paypassArr) && !is_array($paypassArr)) {
			$paypassArrf = $userinfoDb->where(array('token' => $this->token, 'fakeopenid' => $this->wecha_id))->field('paypass,wecha_id,id')->find();
			if (!empty($paypassArrf)) {
				$paypass = $paypassArrf['paypass'];
				!empty($paypassArrf['wecha_id']) && ($this->wecha_id = $paypassArrf['wecha_id']);
			}
		} else {
			$paypass = $paypassArr['paypass'];
		}
		unset($paypassArr);
		unset($paypassArrf);
		if ($_POST['pass'] != false) {
			if (md5($_POST['pass']) == $paypass) {
				if ($redirect) {
					$this->gotopay($single_orderid, $price, $from, $orderName, $redirect);
				} else {
					$this->gotopay($single_orderid, $price, $from, $orderName);
				}
			} else {
				$this->error('密码错误');
			}
		}
		$this->display();
	}
	private function gotopay($orderid, $price, $from, $orderName, $redirect = NULL)
	{
		$userinfo = M('Userinfo');
		$payrecord = M('Member_card_pay_record');
		$create = M('Member_card_create');
		$exchange = M('Member_card_exchange');
		$cardid = $create->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->getField('cardid');
		$cardid = (int) $cardid;
		$reward = $exchange->where(array('token' => $this->token, 'cardid' => $cardid))->getField('reward');
		$reward = (int) $reward;
		$uinfo = $userinfo->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->field('id,balance,expensetotal,total_score')->find();
		if (!$orderid) {
			$this->error('请传入订单号');
		}
		if ($uinfo['balance'] < $price) {
			$this->error('余额不足');
		}
		if ($payrecord->where('orderid = \'' . $orderid . '\'')->getField('id')) {
			$flag1 = true;
		} else {
			$record['orderid'] = $orderid;
			$record['ordername'] = $orderName;
			$record['paytype'] = 'CardPay';
			$record['createtime'] = time();
			$record['paid'] = 0;
			$record['price'] = $price;
			$record['token'] = $this->token;
			$record['wecha_id'] = $this->wecha_id;
			$record['type'] = 0;
			$record['cardid'] = $cardid;
			$flag1 = $payrecord->add($record);
		}
		$payHandel = new payHandle($this->token, $from, 'CardPay');
		$payHandel->afterPay($orderid);
		$udata['balance'] = $uinfo['balance'] - $price;
		$set_exchange = M('Member_card_exchange')->where(array('cardid' => $cardid))->find();
		if (0 < $set_exchange['reward']) {
			$arr["score"] = ($set_exchange["expense"] <= $price ? floor(($set_exchange["reward"] / $set_exchange["expense"]) * $price) : 0);
		}
		$udata['total_score'] = $uinfo['total_score'] + $arr['score'];
		$udata['expensetotal'] = $uinfo['expensetotal'] + $price;
		$flag2 = $userinfo->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save($udata);
		if ($flag1 && $flag2) {
			if ('card' == strtolower($this->_get('from'))) {
				$rwhere = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => $this->_get('consume_id'));
				M('Member_card_coupon_record')->where($rwhere)->save(array('use_time' => time(), 'is_use' => '1', 'staff_id' => -1));
			}
			$payrecord->where(array('orderid' => $orderid, 'token' => $this->token))->setField('paid', 1);
			if (isset($redirect)) {
				$urlinfo = explode('|', $_GET['redirect']);
				$parmArr = explode(',', $urlinfo[1]);
				$parms = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'paytype' => 'CardPay', 'orderid' => $orderid);
				if ($parmArr) {
					foreach ($parmArr as $pa) {
						$pas = explode(':', $pa);
						$parms[$pas[0]] = $pas[1];
					}
				}
				$this->redirect(U($urlinfo[0], $parms));
			} else {
				$url = U($from . '/payReturn', array('orderid' => $orderid, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'paytype' => 'CardPay'));
				echo '<script>alert(\'会员卡支付成功\');window.location.href=\'' . $url . '\';</script>';
			}
		} else {
			$this->error('支付失败');
		}
	}
}