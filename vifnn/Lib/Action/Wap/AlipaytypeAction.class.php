<?php
/**
 * alipay API
 */
class AlipaytypeAction extends WapAction {

	protected $alipay_config = array();
	protected $alipay_save_config;
	public $base_path;

	public function _initialize() {
		parent::_initialize();

		$this->base_path = APP_PATH . 'Lib/ORG/WapAlipay/';
		// 2015-05-26 lixiang  支持平台支付
		if (($_GET['platform'] || $_GET['pl']) && C('platform_open') && C('platform_alipay_open')) {
			$this->alipay_save_config['pid'] = C('platform_alipay_pid');
			$this->alipay_save_config['name'] = C('platform_alipay_name');
			$this->alipay_save_config['key'] = C('platform_alipay_key');
		} else {
			$alipay_save_config = M('Alipay_config')->where(array('token' => $this->token))->find();
			$alipay_save_config = unserialize($alipay_save_config['info']);
			$this->alipay_save_config = $alipay_save_config['alipay'];
		}
		$this->alipay_config = array(

			'partner' => $this->alipay_save_config['pid'],

			'seller_email' => $this->alipay_save_config['name'],

			'key' => $this->alipay_save_config['key'],

			'private_key_path' => $this->base_path . 'key/rsa_private_key.pem',

			'ali_public_key_path' => $this->base_path . 'key/alipay_public_key.pem',

			'sign_type' => 'MD5', //0001

			'input_charset' => 'utf-8',

			'cacert' => $this->base_path . 'cacert.pem',

			'transport' => 'http',

		);

	}

	public function pay() {
		//参数数据
		$orderName = htmlentities($_GET['orderName']);
		$orderid = htmlentities($_GET['orderid']);
		$from = htmlentities($_GET['from']);

		if (!$orderName) {
			$orderName = microtime();
		}

		if (!$orderid) {
			$orderid = htmlentities($_GET['single_orderid']); //单个订单
		}

		$payHandel = new payHandle($this->token, $from, 'alipay');
		$orderInfo = $payHandel->beforePay($orderid);
		$price = $orderInfo['price'];

		//判断是否已经支付过
		if ($orderInfo['paid']) {
			exit('您已经支付过此次订单！');
		}

		if (!$price) {
			exit('必须有价格才能支付');
		}

		require_once $this->base_path . "lib/alipay_submit.class.php";

		/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/

		//返回格式
		$format = "xml";

		//返回格式
		$v = "2.0";

		//请求号
		$req_id = date('Ymdhis');
		//**req_data详细信息**
		if (($_GET['platform'] || $_GET['pl']) && C('platform_open') && C('platform_alipay_open')) {
			$query_string_base = 'token=' . $this->token . '|wecha_id=' . $this->wecha_id . '|from=' . $from . '|pl=1';
			$query_string_base_notify = 'token||' . $this->token . '|wecha_id||' . $this->wecha_id . '|from||' . $from . '|pl||1';
		} else {
			$query_string_base = 'token=' . $this->token . '|wecha_id=' . $this->wecha_id . '|from=' . $from;
			$query_string_base_notify = 'token||' . $this->token . '|wecha_id||' . $this->wecha_id . '|from||' . $from;
		}
		//服务器异步通知页面路径
		//print_r($_SERVER['HTTP_HOST']);exit;
		$notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/wxpay/alipaytype_notify_url.php?user_params=' . $query_string_base_notify;
		//echo $notify_url;die;
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		//$notify_url = 'http://test1.vifnn.cn/alipay/notify_url.php';
		//页面跳转同步通知页面路径

		//?user_params='.$query_string_base
		$call_back_url = 'http://' . $_SERVER['HTTP_HOST'] . '/wxpay/alipaytype_call_back_url.php?user_params=' . $query_string_base;
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		//$call_back_url = 'http://test1.vifnn.cn/alipay/call_back_url.php';
		//操作中断返回地址
		$merchant_url = 'http://' . $_SERVER['HTTP_HOST'] . '/wxpay/alipaytype_break.php';
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
		//$merchant_url = 'http://test1.vifnn.cn/alipay/break.php';
		//商户订单号d
		$out_trade_no = $orderid;
		//商户网站订单系统中唯一订单号，必填

		//订单名称
		$subject = $orderName;
		//必填

		//付款金额
		$total_fee = $price;
		//必填

		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . trim($this->alipay_config['seller_email']) . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		//必填

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$para_token = array(
			"service" => "alipay.wap.trade.create.direct",
			"partner" => trim($this->alipay_config['partner']),
			"sec_id" => trim($this->alipay_config['sign_type']),
			"format" => $format,
			"v" => $v,
			"req_id" => $req_id,
			"req_data" => $req_data,
			"_input_charset" => trim(strtolower($this->alipay_config['input_charset'])),
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);

		//URLDECODE返回的信息
		$html_text = urldecode($html_text);

		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);

