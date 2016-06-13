<?php
class CustomAction extends WapAction{
	public $token;
	public $wecha_id;
	public $thisForm;
	private $field_db;
	private $info_db;
	private $limit_db;
	public  $isamap;
	public  $amap;

	public function __construct(){
		parent::_initialize();
		$this->field_db		= M('custom_field');
		$this->info_db		= M('custom_info');
		$this->limit_db		= M('custom_limit');
		$this->token		= $this->_get('token');
		if (!defined('RES')){
			define('RES',THEME_PATH.'common');
		}
		//$this->wecha_id		= $this->wecha_id;
		if (!$this->wecha_id){
			$this->wecha_id	= 'null';
		}

		$this->thisForm 	= M('custom_set')->where(array('token'=>$this->token,'set_id'=>$this->_get('id','intval')))->find();

		$this->assign('token',$this->token);
		$this->assign('thisForm',$this->thisForm );
		$this->assign('wecha_id',$this->wecha_id);

		if (C('baidu_map')){
			$this->isamap=0;
		}else {
			$this->isamap=1;
			$this->amap=new amap();
		}
	}

	public function index(){
		$set_id 	= $this->_get('id','intval');
		$formData 	= $this->_createForms($this->token,$set_id);
		$setModel=M('custom_set');
		$setInfo    = $setModel->where(array('set_id'=>$set_id))->field('set_id,follow,sms,tel')->find();
		if(IS_POST){
			if($setInfo['follow'] =='1' && $this->isSubscribe() == false)
			{
				$this->error('请关注公众号后再提交');
			}
			$limit_info = $this->limit_db->where(array('limit_id'=>$this->thisForm['limit_id']))->find();
			if($limit_info['enddate']){
				if($limit_info['enddate']<time()){
					$this->error('抱歉，时间已过期，无法提交');
				}
			}

           //
            if($limit_info['apiece_total'] >0){
				$total 		= $this->info_db->where(array('token'=>$this->token,'set_id'=>$this->thisForm['set_id'],'wecha_id'=>$this->wecha_id))->count();
				if($total >= $limit_info['apiece_total']){
					$this->error('抱歉，每人只能提交'.$limit_info['apiece_total'].'次');
				}
			}
           //

			if($limit_info['today_total'] >0){
				$time 		= strtotime(date('Y-m-d')); //凌晨时间
				$total 		= $this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$this->thisForm['set_id'],'add_time'=>array('gt',$time)))->count();
				if($total >= $limit_info['today_total']){
					$this->error('抱歉，今日只能提交'.$limit_info['today_total'].'次');
				}
			}		

			if($limit_info['sub_total'] >0){
				$total 		= $this->info_db->where(array('token'=>$this->token,'set_id'=>$this->thisForm['set_id']))->count();
				//dump($total);   ,'wecha_id'=>$this->wecha_id  jm73com
				if($total >= $limit_info['sub_total']){
					$this->error('抱歉，提交总数已经超过'.$limit_info['sub_total'].'次');
				}
			}

			$data['token']		= $this->token;
			$data['wecha_id']	= $this->wecha_id;
			$data['set_id']		= $set_id;
			$data['add_time']	= time();		
			$data['user_name']	= empty($this->fans['wechaname'])?'匿名':$this->fans['wechaname'];
			$data['phone']		= empty($this->fans['tel'])?'匿名':$this->fans['tel'];
			$array = array();
				foreach ($this->_request() as $key => $value) {
					if ($value == '') {
						$array[] = array($key => '未填写');
					}
			 	}

