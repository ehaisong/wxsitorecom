<?php

class PintuanAction extends WapAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$timeout_order = M('pintuan_order')->where(array(
	'token'   => $this->token,
	'paid'    => 0,
	'addtime' => array('lt', time() - (5 * 60))
	))->select();

		foreach ($timeout_order as $time) {
			M('pintuan')->where(array('token' => $this->token, 'id' => $time['tid']))->setInc('quantity', $time['num']);
			M('pintuan_team')->where(array('token' => $this->token, 'head_id' => $time['id'], 'tid' => $time['tid']))->delete();
			M('pintuan_order')->where(array('token' => $this->token, 'id' => $time['id']))->delete();
		}

		session('pintuan_' . $this->token . '_siteurl', $this->siteUrl);
		cookie('pintuan_' . $this->token . '_siteurl', $this->siteUrl);
	}

	public function index()
	{
		session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
		cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
		$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
		echo '<script>window.location.href=\'' . $url . '\'</script>';
		exit();
	}

	public function payReturn()
	{
		$order = M('pintuan_order')->where(array('token' => $this->token, 'orderid' => $_GET['orderid']))->find();
		$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/mybuy/' . $this->token;

		if ($order['state'] == 1) {
			$this->success('购买成功', $url);
		}
		else {
			ThirdPayPintuan::index($_GET['orderid'], $order['paytype'], $order['third_id']);
			$this->success('购买成功', $url);
		}
	}

	public function buyinfo()
	{
		if ($_GET['orderid'] == 'start') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('该拼团未开始', $url);
		}
		else if ($_GET['orderid'] == 'end') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('该拼团已结束', $url);
		}
		else if ($_GET['orderid'] == 'close') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('该拼团已关闭', $url);
		}
		else if ($_GET['orderid'] == 'nonum') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('没有库存了', $url);
		}
		else if ($_GET['orderid'] == 'head') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('您是团长不能再参团了', $url);
		}
		else if ($_GET['orderid'] == 'myorder') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('您已经参加过此拼团了', $url);
		}
		else if ($_GET['orderid'] == 'overnumer') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('参团人数已满', $url);
		}
		else if ($_GET['orderid'] == 'ishead') {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $_GET['tid'] . '/' . $this->token;
			$this->error('您已经开过团了', $url);
		}
		else {
			$order = M('pintuan_order')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => intval($_GET['orderid'])))->find();
			$pintuan = M('pintuan')->where(array('token' => $this->token, 'id' => $order['tid']))->find();
			$userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
			$this->assign('order', $order);
			$this->assign('userinfo', $userinfo);
			$this->display();
		}
	}

	public function dobuy()
	{
		$order = M('pintuan_order')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => intval($_GET['orderid'])))->find();

		if ($order) {
			$pintuan = M('pintuan')->where(array('token' => $this->token, 'id' => $order['tid']))->find();
			$order_save = array('user_name' => $_GET['name'], 'user_tel' => $_GET['phone'], 'user_address' => $_GET['address'], 'message' => $_GET['message'], 'addtime' => time());
			$userinfo_save = array('truename' => $_GET['name'], 'tel' => $_GET['phone'], 'address' => $_GET['address']);
			M('pintuan_order')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => intval($_GET['orderid'])))->save($order_save);
			$payConfig = M('Alipay_config')->where(array('token' => $this->token, 'open' => 1))->find();
			$payConfigInfo = unserialize($payConfig['info']);
			$payConfig = array_map('trim', $payConfigInfo['weixin']);
			$pl = ($payConfig['open'] ? 0 : 1);
			M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save($userinfo_save);
			$url = 'token=' . $this->token . '&price=' . $order['price'] . '&wecha_id=' . $this->wecha_id . '&orderid=' . $order['orderid'] . '&orderName=' . $order['goods_name'] . '&platform=' . $pl;
			$this->redirect('Alipay/to_pay', array('token' => $this->token, 'price' => $order['price'], 'wecha_id' => $this->wecha_id, 'from' => 'Pintuan', 'orderid' => $order['orderid'], 'orderName' => $order['goods_name'], 'pay_type' => 'Weixin', 'platform' => $pl));
		}
		else {
			session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . (int) $order['tid'] . '/' . $this->token;
			$this->error('超时订单被删除，请重新下单', $url);
		}
	}

	public function share()
	{
		$id = intval($_GET['team']);
		$team = M('pintuan_team')->where(array('token' => $this->token, 'id' => $id))->find();
		session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
		cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);

		if (!empty($team)) {
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/detailinfo/' . $team['tid'] . '/' . $this->token . '/' . $team['type'] . '/' . $team['guize_id'] . '/' . $id;
		}
		else {
			$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/details/' . $_GET['tuan_id'] . '/' . $this->token;
		}

		echo '<script>window.location.href=\'' . $url . '\'</script>';
		exit();
	}

	public function myorder()
	{
		session('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
		cookie('pintuan_' . $this->token . '_wecha_id', $this->wecha_id);
		$url = $this->siteUrl . '/api/module/Pintuan/groupbuy/#/mybuy/' . $this->token;
		echo '<script>window.location.href=\'' . $url . '\'</script>';
		exit();
	}
}

?>
