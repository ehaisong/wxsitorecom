<?php
class DishOutMAction extends DishMobileAction
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //初始化接口
    public function init()
    {
        $data['uid']=$this->groupId==2?$this->dMan['id']:$this->company['id'];
        $data['identity']=$this->groupId;
        $data['storeList']=$this->groupId===0?$this->companyModel->getList($this->token):array($this->companyModel->filterFields($this->company));
        $data['home']=$this->getHome($this->store_id);
        $this->jsonReturn(0,'',$data);
    }

    //首页
    public function home()
    {
        $store_id=I('get.store_id');
        if(empty($store_id)){
            $this->jsonReturn(1,'请选择要切换的门店');
        }
        if($store_id!=$this->company['id']){
            if(in_array($this->groupId,array(1,2))){
                $this->jsonReturn(1,'未获得门店授权');
            }elseif($this->groupId===0){
                $store=$this->companyModel->where(array('token'=>$this->token,'id'=>$store_id))->find();
                if(empty($store))
                    $this->jsonReturn(1,'未获得门店授权');
            }
        }else{
            $store=$this->company;
        }
        session('dish_store_id',$store_id);
        $data=$this->getHome($store_id);
        $this->jsonReturn(0,'',$data);
    }

    private function getHome($id)
    {
        $mess='今日:';
        $order=XD('DishOrder');
        if($this->dMan){
            $waitNum=$order->getOrderNum($this->store_id,'1',null,date('Ymd'));
            $mess.='外卖共有'.($waitNum>0?$waitNum:0).'笔订单待配送！';
            $deNum=$order->getOrderNum($this->store_id,'2',$this->dMan['id'],date('Ymd'));
            if($deNum>0){
                $mess.='我正在配送'.$deNum.'笔订单！';
            }
        }else{
            $dishoutOrderNum=$order->getOrderNum($this->store_id,'0',null,date('Ymd'));
            $mess.='外卖有'.($dishoutOrderNum>0?$dishoutOrderNum:0).'笔新订单等待处理！';
        }
        $mess=$mess?$mess:'暂无最新消息';
        $data['mess']=$mess;
        //0外卖  1预定 2点餐 3点餐订单 4快捷收银 5餐台管理 6排号管理
        $menuList=array(
            '0'=>[0],
            '1'=>[0],
            '2'=>[0]
        );
        $data['menuList']=$menuList[(string)$this->groupId];
        $data['currentStore']=$id;
        return $data;
    }

    //外卖订单
    public function order()
    {
        $allowStatus=$this->groupId=='2'?array('','1','2','3','4'):array('','0','1','2','3','4');
        if(isset($_GET['status'])&&!in_array($_GET['status'],$allowStatus)){
            $this->jsonReturn(1,'状态码不合法');
        }
        $_GET=array_merge(array('create_time'=>'desc'),$_GET);
        $_GET['groupId']=$this->groupId;
        $order=XD('DishOrder');
        $offset=isset($_GET['offset'])?I('get.offset'):0;
        $_GET['cid']=$this->store_id;
        $_GET['token']=$this->token;
        if($this->groupId==2&&in_array($_GET['status'],array('2','3','4'))){
            $_GET['tag']=$this->dMan['id'];
        }
        $total=$order->getTotal(I('get.'));
        $list=$order->getList(I('get.'),$offset,I('get.length'));
        $this->jsonReturn(0,'',array('list'=>$order->listMap($list),'total'=>$total));
    }

    //订单详情
    public function detail()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        if(empty($order_num)&&empty($id)){
            $this->jsonReturn(1,'订单号不能为空');
        }
        $order=XD('DishOrder');
        $detail=$order->getDetail($this->store_id,$id?$id:$order_num);
        if($detail['delivery_fee']<=0){
            unset($detail['delivery_fee']);
        }else{
            $detail['is_dist_free']=0;
            $detail['delivery_fee']=$detail['delivery_fee']/100;
        }
        if($detail['meal_fee']<=0){
            unset($detail['meal_fee']);
        }else{
            $detail['is_pack_free']=0;
            $detail['meal_fee']=$detail['meal_fee']/100;
        }
        if(empty($detail))
            $this->jsonReturn(1,$order->getError());
        $this->jsonReturn(0,'',$order->itemMap($detail));
    }

    //送餐员列表
    public function dman()
    {
        $_GET=array_merge(array('addtime'=>'desc'),$_GET);
        $dman=XD('DishoutDeliveryman');
        $offset=isset($_GET['offset'])?I('get.offset'):0;
        $_GET['cid']=$this->store_id;
        $total=$dman->getTotal(I('get.'));
        $list=$dman->getList(I('get.'),$offset,I('get.length'));
        $this->jsonReturn(0,'',array('list'=>$dman->listMap($list),'total'=>$total));
    }

    //取消订单
    public function cancel()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->cancel($this->store_id,$id?$id:$order_num);
        if(empty($num))
            $this->jsonReturn(1,'取消订单失败');
        $this->jsonReturn(0,'取消订单成功');
    }

    //接单
    public function agree()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->agree($this->store_id,$id?$id:$order_num);
        if(empty($num))
            $this->jsonReturn(1,'接单失败');
        $this->jsonReturn(0,'接单成功');
    }

    //分配订单
    public function assignOrder()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        $did=I('get.shopper');
        $order=XD('DishOrder');
        $num=$order->assign($this->store_id,$id?$id:$order_num,$did);
        if(empty($num))
            $this->jsonReturn(1,'分配失败:'.$order->getError());
        $this->jsonReturn(0,'分配成功');
    }

    //我来送
    public function ICan()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->assign($this->store_id,$id?$id:$order_num,$this->dMan['id'],true);
        if(empty($num))
            $this->jsonReturn(1,'抢单失败:'.$order->getError());
        $this->jsonReturn(0,'抢单成功');
    }

    //已送达
    public function complete()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->complete($this->store_id,$id?$id:$order_num,$this->dMan['id']);
        if(empty($num))
            $this->jsonReturn(1,'切换状态失败:'.$order->getError());
        $this->jsonReturn(0,'切换状态成功');
    }

    public function del()
    {
        $order_num=I('get.order_num');
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->del($this->store_id,$id?$id:$order_num);
        if(empty($num))
            $this->jsonReturn(1,'关闭失败:'.$order->getError());
        $this->jsonReturn(0,'关闭成功');
    }



}