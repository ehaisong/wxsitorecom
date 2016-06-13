<?php
class HostAction extends BaseAction{
	public function __construct(){
		parent::_initialize();
	}
    public function index(){
        $agent = $_SERVER['HTTP_USER_AGENT']; 
        if(!strpos($agent,"icroMessenger")) {
           // echo '此功能只能在微信浏览器中使用';exit;
        }
        $token      = $this->_get('token'); 
        $hid         = $this->_get('hid'); 
        $where      = array('token'=>$token,'hid'=>$hid);             
        $list_add     = M('Host_list_add')->where($where)->select();   
        $hostset =  M('Host')->where(array('token'=>$token,'id'=>$hid))->find();
        $this->assign('list',$list_add);
        //company info
        $company_db=M('Company');
        $thisCompany=$company_db->where(array('token'=>$token,'isbranch'=>0))->find();
        $hostset['address']=$thisCompany['address'];
        $this->assign('set',$hostset);
      //  $this->assign('isAndroid',isAndroid());
        $this->display();
    }
    
    //首次进入，罗列在线商家
    public function online($display=1){
        $agent = $_SERVER['HTTP_USER_AGENT']; 
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $token      = $this->_get('token'); 
        if(empty($token))  $this->error('非法操作');

        $where      = array('token'=>$token); 
        $data=M('Host');
        $count      = $data->where( $where )->count();
        $Page       = new Page($count,7);
        $show       = $Page->show();
        $list = $data->where( $where )->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);
        $this->assign('show',$show);
        //
        $hid         = $this->_get('hid'); 
        $hostset =  M('Host')->where(array('token'=>$token,'id'=>$hid))->find();
        $this->assign('set',$hostset);
        if ($display){
        $this->display();
        }
    }
    public function companyDetail(){
    	$this->online(0);
    	$this->display();
    }
    public function lists(){
       $agent = $_SERVER['HTTP_USER_AGENT']; 
        if(!strpos($agent,"MicroMessenger")) {
           // echo '此功能只能在微信浏览器中使用';exit;
        }
       $id    = $this->_get('id');
       $token = $this->_get('token');
       $hid = $this->_get('hid');
       $wecha_id = $this->wecha_id;
       $userinfo = M('userinfo')->where(array('wecha_id'=>$wecha_id,'token'=>$token))->find();

       $host = M('Host')->where(array('id'=>$hid,'token'=>$token))->find();
       $where = array('id'=>$id,'token'=>$token);
       $types = M('Host_list_add')->where($where)->find();
	   //dump($types);
	   $types['picurl']=str_replace('&amp;','&',$types['picurl']);
       $this->assign('types',$types);
       $save_monery = $types['price'] - $types['yhprice']; 
       $this->assign('userinfo',$userinfo);
       $this->assign('saves',$save_monery );
       $this->assign('host',$host);
        
        $this->display();

    }
    
    //在线预定
    public function book(){ 
        $agent = $_SERVER['HTTP_USER_AGENT']; 
        if(!strpos($agent,"MicroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        if($_POST['action'] == 'book'){           

            $data['wecha_id']  =  $this->_post('wecha_id');
            $data['book_people']  =  $this->_post('truename'); 
            $data['remarks']   =  $this->_post('remarks');  
            $data['tel']       = $this->_post('tel');  
            $data['book_num']      = $this->_post('nums'); 
            $data['book_time']  = strtotime($this->_post('dateline'));           
            $id       = $this->_post('id');
            $data['room_type'] = $this->_post('roomtype'); 
            $data['order_status'] = 3 ;
            $data['hid'] = $this->_get('hid');
            $data['token'] = $this->_get('token');

            $price = M('Host_list_add')->where(array('id'=>$id,'token'=>$data['token'],'hid'=>$data['hid']))->find();

            $data['price'] = $price['yhprice'] * $data['book_num'];
                    
          
            $order = M('Host_order')->data($data)->add();    

            if($order){

//VIFNN增加邮件通知					
			$info=M('deliemail')->where(array('token'=>$this->token))->find();
			$emailstatus=$info['zxyy'];
			$emailreceive=$info['receive'];
			$content = $this->sms();
			if($info['type'] == 1){
			$emailsmtpserver=$info['smtpserver'];
			$emailport=$info['port'];
			$emailsend=$info['name'];
			$emailpassword=$info['password'];
			}else{
			$emailsmtpserver=C('email_server');
			$emailport=C('email_port');
			$emailsend=C('email_user');
			$emailpassword=C('email_pwd');
			}
			$emailuser=explode('@', $emailsend);
			$emailuser=$emailuser[0];
			if ($emailstatus == 1) {
				if ($content) {
					date_default_timezone_set('PRC');
					require("class.phpmailer.php");
					$mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
					$mail = new PHPMailer();
					$mail->IsSMTP();                                      // set mailer to use SMTP
					$mail->Host = "$emailsmtpserver";  // specify main and backup server
					$mail->SMTPAuth = true;     // turn on SMTP authentication
					$mail->Username = "$emailuser"; // SMTP username
					$mail->Password = "$emailpassword"; // SMTP password
					$mail->From = $emailsend;
					$mail->FromName = C('site_name');
					$mail->AddAddress("$emailreceive", "商户");
					//$mail->AddAddress("ellen@example.com");                  // name is optional
					$mail->AddReplyTo($emailsend, "Information");

					$mail->WordWrap = 50;                                 // set word wrap to 50 characters
					//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
					//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
					$mail->IsHTML(false);                                  // set email format to HTML

					$mail->Subject = '您的新预约订单';
					$mail->Body    = $content;
					$mail->AltBody = "";

					if(!$mail->Send())
					{
					   echo "Message could not be sent. <p>";
					   echo "Mailer Error: " . $mail->ErrorInfo;
					   exit;
					}
					//echo "Message has been sent";    
				}
			}

//VIFNN增加邮件通知
			
                echo'{"success":1,"msg":"恭喜,预定成功。"}';
            }else{
                echo'{"success":0,"msg":"请从新预定。"}';
            }            
            exit;
        }    
            
        
    }
	
//VIFNN增加邮件通知	
	public function sms(){
	
		$this->yuyue_order=M('Host_order');
		$orders=$this->yuyue_order->where(array('token'=>$this->token))->order('date desc')->limit(0,1)->find();
		
		
		
			
			$str="\r\n预约订单信息如下：\r\n姓名：".$orders['book_people']."\r\n电话：".$orders['tel']."\r\n预订数量：".$orders['book_num']."\r\n房间类型：".$orders['room_type']."\r\n预订时间：".date('Y-m-d',$orders['book_time'])."\r\n价格：".$orders['price']."\r\n下单时间：".$orders['date']."\r\n";
			
			
			
			return $str;
		
	}	
//VIFNN增加邮件通知		
	
}
    
?>