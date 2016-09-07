<?php
class MessageAction extends UserAction{
	public $thisWxUser;
	public function _initialize() {
		parent::_initialize();
		
        
		$where=array('token'=>$this->token);
		$this->thisWxUser=M('Wxuser')->where($where)->find();
		
		
	}

	public function uploadImage() {
		$html = $this->_post('html', 'html_entity_decode');
		preg_match_all('/<img [^>]*src=[\'\"]([^\'\"]+)[^>]*>/', $html, $matches);
		$material = new Material($this->wxuser);
		foreach ($matches[1] as $image) {
			if ('.' != substr($image, 0, 1)) {
				$do = '.';
			}
			$params['form'] = 'data';
			$params['post'] = array('media'=>'@'.$do.$image);
			if ('gif' == pathinfo($image, PATHINFO_EXTENSION)) {
				$params['post']['type'] = 'image';
				$result = $material->add($params);
			} else {
				$result = $material->uploadimg($params);
			}
			if ($result->url) {
				$data['"'.$image.'"'] = '"'.$image.'" srctitle="'.$result->url.'"';
			}
		}
		if ($data) {
			echo strtr($html, $data);
		} else {
			echo $html;
		}
	}
	
	public function sendHistory(){
		$db 	= D('Send_message');
		$where['token']=$this->token;
		$count 	= $db->where($where)->count();
		$page 	= new Page($count,25);
		$info 	= $db->where($where)->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($info as $key => $val) {
			if($val['groupid']){
				$info[$key]['group_name'] 	= M('Wechat_group')->where(array('wechatgroupid'=>$val['groupid']))->getField('name');
			}else{
				$info[$key]['group_name'] 	= '未分组';
			}
			if($val['openid'] != ""){
				$info[$key]['select_fans_count'] = substr_count($val['openid'],',')+1;
			}
		}
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	public function index(){
		if (IS_POST){
			$msgtype 	= $this->_post('send_type','intval');
			$openid		= rtrim($_POST['openid'],',');
			$wechatgroupid = $this->_post('wechatgroupid','intval');
			$imgids 	= ltrim($_POST['imgids'],',');
			if($imgids){
				$imgidsArr  = explode(',',$imgids);
			}
			$title = trim($_POST['title']);
			$id  = (int)$_POST['id'];
			if($title == ""){
				$this->error('请输入群发标题',U('Message/index',array('id'=>$id)));
			}
			if($msgtype == 1){
				$findcondition = array();
				$findcondition['token'] = $this->token;
				$findcondition['g_id'] = $wechatgroupid;
				if($wechatgroupid == 0){
					$msgtype = 2;
				}
				if($_POST['sex'] != 3){
					$msgtype = 2;
					$findcondition['sex'] = (int)$_POST['sex'];
				}
				if(trim($_POST['send_province']) != "" && trim($_POST['send_city']) != ""){
					$msgtype = 2;
					$findcondition['province'] = array('like','%'.mb_substr($_POST['send_province'],0,2,'utf-8').'%');
					$findcondition['city'] = array('like','%'.mb_substr($_POST['send_city'],0,2,'utf-8').'%');
				}
				$openids = M('wechat_group_list')->where($findcondition)->field('openid')->select();
				$openids_count = M('wechat_group_list')->where($findcondition)->count();
				if($openids_count <= 0){
					$this->error('未找到对应条件下的粉丝,请更改你的条件',U('Message/index',array('id'=>$id)));
				}elseif($openids_count < 2){
					$this->error('群发时至少有2位粉丝',U('Message/index',array('id'=>$id)));
				}
				if($openids_count > 10000 && $msgtype == 2){
					$this->error('发送量超过10000,请去掉性别和地区的限制条件直接按分组发送',U('Message/index',array('id'=>$id)));
				}
				foreach ($openids as $key => $value) {
					$openids[$key] = $value['openid'];
				}
				$openid = implode(",", $openids);
			}else if($msgtype == 2){
				if(empty($openid)){
					$this->error('请选择群发粉丝',U('Message/index',array('id'=>$id)));
				}elseif(substr_count($_POST['openid'],',') < 2){
					$this->error('群发时至少选择2位粉丝',U('Message/index',array('id'=>$id)));
				}
			}
			if(!$id){ //添加
				if (empty($imgidsArr)){
					$this->error('请选择图文消息',U('Message/index',array('id'=>$id)));
				}
				//定时群发功能暂不能使用，先注销，等处理
				/*if($_POST['schedule_type'] == 1){
					$schedule_times = M('send_message')->where(array('token'=>$this->token,'schedule_type'=>1))->field('schedule_time')->select();
					foreach ($schedule_times as $key => $value) {
						$schedule_arr[$key] = $value['schedule_time'];
					}
					if(in_array($_POST['schedule_time'], $schedule_arr)){
						$this->error('该时间点已被设置',U('Message/index',array('id'=>$id)));
					}
					$rt = $this->SendTask($_POST['schedule_time']);
					if($rt == false){
						$this->error('同步任务服务器失败',U('Message/index',array('id'=>$id)));
					}else{
						$taskname = $rt;
					}
				}*/
				$imgs 	= array();
			
				for($i=0;$i<count($imgidsArr);$i++){
					$imgs[$i] = M('Img')->where(array('id'=>$imgidsArr[$i]))->find();
				}
				if(!empty($imgs)){
				$apiOauth 	= new apiOauth();
				$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
				if ($access_token){
					$postMedia=array();
					$postMedia['access_token']=$access_token;
					$postMedia['type']='image';
					$str 	= '';
					foreach ($imgs as $img){
						if(strpos($img['pic'],C('site_url')) !== false){
							$postMedia['media'] = str_replace(C('site_url'),'.',$img['pic']);
						}else{
							$imgStr=$this->curlGet($img['pic']);
							if (!$imgStr){
								$this->error('你的图文消息没有封面或者封面获取不到'.$img['pic'],U('Message/index',array('id'=>$id)));
							}
							file_put_contents(CONF_PATH.'img_'.$img['id'].'.jpg',$imgStr);
							$postMedia['media'] = CONF_PATH.'img_'.$img['id'].'.jpg';
						}
						$postMedia['media'] = $_SERVER['DOCUMENT_ROOT'].str_replace(array('./'),array('/'),$postMedia['media']);

						$rt = $this->curlPost('http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$postMedia['access_token'].'&type='.$postMedia['type'],array('media'=>'@'.$postMedia['media']));
						if($img['url'] != ''){
							$content_source_url = $this->convertLink($img['url']);
						}else{
							$content_source_url = "";
						}
						if($rt['rt']){
							$content = preg_replace('/<img [^>]*srctitle=[\'\"]([^\'\"]+)[^>]*>/', '<img src="$1" />', html_entity_decode($img['info']));
							$content = preg_replace('/<img [^>]*title=[\'\"]([^\'\"]+)[^>]*>/', '<img src="$1" />', html_entity_decode($img['info']));
							$content = str_replace(array('"','"/upload'),array('\"','"'.C('site_url').'/upload'), $content);
							$content = str_replace('//dashang//', '', $content);
							$str .= $comma.'{"thumb_media_id":"'.$rt['media_id'].'","author":"'.$img['writer'].'","title":"'.$img['title'].'","content_source_url":"'.$content_source_url.'","content":"'.$content.'","digest":"'.$img['text'].'","show_cover_pic":'.$img['showpic'].'}';
							$comma=',';
						}else{
							$this->error('操作失败,curl_error:'.$rt['errorno'],U('Message/index',array('id'=>$id)));
						}
					}
					if($str){
						$post 	= '{"articles": ['.$str.']}';
						$rt 	= $this->curlPost('https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$postMedia['access_token'],$post);
						if($rt['rt']){
							$row=array();
							$row['title'] 	= $title;
							$row['openid']	= $openid;
							$row['mediaid'] = $rt['media_id'];
							$row['groupid'] = $wechatgroupid;
							$row['msgtype'] = 'news';
							$row['imgids']  = $imgids;
							$row['token']   = $this->token;
							$row['status'] 	= 0;
							$row['send_type'] = $msgtype;
							$row['schedule_type'] = (int)$_POST['schedule_type'];
							$row['schedule_time'] = strtotime($_POST['schedule_time']);
							//手动指定发送  jm73com
							if ((int)$_POST['schedule_type']==5){  //为手动指定发送
								$row['check_status'] = 5;
							}else{
							     $row['check_status'] = 4;//默认为待审核
							}
							//
							$row['taskname'] = $taskname;
							$mid = M('Send_message')->add($row);
							if($mid){
								S($this->token.'_logfans',null);
								$this->success('信息保存完毕',U('Message/sendHistory',array('token'=>$this->token)));exit;
							}else{
								$this->error('信息保存失败',U('Message/index',array('id'=>$id)));
							}
						}else{
							$this->error('操作失败,curl_error:'.$rt['errorno'],U('Message/index',array('id'=>$id)));
						}
					}
				}else {
					$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg,U('Message/index',array('id'=>$id)));
				}
			}else{
				$this->error('图文未找到',U('Message/index',array('id'=>$id)));
			}
			}else{ //修改---图文不允许修改
				$info = M('send_message')->where(array('id'=>(int)$id))->find();
				if(empty($info)){ $this->error('参数错误');}
				//定时群发先注销
			/*	if($_POST['schedule_type'] == 1 && strtotime($_POST['schedule_time']) != $info['schedule_time']){
					$schedule_times = M('send_message')->where(array('token'=>$this->token,'schedule_type'=>1))->field('schedule_time')->select();
					foreach ($schedule_times as $key => $value) {
						$schedule_arr[$key] = $value['schedule_time'];
					}
					if(in_array($_POST['schedule_time'], $schedule_arr)){
						$this->error('该时间点已被设置');
					}
					$rt = $this->SendTask($_POST['schedule_time']);
					if($rt == false){
						$this->error('同步任务服务器失败');
					}else{
						$taskname = $rt;
					}
				}
				if($info['schedule_type'] == 1 && $_POST['schedule_time'] != $info['schedule_time']){
					$this->DelTask($info['taskname'],$info['schedule_time']);//如果原来有任务,删除掉
				}*/
				$row=array();
				$row['title'] 	= $title;
				$row['openid']	= $openid;
				$row['groupid'] = $wechatgroupid;
				$row['token']   = $this->token;
				$row['status'] 	= 0;
				$row['send_type'] = $msgtype;
				$row['schedule_type'] = (int)$_POST['schedule_type'];
				$row['schedule_time'] = strtotime($_POST['schedule_time']);
				$row['check_status'] = 4;//默认为待审核
				$row['taskname'] = $taskname;
				$update = M('Send_message')->where(array('id'=>$id))->save($row);
				if($update){
					S($this->token.'_logfans',null);
					$this->success('信息修改完毕',U('Message/sendHistory',array('token'=>$this->token)));exit;
				}else{
					$this->error('信息修改失败');
				}
			}
		}else {
			$wechat_group_db=M('Wechat_group');
			$groups=$wechat_group_db->where(array('token'=>$this->token))->order('id ASC')->select();
			$set = M('send_message')->where(array('id'=>(int)$_GET['id']))->find();
			if(!empty($set)){
				S($this->token.'_logfans',trim($set['openid'],',').',');
				$this->assign('selected_fans',trim(S($this->token.'_logfans'),',').',');
				$messages = array();
				if(strpos($set['imgids'], ",") === false){
					$messages[0] = M('img')->where(array('id'=>(int)$set['imgids']))->find();
				}else{
					$messages = M('img')->where(array('id'=>array('in',$set['imgids'])))->order('field(id,'.$set['imgids'].')')->select();
				}
				$this->assign('end_message',$messages[0]);
				array_shift($messages);
				$this->assign('messages',$messages);
			}else{
				S($this->token.'_logfans',null);
				$this->assign('selected_fans','');
			}
			$this->assign('set',$set);
			$this->assign('groups',$groups);
			$this->display();
		}
	}
	//设置审核人
	public function setverifier(){
		if(IS_POST){
			$data = array();
			$data['verifier_phone'] = $_POST['verifier_phone'];
			$data['verifier_code'] = $_POST['verifier_code'];
			$data['nickname'] = trim($_POST['nickname']);
			$data['openid'] = trim($_POST['openid']);
			$data['token'] = trim($_POST['token']);
			if($data['openid'] == ''){
				$this->error('审核人的openid不能为空');
			}
			if(!M('wechat_group_list')->where(array('openid'=>$data['openid']))->find()){
				$this->error('审核人的openid不存在');
			}
			if($_POST['id'] == ''){
				if($data['verifier_phone'] != ''){
					if($data['verifier_code'] == ''){
						$this->error("如果绑定手机号,验证码不能为空");
					}elseif($_SERVER['REQUEST_TIME'] - $_SESSION['comm_send_time'][$data['verifier_phone']] > 1800){
						$this->error("短信验证码过期失效,请重新获取");
					}elseif($data['verifier_code'] != $_SESSION['comm_rand_num'][$data['verifier_phone']]){
						$this->error("短信验证码错误");
					}
					$data['verifier_phone'] = $_POST['verifier_phone'];
				}
				$add = M('verifier_message')->add($data);
				if($add){
					$this->success('添加设置成功',U('Message/sendHistory',array('token'=>$data['token'])));exit;
				}else{
					$this->error('添加设置失败');
				}
			}else{
				$update = M('verifier_message')->where(array('id'=>(int)$_POST['id']))->save($data);
				if($update){
					$this->success('修改设置成功',U('Message/sendHistory',array('token'=>$data['token'])));exit;
				}else{
					$this->error('修改设置失败');
				}
			}
		}
		$set = M('verifier_message')->where(array('token'=>$this->token))->find();
		$this->assign('set',$set);
		$this->display();
	}
	//发给审核人预览
	public function sendpreview(){
		$id = (int)$_GET['id'];
		$verifier = M('verifier_message')->where(array('token'=>$this->token))->find();
		if(empty($verifier)){
			$this->error('未找到审核人,请先添加',U('Message/setverifier',array('token'=>$this->token)));
		}
		$apiOauth 	= new apiOauth();
		$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
		$media_id = M('send_message')->where(array('id'=>$id))->getField('mediaid');
		//发送客服消息
		$text = '<a href=\"'.$this->siteUrl.'/index.php?g=Wap&m=Index&a=CheckMessage&mid='.$media_id.'&check_status=1&token='.$this->token.'\">审核通过</a>       <a href=\"'.$this->siteUrl.'/index.php?g=Wap&m=Index&a=CheckMessage&mid='.$media_id.'&check_status=2&token='.$this->token.'\">审核不通过</a>';
		$checkdata = '{"touser":"'.$verifier['openid'].'","msgtype":"text","text":{"content":"'.$text.'"}}';
		$res = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$checkdata);
		if($res['rt'] && $res['errorno'] == 0){
				$postdata = '{"touser":"'.$verifier['openid'].'", "mpnews":{"media_id":"'.$media_id.'"},"msgtype":"mpnews"}';
				$preview_result 	= $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token,$postdata);
				if($preview_result['rt']){
					//修改为审核中
					M('send_message')->where(array('id'=>$id))->save(array('check_status'=>3));
					$this->success('发送成功',U('Message/sendHistory',array('token'=>$this->token)));exit;
				}else{
					$this->error('发送失败,'.$preview_result['errmsg']);
				}
		}elseif($res['errorno'] == 40001){
			$this->error('access_token获取失败');
		}else{
			$this->error('发送失败,请线下通知你的审核人回复任意关键词跟该公众号互动一次');
		}
	}
	//立即发送
	public function tosendAll(){
		if (IS_GET){
			$id 	= $this->_get('id','intval');
			$info 	= M('Send_message')->where(array('token'=>$this->token,'id'=>$id))->find();
			if($info['check_status'] != 1){
				$this->error('审核状态不是通过状态,暂时不能发送');
			}
			$apiOauth 	= new apiOauth();
			$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
			
			if ($access_token){
				//OPENID就发送给个人
				$openid 	= explode(',', $info['openid']);
				
				if($info['send_type'] == 1){
					$sendrt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token,'{"filter":{"group_id":"'.$info['groupid'].'"},"mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
				}else if($info['send_type'] == 2){
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token,'{"touser":'.json_encode($openid).',"mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
				}else{
					$sendrt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token,'{"filter":{"is_to_all":true},"mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
				}

				if($sendrt['rt']==false){
					$this->error('操作失败,curl_error:'.$sendrt['errorno']);
				}else{
					M('Send_message')->where(array('id'=>$id))->save(array('msg_id'=>$sendrt['msg_id'],'status'=>1,'time'=>time()));
					$this->success('发送任务已经启动，群发可能会在20分钟左右完成',U('Message/sendHistory',array('token'=>$this->token)));
				}

			}else {
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
		}
	}
	//手动发送,jm73com
	
	public function AutoSendMessage(){
		if($this->token == ""){return false;}
		if (IS_GET){
			$id 	= $this->_get('id','intval');
			$info 	= M('Send_message')->where(array('token'=>$this->token,'id'=>$id))->find();

			$apiOauth 	= new apiOauth();
			$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
			
			if ($access_token){
				//OPENID就发送给个人
				$openid 	= explode(',', $info['openid']);
				
				if($info['send_type'] == 1){
								$sendrt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token,'{"filter":{"group_id":"'.$info['groupid'].'"},"mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
							}else if($info['send_type'] == 2){
								$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token,'{"touser":'.json_encode($openid).',"mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
							}else{
								$sendrt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token,'{"filter":{"is_to_all":true},"mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
								/****最新的微信群发文档 必须要带上"is_to_all":true才可以，不然报40152*****/
							}

				if($sendrt['rt']==false){
					$this->error('操作失败,curl_error:'.$sendrt['errorno']);
				}else{
					M('Send_message')->where(array('id'=>$id))->save(array('msg_id'=>$sendrt['msg_id'],'status'=>2,'time'=>time()));
					$this->success('发送任务已经启动，群发可能会在20分钟左右完成',U('Message/sendHistory',array('token'=>$this->token)));
				}

			}else {
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
		}
	}
	//选择粉丝弹框
	public function message_fans(){
		$group 	= $this->_request('group','intval');
		$name  	= $this->_request('name','trim');
		$sex 	= $this->_request('sex','intval');
		$province 	= $this->_request('province','trim');
		$city 	= $this->_request('city','trim');
		$mid = $this->_request('mid','intval');
		$where 	= array('token'=>$this->token);
		if($group){
			$where['g_id'] 	= $group;
		}

		if ($name != "") {
			if (preg_match("/^([0-9]){6,}$/", $name)) {
				$wecha_id = M("userinfo")->where(array("tel" => $name, "token" => $this->token))->getField("wecha_id");
				$where["openid"] = $wecha_id;
			}
			else {
				$where["nickname"] = array("like", "%" . $name . "%");
			}

			$this->assign("name", $name);
		}
		if($sex != '' && $sex != 3){
			$where['sex'] = $sex;
			$this->assign('sex',$sex);
		}
		if($province != "" && $city != ""){
			$where['province'] = array('like','%'.mb_substr($province,0,2,'utf-8').'%');
			$where['city'] = array('like','%'.mb_substr($city,0,2,'utf-8').'%');
			$this->assign('province',$province);
			$this->assign('city',$city);
		}
		$count 	= M('Wechat_group_list')->where($where)->count();
		$page 	= new Page($count,10);

		$list 	= M('Wechat_group_list')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($list as $key => $val) {
			if($val['g_id']){
				$list[$key]['group_name'] 	= M('Wechat_group')->where(array('wechatgroupid'=>$val['g_id']))->getField('name');
			}else{
				$list[$key]['group_name'] 	= '未分组';
			}
		}
		$fans_ids = '';
		$cache_name = ($_REQUEST["cache_name"] ? $this->token . "_" . trim($_REQUEST["cache_name"]) : $this->token . "_logfans");
		$fans_ids .= S($cache_name);
		if($fans_ids != ""){
			$this->assign('selected_nums',substr_count($fans_ids,','));
			$this->assign('selected_fans',$fans_ids);
		}
		$this->assign('all_fans',$count);
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->assign("cache_name", trim($_REQUEST["cache_name"]));
		$this->display();
	}
	//已选粉丝弹框
	public function message_fans_selected(){
		$group 	= $this->_request('group','intval');
		$name  	= $this->_request('name','trim');
		$sex 	= $this->_request('sex','intval');
		$province 	= $this->_request('province','trim');
		$city 	= $this->_request('city','trim');
		$mid = $this->_request('mid','intval');
		$where 	= array('token'=>$this->token);
		if($group){
			$where['g_id'] 	= $group;
		}

		if ($name != "") {
			if (preg_match("/^([0-9]){6,}$/", $name)) {
				$wecha_id = M("userinfo")->where(array("tel" => $name, "token" => $this->token))->getField("wecha_id");
				$where["openid"] = $wecha_id;
			}
			else {
				$where["nickname"] = array("like", "%" . $name . "%");
			}

			$this->assign("name", $name);
		}
		if($sex != '' && $sex != 3){
			$where['sex'] = $sex;
			$this->assign('sex',$sex);
		}
		if($province != "" && $city != ""){
			$where['province'] = array('like','%'.mb_substr($province,0,2,'utf-8').'%');
			$where['city'] = array('like','%'.mb_substr($city,0,2,'utf-8').'%');
			$this->assign('province',$province);
			$this->assign('city',$city);
		}
		$all_fans 	= M('Wechat_group_list')->where($where)->count();
		$this->assign('all_fans',$all_fans);
		$fans_ids = '';
		$cache_name = ($_REQUEST["cache_name"] ? $this->token . "_" . trim($_REQUEST["cache_name"]) : $this->token . "_logfans");
		$fans_ids .= trim(S($cache_name), ",");
		if($fans_ids == ''){
			$this->assign('selected_nums',0);
			$this->display();//缓存里面没有粉丝id时
			exit;
		}
		if(trim($fans_ids) != ''){
			$where['openid']  = array('in',$fans_ids);
		}
		$count 	= M('Wechat_group_list')->where($where)->count();
		$page 	= new Page($count,10);

		$list 	= M('Wechat_group_list')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();

		$group_name =  M('Wechat_group')->where(array('token'=>$this->token))->select();
		$groupArr = array();
		foreach ($group_name as $key => $value) {
			$groupArr[$value['wechatgroupid']] = $value['name'];
		}
		foreach ($list as $key => $val) {
			if($val['g_id']){
				$list[$key]['group_name'] 	= $groupArr[$val['g_id']];
			}else{
				$list[$key]['group_name'] 	= '未分组';
			}
		}
		$this->assign("selected_nums", substr_count(S($cache_name), ","));
		$this->assign('list',$list);
		$this->assign("cache_name", trim($_REQUEST["cache_name"]));
		$this->assign('page',$page->show());
		$this->display();
	}
	//列表页的预览
	public function preview(){
		$id 	= $this->_get('id','intval');
		$info 	= M('Send_message')->where(array('token'=>$this->token,'id'=>$id))->find();

		if(IS_POST){
			$openid 	= $this->_post('openid','trim');
			if($openid){
				$apiOauth 	= new apiOauth();
				$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
				if ($access_token){
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token,'{"touser":"'.$openid.'","mpnews":{"media_id":"'.$info['mediaid'].'"},"msgtype":"mpnews"}');
					if($sendrt['rt']==false){
						$this->error('操作失败,curl_error:'.$sendrt['errorno']);
					}else{
						$this->success('预览消息发送');
					}
				}else{
					$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
				}

			}else{

				$this->error('请填写openid');

			}
		}else{
			$this->display();
		}
	}

	public function getOpenid(){
		$name 	= $this->_get('name','trim');
		$where 	= array('token'=>$this->token,'nickname'=>$name);
		$openid = M('Wechat_group_list')->where($where)->getField('openid');

		if($openid){
			echo json_encode(array('error'=>0,'openid'=>$openid));
		}else{
			echo json_encode(array('error'=>1,'info'=>'没有找到粉丝'));
		}
	}

	public function del(){
		$id 	= $this->_get('id','intval');
		$info 	= M('Send_message')->where(array('token'=>$this->token,'id'=>$id))->find();
		if($info['schedule_type'] == 1){
			//$this->DelTask($info['taskname'],$info['schedule_time']);
		}
		if($info['msg_id']){
			$apiOauth 	= new apiOauth();
			$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);

			if ($access_token){
				$sendrt 		= $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token='.$access_token,'{"msg_id":'.$info['msg_id'].'}',0);

				//if($sendrt['rt']==false){
				//	$this->error('操作失败,curl_error:'.$sendrt['errorno']);
				//}else{
					if(M('Send_message')->where(array('token'=>$this->token,'id'=>$id))->delete()){
						$this->success('删除成功');
					}
				//}
			}else{
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
		}else{
			if(M('Send_message')->where(array('token'=>$this->token,'id'=>$id))->delete()){
				$this->success('删除成功');
			}
		}
	}

	public function item(){
		if (isset($_GET['id'])){
			$info=M('Send_message')->where(array('token'=>$this->token,'id'=>intval($_GET['id'])))->find();
			$this->assign('info',$info);
		}
		if ($info['msgtype']=='news'){
			$imgids=explode(',',$info['imgids']);
			$imgID=0;
			if ($imgids){
				foreach ($imgids as $ii){
					if (intval($ii)){
						$imgID=$ii;
					}
				}
			}
			$thisNews=M('Img')->where(array('id'=>$imgID))->find();
			$this->assign('img',$thisNews);
		}
		$this->display();
	}

	public function send(){
		$fans=M('Wechat_group_list')->where(array('token'=>$this->token))->order('id ASC')->select();
		//$fans=array(array('openid'=>'oCsUfuC0mqT4VM6JjbggaLvzGEXI'));
		$i=intval($_GET['i']);
		$count=count($fans);
		$thisMessage=M('Send_message')->where(array('id'=>intval($_GET['id'])))->find();
		if ($i<$count){
			$fan=$fans[$i];
			$apiOauth 	= new apiOauth();
			$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
			if ($access_token){	
				switch ($thisMessage['msgtype']){
					case 'text':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"text","text":{"content":"'.$thisMessage['text'].'"}}';
						break;
					case 'image':
					case 'voice':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","'.$thisMessage['msgtype'].'":{"media_id":"'.$thisMessage['mediaid'].'"}}';
						break;
					case 'video':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","'.$thisMessage['msgtype'].'":{"media_id":"'.$thisMessage['mediaid'].'","description":"'.$thisMessage['text'].'","title":"'.$thisMessage['title'].'"}}';
						break;
					case 'music':
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","'.$thisMessage['msgtype'].'":{"media_id":"'.$thisMessage['mediaid'].'"}}';
						break;
					case 'news':
						$imgids=explode(',',$thisMessage['imgids']);
						$imgID=0;
						if ($imgids){
							foreach ($imgids as $ii){
								if (intval($ii)){
									$imgID=$ii;
								}
							}
						}
						$thisNews=M('Img')->where(array('id'=>$imgID))->find();
						if ($thisNews['url']){
							$url=$this->convertLink($thisNews['url']);
							
						}else {
							$url=C('site_url').U('Wap/Index/content',array('token'=>$this->token,'wecha_id'=>$fan['openid'],'id'=>$thisNews['id']));
						}
						$thisNews['title']=str_replace('"','\"',$thisNews['title']);
						$data='{"touser":"'.$fan['openid'].'","msgtype":"'.$thisMessage['msgtype'].'","news":{"articles":[{"title":"'.$thisNews['title'].'","description":"'.$thisNews['text'].'","url":"'.$url.'","picurl":"'.$thisNews['pic'].'"}]}}';
						break;
				}
				//
				$rt=$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data,0);
				if($rt['rt']==false){
					//$this->error('操作失败,curl_error:'.$rt['errorno']);
				}else{
					M('Send_message')->where(array('id'=>intval($thisMessage['id'])))->setInc('reachcount');
				}
				$i++;
				$this->success('发送中:'.$i.'/'.$count,U('Message/send',array('id'=>$thisMessage['id'],'i'=>$i)));
			}else {
				$this->error('获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg);
			}
		}else {
			$this->success('发送完成，发送成功'.$thisMessage['reachcount'].'条',U('Message/sendHistory'));
		}
	}
	
