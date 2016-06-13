<?php
class SeckillAction extends WapAction{
	public $action;
	public $action_id;
	public $shop;
	public $share_code;
	public $cdata;
	public $sessarr;
	public $uid;
	public function _initialize(){
		parent::_initialize();
		//获取活动id
		$this->action_id = $this->_get("id", "intval");
		$this->share_code = $this->_get("share_code", "trim");
		$this->action = M("seckill_action")->where(array("action_id" => $this->action_id, "action_open" => 0))->find();
		$this->shop = M("seckill_base_shop")->where(array("action_id" => $this->action_id, "shop_open" => 0))->select();
		$this->cdata = $this->action["action_key"];
		$this->sessarr = $_SESSION[$this->cdata];
	}

	public function index(){
		if (($this->action["action_is_attention"] == 2) && ($this->isSubscribe() == false)) {
			$this->memberNotice("", 1);
		}else {
			if (($this->action["action_is_reg"] == 1) && empty($this->fans["tel"])) {
				$this->memberNotice();
			}
		}
		//链接错误
		if (!$this->action_id || empty($this->action)) {
		        //活动不存在
			$this->error("亲，你来晚了，活动结束了！");
		}

		$_SESSION["suid"] = M("seckill_users")->where(array("user_aid" => $this->action_id, "user_wechaid" => $this->wecha_id))->getField("user_id");

		if ($this->wecha_id) {
			$res = $this->check_user(array("user_aid" => $this->action_id, "user_wechaid" => $this->wecha_id));
			$data = array(
				"user_aid" => (int) $this->action_id, 
				"user_nickname" => !empty($this->fans["wechaname"]) ? $this->fans["wechaname"] : "神秘用户", 
				"user_headimgurl" => !empty($this->fans["portrait"]) ? $this->fans["portrait"] : $this->siteUrl . "/tpl/static/seckill/images/yk.jpg", 
				"user_shareid" => $this->get_key(32), 
				"user_wechaid" => $this->wecha_id, 
				"user_sex" => !empty($this->fans["sex"]) ? $this->fans["sex"] : 0, 
				"user_tel" => !empty($this->fans["tel"]) ? $this->fans["tel"] : "", 
				"user_qq" => !empty($this->fans["qq"]) ? $this->fans["qq"] : "", 
				"user_address" => !empty($this->fans["address"]) ? $this->fans["address"] : "", 
				"user_province" => !empty($this->fans["province"]) ? $this->fans["province"] : "", 
				"user_city" => !empty($this->fans["city"]) ? $this->fans["city"] : "", 
				"user_mintime" => 0, 
				"token" => $this->token
			);
			if ($res) {
				unset($data["user_mintime"]);
				unset($data["user_shareid"]);
				//该微信用户已经参加该活动，更新该用户的微信信息
				$updata = M("seckill_users")->where(array("user_aid" => $this->action_id, "user_wechaid" => $this->wecha_id))->save($data);
			}else {
				$uid = $this->add_user($data);
				$_SESSION["suid"] = $uid;
			}
		}

		$min = M("seckill_users")->where(array("user_aid" => $this->action_id, "user_wechaid" => $this->wecha_id))->field("user_mintime")->find();
		if (isset($min["user_mintime"]) && (0 < $min["user_mintime"])) {
			$user_mintime = $this->action["action_sdate"] - $min["user_mintime"];
		}
		//判断活动状态
		if (isset($this->action["action_sdate"]) && isset($this->action["action_edate"])) {
			$start_time = (!empty($user_mintime) ? $user_mintime : (int) $this->action["action_sdate"]);
			$end_time = (int) $this->action["action_edate"];

			if (time() < $start_time) {
			        //即将开始抢购
				$action_status = 0;
			}else {
			        if (($start_time <= time()) && (time() < $end_time)) {
				//抢购中
					$action_status = 1;
				}else {
					//抢购已结束
					$action_status = 2;
				}
			}
		}

		$scode1 = M("seckill_users")->where(array("user_aid" => $this->action_id, "user_wechaid" => $this->wecha_id))->getField("user_shareid");
		$this->assign("token", $this->token);
		$this->assign($this->action);
		$this->assign("min", $min);
		$this->assign("s_time", $start_time);
		$this->assign("status", $action_status);
		$this->assign("wecha_id", $this->wecha_id);
		$this->assign("action_id", $this->action_id);
		$this->assign("shop", $this->shop);
		$this->assign("uid", session("suid"));
		$this->assign("share_code", $scode1);
		$share_data = M("seckill_share")->where(array("user_aid" => $this->action_id, "user_share" => $scode1))->order("open_time desc")->limit(3)->select();
		$udata = M("seckill_users")->where(array("user_aid" => $this->action_id))->order("user_mintime desc")->select();

		foreach ($udata as $key => $val ) {
			if ($val["user_wechaid"] == $this->wecha_id) {
				$num = $key + 1;
			}
		}

		$this->assign("share", $share_data);
		$this->assign("num", $num);
		$this->display();
	}
		
