<?php
class BargainOrderModel extends Model
{
    public function getTopList($token,$bargain_id,$length=10)
    {
        $prefix=C('DB_PREFIX');
        $tabs["{$prefix}bargain_order"]='order';
        $tabs["{$prefix}userinfo"]='user';
        $where=array('order.token'=>$token,'order.bargain_id'=>$bargain_id);
        $where['_string']='order.wecha_id=user.wecha_id AND order.token=user.token';
        $fields='user.portrait,user.wechaname,user.wecha_id,order.bargain_nowprice';
        $order['order.bargain_nowprice']='asc';
        $order['order.changetime']='asc';
        $result=$this->table($tabs)->where($where)->field($fields)->order($order)->limit(0,$length)->select();
        return $result;
    }
}