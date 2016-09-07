<?php
class DishOrderModel extends XModel
{
    //创建购买清单,并检查库存等
    public function createList($post,$cid=null,$set=array())
    {
        $idArr=isset($post['pro_ids'])?$post['pro_ids']:array();
        $numArr=isset($post['pro_num'])?$post['pro_num']:array();
        $specArr=isset($post['pro_spec'])?$post['pro_spec']:array();
        $attrArr=isset($post['pro_attr'])?$post['pro_attr']:array();
        $list=array();
        if(!(count($idArr)==count($numArr)&&count($numArr)==count($specArr)&&count($specArr)==count($attrArr))){
            $this->error='订单数据不完整';
            return false;
        }
        foreach ($idArr as $index=>$id)
        {
            $num=$numArr[$index];
            $spec=$specArr[$index];
            $attr=$attrArr[$index];
            if(empty($id)){
                $this->error='商品不存在';
                return false;
            }
            if(empty($num)){
                $this->error='请选择购买数量';
                return false;
            }
            $pos=$this->indexOf($list,array('id'=>$id,'spec'=>$spec,'attr'=>$attr));
            if($pos<=-1){
                $list[]=array('id'=>$id,'spec'=>$spec,'attr'=>$attr,'num'=>0);
                $pos=count($list)-1;
            }
            $list[$pos]['num']+=$num;
        }
        if(empty($list)){
            $this->error='订单中商品列表为空或者没有足够的库存';
            return false;
        }
        return $this->checkList($list,$cid,$set);
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

    //检查库存等并且计算总价和总量
    public function checkList($list,$cid=null,$set=array(),&$error=null)
    {
        $totalPrice=0;$totalNum=0;$totalMealFee=0;
        $mealFee=isset($set['meal_fee'])?$set['meal_fee']:0;//餐盒费
        $discount=isset($set['discount'])?$set['discount']:1;//折扣0.1-1
        $kconoff=isset($set['kconoff'])?$set['kconoff']:'1';//是否开启库存管理
        $dishModel=D('Wap/Dish');
        //这一步主要是因为attr不一样订单不一样但是价格和库存一样的,所以不能合并
        $consumeList=$this->createConsumeList($list);
        $skuChart=array();//不同规格不同价格、库存、折扣、餐盒费对照表
        foreach ($consumeList as $item){
            $id=$item['id'];
            $spec=$item['spec'];
            $where=array('id'=>$id);
            if(isset($cid))$where['cid']=$cid;
            $info=$dishModel->where($where)->field('id,instock,name,price,isdiscount,is_meal,image,m_sales')->find();
            $info['did']=$info['id'];//兼容处理
            if(!$info){
                $error=$item;
                $this->error='商品不存在';
                return false;
            }
            $skuInfo=$dishModel->getInfoOrSku($info,$spec);
            $inventory=$skuInfo['inventory'];
            $price=$skuInfo['price'];
            $info['last_inventory']=$skuInfo['last_inventory'];
            if($kconoff=='1'&&$item['num']>($info['last_inventory'])){
                $error=$item;
                $this->error=$info['name'].($spec?$spec:'').'库存不足';
                return false;
            }
            if($info['isdiscount']=='1'){
                $info['discount']=$discount;
            }
            if($info['is_meal']=='1'){
                $info['meal_fee']=$mealFee;
            }
            $info['price']=$price;
            $skuChart[$id.'_'.$spec]=$info;
        }
        foreach ($list as &$item){
            $id=$item['id'];
            $spec=$item['spec'];
            $tmp=$skuChart[$id.'_'.$spec];
            $discount=isset($tmp['discount'])?$tmp['discount']:1;
            $meal_fee=isset($tmp['meal_fee'])?$tmp['meal_fee']:0;
            $item=array_merge($item,$tmp);
            $item['meal_fee']=round($meal_fee*$item['num'],2);
            $totalMealFee=round($totalMealFee+$item['meal_fee'],2);
            $disPrice=round($tmp['price']*$discount,2);
            $item['total']=round($disPrice*$item['num'],2)+$item['meal_fee'];
            $item['dis_price']=$disPrice;
            $item['discount']=$discount;
            $totalPrice+=$item['total'];
            $totalNum+=$item['num'];
        }
        //totalPrice包含了餐盒费
        return array('totalPrice'=>$totalPrice,'totalNum'=>$totalNum,'list'=>$list,'totalMealFee'=>$totalMealFee);
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

    //计算配送费
    public function getDeliveryFee($my_distance,$outset)
    {
        $delivery_fee_jia=0;
        if ($outset['overflow_radius'] == 5 && $outset['removing'] < $my_distance) {
            $delivery_fee_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
        }
        $delivery_fee_jia = $delivery_fee_jia < 0 ? 0 : $delivery_fee_jia;
        return ($outset['delivery_fee'] / 100) + $delivery_fee_jia;
    }

    //计算起步价
    public function getPricing($my_distance,$outset)
    {
        $pricing_jia=0;
        if ($outset['overflow_radius'] == 2 && $outset['removing'] < $my_distance) {
            $pricing_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
        }
        $pricing_jia = $pricing_jia < 0 ? 0 : $pricing_jia;
        return $outset['pricing'] + $pricing_jia;
    }

    //下单后消耗库存
    public function orderConsume($orderId,$list)
    {
        $timeout=C('DISH_ORDER_TIMEOUT');
        $timeoutModel=D('DishTimeout');
        $consumeList=$this->createConsumeList($list);
        foreach ($consumeList as $item){
            $data['order_id']=$orderId;
            $data['pro_id']=$item['id'];
            $data['consume']=$item['num'];
            $data['expired']=time()+$timeout;
            $data['pro_spec']=$item['spec']?sha1($item['spec']):'';
            $timeoutModel->add($data);
        }
    }

    //支付后消耗库存 $isSales是否计入月销量
    public function payConsume($orderId,$list)
    {
        $timeoutModel=D('DishTimeout');
        $consumeList=$this->createConsumeList($list);
        $dishSkuModel=D('DishSku');
        $dishModel=D('Dish');
        foreach ($consumeList as $item){
            if(!empty($item['spec'])){
                $dishSkuModel->where(array('sku'=>sha1($item['spec']),'product_id'=>$item['id']))->setDec('inventory',$item['num']);
            }
            $dishModel->where(array('id'=>$item['id']))->setDec('instock',$item['num']);
        }
        $timeoutModel->where(array('order_id'=>$orderId))->delete();
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

    public function cancel($cid,$id)
    {
        $where['cid']=$cid;
        $where['id']=$id;
        $order=$this->where($where)->field('id,orderid,paytype,token,wecha_id,cid,price,status,paid,info')->find();
        $list=unserialize($order['info']);
        if($order['status']!='0'){
            $this->error='商家已接单目前无法取消';
            return false;
        }
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

}