	/**
	* 粉丝进入邀请页面
	* shop_invite()
	*/
	public function shop_invite(){
		if (empty($this->action_id) && empty($this->share_code)) {
			$this->error("没有该活动,分享码错误！");
		}
		//获取分享的粉丝列表
		$share_data = M("seckill_share")->where(array("user_aid" => $this->action_id, "user_share" => $this->share_code))->order("open_time desc")->select();
		$datau = array("user_shareid" => $this->share_code);//用户分享的key
		$resultu = $this->check_user($datau);
		//判断是否是分享链接
		if ($this->share_code && $resultu && ($resultu["user_wechaid"] != $this->wecha_id) && $this->wecha_id) {
			$bool_share = M("seckill_share")->where(array("user_aid" => $this->action_id, "user_share" => $this->share_code, "share_wechaid" => $this->wecha_id))->find();

			if (!$bool_share) {
				$mintime = rand($this->action["rand_min_time"], $this->action["rand_max_time"]);
				//提前抢购时间
				$where = array("user_aid" => $this->action_id, "user_shareid" => $this->share_code);
				$data["user_mintime"] = array("exp", "user_mintime+" . $mintime);
				M("seckill_users")->where($where)->save($data);
					//添加分享用户
				$share = array(
					"user_aid" => $this->action_id, 
					"user_share" => $this->share_code, 
					"share_nickname" => !empty($this->fans["wechaname"]) ? $this->fans["wechaname"] : "神秘用户", 
					"share_pic" => !empty($this->fans["portrait"]) ? $this->fans["portrait"] : $this->siteUrl . "tpl/User/default/common/images/portrait.jpg", 
					"share_time" => $mintime, 
					"open_time" => time(), 
					"share_wechaid" => $this->wecha_id
				);
				M("seckill_share")->add($share);
			}
		}
		//分享时所需参数
		$this->assign("token", $this->token);
		$this->assign($this->action);
		$this->assign($resultu);
		$this->assign("share", $share_data);
		$this->assign("action_id", $this->action_id);
		$this->assign("share_code", $this->share_code);
		$this->display();
	}
		 
	/**
	* 查看邀请记录
	**/
	public function see_invite(){
		if (empty($this->action_id) && empty($this->share_code)) {
			$this->error("没有该活动,分享码错误！");
		}
		//获取分享的粉丝列表
		$share_data = M("seckill_share")->where(array("user_aid" => $this->action_id, "user_share" => $this->share_code))->order("open_time desc")->select();
		$total_time = M("seckill_users")->where(array("user_aid" => $this->action_id, "user_shareid" => $this->share_code))->field("user_mintime")->find();
		$this->assign("share_data", $share_data);
		$this->assign("total_time", $this->secToTime($total_time["user_mintime"]));
		$this->assign("total_sec", $total_time["user_mintime"]);
		$this->assign("token", $this->token);
		$this->assign("action_id", $this->action_id);
		$this->assign("share_code", $this->share_code);
		$this->display();
	}

	/**
	* 生成随机key
	* @param  integer $length [description]
	* @return [type]          [description]
	*/
	private function get_key($length = 16){
		$str = substr(md5(time() . mt_rand(1000, 9999)), 0, $length);
		return $str;
	}
		
	/**
	* 格式化时间
	* @param  $time
	* @return [string]          [description]
	*/
	private function secToTime($times){
		$result = "00:00:00";

		if (0 < $times) {
			$hour = floor($times / 3600);
			$hour = (10 < $hour ? $hour : "0" . $hour);
			$minute = floor(($times - (3600 * $hour)) / 60);
			$minute = (10 < $minute ? $minute . "'" : "0" . $minute . "'");
			$second = floor(($times - (3600 * $hour) - (60 * $minute)) % 60);
			$second = (10 < $second ? $second . "\"" : "0" . $second . "\"");
			$result = $hour . ":" . $minute . ":" . $second;
		}

		return $result;
	}

