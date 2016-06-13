<?php

class PintuanAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction('Pintuan');
		$timeout_order = M('pintuan_order')->where(array(
	'token'   => $this->token,
	'paid'    => 0,
	'addtime' => array('lt', time() - (5 * 60))
	))->select();

		foreach ($timeout_order as $time) {
			M('pintuan_team')->where(array('token' => $this->token, 'head_id' => $time['id'], 'tid' => $time['tid']))->delete();
			M('pintuan_order')->where(array('token' => $this->token, 'id' => $time['id']))->delete();
		}
	}

	public function index()
	{
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['search'])) {
			$where['title'] = array('like', '%' . $_GET['search'] . '%');
			$where_page['search'] = $_GET['search'];
		}

		$count = M('pintuan')->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$list = M('pintuan')->where($where)->order('sort desc,addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $lk => $lv) {
			$list[$lk]['tuan_count'] = M('pintuan_order')->where(array('token' => $this->token, 'tid' => $lv['id'], 'paid' => 1))->count();
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function set()
	{
		$id = intval($_GET['id']);
		$set = M('pintuan')->where(array('token' => $this->token, 'id' => $id))->find();

		if (IS_POST) {
			$pintuan_data = array('keyword' => $_POST['keyword'], 'info' => trim($_POST['info']), 'title' => $_POST['title'], 'wxpic' => $_POST['wxpic'], 'wxinfo' => $_POST['wxinfo'], 'price' => $_POST['price'] * 100, 'facepic' => $_POST['facepic'], 'startdate' => strtotime($_POST['startdate']), 'enddate' => strtotime($_POST['enddate']), 'goods_info' => $_POST['goods_info'], 'tuan_info' => $_POST['tuan_info'], 'display' => $_POST['display'], 'custom_sharetitle' => trim($_POST['custom_sharetitle']), 'custom_sharedsc' => trim($_POST['custom_sharedsc']), 'sort' => $_POST['sort'], 'tel' => $_POST['tel'], 'quantity' => intval($_POST['quantity']));
			$changepic = $_POST['changepic'];
			$number = $_POST['number'];
			$discount = $_POST['discount'];

			if ($set) {
				M('pintuan_changepic')->where(array('token' => $this->token, 'tid' => $id))->delete();

				foreach ($changepic as $ck => $cv) {
					$add_changepic = array('token' => $this->token, 'tid' => $id, 'pic' => $cv);
					M('pintuan_changepic')->add($add_changepic);
				}

				$tuan_count = M('pintuan_order')->where(array('token' => $this->token, 'tid' => $id))->count();
				if (($tuan_count < 1) && ($set['display'] == 1)) {
					M('pintuan_guize')->where(array('token' => $this->token, 'tid' => $id))->delete();

					foreach ($number as $nk => $nv) {
						$add_guize = array('token' => $this->token, 'tid' => $id, 'number' => $nv, 'discount' => $discount[$nk] * 10);
						M('pintuan_guize')->add($add_guize);
					}
				}

				M('pintuan')->where(array('token' => $this->token, 'id' => $id))->save($pintuan_data);
				$this->handleKeyword($id, 'Pintuan', $this->_post('keyword', 'trim'));
				M('pintuan_order')->where(array('token' => $this->token, 'tid' => $id, 'paid' => 1))->save(array('jilu_enddate' => $pintuan_data['enddate']));
				$this->success('修改成功', U('User/Pintuan/index', array('token' => $this->token)));
			}
			else {
				$pintuan_data['token'] = $this->token;
				$pintuan_data['addtime'] = time();
				$id = M('pintuan')->add($pintuan_data);

				foreach ($changepic as $ck => $cv) {
					$add_changepic = array('token' => $this->token, 'tid' => $id, 'pic' => $cv);
					M('pintuan_changepic')->add($add_changepic);
				}

				foreach ($number as $nk => $nv) {
					$add_guize = array('token' => $this->token, 'tid' => $id, 'number' => $nv, 'discount' => $discount[$nk] * 10);
					M('pintuan_guize')->add($add_guize);
				}

				$this->handleKeyword($id, 'Pintuan', $this->_post('keyword', 'trim'));
				$this->success('添加成功', U('User/Pintuan/index', array('token' => $this->token)));
			}
		}
		else {
			$this->assign('set', $set);

			if ($set) {
				$changepic_list = M('pintuan_changepic')->where(array('token' => $this->token, 'tid' => $id))->select();
				$guize_list = M('pintuan_guize')->where(array('token' => $this->token, 'tid' => $id))->order('discount desc')->select();
				$this->assign('changepic_list', $changepic_list);
				$this->assign('changepicnum', count($changepic_list));
				$this->assign('guize_list', $guize_list);
				$this->assign('guizenum', count($guize_list));
				$tuan_count = M('pintuan_order')->where(array('token' => $this->token, 'tid' => $id))->count();
				$this->assign('tuan_count', $tuan_count);
			}

			$this->display();
		}
	}

	public function del()
	{
		$id = intval($_GET['id']);

		if (0 < $id) {
			M('pintuan')->where(array('token' => $this->token, 'id' => $id))->delete();
			M('pintuan_changepic')->where(array('token' => $this->token, 'tid' => $id))->delete();
			M('pintuan_guize')->where(array('token' => $this->token, 'tid' => $id))->delete();
		}

		$this->success('删除成功', U('User/Pintuan/index', array('token' => $this->token)));
	}

	public function replyset()
	{
		$reply = M('pintuan_reply')->where(array('token' => $this->token))->find();

		if (IS_POST) {
			if ($reply) {
				M('pintuan_reply')->where(array('token' => $this->token))->save($_POST);
				$id = $reply['id'];
			}
			else {
				$_POST['token'] = $this->token;
				$id = M('pintuan_reply')->add($_POST);
			}

			$this->handleKeyword($id, 'Pintuan', $this->_post('keyword', 'trim'));
			$this->success('保存成功', U('User/Pintuan/replyset', array('token' => $this->token)));
		}
		else {
			$this->assign('set', $reply);
			$this->display();
		}
	}

	public function classify()
	{
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['search'])) {
			$where['name'] = array('like', '%' . $_GET['search'] . '%');
			$where_page['search'] = $_GET['search'];
		}

		$count = M('pintuan_classify')->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$list = M('pintuan_classify')->where($where)->order('sort desc')->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function classifyset()
	{
		$id = intval($_GET['id']);
		$classify = M('pintuan_classify')->where(array('token' => $this->token, 'id' => $id))->find();

		if (IS_POST) {
			if ($classify) {
				M('pintuan_classify')->where(array('token' => $this->token, 'id' => $id))->save($_POST);
			}
			else {
				$_POST['token'] = $this->token;
				M('pintuan_classify')->add($_POST);
			}

			$this->success('保存成功', U('User/Pintuan/classify', array('token' => $this->token)));
		}
		else {
			$this->assign('set', $classify);
			$this->display();
		}
	}

	public function classifydel()
	{
		$id = intval($_GET['id']);
		M('pintuan_classify')->where(array('token' => $this->token, 'id' => $id))->delete();
		$this->success('删除成功', U('User/Pintuan/classify', array('token' => $this->token)));
	}

	public function record()
	{
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (0 < intval($_GET['id'])) {
			$where['tid'] = intval($_GET['id']);
			$where_page['id'] = $_GET['id'];
			$pintuan = M('pintuan')->where(array('token' => $this->token, 'id' => intval($_GET['id'])))->find();
			$this->assign('pintuan', $pintuan);
		}

		$count = M('pintuan_team')->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$list = M('pintuan_team')->where($where)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $lk => $lv) {
			$list[$lk]['order'] = M('pintuan_order')->where(array('token' => $this->token, 'id' => $lv['head_id']))->find();
			$list[$lk]['guize'] = M('pintuan_guize')->where(array('token' => $this->token, 'id' => $lv['guize_id']))->find();
			$list[$lk]['pintuan'] = M('pintuan')->where(array('token' => $this->token, 'id' => $lv['tid']))->find();
			$list[$lk]['guize_list'] = M('pintuan_guize')->where(array('token' => $this->token, 'tid' => $lv['tid']))->select();
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function order()
	{
		$team = M('pintuan_team')->where(array('token' => $this->token, 'id' => intval($_GET['team_id'])))->find();
		$this->assign('team', $team);
		$pintuan = M('pintuan')->where(array('token' => $this->token, 'id' => $team['tid']))->find();
		$this->assign('pintuan', $pintuan);
		$jilu_guize = M('pintuan_guize')->where(array('token' => $this->token, 'id' => $team['guize_id']))->find();
		$config_list = M('pintuan_guize')->where(array('token' => $this->token, 'tid' => $team['tid']))->order('discount desc')->select();
		$this_guize = array();

		foreach ($config_list as $con) {
			if (($con['discount'] <= $jilu_guize['discount']) && ($con['number'] <= $team['count'])) {
				$this_guize[] = $con;
			}
		}

		if (0 < count($this_guize)) {
			$guize = $this_guize[0];
		}
		else {
			$guize = $jilu_guize;
		}

		$where['token'] = $this->token;
		$where_page['token'] = $this->token;
		$where['team_id'] = intval($_GET['team_id']);
		$where_page['token'] = intval($_GET['team_id']);

		if (!empty($_GET['search'])) {
			$where['orderid|user_name|user_tel|user_address'] = array('like', '%' . $_GET['search'] . '%');
			$where_page['search'] = $_GET['search'];
		}

		$count = M('pintuan_order')->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$list = M('pintuan_order')->where($where)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $lk => $lv) {
			$shifu = $lv['price'];
			$yingfu = (($pintuan['price'] * $guize['discount']) / 10000) * $lv['num'];

			if ($lv['paid'] < 1) {
				$endtime = $lv['addtime'] + (5 * 60);
				$list[$lk]['state_text'] = '未付款<br>' . date('m-d H:i:s', $endtime) . '自动删除<br>';
			}
			else if (time() < $pintuan['enddate']) {
				$list[$lk]['state_text'] = '完成付款<br>';
			}
			else if ($jilu_guize['number'] <= $team['count']) {
				if ($shifu == $yingfu) {
					$list[$lk]['state_text'] = '完成付款<br>';
				}
				else {
					$list[$lk]['state_text'] = '完成付款<br>应退' . ($shifu - $yingfu) . '元<br>' . ($lv['isbucha'] ? '已退款<br>' : '未退款<br>');
				}

				if ($lv['state'] == 1) {
					$list[$lk]['state_text'] .= '未发货';
				}
				else if ($lv['state'] == 2) {
					$list[$lk]['state_text'] .= '已发货';
				}
			}
			else if ($team['type']) {
				$list[$lk]['state_text'] = '完成付款<br>拼团失败应退' . $shifu . '元<br>' . ($lv['isbucha'] ? '已退款<br>' : '未退款<br>');
			}
			else if ($lv['state'] == 1) {
				$list[$lk]['state_text'] .= '未发货';
			}
			else if ($lv['state'] == 2) {
				$list[$lk]['state_text'] .= '已发货';
			}

			if ($lv['ishead']) {
				$this->assign('head', $lv);
			}
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function orderinfo()
	{
		if (IS_POST) {
			$data = array('state' => $_POST['state'], 'express_name' => $_POST['express_name'], 'express_num' => $_POST['express_num']);
			$where = array('token' => $this->token, 'id' => intval($_POST['id']));
			$order = M('pintuan_order')->where($where)->find();
			$orderid = $order['orderid'];

			if ($_POST['state'] == 2) {
				$model = new templateNews();
				$model->sendTempMsg('OPENTM200565259', array('href' => $this->siteUrl . U('Wap/Pintuan/myorder', array('token' => $this->token)), 'wecha_id' => $order['wecha_id'], 'first' => '您的订单' . $orderid . '已发货', 'keyword1' => $orderid, 'keyword2' => $_POST['express_name'], 'keyword3' => $_POST['express_num'], 'remark' => date('Y年m月d日H时i分s秒')));
			}

			M('pintuan_order')->where($where)->save($data);
			$this->success('设置成功', U('User/Pintuan/orderinfo', array('token' => $this->token, 'id' => $_POST['id'])));
		}
		else {
			$order = M('pintuan_order')->where(array('token' => $this->token, 'id' => intval($_GET['id'])))->find();
			$pintuan = M('pintuan')->where(array('token' => $this->token, 'id' => $order['tid']))->find();
			$team = M('pintuan_team')->where(array('token' => $this->token, 'id' => $order['team_id']))->find();
			$jilu_guize = M('pintuan_guize')->where(array('token' => $this->token, 'id' => $team['guize_id']))->find();
			$config_list = M('pintuan_guize')->where(array('token' => $this->token, 'tid' => $team['tid']))->order('discount desc')->select();
			$this_guize = array();

			foreach ($config_list as $con) {
				if (($con['discount'] <= $jilu_guize['discount']) && ($con['number'] <= $team['count'])) {
					$this_guize[] = $con;
				}
			}

			if (0 < count($this_guize)) {
				$guize = $this_guize[0];
			}
			else {
				$guize = $jilu_guize;
			}

			$shifu = $order['price'];
			$yingfu = (($pintuan['price'] * $guize['discount']) / 10000) * $order['num'];

			if ($order['paid'] == 0) {
				$order['state_text'] = '未付款';
			}
			else if (time() < $pintuan['enddate']) {
				$order['state_text'] = '完成付款';
			}
			else if ($jilu_guize['number'] <= $team['count']) {
				if ($shifu == $yingfu) {
					$order['state_text'] = '完成付款';
				}
				else {
					$order['state_text'] = '完成付款---应退' . ($shifu - $yingfu) . '元---' . ($order['isbucha'] ? '已退款' : '未退款');
				}

				if ($order['state'] == 1) {
					$order['state_text'] .= '---未发货';
				}
				else if ($order['state'] == 2) {
					$order['state_text'] .= '---已发货';
				}
			}
			else if ($team['type']) {
				$order['state_text'] = '完成付款---拼团失败应退' . $shifu . '元---' . ($order['isbucha'] ? '已退款' : '未退款');
			}
			else if ($order['state'] == 1) {
				$order['state_text'] .= '---未发货';
			}
			else if ($order['state'] == 2) {
				$order['state_text'] .= '---已发货';
			}

			$paytype_name = array('cardpay' => '会员卡支付', 'alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpayComputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线');
			$order['paystr'] = $paytype_name[strtolower($order['paytype'])];
			$this->assign('thisOrder', $order);
			$this->display();
		}
	}

	public function refund()
	{
		$tid = (int) $_GET['tid'];
		$team_id = (int) $_GET['team_id'];
		$token = trim($_GET['token']);
		$pintuan = M('pintuan')->where(array('id' => $tid))->find();

		if ($_SERVER['REQUEST_TIME'] < $pintuan['enddate']) {
			$this->error('拼团活动到' . date('Y-m-d H:i:s', $pintuan['enddate']) . '结束，请到时再做退款操作');
		}
		else {
			$team = M('pintuan_team')->alias('p')->where(array('p.id' => $team_id))->join(C('DB_PREFIX') . 'pintuan_guize g ON p.guize_id = g.id')->find();

			if ($team['isrefund'] == 1) {
				$this->error('该团已经退款，请勿重复操作');
			}

			if (0 < $team['type']) {
				$guize = M('pintuan_guize')->where(array('token' => $token, 'id' => $team['guize_id']))->find();
			}
			else {
				$guize = M('pintuan_guize')->where(array('token' => $token, 'tid' => $team['tid']))->order('discount DESC')->find();
			}

			$allguize = M('pintuan_guize')->where(array('tid' => $tid, 'token' => $token))->order('discount ASC')->select();
			$order_list = M('pintuan_order')->where(array('tid' => $tid, 'team_id' => $team_id, 'token' => $token, 'paid' => 1))->select();

			if (empty($order_list)) {
				$this->error('未获取到需要退款的订单');
			}

			if ($guize['number'] <= $team['count']) {
				foreach ($allguize as $key => $value) {
					$discount = 0;

					if ($value['number'] <= $team['count']) {
						$discount = $value['discount'];
						break;
					}
				}

				$msg = '该团拼团成功,并且退还超出的团价';
			}
			else {
				$discount = 0;
				$msg = '该团拼团失败,已退回全部款项';
			}

			if (($guize['number'] <= $team['count']) && (($order_list[0]['price'] - (($pintuan['price'] * $discount) / 10000)) <= 0)) {
				$this->error('退款失败,最终达标级别和开团级别一致所以没有需要返还的金额');
			}

			$successcount = 0;

			foreach ($order_list as $kk => $vv) {
				$refund_money = $vv['price'] - (($pintuan['price'] * $discount) / 10000);

				if (0 < $refund_money) {
					$result = $this->Dorefund($vv, $refund_money);

					if ('SUCCESS' == $result['result_code']) {
						$data = array();
						$data['tid'] = $tid;
						$data['team_id'] = $team_id;
						$data['token'] = $token;
						$data['orderid'] = $vv['id'];
						$data['wecha_id'] = $vv['wecha_id'];
						$data['money'] = $refund_money;
						$data['refundtime'] = $_SERVER['REQUEST_TIME'];
						M('pintuan_refundlog')->add($data);

						if ($team['count'] < $guize['number']) {
							M('pintuan_order')->where(array('id' => $vv['id']))->save(array('isbucha' => 1));
							M('pintuan')->where(array('id' => $vv['tid']))->setInc('quantity', $vv['num']);
						}

						$successcount++;
					}
				}
			}

			if (0 < $successcount) {
				M('pintuan_team')->where(array('id' => $team_id))->save(array('isrefund' => 1));
				$this->success($msg);
			}
			else {
				$this->error('退款失败');
			}
		}
	}

	public function refundlog()
	{
		$tid = (int) $_GET['tid'];
		$team_id = (int) $_GET['team_id'];
		$token = trim($_GET['token']);
		$where = array();
		$where['tid'] = $tid;
		$where['team_id'] = $team_id;
		$where['token'] = $token;

		if (trim($_GET['search']) != '') {
			$where['wecha_id'] = M('Userinfo')->where(array(
	'token'              => $token,
	'truename|wechaname' => array('like', '%' . trim($_GET['search']) . '%')
	))->getField('wecha_id');
		}

		$count = M('pintuan_refundlog')->where($where)->count();
		$page = new Page($count, 10);
		$pintuan_refundlog = M('pintuan_refundlog')->where($where)->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($pintuan_refundlog as $key => $value) {
			$pintuan_refundlog[$key]['nick_name'] = M('Userinfo')->where(array('token' => $token, 'wecha_id' => trim($value['wecha_id'])))->getField('wechaname');
		}

		$this->assign('list', $pintuan_refundlog);
		$this->assign('page', $page->show());
		$this->assign('token', $token);
		$this->display();
	}

	private function Dorefund($data = array(), $refund_fee = '')
	{
		if (($data['orderid'] == '') || ($data['paytype'] == '') || ($data['token'] == '')) {
			return false;
		}

		switch ($data['paytype']) {
		case 'weixin':
			$from = 'pintuan';
			$order = new OrderModel();
			$refund = $order->weixinRefund($data['token'], $data['orderid'], $from, $refund_fee * 100);
			break;

		case 'alipay':
			break;

		case 'CardPay':
			$savedata = array();
			$savedata['balance'] = array('exp', 'balance+' . $refund_fee);
			$recharge = M('userinfo')->where(array('token' => $data['token'], 'wecha_id' => $data['wecha_id']))->save($savedata);

			if ($recharge) {
				$info = M('member_card_create')->where(array('token' => $data['token'], 'wecha_id' => $data['wecha_id']))->find();
				$where = array('token' => $info['token'], 'wecha_id' => $info['wecha_id'], 'cardid' => $info['cardid']);
				$record_data = array('orderid' => date('YmdHis', time()) . mt_rand(1000, 9999), 'ordername' => '拼团返还金额', 'price' => $refund_fee, 'createtime' => time(), 'paytime' => time(), 'cardid' => $info['cardid'], 'wecha_id' => $info['wecha_id'], 'token' => $info['token'], 'module' => 'Card', 'type' => 1, 'paid' => 1);
				$add_record = M('Member_card_pay_record')->where($where)->add($record_data);
				return array('result_code' => 'SUCCESS', 'msg' => '充值成功');
			}

			break;

		default:
			return false;
			break;
		}

		return $refund;
	}
}

?>
