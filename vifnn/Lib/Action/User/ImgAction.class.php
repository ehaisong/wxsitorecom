<?php
class ImgAction extends ImgBaseAction{
	public function _initialize() {
		parent::_initialize();
	}
	public function index(){
		parent::index();
		$this->display();

	}
	
	public function add(){
		parent::add();
		$this->display();
	}

	
	
	public function edit(){
		parent::edit();
		$this->display();
	}
	
	
	public function del(){
		parent::del();
	}
	public function insert(){
		if($this->_post('classid') == ''){
			$this->error('文章所属分类必须选择');
		}
		parent::insert();
	}
	public function upsave(){
		parent::upsave();
	}
	
	public function editClass(){
		parent::editClass();
		$this->display();
	}
	
	public function editUsort(){
		parent::editUsort();
	}
	
	public function multiImgDel(){
		parent::multiImgDel();
	}
	
	public function multi(){
		parent::multi();
		$this->display();
	}
	
	
	public function multiSave(){
		parent::multiSave();
	}
	
	public function diyTool(){
		$this->display();
	}
	public function dashang(){
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;
		$where_page['id'] = $_GET['id'];
		if(!empty($_GET['name'])){
			$s_wecha_id = M('userinfo')->where(array('token'=>$this->token,'wechaname'=>array('like','%'.$_GET['name'].'%')))->limit(1)->getField('wecha_id');
			$where['wecha_id'] = $s_wecha_id;
			$where_page['name'] = $_GET['name'];
		}
		$where['imgid'] = intval($_GET['id']);
		$id = intval($_GET['id']);
		$where['paid'] = 1;
		if($_GET['list_type'] == 1 || $_GET['list_type'] == ''){
			$count_s = M('img_dashang_order')->where($where)->Distinct(true)->field('wecha_id')->select();
			$count = count($count_s);
			$page = new Page($count,8);
			$show = $page->show();
			foreach($where_page as $key=>$val){
				$page->parameter.="$key=".urlencode($val).'&';
			}
			if($_GET['rank_type'] == 1){
				$noworder = "addtime desc";
			}else{
				$noworder = "sum desc";
			}
			$dashang_list = M('img_dashang_order')->where($where)->Distinct(true)->field('wecha_id')->order($noworder)->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$show);
			foreach($dashang_list as $dk=>$dv){
				$dashang_list[$dk]['userinfo'] = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$dv['wecha_id']))->find();
				$dashang_list[$dk]['price'] = (M('img_dashang_order')->where(array('token'=>$this->token,'imgid'=>$id,'paid'=>1,'wecha_id'=>$dv['wecha_id']))->sum('price_s'))/100;
				$dashang_list[$dk]['addtime'] = M('img_dashang_order')->where(array('token'=>$this->token,'imgid'=>$id,'paid'=>1,'wecha_id'=>$dv['wecha_id']))->order('addtime desc')->limit(1)->getField('addtime');
			}
		}else{
			$count = M('img_dashang_order')->where($where)->count();
			$page = new Page($count,8);
			$show = $page->show();
			foreach($where_page as $key=>$val){
				$page->parameter.="$key=".urlencode($val).'&';
			}
			if($_GET['rank_type'] == 1){
				$noworder = "addtime desc";
			}else{
				$noworder = "price_s desc";
			}
			$dashang_list = M('img_dashang_order')->where($where)->order($noworder)->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$show);
			foreach($dashang_list as $dk=>$dv){
				$dashang_list[$dk]['userinfo'] = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$dv['wecha_id']))->find();
				$dashang_list[$dk]['price'] = $dv['price_s']/100;
			}
		}
		$this->assign("dashang_list",$dashang_list);
		$img = M('img')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();
		$this->assign('img',$img);
		$this->display();
	}
    //评论列表
    public function comment(){
        $status = $this->_request('status','trim');
        $reply = $this->_request('reply','intval');
        $where['token'] = $this->token;
        $where_page['token'] = $this->token;
        $where_page['id'] = $_GET['id'];

        $where['imgid'] = intval($_GET['id']);
        if($status != ''){
            $where['status'] = array('eq',$status);
            $where_page['status'] = $status;
        }
        if(!empty($reply)){
            if( $reply === 1 ){
                $where['reply'] = array('neq','');
            }elseif($reply === 0){
                $where['reply'] = array('eq','');
            }
            $where_page['reply'] = $reply;

        }

        $id = intval($_GET['id']);
        $count = M('img_comment')->where($where)->count();
        $page = new Page($count,8);
        foreach($where_page as $key=>$val){
            $page->parameter.="$key=".urlencode($val).'&';
        }
        $show = $page->show();
        $list = M('img_comment')->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('page',$show);
        foreach($list as $dk=>$dv){
        }
        $this->assign("list",$list);
        $img = M('img')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();
        $this->assign('img',$img);
        $this->display();
    }
    
    public function comment_del(){
        $id = $this->_request('id','intval');
        $token = $this->token;
        if(empty($id)){
            $this->error('请勾选要删除的内容');
        }       
        if(is_array($id)){
        
            $id = implode(',',$id);
            $where = "token = '$token' AND id in($id)";
            
        }else{    
            $where = "token = '$token' AND id = $id";
        }
        if(M('img_comment')->where($where)->delete()){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }    
    
    
    
    public function comment_pass(){
        $where['id']=$this->_post('id','intval');
        $status = $this->_post('status','intval');
        $where['token']=$this->token;         
        $data['status'] = $status;
        if(M('img_comment')->where($where)->save($data)){
            $res['status'] = 1;
        }else{
            $res['status'] = 0;
        }
        $res['pass'] = $status;
        $this->ajaxReturn($res);
    }
    
    
    public function comment_passall(){
    
        $id = $this->_request('id','intval');
        
        $token = $this->token;
                
        if(empty($id)){
            $this->error('请勾选要通过审核的内容');
        }
        
        if(is_array($id)){
        
            $id = implode(',',$id);
            $where = "token = '$token' AND id in($id)";
            
        }else{    
            $where = "token = '$token' AND id = $id";
        }
        
        if(M('img_comment')->where( $where )->setField('status',1)){
               
            $this->success('推荐成功');
        }else{
            $this->error('推荐失败');
        }
    
    }        
    
    public function comment_replay(){
        if(IS_POST){
            $icmt_mod = M('img_comment');
            $id = $this->_post('id','intval');
            $reply = $this->_post('replycontent','htmlspecialchars');
            $where['token']=$this->token; 
            $where['id']  = $id;
            $replys = $icmt_mod->where($where)->getField('reply'); 
            $addarr = array(
                    'reply' => $reply,
                    'ctime' => time(),               
            );
            if(!empty($replys)){
                $rearr  = json_decode($replys,true);
                $rearr[] = $addarr;
                krsort($rearr);
                $newjson = json_encode($rearr);
                $replys = $icmt_mod->where($where)->setField('reply',$newjson);               
            }else{
                $newarr[] = $addarr; 
                $newjson = json_encode($newarr);
                $replys = $icmt_mod->where($where)->setField('reply',$newjson); 
            }
            $this->success("操作成功!");
        }else{
            $icmt_mod = M('img_comment');
            $id = $this->_get('id','intval');
            $where['token']=$this->token; 
            $where['id']  = $id;
            $replys = json_decode($icmt_mod->where($where)->getField('reply'),true); 
            $this->assign('replys',$replys);
            $this->display();                
        }
        
    }    
}
?>