	/**
	* 更新用户积分
	*/
	public function u_share(){
		if (IS_POST) {
			$point = $this->_post("point", "intval");
			//增加用户积分
			$awhere = array("user_id" => $_SESSION["suid"]);			
			$adata["user_mintime"] = array("exp", "user_mintime+" . $point);
			M("seckill_users")->where($awhere)->save($adata);		
			//更新分享用户状态
			$swhere = array("share_id" => $this->_post("share_id", "intval"));			
			$sdata["is_opened"] = 1;
			$result = M("seckill_share")->where($swhere)->save($sdata);
			echo json_encode(array("status" => 1));
			exit();
		}else {
			echo json_encode(array("status" => -1));
		}
	}

	/**
	* 添加用户
	* @author  zrone <xujining2008@126.com>
	*/
	public function add_user($data){
			/*******************************************************************			
			`user_id` int not null auto_increment ,
			`user_aid` int not null comment "活动id" ,
			`user_nickname` varchar(20) default "" comment "普通用户昵称" ,
			`user_headimgurl` varchar(500) default "" comment "用户用户头像" ,
			`user_shareid` varchar(100) default "" comment "用户openid" ,
			`user_sex` tinyint default 0 comment "用户性别" ,
			`user_tel` varchar(20) default "" comment "用户电话" ,
			`user_qq` varchar(20) default "" comment "用户QQ" ,
			`user_address` varchar(50) default "" comment "用户address" ,
			`user_province` varchar(50) default "" comment "用户province" ,
			`user_city` varchar(50) default "" comment "用户city"
			*******************************************************************/		

		if (!is_array($data)) {
			return false;
		}else {
			$insert_id = M("seckill_users")->add($data);

			if ($insert_id) {
				return $insert_id;
			}else {
				return false;
			}
		}
	}

	/**
	* 判断用户是否已参过活动
	* @return bool
	* @param string $user_shareid
	* @author  zrone <xujining2008@126.com>
	*/
	private function check_user($data){
		if (!is_array($data)) {
			return false;
		}

		$result = M("seckill_users")->where($data)->find();

		if ($result) {
			return $result;
		}

		return false;
	}
		
	//商品
	public function shop(){
		if (($this->action["action_is_attention"] == 1) && ($this->isSubscribe() == false)) {
			$this->memberNotice("", 1);
		}else {
			if (($this->action["action_is_reg"] == 1) && empty($this->fans["tel"])) {
				$this->memberNotice();
			}
		}

		$seckill_users = $this->check_user(array("user_aid" => $this->action["action_id"], "user_wechaid" => $this->wecha_id));
		if (isset($this->action["action_sdate"]) && isset($this->action["action_edate"])) {
			$start = (int) $this->action["action_sdate"];
			$end = (int) $this->action["action_edate"];

			if (empty($seckill_users)) {
				$mintime = 0;
			}else {
				$mintime = $seckill_users["user_mintime"];
			}

			if (time() < ($start - $mintime)) {
			       //即将开始抢购
				$this->error("活动未开始！");
			}else if ($end < time()) {
			       //抢购已结束
				$this->error("活动已结束！");
			}
		}

		$sid = $this->_get("sid");
		$data = M("seckill_base_shop")->where(array("shop_id" => (int) $sid))->find();
		$data["shop_detail"] = htmlspecialchars_decode($data["shop_detail"]);
		$tdata = M("seckill_shop_thum")->field("shop_thum, shop_big")->where(array("shop_id" => (int) $sid))->select();
		if (($this->action["action_edate"] - time()) < 0) { //活动已结束
			$this->assign("buys", 0);
		}else if (isset($this->fans)) {	//没有获取到微信用户信息
			$this->assign("buys", 2);
		}else {
			$this->assign("buys", 1);
		}

		$book = M("seckill_book")->where(array("wecha_id" => $this->wecha_id, "book_aid" => $this->action["action_id"], "book_sid" => $sid))->find();

		if (!empty($book)) {
			$this->error("您已经秒杀过该商品,请勿重复秒杀");
			exit();
		}

		if (empty($data)) {
			$this->error("很抱歉商品已下架！");
			exit();
		}else if ($data["shop_num"] <= 0) {
		        //抢购结束
			$this->assign("downcart", 1);
			$this->error("商品已经被抢光啦！");
			exit();
		}else {
			$index = 1;

			foreach ($tdata as $key => $value ) {
				$thum_data[$key]["index"] = $index;
				$thum_data[$key]["shop_thum"] = $value["shop_thum"];
				$thum_data[$key]["shop_big"] = $value["shop_big"];
				$index++;
			}
		}
                //用户信息
		$db = M("Userinfo");
		$list = $db->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->find();
		$this->assign("list", $list);
		$uid = $_SESSION["suid"];
		$udata = M("seckill_users")->where(array("user_id" => (int) $uid))->find();
		$this->assign($udata);
		$this->assign($data);
		$this->assign("tdata", $thum_data);
		$this->assign("wecha_id", $this->wecha_id);
		$this->display();
	}

