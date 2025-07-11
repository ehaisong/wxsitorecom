<?php
class HardwareAction extends UserAction{
	public function _initialize() {
		parent::_initialize();

	}
	public function wifi(){
		$this->assign('tab','wifi');
		$this->display();
	}
	public function photoprint(){
		if (IS_POST){
			D('Wxuser')->where(array('token'=>$this->token))->save(array('freephotocount'=>intval($_POST['freephotocount']),'openphotoprint'=>intval($_POST['openphotoprint'])));
			S('wxuser_'.$this->token,NULL);
			$this->success('设置成功');
		}else {
			$this->wxuser=D('Wxuser')->where(array('token'=>$this->token))->find();
			$this->assign('info',$this->wxuser);
			$this->assign('tab','photoprint');
			$this->display();
		}
	}
	public function orderprint(){
		$info=M('Orderprinter')->where(array('token'=>$this->token))->select();
		$this->assign('info',$info);
		$this->assign('tab','orderprint');
		$this->display();
	}
	
	public function orderprintSet()
	{
		$id=intval($_GET['id']);
		$kitchens = array();
		if ($id){
			$thisPrinter=M('Orderprinter')->where(array('token'=>$this->token,'id'=>$id))->find();
			$kitchens = D('Dish_kitchen')->where(array('cid' => $thisPrinter['companyid']))->select();
		}else {
			$thisPrinter=array();
		}
		$this->assign('kitchens', $kitchens);
		$this->assign('info', $thisPrinter);
		$company_db = M('Company');
		$companys = $company_db->where(array('token'=>$this->token))->order('id ASC')->select();
		$this->assign('companys',$companys);
		//
		
		
		
		if ($id){
			$selectedModules=explode(',',$thisPrinter['modules']);
		}else {
			$selectedModules=array();
		}
		$modules=$this->_modules();
		$moduleStr='';
		foreach ($modules as $k=>$m){
			$ckStr='';
			if (in_array($k,$selectedModules)){
				$ckStr=' checked';
			}
			$moduleStr.='<input name="module_'.$k.'" value="'.$k.'" type="checkbox"'.$ckStr.'>&nbsp;'.$m.'</label>&nbsp;&nbsp;';
		}
		$this->assign('moduleStr',$moduleStr);
		//
		if (IS_POST){
			$row=array('token'=>$this->token);
			$_POST['token']=$this->token;
			$_POST['count']=intval($_POST['count']);
			$_POST['mkey']=trim($_POST['mkey']);
			$_POST['mcode']=trim($_POST['mcode']);
			$_POST['qr'] = trim($_POST['qr']);
			if (!strstr($_POST['qr'],'%')){ //已经转码
			  $qb=str_replace('{siteUrl}',C('site_url'),$_POST['qr']);
			  $qb=str_replace('&wecha_id={wechat_id}','',$qb);
			  $_POST['qr'] = urlencode($qb);
			}
			$mstr='';
			$comma='';
			foreach ($modules as $k=>$m){
				if (isset($_POST['module_'.$k])){
					$mstr.=$comma.$k;
					$comma=',';
				}
			}
			$_POST['modules']=$mstr;
			if ($id){
				M('Orderprinter')->where(array('token'=>$this->token,'id'=>$id))->save($_POST);
			}else {
				M('Orderprinter')->add($_POST);
			}
			$this->success('设置成功',U('Hardware/orderprint'));
		}else {
			$this->assign('tab','orderprint');
			$this->display();
		}
	}
	public function orderprintDelete(){
		$id=intval($_GET['id']);
		$rt=M('Orderprinter')->where(array('token'=>$this->token,'id'=>$id))->delete();
		if ($rt){
			$this->success('删除成功',U('Hardware/orderprint'));
		}else {
			$this->error('删除失败',U('Hardware/orderprint'));
		}
	}
	function _modules(){
		return array(
		'Store'=>'商城',
		'Repast'=>'餐饮',
		'Hotel'=>'酒店',
		'DishOut'=>'微外卖',
		'Numqueue'=>'微排号',
		'Medical'=>'微医疗',
		'Business'=>'KTV',
		 'Card' => '会员卡充值'
		);
	}
	
	public function getkitchen()
	{
		$companyid = isset($_POST['companyid']) ? intval($_POST['companyid']) : 0;
		$kitchens = D('Dish_kitchen')->where(array('cid' => $companyid))->select();
		if ($kitchens) {
			exit(json_encode(array('error' => 0, 'list' => $kitchens)));
		} else {
			exit(json_encode(array('error' => 1)));
		}
		
	}
	
}
function curl_get($url){  
    set_time_limit(80); //设置超时时间 秒计算 
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);           //设置超时   
   // curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);   //用户访问代理 User-Agent   
   // curl_setopt($ch, CURLOPT_REFERER,_REFERER_);        //设置 referer   
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
    $r = curl_exec($ch);   
    curl_close($ch);   
    return $r;   
} 
?>