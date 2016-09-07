<?php

class ExtensionAction extends UserAction
{
	private $actModel;

	public function _initialize()
	{
		$this->actModel = new ExtensionActivityModel();
		parent::_initialize();
	}

	public function index()
	{
		$get = array_merge(array('_create_time' => 'desc'), $_GET);
		$get['token'] = $this->token;
		$count = $this->actModel->getTotal($get);
		$page = new Page($count, 15);
		$list = $this->actModel->getList($get, $page->firstRow, $page->listRows);
		$this->assign('_list', $list);
		$this->assign('_get', $get);
		$this->assign('page', $page->show());
		$this->display();
	}

	public function add()
	{
		if (IS_GET) {
			$path = "./uploads/defaultfonts/yahei.ttf";
			$this->assign('hasFont', file_exists($path) ? '1' : '0');
			$this->assign('_info', array('company' => $this->wxuser['wxname'], 'start_time' => time(), 'end_time' => time() + (24 * 3600 * 30)));
			$this->display();
		}
		else if (IS_POST) {
			$this->actModel->setToken($this->token);

			if (!$this->actModel->processing($_POST, 'add')) {
				$this->error($this->actModel->getError());
			}

			$id = $this->actModel->add();

			if (!$id) {
				$this->error('新增推广海报失败');
			}

			$keywordModel = new KeywordModel();
			$keywordModel->addKeyword($id, 'Extension', I('post.keyword'));
			$this->success('新增推广海报成功', U('index', array('token' => $this->token)));
		}
	}

	public function installFont()
	{
		$path = "./uploads/defaultfonts/yahei.ttf";

		if (file_exists($path)) {
			$this->success('安装成功');
			exit();
		}

		$url = $this->staticPath . '/tpl/static/extension/fonts/yahei.ttf';
		$url = (strpos($url, '/') === 0 ? '.' . $url : $url);
		$data = file_get_contents($url);

		if (empty($data)) {
			$this->error('安装失败');
		}

		if (!file_exists(dirname($path))) {
			mkdir(dirname($path), 511, true);
		}

		if (!file_put_contents($path, $data)) {
			$this->error('安装失败');
		}

		$this->success('安装成功');
	}

	public function edit()
	{
		if (IS_GET) {
			if (empty($_GET['id'])) {
				$this->error('推广海报不存在');
			}

			$path = "./uploads/defaultfonts/yahei.ttf";
			$this->assign('hasFont', file_exists($path) ? '1' : '0');
			$where['token'] = $this->token;
			$where['id'] = I('get.id');
			$result = $this->actModel->where($where)->find();

			if (empty($result)) {
				$this->error('推广海报不存在');
			}

			$this->assign('_info', $result);
			$this->display('add');
		}
		else if (IS_POST) {
			$this->actModel->setToken($this->token);

			if (!$this->actModel->processing($_POST, 'update')) {
				$this->error($this->actModel->getError());
			}

			$num = $this->actModel->where(array('id' => I('post.id'), 'token' => $this->token))->save();

			if (!$num) {
				$this->error('修改推广海报失败');
			}

			$keywordModel = new KeywordModel();
			$keywordModel->addKeyword($_POST['id'], 'Extension', I('post.keyword'));
			$this->success('修改推广海报成功', U('index', array('token' => $this->token)));
		}
	}

