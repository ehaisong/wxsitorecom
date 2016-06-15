<?php

class ThirdPayPintuan
{
	public function index($orderid, $paytype, $third_id)
	{
		$where_order['orderid'] = $orderid;
		$order = M('pintuan_order')->where($where_order)->find();

		if ($order) {
			$this->wecha_id = $order['wecha_id'];
			$this->token = $order['token'];

			if ($order['paid'] != 1) {
				exit('该订单还未支付');
			}

			M('pintuan_order')->where($where_order)->save(array('state' => 1));

			if ($order['ishead'] == 0) {
				M('pintuan_team')->where(array('id' => $order['team_id']))->setInc('count');
			}

			$pintuan = M('pintuan')->where(array('token' => $this->token, 'id' => $order['tid']))->find();
			$params['site'] = array('token' => $this->token, 'from' => '拼团购购买消息', 'content' => '您的拼团购有新的拼团被购买，请注意查看。拼团名称：' . $pintuan['title']);
			$error = MessageFactory::method($params, 'SiteMessage');
		}
		else {
			exit('订单不存在：' . $orderid);
		}
	}
}


?>
