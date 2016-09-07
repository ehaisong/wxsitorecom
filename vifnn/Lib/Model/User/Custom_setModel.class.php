<?php

class Custom_setModel extends Model
{
	protected $_validate = array(
		array("keyword", "require", "关键词不能为空", 3),
		array("title", "require", "图文标题不能为空", 3),
		array("address", "require", "位置信息不能为空", 3),
		array("longitude", "require", "经度不能为空", 3),
		array("latitude", "require", "纬度不能为空", 3)
		);
}


?>
