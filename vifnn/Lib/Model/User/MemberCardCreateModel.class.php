<?php
class MemberCardCreateModel extends XModel
{
    //获取记录总数
    public function getTotal($get)
    {
        return $this->setJoin()->setWhere($get)->count();
    }

    public function getTotalNoSearch($get)
    {
        return $this->setWhereNoSearch($get)->count();
    }

    //获取列表
    public function getList($get,$offset,$length)
    {
        $fields='create.id,create.cardid,create.token,create.number,create.wecha_id,create.is_bind,create.old_number,
        user.id uid,user.portrait,user.total_score,user.expensetotal,user.wechaname,user.truename,user.tel,user.sex,user.address,user.getcardtime,user.fakeopenid,
        user.regtime,user.balance';
        $result=$this->setJoin()->field($fields)->limit($offset,$length)->setWhere($get)->setOrder($get)->group("create.wecha_id")->select();
        foreach ($result as &$value)
        {
            $value['truename']=$value['truename']?$value['truename']:$value['wechaname'];
        }
        return $result;
    }

    public function getListNoSearch($get,$offset,$length)
    {
        $result=$this->limit($offset,$length)->setWhereNoSearch($get)->select();
        $userinfoModel=M('userinfo');
        $fields='id uid,portrait,total_score,expensetotal,wechaname,truename,tel,sex,address,getcardtime,fakeopenid,regtime,balance';
        for ($i=0;$i<count($result);$i++)
        {
            $info=$userinfoModel->where(array('wecha_id'=>$result[$i]['wecha_id'],'token'=>$get['token']))->field($fields)->find();
            $info['truename']=$info['truename']?$info['truename']:$info['wechaname'];
            $result[$i]=array_merge($result[$i],$info);
        }
        return $result;
    }

    //设置查询条件
    private function setWhere($get)
    {
        $where=array();
        $where['create.token']=$get['token'];
        $where['user.token']=$get['token'];
        $where['create.wecha_id']=array('neq','');
        if($get['itemid']!='')
        {
            $where['create.id']=$get['itemid'];
        }
        if($get['id']!='')
        {
            $where['create.cardid']=$get['id'];
        }
        if($get['searchkey']!='')
        {
            if(validate_regex($get['searchkey'],'mobile'))
            {
                $where['user.tel']=$get['searchkey'];
            }
            else
            {
                $where['user.truename|user.wechaname|create.number']=array('like','%'.$get['searchkey'].'%');
            }
        }
        return $this->where($where);
    }

    //设置查询条件
    private function setWhereNoSearch($get)
    {
        $where=array();
        $where['token']=$get['token'];
        $where['wecha_id']=array('neq','');
        if($get['itemid']!='')
        {
            $where['id']=$get['itemid'];
        }
        if($get['id']!='')
        {
            $where['cardid']=$get['id'];
        }
        return $this->where($where);
    }

    //设置排序规则
    private function setOrder($get)
    {
        $order=array();
        if($get['total_score']!="")
        {
            $order['total_score']=$get['total_score'];
        }
        if($get['expensetotal']!="")
        {
            $order['expensetotal']=$get['expensetotal'];
        }
        if($get['balance']!="")
        {
            $order['balance']=$get['balance'];
        }
        if($get['number']!="")
        {
            $order['number']=$get['number'];
        }
        return $this->order($order);
    }

    private function setJoin()
    {
        return $this->alias('`create`')->join('LEFT JOIN '.C('DB_PREFIX').'userinfo user ON create.wecha_id=user.wecha_id');
    }
}
?>
