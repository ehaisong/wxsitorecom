<?php
class ComDishOrderModel extends XModel
{
    protected $tableName='dish_order';

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
    public function doRefund($data = array(),$refund_fee = '')
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
                        'ordername' 	=> '外卖退款金额',
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
                    if($add_record) {
                        $this->sendCardTmpMsg($info['wecha_id'],$refund_fee,$info['token'],$info['cardid'],$data['orderid']);
                        return array('result_code'=>'SUCCESS','msg'=>'充值成功');
                    }
                }
                break;
            default:
                return false;
                break;
        }
        return $refund;
    }

    protected function sendCardTmpMsg($wecha_id,$total,$token,$cardId,$orderNum)
    {
        $siteUrl=C('site_url');
        $href = $siteUrl . '/index.php?' . http_build_query(array('g' => 'Wap', 'm' => 'Card', 'a' => 'card', 'token' => $token, 'wecha_id' => $wecha_id, 'cardid' => $cardId));
        $model      = new templateNews();
        $dataKey    = 'TM00004';
        $dataArr    = array(
            'href'=>$href,
            'wecha_id'=> $wecha_id,
            'first'=> '您好！会员卡退款已到账',
            'reason'=> '外卖订单退款',
            'refund'=> $total.'元',
            'remark'=>'外卖订单：'.$orderNum.'已完成退款'
        );
        $model->sendTempMsg($dataKey,$dataArr);
    }

    //支付后消耗库存 $isSales是否计入月销量
    public function payConsume($orderId,$list,$isSales=true)
    {
        $timeoutModel=D('DishTimeout');
        $consumeList=$this->createConsumeList($list);
        $dishSkuModel=D('DishSku');
        $dishModel=D('Wap/Dish');
        foreach ($consumeList as $item){
            if(!empty($item['spec'])){
                $dishSkuModel->where(array('sku'=>sha1($item['spec']),'product_id'=>$item['id']))->setDec('inventory',$item['num']);
            }
            if($isSales){
                //不适合高并发
                $info=$dishModel->where(array('id'=>$item['id']))->field('id,m_sales,instock,m_name')->find();
                $upData=array();
                $upData['m_sales']=$info['m_name']==date('Ym')?$info['m_sales']+$item['num']:$item['num'];
                $upData['instock']=$info['instock']-$item['num'];
                if($info['m_name']!=date('Ym')){
                    $upData['m_name']=date('Ym');
                }
                $dishModel->where(array('id'=>$item['id']))->save($upData);
            }else{
                $dishModel->where(array('id'=>$item['id']))->setDec('instock',$item['num']);
            }
        }
        $timeoutModel->where(array('order_id'=>$orderId))->delete();
    }
}