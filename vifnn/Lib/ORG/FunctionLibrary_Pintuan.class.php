<?php
class FunctionLibrary_Pintuan{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'拼团购',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('pintuan');
			$where	=array('token'=>$this->token);
			$items 	= $db->where($where)->order('id DESC')->select();

			$arr=array(
			'name'=>'拼团购',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['title'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['title'],'link'=>'{siteUrl}/index.php?g=Wap&m=Pintuan&a=index&token='.$this->token.'&wecha_id={wechat_id}&tid='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}


?>