		//获取request_token
		$request_token = $para_html_text['request_token'];

		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "alipay.wap.auth.authAndExecute",
			"partner" => trim($this->alipay_config['partner']),
			"sec_id" => trim($this->alipay_config['sign_type']),
			"format" => $format,
			"v" => $v,
			"req_id" => $req_id,
			"req_data" => $req_data,
			"_input_charset" => trim(strtolower($this->alipay_config['input_charset'])),
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		header("Content-type: text/html; charset=utf-8");
		echo $html_text;
	}

	public function notify_url() {

		require_once $this->base_path . "lib/alipay_notify.class.php";

		$_POST['notify_data'] = htmlspecialchars_decode($_POST['notify_data']);
		$from = htmlentities($_GET['from']);
		/*
			unset($_GET['g']);
			unset($_GET['m']);
			unset($_GET['a']);
			unset($_GET['token']);
			unset($_GET['wecha_id']);
			unset($_GET['from']);
			unset($_GET['rget']);
			unset($_GET['user_params']);
		*/
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($this->alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		if ($verify_result) {
			//验证成功

			//请在这里加上商户的业务逻辑程序代

			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

			//解析notify_data
			//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
			$doc = new DOMDocument();
			if ($this->alipay_config['sign_type'] == 'MD5') {
				$doc->loadXML($_POST['notify_data']);
			}

			if ($this->alipay_config['sign_type'] == '0001') {
				$doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
			}

			if (!empty($doc->getElementsByTagName("notify")->item(0)->nodeValue)) {
				//商户订单号
				$out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
				//支付宝交易号
				$trade_no = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
				//交易状态
				$trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;

				if ($trade_status == 'TRADE_FINISHED') {

					echo "success"; //请不要修改或删除
				} else if ($trade_status == 'TRADE_SUCCESS') {

					$payHandel = new payHandle($this->token, $from, 'alipay');
					$orderInfo = $payHandel->beforePay($out_trade_no);
					if (empty($orderInfo['paid'])) {
						$orderInfo = $payHandel->afterPay($out_trade_no, $trade_no);
						$url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&rget=1&orderid=' . $out_trade_no;
						file_get_contents($url);
					}
					echo "success"; //请不要修改或删除
				}
			}

		} else {
			//验证失败
			echo "fail";

		}
	}
	public function call_back_url() {
		require_once $this->base_path . "lib/alipay_notify.class.php";
		//计算得出通知验证结果
		$from = htmlentities($_GET['from']);
		$pl = $_GET['pl'];
		unset($_GET['g']);
		unset($_GET['m']);
		unset($_GET['a']);
		unset($_GET['token']);
		unset($_GET['wecha_id']);
		unset($_GET['from']);
		unset($_GET['rget']);
		unset($_GET['user_params']);
		unset($_GET['pl']);
		$alipayNotify = new AlipayNotify($this->alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if ($verify_result) {
			//验证成功

			//商户订单号
			$out_trade_no = $_GET['out_trade_no'];

			//支付宝交易号
			$trade_no = $_GET['trade_no'];

			//交易状态
			$result = $_GET['result'];

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			if ($result == 'success') {
				if (!empty($pl)) {
					$_GET['pl'] = 1;
				}
				//after
				$payHandel = new payHandle($this->token, $from, 'alipay');
				$orderInfo = $payHandel->beforePay($out_trade_no);
				$nohandle = '';
				if (empty($orderInfo['paid'])) {
					$orderInfo = $payHandel->afterPay($out_trade_no, $trade_no);
				} else {
					$nohandle = '&nohandle=1';
				}
				//$from=$payHandel->getFrom();

				//支付成功后跳出iframe 如果没有iframe侧直接跳转

				$url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php?g=Wap&m=' . $from . '&a=payReturn&token=' . $orderInfo['token'] . '&wecha_id=' . $orderInfo['wecha_id'] . '&orderid=' . $out_trade_no . $nohandle;
				//echo '<script type="text/javascript">if (window.parent.breakIframe) {window.parent.breakIframe("' . $url . '");}else{window.location.href="' . $url . '"}</script>';
				echo '<script type="text/javascript">top.location.href="' . $url . '";</script>';
			} else {
				exit('付款失败');
			}
		} else {
			//验证失败
			echo "验证失败";
			exit;
		}
	}

}