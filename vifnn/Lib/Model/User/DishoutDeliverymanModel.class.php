<?php
class DishoutDeliverymanModel extends XModel
{
    protected $relationMap=array(
        'default'=>array(
            'avatar'=>'avatar',
        ),
    );

    public function getTotal($get)
    {
        return $this->setWhere($get)->count();
    }

    public function getList($get,$offset=0,$length=10)
    {
        $length=$length<=0?10:($length>=30?30:$length);
        $list=$this->setWhere($get)->setOrder($get)->field('id,name,avatar,tel,task_wait,task_com')->limit($offset,$length)->select();
        return $list;
    }

    //设置条件
    private function setWhere($get)
    {
        $where=array();
        $where['cid']=$get['cid'];
        return $this->where($where);
    }

    //设置排序
    private function setOrder($get)
    {
        $order=array();
        if($get['addtime']!=''){
            $order['addtime']=$get['addtime'];
        }
        return $this->order($order);
    }

    //新增任务
    public function addTask($did,$order)
    {
        $data['task_wait']=array('exp','task_wait+1');
        $num=$this->where(array('id'=>$did))->save($data);
        return $num;
    }

    //送达
    public function complete($did,$order_id)
    {
        $data['task_com']=array('exp','task_com+1');
        $data['task_wait']=array('exp','task_wait-1');
        $num=$this->where(array('id'=>$did))->save($data);
        return $num;
    }

}