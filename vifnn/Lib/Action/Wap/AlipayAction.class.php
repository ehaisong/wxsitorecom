<?php
class AlipayAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $alipayConfig;
	public function __construct(){
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->_get('wecha_id');
		if (!$this->token){
			//
			$product_cart_model=M('product_cart');
			$out_trade_no = $this->_get('out_trade_no');
			$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
			if (!$order){
				$order=$product_cart_model->where(array('id'=>intval($this->_get('out_trade_no'))))->find();
			}
			$this->token=$order['token'];
		}
		//读取配置
		$alipay_config_db=M('Alipay_config');
		$this->alipayConfig=$alipay_config_db->where(array('token'=>$this->token))->find();
	}
	public function pay(){
		//参数数据
		$orderName=$_GET['orderName'];
		$orderid=$_GET['orderid'];
		if (!$orderid){
			$orderid=$_GET['single_orderid'];//单个订单
		}
		//before
		
		$payHandel=new payHandle($this->token,$_GET['from']);
		$orderInfo=$payHandel->beforePay($orderid, 1);
		$price=$orderInfo['price'];
		if(!$orderInfo['old_price']){
			$this->error('必须有价格才能支付');
			exit;
		}
		/***正好是0.01时有问题****/
		if ($price <= 0.009)  {
			$this->error('价格不能小于0.01元');
		}
		$from = (isset($_GET["from"]) ? trim($_GET["from"]) : "shop");
		$alipayConfig = $this->alipayConfig;
		
		//反序列化得到支付的配置信息
		$now_pay_type = '';
		if($alipayConfig){
			$pay_setting = array();
			$config_info = unserialize($alipayConfig['info']);
			foreach($config_info as $key=>$value){
				if(is_array($value)){
					if($value['open']){
						$key = ($key == 'alipay') ? 'Alipaytype' : ucwords($key);
						$pay_setting[$key] = $value;
						$now_pay_type = $key;
					}
				}
			}
		}
		if(empty($alipayConfig) || empty($now_pay_type)){
			$now_pay_type = 'Alipaytype';
		}

		if ($_GET['from'] == "Card") {
			unset($pay_setting['Dianfu']);
		}

		if (strtolower($_GET['from']) == "repast" && isset($_GET['ydMeal']) && ($_GET['ydMeal']=1)) {
			/****微餐饮预定时去掉到付和垫付*****/
			unset($pay_setting['Dianfu'],$pay_setting['Daofu']);
		}

		/*
		if(count($pay_setting) == 1){
			if($now_pay_type == 'Weixin'){
				header('Location:/wxpay/index.php?g=Wap&m=Weixin&a=pay&price='.$price.'&orderName='.$orderName.'&single_orderid='.$orderid.'&showwxpaytitle=1&from='.$from.'&token='.$this->token.'&wecha_id='.$this->wecha_id);
			}else if($now_pay_type != 'Platform'){
				header('Location:/index.php?g=Wap&m='.$now_pay_type.'&a=pay&price='.$price.'&orderName='.$orderName.'&single_orderid='.$orderid.'&from='.$from.'&token='.$this->token.'&wecha_id='.$this->wecha_id);
			}
		}
		*/
		$this->assign('isFuwu', $this->isFuwu);
		$this->assign('isWechat', $this->isWechat) ;
		$this->assign('price',$price);
		$this->assign('orderName',$orderName);
		$this->assign('orderid',$orderid);
		$this->assign('from',$from);
		$this->assign('token',$this->token);
		$this->assign('wecha_id',$this->wecha_id);
		$this->assign('pay_setting',$pay_setting);
		$wxuser = M('Wxuser')->where(array('token'=>$this->token))->find();
		$user = M('Users')->where(array('id'=>$wxuser['uid']))->find();
		if ( 0 < $user['is_syn']) { // 对接 跳转支付
			$_GET['source'] = 'vifnn';
			redirect($user['source_domain'].A('Home/Auth')->getCallbackUrl($user['is_syn'], 'pay').http_build_query($_GET));
		} else {
			$coupons = array();

			if (strtolower($from) != "micrstore") {
			   $obj = new Member_card_coupon_recordModel();
			   $coupons = $obj->get_coupon($this->wecha_id, $this->token, $price, $orderInfo['cid']);
			}
			$this->assign('coupons', $coupons);
			$this->display('check');
		}
	}
	
	public function to_pay()
	{
		if ($_GET['pay_type'] != 'Daofu' && $_GET['pay_type'] != 'Dianfu') {
			//优惠券
			$orderid = isset($_GET['orderid']) ? $_GET['orderid'] : $_GET['single_orderid'];
			
			$payHandel = new payHandle($this->token, $_GET['from']);
			$orderInfo = $payHandel->beforePay($orderid);
			$price = $orderInfo['old_price'];
			
			$coupon_id = isset($_REQUEST['coupon_id']) ? intval($_REQUEST['coupon_id']) : 0;
			D('Coupon_pay_record')->where(array('orderid' => $_GET['orderid'], 'token' => $this->token, 'from' => $_GET['from'], 'wechat_id' => $this->wecha_id, 'dateline' => 0))->delete();
			if ($coupon_id) {
				$obj = new Member_card_coupon_recordModel();
				$coupon = $obj->check_coupon($coupon_id, $this->wecha_id, $this->token, $price);
				if ($coupon['error']) {
					$this->error($coupon['msg']);
					exit();
				} else {
					//优惠券记录
					$coupon = $coupon['data'];
					$cpr = D('Coupon_pay_record')->where(array('orderid' => $_GET['orderid'], 'token' => $this->token, 'from' => $_GET['from'], 'wechat_id' => $this->wecha_id, 'coupon_id' => $coupon_id))->find();
					if (empty($cpr)) {
						D('Coupon_pay_record')->add(array('orderid' => $_GET['orderid'], 'token' => $this->token, 'from' => $_GET['from'], 'wechat_id' => $this->wecha_id, 'coupon_id' => $coupon_id, 'least_cost' => $coupon['least_cost'], 'reduce_cost' => $coupon['reduce_cost']));
					}
					
					if ($coupon['reduce_cost'] >= $price) {
						$payHandel = new payHandle($_GET['token'], $_GET['from'], 'coupon');
						$orderInfo = $payHandel->afterPay($orderid, $orderid, $orderid);
						$className = 'ThirdPay' . $_GET['from'];
						if (class_exists('ThirdPay' . $_GET['from'])){
							$className::index($orderid, 'coupon', $orderid);
						}
						exit();
					}
				}
			}
		}
		if($_GET['pay_type'] == 'Weixin'){
			if (strpos(CONF_PATH,'Datavifnn')){
				$fileName='index_datapig.php';
			}elseif (strpos(CONF_PATH,'vifnnData')){
				$fileName='index_pigdata.php';
			}else {
				$fileName='index.php';
			}

			header('Location:/wxpay/'.$fileName.'?g=Wap&m=Weixin&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&showwxpaytitle=1&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&platform='.intval($_GET['platform']));
		}else{
			header('Location:/index.php?g=Wap&m='.$_GET['pay_type'].'&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&platform='.intval($_GET['platform']));
		}
	}
	
	public function go_pay(){
		if($_GET['pay_type'] == 'Weixin'){
			if (strpos(CONF_PATH,'Datavifnn')){
				$fileName='index_datapig.php';
			}elseif (strpos(CONF_PATH,'vifnnData')){
				$fileName='index_pigdata.php';
			}else {
				$fileName='index.php';
			}
			header('Location:/wxpay/'.$fileName.'?g=Wap&m=Weixin&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&showwxpaytitle=1&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&platform='.intval($_GET['platform']));
		}else{
			header('Location:/index.php?g=Wap&m='.$_GET['pay_type'].'&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&platform='.intval($_GET['platform']));
		}
	}

	/**
	 * 2015-05-12 李祥 iframe 通用方式支付 防止被屏蔽 借用 iframe做为中间层
	 */
	public function iframe_pay() {
		$url = '/index.php?g=Wap&m='.$_GET['pay_type'].'&a=pay&price='.$_GET['price'].'&orderName='.$_GET['orderName'].'&single_orderid='.$_GET['orderid'].'&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&platform='.intval($_GET['platform']);
		$this->assign('url', $url);
		$this->display('iframe_pay');
	}
}


?>