	public function img(){
		$from = isset($_GET['from']) ? htmlspecialchars($_GET['from']) : '';
		$db=M('Img');
		$where=array('token'=>$this->token);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'pic'=>$item['pic'],'info'=>str_replace(array("&#039;",'\'',"\r\n","\r","\n",'"'),'',$item['text']),'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=content&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->assign('from', $from);
		$this->assign('is_syn',$this->wxuser['is_syn']);
		$this->display();
	}
	
	function curlPost($url, $data,$showError=0){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if (intval($js['errcode']==0)){
				return array('rt'=>true,'errorno'=>0,'media_id'=>$js['media_id'],'msg_id'=>$js['msg_id']);
			}else {
				if ($showError){
					if($js['errcode'] == '40130'){
						$this->error('抱歉，群发消息至少要选择两个人以上。');
					}else{
						$this->error('发生了Post错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
					}
				}else{
					return array('rt'=>false,'errorno'=>$js['errcode'],'media_id'=>$js['media_id'],'msg_id'=>$js['msg_id']);
				}
			}
		}
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
	//发送验证码
	public function commonSmsVerify(){
		$mobile = $this->_post('mobile');
		$token = $this->_post('token','trim');
		if(empty($mobile) || empty($token)){
			exit('参数错误');
		}
		$_SESSION['comm_rand_num'][$mobile]='';
		$_SESSION['comm_send_time'][$mobile] = '';
		$rand_num = rand(100000,999999);
		$_SESSION['comm_rand_num'][$mobile] = $rand_num;
		$_SESSION['comm_send_time'][$mobile] = $_SERVER['REQUEST_TIME'];
		$params = array();
		$params['sms'] = array('token'=>$token, 'mobile'=>$mobile,'content'=>'您的验证码是：'.$rand_num.'。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
		$return_status = MessageFactory::method($params, 'SmsMessage');
		if($return_status == 0 && strlen($return_status) == 1){exit('done');}elseif($return_status == null){exit('not_buy');}else{exit('短信发送失败,请稍后再试');}
	}
	//确认验证码公共方法
	public function commonphone(){
		if(IS_POST){
			$data = array();
			$data['verifier_phone'] = $_POST['verifier_phone'];
			$data['verifier_code'] = (int)$_POST['verifier_code'];
			if($data['verifier_phone'] == ''){
				exit('参数错误,手机号未获取到');
			}elseif($data['verifier_code'] == ''){
				exit('验证码不能为空');
			}elseif($_SERVER['REQUEST_TIME'] - $_SESSION['comm_send_time'][$data['verifier_phone']] > 1800){
				exit('短信验证码过期失效,请重新获取');
			}elseif($data['verifier_code'] != $_SESSION['comm_rand_num'][$data['verifier_phone']]){
				exit('短信验证码错误');
			}else{
				exit('done');
			}
		}
		$this->assign('settype',($_GET['settype'] ? $_GET['settype'] : 1));//更换类型 1 更换审核人  2 更换手机号
		$this->assign('id',$_GET['id']);
		$this->assign('phone',$_GET['phone']);
		$this->display();
	}
	//更换审核人
	public function change_verifier(){
		if(IS_POST){
			$openid = trim($_POST['openid']);
			$nickname = trim($_POST['nickname']);
			if($openid == ''){
				exit('审核人openid不能为空');
			}
			if($nickname == ''){
				exit('审核人昵称不能为空');
			}
			if($_POST['id'] == ''){
				exit('参数错误');
			}
			$update = M('verifier_message')->where(array('id'=>(int)$_POST['id']))->save(array('nickname'=>$nickname,'openid'=>$openid));
			if($update){
				exit('done');
			}else{
				exit('更换审核人失败');
			}
		}
		$set = M('verifier_message')->where(array('id'=>(int)$_GET['id']))->find();
		if(empty($set)){
			$this->error('参数错误');
		}
		$this->assign('id',$_GET['id']);
		$this->assign('token',$_GET['token']);
		$this->assign('set',$set);
		$this->display();
	}
	//更换手机号
	public function change_phone(){
		if(IS_POST){
			if($_POST['id'] == ''){
				exit('参数错误');
			}
			$data = array();
			$data['verifier_phone'] = $_POST['verifier_phone'];
			$data['verifier_code'] = (int)$_POST['verifier_code'];
			$data['token'] = trim($_POST['token']);
			if($data['verifier_phone'] == ''){
				exit('绑定的手机号不能为空');
			}elseif($data['verifier_code'] == ''){
				exit('验证码不能为空');
			}elseif($_SERVER['REQUEST_TIME'] - $_SESSION['comm_send_time'][$data['verifier_phone']] > 1800){
				exit('短信验证码过期失效,请重新获取');
			}elseif($data['verifier_code'] != $_SESSION['comm_rand_num'][$data['verifier_phone']]){
				exit('短信验证码错误');
			}
			$update = M('verifier_message')->where(array('id'=>(int)$_POST['id']))->save($data);
			if($update){
				exit('done');
			}else{
				exit('修改设置失败');
			}
		}
		$set = M('verifier_message')->where(array('id'=>(int)$_GET['id']))->find();
		if(empty($set)){
			$this->error('参数错误');
		}
		$this->assign('id',$_GET['id']);
		$this->assign('token',$_GET['token']);
		$this->assign('phone',$set['verifier_phone']);
		$this->display();
	}
	//选中粉丝
	public function select_fans(){
		$fansid = trim($_POST['openid']);
		$token = trim($_POST['token']);
		$cache_name = ($_POST["cache_name"] ? $token . "_" . trim($_POST["cache_name"]) : $token . "_logfans");

		if(strpos(S($cache_name), $fansid) === false){
			$cache_data = S($cache_name).','.$fansid;
			S($cache_name,$cache_data);
			echo trim($cache_data,',').',';
			exit;
		}else{
			exit('no');
		}
	}
	//取消选中的粉丝
	public function cancel_select_fans(){
		$fansid = trim($_POST['openid']);
		$token = trim($_POST['token']);
		$cache_name = ($_POST["cache_name"] ? $token . "_" . trim($_POST["cache_name"]) : $token . "_logfans");

		if(strpos(S($cache_name), $fansid) !== false){
			$cache_data = str_replace(','.$fansid, '', S($cache_name));
			if($cache_data == ''){
				S($cache_name,null);
				exit('no');
			}else{
				S($cache_name,$cache_data);
				echo trim($cache_data,',').',';
				exit;
			}
		}
	}
	//发送给任务服务器的参数
	/*
	public function SendTask($send_time = ''){
		if($send_time == ''){ return false;}
		$postdata = array(
			'name'=>'SendMessage_'.uniqid(),
			'exec_url'=>$this->siteUrl.'/index.php?g=Wap&m=Index&a=AutoSendMessage&token='.$this->token.'&rget=1',
			'exec_time'=> strtotime($send_time),
			'unique_id' => updateSync::uniqueID(),
			'domain' => _getTopDomain(),
			'remark'=>$send_time.'发送',
		);
		$json = json_encode($postdata);
		$taskurl = task_link().'/cron/create';
		$result = HttpClient::getInstance()->post($taskurl,array('post'=>array('cron_info'=>$json)));
		$re = json_decode($result,true);
		if($re['status'] == 0){
			return $postdata['name'];
		}else{
			return false;
		}
	}
	//删除任务服务器的任务
	public function DelTask($name = '',$exec_time = ''){
		if($name == "" || $exec_time == ''){ return false;}
			$postdata = array(
			'name'=> $name,
			'exec_time'=> $exec_time,
			'unique_id' => updateSync::uniqueID(),
			'domain' => _getTopDomain()
		);
		$json = json_encode($postdata);
		$taskurl = task_link().'/cron/delete';
		$result = HttpClient::getInstance()->post($taskurl,array('post'=>array('cron_info'=>$json)));
		$re = json_decode($result,true);
		if($re['status'] == 0){
			return true;
		}
		return false;
	}*/
	public function clearMessage(){
		S($this->token.'_logfans',null);
	}
	public function checkmessage(){
		$mid = (int)$_GET['id'];
		$token = trim($_GET['token']);
		$imgids = M('send_message')->where(array('id'=>$mid))->getField('imgids');
		if($imgids == ""){
			$this->error('图文不存在');
		}
		$messages = array();
		if(strpos($imgids, ",") === false){
			$messages[0] = M('img')->where(array('id'=>(int)$imgids))->find();
		}else{
			$messages = M('img')->where(array('id'=>array('in',$imgids)))->order('field(id,'.$imgids.')')->select();
		}
		$this->assign('end_message',$messages[0]);
		array_shift($messages);
		$this->assign('messages',$messages);
		$this->display();
	}
	function test(){
			/*$verifier = M('verifier_message')->where(array('token'=>$this->token))->find();
		$media_id = 'bht-LaeEE8XKn-aNeRBrvXFcGdsHrO6IDKUY_J683jVFZHnG_zCQIJzPUeo42HKu';
					$apiOauth 	= new apiOauth();
			$access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
		$text = '<a href=\"'.$this->siteUrl.'/index.php?g=Wap&m=Index&a=CheckMessage&mid='.$media_id.'&check_status=1&token='.$this->token.'\">审核通过</a>       <a href=\"'.$this->siteUrl.'/index.php?g=Wap&m=Index&a=CheckMessage&mid='.$media_id.'&check_status=2&token='.$this->token.'\">审核不通过</a>';
		$checkdata = '{"touser":"'.$verifier['openid'].'","msgtype":"text","text":{"content":"'.$text.'"}}';
		$this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$checkdata);*/
		dump($_SESSION['comm_rand_num']);
	}
}
?>