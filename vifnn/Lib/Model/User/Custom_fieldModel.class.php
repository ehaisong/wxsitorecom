<?php 
class Custom_fieldModel extends Model {

	//自动验证
	protected $_validate = array(	
			array('field_name','require','字段名称不能为空'),
			array('field_type','require','输入类型不能为空'),
		array('field_name','1,9','字段名称不能超过9个字符',0,'length'),
	 );


}