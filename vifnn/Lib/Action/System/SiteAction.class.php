<?php
class SiteAction extends BackAction{
	public function _initialize() {
        parent::_initialize();  //RBAC 验证接口初始化.
		$config_file_list = array('alipay.php','email.php','info.php','platform.php','safe.php','sms.php');
		foreach($config_file_list as $vo){
			$fh = fopen(CONF_PATH.$vo,"rb");
			$fs = fread($fh,filesize(CONF_PATH.$vo));
			fclose($fh);
			$fs = str_replace('\'up_exts\'','\'up_exts_error\'',$fs);
			file_put_contents(CONF_PATH.$vo, $fs, LOCK_EX);
			@unlink(RUNTIME_FILE);
		}				
		$_POST = filterPost($_POST, array('up_password'));
    }
	
	public function index(){
		$where=array('agentid'=>$this->agentid);
		$groups=M('User_group')->where($where)->order('id ASC')->select();
		$this->assign('groups',$groups);
		if(class_exists('updateSync')){
			$result = updateSync::getIfWeidian();
			$this->assign('load_config',$result);
		}
		$this->display();
	}
	public function mysql(){
		
		
		$this->display();
		
	}
	public function mysqlajax(){
		switch($_POST['type']){
			case 'table_name':
				$db_name = C('DB_NAME');
				$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$db_name."'";
				$query_sql = M()->query($sql);
				$table_name = array();
				foreach($query_sql as $k=>$v){
					$table_name[$k] = $v['TABLE_NAME'];
				}
				$data['table_name'] = $table_name;
				$data['table_count'] = count($table_name);
				$this->ajaxReturn($data,'JSON');
			break;
			case 'youhuasql':
				$sql_OPTIMIZE = "OPTIMIZE TABLE `".$_POST['table_name']."`";
				$query_sql_OPTIMIZE = M()->query($sql_OPTIMIZE);
				$query_sql_OPTIMIZE[0]['Table'] = str_replace(C('DB_NAME').'.','',$query_sql_OPTIMIZE[0]['Table']);
				$data = $query_sql_OPTIMIZE[0];
				$this->ajaxReturn($data,'JSON');
			break;
			case 'xiufusql':
				$sql_REPAIR = "REPAIR TABLE `".$_POST['table_name']."`";
				$query_sql_REPAIR = M()->query($sql_REPAIR);
				$query_sql_REPAIR[0]['Table'] = str_replace(C('DB_NAME').'.','',$query_sql_REPAIR[0]['Table']);
				$data = $query_sql_REPAIR[0];
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	public function appajax(){
		$appid = $_POST['appid'];
		$secret = $_POST['secret'];

		$apiOauth 	= new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($appid);
		/*
		$url_access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
		$data_access_token = json_decode($this->https_request($url_access_token), true);
		$access_token = $data_access_token['access_token'];
		*/
		$url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token={$access_token}";
		$wxdata = json_decode($this->https_request($url), true);
		if($wxdata['errcode'] == 48001){
			$data['error'] = 1;
		}
		/*
		if($data_access_token['errcode'] == 40001 || $data_access_token['errcode'] == 41002 || $data_access_token['errcode'] == 41004 || $data_access_token['errcode'] == 40125){
			$data['error'] = 2;
		}else{
			$data['error'] = $data_access_token['errcode'];
		}
		*/
		$this->ajaxReturn($data,'JSON');
	}
	//https请求（支持GET和POST）
    protected function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
	public function email(){
		$this->display();
	}	
	public function alipay(){
		$this->display();
	}
	public function safe(){
		$this->display();
	}
	public function upfile(){
		$this->display();
	}
	public function sms(){
		$total=M('Sms_expendrecord')->sum('count');
		$this->assign('total',$total);
		$this->display();
	}
	public function wechat_api(){
		$site 	= M('weixin_account')->find();
		if(IS_POST){
			if($site){
				if(M('Weixin_account')->where('1')->save($_POST)){
					$this->success('操作成功');
				}else{
					$this->success('操作失败');
				}
			}else{
				if(M('Weixin_account')->add($_POST)){
					$this->success('操作成功');
				}else{
					$this->success('操作失败');
				}
			}
		}else{
			$this->assign('site',$site);
			$this->display();
		}	
	}
	public function weixinPay(){
		$pay 	= M('WeixinPay')->where(array('system'=>1))->find();
		if(IS_POST){
			if($_FILES['apiclient_cert']['error'] != 4 || $_FILES['apiclient_key']['error'] != 4 || $_FILES['rootca']['error'] != 4){
				$firstname = ($pay['appid'] != "") ? $pay['appid'] : substr(md5('pay'), 0,18);
				$cert = $this->localupload($firstname);
				if($cert['status'] == 'fail'){
					$this->success($cert['msg']);
				}else{
					$this->success('上传成功');
				}
				exit;
			}
			if($pay){
				$_POST = array_map('trim', $_POST);
				if(M('WeixinPay')->where(array('system'=>1))->save($_POST)){
					$this->success('操作成功');
				}else{
					$this->success('操作失败');
				}
			}else{
				$_POST['system'] = 1;
				$_POST['create_time'] = time();
				$_POST = array_map('trim', $_POST);
				if(M('WeixinPay')->add($_POST)){
					$this->success('操作成功');
				}else{
					$this->success('操作失败');
				}
			}
		}else{
			$this->assign('pay',$pay);
			$this->display();
		}
	}	
	public function insert(){
		$appid = $_POST['appid'];
		$secret = $_POST['secret'];
		if($_POST['up_exts'] != ''){
			$_POST['up_exts'] = str_replace("'",'',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('"','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('‘','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('“','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('，',',',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('’','',$_POST['up_exts']);
			$_POST['up_exts'] = str_replace('”','',$_POST['up_exts']);
			$_POST['up_exts'] = trim($_POST['up_exts']);
			$_POST['up_exts'] = strtolower($_POST['up_exts']);
		}else{
			unset($_POST['up_exts']);
		}
		if($appid != '' && $secret != ''){
			$apiOauth 	= new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($appid);
			/*
			$url_access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$data_access_token = json_decode($this->https_request($url_access_token), true);
			$access_token = $data_access_token['access_token'];
			*/
			$url = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token={$access_token}";
			$wxdata = json_decode($this->https_request($url), true);
			if($wxdata['errcode'] == 48001){
				$this->error('您填的appid和appsecret并不是认证后的服务号！');
				exit;
			}
			/*
			if($data_access_token['errcode'] == 40001 || $data_access_token['errcode'] == 41002 || $data_access_token['errcode'] == 41004 || $data_access_token['errcode'] == 40125){
				$this->error('您填的appid和appsecret不正确！');
				exit;
			}
			*/
		}
		$file=$this->_post('files');
		unset($_POST['files']);
		unset($_POST[C('TOKEN_NAME')]);
		if (isset($_POST['countsz'])){
		$_POST['countsz']=base64_encode($_POST['countsz']);
		}
		if($this->update_config($_POST,CONF_PATH.$file)){
			$this->success('操作成功');
		}else{
			$this->success('操作失败');
		}
	}
	/*
	public function updatekey(){
		$file='info.php';
		
		
		$config_file=CONF_PATH.$file;
		!is_file($config_file) && $config_file = CONF_PATH . 'web.php';
		if (is_writable($config_file)) {
			$config = require $config_file;
			if ($_GET['key']){
			$config['server_key']=$_GET['key'];
			}
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
			@unlink(RUNTIME_FILE);
			exit(1);
		} else {
			exit(-1);
		}
	}
	*/
	public function smssendtest(){
		if (strlen($_GET['mp'])!=11){
			$this->error('请输入正确的手机号');
		}
		$this->error(Sms::sendSms('admin','hello,你好',$_GET['mp']));
	}
	private function update_config($config, $config_file = '') {
		!is_file($config_file) && $config_file = CONF_PATH . 'web.php';
		if (is_writable($config_file)) {
			//$config = require $config_file;
			//$config = array_merge($config, $new_config);
			//dump($config);EXIT;
			file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
			@unlink(RUNTIME_FILE);
			return true;
		} else {
			return false;
		}
	}
	public function localupload($firstname = ''){
		$upload = new UploadFile();
		$upload->allowExts  = array('pem');
		//覆盖同名的文件
		$upload->uploadReplace=1;
        $firstLetter=substr($firstname,0,1);
        $upload->savePath =  './uploads/'.$firstLetter.'/'.$firstname.'/';// 设置附件上传目录
        //
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads')||!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads')){
            mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads',0777);
        }
        $firstLetterDir=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$firstLetter;
        if (!file_exists($firstLetterDir)||!is_dir($firstLetterDir)){
            mkdir($firstLetterDir,0777);
        }
        if (!file_exists($firstLetterDir.'/'.$firstname)||!is_dir($firstLetterDir.'/'.$firstname)){
            mkdir($firstLetterDir.'/'.$firstname,0777);
        }
		if(!file_exists($upload->savePath)||!is_dir($upload->savePath)){
			mkdir($upload->savePath,0777);
		}
       // $upload->hashLevel=2;
        if(!$upload->upload()) {// 上传错误提示错误信息
            $error=1;
            $msg=$upload->getErrorMsg();
			return array('status'=>'fail','msg'=>$msg);
        }else{// 上传成功 获取上传文件信息
            $error=0;
            $info =  $upload->getUploadFileInfo();
            $this->siteUrl=$this->siteUrl?$this->siteUrl:C('site_url');
			$msg=$this->siteUrl.substr($upload->savePath,1).$info[0]['savename'];
			//成功入库
			$res = $this->addCert($info[0]['key'],$msg);
			if($res == 'done'){
				return array('status'=>'success','msg'=>'');
			}else{
				return array('status'=>'fail','msg'=>$res);
			}
        }
	}
	
	public function addCert($type = 'apiclient_cert',$url = ''){
		$data = array();
		if(!in_array($type,array('apiclient_cert','apiclient_key','rootca'))){
			return false;
		}
		$data[$type] = $url;
		$cert = M('WeixinPay')->where(array('system'=>1))->find();
		if(empty($cert)){
			$insert = M('WeixinPay')->add($data);
			if($insert){
				return 'done';
			}else{
				return '上传失败';
			}
		}else{
			$update = M('WeixinPay')->where(array('system'=>1))->save($data);
			if($update){
				return 'done';
			}else{
				return '更新失败';
			}
		}
	}	
	public function rippleos_key(){
		$this->display();
	}		
	public function themes() {
		$this->display();
	}
	public function themes_up() {
		$data=$this->_post('beer');
		$date = substr(preg_replace('|[0-9/?@"<>{&}:+%_-]|','',$data),0,10);
		$setfile = "./Conf/Home/config.php";
		$settingstr="<?php
		  return array(
		     'TMPL_FILE_DEPR'=>'_',
		     'DEFAULT_THEME' => '".$data."',
	         );?>";
		file_put_contents($setfile,$settingstr);
		$this->success('操作成功',U('Site/themes'));
	}

	public function qianmoban() {
		$this->display();
		}
	public function qianmoban_up() {
		$data=$this->_post('beer');
		$setfile = "./Conf/Home/config.php";
		$settingstr="<?php \n return array(\n   'TMPL_FILE_DEPR'=>'_',  \n 'DEFAULT_THEME' => '".$data."',      );\n?>\n";
		file_put_contents($setfile,$settingstr);
		$this->success('操作成功',U('Site/themes'));
	}	
	
	
	
}