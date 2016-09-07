<?php
class WapDishModel extends XModel
{
    protected $tableName='dish';

    public function getTotal($get)
    {
        return $this->setWhere($get)->count();
    }

    public function getList($get,$offset=0,$length=20)
    {
        $list=$this->setWhere($get)->setOrder($get)->limit($offset,$length)->select();
        $specModel=D('DishSpec');
        foreach ($list as &$item)
        {
            if(!empty($item['spec'])){
                $specInfo=$specModel->where(array('id'=>$item['spec']))->field('id,opts')->find();
                $opts=explode(',',$specInfo['opts']);
                $item['opts']=array();
                $instock=0;
                foreach ($opts as $opt){
                    $tmp=$this->getInfoOrSku($item,$opt);
                    $tmp['name']=$opt;
                    $tmp['product_id']=$item['id'];
                    $tmp['inventory']=$tmp['last_inventory'];//这里inventory其实指的是剩余库存量
                    unset($tmp['last_inventory']);
                    $item['opts'][]=$tmp;
                    $instock+=$tmp['inventory'];
                }
                $item['instock']=$instock;
            }else{
                $tmp=$this->getInfoOrSku($item);
                $item['instock']=$tmp['last_inventory'];
            }
        }
        return $list;
    }

    public function getJson($list)
    {
        $tmp=array();
        foreach ($list as $item){
            $arr=array('pro_id'=>$item['id'],'tit'=>$item['name'],'desc'=>$item['des'],'inventory'=>$item['instock']);
            if(!empty($item['opts'])){
                $arr['specifications']=array();
                foreach ($item['opts'] as $opt){
                    $spe=array();
                    $spe['text']=$opt['name'];
                    $spe['price']=$opt['price'];
                    $spe['copies']=1;
                    $spe['inventory']=$opt['inventory'];
                    $arr['specifications'][]=$spe;
                }
            }
            $arr['attribute']=!empty($item['attr'])?explode(',',$item['attr']):array();
            $tmp['pro_'.$item['id']]=$arr;
        }
        return json_encode($tmp);
    }

    //分组结构
    public function getGroupList(&$groups,$list)
    {
		$newGroups = array();
        if(!is_array($groups)&&!empty($groups)){
            $groups = M('Dish_sort')->where(array('cid' => $groups))->order("`sort` ASC")->select();
        }
        $groupList=array();
        foreach ($groups as &$type){
            $tmp=array('group_id'=>$type['id'],'group_name'=>$type['name'],'list'=>array());
            foreach ($list as $item){
                if($item['sid']==$type['id']){
                    $tmp['list'][]=$item;
                }
            }
            $type['total']=count($tmp['list']);
            if(!empty($tmp['list'])){
                $groupList[]=$tmp;
				$newGroups[] = $type;
            }
        }
		$groups = $newGroups;
        return $groupList;
    }

    public function setWhere($get)
    {
        $where=array();
        $where['cid']=$get['cid'];
        $where['isopen']='1';
        if($get['istakeout']!=''){
            $where['istakeout']=$get['istakeout'];
        }
        if($get['keyword']!=''){
            $where['name']=array('like','%'.$get['keyword'].'%');
        }
        return $this->where($where);
    }

    public function setOrder($get)
    {
        $order['sort']='asc';
        return $this->order($order);
    }

    /**
     * 判断营业状态
     * @param $outset 设置
     * @param null $endTime 最近的结束时间
     * @return int
     */
    public function isOpen($set,&$endTime=null)
    {
        $now=date('Hi');
        $times=$set['open_times']?json_decode($set['open_times'],true):array();
        if(empty($set['open_times'])){
            if(!empty($set['startTime1'])||!empty($set['endTime1'])){
                $startTime1=$set['startTime1']?date('H:i',$set['startTime1']):'00:00';
                $endTime1=$set['endTime1']?date('H:i',$set['endTime1']):'23:59';
                $times[]=array('start'=>$startTime1,'end'=>$endTime1);
            }
            if(!empty($set['startTime2'])||!empty($set['endTime2'])){
                $startTime2=$set['startTime2']?date('H:i',$set['startTime2']):'00:00';
                $endTime2=$set['endTime2']?date('H:i',$set['endTime2']):'23:59';
                $times[]=array('start'=>$startTime2,'end'=>$endTime2);
            }
        }
        foreach ($times as $item){
            $start=str_replace(':','',$item['start']);
            $end=str_replace(':','',$item['end']);
            if((int)$now>=(int)$start&&(int)$now<=(int)$end){
                $endTime=$item['end'];
                return 1;
            }
        }
        return 0;
    }

    //配送时间表
    public function deliverySchedule($outset)
    {
        $endTime='23:59';
        $this->isOpen($outset,$endTime);
        list($h,$i)=explode(':',$endTime);
        $end=mktime($h,$i,0);
        $unit=$outset['permin'];
        $time=ceil(time()/($unit*60))*($unit*60);
        $tmp=array();
        while ($time+($unit*60)<=$end){
            $time+=($unit*60);
            $tmp[]=date('H:i',$time);
        }
        return $tmp;
    }

    //获取一种规格商品的价格和剩余库存量和当前库存量
    public function getInfoOrSku($id,$spec='',$order_id='')
    {
        $info=$id;
        if(!is_array($info)){
            $info=$this->where(array('id'=>$id))->field('id,instock,price')->find();
        }
        $dishTimeout=D('DishTimeout');//超时订单
        $dishSkuModel=D('DishSku');
        $where=array('expired'=>array('egt',time()),'pro_id'=>$info['id']);
        if(!empty($order_id)){
            $where['order_id']=array('neq',$order_id);
        }
        if(!empty($spec)){
            $skuInfo=$dishSkuModel->where(array('product_id'=>$info['id'],'sku'=>sha1($spec)))->field('inventory,price')->find();
            $where['pro_spec']=sha1($spec);
            $consume=$dishTimeout->where($where)->sum('consume');
            $inventory=$skuInfo['inventory'];
            $price=$skuInfo['price'];
        }else{
            $price=$info['price'];
            $consume=$dishTimeout->where($where)->sum('consume');
            $inventory=$info['instock'];
        }
        $last_inventory=$inventory-$consume;
        return array('price'=>$price,'inventory'=>$inventory,'last_inventory'=>$last_inventory);
    }

    //获取 $myCompany我的店铺设置 $mainCompany主店设置
    public function getCompanySet($myCompany,$mainCompany)
    {
        $cid=$myCompany['cid'];//菜品cid
        $kconoff=$myCompany['kconoff'];//库存管理
        $discount=$myCompany['discount'];//折扣
        //主店开启了统一库存管理
        if (($mainCompany['cid'] != $myCompany['cid']) && ($mainCompany['dishsame'] == 1)) {
            $cid = $mainCompany['cid'];
            $kconoff = $mainCompany['kconoff'];
            $discount=$mainCompany['discount'];
        }
        return array('kconoff'=>$kconoff,'discount'=>$discount,'cid'=>$cid);
    }



}