	//抢购，生成订单
	public function buy(){
		if (IS_POST) {
				/****************************************************************************
				`book_sn` varchar(32) not null comment "订单号" ,
				`book_aid` int not null comment "活动id" ,
				`book_sid` int not null comment "商品id" ,
				`book_time` int not null comment "订单时间" ,
				`book_uid` int not null comment "订单用户" ,
				`book_status` int not null comment "订单状态 0 待发货 1 已发货 2 已取消" ,
				****************************************************************************/
			$sid = $_POST["shop_id"];
			$uid = $_SESSION["suid"];
			//活动基本信息
			$adata = M("seckill_action")->field("action_sdate, action_edate")->where(array("action_id" => $this->action_id, "action_open" => 0))->find();
			//当前商品基本信息
			$sdata = M("seckill_base_shop")->field("shop_num, shop_price")->where(array("shop_id" => (int) $sid, "shop_open" => 0))->find();
			//获取用户信息
			$udata = M("seckill_users")->where(array("user_id" => (int) $uid))->find();
			$min = $udata["user_mintime"];
			$book = M("seckill_book")->where(array("wecha_id" => $this->wecha_id, "book_aid" => $this->action_id, "book_sid" => $sid))->find();
			if (isset($sdata["shop_num"]) && ($sdata["shop_num"] <= 0)) {
				echo json_encode(array("status" => -1, "msg" => "抢光啦！"));
				exit();
			}else {
				if (isset($adata["action_sdate"]) && (time() <= $adata["action_sdate"] - $min)) {
					echo json_encode(array("status" => -2, "msg" => "还没到抢购时间哦，小秘籍分享给好友提前抢购！"));
					exit();
				}else {
					if (isset($adata["action_edate"]) && (0 < (time() - $adata["action_edate"]))) {
						echo json_encode(array("status" => -3, "msg" => "本地活动已结束！"));
						exit();
					}else if (empty($udata)) {
						echo json_encode(array("status" => -4, "msg" => "请先关注本站公众号！"));
						exit();
					}else if (!empty($book)) {
						echo json_encode(array("status" => -6, "msg" => "您已经秒杀过该商品！"));
						exit();
					}else {
						echo json_encode(array("status" => 0, "msg" => "ok"));
						exit();
					}
				}
			}
		}else {
			echo json_encode(array("status" => -5, "msg" => "网络异常！"));
			exit();
		}
	}

	private function total(){
		$book = M("seckill_book");
		$num = $book->count("book_id");
		return $num + 1;
	}

	//生成订单入库
	private function c_book($data){
		if (!is_array($data)) {
			return false;
		}

		$insert_id = M("seckill_book")->add($data);

		if (0 < $insert_id) {
			return true;
		}else {
			return false;
		}
	}

