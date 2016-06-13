<?php

	class User_groupAction extends BackAction{

		public function index(){
			$map = array();
			if (C('agent_version')){
				$map['agentid']=array('lt',1);
			}
			$UserDB = D('User_group');
			$count = $UserDB->where($map)->count();

			$Page       = new Page($count,15);// 实例化分页类 传入总记录数
			// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
			$nowPage = isset($_GET['p'])?$_GET['p']:1;
			$show       = $Page->show();// 分页显示输出
			$list = $UserDB->where($map)->order('id ASC')->page($nowPage.','.C('PAGE_NUM'))->select();
			if ($list){
				$i=1;
				foreach ($list as $item){
					$UserDB->where(array('id'=>$item['id']))->save(array('taxisid'=>$i));
					$i++;
				}
			}
			$this->assign('list',$list);
			$this->assign('page',$show);// 赋值分页输出
			$this->display();
		}
		public function add(){


			if(IS_POST){
				$_POST['func'] = join(',',$_REQUEST['func']);
				$this->all_insert();
			}else{
				$where = array( 'status' => 1 );
				if(!ALI_FUWU_GROUP){
					$where['funname']  = array('neq','Fuwu');
				}
				$func = M('Function') -> where($where) -> field('funname,name,id') -> select();
				$this->assign('func',$func);
				$info['access_count'] = 0;
				$this->assign('info',$info);
				$this->display();
			}
		}
		public function edit(){
			if(IS_POST){
				$_POST['func'] = join(',',$_REQUEST['func']);
				$access_count = M('UserGroup')->where(array('id'=>(int) $_POST['id']))->getField('access_count');
				$this->all_save();
				if ($_POST['access_count'] != $access_count) {
					$users = M('Users')->field('id')->where(array('gid' => $_POST['id']))->select();
					if ($users) {
						foreach ($users as $user) {
							S('checkVipTime_'.$user['id'], null);
						}
					}
				}
			}else{
				$where = array( 'status' => 1 );
				if(!ALI_FUWU_GROUP){
					$where['funname']  = array('neq','Fuwu');
				}
				$func = M('Function') -> where($where) -> field('funname,name,id') -> select();
				$this->assign('func',$func);
				$id = $this->_get('id','intval',0);
				if(!$id)$this->error('参数错误!');
				$info = D('User_group')->getGroup(array('id'=>$id));
				$this->assign('s','编辑');
				$this->assign('info',$info);
				$this->display('add');
			}
		}
		public function del(){
			$id=$this->_get('id','intval',0);
			if($id==0)$this->error('非法操作');
			$info = D('User_group')->delete($id);
			if($info){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}
		//静态模板管理
		public function tmpls(){
		 	$db = D('Wxuser');
	        $gid = (int)$_GET['id'];
	        $groupinfo = M('user_group')->where(array('id'=>$gid))->find();
	        if(empty($groupinfo)){
	        	$this->error('非法的套餐ID');
	        }
			include('./vifnn/Lib/ORG/index.Tpl.php');
			foreach($tpl as $k=>$v){
				$sort[$k] = $v['sort'];
				$tpltypeid[$k] = $v['tpltypeid'];
			}
			array_multisort($sort, SORT_DESC , $tpltypeid , SORT_DESC ,$tpl);
			$this->assign('groupinfo', $groupinfo);
			$this->assign('tpl',$tpl);
			$this->assign('gid',$gid);
			$this->display();
		}

		//保存选择的模板
		public function addtmpls(){
			if(IS_POST){
				$uncheckedstyle = $_POST['uncheckedstyle'];//未选中的模板编号
				if(empty($_POST['checkedstyle'])){
					exit('nonechecked');
				}
				$save = M('user_group')->where(array('id'=>(int)$_POST['gid']))->save(array('tmpls'=>$uncheckedstyle));
				if($save){
					exit('success');
				}else{
					exit('fail');
				}
			}else{
				exit('fail');
			}
		}

	}
?>