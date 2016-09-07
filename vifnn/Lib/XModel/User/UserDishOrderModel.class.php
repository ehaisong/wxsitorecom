<?php
XD('Com/DishOrder',true);
class UserDishOrderModel extends ComDishOrderModel
{
    private $fieldsArr=array(
        'a1'=>'id,cid,tel,orderid,name,deliverymanid,paid,paytype,price,time,reservetime,address,status,total,offer_list'
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
        $dMan=XD('User/DishoutDeliveryman');
        $companyModel=D('Company');
        foreach ($list as &$item){
            $item['status']=(int)$item['status'];
            $item['paid']=(int)$item['paid'];
            if(!empty($item['deliverymanid'])){
                $item['deliveryman']=$dMan->where(array('id'=>$item['deliverymanid']))->field('id,name,tel,avatar')->find();
            }
            if($get['cid']==''){
                $item['company']=$companyModel->where(array('id'=>$item['cid']))->field('id,name,tel')->find();
            }
            if(!empty($item['offer_list'])){
                $item['offer_list']=json_decode($item['offer_list'],true);
                foreach ($item['offer_list'] as &$offer){
                    $offer['name']=enum_list('offer_name',$offer['type'],'其他优惠');
                }
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
            $where['status']=$get['type'];
        }
        if($get['status']!=''){
            $where['status']=$get['status'];
        }
        //外卖员不需要看到待接单
        if($get['groupId']=='2'&&empty($where['status'])){
            $where['status']=array('in',array('1','2','3','4'));
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
        foreach ($result['pro_list'] as &$pro){
            foreach ($pro['offer_list'] as &$offer){
                $offer['name']=enum_list('offer_name',$offer['type'],'其他优惠');
            }
        }
        unset($result['info']);
        $dMan=XD('User/DishoutDeliveryman');
        if(!empty($result['deliverymanid'])){
            $result['deliveryman']=$dMan->where(array('id'=>$result['deliverymanid']))->field('id,name,tel,avatar')->find();
        }
        $result['status']=(int)$result['status'];
        $result['paid']=(int)$result['paid'];
        if(!empty($result['offer_list'])){
            $result['offer_list']=json_decode($result['offer_list'],true);
            foreach ($result['offer_list'] as &$offer){
                $offer['name']=enum_list('offer_name',$offer['type'],'其他优惠');
            }
        }
        return $result;
    }

    //取消订单
    public function cancel($cid,$id)
    {
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid,info,deliverymanid')->find();
        $list=unserialize($order['info']);
        if($order['paid']=='1'){
            $res=$this->doRefund($order,$order['price']);
            if($res['result_code']!=='SUCCESS'){
                $this->error='退款失败';
                return false;
            }
            $this->where(array('id'=>$order['id']))->save(array('paid'=>'2'));
        }
        //恢复库存
        if($order['paid']=='1'){
            $this->payRecovery($list);
        }else{
            $this->orderRecovery($order['id']);
        }
        $num=$this->where(array('id'=>$order['id']))->save(array('status'=>'4'));
        if($num&&$order['status']=='2'){
            $dManModel=XD('User/DishoutDeliveryman');
            $dManModel->cancelWait($order['deliverymanid'],$order);
        }
        return $num;
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
        $dManModel=XD('User/DishoutDeliveryman');
        $dMan=$dManModel->where(array('cid'=>$cid,'id'=>$did))->field('id,name,tel')->find();
        if(!$isDMan&&empty($dMan)){
            $this->error='送餐员不存在';
            return false;
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
            $this->sendAssignTplMsg($order,$dMan);
        }
        return $num;
    }

    //发送配送模板消息
    protected function sendAssignTplMsg($order,$dMan)
    {
        $siteUrl=C('site_url');
        $model = new templateNews();
        return $model->sendTempMsg('OPENTM202521011', array('href' => $siteUrl . U('Wap/DishOut/myOrder', array('token' => $order['token'], 'wecha_id' => $order['wecha_id'], 'cid' => $order['cid'])), 'wecha_id' => $order['wecha_id'], 'first' => '外卖配送提醒', 'keyword1' => $order['orderid'], 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '您的外卖已送出，配送员：'.$dMan['name']."({$dMan['tel']})"));
    }

    //送达
    public function complete($cid,$id,$did=null)
    {
        $where['cid']=$cid;
        $where[preg_match('/^\d+$/',$id)?'id':'orderid']=$id;
        $where['isdel']=array('neq','1');
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid,deliverymanid,info')->find();
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
        $dManModel=XD('User/DishoutDeliveryman');
        $data['status']='3';
        if(in_array($order['paytype'],array('daofu','dianfu'))&&$order['paid']!='1'){
            $data['paid']='1';
            $infoList=unserialize($order['info']);
            if(!empty($infoList)){
                $this->payConsume($order['id'],$infoList);
            }
        }
        $num=$this->where(array('id'=>$order['id']))->save($data);
        if($num){
            $dManModel->complete($order['deliverymanid'],$order['id']);
        }
        return $num;
    }

    //获取订单数量
    public function getOrderNum($cid,$status='0',$did=null,$date='',$type='dishout')
    {
        $where=array();
        if($status!='') {
            $where['status']=$status;
        }
        if($date!=''){
            $where['date']=$date;
        }
        $where['comefrom']=$type;
        $where['cid']=$cid;
        $where['isdel']=array('neq','1');
        if(isset($did)){
            $where['deliverymanid']=$did;
        }
        return $this->where($where)->count();
    }

    //删除订单(关闭订单)
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