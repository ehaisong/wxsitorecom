<?php 
/**
 * 募捐
 */
class DonationAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		//$this->canUseFunction('Donation');
	}
	
	public function index()
	{
		$donations = D('Donation')->where(array('token' => $this->token))->order('id DESC')->select();
		foreach ($donations as &$d) {
			$d['is_delete'] = $d['is_delete'] ? $d['is_delete'] : ($d['starttime'] > time() ? 0 : 1);
			if ($d['starttime'] > time()) {
				$d['status'] = '<span style="color:red">未开始</span>';
			} elseif ($d['endtime'] > time()) {
				$d['status'] = '<span style="color:green">进行中</span>';
			} else {
				$d['status'] = '<span style="color:red">已结束</span>';
			}
		}
		$this->assign('donations', $donations);
		$this->display();
	}
	
	public function deldonation()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		D('Donation')->where(array('token' => $this->token, 'id' => $id))->delete();
		$this->success('删除成功');
		
	}
	
	public function creat()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$donation = D('Donation')->where(array('id' => $id, 'token' => $this->token))->find();
		if (IS_POST) {
			$data = array();
			$data['keyword'] = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '募捐';
			$data['reply_title'] = isset($_POST['reply_title']) ? htmlspecialchars($_POST['reply_title']) : '';
			$data['reply_content'] = isset($_POST['reply_content']) ? htmlspecialchars($_POST['reply_content']) : '';
			$data['reply_pic'] = isset($_POST['reply_pic']) ? $_POST['reply_pic'] : '';
			$data['share_pic'] = isset($_POST['share_pic']) ? $_POST['share_pic'] : '';
			
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$data['note'] = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';
			$data['tip'] = isset($_POST['tip']) ? htmlspecialchars($_POST['tip']) : '';
			$data['account'] = isset($_POST['account']) ? htmlspecialchars($_POST['account']) : '';
			$data['pic'] = isset($_POST['pic']) ? $_POST['pic'] : '';
			$data['logo'] = isset($_POST['logo']) ? $_POST['logo'] : '';
			$data['content'] = isset($_POST['content']) ? $_POST['content'] : '';
			$data['company'] = isset($_POST['company']) ? htmlspecialchars($_POST['company']) : '';
			$data['fixed_money1'] = isset($_POST['fixed_money1']) ? intval($_POST['fixed_money1']) : 20;
			$data['fixed_money2'] = isset($_POST['fixed_money2']) ? intval($_POST['fixed_money2']) : 50;
			$data['fixed_money3'] = isset($_POST['fixed_money3']) ? intval($_POST['fixed_money3']) : 100;
			$data['fixed_money4'] = isset($_POST['fixed_money4']) ? intval($_POST['fixed_money4']) : 200;
			$data['share_content1'] = isset($_POST['share_content1']) ? htmlspecialchars($_POST['share_content1']) : '';
			$data['share_content2'] = isset($_POST['share_content2']) ? htmlspecialchars($_POST['share_content2']) : '';
			$data['starttime'] = isset($_POST['starttime']) ? strtotime($_POST['starttime'] . ':00') : time();
			$data['endtime'] = isset($_POST['endtime']) ? strtotime($_POST['endtime'] . ':00') : (time() + 30 * 86400);
			
			if (empty($data['keyword'])) $this->error('关键词不能为空');
			if (dstrlen($data['keyword']) > 10) $this->error('关键词不超过10个字');
			if (empty($data['reply_title'])) $this->error('回复标题不能为空');
			if (dstrlen($data['reply_title']) > 48) $this->error('回复标题不超过48个字');
			if (empty($data['reply_content'])) $this->error('回复内容不能为空');
			if (empty($data['name'])) $this->error('募捐名称不能为空');
			if (dstrlen($data['name']) > 10) $this->error('募捐名称不超过10个字');
			if (empty($data['note'])) $this->error('募捐简介不能为空');
			if (empty($data['tip'])) $this->error('募捐提示语不能为空');
			if (dstrlen($data['tip']) > 15) $this->error('募捐提示不超过15个字');
			
			if (empty($data['account'])) $this->error('募捐款去向不能为空');
			if (dstrlen($data['account']) > 20) $this->error('募捐款去向不超过20个字');
			
			if (empty($data['content'])) $this->error('募捐详情不能为空');
			if (empty($data['company'])) $this->error('捐赠接收机构不能为空');
			if (empty($data['share_content1'])) $this->error('分享标语范例一不能为空');
			if (dstrlen($data['share_content1']) > 80) $this->error('分享标语范例一不超过80个字');
			if (empty($data['share_content2'])) $this->error('分享标语范例二不能为空');
			if (dstrlen($data['share_content2']) > 80) $this->error('分享标语范例二不超过80个字');
			if (empty($data['fixed_money1']) || empty($data['fixed_money2']) || empty($data['fixed_money3']) || empty($data['fixed_money4'])) $this->error('固定金额不能为0');
			if ($data['starttime'] >= $data['endtime']) $this->error('活动的结束时间应大于开始时间');
			$data['token'] = $this->token;
			$data['creattime'] = time();
			if ($donation) {
				if (D('Donation')->where(array('token' => $this->token, 'id' => $id))->save($data)) {
					$this->handleKeyword($donation['id'], 'Donation', $data['keyword']);
					$this->success('修改成功！', U('Donation/index'));
				} else {
					$this->error('修改失败，稍后重试！');
				}
			} else {
				if ($id = D('Donation')->add($data)) {
					$this->handleKeyword($id, 'Donation', $data['keyword']);
					$this->success('新增成功！', U('Donation/index'));
				} else {
					$this->error('新增失败，稍后重试！');
				}
			}
		} else {
			if (empty($donation)) $donation = array('keyword' => '募捐', 'fixed_money1' => 20, 'fixed_money2' => 50, 'fixed_money3' => 100, 'fixed_money4' => 200);
			$this->assign('donation', $donation);
			$this->display();
		}
	}
	
	
	//募捐的记录
	public function order()
	{
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$donation = $this->check_donation($did);
		$this->assign('donation', $donation);
		$donation_order_model = M('Donation_order');
		$search_where = '';
		//获取useinfo的信息
		$userinfo = M('userinfo')->where(array('token'=>$this->token))->getField('wecha_id,tel,truename,wechaname,portrait');
		if (IS_POST) {
			$searchkey = isset($_POST['searchkey']) ? htmlspecialchars($_POST['searchkey']) : '';
			if ($searchkey) {
				$search_where .= " AND (o.orderid LIKE '%{$searchkey}%' OR u.tel LIKE '%{$searchkey}%' OR u.truename LIKE '%{$searchkey}%') ";
			}
		}
		$flag = 1; //两种不同的查询取出订单记录 的开关
		if($flag){
			$sql = "SELECT count(*) as total FROM " . C('DB_PREFIX') . "donation_order AS o WHERE o.paid=1 AND o.token='{$this->token}' AND o.did='{$did}' {$search_where}";
			$count = D()->query($sql);
			$count = $count[0]['total'];
		}else{
			$where = array('token' => $this->token, 'paid' => 1, 'did' => $did);
			$count = $donation_order_model->where($where)->count();
		}
		$Page = new Page($count, 20);
		$show = $Page->show();
		
		if ($flag) {
			$sql = "SELECT o.* FROM " . C('DB_PREFIX') . "donation_order AS o WHERE o.paid=1 AND o.token='{$this->token}' AND o.did='{$did}' {$search_where} ORDER BY o.id DESC LIMIT {$Page->firstRow}, {$Page->listRows}";
			$orders = D()->query($sql);
		} else {
			$orders = $donation_order_model->where($where)->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			$wechat_ids = array();
			foreach ($orders as $ord) {
				if (!in_array($ord['wecha_id'], $wechat_ids)) $wechat_ids[] = $ord['wecha_id'];
			}
			
			if ($wechat_ids) {
				$uselist = D('Userinfo')->where(array('wecha_id' => array('in', $wechat_ids), 'token' => $this->token))->select();
				$list = array();
				foreach ($uselist as $ul) {
					$list[$ul['wecha_id']] = $ul;
				}
			}
			foreach ($orders as &$orow) {
				$tel = $userinfo[$orow['wecha_id']]['tel'] ? $userinfo[$orow['wecha_id']]['tel'] : 'null';
				$truename = $userinfo[$orow['wecha_id']]['truename'] ? $userinfo[$orow['wecha_id']]['truename'] : 'null';
				$wechaname = $userinfo[$orow['wecha_id']]['wechaname'] ? $userinfo[$orow['wecha_id']]['wechaname'] : 'null';
				$portrait = $userinfo[$orow['wecha_id']]['portrait'] ? $userinfo[$orow['wecha_id']]['portrait'] : 'null';
				$orow['portrait'] = $portrait;
				$orow['truename'] = $truename;
				$orow['wechaname'] = $wechaname;
				$orow['tel'] = $tel;
			}
		}
		foreach ($orders as &$orow) {
			$tel = $userinfo[$orow['wecha_id']]['tel'] ? $userinfo[$orow['wecha_id']]['tel'] : '';
			$truename = $userinfo[$orow['wecha_id']]['truename'] ? $userinfo[$orow['wecha_id']]['truename'] : '';
			$wechaname = $userinfo[$orow['wecha_id']]['wechaname'] ? $userinfo[$orow['wecha_id']]['wechaname'] : '';
			$portrait = $userinfo[$orow['wecha_id']]['portrait'] ? $userinfo[$orow['wecha_id']]['portrait'] : '';
			$orow['portrait'] = $portrait;
			$orow['truename'] = $truename;
			$orow['wechaname'] = $wechaname;
			$orow['tel'] = $tel;
		}
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function dynamic()
	{
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$donation = $this->check_donation($did);
		$this->assign('donation', $donation);
		
		$count = D('Donation_dynamic')->where("did={$did} AND token='{$this->token}'")->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		
		
		$sql = "SELECT d.id, i.title, i.text,d.dateline FROM " . C('DB_PREFIX') . "img AS i INNER JOIN " . C('DB_PREFIX') . "donation_dynamic AS d ON d.image_id=i.id WHERE d.did={$did} AND d.token='{$this->token}' ORDER BY d.id DESC LIMIT {$Page->firstRow}, {$Page->listRows}";
		$dynamic_list = D()->query($sql);
		$this->assign('page', $show);
		$this->assign('dynamic_list', $dynamic_list);
		$this->assign('did', $did);
		$this->display();
		
	}
	
	public function adddynamic()
	{
		$did = isset($_REQUEST['did']) ? intval($_REQUEST['did']) : 0;
		$donation = $this->check_donation($did);
		$this->assign('donation', $donation);
		if (IS_POST) {
			$image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;
			if (empty($did) || empty($image_id)) $this->error('参数错误');
			if (D('Donation_dynamic')->add(array('image_id' => $image_id, 'did' => $did, 'token' => $this->token, 'dateline' => time()))) {
				$this->success('新增成功', U('Donation/dynamic', array('did' => $did)));
			} else {
				$this->error('新增失败，稍后重试！');
			}
		} else {
			$this->assign('did', $did);
			$this->display();
		}
	}
	
	public function deldynamic()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$this->check_donation($did);
		
		D('Donation_dynamic')->where(array('token' => $this->token, 'did' => $did, 'id' => $id))->delete();
		$this->success('删除成功');
	}
	
	public function set()
	{
		$did = isset($_REQUEST['did']) ? intval($_REQUEST['did']) : 0;
		$donation = $this->check_donation($did);
		$this->assign('donation', $donation);
		$set = D('Donation_set')->where(array('token' => $this->token, 'did' => $did))->find();
		if (IS_POST) {
			$data['circle_name'] = isset($_POST['circle_name']) ? htmlspecialchars($_POST['circle_name']) : '';
			$data['tip'] = isset($_POST['tip']) ? htmlspecialchars($_POST['tip']) : '';
			$data['money'] = isset($_POST['money']) && $_POST['money'] ? floatval($_POST['money']) : 1;
// 			$data['one'] = isset($_POST['one']) && $_POST['one'] ? floatval($_POST['one']) : 0;
// 			$data['two'] = isset($_POST['two']) && $_POST['two'] ? floatval($_POST['two']) : 0;
// 			$data['three'] = isset($_POST['three']) && $_POST['three'] ? floatval($_POST['three']) : 0;
// 			$data['four'] = isset($_POST['four']) && $_POST['four'] ? floatval($_POST['four']) : 0;
// 			$data['five'] = isset($_POST['five']) && $_POST['five'] ? floatval($_POST['five']) : 0;
			$data['agreement'] = isset($_POST['agreement']) ? $_POST['agreement'] : '';
			if (empty($data['circle_name'])) $this->error('圈子名称不能为空！');
			if (dstrlen($data['circle_name']) > 10) $this->error('圈子名称不能超过十个字！');
// 			if ($data['two'] <= $data['one']) {
// 				$this->error('两个奖牌的获得要比一个奖牌的获得所用金额要多！');
// 			} elseif ($data['three'] <= $data['two']) {
// 				$this->error('三个奖牌的获得要比两个奖牌的获得所用金额要多！');
// 			} elseif ($data['four'] <= $data['three']) {
// 				$this->error('四个奖牌的获得要比三个奖牌的获得所用金额要多！');
// 			} elseif ($data['five'] <= $data['four']) {
// 				$this->error('五个奖牌的获得要比四个奖牌的获得所用金额要多！');
// 			}
			if (empty($data['tip'])) {
				$this->error('感谢语不能为空');
			} elseif (dstrlen($data['tip']) > 10) {
				$this->error('感谢语不超过十个字');
			}
			$data['token'] = $this->token;
			$data['did'] = $did;
			$data['dateline'] = time();
			if ($set) {
				if (D('Donation_set')->where(array('token' => $this->token, 'did' => $did))->save($data)) {
					$this->success('配置修改成功');
				} else {
					$this->error('配置修改失败');
				}
			} else {
				if (D('Donation_set')->add($data)) {
					$this->success('配置新增成功');
				} else {
					$this->error('配置新增失败');
				}
			}
		} else {
			$this->assign('did', $did);
			$this->assign('set', $set);
			$this->display();	
		}	
	}
	
	private function check_donation($id)
	{
		if ($donation = D('Donation')->where(array('token' => $this->token, 'id' => $id))->find()) {
			return $donation;
		} else {
			$this->error('不存在的活动');
		}
	}
	
	public function export()
	{
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$donation = $this->check_donation($did);
		
// 		header("Content-Type: text/html; charset=utf-8");
// 		header("Content-type:application/vnd.ms-execl");
// 		header("Content-Disposition:filename=donation_{$did}.xls");
// 		//   以下\t代表横向跨越一格，\n 代表跳到下一行，可以根据自己的要求，增加相应的输出相，要和循环中的对应哈
// 		//字段
		
// 		$arr = array(
// 				array('en' => 'orderid', 'cn' => '捐赠流水号'),
// 				array('en' => 'truename', 'cn' => '姓名'),
// 				array('en' => 'tel', 'cn' => '电话'),
// 				array('en' => 'wechaname', 'cn' => '微信昵称'),
// 				array('en' => 'price', 'cn' => '捐赠金额'),
// 				array('en' => 'dateline', 'cn' => '捐赠时间'));
		
// 		$fieldCount = count($arr);
// 		$s = 0;
// 		foreach ($arr as $f){
// 			if ($s < $fieldCount - 1) {
// 				echo iconv('utf-8','gbk',$f['cn'])."\t";
// 			} else {
// 				echo iconv('utf-8','gbk',$f['cn'])."\n";
// 			}
// 			$s++;
// 		}
		
// 		$sql = "SELECT o.orderid, o.price, o.dateline, u.wechaname, u.truename, u.tel FROM " . C('DB_PREFIX') . "donation_order AS o INNER JOIN " . C('DB_PREFIX') . "userinfo AS u ON o.token=u.token AND o.wecha_id=u.wecha_id WHERE o.paid=1 AND o.token='{$this->token}' AND o.did='{$did}' ORDER BY o.id DESC";
// 		$orders = D()->query($sql);
// 		foreach ($orders as $order) {
// 			$j = 0;
// 			foreach ($arr as $field) {
// 				$fieldValue = $order[$field['en']];
// 				if ($field['en'] == 'dateline') {
// 					$fieldValue = $fieldValue ? date('Y-m-d H:i:s', $fieldValue) : '';
// 				} else {
// 					$fieldValue = iconv('utf-8', 'gbk', $fieldValue);
// 				}
// 				if ($j < $fieldCount-1) {
// 					echo $fieldValue . "\t";
// 				} else {
// 					echo $fieldValue . "\n";
// 				}
// 				$j++;
// 			}
// 		}
// 		exit();
		
		
		import('@.ORG.phpexcel.PHPExcel');
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($donation['name']);
		$objProps->setTitle($donation['name']);
		$objProps->setSubject($donation['name']);
		$objProps->setDescription($donation['name']);
		// 设置当前的sheet
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();
		
		// 开始填充头部
		$objActSheet->setCellValue('A1', '捐赠流水号');
		$objActSheet->setCellValue('B1', '姓名');
		$objActSheet->setCellValue('C1', '电话');
		$objActSheet->setCellValue('D1', '微信昵称');
		$objActSheet->setCellValue('E1', '捐赠金额');
		$objActSheet->setCellValue('F1', '捐赠时间');
		//获取useinfo的tel信息
		$userinfo = M('userinfo')->where(array('token'=>$this->token))->getField('wecha_id,tel,truename,wechaname');
		
		//$sql = "SELECT o.orderid, o.price, o.dateline, u.wechaname, u.truename, u.tel FROM " . C('DB_PREFIX') . "donation_order AS o INNER JOIN " . C('DB_PREFIX') . "userinfo AS u ON o.token=u.token AND o.wecha_id=u.wecha_id WHERE o.paid=1 AND o.token='{$this->token}' AND o.did='{$did}' ORDER BY o.id DESC";
		$sql = "SELECT * FROM " . C('DB_PREFIX') . "donation_order AS o WHERE o.paid=1 AND o.token='{$this->token}' AND o.did='{$did}' ORDER BY o.id DESC";
		$orders = D()->query($sql);
		// 中间部分
		if ($orders) {
			$i = 2;
			foreach ($orders as $line) {
				$tel = $userinfo[$line['wecha_id']]['tel'] ? $userinfo[$line['wecha_id']]['tel'] : '';
				$truename = $userinfo[$line['wecha_id']]['truename'] ? $userinfo[$line['wecha_id']]['truename'] : '';
				$wechaname = $userinfo[$line['wecha_id']]['wechaname'] ? $userinfo[$line['wecha_id']]['wechaname'] : '';
				$objActSheet->setCellValue('A'.$i, $line['orderid']);
				$objActSheet->setCellValue('B'.$i, $truename);
				$objActSheet->setCellValue('C'.$i, $tel);
				$objActSheet->setCellValue('D'.$i, $wechaname);
				$objActSheet->setCellValue('E'.$i, $line['price']);
				$objActSheet->setCellValue('F'.$i, date('Y-m-d H:i:s', $line['dateline']));
				$i++;
			}
		}
		
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="donation_' . $did . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();	
	}
	
	
	public function gift()
	{
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$this->check_donation($did);
		$this->assign('did', $did);
		$gifts = D('Donation_gift')->where(array('token' => $this->token, 'did' => $did))->select();
		$this->assign('gifts', $gifts);
		$this->display();
	}
	
	public function addgift()
	{
		$did = isset($_REQUEST['did']) ? intval($_REQUEST['did']) : 0;
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$this->check_donation($did);
		if ($gift = D('Donation_gift')->where(array('id' => $id, 'token' => $this->token, 'did' => $did))->find()) {
			$this->assign('gift', $gift);
		}
		
		if (IS_POST) {
			$money = isset($_POST['money']) ? htmlspecialchars($_POST['money']) : 0;
			$pic = isset($_POST['pic']) ? $_POST['pic'] : '';
			if ($money < 0) {
				$this->error('金额必须大于等于0');
			}
			if (empty($pic)) {
				$this->error('图片不能为空');
			}
			$data = array('pic' => $pic, 'money' => $money, 'token' => $this->token, 'dateline' => time(), 'did' => $did);
			if ($tgift = D('Donation_gift')->where("money='{$money}' AND id<>{$id} AND token='{$this->token}' AND did={$did}")->find()) {
				$this->error("大于{$money}元的礼物已经存在了");
			}
			if ($gift) {
				if (D('Donation_gift')->where(array('token' => $this->token, 'id' => $id, 'did' => $did))->save($data)) {
					$this->success('修改成功', U('Donation/gift', array('did' => $did)));
				} else {
					$this->error('修改失败，稍后重试');
				}
			} else {
				if (D('Donation_gift')->add($data)) {
					$this->success('新增成功', U('Donation/gift', array('did' => $did)));
				} else {
					$this->error('新增失败，稍后重试');
				}
			}
		} else {
			$this->assign('did', $did);
			$this->display();
		}
	}
	
	public function delgift()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$this->check_donation($did);
		
		D('Donation_gift')->where(array('token' => $this->token, 'did' => $did, 'id' => $id))->delete();
		$this->success('删除成功');
	}
	
	public function medal()
	{
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$this->check_donation($did);
		$this->assign('did', $did);
		$medals = D('Donation_medal')->where(array('token' => $this->token, 'did' => $did))->select();
		$this->assign('medals', $medals);
		$this->display();
	}
	
	public function addmedal()
	{
		$did = isset($_REQUEST['did']) ? intval($_REQUEST['did']) : 0;
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		$this->check_donation($did);
		if ($medal = D('Donation_medal')->where(array('id' => $id, 'token' => $this->token, 'did' => $did))->find()) {
			$this->assign('medal', $medal);
		}
		
		if (IS_POST) {
			$num = isset($_POST['num']) ? intval($_POST['num']) : 0;
			$money = isset($_POST['money']) ? htmlspecialchars($_POST['money']) : 0;
			$note = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';
			$pic = isset($_POST['pic']) ? $_POST['pic'] : '';
			if ($num < 0) {
				$this->error('奖牌显示个数大于0的整数');
			}
			if ($money < 0) {
				$this->error('金额必须大于等于0');
			}
			if (empty($pic)) {
				$this->error('图片不能为空');
			}
			if (empty($note)) {
				$this->error('奖牌说明不能为空');
			}
			if (dstrlen($note) > 10) {
				$this->error('奖牌说明不能超过十个字');
			}
			$data = array('pic' => $pic, 'money' => $money, 'token' => $this->token, 'dateline' => time(), 'did' => $did, 'num' => $num, 'note' => $note);
			if ($tmedal = D('Donation_medal')->where("money='{$money}' AND id<>{$id} AND token='{$this->token}' AND did={$did}")->find()) {
				$this->error("大于{$money}元的礼物已经存在了");
			}
			if ($medal) {
				if (D('Donation_medal')->where(array('token' => $this->token, 'id' => $id, 'did' => $did))->save($data)) {
					$this->success('修改成功', U('Donation/medal', array('did' => $did)));
				} else {
					$this->error('修改失败，稍后重试');
				}
			} else {
				if (D('Donation_medal')->add($data)) {
					$this->success('新增成功', U('Donation/medal', array('did' => $did)));
				} else {
					$this->error('新增失败，稍后重试');
				}
			}
		} else {
			$this->assign('did', $did);
			$this->display();
		}
	}
	
	public function delmedal()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$this->check_donation($did);
		D('Donation_medal')->where(array('token' => $this->token, 'did' => $did, 'id' => $id))->delete();
		$this->success('删除成功');
	}
}
?>