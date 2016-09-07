<?php
class DishMobileAction extends Action
{
    protected $token;//商家公众号token
    protected $wxuser;//商家公众号信息
    protected $companyModel;
    protected $dManModel;
    protected $company;//门店
    protected $dMan;//外卖员
    protected $store_id;//当前所在门店ID
    protected $groupId;//所在组id 0总管理员 1门店管理员 2外卖员
    //分组权限表
    protected $authList=array(
        '0'=>array('dishoutm_init','dishoutm_home','dishoutm_order','dishoutm_detail','dishoutm_dman','dishoutm_cancel','dishoutm_agree','dishoutm_assignorder'),
        '1'=>array('dishoutm_init','dishoutm_home','dishoutm_order','dishoutm_detail','dishoutm_dman','dishoutm_cancel','dishoutm_agree','dishoutm_assignorder'),
        '2'=>array('dishoutm_init','dishoutm_home','dishoutm_order','dishoutm_detail','dishoutm_ican','dishoutm_complete')
    );

    public function _initialize()
    {
        $cid=session('dish_cid');
        $did=session('dish_did');
        $this->token=session('dish_token');
        $this->groupId=-1;

        if(!empty($_GET['token'])){
            $this->token=I('get.token');
        }
        if(!empty($_GET['rwname'])){
            $rUser=M('userinfo')->where(array('token'=>$this->token,'wechaname'=>array('like','%'.$_GET['rwname'].'%')))->field('wecha_id')->find();
            $rwid=$rUser['wecha_id'];
            $dManModel=D('DishoutDeliveryman');
            $bindModel=D('DishBind');
            $bindInfo=$bindModel->where(array('token'=>$this->token,'wecha_id'=>$rwid))->find();
            if(!empty($bindInfo)){
                $cid=$bindInfo['cid'];
            }else{
                $dMan=$dManModel->where(array('openid'=>$rwid,'token'=>$this->token))->find();
                if(!empty($dMan)){
                    $did=$dMan['id'];
                }
            }
        }
        $this->companyModel=$compnayModel=D('Company');
        $this->dManModel=$dManModel=D('DishoutDeliveryman');
        if(!empty($cid)){
            $this->company=$compnayModel->where(array('id'=>$cid))->find();
        }else if(!empty($did)){
            $this->dMan=$dManModel->where(array('id'=>$did))->find();
        }
        if(!empty($this->company)){
            $this->groupId=empty($this->company['isbranch'])?0:1;
        }elseif(!empty($this->dMan)){
            $this->groupId=2;
            $this->company=$compnayModel->where(array('id'=>$this->dMan['cid']))->find();
        }
        if($this->groupId===-1){
            $this->jsonReturn(1,'您尚未登录或登录身份已过期');
        }
        if(!$this->checkAuth()){
            $this->jsonReturn(1,'您没有操作权限');
        }
        if(empty($this->company)){
            $this->jsonReturn(1,'门店不存在');
        }
        $allow=array('init');
        $this->store_id=session('?dish_store_id')?session('dish_store_id'):$this->company['id'];
        if(!in_array(ACTION_NAME,$allow)){
            if(empty($this->store_id)){
                $this->jsonReturn(1,'请选择门店');
            }
        }
    }

    //检查权限
    protected function checkAuth()
    {
        $mark=strtolower(MODULE_NAME.'_'.ACTION_NAME);
        $arr=$this->authList[(string)$this->groupId];
        return in_array($mark,$arr);
    }

    public function jsonReturn($status,$info='',$data)
    {
        $arr=array('status'=>$status,'info'=>$info?$info:'');
        if($status==0){
            $arr['data']=$data;
            //pre_echo($data);exit();
        }
        header('Content-type: application/json;charset=utf-8');
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        exit();
    }

}