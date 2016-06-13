<?php
class VoteModel extends Model {

	//自动验证
	protected $_validate = array(

			array('keyword','require','关键词不能为空',1),
			array('title','require','投票标题不能为空',1),
			array('info','require','投票说明不能为空',1),
	 );

	function checkdate(){
		 if(strtotime($_POST['enddate'])<strtotime($_POST['statdate'])){
			 return false;
		}else{
			return true;
		}
	}


}