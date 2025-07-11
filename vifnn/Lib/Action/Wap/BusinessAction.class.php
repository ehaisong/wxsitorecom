<?php
class BusinessAction extends WapAction{

    public $token;
    public $wecha_id;
    public $type;
    public $bid;
    private $tpl;
    private $info;
    public $weixinUser;
    public $homeInfo;
	public $busines;
    public function _initialize() {
        parent::_initialize();
        $this->token    = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $this->wecha_id = filter_var($this->wecha_id,FILTER_SANITIZE_STRING);
        $this->type     = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $this->bid      = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $arrAllow       = array('fitness','gover','food','travel','flower','property','ktv','bar','fitment','wedding','affections','housekeeper','lease','beauty');
        $orderid    =   filter_var($this->_get('orderid'),FILTER_SANITIZE_STRING);
        if(!in_array($this->type,$arrAllow) && empty($_GET['orderid'])){
            $this->error('抱歉,您的参数不合法!',U('Index/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id)));
        }
        $where   = array('token'=>$this->token,'type'=>$this->type,'bid'=>$this->bid);
		$busines = $this->busines = M("busines")->where($where)->find();
        $this->assign('busines',$busines);
        $this->assign('picurl',$busines['picurl']);
        $this->assign('title',$busines['title']);
        $this->assign('token',$this->token);
        $this->assign('wecha_id',$this->wecha_id);
        $this->assign('type',$this->type);
        $this->assign('bid',$this->bid);
        $tpl=$this->wxuser;
        $this->tpl=$tpl;

    }


    public function index(){
        $data   = D('busines');
        $type   = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token  = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid    = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $wecha_id = filter_var($this->wecha_id,FILTER_SANITIZE_STRING);

        if($bid == '' || $type == ''){
            $this->error('抱歉,您访问的URL路径出错,马上带您到首页...',U('Index/index',array('token'=>$token,'wecha_id'=>$wecha_id)));
        }
        $where      = array('token'=>$token,'type'=>$type,'bid'=>$bid);

        $busines = $data->where($where)->find();
        if($busines == null){
            $this->error('抱歉,没有该记录,马上带您到首页...',U('Index/index',array('token'=>$token,'wecha_id'=>$wecha_id)));
        }
        // 背景轮播图
        $pic     = M('busines_pic')->where(array('bid_id'=>$busines['bid'],'token'=>$token,'type'=>$type))->find();
        $flashbg[0]['img'] = $pic['picurl_1'];
        $flash[0]['img'] = $pic['picurl_1'];
		$flash[0]["url"] = "javascript:;";
		$flash[0]["info"] = "海报图1";
        if($pic['picurl_2'] != ''){
            $flashbg[1]['img'] = $pic['picurl_2'];
            $flash[1]['img'] = $pic['picurl_2'];
			$flash[1]["url"] = "javascript:;";
			$flash[1]["info"] = "海报图2";
        }
        if($pic['picurl_3'] != ''){
            $flashbg[2]['img'] = $pic['picurl_3'];
            $flash[2]['img'] = $pic['picurl_3'];
			$flash[2]["url"] = "javascript:;";
			$flash[2]["info"] = "海报图3";
        }
        if($pic['picurl_4'] != ''){
            $flashbg[3]['img'] = $pic['picurl_4'];
            $flash[3]['img'] = $pic['picurl_4'];
			$flash[3]["url"] = "javascript:;";
			$flash[3]["info"] = "海报图4";
        }
        if($pic['picurl_5'] != ''){
            $flashbg[4]['img'] = $pic['picurl_5'];
            $flash[4]['img'] = $pic['picurl_5'];
			$flash[4]["url"] = "javascript:;";
			$flash[4]["info"] = "海报图5";
        }

        $this->assign('busines',$busines);
        $show    = filter_var($this->_get('show'),FILTER_SANITIZE_STRING);
        if($show == 'intro' && $show != ''){
            $where_2 = array('token'=>$token,'type'=>$type,'bid_id'=>$busines['bid']);
            $b_main = D('busines_main')->where($where_2)->select();
            $this->assign('b_main',$b_main);
	    $this->assign("show", $show);
            $this->display('intro');
            exit;
        }

        include('./vifnn/Lib/ORG/index.Tpl.php');
        foreach($tpl as $k=>$v){
            if($v['tpltypeid'] == $busines['tpid']){
                 $tplinfo = $v;
            }
        }

$info = array();

//$info[0]['url']  = "/index.php?g=Wap&m=Business&a=index&token=$token&wecha_id=$wecha_id&type=$type&bid=$bid";
//$info[0]['img']  = '/tpl/static/attachment/icon/white/1.png';

$info[1]['url']  = "/index.php?g=Wap&m=Business&a=index&token=$token&wecha_id=$wecha_id&type=$type&bid=$bid&show=intro";
$info[1]['img']  = '/tpl/static/attachment/icon/white/5.png';

$info[2]['url']  = "/index.php?g=Wap&m=Business&a=classify&token=$token&wecha_id=$wecha_id&type=$type&bid=$bid";
$info[2]['img']  = '/tpl/static/attachment/icon/white/4.png';

$info[3]['url']  = "/index.php?g=Wap&m=Business&a=plist&token=$token&wecha_id=$wecha_id&type=$type&bid=$bid";
$info[3]['img']  = '/tpl/static/attachment/icon/white/13.png';

$info[4]['url']  = "/index.php?g=Wap&m=Business&a=comments&token=$token&wecha_id=$wecha_id&type=$type&bid=$bid";
$info[4]['img']  = '/tpl/static/attachment/icon/white/9.png';

$info[5]['url']  = "/index.php?g=Wap&m=Business&a=mylist&token=$token&wecha_id=$wecha_id&type=$type&bid=$bid";
$info[5]['img']  = '/tpl/static/attachment/icon/white/15.png';

switch($busines['type']){
    case 'fitness':
       // $info[0]['name'] = '健身首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '健身房间';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'gover':
        //$info[0]['name'] = '宣传首页';
        $info[1]['name'] = '部门简介';
        $info[2]['name'] = '服务窗口';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '领导点评';
        $info[5]['name'] = '我的订单';
        break;
    case 'food':
       // $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '销售门店';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;
    case 'travel':
        //$info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '景区景点';
        $info[3]['name'] = '景区相册';
        $info[4]['name'] = '专家点评';
        $info[5]['name'] = '我的订单';
        break;
    case 'flower':
       // $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '花店分店';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'property':
      //  $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '小区管理';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '专家点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'ktv':
      //  $info[0]['name'] = '宣传首页';
        $info[1]['name'] = 'KTV简介';
        $info[2]['name'] = 'KTV分店';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'bar':
       // $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '酒吧简介';
        $info[2]['name'] = '酒吧分店';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'fitment':
      //  $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '装修分店';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'wedding':
        //$info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '分店服务';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;


    case 'affections':
      //  $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '宠物分店';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

     case 'housekeeper':
        //$info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '分店服务';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;


    case 'lease':
       // $info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '分店服务';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

    case 'beauty':
        //$info[0]['name'] = '宣传首页';
        $info[1]['name'] = '公司简介';
        $info[2]['name'] = '分店服务';
        $info[3]['name'] = '相册展示';
        $info[4]['name'] = '客户点评';
        $info[5]['name'] = '我的订单';
        break;

}


        $this->assign('flash',$flash);
        $this->assign('info',$info);   // 菜单相关,url(连接),img(菜单背景图),name(菜单名)
        $this->assign('flashbg',$flashbg);  //背景轮播图 img(图片地址)
        $this->assign('tpl',$this->tpl);
		$this->homeInfo['title'] = $busines['mtitle'];
		$this->homeInfo['info'] = $busines['mtitle'];
		$this->assign('homeInfo',$this->homeInfo);
        if($busines['tpid'] == 36){
            $this->assign('html36',$HTMLCSS);
        }
        if($busines['tpid'] == 117 ){
            $this->display('Index:1117_index_35y5buss');
        }elseif($busines['tpid'] == 36){
            $this->display('Index:136_index_esfsd344buss');
        }else{
            $this->display('Index:'.$tplinfo['tpltypename']);
        }
	}

	public function companyMap()
	{
		if (C("baidu_map")) {
			$isamap = 0;
		}
		else {
			$isamap = 1;
		}

		$this->apikey = C("baidu_map_api");
		$this->assign("apikey", $this->apikey);
		$this->assign("thisCompany", $this->busines);

		if (!$isamap) {
			$this->display();
		}
		else {
			$this->amap = new amap();
			$link = $this->amap->getPointMapLink($this->busines["longitude"], $this->busines["latitude"], $this->busines["mtitle"]);
			header("Location:" . $link);
		}
	}

    public function classify(){
        //Load('extend');
        $data       = D('busines_main');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $where      = array('token'=>$token,'type'=>$type,'bid_id'=>$bid);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,5);
        $show       = $Page->show();
        $classify   = $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',6);
        $this->assign('page',$show);
        $this->assign('classify',$classify);
        $this->display();
    }

    public function classify_item(){
        $data       = D('busines_main');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $mid        = filter_var($this->_get('mid'),FILTER_VALIDATE_INT);
        $where_2    = array('token'=>$token,'type'=>$type,'mid'=>$mid);
        $classify   = $data->where($where_2)->find();
        $b_second   = M('busines_second');
        $where_3    = array('token'=>$token,'type'=>$type,'mid_id'=>$classify['mid']);

        //$sec_item   = $b_second->where($where_3)->order('sort desc')->select();
        $count      = $b_second->where($where_3)->count();
        $Page       = new Page($count,10);
        $show       = $Page->show();
        $sec_item   = $b_second->where($where_3)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('bid',$bid);
        $this->assign('page',$show);
        $this->assign('sec_item',$sec_item);

        $this->assign('picurl',$classify['desc_pic']);
        $this->assign('title',$classify['name']);
        $this->assign('classify',$classify);
        $this->display();
    }


    public function project(){
        $data       = D('busines_second');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $sid        = filter_var($this->_get('sid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'sid'=>$sid);
        $t_second   = $data->where($where)->find();
        $this->assign('sec_item',$t_second);
       $this->assign('picurl',$t_second['picurl']);
        $this->display();
    }

    public function goCart(){
        $data       = D('busines_second');
        $t_busines  = D('busines');
        $tb_resbook = D('reservebook');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $sid        = filter_var($this->_get('sid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->wecha_id,FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'sid'=>$sid);
        $second     = $data->where($where)->find();
        $where_2    = array('token'=>$token,'type'=>$type,'bid'=>$bid);
        $busines    = $t_busines->where($where_2)->field('bid,roompicurl,address,businesphone,orderInfo,compyphone')->find();
        $maindata   = M('busines_main')->where(array('mid'=>$second['mid_id'],'token'=>$token,'type'=>$type))->field('mid,name as title')->find();
        if(!empty($second) && !empty($busines)){
            $oput   = array_merge($second,$busines,$maindata);
        }
        //var_dump($oput);
        $count      = $tb_resbook->where(array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type))->count();
        if(IS_POST){
             $_POST['type']       = filter_var($this->_post('type'),FILTER_SANITIZE_STRING);
             $_POST['bid']        = filter_var($this->_post('bid'),FILTER_VALIDATE_INT);
             $_POST['sid']        = filter_var($this->_post('sid'),FILTER_VALIDATE_INT);
             $_POST['token']      = filter_var($this->_post('token'),FILTER_SANITIZE_STRING);
             $_POST['wecha_id']   = filter_var($this->_post('wecha_id'),FILTER_SANITIZE_STRING);
             $_POST['truename']   = trim(filter_var($this->_post('truename'),FILTER_SANITIZE_STRING));
             $_POST['tel']        = filter_var($this->_post('tel'),FILTER_SANITIZE_STRING);
             $_POST['address']    = filter_var($this->_post('address'),FILTER_SANITIZE_STRING);
             $_POST['info']       = filter_var($this->_post('info'),FILTER_SANITIZE_STRING);
             $_POST['productName']= filter_var($this->_post('productName'),FILTER_SANITIZE_STRING);
             $_POST['orderid']    = self::generateOrderSn();
             $_POST['paid']       = 0;
             $_POST['booktime']   = time();
			empty($_POST["bid"]) && !empty($bid) && ($_POST["bid"] = $bid);
            $where_stork          = array('token'=>$_POST['token'],'type'=>$_POST['type'],'sid'=>$_POST['sid']);
            $checkdata            = $data->where($where_stork)->find();
            if($_POST['wecha_id'] == '' || $_POST['token'] =='' || $_POST['truename'] == ''){
                exit($this->error('抱歉,请先关注我们的公众号.',
                    U('Index/index',array('token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],
                                        'bid'=>$_POST['bid'],'type'=>$_POST['type']))));
            }
            if($_POST['type'] == 'property' || $_POST['type'] =='gover'){
            }else{
                if(intval($checkdata['googsnumber']) <= 0){
                    exit($this->error('非常遗憾,您来晚了一步.',
                                U('Business/index',array('token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],
                                                    'bid'=>$_POST['bid'],'type'=>$_POST['type']))));
                }

            }
            $_POST['orderid']     = self::generateOrderSn();
            $_POST['orderName']   = $checkdata['name'];
            $_POST['payprice']    = $checkdata['oneprice'];
            $_POST['rid']         = filter_var($this->_post('sid'),FILTER_VALIDATE_INT);
            $insertdata           = $tb_resbook->data($_POST)->add();
            if($insertdata){
//VIFNN增加邮件通知				
			$info=M('deliemail')->where(array('token'=>$_POST['token']))->find();
			$mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
			$emailstatus=$info[$_POST['type']];
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

					$mail->Subject = '您有新的订单';
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
                $data->where(array('sid'=>$_POST['sid'],'type'=>$_POST['type'],'token'=>$_POST['token']))->setDec('googsnumber');
                if($checkdata['oneprice'] <= 0){
                //if(is_numeric($checkdata['oneprice']) <= 0){
                // if(intval($checkdata['oneprice']) == 0){
                    $msg = ($_POST['type'] == 'property') ? '微物业' : '行业应用';
                    $this->assign('type',$_POST['type']);
                    $this->assign('token',$_POST['token']);
                    $this->assign('wecha_id',$_POST['wecha_id']);
                    $savedata['paid'] = 1;
                    $tb_resbook->where(array('id'=>$insertdata ,'token'=>$token))->save($savedata);

                    //打印ktv
                    if($_POST['type'] == 'ktv'){
                        $op = new orderPrint();
                        $paid = $_POST['payprice'] > 0 ? 0 : 1;
                        $msg = $this->content_layout($_POST);
                        $op->printit($_POST['token'], 0, 'Business', $msg, $paid);
                    }

                    Sms::sendSms($_POST['token'], "您的会员 {$_POST['truename']},对{$_POST['orderName']}下单了,订单号为{$_POST['orderid']} 。". date('Y-m-d H:i:s',time()));
					
					//给商家发站内信
					$params = array();
					$params['site'] = array('token'=>$_POST['token'], 'from'=>$msg.'消息','content'=>"您的会员".$_POST['truename'].",对".$_POST['orderName']."下单了，订单号为".$_POST['orderid']);
					MessageFactory::method($params, 'SiteMessage');

                    Sms::sendSms($_POST['token'], "尊敬的 {$_POST['truename']},您对{$_POST['orderName']}进行了下单,订单号为{$_POST['orderid']} 。 ". date('Y-m-d H:i:s',time()),$_POST['tel']);
					header("Location: " . U("Business/mylist", array("token" => $_POST["token"], "type" => $_POST["type"])));
                    exit;
                }else{
		       header("Location: " . U("Alipay/pay/", array("from" => "Business", "orderName" => urlencode($checkdata["name"]), "price" => trim($checkdata["oneprice"]), "orderid" => $_POST["orderid"], "token" => $_POST["token"], "wecha_id" => $_POST["wecha_id"], "type" => $_POST["type"], "bid" => $_POST["bid"], "sid" => $_POST["sid"])));
                }


            }else{
                exit($this->error('Sorry,请重新下单.',
                    U('Business/index',array('token'=>$_POST['token'],'wecha_id'=>$_POST['wecha_id'],
                                        'bid'=>$_POST['bid'],'type'=>$_POST['type']))));
            }

        }
        $this->assign('oput',$oput);
        $this->assign('count',$count);
        $this->display();
    }

    private function  generateOrderSn(){
        date_default_timezone_set('PRC');
        list($msec, $sec) = explode(' ',microtime());
        return date('ymdHis',$sec).substr($msec,2,6);
    }

    public function payReturn(){

        $tb_resbook = D('reservebook');
        $orderid    =   filter_var($this->_get('orderid'),FILTER_SANITIZE_STRING);
        $token      =   filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $checkOrder = $tb_resbook->where(array('orderid'=>$orderid,'token'=>$token))->find();       //根据订单号查出$order
       if($checkOrder){
            if($checkOrder['paid'] === '1'){
                $this->assign('type',$checkOrder['type']);
                $this->assign('token',$checkOrder['token']);
                $this->assign('wecha_id',$checkOrder['wecha_id']);
                //打印ktv订单
                if($checkOrder['type'] == 'ktv'){   
                    $op = new orderPrint();
                    $msg = $this->content_layout($checkOrder);
                    $op->printit($checkOrder['token'], 0, 'Business', $msg, 1);
                }
                Sms::sendSms($checkOrder['token'], "您的会员 {$checkOrder['truename']},已经购买了{$checkOrder['orderName']} 并付款成功,金额为{$checkOrder['payprice']},订单号为{$checkOrder['orderid']}。". date('Y-m-d H:i:s',time()));
				
				//给商家发站内信
				$params = array();
				$params['site'] = array('token'=>$checkOrder['token'], 'from'=>'行业应用消息','content'=>"您的会员 {$checkOrder['truename']},已经购买了{$checkOrder['orderName']} 并付款成功,金额为{$checkOrder['payprice']},订单号为{$checkOrder['orderid']}。");
				MessageFactory::method($params, 'SiteMessage');
				
                Sms::sendSms($checkOrder['token'], "尊敬的 {$checkOrder['truename']},您购买的{$checkOrder['orderName']} 已经付款成功,金额为{$checkOrder['payprice']},订单号为{$checkOrder['orderid']}。 ". date('Y-m-d H:i:s',time()),$checkOrder['tel']);
				redirect(U('Business/mylist',array('type'=>$checkOrder['type'],'token'=>$checkOrder['token'], "bid" => $checkOrder["bid"])));
                //self::mylist();
                exit;
            }else{

            M('busines_second')->where(array('sid'=>$checkOrder['rid'],'type'=>$checkOrder['type'],'token'=>$checkOrder['token']))->setInc('googsnumber');
			//给商家发站内信
			$params = array();
			$params['site'] = array('token'=>$checkOrder['token'], 'from'=>'行业应用消息','content'=>"您的会员 {$checkOrder['truename']},已经购买了{$checkOrder['orderName']},订单号为{$checkOrder['orderid']}，他选择的是货到付款，请注意查看");
			MessageFactory::method($params, 'SiteMessage');
			//货到付款、店到支付直接跳转
			redirect(U('Business/mylist',array('type'=>$checkOrder['type'],'token'=>$checkOrder['token'], "bid" => $checkOrder["bid"])));
            }

       }else{

          exit('订单不存在!');
        }

    }

    public function mylist(){
        $tb_resbook = D('reservebook');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
	$wecha_id = $this->wecha_id;
        $where      = array('token'=>$token,'type'=>$type,'wecha_id'=>$wecha_id);

        $count      = $tb_resbook->where($where)->count();
        $Page       = new Page($count,10);
        $show       = $Page->show();
        $books      = $tb_resbook->where($where)->order('booktime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('books',$books);
        $this->display();
    }

    public function delOrder(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent,"icroMessenger")) {
            //echo '此功能只能在微信浏览器中使用';exit;
        }
        $id         = filter_var($this->_get('id'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $wecha_id   = filter_var($this->wecha_id,FILTER_SANITIZE_STRING);
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
         M('reservebook')->where(array('id'=>$id))->delete();
         $this->success('删除成功',U('Business/mylist',array('token'=>$token,'wecha_id'=>$wecha_id,'type'=>$type,'bid'=>$bid)));
        exit;
    }

    public function plist(){
        $this->token=$this->_get('token');
        $reply_info_db=M('Reply_info');
        $config=$reply_info_db->where(array('token'=>$this->token,'infotype'=>'album'))->find();
        if ($config){
            $headpic=$config['picurl'];
        }else {
            $headpic='/tpl/Wap/default/common/css/Photo/banner.jpg';
        }
        $this->assign('headpic',$headpic);

        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
	$get_id = M("busines_pic")->where(array("bid_id" => $bid, "token" => $token, "type" => $type))->find();
        $info=M('Photo')->field('title,picurl,id')->where(array('token'=>$token,'id'=>$get_id['ablum_id']))->find();
    $photo_list=M('Photo_list')->where(array('token'=>$token,'pid'=>$get_id['ablum_id'],'status'=>1))->order('sort desc')->select();
		if (empty($photo_list)) {
			$photo_list = array();
		}
		if (!empty($get_id)) {
			!empty($get_id["picurl_1"]) && ($photo_list[] = array("id" => 1, "token" => $this->token, "pid" => $get_id["ablum_id"], "title" => "", "sort" => 1, "picurl" => $get_id["picurl_1"], "info" => "", "status" => 1));
			!empty($get_id["picurl_2"]) && ($photo_list[] = array("id" => 2, "token" => $this->token, "pid" => $get_id["ablum_id"], "title" => "", "sort" => 2, "picurl" => $get_id["picurl_2"], "info" => "", "status" => 1));
			!empty($get_id["picurl_3"]) && ($photo_list[] = array("id" => 3, "token" => $this->token, "pid" => $get_id["ablum_id"], "title" => "", "sort" => 3, "picurl" => $get_id["picurl_3"], "info" => "", "status" => 1));
			!empty($get_id["picurl_4"]) && ($photo_list[] = array("id" => 4, "token" => $this->token, "pid" => $get_id["ablum_id"], "title" => "", "sort" => 4, "picurl" => $get_id["picurl_4"], "info" => "", "status" => 1));
			!empty($get_id["picurl_5"]) && ($photo_list[] = array("id" => 5, "token" => $this->token, "pid" => $get_id["ablum_id"], "title" => "", "sort" => 5, "picurl" => $get_id["picurl_5"], "info" => "", "status" => 1));
		}
        $this->assign('info',$info);
        $this->assign('photo',$photo_list);
        $this->display();
    }


    public function comments(){
        $data       = D('busines_comment');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'bid_id'=>$bid);
        $count      = $data->where($where)->count();
        $Page       = new Page($count,6);
        $show       = $Page->show();
        $comments= $data->where($where)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('count',6);
        $this->assign('comments',$comments);
        $this->display();

    }

    public function comlist(){
        $data       = D('busines_comment');
        $type       = filter_var($this->_get('type'),FILTER_SANITIZE_STRING);
        $bid        = filter_var($this->_get('bid'),FILTER_VALIDATE_INT);
        $cid        = filter_var($this->_get('cid'),FILTER_VALIDATE_INT);
        $token      = filter_var($this->_get('token'),FILTER_SANITIZE_STRING);
        $where      = array('token'=>$token,'type'=>$type,'cid'=>$cid);
        $comments= $data->where($where)->order('sort desc')->find();
        $this->assign('classify',$comments);
        $this->display();
    }
    //格式化打印信息
    private function content_layout($order) 
    {
        $return = '';
        $return .= '姓名：' . $order['truename'];
        $return .= chr(10). '联系电话：' . $order['tel'];
        $return .= chr(10). 'KTV分店：' . $order['productName'];
        $return .= chr(10). 'KTV包厢' . $order['orderName'];
        $return .= chr(10). '地址：' . $order['address'];
        if ($order['paid']) {
            $return .= chr(10). '付款状态：已付款';
        } else {
            $return .= chr(10). '付款状态：未付款';
        }
        $return .= chr(10). '下单单号：' . $order['orderid'];
        
        $return .= chr(10). '付款金额：' . $order['payprice'] . '元';
        $return .= chr(10). '提交时间：' . date('Y-m-d H:i:s', $order['booktime']);
        if ($order['info']) {
            $return .= chr(10). '留言：' . strip_tags($order['info']);
        } else {
            $return .= chr(10). '留言：无';
        }
        return $return;
    }
//VIFNN增加邮件通知			
public function sms(){
	
		$this->Business_order=M('reservebook');
		$this->Business_info=M('busines_second');
		$this->Business_dian=M('busines_main');
		$orders=$this->Business_order->where(array('token'=>$_GET['token'],'type'=> $_GET['type']))->order('date desc')->limit(0,1)->find();
		$info=$this->Business_info->where(array('token'=>$_GET['token'],'type'=> $_GET['type'],'sid'=>$this->_get('sid')))->find();
		$dian=$this->Business_dian->where(array('token'=>$_GET['token'],'type'=> $_GET['type'],'bid'=>$this->_get('bid')))->find();
		

		 if ($orders){
			$thisOrder=$_GET['type'];
			switch ($_GET['type']){
				case 'beauty':
					$orderType='微美容';
					break;
				case 'fitness':
					$orderType='健微身';
					break;
				case 'gover':
					$orderType='微政务';
					break;
				case 'food':
					$orderType='微食品';
					break;
				case 'travel':
					$orderType='微旅游';
					break;
				case 'flower':
					$orderType='微花店';
					break;
				case 'property':
					$orderType='微物业';
					break;	
				case 'bar':
					$orderType='微酒吧';
					break;	
				case 'fitment':
					$orderType='微装修';
					break;
				case 'wedding':
					$orderType='微婚庆';
					break;	
				case 'affections':
					$orderType='微宠物';
					break;		
				case 'housekeeper':
					$orderType='微家政';
					break;	
				case 'lease':
					$orderType='微租赁';
					break;				
			}}
			
			$str="\r\n订单类型：".$orderType."\r\n所属门店：".$dian['name']."\r\n价格：".$info['oneprice']."\r\n姓名：".$orders['truename']."\r\n电话：".$orders['tel']."\r\n地址：".$orders['address']."\r\n备注：".$orders['info']."\r\n下单时间：".$orders['date']."\r\n";
			
			
			
			return $str;
		
	}	
//VIFNN增加邮件通知	

}
?>