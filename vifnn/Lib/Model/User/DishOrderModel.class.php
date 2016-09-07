<?php
class DishOrderModel extends XModel
{
    private $fieldsArr=array(
        'a1'=>'id,cid,tel,orderid,name,deliverymanid,paid,paytype,price,time,reservetime,address,status,total'
    );

    protected $relationMap=array(
        'default'=>array(
            'name'=>'cst_name',
            'paid'=>'pay_status',
            'paytype'=>'pay_type',
            'time'=>'create_time',
            'reservetime'=>'arra_time',
            'orderid'=>'order_num',
            'price'=>'sum',
            'status'=>'order_statu',
            'total'=>'goods_num',
            'deliveryman'=>'shopper[item:DishoutDeliveryman@default]',
            'pro_list'=>'goods[list:product]',
            'delivery_fee'=>'dist_charge',
            'meal_fee'=>'pack_charge'
        ),
        'product'=>array(
            'num'=>'count'
        )
    );

    public function getTotal($get)
    {
        return $this->setWhere($get)->count();
    }

    public function getList($get,$offset=0,$length=10)
    {
        $length=$length<=0?10:($length>=30?30:$length);
        $list=$this->setWhere($get)->setOrder($get)->field($this->fieldsArr['a1'])->limit($offset,$length)->select();
        $dMan=D('DishoutDeliveryman');
        $companyModel=D('Company');
        foreach ($list as &$item){
            $item['status']=(int)$item['status'];
            $item['paid']=(int)$item['paid'];
            if(!empty($item['deliverymanid'])){
                $item['deliveryman']=$dMan->where(array('id'=>$item['deliverymanid']))->field('id,name,tel,avatar')->find();
            }
            if($get['cid']==''){
                $item['company']=$companyModel->where(array('cid'=>$item['cid']))->field('id,name,tel')->find();
            }
        }
        return $list;
    }

    protected function setWhere($get)
    {
        $where=array();
        $where['comefrom']='dishout';
        $where['isdel']=array('neq','1');
        if($get['cid']!=''){
            $where['cid']=$get['cid'];
        }else{
            $where['token']=$get['token'];
        }
        if($get['paid']!=''){
            $where['paid']=$get['paid'];
        }
        if($get['keyword']!=''){
            if(validate_regex($get['keyword'],'mobile')){
                $where['tel']=$get['keyword'];
            }else{
                $where['_string']='orderid = \''.$get['keyword'].'\' OR name like \'%'.$get['keyword'].'%\'';
            }
        }
        if($get['type']!=''&&$get['type']!='all'){
            $where['status']=$get['groupId']=='2'?array('in',array('1','2','3','4')):$get['type'];//外卖员不需要看到待接单
        }
        if($get['status']!=''){
            $where['status']=$get['status'];
        }
        if($get['tag']!=''&&$get['tag']!='all'){
            $where['deliverymanid']=$get['tag'];
        }
        if($get['date']!=''){
            $where['date']=$get['date'];
        }
        return $this->where($where);
    }

    protected function setOrder($get)
    {
        $order=array();
        if($get['create_time']!=''){
            $order['time']=$get['create_time'];
        }
        return $this->order($order);
    }

    public function getDetail($cid,$id)
    {
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $result=$this->where($where)->field($this->fieldsArr['a1'].',info,delivery_fee,meal_fee')->find();
        if(empty($result)) {
            $this->error='订单不存在';
            return false;
        }
        $result['pro_list']=unserialize($result['info']);
        unset($result['info']);
        $dMan=D('DishoutDeliveryman');
        if(!empty($result['deliverymanid'])){
            $result['deliveryman']=$dMan->where(array('id'=>$result['deliverymanid']))->field('id,name,tel,avatar')->find();
        }
        $result['status']=(int)$result['status'];
        $result['paid']=(int)$result['paid'];
        return $result;
    }

    //取消订单
    public function cancel($cid,$id)
    {
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid,info')->find();
        $list=unserialize($order['info']);
        if($order['paid']=='1'){
            $orderModel=D('User/DishOrder');
            $res=$orderModel->doRefund($order,$order['price']);
            if($res['result_code']!=='SUCCESS'){
                $this->error='退款失败';
                return false;
            }
        }
        //恢复库存
        if($order['paid']=='1'||in_array($order['paytype'],array('daofu','dianfu'))){
            $this->payRecovery($list);
        }else{
            $this->orderRecovery($order['id']);
        }
        $num=$this->where(array('id'=>$order['id']))->save(array('status'=>'4'));
        return $num;
    }

    //返回相同属性的商品位置
    protected function indexOf($list,$condition=array())
    {
        $condition=is_int($condition)?array('id'=>$condition):$condition;
        foreach ($list as $index=>$item){
            $res=true;
            foreach ($condition as $key=>$value){
                if(!isset($item[$key])||$item[$key]!=$value){
                    $res=false;
                    break;
                }
            }
            if($res)return $index;
        }
        return -1;
    }

    //消耗库存列表，按照商品ID和规格来区分商品，不考虑attr的不同，只考虑id和spec的不同
    public function createConsumeList($list)
    {
        $consumeList=array();
        foreach ($list as $item){
            $pos=$this->indexOf($consumeList,array('id'=>$item['id'],'spec'=>$item['spec']));
            if($pos<=-1){
                $consumeList[]=array('id'=>$item['id'],'spec'=>$item['spec'],'num'=>0);
                $pos=count($consumeList)-1;
            }
            $consumeList[$pos]['num']+=$item['num'];
        }
        return $consumeList;
    }

