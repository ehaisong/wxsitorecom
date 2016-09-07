<?php
class DishModel extends XModel
{
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

    public function setWhere($get)
    {
        $where=array();
        $where['cid']=$get['cid'];
        $where['isopen']='1';
        $where['istakeout']='1';
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
     * @param $outset 外卖设置
     * @param null $endTime 最近的结束时间
     * @return int
     */
    public function isOpen($outset,&$endTime=null)
    {
        $now=date('Hi');
        $times=$outset['open_times']?json_decode($outset['open_times'],true):array();
        if(empty($outset['open_times'])){
            if(!empty($outset['zc_sdate'])||!empty($outset['zc_edate'])){
                $zc_sdate=$outset['zc_sdate']?date('H:i',$outset['zc_sdate']):'00:00';
                $zc_edate=$outset['zc_edate']?date('H:i',$outset['zc_edate']):'23:59';
                $times[]=array('start'=>$zc_sdate,'end'=>$zc_edate);
            }
            if(!empty($outset['wc_sdate'])||!empty($outset['wc_edate'])){
                $wc_sdate=$outset['wc_sdate']?date('H:i',$outset['wc_sdate']):'00:00';
                $wc_edate=$outset['wc_edate']?date('H:i',$outset['wc_edate']):'23:59';
                $times[]=array('start'=>$wc_sdate,'end'=>$wc_edate);
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
    public function getInfoOrSku($id,$spec='')
    {
        $info=$id;
        if(!is_array($info)){
            $info=$this->where(array('id'=>$id))->field('id,instock,price')->find();
        }
        $dishTimeout=D('DishTimeout');//超时订单
        $dishSkuModel=D('DishSku');
        if(!empty($spec)){
            $skuInfo=$dishSkuModel->where(array('product_id'=>$info['id'],'sku'=>sha1($spec)))->field('inventory,price')->find();
            $consume=$dishTimeout->where(array('pro_id'=>$info['id'],'pro_spec'=>sha1($spec),'expired'=>array('egt',time())))->sum('consume');
            $inventory=$skuInfo['inventory'];
            $price=$skuInfo['price'];
        }else{
            $price=$info['price'];
            $consume=$dishTimeout->where(array('pro_id'=>$info['id'],'expired'=>array('egt',time())))->sum('consume');
            $inventory=$info['instock'];
        }
        $last_inventory=$inventory-$consume;
        return array('price'=>$price,'inventory'=>$inventory,'last_inventory'=>$last_inventory);
    }
}