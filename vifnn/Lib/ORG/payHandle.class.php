<?php
final class payHandle {
	public $from;
	public $db;
	public $payType;
	public $token;
	public function __construct($token, $from, $paytype = 'tenpay') {

		$this->from = $from;
		$this->from = $from ? $from : 'Groupon';
		$this->from = $this->from != 'groupon' ? $this->from : 'Groupon';
		switch (strtolower($this->from)) {

		case 'groupon':
			    $this->db=M('Product_cart');//自行 增加团购支付
				break;
			case 'storenew': //自行增加 新版商城
			    $this->db = M('New_product_cart');
			    break;
		case 'store':
			$this->db = M('Product_cart');
			break;
		case 'repast':
			$this->db = M('Dish_order');
			break;
		case 'dishout':
			$this->db = M('Dish_order');
			break;
		case 'hotels':
			$this->db = M('Hotels_order');
			break;
		case 'business':
			$this->db = M('Reservebook');
			break;
		case 'card':
			$this->db = M('Member_card_pay_record');
			break;
		case 'medical':
			$this->db = M('Medical_user');
			break;
		case 'unitary':
			$this->db = M('unitary_order');
			break;
		case 'livingcircle':
			$this->db = M('livingcircle_mysellerorder');
			break;
		case 'bargain':
			$this->db = M('bargain_order');
			break;
		case 'crowdfunding':
			$this->db = M('Crowdfunding_order');
			break;
		case 'seckill':
			$this->db = M('Seckill_book');
			break;
		case 'micrstore':
			$this->db = M('Micrstore');
			break;
		case 'drppayment': //分销支付返回
			$this->db = M('Product_cart');
			break;
			case 'cutprice': //分销支付返回
			$this->db = M('cutprice_order');
			break;
		case 'auction':
			$this->db = M('auction_order');
			break;
		case 'donation':
			$this->db = M('donation_order');
			break;
		case 'voteimg':
			$this->db = M('voteimg_book');
			break;
		case 'custom':
			$this->db = M('custom_order');
			break;
		case 'index':
			$this->db = M('img_dashang_order');
			break;
		case 'pintuan':
			$this->db = M('pintuan_order');
			break;
		default:

			break;
		}
		$this->token = $token;
		$this->payType = $paytype;
	}
	public function getFrom() {
		return $this->from;
	}
	public function beforePay($id, $first = 0) {
		if (strtolower($this->from) == 'repast') {
			$wherearr = array('token' => $this->token, 'tmporderid' => $id);
		} elseif (strtolower($this->from) == 'micrstore') {
			$wherearr = array('token' => $this->token, 'trade_no' => $id);
		} else {
			$wherearr = array('token' => $this->token, 'orderid' => $id);
		}

		$thisOrder = $this->db->where($wherearr)->find();

		switch (strtolower($this->from)) {
		case 'business':
			$price = $thisOrder['payprice'];
			break;
		case 'repast':
			if (($thisOrder['advancepay'] > 0) && !($thisOrder['paycount'] > 0)) {
				$price = $thisOrder['advancepay'];
			} else {
				$price = $thisOrder['price'] - $thisOrder['havepaid'];
			}
			break;
		case 'micrstore':
			$thisOrder['orderid'] = $thisOrder['trade_no'];
			$price = $thisOrder['price'];
			break;
		default:
			$price = $thisOrder['price'];
			break;
		}
		$old_price = $price;
		if ($cpr = D('Coupon_pay_record')->where(array('orderid' => $id, 'token' => $this->token, 'wechat_id' => $thisOrder['wecha_id'], 'from' => $this->from))->find()) {
			if ($price >= $cpr['least_cost'] && empty($first)) {
				$price = number_format($price - $cpr['reduce_cost'], 2, '.', '');
			}
		}
		/******tmporderid支持微信订单多次支付*********/
		if (key_exists('third_id', $thisOrder)) {
			return array("orderid" => $thisOrder["orderid"], "cid" => isset($thisOrder["cid"]) ? $thisOrder["cid"] : 0, "old_price" => $old_price, "price" => $price, "wecha_id" => $thisOrder["wecha_id"], "token" => $thisOrder["token"], "paid" => $thisOrder["paid"], "third_id" => $thisOrder["third_id"], "tmporderid" => isset($thisOrder["tmporderid"]) ? $thisOrder["tmporderid"] : "", "isover" => isset($thisOrder["isover"]) ? $thisOrder["isover"] : 0);
		} else {
			return array("orderid" => $thisOrder["orderid"], "cid" => isset($thisOrder["cid"]) ? $thisOrder["cid"] : 0, "old_price" => $old_price, "price" => $price, "wecha_id" => $thisOrder["wecha_id"], "token" => $thisOrder["token"], "paid" => $thisOrder["paid"], "transactionid" => $thisOrder["transactionid"], "tmporderid" => isset($thisOrder["tmporderid"]) ? $thisOrder["tmporderid"] : "", "isover" => isset($thisOrder["isover"]) ? $thisOrder["isover"] : 0);
		}

	}
	public function afterPay($id, $third_id = '', $transaction_id = '') {
		$thisOrder = $this->beforePay($id);
		if (empty($thisOrder)) {
			exit('订单不存在！');
		} elseif ($thisOrder['paid']) {
			exit('此订单已付款，请勿重复操作！');
		}

		if ($this->payType == "weixin") {
			if ($thisOrder["paid"] == 1) {
				exit("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
			}
			else {
				if ((strtolower($this->from) == "repast") && ($thisOrder["paid"] != 1) && ($thisOrder["isover"] == 1) && !empty($thisOrder["third_id"])) {
					exit("<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>");
				}
			}
		}

		$wecha_id = $thisOrder["wecha_id"];
		if ($this->payType != 'daofu' && $this->payType != 'dianfu') {

			//检查是否使用优惠券
			if ($cpr = D('Coupon_pay_record')->where(array('orderid' => $id, 'token' => $this->token, 'wechat_id' => $thisOrder['wecha_id'], 'from' => $this->from))->find()) {
				$tprice = number_format($thisOrder['price'] + $cpr['reduce_cost'], 2, '.', '');
				$obj = new Member_card_coupon_recordModel();
				$coupon = $obj->check_coupon($cpr['coupon_id'], $thisOrder['wecha_id'], $this->token, $tprice);
				if ($coupon['error']) {
					//优惠券不能使用的时候 将支付的金额充值到粉丝账号中去
					D('Userinfo')->where(array('wecha_id' => $thisOrder['wecha_id'], 'token' => $this->token))->setInc('balance', $thisOrder['price']);
					exit($coupon['msg'] . ",您支付的金额已经充值到您的 账号里");
					exit();
				} else {
					$coupon = $coupon['data'];
					$obj->use_coupon($cpr['coupon_id'], $thisOrder['wecha_id'], $this->token, $tprice);
					D('Coupon_pay_record')->where(array('orderid' => $id, 'token' => $this->token, 'wechat_id' => $thisOrder['wecha_id'], 'from' => $this->from))->save(array('dateline' => time()));

					if ($coupon['is_weixin']) {
						//同步核销微信卡包中的券
						$thisWxUser = M("Wxuser")->where(array('token' => $this->token))->find();
						$coupons = new WechatCoupons($thisWxUser);
						$res = $coupons->consumeCoupons($coupon['card_id'], $coupon['cancel_code']);
						// 					if($res['errcode'] > 0){
						// 						$result['err'] 	= 2;
						// 						$result['msg'] 	= $res['errmsg'];
						// 					}
					}
				}
			}

			$member_card_create_db = M('Member_card_create');
			$userCard = $member_card_create_db->where(array('token' => $this->token, 'wecha_id' => $wecha_id))->find();
			$userinfo_db = M('Userinfo');
			if ($userCard && $this->from != 'Card') {
				$member_card_set_db = M('Member_card_set');
				$thisCard = $member_card_set_db->where(array('id' => intval($userCard['cardid'])))->find();
				if ($thisCard) {
					$set_exchange = M('Member_card_exchange')->where(array('cardid' => intval($thisCard['id'])))->find();
					//
					$arr['token'] = $this->token;
					$arr['wecha_id'] = $wecha_id;
					$arr['expense'] = $thisOrder['price'];
					$arr['time'] = time();
					$arr['cat'] = 99;
					$arr['staffid'] = 0;
					$arr['cardid'] = intval($userCard['cardid']);
					$arr["score"] = ($set_exchange["expense"] <= $arr["expense"] ? floor(($set_exchange["reward"] / $set_exchange["expense"]) * $arr["expense"]) : 0);

					if (isset($_GET['redirect'])) {
						$infoArr = explode('|', $_GET['redirect']);

						$param = explode(',', $infoArr[1]);
						if ($param) {
							foreach ($param as $pa) {
								$pas = explode(':', $pa);
								if ($pas[0] == 'itemid') {
									$arr['itemid'] = $pas[1];
								}
							}
						}

					}

					M('Member_card_use_record')->add($arr);

					$thisUser = $userinfo_db->where(array('token' => $thisCard['token'], 'wecha_id' => $arr['wecha_id']))->find();
					$userArr = array();
					$is_syn = M('Wxuser')->where(array('token' => $this->token))->getField('is_syn');
					if (empty($is_syn)) {
						$userArr['total_score'] = $thisUser['total_score'] + $arr['score'];
					}
					$userArr['expensetotal'] = $thisUser['expensetotal'] + $arr['expense'];
					$userinfo_db->where(array('token' => $this->token, 'wecha_id' => $arr['wecha_id']))->save($userArr);
				}
			}
			$data_order['paid'] = 1;

			/***********这里对接收银台，请勿删除***********/
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/Cashier/pigcms/base.php') && 0 < $thisOrder['price']) {
				$postdata['thirduserid'] = $this->token;
				$postdata['order_id'] = $id;
				$postdata['comefrom'] = 1;
				$postdata['pay_way'] = $this->payType;
				$postdata['pay_type'] = $this->payType;
				$postdata['goods_id'] = 111; /****商品id****/
				$postdata['goods_name'] = ''; /****商品名称****/
				$postdata['goods_price'] = $thisOrder['price'];
				$postdata['transaction_id'] = !empty($third_id) ? $third_id : (isset($thisOrder['third_id']) ? $thisOrder['third_id'] : $thisOrder['transactionid']);
				$postdata['openid'] = $thisOrder['wecha_id'];
				if (!isset($thisUser)) {
					$thisUser = $userinfo_db->where(array('token' => $this->token, 'wecha_id' => $thisOrder['wecha_id']))->find();
				}
				$postdata['headimgurl'] = isset($thisUser['portrait']) ? $thisUser['portrait'] : '';
				$nickname = '';
				isset($thisUser['wechaname']) && $nickname = !empty($thisUser['wechaname']) ? $thisUser['wechaname'] : $thisUser['truename'];
				$postdata['is_subscribe'] = !empty($postdata['headimgurl']) ? 1 : 0;
				$postdata['nickname'] = $nickname;
				$postdata['sex'] = isset($thisUser['sex']) ? $thisUser['sex'] : 0;
				$postdata['province'] = isset($thisUser['province']) ? $thisUser['province'] : '';
				$postdata['city'] = isset($thisUser['city']) ? $thisUser['city'] : '';
				$this->apiCashierPayNofity($postdata);
			}

		}
		//
		$order_model = $this->db;
		$data_order['paytype'] = $this->payType;

		//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/Datavifnn/conf/3'.$this->token.'.txt',json_encode($thisOrder));

		if (key_exists('third_id', $thisOrder)) {
			$data_order['third_id'] = $third_id;
		} else {
			$data_order['transactionid'] = $third_id;
		}

		//$order_model->where(array('orderid'=>$id))->setField('paid',1);

		$where_arr = array('orderid' => $id);
		if (strtolower($this->from) == 'repast') {
			$where_arr = array('tmporderid' => $id);
		}

		if (strtolower($this->from) == 'micrstore') {
			$where_arr = array('trade_no' => $id);

		}

		$order_model->where($where_arr)->data($data_order)->save();

		// 微店
		if (strtolower($this->from) == 'micrstore') {
			$micrstore_orderid = $order_model->where($where_arr)->getField('orderid');
			$this->apiMicrstorePayNofity(array('order_no' => $micrstore_orderid, 'third_id' => $third_id, 'payment_method' => $this->payType, 'pay_money' => $data_order['price']));
		}

		if (strtolower($this->getFrom()) == 'groupon') {

			$order_model->where(array('orderid' => $thisOrder['orderid']))->save(array('transactionid' => $transaction_id, 'paytype' => $this->payType));

		}

		if (isset($_GET['pl']) && $_GET['pl'] == 1) {
			$database_platform_pay = D('Platform_pay');
			if (!$database_platform_pay->where(array('from' => $this->from, 'orderid' => $thisOrder['orderid']))->find()) {
				$data_platform_pay['orderid'] = $thisOrder['orderid'];
				$data_platform_pay['price'] = $thisOrder['price'];
				$data_platform_pay['wecha_id'] = $thisOrder['wecha_id'];
				$data_platform_pay['token'] = $thisOrder['token'];
				$data_platform_pay['from'] = $this->from;
				$data_platform_pay['time'] = $_SERVER['REQUEST_TIME'];
				$data_platform_pay['thirdorderid'] = $transaction_id;
				$database_platform_pay->data($data_platform_pay)->add();
			}
		}

		if ($this->payType == 'weixin') {
			//记录微信支付支付到平台账号的支付记录
			$plat_type = isset($_GET['pl']) ? intval($_GET['pl']) : 0;
			$database_weixin_bill = D('Weixin_bill');
			$billWhere=array('from' => $this->from, 'orderid' => $thisOrder['orderid']);
			if (strtolower($this->from) == 'repast') {
				/****餐饮多次支付问题*****/
			    $billWhere=array('from' => $this->from, 'orderid' => !empty($thisOrder['tmporderid']) ? $thisOrder['tmporderid']:$thisOrder['orderid']);
			}
			if (!$database_weixin_bill->where($billWhere)->find()) {
				if ($plat_type != 1) {
					$payConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
					$payConfigInfo = unserialize($payConfig['info']);
					$appid = isset($payConfigInfo['weixin']['new_appid']) ? $payConfigInfo['weixin']['new_appid'] : $payConfigInfo['weixin']['appid'];
					$mchid = isset($payConfigInfo['weixin']['mchid']) ? $payConfigInfo['weixin']['mchid'] : '';
				} else {
					$appid = C('appid');
					$mchid = C('platform_weixin_mchid');
				}

				$data_system_pay['orderid'] = !empty($thisOrder['tmporderid']) ? $thisOrder['tmporderid']:$thisOrder['orderid'];
				$data_system_pay['price'] = $thisOrder['price'];
				$data_system_pay['wecha_id'] = $thisOrder['wecha_id'];
				$data_system_pay['token'] = $thisOrder['token'];
				$data_system_pay['from'] = $this->from;
				$data_system_pay['time'] = $_SERVER['REQUEST_TIME'];
				$data_system_pay['third_id'] = $third_id;
				$data_system_pay['plat_type'] = $plat_type;
				$data_system_pay['appid'] = $appid;
				$data_system_pay['mchid'] = $mchid;
				$database_weixin_bill->data($data_system_pay)->add();
			}
		}
		return $thisOrder;
	}

