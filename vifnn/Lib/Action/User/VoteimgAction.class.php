<?php 
/**
* 图片投票
**/
class VoteimgAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		
		$this->canUseFunction('Voteimg');
	}
	//后台首页
	public function index(){
		$where = array('token'=>session('token'));
		$total = M('voteimg')->where($where)->count();
		$Page = new Page($total,10);
		$list = M('voteimg')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->assign('token',session('token'));
		$this->display();
	}
	//添加活动
	public function add_voteimg(){
		if(IS_POST){
			$action_data = array();
			$action_data['keyword'] = $this->_post('keyword','trim');
			$action_data['action_name'] = $this->_post('action_name','trim');
			$action_data['reply_title'] = $this->_post('reply_title','trim');
			$action_data['reply_content'] = $this->_post('reply_content','trim');
			$action_data['reply_pic'] = $this->_post('reply_pic','trim');
			if(strlen($action_data['keyword']) > 30){
				echo json_encode(array('status'=>'fail','data'=>'关键词不超过10个汉字'));exit;
			}
			if(strlen($action_data['action_name']) > 150){
				echo json_encode(array('status'=>'fail','data'=>'活动名称不超过50个汉字'));exit;
			}
			if(strpos($action_data['reply_pic'],'http') === false){
				$action_data['reply_pic'] = $this->siteUrl.$action_data['reply_pic'];
			}
			if((int)$_POST['id'] != ""){ //修改
				$update_action = M('voteimg')->where(array('id'=>(int)$_POST['id']))->save($action_data);
				//修改关键词
				$this->handleKeyword($this->_post('id','intval'),'Voteimg',$this->_post('keyword','trim'));
				if($update_action !== false){
					echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
				}else{
					echo json_encode(array('status'=>'fail','data'=>'投票活动修改失败,请稍后再试'));exit;
				}
			}else{	//添加
				$action_data['start_time'] = (int)strtotime($_POST['start_time']);
				$action_data['end_time'] = (int)strtotime($_POST['end_time']);
				//报名时间
				$action_data['apply_start_time'] = (int)strtotime($_POST['apply_start_time']);
				$action_data['apply_end_time'] = (int)strtotime($_POST['apply_end_time']);
				$action_data['is_follow'] = 2;
				$action_data['is_register'] = 1;
				$action_data['limit_vote'] = 0;
				$action_data['limit_vote_day'] = 0;
				$action_data['limit_vote_item'] = 0;
				$action_data['display'] = 1;
				$action_data['self_status']=1;
				$action_data['phone'] = '';
				$action_data['page_type'] = 'waterfall';
				$action_data['token'] = session('token');
				$action_data['default_skin'] = 1;
				$action_data['territory_limit'] = 0;
				$action_data['img_count'] = 1;
				$action_data['need_check'] = 1;
				$vote_id = M('voteimg')->add($action_data);
				if($vote_id){
					//添加内置统计栏目
					$this->add_stat($vote_id);
					//添加内置菜单
					$this->add_menu($vote_id,array('start_time'=>$action_data['start_time'],'end_time'=>$action_data['end_time']));
					//添加内置底部导航
					$this->bottom_nav($vote_id);
					//添加关键词
					$this->handleKeyword($vote_id,'Voteimg',$this->_post('keyword','trim'));
					//$this->success('投票活动添加成功',U('Voteimg/index'));exit;
					echo json_encode(array('status'=>'done','data'=>$vote_id));exit;
				}else{
					//$this->error('投票活动添加失败');exit;
					echo json_encode(array('status'=>'fail','data'=>'投票活动添加失败,请稍后再试'));exit;
				}
			}
		}else{ //修改默认值
			if(!empty($_GET['token'])){
				$set = M('voteimg')->where(array('id'=>$_GET['id'],'token'=>$_GET['token']))->find();
				$where = array('vote_id'=>$_GET['id'],'token'=>$_GET['token'],'check_pass'=>1,'upload_type'=>1);
				$total = M('voteimg_item')->where($where)->count();
				$Page = new Page($total,5);
				$vote_item = M('voteimg_item')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
				if(strpos($set['pro_city'],'|') !== false){
					$limit_area = explode("|",$set['pro_city']);
					foreach($limit_area as $k=>$v){
						list($province_name[$k],$city_name[$k]) = explode("_",$v);
					}
					$this->assign('province_name',$province_name);
					$this->assign('city_name',$city_name);
				}else{
					list($province_name[],$city_name[]) = explode("_",$set['pro_city']);
				}
				//奖品修改
				$prizelist = M('voteimg_prize')->where(array('vote_id'=>(int)$_GET['id']))->order('id asc')->select();
				//赞助商修改
				$sponsorlist = M('voteimg_sponor')->where(array('vote_id'=>(int)$_GET['id']))->order('id asc')->select();
				//菜单管理
				$menulist = M('voteimg_menus')->where(array('vote_id'=>(int)$_GET['id'],'token'=>$_GET['token']))->order('id asc')->select();
				//底部导航管理
				$bottomlist = M('voteimg_bottom')->where(array('vote_id'=>(int)$_GET['id'],'token'=>$_GET['token']))->order('id asc')->select();
				//横幅管理
				$banner = M('voteimg_banner')->where(array('vote_id'=>(int)$_GET['id'],'token'=>$_GET['token']))->select();
				//统计信息管理
				$statinfo = M('voteimg_stat')->where(array('vote_id'=>(int)$_GET['id'],'token'=>$_GET['token']))->order('id asc')->find();
				$split = explode(",",$statinfo['stat_name']);
				$this->assign('stat_name',$split);
				$this->assign('hide',$statinfo['hide']);
				$this->assign('count',$statinfo['count']);
				$this->assign('banner',$banner);
				$this->assign('bottomlist',$bottomlist);
				$this->assign('menulist',$menulist);
				$this->assign('prizelist',$prizelist);
				$this->assign('sponsorlist',$sponsorlist);
				$this->assign('province_name',$province_name);
				$this->assign('city_name',$city_name);
				$this->assign('voteimg',$set);
				$this->assign('vote_item',$vote_item);
				$this->assign('page',$Page->show());
				$this->assign('winxintype',$this->wxuser['winxintype']);
			}else{
				$this->error('参数错误,token不能为空');
			}
			//拆分旧数据
			$this->splittable($_GET['id'],$_GET['token']);
			$this->assign('token',$_GET['token']);
			$this->display();
		}
	}
	//活动设置
	public function add_voteimg_action(){
		if(IS_POST && $_POST['id'] != ""){
			$action_data = array();
			$action_data['action_desc'] = str_replace('&nbsp;','',$this->_post('action_desc','trim'));
			$action_data['join_desc'] = str_replace('&nbsp;','',$this->_post('join_desc','trim'));
			$action_data['flow_desc'] = str_replace('&nbsp;','',$this->_post('flow_desc','trim'));
			$action_data['award_desc'] = str_replace('&nbsp;','',$this->_post('award_desc','trim'));
			$action_data['start_time'] = (int)strtotime($_POST['start_time']);
			$action_data['end_time'] = (int)strtotime($_POST['end_time']);
			//报名时间
			$action_data['apply_start_time'] = (int)strtotime($_POST['apply_start_time']);
			$action_data['apply_end_time'] = (int)strtotime($_POST['apply_end_time']);
			$action_data['limit_vote'] = !empty($_POST['limit_vote']) ? (int)$_POST['limit_vote'] : 0;
			$action_data['limit_vote_day'] = !empty($_POST['limit_vote_day']) ? (int)$_POST['limit_vote_day'] : 0;
			$action_data['limit_vote_item'] = !empty($_POST['limit_vote_item']) ? (int)$_POST['limit_vote_item'] : 0;
			$action_data['is_follow'] = (int)$_POST['is_follow'];
			$action_data['is_numtime'] = (int)$_POST['is_numtime']; //限制重复报名jm73com
			$action_data['is_register'] = (int)$_POST['is_register'];
			$action_data['display'] = (int)$_POST['display'];
			$action_data['need_check'] = (int)$_POST['need_check'];
			$action_data['self_status']=(int)$_POST['self_id'];
			$action_data['phone'] = $_POST['phone'];
			$action_data['img_count'] = $_POST['img_count'] ? (int)$_POST['img_count'] : 1;
			$action_data['page_type'] = $_POST['page_type'];
			$action_data['default_skin'] = $this->_post('default_skin','intval');
			$action_data['territory_limit'] = $this->_post('territory_limit','intval');
			$action_data['onoff_voice'] = $this->_post('onoff_voice','intval');
			$action_data['onoff_video'] = $this->_post('onoff_video','intval');
			if($action_data['territory_limit'] == 1){
				$provinces = $_POST['province_name'];
				$citys = $_POST['city_name'];
				foreach($provinces as $key=>$val){
					if(!empty($val)){
						$pro_city .= $val.'_'.$citys[$key].'|';
					}
				}
				if(trim($pro_city,'|') == ""){
					echo json_encode(array('status'=>'fail','data'=>'您开启了地区限制,请选择至少一个限制省市'));
					exit;
				}
				$action_data['pro_city'] = trim($pro_city,'|');
				if($action_data['is_register'] != 1){
					echo json_encode(array('status'=>'fail','data'=>'您开启了地区限制,是否需要粉丝信息项请选择【是】'));exit;
				}
			}
			$action_data['register_msg'] = $this->_post('register_msg','trim');
			if($action_data['limit_vote'] != 0){
				if($action_data['limit_vote_day'] > $action_data['limit_vote']){
					echo json_encode(array('status'=>'fail','data'=>'限制每天投票数不能大于限制总的投票数'));exit;
				}
			}
			if($action_data['limit_vote_day'] !=0){
				if($action_data['limit_vote_item'] > $action_data['limit_vote_day']){
					echo json_encode(array('status'=>'fail','data'=>'限制某个选项每天的得票数不能大于限制每天的投票数'));exit;
				}
			}
			if($action_data['start_time'] > $action_data['end_time']){
				echo json_encode(array('status'=>'fail','data'=>'活动开始时间不能在活动结束时间之后'));exit;
			}
			if($action_data['apply_start_time'] > $action_data['apply_end_time']){
				echo json_encode(array('status'=>'fail','data'=>'报名开始时间不能在报名结束时间之后'));exit;
			}
			if($action_data['apply_end_time'] > $action_data['end_time']){
				echo json_encode(array('status'=>'fail','data'=>'报名截止时间不能在活动结束时间之后'));exit;
			}
			if((int)$_POST['id'] != ""){ //修改
				$update_action = M('voteimg')->where(array('id'=>(int)$_POST['id']))->save($action_data);
				if($update_action !== false){
					echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
				}else{
					echo json_encode(array('status'=>'fail','data'=>'活动设置失败'));exit;
				}
			}
		}else{
			echo json_encode(array('status'=>'fail','data'=>'活动设置失败'));exit;
		}
	}
	//吸粉设置
	public function add_voteimg_fans(){
		if(IS_POST && $_POST['id'] != ""){
			$action_data = array();
			$action_data['onoff_hongbao'] = (int)$_POST['onoff_hongbao'];
			$action_data['lottery_type'] = (int)$_POST['lottery_type'];
			$action_data['lottery_id'] = (int)$_POST['lottery_id'];
			$action_data['lottery_name'] = $this->_post('lottery_name','trim');
			$action_data['follow_msg'] = $this->_post('follow_msg','trim');
			$action_data['follow_url'] = $this->_post('follow_url','trim');
			$action_data['follow_btn_msg'] = $this->_post('follow_btn_msg','trim');
			$action_data['register_msg'] = $this->_post('register_msg','trim');
			$action_data['custom_sharetitle'] = $this->_post('custom_sharetitle','trim');
			$action_data['custom_sharedsc'] = $this->_post('custom_sharedsc','trim');
			$action_data['custom_sharetitle_lp'] = $this->_post('custom_sharetitle_lp','trim');
			$action_data['custom_sharedsc_lp'] = $this->_post('custom_sharedsc_lp','trim');
			if($_POST['id'] != ""){ //修改
				$update_action = M('voteimg')->where(array('id'=>(int)$_POST['id']))->save($action_data);
				if($update_action !== false){
					echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
				}else{
					echo json_encode(array('status'=>'fail','data'=>0));exit;
				}
			}
		}else{
			echo json_encode(array('status'=>'fail','data'=>0));exit;
		}
	}
	//添加奖品
	public function add_prize(){
		if(IS_POST && $_POST['id'] != ""){
			$postdata = array();
			$status = true;
			$voteimg_prize = M('voteimg_prize');
			foreach((array)$_POST['add']['id'] as $key=>$val){
				if($_POST['add']['prizegrade'][$key] != "" && $_POST['add']['prizename'][$key] != "" && $_POST['add']['prizepic'][$key] != "" && $_POST['add']['prizenum'][$key] != ""){
						$postdata[$key]['prizegrade'] = trim($_POST['add']['prizegrade'][$key]);
						$postdata[$key]['prizename'] = trim($_POST['add']['prizename'][$key]);
						$postdata[$key]['prizesponsor'] = trim($_POST['add']['prizesponsor'][$key]);
						$postdata[$key]['sponsorurl'] = trim($_POST['add']['sponsorurl'][$key]);
						$postdata[$key]['prizepic'] = trim($_POST['add']['prizepic'][$key]);
						$postdata[$key]['prizenum'] = intval($_POST['add']['prizenum'][$key]);
						if(!preg_match('/^[0-9]*[1-9][0-9]*$/', $_POST['add']['prizenum'][$key]) && $_POST['add']['prizenum'][$key] != ""){
							echo json_encode(array('status'=>'fail','data'=>'奖品数量请输入大于0的整数'));exit;
						}
						if($_POST['add']['sponsorurl'][$key] != "" && strpos($_POST['add']['sponsorurl'][$key], 'http') === false){
							echo json_encode(array('status'=>'fail','data'=>'赞助商链接输入错误'));exit;
						}
						if($val == 0){//添加
							$postdata[$key]['vote_id'] = (int)$_POST['id'];
							$postdata[$key]['token'] = $_POST['token'];
							$add = $voteimg_prize->add($postdata[$key]);
								if(!$add){ $status = false;}
							}else{//修改
								$update = $voteimg_prize->where(array('id'=>$val))->save($postdata[$key]);
								if($update !== false){ S($_POST['token'].'_'.$_POST['id'].'_prize',null);}
							}
						}elseif($_POST['add']['prizegrade'][$key] == "" && $_POST['add']['prizename'][$key] == "" && $_POST['add']['prizepic'][$key] == "" && $_POST['add']['prizenum'][$key] == ""){
							continue;
						}elseif($_POST['add']['prizegrade'][$key] == "" || $_POST['add']['prizename'][$key] == "" || $_POST['add']['prizepic'][$key] == "" || $_POST['add']['prizenum'][$key] == ""){
								echo json_encode(array('status'=>'fail','data'=>'如果某行被填写,则整行中带红色星号的字段必填'));exit;
						}
			}
			if($status){
				S($_POST['token'].'_'.$_POST['id'].'_prize',null);
				echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
			}else{
				echo json_encode(array('status'=>'fail','data'=>'奖品设置失败'));exit;
			}
		}
	}
	//赞助商设置
	public function add_sponor(){
		if(IS_POST && $_POST['id'] != ""){
			$postdata = array();
			$status = true;
			$voteimg_sponor = M('voteimg_sponor');
			foreach((array)$_POST['sp']['id'] as $key=>$val){
				if($_POST['sp']['sponsorname'][$key] != "" && $_POST['sp']['sponsorurl'][$key] != "" && $_POST['sp']['sponsorpic'][$key]!= ""){
						$postdata[$key]['sponsorname'] = trim($_POST['sp']['sponsorname'][$key]);
						$postdata[$key]['sponsorurl'] = trim($_POST['sp']['sponsorurl'][$key]);
						$postdata[$key]['sponsorpic'] = trim($_POST['sp']['sponsorpic'][$key]);
						if(strpos($_POST['sp']['sponsorurl'][$key], 'http') === false){
							echo json_encode(array('status'=>'fail','data'=>'赞助商链接输入错误'));exit;
						}
						if($val == 0){//添加
							$postdata[$key]['vote_id'] = (int)$_POST['id'];
							$postdata[$key]['token'] = $_POST['token'];
							$add = $voteimg_sponor->add($postdata[$key]);
							if(!$add){ $status = false;}
						}else{//修改
							$update = $voteimg_sponor->where(array('id'=>$val))->save($postdata[$key]);
							if($update !== false){ S($_POST['token'].'_'.$_POST['id'].'_sponor',null);}
						}
				}elseif($_POST['sp']['sponsorname'][$key] == "" && $_POST['sp']['sponsorurl'][$key] == "" && $_POST['sp']['sponsorpic'][$key]== ""){
						continue;
				}elseif($_POST['sp']['sponsorname'][$key] == "" || $_POST['sp']['sponsorurl'][$key] == "" || $_POST['sp']['sponsorpic'][$key]== ""){
					echo json_encode(array('status'=>'fail','data'=>'整行值可以同时为空,但整行的某个值不为空则整行的每个值都不能为空'));exit;
				}
			}
			if($status){
				S($_POST['token'].'_'.$_POST['id'].'_sponor',null);
				echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
			}else{
				echo json_encode(array('status'=>'fail','data'=>'赞助商设置失败'));exit;
			}
		}
	}
	//模板设置
	public function set_skin(){
		if(IS_POST && $_POST['id'] != ""){
			$page_type = trim($_POST['page_type']);
			$default_skin = (int)$_POST['default_skin'];
			$update = M('voteimg')->where(array('id'=>(int)$_POST['id']))->save(array('page_type'=>$page_type,'default_skin'=>$default_skin));
			if($update !== false){
				echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
			}else{
				echo json_encode(array('status'=>'fail','data'=>'模板设置失败'));exit;
			}
		}else{
			echo json_encode(array('status'=>'fail','data'=>'模板设置失败'));exit;
		}
	}
	//横幅统计栏设置
	public function set_bannerstat(){
		if(IS_POST && $_POST['id'] != "" && $_POST['token'] != ""){
			$stat = true;
			foreach ($_POST['banner']['id'] as $key => $value) {
				if($_POST['banner']['img_url'][$key] != ""){
					if(strpos($_POST['banner']['img_url'][$key], 'http') === false){
						echo json_encode(array('status'=>'fail','data'=>'横幅图片地址错误'));exit;
					}
					if($value == 0){
						$data = array();
						$data['img_url'] = trim($_POST['banner']['img_url'][$key]);
						$data['external_links'] = trim($_POST['banner']['external_links'][$key]);
						$data['vote_id'] = (int)$_POST['id'];
						$data['token'] = $_POST['token'];
						$addbanner = M('voteimg_banner')->add($data);
						if(!$addbanner){ $stat = false;break;}//添加失败退出循环
					}else{
						$setbanner = M('voteimg_banner')->where(array('id'=>(int)$value))->save(array('img_url'=>trim($_POST['banner']['img_url'][$key]),'external_links'=>trim($_POST['banner']['external_links'][$key])));
						if($setbanner === false){ $stat = false;break;}
					}
				}
			}
			//横幅设置成功
			if($stat){
				S($_POST['token'].'_'.$_POST['id'].'_banner',null);
				$_POST['stat_name'][0]  = (trim($_POST['stat_name'][0]) != "") ? trim($_POST['stat_name'][0]) : '统计参与者';
				$_POST['stat_name'][1]  = (trim($_POST['stat_name'][1]) != "") ? trim($_POST['stat_name'][1]) : '统计投票数';
				$_POST['stat_name'][2]  = (trim($_POST['stat_name'][2]) != "") ? trim($_POST['stat_name'][2]) : '统计访问量';
				$statarray = array();
				$statarray['stat_name'] = implode(",",$_POST['stat_name']);
				$statarray['hide'] = $this->_post('stat_hide','intval');
				$statarray['count'] = $this->_post('nub','intval');
				$updatestat = M('voteimg_stat')->where(array('vote_id'=>$_POST['id'],'token'=>$_POST['token']))->save($statarray);
				if($updatestat !== false){
					S($_POST['token'].'_'.$_POST['id'].'_stat',null);
				}else{
					$stat = false;
				}
			}
			if($stat){
				echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
			}else{
				echo json_encode(array('status'=>'fail','data'=>'横幅统计栏设置失败'));exit;
			}
		}
	}
	public function default_menu(){
		$vote_id = (int)$_GET['vote_id'];
		$menulist = M('voteimg_menus')->where(array('vote_id'=>$vote_id,'type'=>2))->select();
		if($menulist){
			echo json_encode(array('status'=>'done','data'=>$menulist));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>''));exit;
		}
	}
	//菜单设置
	public function set_menu(){
		if(IS_POST && $_POST['id'] != ""){
			$postdata = array();
			$status = true;
			$voteimg_menus = M('voteimg_menus');
			foreach((array)$_POST['menu']['id'] as $key=>$val){
				if($_POST['menu']['menu_name'][$key] != "" && $_POST['menu']['menu_icon'][$key] != ""){
					$postdata[$key]['menu_name'] = trim($_POST['menu']['menu_name'][$key]);
					$postdata[$key]['menu_icon'] = trim($_POST['menu']['menu_icon'][$key]);
					$postdata[$key]['menu_link'] = trim($_POST['menu']['menu_link'][$key]);
					$postdata[$key]['hide'] = (int)$_POST['menu']['hide'][$key];
					if($val == 0){//添加
						$postdata[$key]['vote_id'] = (int)$_POST['id'];
						$postdata[$key]['token'] = $_POST['token'];
						$postdata[$key]['type'] = 1;
						$add = $voteimg_menus->add($postdata[$key]);
						if(!$add){ $status = false;}
					}else{//修改
						$update = $voteimg_menus->where(array('id'=>$val))->save($postdata[$key]);
						if($update !== false){
							S($_POST['token'].'_'.$_POST['id'].'_menu',null);
						}
					}
				}elseif($_POST['menu']['menu_name'][$key] == "" && $_POST['menu']['menu_icon'][$key] == ""){
					continue;
				}elseif($_POST['menu']['menu_name'][$key] == "" || $_POST['menu']['menu_icon'][$key] == ""){
					echo json_encode(array('status'=>'fail','data'=>'菜单名称和图标可以同时为空,但一旦有一个不为空另一个也不能为空'));exit;
				}
			}
			if($status){
				S($_POST['token'].'_'.$_POST['id'].'_menu',null);
				echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
			}else{
				echo json_encode(array('status'=>'fail','data'=>'菜单设置失败'));exit;
			}
		}
	}
	public function default_bottom(){
		$vote_id = (int)$_GET['vote_id'];
		$bottomlist = M('voteimg_bottom')->where(array('vote_id'=>$vote_id,'type'=>2))->select();
		if($bottomlist){
			echo json_encode(array('status'=>'done','data'=>$bottomlist));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>''));exit;
		}
	}
	//底部导航设置
	public function set_bottom(){
		if(IS_POST && $_POST['id'] != ""){
			$postdata = array();
			$status = true;
			$voteimg_bottom = M('voteimg_bottom');
			foreach((array)$_POST['bottom']['id'] as $key=>$val){
				if($_POST['bottom']['bottom_name'][$key] != "" && $_POST['bottom']['bottom_icon'][$key] != ""){
					$postdata[$key]['bottom_name'] = trim($_POST['bottom']['bottom_name'][$key]);
					if(in_array($key, array(0,1,2,3))){
						$postdata[$key]['bottom_icon'] = str_replace($this->siteUrl,'',trim($_POST['bottom']['bottom_icon'][$key]));
					}else{
						$postdata[$key]['bottom_icon'] = trim($_POST['bottom']['bottom_icon'][$key]);
					}
					$postdata[$key]['bottom_link'] = trim($_POST['bottom']['bottom_link'][$key]);
					$postdata[$key]['bottom_rank'] = (int)$_POST['bottom']['bottom_rank'][$key];
					$postdata[$key]['hide'] = (int)$_POST['bottom']['hide'][$key];
					if($val == 0){//添加
						$postdata[$key]['vote_id'] = (int)$_POST['id'];
						$postdata[$key]['token'] = $_POST['token'];
						$postdata[$key]['type'] = 1;
						$add = $voteimg_bottom->add($postdata[$key]);
						if(!$add){
							$status = false;
						}
					}else{//修改
						$update = $voteimg_bottom->where(array('id'=>$val))->save($postdata[$key]);
						if($update !== false){
							S($_POST['token'].'_'.$_POST['id'].'_bottom',null);
						}
					}
				}elseif($_POST['bottom']['bottom_name'][$key] == "" && $_POST['bottom']['bottom_icon'][$key] == ""){
					continue;
				}elseif($_POST['bottom']['bottom_name'][$key] == "" || $_POST['bottom']['bottom_icon'][$key] == "" ){
					echo json_encode(array('status'=>'fail','data'=>'导航名称和图标可以同时为空,但一旦有一个不为空另一个也不能为空'));exit;
				}
			}
			if($status){
				S($_POST['token'].'_'.$_POST['id'].'_bottom',null);
				echo json_encode(array('status'=>'done','data'=>(int)$_POST['id']));exit;
			}else{
				echo json_encode(array('status'=>'fail','data'=>'菜单设置失败'));exit;
			}
		}
	}
	//投票选项列表
	public function item_list(){
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		if(empty($vote_id) || empty($token)){
			$this->error('非法操作');exit;
		}
		$where = array('vote_id'=>$vote_id,'token'=>$token,'check_pass'=>1);
		$total = M('voteimg_item')->where($where)->count();
		$Page = new Page($total,10);
		$list = M('voteimg_item')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('baby_id desc')->select();
		foreach($list as $key=>$val){
			if(strpos($val['vote_img'],';') !== false){
				$vote_img = explode(";",$val['vote_img']);
				$list[$key]['vote_img'] = end($vote_img);
			}else{
				$list[$key]['vote_img'] = $val['vote_img'];
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->assign('vote_id',$vote_id);
		$this->assign('token',$token);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();	
	}
	//添加编辑投票选项
	public function add_item(){
		if(IS_POST){
			if(empty($_POST['vote_title'])){
				$this->error('选项标题不能为空');exit;
			}
			if(mb_strlen($_POST['vote_title'],'utf8') > 8){
				$this->error('选项标题不超过8个汉字');exit;
			}
			if(empty($_POST['vote_img'])){
				$this->error('图片地址不能为空');exit;
			}
			if(empty($_POST['manifesto'])){
				$this->error('拉票宣言不能为空');exit;
			}
			if($_POST['vote_count'] != ""){
				if(!preg_match('/^[0-9]+[0-9]*]*$/',$_POST['vote_count'])){
					$this->error('票数请输入整数');exit;
				}
			}
			if($_POST['contact'] != ""){
				if(!preg_match('/^([0-9]){6,}$/',$_POST['contact'])){
					$this->error('手机号格式不正确');exit;
				}
			}
			$preg = '/<iframe.*?src="(.*?)".*?\><\/iframe\>/';
			if($_POST['video_path'] != ""){
				if(!preg_match($preg, html_entity_decode(html_entity_decode($_POST['video_path'])),$match)){
					$this->error('通用地址格式不正确');exit;
				}else{
					$video_path = $match[1];
					if(strpos($_POST['video_path'], 'http') !== false){
						if(strpos($_POST['video_path'], 'youku.com') === false && strpos($_POST['video_path'], 'qq.com') === false && strpos($_POST['video_path'], 'tudou.com') === false){
							$this->error('视频地址目前仅支持优酷、腾讯、土豆三个门户地址');exit;
						}
					}else{
						$this->error('视频格式不正确');exit;
					}
				}
			}
			$vote_id = $this->_post('vote_id','intval');
			$token = $this->_POST('token','trim');
			//$baby_id =  M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'check_pass'=>1))->count();
			$baby_id =  M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'check_pass'=>1))->max('baby_id');
			$img_data = array();
			$img_data['vote_id'] = $vote_id;
			$img_data['upload_time'] = time();
			$img_data['token'] = $token;
			$img_data['check_pass'] = 1;
			$img_data['upload_type'] = 1;
			$img_data['vote_title'] = trim($_POST['vote_title']);
			$img_data['introduction'] = str_replace('&nbsp;','',trim($_POST['introduction']));
			//$img_data['introduction'] = trim(filter_var($_POST['introduction'],FILTER_SANITIZE_STRING));
			$img_data['manifesto'] = trim($_POST['manifesto']);
			$img_data['vote_count'] = (int)$_POST['vote_count'];
			$img_data['vote_img'] = trim($_POST['vote_img']);
			$img_data['jump_url'] = trim($_POST['jump_url']);
			$img_data['contact'] = trim($_POST['contact']);
			$img_data['baby_id'] = (int)$baby_id+1;
			$img_data['video_path'] = htmlspecialchars_decode($_POST['video_path']);
			if($_FILES['upload_voice']['error'] != 4){
				$upload = $this->localupload();
				if($upload['status'] == 'success'){
					$img_data['upload_voice'] = $upload['msg'];
				}else{
					$this->error($upload['msg']);exit;
				}
			}
			$result = M('voteimg_item')->add($img_data);
			if($result){
				$this->save_attach($result,$_POST);
				$this->success('投票选项添加成功',U('Voteimg/item_list',array('vote_id'=>$vote_id,'token'=>$token)));exit;
			}else{
				$this->error('投票选项添加失败');exit;
			}
		}
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$html_layout = $this->html_layout($token,$vote_id,0);
		$this->assign('verify', $html_layout['verify']);
		$this->assign('formData', $html_layout['string']);
		$apply_fields_name = M('voteimg')->where(array('id'=>(int)$vote_id))->getField('apply_fields_name');
		$this->assign('apply_fields_name', json_decode($apply_fields_name,true));
		$this->assign('vote_id',$vote_id);
		$this->assign('token',$token);
		$this->display();
	}
	//投票选项修改
	public function edit_item(){
		if(IS_POST){
			if(empty($_POST['id'])){
				$this->error('非法操作');exit;
			}
			if(empty($_POST['vote_title'])){
				$this->error('选项标题不能为空');exit;
			}
			if(mb_strlen($_POST['vote_title'],'utf8') > 8){
				$this->error('选项标题不超过8个汉字');exit;
			}
			if(empty($_POST['vote_img'])){
				$this->error('图片地址不能为空');exit;
			}
			if(empty($_POST['manifesto'])){
				$this->error('拉票宣言不能为空');exit;
			}
			if($_POST['vote_count'] != ""){
				if(!preg_match('/^[0-9]+[0-9]*]*$/',$_POST['vote_count'])){
					$this->error('票数请输入整数');exit;
				}
			}
			if($_POST['contact'] != ""){
				if(!preg_match('/^([0-9]){6,}$/',$_POST['contact'])){
					$this->error('手机号格式不正确');exit;
				}
			}
			$preg = '/<iframe.*?src="(.*?)".*?\><\/iframe\>/';
			if($_POST['video_path'] != ""){
				if(!preg_match($preg, html_entity_decode(html_entity_decode($_POST['video_path'])),$match)){
					$this->error('通用地址格式不正确');exit;
				}else{
					$video_path = $match[1];
					if(strpos($_POST['video_path'], 'http') !== false){
						if(strpos($_POST['video_path'], 'youku.com') === false && strpos($_POST['video_path'], 'qq.com') === false && strpos($_POST['video_path'], 'tudou.com') === false){
							$this->error('视频地址目前仅支持优酷、腾讯、土豆三个门户地址');exit;
						}
					}else{
						$this->error('视频格式不正确');exit;
					}
				}
			}
			$vote_img = array_reverse($_POST['vote_img']);
			$vote_img = implode(";",$vote_img);
			$img_data = array();
			$img_data['vote_title'] = trim($_POST['vote_title']);
			$img_data['introduction'] = str_replace('&nbsp;','',trim($_POST['introduction']));
			$img_data['vote_img'] = $vote_img;
			$img_data['jump_url'] = trim($_POST['jump_url']);
			$img_data['manifesto'] = trim($_POST['manifesto']);
			$img_data['vote_count'] = (int)$_POST['vote_count'];
			$img_data['contact'] = 	$_POST['contact'];
			$img_data['upload_time'] = time();
			$img_data['video_path'] = htmlspecialchars_decode($_POST['video_path']);
			if($_FILES['upload_voice']['error'] != 4){
				$upload = $this->localupload();
				if($upload['status'] == 'success'){
					$img_data['upload_voice'] = $upload['msg'];
				}else{
					$this->error($upload['msg']);exit;
				}
			}
			$update = M('voteimg_item')->where(array('id'=>(int)$_POST['id']))->save($img_data);
			if($update){
				$this->save_attach($_POST['id'],$_POST);
				if(!empty($_POST['upload_type']) && $_POST['upload_type'] == 'phone'){
					$this->success('报名选项修改成功',U('Voteimg/apply_list',array('vote_id'=>$_POST['vote_id'],'token'=>$_POST['token'])));exit;
				}else{
					$this->success('投票选项修改成功',U('Voteimg/item_list',array('vote_id'=>$_POST['vote_id'],'token'=>$_POST['token'])));exit;
				}
			}else{
				$this->error('投票选项修改失败');exit;
			}
		}else{
			$id = $this->_get('id','intval');
			$token = $this->_get('token','trim');
			$item = M('voteimg_item')->where(array('id'=>$id,'token'=>$token))->find();
			$vote_img = explode(";",$item['vote_img']);
			$vote_img = array_reverse($vote_img);
			if(empty($item)){
				$this->error('未找到要修改的选项');exit;
			}else{
				$this->assign('vote_imgs',$vote_img);
				$this->assign("set",$item);
				$this->assign("id",$id);
				$this->assign("token",$token);
				$html_layout = $this->html_layout($token,$item['vote_id'],$id);
				$this->assign('verify', $html_layout['verify']);
				$this->assign('formData', $html_layout['string']);
				$apply_fields_name = M('voteimg')->where(array('id'=>(int)$item['vote_id']))->getField('apply_fields_name');
				$this->assign('apply_fields_name', json_decode($apply_fields_name,true));

			}
			$this->display();
		}
	}
	//删除选项
	public function del_item(){
		$id = $this->_get('id','intval');
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$voteimg_item = M('voteimg_item')->where(array('id'=>$id))->find();
		if($voteimg_item){
			M('voteimg_item')->where(array('id'=>$id))->delete();
			//删除扩张字段
			M('voteimg_item_attach')->where(array('item_id'=>$id))->delete();
			//将前面的编号减1
			//M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'baby_id'=>array('gt',$voteimg_item['baby_id'])))->save(array('baby_id'=>array('exp','baby_id-1')));
			$this->success('删除成功',U('Voteimg/item_list',array('token'=>$voteimg_item['token'],'vote_id'=>$voteimg_item['vote_id'])));
			exit;
		}else{
			$this->error('非法操作');
			exit;
		}
	}
	//删除活动
	public function action_del(){
		$id = $this->_get('id','intval');
		$token = $this->_get('token','trim');
		$where = array('id'=>$id,'token'=>$token);
		$voteimg = M('voteimg')->where($where)->find();
		if($voteimg){
			M('voteimg')->where(array('id'=>$id))->delete();
			$this->handleKeyword(intval($id),'Voteimg','','',1);
			$this->success('删除成功',U('Voteimg/index',array('token'=>$token)));
			exit;
		}else{
			$this->error('非法操作');
			exit;
		}
	}  
	//清空投票记录---old
	public function clear_votelog_old(){
		$id = (int)$this->_get('id');
		$vote_id = (int)$this->_get('vote_id');
		$token = $this->_get('token','trim');
		$where = array('user_id'=>$id,'vote_id'=>$vote_id,'token'=>$token);
		$voteimg_users = M('voteimg_users')->where($where)->find();
		if(!empty($voteimg_users) && !empty($voteimg_users['wecha_id'])){
			$delete = M('voteimg_users')->where($where)->delete();
			if($delete){
				S($token.'_'.$vote_id.'_'.$voteimg_users['wecha_id'].'_voter',null);
				$this->success('删除成功');exit;
			}else{
				$this->error('删除失败');
			}
		}else{
			$this->error('没有找到删除项');exit;
		}
	}
	//清空投票记录--new
	public function clear_votelog(){
		$id = (int)$this->_get('id');
		$vote_id = (int)$this->_get('vote_id');
		$token = $this->_get('token','trim');
		$where = array('user_id'=>$id,'vote_id'=>$vote_id,'token'=>$token);
		$voteimg_users = M('voteimg_users')->where($where)->find();
		if(!empty($voteimg_users) && !empty($voteimg_users['wecha_id'])){
			$delete_voter = M('voteimg_users')->where($where)->delete();
			$delete_record = M('voteimg_record')->where(array('user_id'=>$voteimg_users['user_id']));
			if($delete_voter && $delete_record){
				$this->success('删除成功');exit;
			}else{
				$this->error('删除失败');
			}
		}else{
			$this->error('没有找到删除项');exit;
		}
	}
	
	//清空宝宝获得票数
	public function clear_votecount(){
		$id = (int)$this->_get('id');
		$vote_id = (int)$this->_get('vote_id');
		$token = $this->_get('token','trim');
		$where = array('id'=>$id,'vote_id'=>$vote_id,'token'=>$token);
		$voteimg_item = M('voteimg_item')->where($where)->find();
		if(!empty($voteimg_item)){
			M('voteimg_item')->where($where)->save(array('vote_count'=>0));
			$this->success('清空成功',U('Voteimg/vote_log',array('vote_id'=>$vote_id,'token'=>$token,'type'=>'baobao')));
			exit;
		}else{
			$this->error('非法操作');
			exit;
		}
	}
	
	//投票记录
	public function vote_log(){
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$type = $this->_get('type','trim');
		if(empty($vote_id) && empty($token)){
			$this->error('非法操作');exit;
		}
		C('TOKEN_ON',false);
		$key_word = $this->_get('key_word');
		if($type == 'voter' || $type == ''){
			$this->clear_vote_day($vote_id,$token);
			$where = array('vote_id'=>$vote_id,'token'=>$token,'votenum'=>array('gt',0));
			$like_sql = '';
			if(!empty($key_word)){
				$where['nick_name'] = array('like',"%$key_word%");
				$like_sql = " AND u.nick_name like '%".$key_word."%' ";
			}
			$total = M('voteimg_users')->where($where)->count();
			$page = new Page($total,20);
			$sql = "select u.user_id,u.vote_id,u.token,u.item_id,u.nick_name,u.phone,u.wecha_id,u.votenum,u.votenum_day,u.vote_time,v.action_name from ".C('DB_PREFIX')."voteimg_users as u,".C('DB_PREFIX')."voteimg as v where u.token = '{$token}' AND u.vote_id = {$vote_id} AND v.id = u.vote_id and u.votenum > 0 ".$like_sql."order by vote_time desc limit ".$page->firstRow.','.$page->listRows;
			$vote_logs = M('voteimg_users')->query($sql);
			$this->assign('page',$page->show());
		}elseif($type == 'baobao'){
			$where = array('vote_id'=>$vote_id,'token'=>$token);
			if(!empty($key_word)){
				if(is_numeric($key_word)){
					$where['baby_id'] = (int)$key_word;
				}else{
					$where['vote_title'] = array('like',"%$key_word%");
				}
			}
			$total = M('voteimg_item')->where($where)->count();
			$page = new Page($total,20);
			$vote_logs = M('voteimg_item')->where($where)->order('vote_count desc')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($vote_logs as $key=>$val){
				$vote_img = explode(";",$val['vote_img']);
				$vote_logs[$key]['vote_img'] = end($vote_img);
			}
			$this->assign('page',$page->show());
		}
		$this->assign('vote_id',$vote_id);
		$this->assign('token',$token);
		$this->assign('type',$type);
		$this->assign('vote_logs',$vote_logs);
		$this->assign('key_word',$key_word);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();
	}
	//粉丝充值记录
	public function fansPayLog(){
		$vote_id = (int)$_REQUEST['vote_id'];
		$p = $_REQUEST['p'] ? $_REQUEST['p'] : 0;
		$key_word = $_REQUEST['key_word'];
		$where = '';
		if($key_word != ""){
			$where = ' and nick_name like "%'.$key_word.'%" ';
		}
		$sql = "select ".C('DB_PREFIX')."voteimg_users.nick_name,".C('DB_PREFIX')."voteimg_users.phone,".C('DB_PREFIX')."voteimg_users.isrefund,".C('DB_PREFIX')."voteimg_confighb.* from ".C('DB_PREFIX')."voteimg_confighb left join ".C('DB_PREFIX')."voteimg_users on ".C('DB_PREFIX')."voteimg_confighb.user_id = ".C('DB_PREFIX')."voteimg_users.user_id where ".C('DB_PREFIX')."voteimg_confighb.vote_id = {$vote_id} and ".C('DB_PREFIX')."voteimg_confighb.token = '{$this->token}' and ".C('DB_PREFIX')."voteimg_confighb.paystatus = 1 ".$where."group by ".C('DB_PREFIX')."voteimg_confighb.user_id order by ".C('DB_PREFIX')."voteimg_confighb.hb_id desc limit ".($p*10).",10";
		$where = array('vote_id'=>$vote_id,'token'=>$this->token,'paystatus'=>1);
		$total = M('voteimg_confighb')->where($where)->count();
		$Page = new Page($total,10);
		$bookList = M('voteimg_book')->query($sql);
		$all_money = M('voteimg_confighb')->where(array('vote_id'=>$vote_id,'token'=>$this->token))->sum('total_money');
		$all_money = $all_money ? $all_money : '0.00';
		$this->assign('page',$Page->show());
		$this->assign('token',$this->token);
		$this->assign('vote_id',$vote_id);
		$this->assign('booklist',$bookList);
		$this->assign('key_word',$key_word);
		$this->assign('all_money',$all_money);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();
	}
	//充值详细记录
	public function pay_details(){
		$vote_id = (int)$_GET['vote_id'];
		$token = $_GET['token'];
		$wecha_id = $_GET['wecha_id'];
		$where = array('vote_id'=>$vote_id,'token'=>$token,'wecha_id'=>$wecha_id,'paystatus'=>1);
		$total = M('voteimg_book')->where($where)->count();
		$Page = new Page($total,10);
		$pay_details = M('voteimg_book')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$Page->show());
		$this->assign('firstRow',$Page->firstRow);
		$this->assign('token',$this->token);
		$this->assign('pay_details',$pay_details);
		$this->display();
	}
	//粉丝退款记录
	public function refundlog(){
		if($_REQUEST['key_word'] != ""){
			$wecha_id = M('userinfo')->where(array('token'=>$this->token,'wechaname'=>array('like','%'.$_REQUEST['key_word'].'%')))->getField('wecha_id');
			if($wecha_id){
				$where = ' AND wecha_id = "'.$wecha_id.'"';
			}else{
				$where = ' AND wecha_id = ""';
			}
		}
		$vote_id = (int)$_REQUEST['vote_id'];
		$p = $_REQUEST['p'] ? $_REQUEST['p'] : 0;
		$sql = "select sum(money) as m,".C('DB_PREFIX')."voteimg_hbinfo.* from ".C('DB_PREFIX')."voteimg_hbinfo where ".C('DB_PREFIX')."voteimg_hbinfo.vote_id = {$vote_id} and ".C('DB_PREFIX')."voteimg_hbinfo.token = '{$this->token}' ".$where."group by ".C('DB_PREFIX')."voteimg_hbinfo.user_id order by refund_time desc limit ".($p*10).",10";
		$refundlog = M('voteimg_hbinfo')->query($sql);
		foreach ($refundlog as $key => $value) {
			 $refundlog[$key]['nick_name'] = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$value['wecha_id']))->getField('wechaname');
		}
		$total =count($refundlog);//因为退款记录没几条，直接使用count();
		$Page = new Page($total,10);
		$this->assign('page',$Page->show());
		$this->assign('token',$this->token);
		$this->assign('vote_id',$vote_id);
		$this->assign('refundlog',$refundlog);
		$this->assign('key_word',$_REQUEST['key_word']);
		$this->display();
	}
	//banner管理
	public function banner_manage(){
		if(IS_POST && !empty($_POST['vote_id']) && !empty($_POST['token'])){
			$post = array();
			$status = true;
			$banner_db = M('voteimg_banner');
			foreach((array)$_POST['add']['id'] as $key=>$val){
				if($_POST['add']['img_url'][$key] != ''){
					$post[$key]['img_url'] = $_POST['add']['img_url'][$key];
					$post[$key]['external_links'] = $_POST['add']['external_links'][$key];
					$post[$key]['banner_rank'] = (int)$_POST['add']['banner_rank'][$key];
					if($val == 0){//添加
						$post[$key]['vote_id'] = $_POST['vote_id'];
						$post[$key]['token'] = $_POST['token'];
						$add = $banner_db->add($post[$key]);
						if(!$add){
							$status = false;
						}
					}else{//修改
						$update = $banner_db->where(array('id'=>$val))->save($post[$key]);
						if($update){
							S($_POST['token'].'_'.$_POST['vote_id'].'_banner',null);
						}
					}
				}
			}
			if($status){
				S($_POST['token'].'_'.$_POST['vote_id'].'_banner',null);
				$this->success('上传成功',U('Voteimg/banner_manage',array('vote_id'=>$_POST['vote_id'],'token'=>$_POST['token'])));exit;
			}else{
				$this->error('上传失败');exit;
			}
		}
		if($_GET['vote_id'] && $_GET['token']){
			$banner_list = M('voteimg_banner')->where(array('vote_id'=>$_GET['vote_id'],'token'=>$_GET['token']))->select();
			$this->assign('banner_list',$banner_list);
		}
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();
	}
	//删除banner
	public function del_banner(){
		$banner_id = $this->_get('id','intval');
		if(!$banner_id){
			echo json_encode(array('status'=>'fail','data'=>'横幅id不能为空'));exit;
		}
		$exists = M('voteimg_banner')->where(array('id'=>$banner_id))->find();
		if(empty($exists)){
			echo json_encode(array('status'=>'fail','data'=>'删除的横幅未找到'));exit;
		}
		$del = M('voteimg_banner')->where(array('id'=>$banner_id))->delete();
		if($del){
			S($exists['token'].'_'.$exists['vote_id'].'_banner',null);
			echo json_encode(array('status'=>'done','data'=>''));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>'删除失败'));exit;
		}
	}
	//统计栏
	public function stat_list(){
		if(IS_POST){
			$data = array();
			$data['stat_name'] = implode(",",$_POST['stat_name']);
			$data['hide'] = $this->_post('stat_hide','intval');
			$data['count'] = $this->_post('nub','intval');
			if(empty($_POST['vote_id']) || empty($_POST['token'])){
				$this->error('参数错误,修改失败');exit;
			}
			$update = M('voteimg_stat')->where(array('vote_id'=>$_POST['vote_id'],'token'=>$_POST['token']))->save($data);
			if($update){
				S($_POST['token'].'_'.$_POST['vote_id'].'_stat',null);
				$this->success('修改成功',U('Voteimg/stat_list',array('vote_id'=>$_POST['vote_id'],'token'=>$_POST['token'])));exit;
			}else{
				$this->error('修改失败');exit;
			}
		}
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		if(!$vote_id && !$token){
			$this->error('非法操作');exit;
		}
		$info = M('voteimg_stat')->where(array('vote_id'=>$vote_id,'token'=>$token))->field('stat_name,hide,count')->find();
		$split = explode(",",$info['stat_name']);
		$this->assign('stat_name',$split);
		$this->assign('hide',$info['hide']);
		$this->assign('count',$info['count']);
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		$this->assign('list',$list);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();
	}
	//统计栏管理
	public function stat_manage(){
		if(IS_POST){
			$data = array();
			$data['stat_name'] = $this->_post('stat_name','trim');
			$data['stat_rank'] = $this->_post('stat_rank','intval');
			$data['hide'] = $this->_post('hide','intval');
			if($_POST['id'] && $_POST['token']){
				$exists = M('voteimg_stat')->where(array('id'=>$_POST['id'],'token'=>$_POST['token']))->find();
				if(!empty($exists)){
					$update = M('voteimg_stat')->where(array('id'=>$_POST['id'],'token'=>$_POST['token']))->save($data);
					if($update){
						S($_POST['token'].'_'.$exists['vote_id'].'_stat',null);
						$this->success('修改成功',U('Voteimg/stat_list',array('vote_id'=>$exists['vote_id'],'token'=>$_POST['token'])));exit;
					}else{
						$this->error('修改失败');exit;
					}
				}
			}
		}
		if($_GET['id'] && $_GET['token']){
			$stat = M('voteimg_stat')->where(array('id'=>(int)$_GET['id'],'token'=>$_GET['token']))->find();
			if($stat){
				$this->assign('stat',$stat);
			}
		}
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		$this->assign('id',$_GET['id']);
		$this->display();
	}
	//菜单管理
	public function menu_list(){
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		if(!$vote_id && !$token){
			$this->error('非法操作');exit;
		}
		$list = M('voteimg_menus')->where(array('vote_id'=>$vote_id,'token'=>$token))->select();
		$this->assign('list',$list);
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();
	}
	//添加修改菜单
	public function menu_add(){
		if(IS_POST){
			$data = array();
			$data['menu_name'] = $this->_post('menu_name','trim');
			$data['menu_icon'] = $this->_post('menu_icon','trim');
			$data['menu_link'] = $this->_post('menu_link','trim');
			$data['hide'] = $this->_post('hide','intval');
			$data['vote_id'] = $this->_post('vote_id','intval');
			$data['token'] = $this->_post('token','trim');
			if(empty($data['vote_id']) || empty($data['token'])){
				$this->error('参数错误,修改失败');exit;
			}
			if(empty($_POST['id'])){//添加
				$num = M('voteimg_menus')->where(array('vote_id'=>$data['vote_id'],'token'=>$data['token'],'type'=>1))->count();
				if($num == 4){
					$this->error('最多添加4个自定义菜单');exit;
				}
				$data['type'] = 1;
				$insert = M('voteimg_menus')->add($data);
				if($insert){
					S($data['token'].'_'.$data['vote_id'].'_menu',null);
					$this->success('添加成功',U('Voteimg/menu_list',array('vote_id'=>$data['vote_id'],'token'=>$data['token'])));exit;
				}else{
					$this->error('添加失败');exit;
				}
			}else{//修改
				$where = array('id'=>(int)$_POST['id']);
				$update = M('voteimg_menus')->where($where)->save($data);
				if($update){
					S($data['token'].'_'.$data['vote_id'].'_menu',null);
					$this->success('修改成功',U('Voteimg/menu_list',array('vote_id'=>$data['vote_id'],'token'=>$data['token'])));exit;
				}else{
					$this->error('修改失败');exit;
				}
			}
		}else{
			if(!empty($_GET['id'])){
				$menu = M('voteimg_menus')->where(array('id'=>(int)$_GET['id']))->find();
				$this->assign('set',$menu);
			}
		}
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		$this->display();
	}
	//删除自定义菜单
	public function menu_del(){
		$menu_id = $this->_get('menu_id','intval');
		if(!$menu_id){
			echo json_encode(array('status'=>'fail','data'=>'菜单id不能为空'));exit;
		}
		$exists = M('voteimg_menus')->where(array('id'=>$menu_id))->find();
		if(empty($exists)){
			echo json_encode(array('status'=>'fail','data'=>'删除的菜单未找到'));exit;
		}
		if($exists['type'] == 2){
			echo json_encode(array('status'=>'fail','data'=>'内置菜单不可以删除'));exit;
		}
		$del = M('voteimg_menus')->where(array('id'=>$menu_id))->delete();
		if($del){
			S($exists['token'].'_'.$exists['vote_id'].'_menu',null);
			echo json_encode(array('status'=>'done','data'=>'删除成功'));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>'删除失败'));exit;
		}
	}
	//底部导航展示
	public function bottom_list(){
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		if(!$vote_id && !$token){
			$this->error('非法操作');exit;
		}
		$list = M('voteimg_bottom')->where(array('vote_id'=>$vote_id,'token'=>$token))->select();
		$this->assign('list',$list);
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		//拆分旧数据
		$this->splittable($_GET['vote_id'],$_GET['token']);
		$this->display();
	}
	//底部管理
	public function bottom_add(){
		if(IS_POST){
			$data = array();
			$data['bottom_name'] = $this->_post('bottom_name','trim');
			$data['bottom_link'] = $this->_post('bottom_link','trim');
			$data['bottom_icon'] = $this->_post('bottom_icon','trim');
			$data['bottom_rank'] = $this->_post('bottom_rank','intval');
			$data['hide'] = $this->_post('hide','intval');
			$data['vote_id'] = $this->_post('vote_id','intval');
			$data['token'] = $this->_post('token','trim');
			if(empty($data['vote_id']) || empty($data['token'])){
				$this->error('参数错误,修改失败');exit;
			}
			if(empty($_POST['id'])){//添加
				$num = M('voteimg_bottom')->where(array('vote_id'=>$data['vote_id'],'token'=>$data['token'],'type'=>1))->count();
				if($num == 4){
					$this->error('最多添加4个自定义导航');exit;
				}
				$insert = M('voteimg_bottom')->add($data);
				if($insert){
					S($data['token'].'_'.$data['vote_id'].'_bottom',null);
					$this->success('添加成功',U('Voteimg/bottom_list',array('vote_id'=>$data['vote_id'],'token'=>$data['token'])));exit;
				}else{
					$this->error('添加失败');exit;
				}
			}else{//修改
				$where = array('id'=>(int)$_POST['id']);
				$update = M('voteimg_bottom')->where($where)->save($data);
				if($update){
					S($data['token'].'_'.$data['vote_id'].'_bottom',null);
					$this->success('修改成功',U('Voteimg/bottom_list',array('vote_id'=>$data['vote_id'],'token'=>$data['token'])));exit;
				}else{
					$this->error('修改失败');exit;
				}
			}
		}else{
			if($_GET['id']){
				$bottom = M('voteimg_bottom')->where(array('id'=>$_GET['id']))->find();
				$this->assign('set',$bottom);
			}
		}
		$this->assign('token',$_GET['token']);
		$this->assign('vote_id',$_GET['vote_id']);
		$this->display();
	}
	//底部导航删除
	public function bottom_del(){
		$bottom_id = $this->_get('bottom_id','intval');
		if(!$bottom_id){
			echo json_encode(array('status'=>'fail','data'=>'导航id不能为空'));exit;
		}
		$exists = M('voteimg_bottom')->where(array('id'=>$bottom_id))->find();
		if(empty($exists)){
			echo json_encode(array('status'=>'fail','data'=>'删除的底部导航未找到'));exit;
		}
		if($exists['type'] == 2){
			echo json_encode(array('status'=>'fail','data'=>'内置导航不可以删除'));exit;
		}
		$del = M('voteimg_bottom')->where(array('id'=>$bottom_id))->delete();
		if($del){
			S($exists['token'].'_'.$exists['vote_id'].'_bottom',null);
			echo json_encode(array('status'=>'done','data'=>'删除成功'));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>'删除失败'));exit;
		}
	}
	//删除奖项
	public function prize_del(){
		$prize_id = $this->_get('prize_id','intval');
		if(!$prize_id){
			echo json_encode(array('status'=>'fail','data'=>'奖品id不能为空'));exit;
		}
		$exists = M('voteimg_prize')->where(array('id'=>$prize_id))->find();
		if(empty($exists)){
			echo json_encode(array('status'=>'fail','data'=>'要删除的奖项未找到'));exit;
		}
		$del = M('voteimg_prize')->where(array('id'=>$prize_id))->delete();
		if($del){
			S($exists['token'].'_'.$exists['vote_id'].'_prize',null);
			echo json_encode(array('status'=>'done','data'=>'删除成功'));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>'删除失败'));exit;
		}
	}
	//删除赞助商
	public function sponsor_del(){
		$sponsor_id = $this->_get('sponsor_id','intval');
		if(!$sponsor_id){
			echo json_encode(array('status'=>'fail','data'=>'赞助商id不能为空'));exit;
		}
		$exists = M('voteimg_sponor')->where(array('id'=>$sponsor_id))->find();
		if(empty($exists)){
			echo json_encode(array('status'=>'fail','data'=>'要删除的赞助商未找到'));exit;
		}
		$del = M('voteimg_sponor')->where(array('id'=>$sponsor_id))->delete();
		if($del){
			S($exists['token'].'_'.$exists['vote_id'].'_sponsor',null);
			echo json_encode(array('status'=>'done','data'=>'删除成功'));exit;
		}else{
			echo json_encode(array('status'=>'fail','data'=>'删除失败'));exit;
		}
	}
	//报名管理
	public function apply_list(){
		C('TOKEN_ON',false);
		$key_word = $this->_request('key_word');
		$where = array('vote_id'=>$_REQUEST['vote_id'],'token'=>$_REQUEST['token'],'upload_type'=>0);
		if($key_word != ""){
			$where['vote_title'] =  array('like',"%$key_word%");
		}
		if($_REQUEST['check_pass'] != 'all' && $_REQUEST['check_pass'] != ""){
			$check_pass = $_REQUEST['check_pass'] == 3 ? 0 : $_REQUEST['check_pass'];
			$where['check_pass'] = $check_pass;
		}
		$total = M('voteimg_item')->where($where)->count();
		$Page = new Page($total,10);
		$list = M('voteimg_item')->where($where)->order('upload_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key=>$val){
			$vote_img = explode(";",$val['vote_img']);
			$list[$key]['vote_img'] = end($vote_img);
		}
		$this->assign('list',$list);
		$this->assign('page',$Page->show());
		$this->assign('token',$_REQUEST['token']);
		$this->assign('vote_id',$_REQUEST['vote_id']);
		$this->assign('key_word',$key_word);
		$this->assign('check_pass',$_REQUEST['check_pass']);
		//拆分旧数据
		$this->splittable($_REQUEST['vote_id'],$_REQUEST['token']);
		$this->display();
	}
	
	//报名图片预览
	public function apply_phone_list(){
		$id= $this->_get('id','intval');
		$item = M('voteimg_item')->where(array('id'=>$id))->find();
		$item_img = explode(";",$item['vote_img']);
		$this->assign('item_img',$item_img);
		$this->display();
	}
	//审核通过
	public function apply_check(){
		if($_GET['vote_id'] != "" && $_GET['item_id'] != "" && $_GET['token'] != ""){
			$item = M('voteimg_item')->where(array('id'=>$_GET['item_id']))->find();
			if(empty($item)){
				$this->error('投票选项不存在');
			}
			if($item['check_pass'] ==1 || $item['check_pass'] == 2){
				$this->error('请勿重复审核');
			}
			$status = trim($_GET['status']);
			$data = array();
			$msg = '';
			if($status == 'pass'){
				$max_babyid = M('voteimg_item')->where(array('vote_id'=>$_GET['vote_id'],'token'=>$_GET['token'],'check_pass'=>1))->max('baby_id');
				$data['check_pass'] = 1;
				$data['baby_id'] = (int)$max_babyid + 1;
				$msg = '通过审核';
			}elseif($status == 'refuse'){
				$data['baby_id'] = 0;
				$data['check_pass'] = 2;
				$msg = '不通过审核';
			}else{
				$this->error('非法操作');
			}
			$update = M('voteimg_item')->where(array('id'=>$_GET['item_id']))->save($data);
			if($update){
				$this->success($msg,U('Voteimg/apply_list',array('vote_id'=>$_GET['vote_id'],'token'=>$_GET['token'])));exit;
			}else{
				$this->error('审核失败');
			}
		}else{
			$this->error('非法操作');
		}
	}
	//批量通过
	function batch_pass(){
		$stat = true;
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$ids = $this->_post('ids');
		if(empty($vote_id) || empty($token) || !is_array($ids)){
			exit('fail');
		}
		foreach((array)$ids as $id){
			$item = M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'id'=>$id))->find();
			if($item['check_pass'] == 0){
				//$max_babyid = M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'check_pass'=>1))->count();
				$max_babyid = M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'check_pass'=>1))->max('baby_id');
				$update = M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'id'=>$id))->save(array('check_pass'=>1,'baby_id'=>(int)$max_babyid+1));
				if(!$update){
					$stat = false;
				}
			}
		}
		if($stat){
			exit('done');
		}else{
			exit('fail');
		}
	}
	//批量不通过
	function unbatch_pass(){
		$stat = true;
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$unids = $this->_post('unids');
		if(empty($vote_id) || empty($token) || !is_array($unids)){
			exit('fail');
		}
		foreach((array)$unids as $unid){
			$item = M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'id'=>$unid))->find();
				if($item['check_pass'] == 0){
					//将前面的编号减1
					//M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'baby_id'=>array('gt',$item['baby_id']),'id'=>$unid))->save(array('baby_id'=>array('exp','baby_id-1')));
					$update = M('voteimg_item')->where(array('vote_id'=>$vote_id,'token'=>$token,'id'=>$unid))->save(array('check_pass'=>2,'baby_id'=>0));
					if(!$update){
						$stat = false;
					}
				}
		}
		if($stat){
			exit('done');
		}else{
			exit('fail');
		}
	}
	//删除申请
	public function apply_del(){
		if(!empty($_GET['item_id']) || !empty($_GET['vote_id']) || !empty($_GET['token'])){
			$where = array('id'=>$_GET['item_id']);
			$exists = M('voteimg_item')->where($where)->find();
			if($exists){
				$dalete = M('voteimg_item')->where($where)->limit(1)->delete();
				if($dalete){
					//删除扩张字段
					M('voteimg_item_attach')->where(array('item_id'=>(int)$_GET['item_id']))->delete();
					$this->success('删除成功',U('Voteimg/apply_list',array('vote_id'=>$_GET['vote_id'],'token'=>$_GET['token'])));exit;
				}else{
					$this->error('删除失败');exit;
				}
			}else{
				$this->error('删除失败');exit;
			}
		}else{
			$this->error('非法操作');exit;
		}
	}
	//增加默认栏目统计
	public function add_stat($vote_id = 1){
		if(!$vote_id){
			return false;
		}
		$exists = M('voteimg_stat')->where(array('vote_id'=>$vote_id,'token'=>session('token')))->find();
		if($exists){
			return false;
		}
		$stat_data = array();
		$add = array('参与选手','累计投票','访问量');
		$stat_data['vote_id'] = $vote_id;
		$stat_data['token'] = session('token');
		$stat_data['stat_name'] = implode(",",$add);
		$stat_data['hide'] = 1;
		$stat_data['stat_rank'] = 0;
		$insert = M('voteimg_stat')->add($stat_data);
		if($insert){
			return true;
		}
		return false;
	}
	//增加默认菜单
	public function add_menu($vote_id = 1,$action_data = ''){
		if(!$vote_id){
			return false;
		}
		$exists = M('voteimg_menus')->where(array('vote_id'=>$vote_id,'token'=>session('token')))->find();
		if($exists){
			return false;
		}
		//$date = (!empty($action_data['start_time']) && !empty($action_data['end_time'])) ? date('Y-m-d',$action_data['start_time']).'至'.date('Y-m-d',$action_data['end_time']) : date('Y-m-d',time()).'至'.date('Y-m-d',strtotime("+1 month"));
		$static_img = '/tpl/static/voteimg/img/';
		$menu_data = array(
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'menu_name'=>'投票评选',
				'menu_icon'=>$static_img.'tubiao_01.png',
				'menu_link'=>'',
				'hide'=>1,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'menu_name'=>'活动日期',
				'menu_icon'=>$static_img.'tubiao_02.png',
				'menu_link'=>'',
				'hide'=>1,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'menu_name'=>'活动介绍',
				'menu_icon'=>$static_img.'tubiao_03.png',
				'menu_link'=>'',
				'hide'=>1,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'menu_name'=>'个人中心',
				'menu_icon'=>$static_img.'tubiao_04.png',
				'menu_link'=>'',
				'hide'=>1,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'menu_name'=>'我要报名',
				'menu_icon'=>$static_img.'tubiao_05.png',
				'menu_link'=>'',
				'hide'=>1,
				'type'=>2
			)
		);
		$insert = M('voteimg_menus')->addAll($menu_data);
		if($insert){
			return true;
		}
		return false;
	}
	
	//添加内置底部导航
	public function  bottom_nav($vote_id = 1){
		if(!$vote_id){
			return false;
		}
		$exists = M('voteimg_bottom')->where(array('vote_id'=>$vote_id,'token'=>session('token')))->find();
		if($exists){
			return false;
		}
		$base_url = '/tpl/static/voteimg/img/';
		$bottom_data = array(
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'bottom_name'=>'电话',
				'model'=>'',
				'action'=>'',
				'bottom_icon'=>$base_url.'daohang_01.png',
				'hide'=>1,
				'bottom_rank'=>0,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'bottom_name'=>'搜索',
				'model'=>'',
				'action'=>'',
				'bottom_icon'=>$base_url.'daohang_02.png',
				'bottom_rank'=>1,
				'hide'=>1,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'bottom_name'=>'排行',
				'model'=>'',
				'action'=>'',
				'bottom_icon'=>$base_url.'daohang_03.png',
				'hide'=>1,
				'bottom_rank'=>2,
				'type'=>2
			),
			array(	
				'vote_id'=>$vote_id,
				'token'=>session('token'),
				'bottom_name'=>'拉票',
				'model'=>'',
				'action'=>'',
				'bottom_icon'=>$base_url.'daohang_04.png',
				'hide'=>1,
				'bottom_rank'=>3,
				'type'=>2
			),
		);
		$insert = M('voteimg_bottom')->addAll($bottom_data);
		if($insert){
			return true;
		}
		return false;
	}
	//导出投票者信息
	public function exExcel(){
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$page 	= $_GET['page']?intval($_GET['page']):1;
		$limit = 5000;
		$total = M('voteimg_users')->where(array('token'=>$token,'vote_id'=>$vote_id,'votenum'=>array('gt',0)))->count();
		if($total>$limit){
			 $offset=($page-1)*$limit;
			 $limitstr="{$offset},{$limit}";
			 $needpage=ceil($total/$limit);
		}
		$vote_logs = M('voteimg_users')->where(array('token'=>$token,'vote_id'=>$vote_id,'votenum'=>array('gt',0)))->order('vote_time desc')->limit($limitstr)->select();
		if(!empty($vote_logs)){
			$data = array();
			$data[0] = array('昵称','手机号','已投票数','今日投票数','最后投票时间');
			$i = 1;
			foreach($vote_logs as $key=>$val){
				if($val['nick_name'] != ""){
					$data[$i]['nick_name'] = $val['nick_name'];
					$data[$i]['phone'] = (!empty($val['phone'])) ? $val['phone'] : '---';
					$data[$i]['votenum'] = $val['votenum'];
					$data[$i]['votenum_day'] = $val['votenum_day'];
					$data[$i]['vote_time'] = date('Y-m-d H:i:s',$val['vote_time']);
					$i++;
				}
			}
			if($total>$limit){
			  $filename="voteimg_voter_".$this->token."_".$vote_id."_".date('Ymd')."_{$page}.csv";
			}else{
			  $filename="voteimg_voter_".$this->token."_".$vote_id.".csv";
			}
			$f = $this->writeCsv($data,$filename);
			echo json_encode(array('error_code'=>0, 'p' =>$page,'tt'=>$needpage,'fileurl'=>$f));exit;
		}else{
			echo json_encode(array('error_code'=>1, 'p' =>'','tt'=>0,'fileurl'=>''));exit;
		}
    }
	function writeCsv($data=array(),$filename='report') {
		header("Content-Type: application/vnd.ms-excel; charset=GBK"); 
		 $filename = RUNTIME_PATH.$filename;
		 $fp = fopen($filename, 'w');
		  foreach ($data as $key=>$fields) {
		  	if($key == 0){
		  		foreach ($fields as $k => $v) {
		  			$fields[$k] = iconv("UTF-8", "GBK//IGNORE",$v);
		  		}
		  	}
		  	$fields['nick_name'] = iconv("UTF-8", "GBK//IGNORE",$fields['nick_name']);
		    fputcsv($fp, $fields);
		  }
		 fclose($fp);
		  return $filename;
	}

	public function export_excel_zip(){
          $vote_id = (int)$_POST['vote_id'];
          $token = trim($_POST['token']);
          $page =max(1, (int)$_POST['page']);
		  $zip  = new ZipArchive();
		  $filename="voteimg_voter_".$token."_".$vote_id."_".date('YmdHis').".zip";
		  $zipname= RUNTIME_PATH.$filename;
		  if (!file_exists($zipname)){
			if($zip->open($zipname,ZipArchive::OVERWRITE)){//创建一个空的zip文件
				for($i=1;$i<=$page;$i++){
					$tmp=RUNTIME_PATH."voteimg_voter_".$token."_".$vote_id."_".date('Ymd')."_{$i}.csv";
					if(file_exists($tmp)){
						$zip->addFile($tmp,'csvfile'.$i.'.csv');
					}
					//unlink($tmp);//删除临时文件
				}
				if($zip->close()){
					for($j=1;$j<=$page;$j++){
						unlink(RUNTIME_PATH."voteimg_voter_".$token."_".$vote_id."_".date('Ymd')."_{$j}.csv");//删除临时文件
					}
					echo json_encode(array('error_code'=>0, 'p' =>$page,'fileurl'=>RUNTIME_PATH.$filename));exit;
				}else{
					echo json_encode(array('error_code'=>2, 'p' =>'','fileurl'=>''));exit;
				}
			}
		}else{			
			echo json_encode(array('error_code'=>1, 'p' =>'','fileurl'=>''));exit;
		}
   }
	//导出投票选项信息
	public function exExcel_item(){
		$vote_id = $this->_get('vote_id','intval');
		$token = $this->_get('token','trim');
		$where = array('vote_id'=>$vote_id,'token'=>$token);
		//活动名
		$action_name = M('voteimg')->where($where)->getField('action_name');
		$item = M('voteimg_item')->where($where)->order("vote_count desc")->select();
		if(!empty($item)){
			$export = array();
			foreach($item as $key=>$val){
				if(!empty($val['vote_title']) && !empty($val['baby_id'])){
					$export[$key]['baby_id'] = $val['baby_id'];
					$export[$key]['vote_title'] = $val['vote_title'];
					$export[$key]['contact'] = (!empty($val['contact'])) ? $val['contact'] : '---';
					$export[$key]['vote_count'] = $val['vote_count'];
					$export[$key]['upload_time'] = date('Y-m-d H:i:s',$val['upload_time']);
				}
			}
			$title = array('编号','选项名称','联系方式','获得票数','报名时间');
			$this->exportexcel($export,$title,$action_name.'投票选项统计_'.date('YmdHis'));
		}else{
			$this->error('导出错误,没有获取到要导出的数据');
		}
	}
	
	function exportexcel($data=array(),$title=array(),$filename='report'){
    	header("Content-type:application/octet-stream");
    	header("Accept-Ranges:bytes");
    	header("Content-type:application/vnd.ms-excel");
    	header("Content-Disposition:attachment;filename=".$filename.".xls");
    	header("Pragma: no-cache");
    	header("Expires: 0");
    	//导出xls 开始
    	if (!empty($title)){
    		foreach ($title as $k => $v) {
    			$title[$k]=iconv("UTF-8", "GBK//IGNORE",$v);
    		}
    		$title= implode("\t", $title);
    		echo "$title\n";
    	}
    	if (!empty($data)){
    		foreach($data as $key=>$val){
    			foreach ($val as $ck => $cv) {
    				$data[$key][$ck]=iconv("UTF-8", "GBK//IGNORE", $cv);
    			}
    			
    			$data[$key]=implode("\t", $data[$key]);
    		}
    		echo implode("\n",$data);
    	}
    }
	public function introduction_view(){
		$id = $this->_get('id','intval');
		$manifesto = M('voteimg_item')->where(array('id'=>$id))->getField('manifesto');
		$this->assign('introduction',$manifesto);
		$this->display();
	}
	public function set_reply(){
		$this->display();
	}
	//查看投票详情
	public function vote_details(){
		C('TOKEN_ON',false);
		$user_id = $this->_request('user_id','intval');
		$type_view = $this->_request('type_view','trim');
		$action_name = $this->_request('action_name','trim');
		$nick_name = $this->_request('nick_name','trim');
		$searchword = $this->_request('searchword','trim');
		$token = $this->token;
		$where = array();
		$where[C('DB_PREFIX').'voteimg_record.user_id'] = $user_id;
		$where[C('DB_PREFIX').'voteimg_record.token'] = $token;
		if($searchword != ""){
			if(is_numeric($searchword)){
				$where[C('DB_PREFIX').'voteimg_item.baby_id'] = (int)$searchword;
			}else{
				$where[C('DB_PREFIX').'voteimg_item.vote_title'] = array('like',"%{$searchword}%");
			}
		}
		if($type_view == 'all'){
			$total = M('voteimg_record')->where($where)->join(C('DB_PREFIX').'voteimg_item on '.C('DB_PREFIX').'voteimg_item.id = '.C('DB_PREFIX').'voteimg_record.item_id')->field('baby_id,vote_title,vote_time')->order('vote_time desc')->limit($page->firstRow.','.$page->listRows)->count();
			$page = new Page($total,10);
			$record = M('voteimg_record')->where($where)->join(C('DB_PREFIX').'voteimg_item on '.C('DB_PREFIX').'voteimg_item.id = '.C('DB_PREFIX').'voteimg_record.item_id')->field('baby_id,vote_title,vote_time')->order('vote_time desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
		}elseif($type_view == 'today'){
			$today_time = strtotime(date('Y-m-d 00:00:00',$_SERVER['REQUEST_TIME']));
			$evening_time = strtotime(date('Y-m-d 23:59:59',$_SERVER['REQUEST_TIME']));
			$where['vote_time'] = array('elt',$evening_time);
			$where['vote_time'] = array('egt',$today_time);
			$total = M('voteimg_record')->where($where)->join(C('DB_PREFIX').'voteimg_item on '.C('DB_PREFIX').'voteimg_item.id = '.C('DB_PREFIX').'voteimg_record.item_id')->field('baby_id,vote_title,vote_time')->order('vote_time desc')->limit($page->firstRow.','.$page->listRows)->count();
			$page = new Page($total,10);
			$record = M('voteimg_record')->where($where)->join(C('DB_PREFIX').'voteimg_item on '.C('DB_PREFIX').'voteimg_item.id = '.C('DB_PREFIX').'voteimg_record.item_id')->field('baby_id,vote_title,vote_time')->order('vote_time desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
		}
		$this->assign('nick_name',$nick_name);
		$this->assign('action_name',$action_name);
		$this->assign('vote_record',$record);
		$this->assign('type_view',$type_view);
		$this->assign('user_id',$user_id);
		$this->assign('firstRow',$page->firstRow);
		$this->assign('searchword',$searchword);
		$this->display();
	}
	public function get_vote_details(){
		$item_id = (int)$_GET['item_id'];
		$vote_id = (int)$_GET['vote_id'];
		$total_sql = "select count(*) as total from (select count(*) from ".C('DB_PREFIX')."voteimg_record where item_id = {$item_id} AND token = '{$this->token}' AND vote_id = {$vote_id} group by user_id) as c";
		$total_arr = M('voteimg_record')->query($total_sql);
		$total = $total_arr[0]['total'];
		$page = new Page($total,10);
		$get_sql = "select count(*) as my_vote_count,client_ip,wecha_pic,nick_name,".C('DB_PREFIX')."voteimg_record.user_id,".C('DB_PREFIX')."voteimg_record.item_id from ".C('DB_PREFIX')."voteimg_record left join ".C('DB_PREFIX')."voteimg_users on ".C('DB_PREFIX')."voteimg_record.user_id = ".C('DB_PREFIX')."voteimg_users.user_id where ".C('DB_PREFIX')."voteimg_record.item_id = {$item_id} and ".C('DB_PREFIX')."voteimg_record.token = '{$this->token}' and ".C('DB_PREFIX')."voteimg_record.vote_id = '{$vote_id}' group by ".C('DB_PREFIX')."voteimg_record.user_id order by my_vote_count desc limit ".$page->firstRow.",".$page->listRows;
			$get_voterecord  = M('voteimg_record')->query($get_sql);
		$this->assign('get_voterecord',$get_voterecord);
		$this->assign('firstRow',$page->firstRow);
		$this->assign('page',$page->show());
		$this->display();
	}
	public function select_lottery(){
		$lottery_type = (int)$_GET['lottery_type'];
		if($lottery_type == ""){ $this->error('参数错误');}
		$where = array('token'=>$this->token);
		$search_word = $this->_request('search_word','trim');
		if(in_array($lottery_type, array(1,2,5,10))){
			if(!empty($search_word)){
				$where['title'] = array('like','%'.$search_word.'%');
			}
			$where['type'] = (int)$lottery_type;
			$where['status'] = 1;
			$where['statdate'] = array('lt',$_SERVER['REQUEST_TIME']);
			$where['enddate'] = array('gt',$_SERVER['REQUEST_TIME']);
			$total = M('Lottery')->where($where)->count();
			$Page = new Page($total,10);
			$list = M('Lottery')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
			$type = 1;
		}elseif($lottery_type == 3){
			if(!empty($search_word)){
				$where['action_name'] = array('like','%'.$search_word.'%');
			}
			$total = M('shakelottery')->where($where)->count();
			$Page = new Page($total,10);
			$list = M('shakelottery')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
			$type = 2;
		}

		$this->assign('name',$search_word);
		$this->assign('type',$type);
		$this->assign('list',$list);
		$this->display();
	}
	//清空当天投票记录
	private function clear_vote_day($vote_id = '',$token = ''){
		//将今日之前的[今日投票数]归零
		$today_time = strtotime(date('Y-m-d 00:00:00',$_SERVER['REQUEST_TIME']));
		$evening_time = strtotime(date('Y-m-d 23:59:59',$_SERVER['REQUEST_TIME']));
		$cache_time = $evening_time - $_SERVER['REQUEST_TIME'];
		$where = "vote_id = {$vote_id} and token = '{$token}' and vote_time < '{$today_time}'";
		if(M('voteimg_users')->where($where)->find()){
			M('voteimg_users')->where($where)->save(array('votenum_day'=>0,'vote_today'=>0));
		}
	}
	//删除添加的省市
	public function DelProCity(){
		if(IS_AJAX){
			$action_id = (int)$_GET['action_id'];
			$id = (int)$_GET['id'];
			if(empty($action_id) || empty($id)){
				exit('fail');
			}
			$voteimg = M('voteimg')->where(array('id'=>$action_id))->find();
			$pro_city = $voteimg['pro_city'];
			if(strpos($pro_city,'|') !== false){
				$explode = explode('|',$pro_city);
				unset($explode[$id-1]);
				$pro_city = implode("|",$explode);
				if(trim($pro_city,'|') == ''){
					exit('删除失败,您开启了地区限制,至少要有一个限制的省市');
				}
				$update = M('voteimg')->where(array('id'=>$action_id))->save(array('pro_city'=>trim($pro_city,'|')));
				if($update){
					exit('done');
				}else{
					exit('fail');
				}
			}else{
				if($voteimg['territory_limit'] == 1){
					exit('删除失败,您开启了地区限制,至少要有一个限制的省市');
				}
			}
		}
		exit('fail');
	}

	//拆分旧的投票记录表兼容新版投票
	public function splittable($vote_id = '',$token = ''){
		if($vote_id == "" || $token == ""){ return false;}
		$vote_id = (int)$vote_id;
		$voteimg = M("voteimg")->where(array('id'=>$vote_id))->field('split_number,ifsplit')->find();
		if($voteimg['ifsplit'] == 1){ return false;}
		$number = (int)$voteimg['split_number'];
		$pointtime = strtotime('2015-11-20 16:10:00');
		$where = array('vote_id'=>$vote_id,'votenum'=>array('gt',0),'item_id'=>array('neq',''));
		$total = M("voteimg_users")->where($where)->count();
		if($total <= 0){ return false;}
		if($total > 1500){
			$times = ceil($total/1500);
			if($number == $times){ //拆分完毕
				M("voteimg")->where(array('id'=>$vote_id))->save(array('ifsplit'=>1));
				return false;
			}
			//数据量太大分批拆分
			$items = M("voteimg_users")->where($where)->order('vote_time desc')->limit($number*1500,1500)->select();
			$i = 0;
			$j =0;
			$add = array();
			$addArray = array();
			foreach ($items as $key => $value){
				$time = 0;
				if(strpos($value['item_id'], ',') !== false){
					$ids = explode(",", trim($value['item_id'],','));
					foreach ($ids as $k => $v) {
						$time = $time + 10;
						$addArray[$i]['vote_id'] = $value['vote_id'];
						$addArray[$i]['user_id'] =  $value['user_id'];
						$addArray[$i]['item_id'] =  $v;
						$addArray[$i]['vote_time'] =  $value['vote_time']+$time;
						$addArray[$i]['token'] =  $value['token'];
						$addArray[$i]['vote_type'] = 1;
						$i++;
					}
				}else{
					$time = $time + 10;
					$add[$j]['vote_id'] = $value['vote_id'];
					$add[$j]['user_id'] =  $value['user_id'];
					$add[$j]['item_id'] =  $value['item_id'];
					$add[$j]['vote_time'] =  $value['vote_time']+$time;
					$add[$j]['token'] =  $value['token'];
					$add[$j]['vote_type'] = 1;
					$j++;
				}
			}
			M('voteimg_record')->addAll($addArray);
			M('voteimg_record')->addAll($add);
			unset($addArray);
			unset($add);
			M("voteimg")->where(array('id'=>$vote_id))->setInc('split_number');
			$this->success('由于您的数据量过大需对数据重新整合,请勿关闭浏览器耐心等待1-2分钟',U('Voteimg/add_voteimg',array('id'=>$vote_id,'token'=>$token)));exit;
		}else{
			$items = M("voteimg_users")->where($where)->order('vote_time desc')->select();
			$i = 0;
			$j =0;
			$add = array();
			$addArray = array();
			foreach ($items as $key => $value) {
				$time = 0;
				if(strpos($value['item_id'], ',') !== false){
					$ids = explode(",", trim($value['item_id'],','));
					foreach ($ids as $k => $v) {
						$time = $time + 10;
						$addArray[$i]['vote_id'] = $value['vote_id'];
						$addArray[$i]['user_id'] =  $value['user_id'];
						$addArray[$i]['item_id'] =  $v;
						$addArray[$i]['vote_time'] =  $value['vote_time']+$time;
						$addArray[$i]['token'] =  $value['token'];
						$addArray[$i]['vote_type'] = 1;
						$i++;
					}
				}else{
					$time = $time + 10;
					$add[$j]['vote_id'] = $value['vote_id'];
					$add[$j]['user_id'] =  $value['user_id'];
					$add[$j]['item_id'] =  $value['item_id'];
					$add[$j]['vote_time'] =  $value['vote_time']+$time;
					$add[$j]['token'] =  $value['token'];
					$add[$j]['vote_type'] = 1;
					$j++;
				}
			}
			M('voteimg_record')->addAll($addArray);
			M('voteimg_record')->addAll($add);
			M("voteimg")->where(array('id'=>$vote_id))->save(array('ifsplit'=>1));
		}
	}
	public function create_quickmark_1(){
		include './vifnn/Lib/ORG/phpqrcode.php';
		$vote_id = $this->_get('vote_id','intval');
		$item_id = $this->_get('item_id','intval');
		$token = $this->_get('token','trim');
		$url = $this->siteUrl.'/index.php?g=Wap&m=Voteimg&a=playVoice&vote_id='.$vote_id.'&token='.$token.'&item_id='.$item_id;
		QRcode::png($url,false,1,11);
	}
	//二维码
	public function create_quickmark(){
		$this->assign('vote_id',$_GET['vote_id']);
		$this->assign('token',$_GET['token']);
		$this->assign('item_id',$_GET['item_id']);
		$this->display();
	}
	//播放视频
	public function playvideo(){
		$item_id = (int)$_GET['item_id'];
		$video = M('voteimg_item')->where(array('id'=>$item_id))->find();
		$this->assign('video_path',$video['video_path']);
		$this->display();
	}
	//上传语音视频
	public function localupload(){
		$upload = new UploadFile();
		$upload->allowExts  = array('mp3');
		//覆盖同名的文件
		$upload->uploadReplace=1;
        $firstLetter=substr($this->token,0,1);
        $upload->savePath =  './uploads/'.$firstLetter.'/'.$this->token.'/';// 设置附件上传目录
        //
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads')||!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads')){
            mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads',0777);
        }
        $firstLetterDir=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$firstLetter;
        if (!file_exists($firstLetterDir)||!is_dir($firstLetterDir)){
            mkdir($firstLetterDir,0777);
        }
        if (!file_exists($firstLetterDir.'/'.$this->token)||!is_dir($firstLetterDir.'/'.$this->token)){
            mkdir($firstLetterDir.'/'.$this->token,0777);
        }
		if(!file_exists($upload->savePath)||!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777);
		}
        if(!$upload->upload()) {// 上传错误提示错误信息
            $error=1;
            $msg=$upload->getErrorMsg();
            return array('status'=>'fail','msg'=>$msg);
        }else{// 上传成功 获取上传文件信息
            $error=0;
            $info =  $upload->getUploadFileInfo();
            $this->siteUrl=$this->siteUrl?$this->siteUrl:C('site_url');
			$msg=$this->siteUrl.substr($upload->savePath,1).$info[0]['savename'];
			 return array('status'=>'success','msg'=>$msg);
        }
	}
	//报名页自定义输入项管理
	public function apply_custom_field(){
		if($_GET['cid'] == ''){ $this->error('参数错误');}
		$fields = M('voteimg_custom_field')->where(array('token' => $this->token,'action_id'=>(int)$_GET['cid']))->order('sort DESC, field_id ASC')->select();
		foreach ($fields as &$f) {
			$f['field_type_html'] = $this->_formConf('field_type', $f['field_type']);
			$f['field_match_html'] = $this->_formConf('field_match', $f['field_match']);
		}	
		$apply_fields_name = M('voteimg')->where(array('id'=>(int)$_GET['cid']))->getField('apply_fields_name');
		$this->assign('apply_fields_name', json_decode($apply_fields_name,true));
		$this->assign('fields', $fields);
		$this->assign('action_id',$_GET['cid']);
		$this->display();
	}
	//自定义输入框配置入库
	public function customSave(){
		$field_name = $_POST['field_name'];
		$field_id = $_POST['field_id'];
		$is_details = $_POST['is_details'];
		$is_empty = $_POST['is_empty'];
		$field_type = $_POST['field_type'];
		$field_option = $_POST['field_option'];
		$field_match = $_POST['field_match'];
		$sort = $_POST['sort'];
		$item_name = $_POST['item_name'];
		$action_id = (int)$_POST['action_id'];
		$apply_fields_name = json_encode(array('vote_title'=>trim($_POST['vote_title']),'contact'=>trim($_POST['contact']),'filename'=>trim($_POST['filename']),'manifesto'=>trim($_POST['manifesto']),'voice_path'=>trim($_POST['voice_path'])));
			M('voteimg')->where(array('id'=>$action_id))->save(array('apply_fields_name'=>$apply_fields_name));
		
		$db_field = M('voteimg_custom_field');
		$ids = array();
		$t_list = $db_field->field('field_id')->where(array('token' => $this->token))->select();
		foreach ($t_list as $v) $ids[] = $v['field_id'];
		foreach ($field_name as $k => $val) {
			if (empty($val)) continue;
			$f_data = array('token' => $this->token, 'set_id' => 0, 'item_name' => $item_name[$k], 'sort' => intval($sort[$k]), 'field_name' => $val, 'field_option' => $field_option[$k], 'field_type' => $field_type[$k], 'field_match' => $field_match[$k], 'is_show' => 1, 'is_details' => $is_details[$k],'is_empty' => $is_empty[$k],'action_id'=>$action_id);
			if ($field_id[$k] && in_array($field_id[$k], $ids)) {
				$db_field->where(array('field_id' => $field_id[$k]))->save($f_data);
				$ids = array_diff($ids, array($field_id[$k]));
			} else {
				$db_field->add($f_data);
			}
		}
		/*
		if ($ids) {
			foreach ($ids as $id) {
				$db_field->where(array('token' => $this->token, 'field_id' => $id))->delete();
			}
		}*/
		$this->success('操作成功');
	}
	public function _formConf($type = '', $select = ''){
		$conf = array(
			'field_type' => array(
				array('value'=>'','text'=>'请选择类型'),
				array('value'=>'text','text'=>'单行文本框'),
				array('value'=>'textarea','text'=>'多行文本框'),
				array('value'=>'checkbox','text'=>'多选选框'),
				array('value'=>'radio','text'=>'单选按钮'),
				array('value'=>'select','text'=>'下拉框'),
				array('value'=>'date','text'=>'日期选择'),
			),
			'field_match' => array(
				array('value'=>'','text'=>'请选择验证方式'),
				array('value'=>'^[\u4e00-\u9fa5\a-zA-Z0-9]+$','text'=>'英文数字汉字'),
				array('value'=>'^[A-Za-z]+$','text'=>'英文大小写字符'),			
				array('value'=>'^[1-9]\d*|0$','text'=>'0或正整数'),
				array('value'=>'^[A-Za-z0-9_]+$','text'=>'字母数字下滑线'),
				array('value'=>'^[0-9]{1,30}$','text'=>'正整数'),
				array('value'=>'^[-\+]?\d+(\.\d+)?$','text'=>'整数或小数'),
				array('value'=>'\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*','text'=>'邮箱'),
				array('value'=>'^\d{6,}$','text'=>'手机'),
			)

		);

		$str = '';
		foreach($conf[$type] as $key=>$value){
			if($select == $value['value']){
				$selected	= 'selected';
			}else{
				$selected	= '';
			}
			$str 	.='<option value="'.$value['value'].'" '.$selected.'>'.$value['text'].'</option>';
		}
		return $str;
	}

	//格式化输出自定义表单
   private function html_layout($token,$vote_id,$item_id = 0){
		$fields = M('Voteimg_custom_field')->where(array('token' => $token,'action_id'=>$vote_id))->order('sort DESC')->select();
    	$list = array();
    	if($item_id != 0){
	    	$attachs = M('voteimg_item_attach')->where(array('item_id' => $item_id))->select();
		  	foreach ($attachs as $a) {
		    	$list[$a['field_id']] = $a;
		    }
		}
		$str = '';
		$arr = array();
		foreach($fields as $key => $value){
			if (empty($value['is_show'])) continue;	
			$v = isset($list[$value['field_id']]['field_value']) ? $list[$value['field_id']]['field_value'] : '';
			$str .= '<tr>';
			$str .= '<th width="120">';
			if ($value['is_empty']) {
				$str .= '<span class="red">*</span>';
			}
			$str .= $value['field_name'].'：';
			$str .= '</th>';
			$str .= $this->_getInput($value,$v,$key);
			$str .= '<input type="hidden" id="field_'.$value['field_id'].'" name="field_'.$value['field_id'].'" value="'.$value['field_id'].'"></tr>';
			$str .= '</tr>';
			$arr[] = array('id' => $value['field_id'], 'name' => $value['field_name'], 'type' => $value['field_type'], 'item_name' => $value['item_name'], 'match' => $value['field_match'], 'is_empty' => $value['is_empty']);  //js验证信息
		}
		return array('string' => $str, 'verify' => $arr);
    }
    //获取自定义表单
	private function _getInput($value,$v,$key){
		$input 	= '';
		switch($value['field_type']){
			case 'text':
				$input 	.= '<td><input type="text" class="px" style="width:200px;" id="field_id_'.$value['field_id'].'" name="field_id_'.$value['field_id'].'" value="' . $v . '" data-empty="' . $value['is_empty'] . '"><input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data['.$key.'][val] value=""/><input type="hidden" name=data['.$key.'][type] value="text"/></td>';
				break;
			case 'password':
				$input 	.= '<td><input type="password" class="px" style="width:350px;" id="field_id_'.$value['field_id'].'" name="field_id_'.$value['field_id'].'" value="' . $v . '"  data-empty="' . $value['is_empty'] . '"><input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data['.$key.'][val] value=""/><input type="hidden" name=data['.$key.'][type] value="password"/></td>';
				break;
			case 'textarea':
				$input 	.= '<td><textarea name="field_id_'.$value['field_id'].'" id="field_id_'.$value['field_id'].'" rows="4" cols="25" class="px" style="width:350px;height:100px;" data-empty="' . $value['is_empty'] . '">' . $v . '</textarea><input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data['.$key.'][val] value=""/><input type="hidden" name=data['.$key.'][type] value="textarea"/></td>';
				break;
			case 'checkbox':
				$option = explode('|', trim($value['field_option'],'|'));
				$v_arr = explode('|', trim($v,'|'));
				$input .= '<td>';
				for ($i=0; $i < count($option); $i++) {
					if ($v_arr && in_array($option[$i], $v_arr)) {
						$checked = 'checked=true';
					} else $checked = '';
					
					$input .= '<input type="checkbox" name="field_id_'.$value['field_id'].'[]" class="field_id_'.$value['field_id'].'" value="'.$option[$i].'" '.$checked.' /><label>' . $option[$i] . '</label>&nbsp;&nbsp;';
				}
				$input .= '<input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data['.$key.'][val] value=""/><input type="hidden" name=data['.$key.'][type] value="checkbox"/></td>';
				break;
			case 'radio':
				$option = explode('|', trim($value['field_option'],'|'));
				$input .= '<td>';
				for ($i=0; $i<count($option); $i++) {
					if ($v) {
						$checked = $v == $option[$i] ? 'checked=true' : '';
					} else {
						$checked = $i == 0 ? 'checked=true' : '';
					}
					
					$input .= '<label><input type="radio" name="field_id_'.$value['field_id'].'" class="field_id_'.$value['field_id'].'" value="'.$option[$i].'" '.$checked.' />' . $option[$i] . '</label>　';
				}
				$input .= '<input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data'.$key.'][val] value="'.$v.'"/><input type="hidden" name=data['.$key.'][type] value="radio"/></td>';
				break;
			case 'select':
				$input 	.= '<td><select style="width:200px;" name="field_id_'.$value['field_id'].'" id="field_id_'.$value['field_id'].'" class="dropdown-select"><option value="">请选择..</option>';
				$op_arr	= explode('|',trim($value['field_option'],'|'));
				$num	= count($op_arr);
				if($num > 0){
					for ($i = 0; $i < $num; $i++) {
						if ($v && $v == $op_arr[$i]) {
							$input .= '<option value="' . $op_arr[$i] . '" selected>' . $op_arr[$i] . '</option>';
						} else {
							$input .= '<option value="' . $op_arr[$i] . '">' . $op_arr[$i] . '</option>';
						}
					}
				}
				$input  .='</select><input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data['.$key.'][val] value=""/><input type="hidden" name=data['.$key.'][type] value="select"/></td>';
				break;
			case 'date':
				$v = $v ? $v : date('Y-m-d H:i');
				$input .= '<td><input type="text" class="px" name="field_id_'.$value['field_id'].'" id="field_id_'.$value['field_id'].'" value="' . $v . '" onClick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm\'})"  data-empty="' . $value['is_empty'] . '"/><input type="hidden" name=data['.$key.'][fid] value="'.$value['field_id'].'"/><input type="hidden" name=data['.$key.'][val] value=""/><input type="hidden" name=data['.$key.'][type] value="date"/></td>';
		}

		return $input;
	}

	//扩张字段入库
	private function save_attach($item_id, $data){
		    	$db = D('voteimg_item_attach');
    	$attachs = $db->where(array('item_id' => $item_id))->select();
    	$list = array();
    	foreach ($attachs as $a) {
    		$list[$a['field_id']] = $a;
    	}
    	foreach ($data['data'] as $d) {
    		if (empty($d['fid']) || !is_numeric($d['fid'])) continue;
    		if($d['type'] == 'checkbox') $data['field_id_'.$d['fid']] = implode("|", $data['field_id_'.$d['fid']]);
    		if (isset($list[$d['fid']])) {
    			$db->where(array('item_id' => $item_id, 'field_id' => $d['fid']))->save(array('item_id' => $item_id, 'field_id' => $d['fid'],'field_value' => $data['field_id_'.$d['fid']]));
    			unset($list[$d['fid']]);
    		} else {
    			$db->add(array('item_id' => $item_id, 'field_id' => $d['fid'], 'field_value' => $data['field_id_'.$d['fid']]));
    		}
    	}
    	if ($list) {
    		$ids = array();
    		foreach ($list as $l) {
    			$ids[] = $l['field_id'];
    		}
    		$db->where(array('item_id' => $item_id, 'field_id' => array('in', $ids)))->delete();
    	}
    }

    //删除自定义项配置
    public function delcustom(){
    	$field_id = (int)$_GET['did'];
    	$delField = M('voteimg_custom_field')->where(array('field_id'=>$field_id))->find();
    	if(empty($delField)){
    		exit('没有找到删除项');
    	}
    	$del = M('voteimg_custom_field')->where(array('field_id'=>$field_id))->delete();
    	if($del){
    		exit('success');
    	}else{
    		exit('删除失败');
    	}
    }
}
?>