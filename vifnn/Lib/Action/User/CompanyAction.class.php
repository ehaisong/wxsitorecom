<?php
class CompanyAction extends UserAction{
	public $token;
	public $isBranch;
	public $company_model;
	private $_business_type = array('Repast' => '订餐', 'Store' => '商城', 'DishOut' => '外卖', 'Hotels' => '酒店');
	public function _initialize() {
		parent::_initialize();
		$this->token=session('token');
		$this->assign('token',$this->token);
		//权限
		if ($this->token!=$_GET['token']){
			exit();
		}
		//是否是分店
		$this->isBranch=0;
		if (isset($_GET['isBranch'])&&intval($_GET['isBranch'])){
			$this->isBranch=1;
		}
		$this->assign('isBranch',$this->isBranch);
		//
		$this->company_model=M('Company');
	}
	
	public function index()
	{
		/*
		if (!$this->isBranch){
			$fatherCompany = $this->company_model->where(array('token' => $this->token, 'isbranch' => 0))->order('id ASC')->find();
			if ($fatherCompany){
				$tj = array('token' => $this->token);
				$tj['id'] = array('neq', intval($fatherCompany['id']));
				$this->company_model->where($tj)->save(array('isbranch' => 1));
			}
		}*/
		$where = array('token' => $this->token,'business_type'=>array('neq','Medical'));
		if ($this->isBranch) {
			$where['id'] = intval($_GET['id']);
			$where['isbranch'] = 1;
		} else {
			$where['isbranch'] = 0;
		}
		$thisCompany = $this->company_model->where($where)->find();
		$max_id = $this->company_model->where(array('token' => $this->token))->max('id');
		if (IS_POST) {
			$_POST['password'] = isset($_POST['password']) && $_POST['password'] ? md5(trim($_POST['password'])) : '';
			$_POST['business_type'] = implode(',', $_POST['business_type']);
			if(empty($_POST['tel'])) $this->error('电话不能为空');
			if(empty($_POST['logourl'])) $this->error('Logo地址不能为空');
			if($_POST['avg_price'] == ''){
				$this->error('人均消费不能为空');
			}elseif(!is_numeric($_POST['avg_price'])){
				$this->error('人均消费请输入数字');
			}
			if(empty($_POST['business_type']) && $this->isBranch) $this->error('经营类型不能为空');
			$jump_url = 'index';
			if (!$thisCompany) {
				if(empty($_POST['name'])) $this->error('名称不能为空');
				if(empty($_POST['province']) || empty($_POST['city']) || empty($_POST['district']))$this->error('门店地址省市地区不能为空');
				if($_POST['longitude'] == '') $this->error('经度不能为空');
				if($_POST['latitude'] == '') $this->error('纬度不能为空');
				if(empty($_POST['address'])) $this->error('详细地址不能为空');
				//将添加的门店同步到微信
				if ($this->isBranch) {
					//if($_POST['shortname'] == '') $this->error('分支机构名称不能为空');
					if(empty($_POST['username'])) $this->error('分支登陆账号不能为空');
					if(empty($_POST['password'])) $this->error('分支登陆密码不能为空');
					$jump_url = 'branches';
				}
				if(in_array($this->wxuser['winxintype'],array(3,4))){
					if($_POST['categories'] == ""){
						$this->error('门店类型未获取到,请确定您是否开启了微信门店插件');
					}
				}
				$_POST['sid'] = uniqid();
				$_POST['available_state'] = 0;
				$_POST['add_time'] = $_SERVER["REQUEST_TIME"];
				$company_id = $this->company_model->add($_POST);
				if($company_id){
					if(in_array($this->wxuser['winxintype'],array(3,4))){
						$coupons 	= new WechatCoupons($this->wxuser);
						$res  		= $coupons->addCompany($_POST);
						if($res['errcode'] == 0){
							$this->company_model->where(array('id'=>$company_id))->save(array('available_state'=>2));
						}else{
							$this->company_model->where(array('id'=>$company_id))->delete();
							$this->error($res['errmsg']);
						}
					}
					S($this->token.'_shoplist',null);//清除微信wifi的门店列表缓存
					$this->success('添加成功',U('Company/'.$jump_url, array('token' => $this->token, 'isBranch' => $this->isBranch)));exit;
				}else{
					$this->error('添加失败');
				}
			} else {
				if(in_array($this->wxuser['winxintype'],array(3,4)) && $thisCompany['available_state'] == 2){
					$this->error('门店还在审核中');
				}
				$amap = new amap();
				if (!$thisCompany['amapid'] && $thisCompany['longitude'] == $_POST['longitude']) {
					$locations = $amap->coordinateConvert($thisCompany['longitude'], $thisCompany['latitude']);
					$_POST['longitude'] = $locations['longitude'];
					$_POST['latitude'] = $locations['latitude'];
				}
				if (!$thisCompany['amapid']) {
					$ampaid = $amap->create($_POST['name'], $_POST['longitude'] . ',' . $_POST['latitude'], $_POST['tel'], $_POST['address']);
					$_POST['amapid'] = intval($ampaid);
				} else {
					$amap->update($thisCompany['amapid'], $_POST['name'], $_POST['longitude'] . ',' . $_POST['latitude'], $_POST['tel'], $_POST['address']);
				}
				if($this->company_model->create()) {
					if (empty($_POST['password'])) unset($_POST['password']);
					$jump_url = ($this->isBranch) ? 'branches' : 'index';
					if($thisCompany['logourl'] == $_POST['logourl']){ unset($_POST['logourl']);}
					$_POST['location_id'] = $thisCompany['location_id'];
					unset($_POST['id']);
					$update = $this->company_model->where($where)->save($_POST);
					$_POST['id'] = $thisCompany['id'];
					if($update){
						if(in_array($this->wxuser['winxintype'],array(3,4))){
							$coupons 	= new WechatCoupons($this->wxuser);
							$res  		= $coupons->updatepoi($_POST);
							if($res['errcode'] == 0){
								$this->company_model->where($where)->save(array('available_state'=>2));
							}
						}
						$this->success('修改成功', U('Company/'.$jump_url, array('token' => $this->token, 'isBranch' => $this->isBranch)));exit;
					}else{
						$this->error('修改失败');
					}
				} else {
					$this->error($this->company_model->getError());
				}
			}
		} else {
			if(in_array($this->wxuser['winxintype'],array(3,4))){
				$category_list = include('./vifnn/Lib/ORG/CategoryList.php');
			}
			$this->assign('category_list',$category_list['category_list']);
			$thisCompany['business_type'] = explode(',', $thisCompany['business_type']);
			/*if ($this->isBranch) {
				$thisCompany['name'] = $this->company_model->where(array('token' => $this->token, 'isbranch' => 0))->order('id ASC')->getField('name');
			}*/
			$this->assign('winxintype',$this->wxuser['winxintype']);
			$this->assign('set', $thisCompany);
			$this->display();
		}
	}
	public function branches(){
		$thisCompany=$this->company_model->where(array('token'=>$this->token))->order('id ASC')->find();
		$where=array('token'=>$this->token);
		$where	  	=array('token'=>$this->token,'isbranch'=>1);
		$branches 	= $this->company_model->where($where)->order('taxis ASC')->select();
		
		$list = array();
		foreach ($branches as $b) {
			$b['url'] = $_SERVER['HTTP_HOST'] . '/index.php?m=Index&a=clogin&cid=' . $b['id'] . '&k=' . md5($b['id'] . $b['username']);
			$t_business = explode(',', $b['business_type']);
			$b['business_type_name'] = '';
			$pre = '';
			foreach ($t_business as $v) {
				$b['business_type_name'] .= $pre . $this->_business_type[$v];
				$pre = ',';
			}
			$list[] = $b;
		}
		$this->assign('branches', $list);
		$this->assign('winxintype',$this->wxuser['winxintype']);
		$this->display();
	}
	public function delete(){
		$where=array('token'=>$this->token,'id'=>intval($_GET['id']));
		$thisCompany=$this->company_model->where($where)->find();
		$where=array('token'=>$this->token,'id'=>intval($_GET['id']));
		$jump = ($_GET['isBranch'] == 1) ? 'branches' : 'index';
		$thisCompany=$this->company_model->where($where)->find();
		if(in_array($this->wxuser['winxintype'],array(3,4)) && $thisCompany['available_state'] == 2){
			$this->error('门店还在审核中');
		}
		$rt=$this->company_model->where($where)->delete();
		if($rt==true){
			$amap=new amap();
			$amap->delete($thisCompany['amapid']);
			if($thisCompany['location_id'] == ''){ 
				$coupons 	= new WechatCoupons($this->wxuser);
				$res  		= $coupons->delpoi($thisCompany['location_id']);
			}
			$this->success('删除成功',U('Company/'.$jump,array('token'=>$this->token,'isBranch'=>$_GET['isBranch'])));exit;
		}else{
			$this->error('服务器繁忙,请稍后再试',U('Company/'.$jump,array('token'=>$this->token,'isBranch'=>$_GET['isBranch'])));
		}
	}
	public function deletecompany(){
		$where=array('token'=>$this->token,'id'=>intval($_GET['id']));
		$thisCompany=$this->company_model->where($where)->find();
		$where=array('token'=>$this->token,'id'=>intval($_GET['id']));
		$jump = ($_GET['branches'] == 1) ? 'branches' : 'index';
		$thisCompany=$this->company_model->where($where)->find();
		$rt=$this->company_model->where($where)->delete();
		if($rt==true){
			$amap=new amap();
			$amap->delete($thisCompany['amapid']);
			$this->success('删除成功',U('Company/'.$jump,array('token'=>$this->token,'isBranch'=>$_GET['isBranch'])));exit;
		}else{
			$this->error('服务器繁忙,请稍后再试',U('Company/'.$jump,array('token'=>$this->token,'isBranch'=>$_GET['isBranch'])));
		}
	}
	//更新门店状态
	public function updateallpoi(){
		if($this->wxuser['winxintype'] != 3 && $this->wxuser['winxintype'] != 4){
			$this->error('非法操作');
		}
		$company 	= $this->company_model->where(array('token'=>$this->token))->select();
		$jump = ($_GET['isBranch'] == 1) ? 'branches' : 'index';
		$wxUser = M("Wxuser")->where(array('token'=>$this->token))->find();
		$apiOauth 	= new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($wxUser['appid']);
		$url = 'https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token='.$access_token;
		$json = '{"begin":0,"limit":50}';
		$shop_list = array();
		$list = array();
		$shop_list = $apiOauth->https_request($url,$json);
		if ($shop_list['errcode'] == 0 && !empty($shop_list['business_list'])) {
			$shopNum = $shop_list['total_count'];
			if($shopNum > 50){
				for($i=1;$i<ceil($shopNum/50);$i++){
					$extend_json = '{"begin":'.$i.',"limit":50}';
					$list  = $apiOauth->https_request($url,$extend_json);
					if($list['errcode'] == 0){
						$shop_list['business_list'] = array_merge_recursive($shop_list['business_list'],$list['business_list']);
					}
				}
			}
		}else{
			return false;
		}
		$business_list = $shop_list['business_list'];
		foreach ($business_list as $key => $value){
			$savedata = array('location_id'=>$value['base_info']['poi_id'],'available_state'=>$value['base_info']['available_state']);
			if(M('company')->where(array('sid'=>$value['base_info']['sid']))->find()){
				M('company')->where(array('sid'=>$value['base_info']['sid']))->save($savedata);
			}elseif(M('company')->where(array('name'=>$value['base_info']['business_name']))->find()){
				M('company')->where(array('name'=>$value['base_info']['business_name']))->save($savedata);
			}
		}
		$this->success('更新成功',U('Company/'.$jump,array('token'=>$this->token,'isBranch'=>$_GET['isBranch'])));exit;
	}
	//门店类目表
	private function category_list(){
		$coupons 	= new WechatCoupons($this->wxuser);
		$res  = $coupons->category_list();
		return $res;
	}
}


?>