	private function apiMicrstorePayNofity($data) {
		function callback($v) {
			if (empty($v)) {
				return $v = '';
			} else {
				return $v;
			}
		}
		if (updateSync::getIfWeidian()) {
			$Micrstore_URL = C('weidian_domain') ? C('weidian_domain') : 'http://vd.vifnn.com';
			$SALT = C('encryption') ? C('encryption') : 'vifnn.com';
		} else {
			$Micrstore_URL = 'http://vd.vifnn.com';
			$SALT = 'vifnn.com';
		}
		$sort_data = $data;
		$sort_data['salt'] = $SALT;
		ksort($sort_data);
		$sort_data = array_map('callback', $sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$data['sign_key'] = $sign_key;
		$data['request_time'] = time();
		$url = $Micrstore_URL . "/api/pay_notify.php"; //微店接收数据的地址
		$return = json_decode($this->curl_post($url, $data), true); //微店返回数据
	}

	private function apiCashierPayNofity($data) {
		if (!empty($data)) {
			$validate = $data;
			$validate['salt'] = 'pigcmso2oCashier';
			sort($validate, SORT_STRING);
			$data['sign'] = sha1(implode($validate));
			unset($validate);
			$postdataStr = base64_encode(json_encode($data));
			$purl = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$purl = strtolower($purl);
			if (strpos($purl, "http:") === false && strpos($purl, "https:") === false) {
				$purl = 'http://' . $purl;
			}

			$purl = rtrim($purl, '/');
			$returnret = $this->curl_post($purl . '/merchants.php?m=Index&c=auth&a=orderCreat', $postdataStr);
		}
	}

	// CURL POST 传输
	private function curl_post($url, $post) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$output = curl_exec($ch);
		curl_close($ch);
		//返回获得的数据
		return $output;
	}
}

?>