	//支付
	public function pay(){
		if (IS_POST) {
			$token = (isset($_POST["token"]) ? trim($_POST["token"]) : "");
			$wecha_id = (isset($_POST["wecha_id"]) ? trim($_POST["wecha_id"]) : $this->wecha_id);
			$price1 = (isset($_POST["price"]) ? (double) $_POST["price"] : 0);
			$tran = (isset($_POST["tran"]) ? (double) $_POST["tran"] : 0);
			$orderName = (isset($_POST["title"]) ? trim($_POST["title"]) : time());
			$tel = (isset($_POST["tel"]) ? trim($_POST["tel"]) : "");
			$cel = (isset($_POST["cel"]) ? trim($_POST["cel"]) : "");
			$addr = (isset($_POST["addr"]) ? trim($_POST["addr"]) : "");
			$aid = (isset($_POST["aid"]) ? trim($_POST["aid"]) : "");
			$sid = (isset($_POST["sid"]) ? trim($_POST["sid"]) : "");
			$uid = (isset($_POST["uid"]) ? trim($_POST["uid"]) : "");
			$name = (isset($_POST["name"]) ? trim($_POST["name"]) : "");
			$price = $price1 + $tran;

			if (empty($token)) {
				$this->error("获取商家信息失败！");
			}else if (empty($addr)) {
				$this->error("收货地址不能为空！");
			}else if (empty($name)) {
				$this->error("收货人姓名不能为空！");
			}else {
				if (empty($tel) && empty($cel)) {
					$this->error("请填写您的联系电话或者手机！");
				}
			}

			$seckill_base_shop = M("seckill_base_shop")->field("shop_num")->where(array("shop_id" => (int) $sid, "shop_open" => 0))->find();

			if ($seckill_base_shop["shop_num"] <= 0) {
				$this->error("未及时付款，货品已被抢光");
			}

			$book = M("seckill_book")->where(array("wecha_id" => $wecha_id, "book_aid" => $aid, "book_sid" => $sid))->find();

			if (!empty($book)) {
				$this->error("您已经秒杀过该商品");
			}

			$orderid = date("YmdHis", time()) . rand(10000, 99999);
			$db = M("Userinfo");
			$datas["tel"] = $cel;
			$datas["wechaname"] = $name;
			$datas["address"] = $addr;
			$where["token"] = $this->token;
			$where["wecha_id"] = $this->wecha_id;
			$find = $db->where($where)->find();

			if ($find == NULL) {
				$db->add($datas);
			}else {
				$db->where($where)->save($datas);
			}

			$data = array(
				"paytype" => "", 
				"paid" => 0, 
				"third_id" => "", 
				"price" => $price, 
				"book_aid" => $aid, 
				"book_sid" => $sid, 
				"book_time" => time(), 
				"pay_time" => 0, 
				"book_uid" => $uid, 
				"book_status" => 3, //未付款
				"deli_addr" => $addr, 
				"true_name" => $name, 
				"tel" => $tel, 
				"cel" => $cel, 
				"wecha_id" => $wecha_id, 
				"token" => $token, 
				"orderid" => $orderid
			);
			$info = $this->c_book($data);
			$url = U("Alipay/pay", array(
				"token" => $token, 
				"wecha_id" => $wecha_id, 
				"price" => $price, 
				"from" => "Seckill", 
				"orderName" => $orderName, 
				"orderid" => $orderid, 
				"notOffline" => 1
			));
			header("Location:" . $url);
		}else {
			$orderid = $this->_get("orderid", "trim");
			$token = $this->token;
			$price = (double) $this->_get("price", "trim");
			$orderName = $this->_get("orderName", "trim");
			
			$url = U("Alipay/pay", array(
				"token" => $token, 
				"wecha_id" => $this->wecha_id, 
				"price" => $price, 
				"from" => "Seckill", 
				"orderName" => $orderName, 
				"orderid" => $orderid
			));
			header("Location:" . $url);
		}
	}

	/**
	* 支付成功回调页面
	* @return [type] [description]
	*/
	public function payReturn(){
		if (isset($_GET["nohandle"])) {
			$order = M("seckill_book")->where(array("orderid" => $_GET["orderid"]))->find();
			header("Location:" . U("Seckill/my_cart", array("token" => $this->token, "wecha_id" => $this->wecha_id, "id" => $order["book_aid"])));
		}else {
			$orderid = trim($_GET["orderid"]);
			ThirdPaySeckill::index($orderid);
		}
	}

	public function my_cart(){
		if (($this->action["action_is_attention"] == 1) && ($this->isSubscribe() == false)) {
			$this->memberNotice("", 1);
		}else {
			if (($this->action["action_is_reg"] == 1) && empty($this->fans["tel"])) {
				$this->memberNotice();
			}
		}

		$uid = (int) $_SESSION["suid"];
		$uinfo = M("seckill_users")->find($uid);
		$model = new Model();
		$sql = "select a.shop_name ,a.shop_id ,a.shop_price, a.shop_img,a.shop_num, b.orderid, b.paid, b.price,b.paytype,b.book_id from " . C("DB_PREFIX") . "seckill_base_shop as a left join " . C("DB_PREFIX") . "seckill_book as b on a.shop_id = b.book_sid where b.book_uid = $uid and b.token = '$this->token' and b.book_aid = $this->action_id order by b.paid , b.book_time desc";
		$data = $model->query($sql);
		$num = count($data);
		$this->assign("book", $data);
		$this->assign($uinfo);
		$this->assign($shop);
		$this->assign("num", $num);
		$this->display();
	}

	public function cancle_book(){
		if (IS_POST) {
			$orderid = trim($_POST["orderid"]);
			$info = M("seckill_book")->where(array("orderid" => $orderid))->delete();

			if (!$info) {
				echo json_encode(array("status" => 0));
				exit();
			}else {
				echo json_encode(array("status" => 1));
				exit();
			}
		}else {
			header("HTTP/1.1 404 Not Found");
		}
	}
}


?>