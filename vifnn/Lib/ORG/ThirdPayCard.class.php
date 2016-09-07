<?php

class ThirdPayCard {

	public function index($orderid, $paytype = '', $third_id = '') {
		$where = array('orderid' => $orderid);

		$order = M('Member_card_pay_record')->where($where)->find();

		if ($order) {
			$wecha_id = $order['wecha_id'];
			$token = $order['token'];

			if ($order['paid'] == 1) {
				$paytime = time();
				M('Member_card_pay_record')->where($where)->setField('paytime', $paytime);
				if ($order['type'] == 1) {
					$calculate = self::_calculate($order["price"], $token, $order["cardid"], $wecha_id, $orderid);
					M("Userinfo")->where("wecha_id = '$wecha_id' AND token = '$token'")->setInc("balance", $calculate["price"]);
					if ($calculate["is_donate"] == 1) {
						$data = array("price" => $calculate["donate_price"], "cardid" => $order["cardid"], "token" => $token, "wecha_id" => $wecha_id, "createtime" => time(), "paytype" => "donate", "ordername" => $order["ordername"] . "赠送", "paid" => 1);
						M("Member_card_pay_record")->add($data);
					}
				} else {
					$lastid = M('Member_card_use_record')->where(array('token' => $token, 'wecha_id' => $wecha_id))->order('id DESC')->getField('id');
					if ($this->_get('type') == 'coupon') {
						M('Member_card_coupon')->where(array('token' => $token, 'id' => (int) $this->_get('itemid')))->setInc('usetime', (int) $this->_get('usecount'));
						M('Member_card_use_record')->where(array('token' => $token, 'id' => $lastid))->setField(array('usecount' => (int) $this->_get('usecount'), 'cat' => 6));
					} elseif ($this->_get('type') == 'privelege') {
						M('Member_card_vip')->where(array('token' => $token, 'id' => (int) $this->_get('itemid')))->setInc('usetime');
						M('Member_card_use_record')->where(array('token' => $token, 'id' => $lastid))->setField('cat', 4);
					}

				}
				/*
					                if(empty($act)){
					                	header('Location:'.U('Card/card',array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$order['cardid'])));
					                }else{
					                	header('Location:'.U('Card/'.$act,array('token'=>$token,'wecha_id'=>$wecha_id,'cardid'=>$order['cardid'])));
					                }
				*/
				$info = M('Member_card_set')->where(array('token' => $token, 'cardid' => $order['cardid']))->find();
				$cardinfo = M('Member_card_create')->where(array('token' => $token, 'cardid' => $order['cardid'], 'wecha_id' => $wecha_id))->find();

				$href = $this->siteUrl . '/index.php?' . http_build_query(array('g' => 'Wap', 'm' => 'Card', 'a' => 'card', 'token' => $token, 'wecha_id' => $wecha_id, 'cardid' => $order['cardid']));
				//模板消息
				$model = new templateNews();
				if ('CardPay' == $_GET['paytype']) {
					// 消费
					$dataKey = 'OPENTM202183094';
					$dataArr = array(
						'href' => $href,
						'wecha_id' => $wecha_id,
						'first' => '您好，你已经支付成功。',
						'keyword1' => $order['price'],
						'keyword2' => '会员卡消费',
						'keyword3' => '会员卡支付',
						'keyword4' => $orderid,
						'keyword5' => date('Y-m-d H:i:s'),
						'remark' => '会员卡消费',
					);
					$model->sendTempMsg($dataKey, $dataArr);
					header('Location:' . U('Card/card', array('token' => $token, 'wecha_id' => $wecha_id, 'cardid' => $order['cardid'])));
				} else {
					$dataKey = 'TM00009';
					$dataArr = array(
						'href' => $href,
						'wecha_id' => $wecha_id,
						'first' => '您好，你已经成功充值。',
						'accountType' => $info['cardname'],
						'account' => $cardinfo['number'],
						'amount' => $order['price'],
						'result' => '充值成功',
						'remark' => '会员充值',
					);
					$model->sendTempMsg($dataKey, $dataArr);
					$wechaname = M("userinfo")->where(array("token" => $token, "wecha_id" => $wecha_id))->getField("wechaname");
					$msg = "";
					$msg .= chr(10) . "昵称:" . ($wechaname != "" ? $wechaname : "匿名");
					$msg .= chr(10) . "订单编号:" . $order["orderid"];
					$msg .= chr(10) . "订单名称:" . $order["ordername"];
					$msg .= chr(10) . "支付金额:" . $order["price"] . "元";
					$msg .= chr(10) . "支付时间:" . date("YmdHis", $paytime);
					$msg .= chr(10) . "*******************************";
					$msg .= chr(10) . "感谢使用!";
					$op = new orderPrint();
					$op->printit($token, $order["company_id"], "Card", $msg, 1);
					return true;
				}

			} else {
				exit('支付未完成');
			}

		} else {
			exit('订单不存在');
		}

	}

	/**
	 * 计算充值赠送
	 * @param number $price
	 * @return number
	 */
	private function _calculate($price, $token, $cardid, $wecha_id, $orderid) {
		$where = "token='$token' AND cardid=$cardid AND is_open=1 AND ((min_price<$price AND max_price>=$price) OR (min_price<$price AND max_price=0))";
		$models = M('MemberCardDonate')->where($where)->order('min_price DESC,max_price DESC')->find();
		if (empty($models)) {
			return array("is_donate" => 0, "price" => $price);
		} else {
			self::_upgrade($token, $cardid, $wecha_id, $models, $orderid);
			return array("is_donate" => 1, "price" => $price + $models["donate_price"], "donate_price" => $models["donate_price"]);
		}
	}

	private function _upgrade($token, $cardid, $wecha_id, $donate, $orderid)
	{
		if (($token == "") || ($wecha_id == "") || $price) {
			return false;
		}

		$currentcard = M("member_card_set")->where(array("id" => $cardid))->find();

		if (empty($currentcard)) {
			return false;
		}

		if (!empty($donate) && ($donate["gradeid"] != 0) && ($currentcard["gradeid"] < $donate["gradeid"])) {
			$gradecard = M("member_card_set")->where(array("token" => $token, "gradeid" => $donate["gradeid"]))->order("id desc")->select();

			foreach ($gradecard as $key => $value ) {
				$freecard = M("Member_card_create")->field("id,number,cardid")->where("token='" . $token . "' and cardid=" . intval($value["id"]) . " and wecha_id = ''")->order("id ASC")->find();

				if ($freecard) {
					break;
				}
			}

			if (empty($freecard)) {
				return false;
			}

			M("Member_card_create")->where(array("token" => $token, "wecha_id" => $wecha_id))->delete();
			M("Member_card_create")->where(array("id" => $freecard["id"]))->save(array("wecha_id" => $wecha_id));
			M("Member_card_pay_record")->where(array("orderid" => $orderid))->setField("cardid", $freecard["cardid"]);
		}

		return true;
	}
}

?>