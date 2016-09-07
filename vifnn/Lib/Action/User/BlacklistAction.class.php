<?php

/**
 * Class ExtensionAction
 */
class BlacklistAction extends UserAction
{
    private $blacklistModel;

    public function _initialize()
    {
        $this->blacklistModel=D('User/Blacklist');
        parent::_initialize();
    }

    public function index()
    {
        //默认条件
        $get=array_merge(array(
            '_create_time'=>'desc'
        ),$_GET);
        $get['token']=$this->token;
        $count=$this->blacklistModel->getTotal($get);
        $page = new Page($count,15);
        $list = $this->blacklistModel->getList($get,$page->firstRow,$page->listRows);
        $this->assign('_list',$list);
        $this->assign('_get',$get);
        $this->assign('page',$page->show());
        $this->display();
    }

    //新增
    public function add()
    {
        if(IS_GET)
        {
            $this->display();
        }
        elseif (IS_POST)
        {
            if(I('post.is_limit_time')!='1')
            {
                unset($_POST['limit_start']);
                unset($_POST['limit_end']);
            }
            $_POST['token']=$this->token;
            if(!$this->blacklistModel->processing($_POST,'add'))
            {
                $this->error($this->blacklistModel->getError());
            }
            $id=$this->blacklistModel->add();
            if(!$id)
                $this->error('新增规则失败');
            S('blacklist_'.$this->token,null);
            $this->success('新增规则成功',U('index',array('token'=>$this->token)));
        }
    }

    //编辑
    public function edit()
    {
        if(IS_GET)
        {
            if(empty($_GET['id']))
                $this->error('规则不存在');
            $where['token']=$this->token;
            $where['id']=I('get.id');
            $result=$this->blacklistModel->where($where)->find();
            if(empty($result))
                $this->error('规则不存在');
            $this->assign('_info',$result);
            $this->display('add');
        }
        elseif (IS_POST)
        {
            if(I('post.is_limit_time')!='1')
            {
                $_POST['limit_start']='';
                $_POST['limit_end']='';
            }
            $_POST['token']=$this->token;
            if(!$this->blacklistModel->processing($_POST,'update'))
            {
                $this->error($this->blacklistModel->getError());
            }
            $num=$this->blacklistModel->where(array('id'=>I('post.id'),'token'=>$this->token))->save();
            if(!$num)
                $this->error('修改规则失败');
            S('blacklist_'.$this->token,null);
            $this->success('修改规则成功',U('index',array('token'=>$this->token)));
        }
    }

    public function del()
    {
        $ids=get_request_ids();
        if(count($ids)<=0)$this->error("请选择规则");
        $where['id']=array("in",$ids);
        $where['token']=$this->token;
        $result=$this->blacklistModel->where($where)->delete();
        if($result<=0)$this->error('删除失败');
        S('blacklist_'.$this->token,null);
        $this->success('删除成功');//'删除成功，共计删除了'.$result.'记录'
    }

    public function changeStatus()
    {
        $ids=get_request_ids();
        if(count($ids)<=0)$this->error("请选择规则");
        $where['id']=array("in",$ids);
        $where['token']=$this->token;
        $status=I('request.status');
        $result=$this->blacklistModel->where($where)->save(array('status'=>$status));
        if($result<=0)
            $this->error($status=='1'?'开启失败':'关闭失败');
        S('blacklist_'.$this->token,null);
        $this->success($status=='1'?'开启成功':'关闭成功');//'更新状态成功，共计更新了'.$result.'记录'
    }

    public function changeBlacklistStatus()
    {
        $where['token']=$this->token;
        $status=I('request.blacklist_status');
        $result=M('wxuser')->where($where)->save(array('blacklist_status'=>$status));
        if($result<=0)
            $this->error($status=='1'?'开启失败':'关闭失败');
        $wxuser=S('wxuser_' . $this->token);
        if(!empty($wxuser))
        {
            $wxuser['blacklist_status']=$status;
        }
        S('wxuser_' . $this->token,$wxuser);
        $this->success($status=='1'?'开启成功':'关闭成功');
    }













}


?>