    //下单后取消时恢复库存
    public function orderRecovery($orderId)
    {
        $timeoutModel=D('DishTimeout');
        $timeoutModel->where(array('order_id'=>$orderId))->delete();
    }

    //支付后取消时恢复库存
    public function payRecovery($list)
    {
        $consumeList=$this->createConsumeList($list);
        $dishSkuModel=D('DishSku');
        $dishModel=D('Dish');
        foreach ($consumeList as $item){
            if(!empty($item['spec'])){
                $dishSkuModel->where(array('sku'=>sha1($item['spec']),'product_id'=>$item['id']))->setInc('inventory',$item['num']);
            }
            $dishModel->where(array('id'=>$item['id']))->setInc('instock',$item['num']);
        }
    }

    //请求退款接口
    protected function doRefund($data = array(),$refund_fee = '')
    {
        if($data['orderid'] == '' || $data['paytype'] == '' || $data['token'] == ''){
            $this->error='退款参数错误';
            return false;
        }
        switch ($data['paytype']) {
            case 'weixin':
                $from = 'dishout';
                $order = new OrderModel();
                $refund = $order->weixinRefund($data['token'],$data['orderid'],$from,$refund_fee*100);
                break;
            case 'alipay':
                break;
            case 'CardPay':
                $savedata = array();
                $savedata['balance'] = array('exp','balance+'.$refund_fee);//返还
                $recharge = M('userinfo')->where(array('token'=>$data['token'],'wecha_id'=>$data['wecha_id']))->save($savedata);
                if($recharge){
                    $info =  M('member_card_create')->where(array('token'=>$data['token'],'wecha_id'=>$data['wecha_id']))->find();
                    $where 	= array('token'=>$info['token'],'wecha_id'=>$info['wecha_id'],'cardid'=>$info['cardid']);
                    $record_data 	= array(
                        'orderid' 		=> date('YmdHis',time()).mt_rand(1000,9999),
                        'ordername' 	=> '外卖返还金额',
                        'price' 		=> $refund_fee,
                        'createtime'	=> time(),
                        'paytime'		=> time(),
                        'cardid' 		=> $info['cardid'],
                        'wecha_id' 		=> $info['wecha_id'],
                        'token' 		=> $info['token'],
                        'module'		=> 'Card',
                        'type'			=> 1,
                        'paid'			=> 1,
                    );
                    $add_record = M('Member_card_pay_record')->where($where)->add($record_data);
                    if($add_record)
                        return array('result_code'=>'SUCCESS','msg'=>'充值成功');
                }
                break;
            default:
                return false;
                break;
        }
        return $refund;
    }

    //接单
    public function agree($cid,$id)
    {
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid')->find();
        if(empty($order)||$order['status']!='0'){
            $this->error=empty($order)?'订单不存在':'状态不正确';
            return false;
        }
        $num=$this->where(array('id'=>$order['id']))->save(array('status'=>'1'));
        return $num;
    }

    //分配给送餐员 $isDMan 是否需要验证送餐员ID false 需要 true不需要
    public function assign($cid,$id,$did,$isDMan=false)
    {
        $dManModel=D('DishoutDeliveryman');
        if(!$isDMan){
            $dMan=$dManModel->where(array('cid'=>$cid,'id'=>$did))->field('id')->find();
            if(empty($dMan)){
                $this->error='送餐员不存在';
                return false;
            }
        }
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid')->find();
        if(empty($order)||$order['status']!='1'){
            $this->error=empty($order)?'订单不存在':'状态不正确';
            return false;
        }
        $data['deliverymanid']=$did;
        $data['status']='2';
        $num=$this->where(array('id'=>$order['id']))->save($data);
        if($num){
            $dManModel->addTask($did,$order);
        }
        return $num;
    }

    //送达
    public function complete($cid,$id,$did=null)
    {
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid,deliverymanid')->find();
        if(empty($order)||$order['status']!='2'){
            $this->error=empty($order)?'订单不存在':'状态不正确';
            return false;
        }
        if(isset($did)){
            if($order['deliverymanid']!=$did){
                $this->error='送餐员不正确';
                return false;
            }
        }
        $dManModel=D('DishoutDeliveryman');
        $data['status']='3';
        if(in_array($order['paytype'],array('daofu','dianfu'))&&$order['paid']!='1'){
            $data['paid']='1';
        }
        $num=$this->where(array('id'=>$order['id']))->save($data);
        if($num){
            $dManModel->complete($order['deliverymanid'],$order['id']);
        }
        return $num;
    }

    //获取订单数量
    public function getOrderNum($cid,$status='0',$type='dishout')
    {
        $where=array();
        if($status!='') {
            $where['status']=$status;
        }
        $where['comefrom']=$type;
        $where['cid']=$cid;
        $where['isdel']=array('neq','1');
        return $this->where($where)->count();
    }

    //删除订单
    public function del($cid,$id,$real=false)
    {
        $where['cid']=$cid;
        if(!$real){
            $where['isdel']=array('neq','1');
        }
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $order=$this->where($where)->field('id,status')->find();
        if(empty($order)||($order['status']!='3'&&$order['status']!='4')){
            $this->error=empty($order)?'订单不存在':'状态不正确';
            return false;
        }
        if($real){
            $num=$this->where(array('id'=>$order['id']))->delete();
        }else{
            $num=$this->where(array('id'=>$order['id']))->save(array('isdel'=>'1'));
        }
        return $num;
    }


}