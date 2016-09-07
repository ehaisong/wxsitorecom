<?php
class DishMAction extends WapAction {

    protected $isLogin=false;

    public function _initialize()
    {
        parent::_initialize();
        $dManModel=D('DishoutDeliveryman');
        $bindModel=D('DishBind');
        $bindInfo=$bindModel->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id))->find();
        if(!empty($bindInfo)){
            session('dish_cid',$bindInfo['cid']);
            session('dish_did',null);
            $this->isLogin=true;
        }else{
            $dMan=$dManModel->where(array('openid'=>$this->wecha_id,'token'=>$this->token))->find();
            if(!empty($dMan)){
                session('dish_did',$dMan['id']);
                session('dish_cid',null);
                $this->isLogin=true;
            }
        }
        if(!$this->isLogin){
            $this->error('您没有登录权限');
        }
        session('dish_token',$this->token);
    }

    public function index()
    {
        $api='/index.php?g=User';
        $this->assign('api',$api);
        $this->display();
    }
}

?>