			$arr = array();
			foreach ($array as $k => $v) {
				foreach ($v as $key => $value) {
					$arr[$key] = $value;
				}	
			}
			$b = $this->_request();
			$request = array_merge($b,$arr);
			$data['sub_info'] = $this->_serializeSubInfo($request,$set_id);
			$id_info = $this->info_db->add($data);
			if($id_info > 0){
			
//VIFNN增加邮件通知			
				$info=M('deliemail')->where(array('token'=>$this->token))->find();
				$mail->CharSet = 'UTF-8';
				$emailstatus=$info['wn'];
				$emailreceive=$info['receive'];
				$content = $this->sms();
				if($info['type'] == 1)
				{
					$emailsmtpserver=$info['smtpserver'];
					$emailport=$info['port'];
					$emailsend=$info['name'];
					$emailpassword=$info['password'];
				}
				else
				{
					$emailsmtpserver=C('email_server');
					$emailport=C('email_port');
					$emailsend=C('email_user');
					$emailpassword=C('email_pwd');
				}
				$emailuser=explode('@',$emailsend);
				$emailuser=$emailuser[0];
				if ($emailstatus == 1) 
				{
					if ($content) 
					{
						date_default_timezone_set('PRC');
						require("class.phpmailer.php");
						$mail = new PHPMailer();
						$mail->IsSMTP();
						$mail->Host = "$emailsmtpserver";
						$mail->SMTPAuth = true;
						$mail->Username = "$emailuser";
						$mail->Password = "$emailpassword";
						$mail->From = $emailsend;
						$mail->FromName = C('site_name');
						$mail->AddAddress("$emailreceive","商户");
						$mail->AddReplyTo($emailsend,"Information");
						$mail->WordWrap = 50;
						$mail->IsHTML(false);
						$mail->Subject = '您有新的订单';
						$mail->Body = $content;
						$mail->AltBody = "";
						if(!$mail->Send()) 
						{
							echo "Message could not be sent. <p>";
							echo "Mailer Error: ".$mail->ErrorInfo;
							exit;
						}
					}
				}			
//VIFNN增加邮件通知					
			
				if($setInfo['sms']=='1')
					Sms::sendSms($this->token, '你的表单“'.$this->thisForm['title'].'”中有新的信息',$setInfo['mp']); //发送商家短信 mp才是手机  jm73com
				if($this->thisForm['is_pay'] == 1){
					$spoor = $this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id))->count();
					$order_count = M('custom_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'paid'=>1))->count();
					if($order_count <= $spoor){
						$this->redirect('Wap/Custom/topay',array('token'=>$this->token,'id'=>$set_id,'infoid'=>$id_info));exit;
					}
				}
				$this->success($this->thisForm['succ_info']);
			}else{
				$this->error($this->thisForm['err_info']);
			}
		}else{
			//没有关注
			if($setInfo['follow'] =='1' && $this->isSubscribe() == false)
			{
				$follow_msg = (!empty($setInfo['follow_msg'])) ? $setInfo['follow_msg'] : '';
				$custom_url = (!empty($setInfo['follow_url'])) ? $setInfo['follow_url'] : '';
				$custom_btn_msg = (!empty($setInfo['follow_btn_msg'])) ? $setInfo['follow_btn_msg'] : '';
				$this->memberNotice($follow_msg,1,$custom_url,$custom_btn_msg);
			}
			$orders = M('custom_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'state'=>array('gt',0),'paid'=>0))->select();
			foreach($orders as $orv){
				$this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'info_id'=>$orv['state']))->delete();
				M('custom_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'id'=>$orv['id']))->delete();
			}
			//提交记录
			$spoor = $this->info_db->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id))->count();
			$this->assign('spoor',$spoor);
			if($this->thisForm['is_pay'] == 1){
				$order_count = M('custom_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'paid'=>1))->count();
				$this->assign('order_count',$order_count);
			}
			$this->assign('verify',$formData['verify']);
			$this->assign('formData',$formData['string']);
			$this->assign('set_id',$set_id);
			$this->assign('token',$this->token);
			$this->display();
		}
	}

	//上传文件
	public function uploadFile()
	{
		$set_id=$this->_post('set_id');
		$field_id=$this->_post('field_id');
		if(empty($set_id))
		{
			$this->ajaxReturn(array('info'=>'表单不存在','status'=>0));
		}
		$customFieldModel=M('custom_field');
		$result=$customFieldModel->where(array('field_id'=>$field_id,'set_id'=>$set_id))->field('file_type,file_size,file_num')->find();
		if(empty($result))
		{
			$this->ajaxReturn(array('info'=>'表单不存在','status'=>0));
		}
		if(empty($_FILES['file']))
		{
			$this->ajaxReturn(array('info'=>'上传文件不存在','status'=>0));
		}
		$upload = new UploadFile();
		$upload->maxSize = (int)$result['file_size']*1024*1024;
		$upload->autoSub=true;
		$upload->savePath =  './uploads/custom/'.$this->token.'/';// 设置附件上传目录
		if(!file_exists('./uploads/custom/'.$this->token.'/'))
		{
			mkdir('./uploads/custom/'.$this->token.'/',0777,true);
		}
		$upload->allowExts=array('jpg','jpeg','png','gif');
		if(!$upload->upload())
		{
			$this->ajaxReturn(array('status'=>0,'info'=>$upload->getErrorMsg()));
		}
		$info=$upload->getUploadFileInfo();
		$image = new Image();
		$imgPath=$info[0]['savepath'].$info[0]['savename'];
		$dirName=dirname($imgPath);
		$imgName=basename($imgPath);
		if(!file_exists($dirName.'/200_200'))
			mkdir($dirName.'/200_200',0777,true);
		$image->thumb2($imgPath,$dirName.'/200_200/'.$imgName,'',200,200);
		if(!file_exists($dirName.'/650_950'))
			mkdir($dirName.'/650_950',0777,true);
		$image->thumb($imgPath,$dirName.'/650_950/'.$imgName,'',650,950);
		$path=$this->siteUrl.ltrim($info[0]['savepath'],'.').$info[0]['savename'];
		$this->ajaxReturn(array('status'=>1,'info'=>'上传成功','data'=>$path));
	}
	
	
