<?php
class ShakearoundAction extends UserAction
{
	public $token;
	public $expiration_time;
	public $update_expiration;
	public $shakearound;
	public $now;

	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction('shakearound');
		$this->token = session('token') ? session('token') : session('wp_token');
		$this->expiration_time = 12 * 3600;
		$this->update_expiration = 10 * 1800;
		$this->now = time();
		$this->shakearound = new Shakearound();
	}

	public function index()
	{
		$page_info = array();
		$device = M('shakearoung_device');
		$token = $this->_get('token', 'trim');
		$where = array('token' => $token);
		$total = $device->where($where)->count();
		$Page = new Page($total, 20);
		$device_list = $device->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$page_list = M('shakearoung_page')->where($where)->select();

		foreach ((array) $page_list as $val) {
			$page_info[$val['page_id']] = $val['title'];
		}

		foreach ($device_list as $k => $v) {
			$bind_pages = explode(';', $v['page_ids']);

			foreach ($bind_pages as $id) {
				if (!empty($id)) {
					$device_list[$k]['page_name'] .= $page_info[$id] . ';';
				}
			}
		}

		$this->assign('device_list', $device_list);
		$this->assign('token', $token);
		$this->assign('pagfirstRowe', $Page->firstRow);
		$this->assign('listRows', $Page->listRows);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function apply_device()
	{
		if (IS_POST) {
			$quantity = 1;
			$apply_reason = $this->_post('apply_reason', 'trim');
			$apply_comment = $this->_post('apply_comment', 'trim');

			if (empty($apply_comment)) {
				$this->error('设备名称不能为空');
				exit();
			}

			if (empty($apply_reason)) {
				$this->error('添加理由不能为空');
				exit();
			}

			$respon = $this->shakearound->apply_device($quantity, $apply_reason, $apply_comment);
			$result = json_decode($respon, true);
			if (($result['errcode'] == 0) && ($result['errmsg'] == 'success.')) {
				$device = array();
				$device_identifiers = $result['data']['device_identifiers'][0];
				$device['device_id'] = $device_identifiers['device_id'];
				$device['uuid'] = $device_identifiers['uuid'];
				$device['major'] = $device_identifiers['major'];
				$device['minor'] = $device_identifiers['minor'];
				$device['apply_id'] = $result['data']['apply_id'];
				$device['device_comment'] = $apply_comment;
				$device['page_num'] = 0;
				$device['status'] = $device_identifiers['device_id'] == 0 ? 3 : 0;
				$device['add_reason'] = $apply_reason;
				$device['add_time'] = $this->now;
				$device['token'] = $this->token;
				$inset_device = array_map('nulltoblank', $device);
				$add_device = M('shakearoung_device')->add($inset_device);

				if ($add_device) {
					if ($device_identifiers['device_id'] == 0) {
						$this->success('申请成功等待审核,您可以点击【手动更新设备状态】更新状态', U('Shakearound/index', array('token' => $this->token)));
						exit();
					}
					else {
						$this->success('添加成功', U('Shakearound/index', array('token' => $this->token)));
						exit();
					}
				}
				else {
					$this->error('设备入库失败');
					exit();
				}
			}
			else {
				$this->error('添加设备失败：' . $result['errmsg']);
				exit();
			}
		}

		$this->display();
	}

	public function edit_device()
	{
		if (IS_POST) {
			$device_comment = $this->_post('device_comment', 'trim');

			if (empty($device_comment)) {
				$this->error('设备名称不能为空');
				exit();
			}

			$token = $this->_post('token', 'trim');
			$id = $this->_post('id', 'intval');
			$device_info = M('shakearoung_device')->where(array('id' => $id, 'token' => $token))->find();

			if (empty($device_info)) {
				$this->error('未获取到设备');
				exit();
			}

			$device_id = $device_info['device_id'];
			$uuid = $device_info['uuid'];
			$major = $device_info['major'];
			$minor = $device_info['minor'];
			$status = false;
			$msg = '';

			if ($device_info['device_comment'] == $device_comment) {
				$this->error('编辑失败');
				exit();
			}
			else {
				$up_comment = $this->shakearound->update_device($device_id, $uuid, $major, $minor, $device_comment);
				$res_comment = json_decode($up_comment, true);
				if (($res_comment['errcode'] == 0) && ($res_comment['errmsg'] == 'success.')) {
					$status = true;
				}
				else {
					$status = false;
					$msg .= '编辑失败：' . $res_comment['errmsg'];
				}
			}

			if ($status) {
				$edit = M('shakearoung_device')->where(array('token' => $token, 'device_id' => $device_id))->save(array('device_comment' => $device_comment));

				if ($edit) {
					$this->success('编辑名称成功', U('Shakearound/index', array('token' => $token)));
				}
				else {
					$this->error('编辑名称失败');
					exit();
				}
			}
			else {
				$this->error($msg);
				exit();
			}
		}
		else {
			if (!empty($_GET['token']) && !empty($_GET['id'])) {
				$device_info = M('shakearoung_device')->where(array('id' => $_GET['id'], 'token' => $_GET['token']))->field('device_comment,device_id')->find();

				if (!empty($device_info)) {
					$this->assign('device_info', $device_info);
					$this->assign('token', $_GET['token']);
					$this->assign('id', $_GET['id']);
				}
				else {
					$this->error('未获取到要编辑的设备');
					exit();
				}
			}

			$this->display();
		}
	}

	public function update_status()
	{
		$apply_id = (int) $_GET['apply_id'];
		$token = trim($_GET['token']);

		if ($apply_id == 0) {
			header('Location: /index.php?g=User&m=Shakearound&a=index&token=' . $this->token);
			exit();
		}

		$db_device = M('shakearoung_device');
		$database_device = $db_device->where(array('token' => $token))->select();

		if (count($database_device) < 1) {
			header('Location: /index.php?g=User&m=Shakearound&a=index&token=' . $this->token);
			exit();
		}

		foreach ($database_device as $k => $v) {
			$device_ids[] = $v['device_id'];
		}

		$device_list = $this->shakearound->search_device('', '', '', '', $apply_id, 0, 50);
		$device = json_decode($device_list, true);
		if (($device['errcode'] == 0) && ($device['errmsg'] == 'success.')) {
			$data = array();
			$count = $db_device->where(array(
	'token'     => $token,
	'device_id' => array('eq', 0)
	))->count();

			if (0 < $count) {
				$db_device->where(array(
	'token'     => $token,
	'device_id' => array('eq', 0)
	))->delete();
			}

			foreach ($device['data']['devices'] as $key => $val) {
				$data['status'] = $val['status'];
				$data['major'] = $val['major'];
				$data['minor'] = $val['minor'];
				$data['uuid'] = $val['uuid'];
				if (in_array($val['device_id'], $device_ids) && ($val['device_id'] != 0)) {
					$db_device->where(array('device_id' => $val['device_id']))->save($data);
				}
				else {
					$data['device_id'] = $val['device_id'];
					$data['apply_id'] = $apply_id;
					$data['device_comment'] = $val['comment'];
					$data['page_num'] = 0;
					$data['page_ids'] = 0;
					$data['add_reason'] = 0;
					$data['add_time'] = time();
					$data['token'] = $token;
					$insert_data = array_map('nulltoblank', $data);
					$db_device->add($insert_data);
				}
			}
		}

		header('Location: /index.php?g=User&m=Shakearound&a=index&token=' . $this->token);
	}

	public function manual_update()
	{
		$token = $this->_get('token', 'trim');
		$pagfirstRowe = ($this->_get('pagfirstRowe', 'intval') ? $this->_get('pagfirstRowe', 'intval') : 0);
		$listRows = ($this->_get('listRows', 'intval') ? $this->_get('listRows', 'intval') : 20);

		if (empty($token)) {
			$this->error('Token错误');
			exit();
		}

		$db_device = M('shakearoung_device');
		$database_device = $db_device->where(array('token' => $token))->select();

		if (count($database_device) < 1) {
			header('Location: /index.php?g=User&m=Shakearound&a=index&token=' . $this->token);
			exit();
		}

		foreach ($database_device as $k => $v) {
			$device_ids[] = $v['device_id'];
		}

		$device_list = $this->shakearound->search_device('', '', '', '', '', $pagfirstRowe, $listRows);
		$device = json_decode($device_list, true);
		if (($device['errcode'] == 0) && ($device['errmsg'] == 'success.')) {
			$data = array();
			$count = $db_device->where(array(
	'token'     => $token,
	'device_id' => array('eq', 0)
	))->count();

			if (0 < $count) {
				$db_device->where(array(
	'token'     => $token,
	'device_id' => array('eq', 0)
	))->delete();
			}

			foreach ($device['data']['devices'] as $key => $val) {
				$data['status'] = $val['status'];
				$data['major'] = $val['major'];
				$data['minor'] = $val['minor'];
				$data['uuid'] = $val['uuid'];
				if (in_array($val['device_id'], $device_ids) && ($val['device_id'] != 0)) {
					$db_device->where(array('device_id' => $val['device_id']))->save($data);
				}
				else {
					$data['device_id'] = $val['device_id'];
					$data['apply_id'] = 0;
					$data['device_comment'] = $val['comment'];
					$data['page_num'] = 0;
					$data['page_ids'] = 0;
					$data['add_reason'] = 0;
					$data['add_time'] = time();
					$data['token'] = $token;
					$insert_data = array_map('nulltoblank', $data);
					$db_device->add($insert_data);
				}
			}
		}

		header('Location: /index.php?g=User&m=Shakearound&a=index&token=' . $this->token);
	}

	public function page_index()
	{
		$shakearoung_page = M('shakearoung_page');
		$where = array('token' => $this->token);
		$total = $shakearoung_page->where($where)->count();
		$Page = new Page($total, 10);
		$page_list = $shakearoung_page->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('page_list', $page_list);
		$this->assign('token', $this->token);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function add_page()
	{
		if (IS_POST) {
			$title = $this->_post('title', 'trim');
			$description = $this->_post('description', 'trim');
			$page_url = $this->_post('page_url', 'trim');
			$icon_url = $this->_post('icon_url', 'trim');
			$page_comment = ($this->_post('page_comment', 'trim') ? $this->_post('page_comment', 'trim') : '');
			if (empty($title) || empty($description) || empty($page_url) || empty($icon_url)) {
				$this->error('标题、副标题、跳转地址、图标地址都不能为空');
				exit();
			}

			$page_url = $this->convertLink($page_url);

			if (strpos($page_url, 'http') === false) {
				$this->error('跳转地址框请输入链接');
				exit();
			}

			if (strpos($icon_url, 'http') === false) {
				$this->error('图标地址框请输入链接');
				exit();
			}

			$imginfo = getimagesize($icon_url);
			if (!empty($imginfo[0]) && !empty($imginfo[1])) {
				if ((200 < $imginfo[0]) || (200 < $imginfo[1])) {
					$this->error('图片大小不超过200px*200px');
					exit();
				}

				if ($imginfo[0] != $imginfo[1]) {
					$this->error('图片需为正方形');
					exit();
				}
			}

			$add_material = $this->shakearound->add_material($icon_url);
			$res_material = json_decode($add_material, true);
			if (($res_material['errcode'] == 0) && ($res_material['errmsg'] == 'success.')) {
				$pic_url = $res_material['data']['pic_url'];
				$respon = $this->shakearound->add_page($title, $description, $page_url, $pic_url, $page_comment);
				$result = json_decode($respon, true);
				if (($result['errcode'] == 0) && ($result['errmsg'] == 'success.')) {
					$data = array();
					$data['page_id'] = $result['data']['page_id'];
					$data['title'] = $title;
					$data['description'] = $description;
					$data['icon_url'] = $pic_url;
					$data['page_url'] = $page_url;
					$data['device_id'] = 0;
					$data['token'] = $this->token;
					$data['add_time'] = $this->now;
					$addpage = M('shakearoung_page')->add($data);

					if ($addpage) {
						$this->success('页面添加成功', U('Shakearound/page_index', array('token' => $this->token)));
						exit();
					}
					else {
						$this->error('页面入库失败');
						exit();
					}
				}
				else {
					$this->error('添加页面失败：' . $result['errmsg']);
					exit();
				}
			}
			else {
				$this->error('上传图标失败：' . $res_material['errmsg']);
				exit();
			}
		}

		$token = $this->_get('token', 'trim');
		$this->assign('token', $token);
		$this->display();
	}

	public function edit_page()
	{
		$db_page = M('shakearoung_page');

		if (IS_POST) {
			$title = $this->_post('title', 'trim');
			$description = $this->_post('description', 'trim');
			$page_url = $this->_post('page_url', 'trim');
			$icon_url = $this->_post('icon_url', 'trim');
			$page_comment = ($this->_post('page_comment', 'trim') ? $this->_post('page_comment', 'trim') : '');
			$post_id = $this->_post('id', 'intval');
			$post_token = $this->_post('token', 'trim');
			$page_id = $this->_post('page_id', 'intval');
			$pic_url = '';

			if (empty($page_id)) {
				$this->error('page_id获取失败');
				exit();
			}

			if (empty($title) || empty($description) || empty($page_url) || empty($icon_url)) {
				$this->error('标题、副标题、跳转地址、图标地址都不能为空');
				exit();
			}

			if ((strpos($page_url, '{siteUrl}') !== false) || (strpos($page_url, '{wechat_id}') !== false)) {
				$page_url = str_replace(array('{siteUrl}', '{wechat_id}'), array($this->siteUrl, ''), $page_url);
			}

			if (strpos($page_url, 'http') === false) {
				$this->error('跳转地址框请输入链接');
				exit();
			}

			if (strpos($icon_url, 'http') === false) {
				$this->error('图标地址框请输入链接');
				exit();
			}

			$imginfo = getimagesize($icon_url);
			if (!empty($imginfo[0]) && !empty($imginfo[1])) {
				if ((200 < $imginfo[0]) || (200 < $imginfo[1])) {
					$this->error('图片大小不超过200px*200px');
					exit();
				}

				if ($imginfo[0] != $imginfo[1]) {
					$this->error('图片需为正方形');
					exit();
				}
			}

			$page_info = $db_page->where(array('id' => $post_id, 'token' => $post_token, 'page_id' => $page_id))->find();

			if (empty($page_info)) {
				$this->error('获取修改项失败');
				exit();
			}

			if ($page_info['icon_url'] != $icon_url) {
				$add_material = $this->shakearound->add_material($icon_url);
				$res_material = json_decode($add_material, true);
				if (($res_material['errcode'] == 0) && ($res_material['errmsg'] == 'success.')) {
					$pic_url = $res_material['data']['pic_url'];
				}
				else {
					$this->error('上传图标失败:' . $res_material['errmsg']);
					exit();
				}
			}
			else {
				$pic_url = $page_info['icon_url'];
			}

			$update_respon = $this->shakearound->update_page($page_id, $title, $description, $page_url, $icon_url, $page_comment);
			$update_result = json_decode($update_respon, true);
			if (($update_result['errcode'] == 0) && ($update_result['errmsg'] == 'success.')) {
				$data = array();
				$data['title'] = $title;
				$data['description'] = $description;
				$data['page_url'] = $page_url;
				$data['icon_url'] = $pic_url;
				$data['page_comment'] = $page_comment;
				$update = $db_page->where(array('id' => $post_id, 'token' => $post_token))->save($data);

				if ($update) {
					$this->success('修改页面成功', U('Shakearound/page_index'));
					exit();
				}
				else {
					$this->error('更新页面失败');
					exit();
				}
			}
			else {
				$this->error('编辑页面失败：' . $update_result['errmsg']);
				exit();
			}
		}
		else {
			$id = $this->_get('id', 'intval');
			$token = $this->_get('token', 'trim');
			$set = $db_page->where(array('id' => $id, 'token' => $token))->find();

			if ($set) {
				$this->assign('set', $set);
			}
			else {
				$this->error('Token错误');
				exit();
			}
		}

		$this->display();
	}

	public function del_page()
	{
		$id = $this->_get('id', 'intval');
		$token = $this->_get('token', 'trim');
		if (empty($id) || empty($token)) {
			$this->error('非法操作,删除失败');
			exit();
		}

		$db_page = M('shakearoung_page');
		$page = $db_page->where(array('id' => $id, 'token' => $token))->find();

		if (!empty($page)) {
			$del_respon = $this->shakearound->delete_page((int) $page['page_id']);
			$del_result = json_decode($del_respon, true);
			if (($del_result['errcode'] == 0) && ($del_result['errmsg'] == 'success.')) {
				$del = $db_page->where(array('id' => $id, 'token' => $token))->delete();

				if ($del) {
					$this->success('删除成功', U('Shakearound/index', array('token' => $token)));
					exit();
				}
				else {
					$this->error('删除失败');
					exit();
				}
			}
			else {
				$this->error('删除页面失败：' . $del_result['errmsg']);
				exit();
			}
		}
		else {
			$this->error('删除项未找到,删除失败');
			exit();
		}
	}

	public function bindpage()
	{
		$db_device = M('shakearoung_device');
		$db_page = M('shakearoung_page');

		if (IS_POST) {
			$bind_ids = array();
			$unbind_ids = array();
			$stat = true;
			$msg = '';
			$device_id = $this->_post('device_id', 'intval');
			$token = $this->_post('token', 'trim');
			$page_id = $this->_post('page_id');
			$append = 0;
			if (empty($token) || empty($page_id)) {
				$this->error('绑定页面失败');
				exit();
			}

			$old_device_info = $db_device->where(array('token' => $token, 'device_id' => $device_id))->find();
			$old_bind_page = explode(';', $old_device_info['page_ids']);
			$bind_ids = array_diff($page_id, $old_bind_page);
			$bind_ids = array_filter($bind_ids);
			$unbind_ids = array_diff($old_bind_page, $page_id);
			$unbind_ids = array_filter($unbind_ids);
			$bind_ids = array_values($bind_ids);
			$unbind_ids = array_values($unbind_ids);

			if (!empty($bind_ids)) {
				$bind_result = $this->shakearound->bindpage($bind_ids, $device_id, $old_device_info['uuid'], $old_device_info['major'], $old_device_info['minor'], 1, $append);
				$respon_bind = json_decode($bind_result, true);
				if (($respon_bind['errcode'] != 0) && ($respon_bind['errmsg'] != 'success.')) {
					$stat = false;
					$msg .= '绑定页面失败:' . $respon_bind['errmsg'];
				}
			}

			if (!empty($unbind_ids)) {
				$unbind_result = $this->shakearound->bindpage($unbind_ids, $device_id, $old_device_info['uuid'], $old_device_info['major'], $old_device_info['minor'], 0, $append);
				$respon_unbind = json_decode($unbind_result, true);
				if (($respon_unbind['errcode'] != 0) && ($respon_unbind['errmsg'] != 'success.')) {
					$stat = false;
					$msg .= '解除绑定页面失败:' . $respon_bind['errmsg'];
				}
			}

			if ($stat) {
				$p_id = implode(';', $page_id);
				$update = $db_device->where(array('token' => $token, 'device_id' => $device_id))->save(array('page_ids' => $p_id));

				if ($update) {
					$this->success('绑定页面成功', U('Shakearound/index', array('token' => $token)));
					exit();
				}
				else {
					$this->error('绑定页面失败');
					exit();
				}
			}
			else {
				$this->error($msg);
				exit();
			}
		}

		$token = $this->_get('token', 'trim');
		$id = $this->_get('id', 'intval');
		$where = array('id' => $id, 'token' => $token);
		$device_info = $db_device->where($where)->find();

		if ($device_info) {
			$page_list = $db_page->where(array('token' => $token))->select();
			$this->assign('page_list', $page_list);
			$this->assign('set', $device_info);
			$this->assign('page_ids', explode(';', $device_info['page_ids']));
			$this->display();
		}
		else {
			$this->error('非法操作');
			exit();
		}
	}

	public function statistics()
	{
		$token = $this->_get('token', 'trim');
		$map = array();
		$map['token'] = $token;
		$map['status'] = array('neq', 0);
		$devices = M('shakearoung_device')->where($map)->order('id desc')->select();
		$pages = M('shakearoung_page')->where(array('token' => $token))->select();
		$this->assign('devices', $devices);
		$this->assign('pages', $pages);
		$this->assign('current_month', date('n'));
		$this->assign('current_year', date('Y'));
		$this->assign('token', $token);
		$this->display();
	}

	public function alldevice_statistics()
	{
		$year = ($_GET['year'] ? (int) $_GET['year'] : date('Y'));
		$month = (int) $_GET['month'];
		$days_count = date('t', mktime(0, 0, 0, $month, 1, $year));
		$token = trim($_GET['token']);
		$charts = array();
		$cache_name = 'alldevice_' . $token . '_' . $year . '_' . $month . '_statistics';

		for ($i = 1; $i <= $days_count; $i++) {
			$timestamp = mktime(23, 59, 59, $month, $i, $year);
			$cache_name = 'alldevice_' . $timestamp;
			$cache_data = cache($cache_name);

			if ($cache_data['status'] == 1) {
				$records = $cache_data['data'];
			}
			else {
				$respon_json = $this->shakearound->devicelist($timestamp, 1);
				$respon_result = json_decode($respon_json, true);
				$records = array();
				if (($respon_result['errcode'] == 0) && ($respon_result['errmsg'] == 'success.')) {
					$records = $respon_result['data']['devices'];

					if (50 < $respon_result['total_count']) {
						for ($i = 2; $i <= ceil($respon_result['total_count'] / 50); $i++) {
							$list = $this->shakearound->devicelist($date, $i);
							if (($list['errcode'] == 0) && ($list['errmsg'] == 'success.') && !empty($list['data']['devices'])) {
								$records = array_merge_recursive($records, $list['data']['devices']);
							}
						}
					}
				}
			}

			$click_pv = 0;
			$click_uv = 0;
			$shake_pv = 0;
			$shake_uv = 0;

			if (!empty($records)) {
				foreach ($records as $key => $value) {
					$click_pv += ($value['click_pv'] ? (int) $value['click_pv'] : 0);
					$click_uv += ($value['click_uv'] ? (int) $value['click_uv'] : 0);
					$shake_pv += ($value['shake_pv'] ? (int) $value['shake_pv'] : 0);
					$shake_uv += ($value['shake_uv'] ? (int) $value['shake_uv'] : 0);
				}
			}

			$charts['xAxis'] .= '"' . $i . '日",';
			$charts['click_pv'] .= '"' . $click_pv . '",';
			$charts['click_uv'] .= '"' . $click_uv . '",';
			$charts['shake_pv'] .= '"' . $shake_pv . '",';
			$charts['shake_uv'] .= '"' . $shake_uv . '",';
			cache($cache_name, array('data' => $records, 'status' => 1), $this->expiration_time);
		}

		if (!empty($charts)) {
			$this->assign('charts', $charts);
			$this->display('statistics_by_device');
		}
		else {
			$this->default_charts('charts', $days_count, 'statistics_by_device');
			exit();
		}
	}

	public function allpage_statistics()
	{
		$pageyear = ($_GET['pageyear'] ? (int) $_GET['pageyear'] : date('Y'));
		$pagemonth = (int) $_GET['pagemonth'];
		$days_count = date('t', mktime(0, 0, 0, $pagemonth, 1, $pageyear));
		$token = trim($_GET['token']);
		$charts = array();
		$cache_name = 'allpage_' . $token . '_' . $pageyear . '_' . $pagemonth . '_pages';

		for ($i = 1; $i <= $days_count; $i++) {
			$timestamp = mktime(23, 59, 59, $pagemonth, $i, $pageyear);
			$cache_name = 'allpage_' . $timestamp;
			$cache_data = cache($cache_name);

			if ($cache_data['status'] == 1) {
				$records = $cache_data['data'];
			}
			else {
				$respon_json = $this->shakearound->pagelist($timestamp, 1);
				$respon_result = json_decode($respon_json, true);
				$records = array();
				if (($respon_result['errcode'] == 0) && ($respon_result['errmsg'] == 'success.')) {
					$records = $respon_result['data']['pages'];

					if (50 < $respon_result['total_count']) {
						for ($i = 2; $i <= ceil($respon_result['total_count'] / 50); $i++) {
							$list = $this->shakearound->pagelist($date, $i);
							if (($list['errcode'] == 0) && ($list['errmsg'] == 'success.') && !empty($list['data']['devices'])) {
								$records = array_merge_recursive($records, $list['data']['devices']);
							}
						}
					}
				}
			}

			$click_pv = 0;
			$click_uv = 0;
			$shake_pv = 0;
			$shake_uv = 0;

			if (!empty($records)) {
				foreach ($records as $key => $value) {
					$click_pv += ($value['click_pv'] ? (int) $value['click_pv'] : 0);
					$click_uv += ($value['click_uv'] ? (int) $value['click_uv'] : 0);
					$shake_pv += ($value['shake_pv'] ? (int) $value['shake_pv'] : 0);
					$shake_uv += ($value['shake_uv'] ? (int) $value['shake_uv'] : 0);
				}
			}

			$pagecharts['xAxis'] .= '"' . $i . '日",';
			$pagecharts['click_pv'] .= '"' . $click_pv . '",';
			$pagecharts['click_uv'] .= '"' . $click_uv . '",';
			$pagecharts['shake_pv'] .= '"' . $shake_pv . '",';
			$pagecharts['shake_uv'] .= '"' . $shake_uv . '",';
			cache($cache_name, array('data' => $records, 'status' => 1), $this->expiration_time);
		}

		if (!empty($pagecharts)) {
			$this->assign('pagecharts', $pagecharts);
			$this->display('statistics_by_page');
		}
		else {
			$this->default_charts('pagecharts', date('t', $page_begin_date));
			exit();
		}
	}

	public function statistics_by_device()
	{
		$device_id = $this->_get('device_id', 'intval');
		$token = $this->_get('token', 'trim');
		$year = ($_GET['year'] ? (int) $_GET['year'] : date('Y'));
		$month = $this->_get('month', 'intval');
		$days_count = date('t', mktime(0, 0, 0, $month, 1, $year));
		$charts = array();
		$cache_name = 'device_' . $token . '_' . $device_id . '_' . $year . '_' . $month;
		$map = array();
		$map['device_id'] = $device_id;
		$map['token'] = $token;
		$map['status'] = array('neq', 0);
		$device_info = M('shakearoung_device')->where($map)->find();
		$begin_date = mktime(0, 0, 0, $month, 1, $year);

		if ($month == date('n')) {
			$end_date = time();
		}
		else {
			$end_date = mktime(0, 0, 0, $month, date('t', $begin_date), $year);
		}

		if ($month < date('n', $device_info['add_time'])) {
			$this->default_charts('charts', date('t', $begin_date));
			exit();
		}

		if (date('n', time()) < $month) {
			$this->default_charts('charts', date('t', $begin_date));
			exit();
		}

		if ($device_info) {
			$cache_data = cache($cache_name);

			if ($cache_data['status'] == 1) {
				$statistics_data = $cache_data['data'];
			}
			else {
				$statistics_respon = $this->shakearound->statistics_device($device_info['device_id'], $device_info['uuid'], $device_info['major'], $device_info['minor'], $begin_date, $end_date);
				$statistics_result = json_decode($statistics_respon, 1);
				if (($statistics_result['errcode'] == 0) && ($statistics_result['errmsg'] == 'success.') && !empty($statistics_result['data'])) {
					$statistics_data = $statistics_result['data'];
					cache($cache_name, array('status' => 1, 'data' => $statistics_result['data']), $this->expiration_time);
				}
				else {
					$this->default_charts('charts', date('t', $begin_date));
					exit();
				}
			}

			for ($i = 1; $i <= $days_count; $i++) {
				$click_pv = 0;
				$click_uv = 0;
				$shake_pv = 0;
				$shake_uv = 0;

				if (!empty($statistics_data)) {
					foreach ((array) $statistics_data as $key => $val) {
						if (($year . '-' . $month . '-' . $i) == date('Y-n-j', $val['ftime'])) {
							$click_pv = ($val['click_pv'] ? (int) $val['click_pv'] : 0);
							$click_uv = ($val['click_uv'] ? (int) $val['click_uv'] : 0);
							$shake_pv = ($val['shake_pv'] ? (int) $val['shake_pv'] : 0);
							$shake_uv = ($val['shake_uv'] ? (int) $val['shake_uv'] : 0);
						}
						else {
							break;
						}
					}
				}

				$charts['xAxis'] .= '"' . $i . '日",';
				$charts['click_pv'] .= '"' . $click_pv . '",';
				$charts['click_uv'] .= '"' . $click_uv . '",';
				$charts['shake_pv'] .= '"' . $shake_pv . '",';
				$charts['shake_uv'] .= '"' . $shake_uv . '",';
			}

			$this->assign('charts', $charts);
			$this->display();
		}
		else {
			$this->default_charts('charts', date('t', $begin_date));
			exit();
		}
	}

	public function statistics_by_page()
	{
		$page_id = $this->_get('page_id', 'intval');
		$token = $this->_get('token', 'trim');
		$pagemonth = $this->_get('pagemonth', 'intval');
		$pageyear = ($_GET['pageyear'] ? (int) $_GET['pageyear'] : date('Y'));
		$days_count = date('t', mktime(0, 0, 0, $pagemonth, 1, $pageyear));
		$cache_name = 'page_' . $token . '_' . $page_id . '_' . $pageyear . '_' . $pagemonth;
		$pagecharts = array();
		$page_begin_date = mktime(0, 0, 0, $pagemonth, 1, $pageyear);
		$is_binded = M('shakearoung_device')->query('select count(*) as count from ' . C('DB_PREFIX') . 'shakearoung_device where find_in_set(' . $page_id . ',page_ids) and token = \'' . $token . '\'');

		if ($is_binded[0]['count'] == 0) {
			$this->default_charts('pagecharts', date('t', $page_begin_date));
			exit();
		}

		$page_info = M('shakearoung_page')->where(array('page_id' => $page_id, 'token' => $token))->find();

		if ($pagemonth == date('n')) {
			$page_end_date = time();
		}
		else {
			$page_end_date = mktime(0, 0, 0, $pagemonth, date('t', $page_begin_date), $pageyear);
		}

		if ($pagemonth < date('n', $page_info['add_time'])) {
			$this->default_charts('pagecharts', date('t', $page_begin_date));
			exit();
		}

		if (date('n', time()) < $pagemonth) {
			$this->default_charts('pagecharts', date('t', $page_begin_date));
			exit();
		}

		if ($page_info) {
			$cache_data = cache($cache_name);

			if ($cache_data['page_status'] == 1) {
				$page_data = $cache_data['data'];
			}
			else {
				$page_respon = $this->shakearound->statistics_page($page_info['page_id'], $page_begin_date, $page_end_date);
				$page_result = json_decode($page_respon, 1);
				if (($page_result['errcode'] == 0) && ($page_result['errmsg'] == 'success.') && !empty($page_result['data'])) {
					$page_data = $page_result['data'];
					cache($cache_name, array('page_status' => 1, 'data' => $page_result['data']), $this->expiration_time);
				}
				else {
					$this->default_charts('pagecharts', date('t', $page_begin_date));
					exit();
				}
			}

			$pagecharts = '';

			for ($i = 1; $i <= $days_count; $i++) {
				$click_pv = 0;
				$click_uv = 0;
				$shake_pv = 0;
				$shake_uv = 0;

				if (!empty($page_data)) {
					foreach ((array) $page_data as $key => $val) {
						if (($pageyear . '-' . $pagemonth . '-' . $i) == date('Y-n-j', $val['ftime'])) {
							$click_pv = ($val['click_pv'] ? (int) $val['click_pv'] : 0);
							$click_uv = ($val['click_uv'] ? (int) $val['click_uv'] : 0);
							$shake_pv = ($val['shake_pv'] ? (int) $val['shake_pv'] : 0);
							$shake_uv = ($val['shake_uv'] ? (int) $val['shake_uv'] : 0);
						}
						else {
							break;
						}
					}
				}

				$pagecharts['xAxis'] .= '"' . $i . '日",';
				$pagecharts['click_pv'] .= '"' . $click_pv . '",';
				$pagecharts['click_uv'] .= '"' . $click_uv . '",';
				$pagecharts['shake_pv'] .= '"' . $shake_pv . '",';
				$pagecharts['shake_uv'] .= '"' . $shake_uv . '",';
			}

			$this->assign('pagecharts', $pagecharts);
			$this->display();
		}
		else {
			$this->default_charts('pagecharts', date('t', $page_begin_date));
			exit();
		}
	}

	private function default_charts($assign = 'charts', $times = 30, $template = ACTION_NAME)
	{
		$data = array();

		for ($i = 1; $i <= $times; $i++) {
			$data['xAxis'] .= '"' . $i . '日",';
			$data['click_pv'] .= '"0",';
			$data['click_uv'] .= '"0",';
			$data['shake_pv'] .= '"0",';
			$data['shake_uv'] .= '"0",';
		}

		$this->assign($assign, $data);
		$this->display($template);
		exit();
	}
}

?>