	public function uploadFile()
	{
		if (empty($_FILES['file'])) {
			$this->ajaxReturn(array('info' => '上传文件不存在', 'status' => 0));
		}

		$upload = new UploadFile();
		$upload->maxSize = 2 * 1024 * 1024;
		$upload->autoSub = true;
		$uploadPath = './uploads/extension/bg/' . $this->token . '/';
		$upload->savePath = $uploadPath;

		if (!file_exists($uploadPath)) {
			mkdir($uploadPath, 511, true);
		}

		$upload->allowExts = array('jpg', 'jpeg', 'png', 'gif');

		if (!$upload->upload()) {
			$this->ajaxReturn(array('status' => 0, 'info' => $upload->getErrorMsg()));
		}

		$info = $upload->getUploadFileInfo();
		$image = new Image();
		$imgPath = $info[0]['savepath'] . $info[0]['savename'];
		$dirName = dirname($imgPath);
		$imgName = basename($imgPath);
		$sizeArr = array(
			array(400, 256),
			array(320, 500),
			array(320, 320)
			);
		$pathData = array('path' => $this->siteUrl . ltrim($info[0]['savepath'], '.') . $info[0]['savename']);
		$targetWidth = 900;

		for ($i = 0; $i < count($sizeArr); $i++) {
			if (!file_exists($dirName . '/' . $sizeArr[$i][0] . '_' . $sizeArr[$i][1])) {
				mkdir($dirName . '/' . $sizeArr[$i][0] . '_' . $sizeArr[$i][1], 511, true);
			}

			$image->thumb2($imgPath, $dirName . '/' . $sizeArr[$i][0] . '_' . $sizeArr[$i][1] . '/' . $imgName, '', $sizeArr[$i][0], $sizeArr[$i][1]);
			$pathData[$sizeArr[$i][0] . '_' . $sizeArr[$i][1]] = $this->siteUrl . ltrim($dirName, '.') . '/' . $sizeArr[$i][0] . '_' . $sizeArr[$i][1] . '/' . $imgName;
			$scale = $targetWidth / $sizeArr[$i][0];
			$scaleWidth = round($sizeArr[$i][0] * $scale);
			$scaleHeight = round($sizeArr[$i][1] * $scale);

			if (!file_exists($dirName . '/' . $scaleWidth . '_' . $scaleHeight)) {
				mkdir($dirName . '/' . $scaleWidth . '_' . $scaleHeight, 511, true);
			}

			$image->thumb2($imgPath, $dirName . '/' . $scaleWidth . '_' . $scaleHeight . '/' . $imgName, '', $scaleWidth, $scaleHeight);
		}

		$this->ajaxReturn(array('status' => 1, 'info' => '上传成功', 'data' => $pathData));
	}

	public function del()
	{
		$ids = get_request_ids();

		if (count($ids) <= 0) {
			$this->error('请选择推广海报');
		}

		$where['id'] = array('in', $ids);
		$where['token'] = $this->token;
		$result = $this->actModel->where($where)->delete();

		if ($result <= 0) {
			$this->error('删除失败');
		}

		$this->success('删除成功');
	}

	public function changeStatus()
	{
		$ids = get_request_ids();

		if (count($ids) <= 0) {
			$this->error('请选择推广海报');
		}

		$where['id'] = array('in', $ids);
		$where['token'] = $this->token;
		$status = I('request.status');
		$result = $this->actModel->where($where)->save(array('status' => $status));

		if ($result <= 0) {
			$this->error($status == '1' ? '发布失败' : '关闭失败');
		}

		$this->success($status == '1' ? '发布成功' : '关闭成功');
	}

	public function promote()
	{
		if (empty($_GET['id'])) {
			$this->error('推广海报不存在');
		}

		$where['token'] = $this->token;
		$where['id'] = I('get.id');
		$result = $this->actModel->where($where)->find();

		if (empty($result)) {
			$this->error('推广海报不存在');
		}

		$this->assign('_info', $result);
		$extensionUser = D('ExtensionUser');
		$get = array_merge(array('_create_time' => 'desc'), $_GET);
		$get['token'] = $this->token;
		$count = $extensionUser->getTotal($get);
		$page = new Page($count, 15);
		$list = $extensionUser->getList($get, $page->firstRow, $page->listRows);
		$this->assign('_list', $list);
		$this->assign('_get', $get);
		$this->assign('page', $page->show());
		$this->display();
	}

	public function follower()
	{
		if (empty($_GET['aid'])) {
			$this->error('推广海报不存在');
		}

		$pid = I('get.pid');
		$where['token'] = $this->token;
		$where['id'] = I('get.aid');
		$result = $this->actModel->where($where)->find();

		if (empty($result)) {
			$this->error('推广海报不存在');
		}

		$this->assign('_info', $result);
		$userInfoModel = D('Userinfo');
		$userInfo = $userInfoModel->where(array('id' => I('get.uid')))->find();

		if (empty($userInfo)) {
			$this->error('用户不存在');
		}

		$this->assign('user_info', $userInfo);
		$extensionRelation = new ExtensionRelationModel();
		$get = array_merge(array('_follow_time' => 'desc'), $_GET);
		$get['token'] = $this->token;
		$count = $extensionRelation->getTotal($get);
		$page = new Page($count, 15);
		$list = $extensionRelation->getList($get, $page->firstRow, $page->listRows);
		$this->assign('_list', $list);
		$this->assign('_get', $get);
		$this->assign('page', $page->show());
		$parents = $extensionRelation->getParents($pid, I('get.aid'), I('get.uid'));
		$this->assign('_parents', $parents);
		$this->display();
	}
}

?>