//VIFNN增加邮件通知	
	public function sms()
	{
		$str="您有新的订单产生，请注意查看万能表单里的订单，以便及时处理订单";
		return $str;
	}	
//VIFNN增加邮件通知		
	
	//执行支付
	public function topay(){
		$set_id = $this->_get('id','intval');
		
		M('custom_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'paid'=>0))->delete();
		$add_order['token'] = $this->token;
		$add_order['wecha_id'] = $this->wecha_id;
		$add_order['set_id'] = $set_id;
		$add_order['price'] = $this->thisForm['price']/100;
		$add_order['addtime'] = time();
		$add_order['state'] = $_GET['infoid'];
		$id_order = M('custom_order')->add($add_order);
		$orderid = $id_order.'CUSTOM'.time();
		M('custom_order')->where(array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id,'id'=>$id_order))->save(array('orderid'=>$orderid));
		$this->redirect("Alipay/pay",array("token"=>$this->token,"price"=>$add_order['price'],"wecha_id"=>$this->wecha_id,"from"=>"Custom","orderid"=>$orderid,'single_orderid'=>$orderid,'notOffline'=>1));
	}
	
	public function payReturn(){
		$order = M('custom_order')->where(array('token'=>$this->token,'orderid'=>$_GET['orderid']))->find();
		if($order['paid'] == 1){
			$this->success('提交成功',U('Wap/Custom/index',array('token'=>$this->token,'id'=>$order['set_id'])));
		}else{
			$this->error('支付失败',U('Wap/Custom/index',array('token'=>$this->token,'id'=>$order['set_id'])));
		}
	}


	/*表单详情简介*/
	public function detail()
	{
		$this->display();
	}

	/*表单提交记录*/
	public function spoor(){
		$set_id = $this->_get('id','intval');
		if(empty($this->wecha_id)||$this->wecha_id=='null')
		{
			$this->error('暂无提交记录');
		}
		$where 	= array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'set_id'=>$set_id);
		$list	= $this->info_db->where($where)->order('add_time desc')->select();
		$customReply=M('custom_reply');
		foreach($list as $key=>$value){
			$replyWhere['info_id']=$value['info_id'];
			$replyWhere['token']=$value['token'];
			$list[$key]['reply_info']=$customReply->where($replyWhere)->order('create_time desc')->find();
			$list[$key]['reply_num']=$customReply->where($replyWhere)->count();
			$sub_info	= unserialize($value['sub_info']);
			for($i=0;$i<count($sub_info);$i++)
			{
				if($sub_info[$i]['type']=='image')
				{
					if(empty($sub_info[$i]['value'])||$sub_info[$i]['value']=='未填写')
					{
						$sub_info[$i]['value']='未上传';
					}
					else
					{
						$tmp=explode(',',$sub_info[$i]['value']);
						for($j=0;$j<count($tmp);$j++)
						{
							$tmp[$j]=array('path'=>$tmp[$j]);
							$tmp[$j]['name']=basename($tmp[$j]);
						}
						$sub_info[$i]['value']=$tmp;
					}
				}
			}
			$list[$key]['sub_info']=$sub_info;
		}
		$this->assign('set_id',$set_id);
		$this->assign('list',$list);
		$this->display();
	}

	public function reply()
	{
		$info_id = $this->_get('info_id','intval');
		if(empty($this->wecha_id)||$this->wecha_id=='null')
			$this->error('提交记录不存在');
		$where 	= array('wecha_id'=>$this->wecha_id,'info_id'=>$info_id);
		$result= $this->info_db->where($where)->field('info_id,token')->find();
		if(!$result)
			$this->error('提交记录不存在');
		$customReply=M('custom_reply');
		$replyWhere['info_id']=$result['info_id'];
		$replyWhere['token']=$result['token'];
		$list=$customReply->where($replyWhere)->order(array('create_time'=>'desc'))->select();
		$this->assign('_list',$list);
		$this->display();
	}


	/*地图位置*/
	public function maps(){	
		$this->apikey	= C('baidu_map_api');
		$this->assign('apikey',$this->apikey);

		if (!$this->isamap){
			$this->display();
		}else {			
			$link=$this->amap->getPointMapLink($this->thisForm['longitude'],$this->thisForm['latitude'],$this->thisForm['address']);
			header('Location:'.$link);
		}
	}
	/*创建序列化提交信息*/
	private function _serializeSubInfo($post,$set_id){
		$field_info = $this->field_db->where(array('token'=>$this->token,'set_id'=>$set_id))->field('field_name,item_name,field_type')->order('sort desc')->select();
		$info 		= array();
		foreach($field_info as $key=>$value){
			$info[$key]['name'] 	= $value['field_name'];
			$info[$key]['type']     =$value['field_type'];
			if($value['field_type'] == 'checkbox'){
				$info[$key]['value']	= implode(',', $post[$value['item_name']]);
			}else{
				$info[$key]['value']	= $post[$value['item_name']];
			}
		}
		return serialize($info);
	}

	/*获取自定义表单字段信息*/
	private function _createForms($token,$set_id){

		$where	= array('token'=>$token,'set_id'=>$set_id,'is_show'=>'1');
		$forms 	= $this->field_db->where($where)->order('sort desc')->select();

		$str	= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">';
		$arr 	= array();
		foreach($forms as $key=>$value){
			$str	.= '<tr><th>';
			$str    .=$value['is_empty']=='1'?'<div style="color: red;display: inline-block;">*&nbsp;</div>':'';
			$str	.= $value['field_name'];
			$str 	.= '</th><td>';
			$str	.= $this->_getInput($value);
			$str	.= '</td></tr>';

			$arr[] 	 = array('id'=>$value['item_name'],'name'=>$value['field_name'],'type'=>$value['field_type'],'match'=>$value['field_match'],'is_empty'=>$value['is_empty'],'err_info'=>$value['err_info']);  //js验证信息
		}
		$str	.= '</table>';
		return array('string'=>$str,'verify'=>$arr);
	}

	/*获取自定义表单*/
	private function _getInput($value){
		$input 	= '';
		switch($value['field_type']){
			case 'text':
				$input 	.= '<input type="text" class="px" id="'.$value['item_name'].'" name="'.$value['item_name'].'" value="">';
				break;
			case 'textarea':
				$input 	.= '<textarea name="'.$value['item_name'].'" id="'.$value['item_name'].'" rows="4" cols="25"  ></textarea>';
				break;
			case 'checkbox':
				$option = explode('|', $value['filed_option']);
				for($i=0;$i<count($option);$i++){
					$input 	.= '<input type="checkbox" name="'.$value['item_name'].'[]" id="'.$value['item_name'].'" value="'.$option[$i].'"  />'.$option[$i];
				}
				break;
			case 'radio':
				$option = explode('|', $value['filed_option']);
				for($i=0;$i<count($option);$i++){
					$checked = $i==0?'checked=true':'';
					$input 	.= '<input type="radio" name="'.$value['item_name'].'" id="'.$value['item_name'].'" value="'.$option[$i].'" '.$checked.' />'.$option[$i];
				}
				break;
			case 'select':
				$input 	.= '<select name="'.$value['item_name'].'" id="'.$value['item_name'].'"><option value="">请选择..</option>';
				$op_arr	= explode('|',$value['filed_option']);
				$num	= count($op_arr);
				if($num > 0){
					for($i=0;$i<$num;$i++){
						$input 	.= '<option value="'.$op_arr[$i].'">'.$op_arr[$i].'</option>';
					}
				}
				$input  .='</select>';
				break;
			case 'date':
				$input 	.= '<input type="text" class="px" name="'.$value['item_name'].'" id="'.$value['item_name'].'" value="'.date('Y-m-d',time()).'" onClick="WdatePicker()"/>';
				break;
			case 'image':
				$input .='<div class="uploader_list" field_id="'.$value['field_id'].'" file_num="'.$value['file_num'].'" file_size="'.$value['file_size'].'"><input type="hidden" id="'.$value['item_name'].'" name="'.$value['item_name'].'" class="uploader_input" /><div class="uploader_item uploader_add">
				<div class="uploader_iconbox"><div class="icon-plus2"></div></div>
				<div id="uploader_pick_'.$value['field_id'].'" class="uploader_pick"></div></div></div>
				<div style="font-size:12px;color:#999;line-height:20px;margin:0px;margin-top:5px;padding:0px;clear: both;">最多可上传'.$value['file_num'].'张图片，每张图片上传不超过:'.format_bytes((int)$value['file_size']*1024*1024).'</div>';
				break;

		}

		return $input;
	}

	function generateQRfromGoogle($chl,$widhtHeight ='150',$EC_level='L',$margin='0'){
		$chl = urlencode($chl);
    	$src='http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl;
    return $src;
	}

}
?>