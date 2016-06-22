<?php
class BlacklistModel extends XModel
{
    public function __construct($name='', $tablePrefix='', $connection='')
    {
        parent::__construct($name, $tablePrefix, $connection);
        $this->_rules=array(
            'all'=>array(
                'deny'=>'create_time',
                'validate'=>array(
                    array('ips','regex','require','IP列表不能为空'),
                    array('ips','validateIps','','IP格式不正确'),
                    array('url','regex','require','URL不能为空'),
                    array('token','regex','require','TOKEN不能为空'),
                    array('limit_start,limit_end','c:validateTime','','开始时间不能小于或等于结束时间'),
                ),
                'assign'=>array(
                    array('limit_start','f:strtotime',null,self::VALUE_NOT_EMPTY),
                    array('limit_end','f:strtotime',null,self::VALUE_NOT_EMPTY),
                ),
            ),
            'add'=>array(
                'deny'=>'id',
                'must'=>'ips,url',
                'assign'=>array(
                    array('create_time','f:time',null,self::FIELD_BOTH),
                ),
            ),
            'update'=>array(
                'must'=>'id',
            )
        );
    }

    //验证开始和结束时间
    protected function validateTime($value,$field,$formData,$param)
    {
        if(empty($formData['limit_start'])&&empty($formData['limit_end']))
        {
            return true;
        }
        $limit_start=strtotime($formData['limit_start']);
        $limit_end=strtotime($formData['limit_end']);
        return $limit_end>$limit_start;
    }

    protected function validateIps($value,$field,$formData,$param)
    {
        $ips=$this->getIps($value);
        foreach ($ips as $val)
        {
            if(!validate_regex($val, 'ip'))
                return false;
        }
        return true;
    }

    public function getIps($data)
    {
        $data=preg_replace('/\r\n/',',',$data);
        $data=preg_replace('/\,{2,}/',',',$data);
        $data=preg_replace('/\s/','',$data);
        $arr=explode(',',$data);
        $list=array();
        for($i=0;$i<count($arr);$i++)
        {
            if(!empty($arr[$i]))
            {
                if(strpos($arr[$i],'-')===false)
                {
                    $list[]=$arr[$i];
                }
                else
                {
                    list($start,$end)=explode('-',$arr[$i]);
                    preg_match('/\.(\d{0,3})$/',$start,$startMatch);
                    preg_match('/\.(\d{0,3})$/',$end,$endMatch);
                    for($j=(int)$startMatch[1];$j<(int)$endMatch[1]+1;$j++)
                    {
                        $list[]=preg_replace('/\.(\d{0,3})$/','.'.$j,$start);
                    }
                }
            }
        }
        return $list;
    }

    public function getTotal($get)
    {
        return $this->setWhere($get)->count();
    }

    public function getList($get,$offset,$length)
    {
        $result=$this->limit($offset,$length)->setWhere($get)->setOrder($get)->select();
        foreach($result as &$value)
        {
            $arr=explode(',',$value['ips']);
            $len=count($arr);
            $value['ips_str']=$arr[0].($len>1?'等'.$len.'条':'');
        }
        return $result;
    }

    //设置查询条件
    private function setWhere($get)
    {
        $where=array();
        if($get['status']!="")
        {
            $where['status']=$get['status'];
        }
        if($get['keyword']!="")
        {
            if(validate_regex($get['keyword'],'ip'))
            {
                $where['ips']=array('like','%'.$get['keyword'].'%');
            }
            else
            {
                $com=array('remark'=>array('like','%'.$get['keyword'].'%'),'id'=>$get['keyword'],'_logic'=>'OR');
                $com['url']=array('like','%'.$get['keyword'].'%');
                $where['_complex']=$com;
            }
        }
        $where['token']=$get['token'];
        return $this->where($where);
    }

    //设置排序规则
    private function setOrder($get)
    {
        $order=array();
        if($get['_create_time']!="")
        {
            $order['create_time']=$get['_create_time'];
        }
        return $this->order($order);
    }



}