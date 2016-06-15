<?php
class BargainKanuserModel extends Model
{
    public function getList($token,$bargain_id,$orderid,$offset=0,$length=10)
    {
        $prefix=C('DB_PREFIX');
        $tabs["{$prefix}bargain_kanuser"]='kan';
        $tabs["{$prefix}userinfo"]='user';
        $where=array('kan.token'=>$token,'kan.bargain_id'=>$bargain_id,'kan.orderid'=>$orderid);
        $where['user.token']=$token;
        $where['_string']='user.wecha_id=kan.friend AND kan.token=user.token';
        $fields='user.portrait,user.wechaname,user.wecha_id,kan.addtime,kan.dao';
        $order='kan.addtime desc';
        $list=$this->table($tabs)->where($where)->field($fields)->order($order)->limit($offset,$length)->select();
        return $list;
    }
}