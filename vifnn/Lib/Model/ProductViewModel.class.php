<?php
/**
 * 商品视图
 * User: vifnn_21
 * Date: 2014/12/29
 * Time: 13:11
 */

class ProductViewModel extends ViewModel
{
    public $viewFields = array(
        'Product' => array('*'),
        'DistributorProduct' => array('did','_on'=>'Product.id = DistributorProduct.pid'),
    );
}