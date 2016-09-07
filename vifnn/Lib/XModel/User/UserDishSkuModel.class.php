<?php
class UserDishSkuModel extends XModel
{
    protected $tableName='dish_sku';
    //添加sku
    public function addSku($product_id,$opts,$values)
    {
        $tmp=array();
        foreach ($opts as $opt){
            $sha1=sha1($opt);
            $data['price']=$values[$opt]['price'];
            $data['inventory']=$values[$opt]['inventory'];
            $data['refresh_inve']=$values[$opt]['refresh_inve']?$values[$opt]['refresh_inve']:0;
            $data['sku']=$sha1;
            $data['product_id']=$product_id;
            $id=$this->add($data);
            if(!$id)
                return false;
            $tmp[$opt]=$id;
        }
        return $tmp;
    }

    //检查sku
    public function checkSku($opts,$values)
    {
        $minPrice=null;
        $totalInstock=0;
        foreach ($opts as $opt){
            $price=$values[$opt]['price'];
            $inventory=$values[$opt]['inventory'];
            $refresh_inve=$values[$opt]['refresh_inve']?$values[$opt]['refresh_inve']:0;
            if(empty($price)||$price=='0'||!preg_match('/^\d+(\.\d+)?$/',$price)){
                $this->error=$opt.'价格填写不正确';
                return false;
            }
            if(empty($inventory)||!preg_match('/^\d+$/',$inventory)){
                $this->error=$opt.'在售库存填写不正确';
                return false;
            }
            if(!empty($refresh_inve)&&!preg_match('/^\d+$/',$refresh_inve)){
                $this->error=$opt.'刷新库存填写不正确';
                return false;
            }
            $minPrice=!isset($minPrice)?$price:min($minPrice,$price);
            $totalInstock+=$inventory;
        }
        return array('instock'=>$totalInstock,'price'=>$minPrice);
    }

    //更新sku
    public function updateSku($product_id,$opts,$values, $newSpec, $oldSpec)
    {
		if ($newSpec != $oldSpec) {
			$this->delSku($product_id);
			return $this->addSku($product_id, $opts, $values);
		}
        $num=0;
        foreach ($opts as $opt){
            $sha1=sha1($opt);
            $data['price']=$values[$opt]['price'];
            $data['inventory']=$values[$opt]['inventory'];
            $data['refresh_inve']=$values[$opt]['refresh_inve']?$values[$opt]['refresh_inve']:0;
            $res=$this->where(array('product_id'=>$product_id,'sku'=>$sha1))->save($data);
            if($res){
                $num++;
            }
        }
        return $num;
    }

    //删除sku
    public function delSku($product_id)
    {
        $res=$this->where(array('product_id'=>$product_id))->delete();
        return $res;
    }

    public function getSku($product_id,$spec)
    {
        $specInfo=M('dish_spec')->where(array('id'=>$spec))->find();
        $opts=explode(',',$specInfo['opts']);
        $tmp=array();
        foreach ($opts as $opt){
            $sha1=sha1($opt);
            $tmp[$opt]=$this->where(array('product_id'=>$product_id,'sku'=>$sha1))->find();
        }
        return $tmp;
    }


}