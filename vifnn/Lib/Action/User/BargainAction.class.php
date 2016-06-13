<?php
class BargainAction extends UserAction{

	public $store_id = 0;
	public $is_syn;
	public $SALT;

	public function _initialize(){
		parent::_initialize();
		
		$this->canUseFunction("Bargain");
		
		//加载数据库
		$this->m_bargain = M("bargain");
		$this->m_order = M("bargain_order");
		$this->m_kanuser = M("bargain_kanuser");
		$this->m_userinfo = M("userinfo");

		if(updateSync::getIfWeidian()){
			$Micrstore_URL = C('weidian_domain') ? C('weidian_domain') : 'http://vd.vifnn.com';
		}else{
			$Micrstore_URL = 'http://vd.vifnn.com';
		}

		$this->Micrstore_URL = $_SESSION['source_domain']?$_SESSION['source_domain']:$Micrstore_URL;
		$this->SALT = C('encryption') ? C('encryption') : 'vifnn.com';

		$this->store_id = $this->wxuser['routerid'];
		
		$this->is_syn = session('is_syn');

	}
	//首页
	public function index(){
		
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;
		if(!empty($_GET['name'])){
			$where['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_bargain->where($where)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$bargain_list = $this->m_bargain->where($where)->order("addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		foreach($bargain_list as $k=>$v){
			$where_kanuser['token'] = $this->token;
			$where_kanuser['bargain_id'] = $v['vifnn_id'];
			$count_canyu = $this->m_kanuser->where($where_kanuser)->distinct(true)->field('friend')->select();
			$count_canyu=count($count_canyu);
			$where_order['token'] = $this->token;
			$where_order['paid'] = 1;
			$where_order['bargain_id'] = $v['vifnn_id'];
			$count_pay = $this->m_order->where($where_order)->count();
			$bargain_list[$k]['count_canyu'] = $count_canyu;
			$bargain_list[$k]['count_pay'] = $count_pay;
			if($v['is_new'] == 2){
				$bargain_list[$k]['original'] = $bargain_list[$k]['original']/100;
				$bargain_list[$k]['minimum'] = $bargain_list[$k]['minimum']/100;
			}
			if($this->is_syn == 1){
				$post_data = null;
				$sort_data = null;
				$sign_key = null;
				$url = $this->Micrstore_URL . "/api/activity/act_getquantity.php";
				$post_data['product_id'] = $v['product_id'];
				$post_data['sku_id'] = $v['sku_id'];
				$sort_data = $post_data;
				$sort_data['salt'] = $this->SALT;
				ksort($sort_data);
				$sign_key = sha1(http_build_query($sort_data));
				$post_data['sign_key'] = $sign_key;
				$curlResult = $this->curl_post($url,$post_data);
				$return = json_decode($curlResult,true);
				$bargain_list[$k]['inventory'] = $return['num'];
			}
		}
		$this->assign('page',$show);
		$this->assign("bargain_list",$bargain_list);
		$this->display();
	}
	//添加砍价
	public function add(){
		if ($_SESSION['is_syn'] == 1) {
			// 电商是否绑定微信
			$isBind = $this->isBindWeixin($this->token);
			$this->assign('wdIsBind', $isBind);
		}
		$this->display();
	}
	//执行添加
	public function doadd(){
		if(IS_POST){
			$_POST['token'] = $this->token;
			$_POST['addtime'] = time();
			if($_POST['is_new'] == 2){
				$_POST['original'] = floor(($_POST['original']*100).'');
				$_POST['minimum'] = floor(($_POST['minimum']*100).'');
				$_POST['kan_min'] = floor(($_POST['kan_min']*100).'');
				$_POST['kan_max'] = floor(($_POST['kan_max']*100).'');
			}
			if($this->m_bargain->create()!=false){
				if($id=$this->m_bargain->add()){
					$this->handleKeyword($id,'Bargain',$_POST['keyword'],0,0);
					if ($this->is_syn == 1) {	//电商对接 
						$this->apiWdActivity($id);		
					}
					$this->success("活动创建成功",U('Bargain/index',array('token'=>$this->token)));
				}else{
					$this->error('服务器繁忙,请稍候再试');
				}
			}else{
				$this->error($this->m_bargain->getError());
			}
		}else{
			$this->error("操作失败");
		}
	}
	//修改砍价
	public function update(){

		if ($_SESSION['is_syn'] == 1) {
			// 电商是否绑定微信
			$isBind = $this->isBindWeixin($this->token);
			$this->assign('wdIsBind', $isBind);
		}
		
		$where['token'] = $this->token;
		$where['vifnn_id'] = (int)($_GET['id']);
		$bargain = $this->m_bargain->where($where)->find();
		if($bargain['is_new'] == 2){
			$bargain['original'] = $bargain['original']/100;
			$bargain['minimum'] = $bargain['minimum']/100;
			$bargain['kan_min'] = $bargain['kan_min']/100;
			$bargain['kan_max'] = $bargain['kan_max']/100;
		}
		$this->assign("bargain",$bargain);
		$where_order['token'] = $this->token;
		$where_order['bargain_id'] = (int)($_GET['id']);
		$count_order = $this->m_order->where($where_order)->count();
		$this->assign('count_order',$count_order);
		$this->display();
	}
	//执行修改
	public function doupdate(){
		if(IS_POST){
			$where['vifnn_id'] = $_POST['vifnn_id'];
			$where['token'] = $this->token;
			if($_POST['is_new'] == 2){
				$_POST['original'] = floor(($_POST['original']*100).'');
				$_POST['minimum'] = floor(($_POST['minimum']*100).'');
				$_POST['kan_min'] = floor(($_POST['kan_min']*100).'');
				$_POST['kan_max'] = floor(($_POST['kan_max']*100).'');
			}
			if($this->m_bargain->create()!=false){
				$update=$this->m_bargain->where($where)->save();
				$id = $_POST['vifnn_id'];
				$this->handleKeyword($id,'Bargain',$_POST['keyword'],0,0);
				if ($this->is_syn == 1) {	//电商对接 
					$this->apiWdActivity($id);		
				}
				S($_POST['vifnn_id'].'bargain'.$this->token,null);
				$this->success("活动修改成功",U('Bargain/index',array('token'=>$this->token)));
			}else{
				$this->error($this->m_bargain->getError());
			}
		}else{
			$this->error("操作失败");
		}
	}
	//订单管理
	public function order(){
		$_GET=array_merge(array(
			'paid'=>'1'
		),$_GET);
		$where_order['token'] = $this->token;
		if($_GET['paid']!='')
		{
			$where_order['paid']=I('get.paid');
		}
		if($_GET['id']!='')
		{
			$where_order['bargain_id'] = (int)($_GET['id']);
			$where_page['id'] = $_GET['id'];
		}
		if($_GET['orderid']!='')
		{
			if(!(strpos($_GET['orderid'],'bargain') === FALSE)){
				$where_order['orderid'] = $_GET['orderid'];
			}else{
				$where_order['vifnn_id'] = $_GET['orderid'] - 10000000;
			}
			$where_page['orderid'] = $_GET['orderid'];
		}
		import('ORG.Util.Page');
		$count = $this->m_order->where($where_order)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$order_list = $this->m_order->where($where_order)->order("addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		foreach($order_list as $k=>$v){
			$where_userinfo['token'] = $this->token;
			$where_userinfo['wecha_id'] = $v['wecha_id'];
			$userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$order_list[$k]['wechaname'] = $userinfo['wechaname']?$userinfo['wechaname']:$v['username'];
			$order_list[$k]['tel'] = $userinfo['tel']?$userinfo['tel']:$v['phone'];
			$order_list[$k]['address'] = $userinfo['address']?$userinfo['address']:$v['address'];
		}
		$this->assign('page',$show);
		$this->assign("order_list",$order_list);
		$this->display();
	}
	//参与砍价的人
	public function kanuser(){
		$where_kanuser['token'] = $this->token;
		$where_page['token'] = $this->token;
		$where_kanuser['orderid'] = (int)($_GET['orderid']);
		$where_page['orderid'] = $_GET['orderid'];
		import('ORG.Util.Page');
		$count = $this->m_kanuser->where($where_kanuser)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$kanuser_list = $this->m_kanuser->where($where_kanuser)->order("addtime")->limit($page->firstRow.','.$page->listRows)->select();
		foreach($kanuser_list as $k=>$v){
				$where_userinfo2['token'] = $this->token;
				$where_userinfo2['wecha_id'] = $v['friend'];
				$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
				$kanuser_list[$k]['wechaname'] = $userinfo2['wechaname'];
			}
		$this->assign('page',$show);
		$this->assign("kanuser_list",$kanuser_list);
		$where_order['token'] = $this->token;
		$where_order['vifnn_id'] = (int)($_GET['orderid']);
		$order = $this->m_order->where($where_order)->find();
		$where['token'] = $this->token;
		$where['vifnn_id'] = $order['bargain_id'];
		$bargain = $this->m_bargain->where($where)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $order['wecha_id'];
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('bargain',$bargain);
		$this->assign('userinfo',$userinfo);
		$this->display();
	}
	//ajax
	public function ajax(){
		switch($_POST['type']){
			case 'state':
				$where['token'] = $_POST['token'];
				$where['vifnn_id'] = (int)($_POST['id']);
				$state = $this->m_bargain->where($where)->getField("state");
				if($state == 1){
					$save['state'] = 0;
					$data['error'] = 0;
				}else{
					$save['state'] = 1;
					$data['error'] = 1;
				}
				$update = $this->m_bargain->where($where)->save($save);
				S($_POST['id'].'bargain'.$this->token,null);
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	//操作执行
	public function operate(){
		switch($_GET['type']){
			case 'del':
				$where['token'] = $this->token;
				$where['vifnn_id'] = (int)($_GET['id']);
				$del = $this->m_bargain->where($where)->delete();
				$this->m_order->where(array('bargain_id'=>(int)($_GET['id'])))->delete();
				$this->m_kanuser->where(array('bargain_id'=>(int)($_GET['id'])))->delete();
				S((int)($_GET['id']).'bargain'.$this->token,null);
				$this->success("删除成功",U("Bargain/index",array("token"=>$this->token)));
			break;
			case 'fahuo':
				$where_order['token'] = $this->token;
				$where_order['vifnn_id'] = (int)($_GET['orderid']);
				$save_order['state'] = 0;
				$save_order['expressnum'] = 0;
				$save_order['expressname'] = 0;
				$update_order = $this->m_order->where($where_order)->save($save_order);
				$this->redirect("Bargain/order",array("token"=>$this->token,'id'=>$_GET['id']));
			break;
		}
	}
	//发货
	public function fahuo(){
		$where_order['token'] = $_POST['token'];
		$where_order['vifnn_id'] = (int)($_POST['orderid']);
		$save_order['state'] = 1;
		$save_order['expressnum'] = $_POST['fahuoid'];
		$save_order['expressname'] = $_POST['fahuoname'];
		$update_order = $this->m_order->where($where_order)->save($save_order);
		$order = $this->m_order->where($where_order)->find();
		$orderid = $order['vifnn_id'] + 10000000;
		$model = new templateNews();
		$model->sendTempMsg('OPENTM200565259', array('href' => $this->siteUrl.U('Wap/Bargain/myorder',array('token' => $this->token)), 'wecha_id' => $order['wecha_id'], 'first' => '您的订单'.$orderid.'已发货', 'keyword1' => $orderid, 'keyword2' => $order['expressname'], 'keyword3' => $order['expressnum'], 'remark' => date("Y年m月d日H时i分s秒")));
		$data['error'] = 0;
		$this->ajaxReturn($data,'JSON');
	}

	// 对接跳转微店活动订单
	public function wdActOrder () {
		$wxuser = M('Wxuser')->where(array('token'=>$this->token))->find();
		$user = M('Users')->where(array('id'=>$wxuser['uid']))->find();
		if ($user['is_syn'] == 1) {
			redirect($user['source_domain'].'/wap/act_redirect.php');
			exit;
		} else {
			$this->error("非法访问");
		}
	}

	// 店铺商品弹窗接口
	public function goods () {

		$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

		$post_data = array(
			'p' => $p,
			'token' => $this->token,
			'keyword' => $keyword,
		);
		$this->assign('keyword', $keyword);

		/* sign 串 */
		$sort_data = $post_data;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		/* sign 串 */

		$post_data['sign_key'] = $sign_key;
		$url = $this->Micrstore_URL . "/api/activity/act_goods.php";//微店接收数据的地址
		// echo $url;exit;
		$curlResult = $this->curl_post($url,$post_data);
		// var_dump($curlResult);exit;
		$return = json_decode($curlResult,true);//微店返回数据
		$data = $return['data'];

		$page = new Page($data['total'], 5);

		$this->assign('data', $return['data']);
		$this->assign('page', $page);
		$this->display();
	}

	// 获取电商推广用二维码接口
	public function qrcode()
	{

		$modelId = isset($_GET['modelId']) ? intval($_GET['modelId']) : 0;

		$bargainRow = M('Bargain')->where(array('vifnn_id'=>$modelId))->find();
		// dump($bargainRow);exit;
		$post_data = array(
			'title' => $bargainRow['name'],
			'info' => $bargainRow['wxinfo'],
			'image' => $bargainRow['wxpic'],
			'token' => $bargainRow['token'],
			'keyword' => $bargainRow['keyword'],
			'model' => 'bargain',
			'modelId' => $modelId,
		);

		/* sign 串 */
		$sort_data = $post_data;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		/* sign 串 */
		// dump($sort_data);
		$post_data['sign_key'] = $sign_key;
		$url = $this->Micrstore_URL . "/api/activity/act_shop_qrcode.php";//微店接收数据的地址

		$curlResult = $this->curl_post($url,$post_data);
		// var_dump($curlResult);exit;
		$return = json_decode($curlResult,true);//微店返回数据
		// var_dump($return);exit;
		
		$data = $return['data'];

		$this->assign('data', $return);
		$this->display();
	}

	// 活动添加/修改 更新微店对接
	public function apiWdActivity($modelId)
	{
		$bargainRow = M('Bargain')->where(array('vifnn_id'=>$modelId))->find();

		$post_data = array(
			'title' => $bargainRow['name'],
			'info' => $bargainRow['wxinfo'],
			'image' => $bargainRow['wxpic'],
			'token' => $bargainRow['token'],
			'keyword' => $bargainRow['keyword'],
			'model' => 'bargain',
			'modelId' => $modelId,
		);

		/* sign 串 */
		$sort_data = $post_data;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		/* sign 串 */
		// dump($sort_data);
		$post_data['sign_key'] = $sign_key;
		$url = $this->Micrstore_URL . "/api/activity/act_qrcode.php";//微店接收数据的地址
		// $url = $this->Micrstore_URL . "/api/activity/act_qrcode.php";//微店接收数据的地址
		$curlResult = $this->curl_post($url,$post_data);
		// var_dump($curlResult);exit;
		$return = json_decode($curlResult,true);//微店返回数据
		// var_dump($return);exit;
		
		$data = $return['data'];
		if ($return['error_code'] != 0) {
			return false;
		}

		return true;

	}

	// 判断电商店铺是否绑定微信公众号
	public function isBindWeixin($token) {

		$post_data = array(
			'token' => $token,
		);

		$sort_data = $post_data;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		/* sign 串 */

		$post_data['sign_key'] = $sign_key;
		$url = $this->Micrstore_URL . "/api/activity/act_wxbind.php";//微店接收数据的地址
		$curlResult = $this->curl_post($url, $post_data);
		// var_dump($curlResult);exit;
		$return = json_decode($curlResult,true);//微店返回数据
		$isBind = $return['is_bind'];

		return $isBind;
	}

	// CURL POST 传输
	private function curl_post($url,$post)
	{
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