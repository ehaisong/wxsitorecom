<?php
class UserMobileAction extends Action
{
    protected $token;//商家公众号token
    protected $wxuser;//商家公众号信息
    protected $wecha_id;//粉丝openid
    protected $companyModel;
    protected $dManModel;
    protected $company;//门店
    protected $dMan;//外卖员
    protected $store_id;//当前所在门店ID
    protected $store;//当前所在门店
    protected $groupId;//所在组id 0总管理员 1门店管理员 2外卖员
    //分组权限表
    protected $authList=array(
        '0'=>array('dishoutm_init','dishoutm_home','dishoutm_order','dishoutm_detail','dishoutm_dman','dishoutm_cancel','dishoutm_agree','dishoutm_assign'),
        '1'=>array('dishoutm_init','dishoutm_home','dishoutm_order','dishoutm_detail','dishoutm_dman','dishoutm_cancel','dishoutm_agree','dishoutm_assign'),
        '2'=>array('dishoutm_init','dishoutm_home','dishoutm_order','dishoutm_detail','dishoutm_ican','dishoutm_complete')
    );

    public function _initialize()
    {
        $session_openid_name = 'token_openid_' . $this->token;
        $this->wecha_id=session('?dish_wecha_id')?session('dish_wecha_id'):session($session_openid_name);
        $this->token=session('dish_token');
        $this->groupId=-1;
        $this->companyModel=$compnayModel=D('Company');
        $this->dManModel=$dManModel=D('DishoutDeliveryman');
        $bindModel=D('DishBind');
        $bindInfo=$bindModel->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
        if(!empty($bindInfo)){
            $this->company=$compnayModel->where(array('id'=>$bindInfo['cid']))->find();
        }
        if(empty($this->company)){
            $this->dMan=$dManModel->where(array('openid'=>$this->wecha_id,'token'=>$this->token))->find();
            if(!empty($this->dMan)){
                $this->groupId=2;
                $this->company=$compnayModel->where(array('id'=>$this->dMan['cid']))->find();
            }
        }else{
            $this->groupId=empty($this->company['isbranch'])?0:1;
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
        $this->store_id=!empty($_REQUEST['store_id'])?I('request.store_id'):$this->company['id'];
        if(!in_array(ACTION_NAME,$allow)){
            if(empty($this->store_id)){
                $this->jsonReturn(1,'请选择门店');
            }
            if($this->store_id!=$this->company['id']){
                if(in_array($this->groupId,array(1,2))){
                    $this->jsonReturn(1,'未获得门店授权');
                }elseif($this->groupId===0){
                    $this->store=$this->companyModel->where(array('token'=>$this->token,'id'=>$this->store_id))->find();
                    if(empty($this->store))
                        $this->jsonReturn(1,'未获得门店授权');
                }
            }else{
                $this->store=$this->company;
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