<?php
class CustomAction extends UserAction{
	public $token;
	public $set_db;
	public $limit_db;
	public $field_db;
	public $info_db;
	public function _initialize(){
		parent::_initialize();
		$this->canUseFunction('Custom');
		$this->token 	= session('token');
		$this->set_db 	= D('Custom_set');
		$this->limit_db	= M('Custom_limit');
		$this->field_db	= D('Custom_field');
		$this->info_db	= D('Custom_info');
		$set_id 		= $this->_get('set_id','intval');
		$this->assign('set_id',$set_id);
	}

	public function index(){
		$where 	= array('token'=>$this->token);
		
		if($this->_post('search','trim')){
			$where['title|keyword']	= array('like','%'.$this->_post('search','trim').'%');
		}
		
		$count		= $this->set_db->where($where)->count();
		$Page       = new Page($count,15);
		$list 		= $this->set_db->where($where)->order('set_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		

		foreach($list as $key=>$value){
			if ($value["is_pay"] == 1) {
				$sql = "SELECT COUNT(*) AS tp_count FROM " . C("DB_PREFIX") . "custom_info i," . C("DB_PREFIX") . "custom_order o WHERE i.info_id=o.state AND i.token = o.token AND i.set_id = " . $value["set_id"] . " LIMIT 1";
				$total = $this->info_db->query($sql);
				$list[$key]["count"] = $total[0]["tp_count"];
			}
			else {
		  	        $list[$key]['count']	= $this->info_db->where(array('token'=>$this->token,'set_id'=>$value['set_id']))->count();
			}
		}

		$this->assign('count',$count);
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}

	public function set(){

		$keyword_db	= M('keyword'); //关键词
		$where 		= array('token'=>$this->token,'set_id'=>$this->_get('set_id','intval'));
		$set_info	= $this->set_db->where($where)->find();

		if(IS_POST){
			//设置表数据
			$_POST['token']		= $this->token;	
			$_POST['enddate']	= strtotime($this->_post('enddate','trim'));
			$_POST['succ_info'] = empty($_POST['succ_info'])?'提交成功':$this->_post('succ_info','trim');
			$_POST['err_info']	= empty($_POST['succ_info'])?'提交失败':$this->_post('err_info','trim');	

			//限制表数据
			if($_POST['endtime']){
				$limit['enddate']		=	strtotime($this->_post('end_value','trim').' 23:59:59');		
			}else{
				$limit['enddate']		= '';
			}
           //限制总提交用户数  jm73com
            if($_POST['apiece_total']){
				$limit['apiece_total']	=	$this->_post('apiece_value','intval');		
			}else{
				$limit['apiece_total']	= 0;
			}
           //
			if($_POST['today_total']){
				$limit['today_total']	=	$this->_post('today_value','intval');		
			}else{
				$limit['today_total']	= 0;
			}
			if($_POST['sub_total']){
				$limit['sub_total']		=	$this->_post('sub_value','intval');
			}else{
				$limit['sub_total']		= 0;
			}

			$_POST['price'] = (string)($_POST['price']*100);

			/*修改添加判断*/
			if($set_info){
				//修改时不能再次修改支付相关设置
				unset($_POST['is_pay']);
				unset($_POST['pay_name']);
				unset($_POST['price']);
				if($this->set_db->create()){
					$_POST['detail']	= $this->_post('detail','trim');
					$this->set_db->where($where)->save($_POST);//更新设置表
					$this->limit_db->where(array('limit_id'=>$set_info['limit_id']))->save($limit);//更新限制表

/*					$keyword['pid']		= $this->_get('set_id','intval');
                	$keyword['module']	= 'Custom';
               		$keyword['token']	= $this->token;
               		$keyword['keyword']	= $this->_post('keyword','trim');
                	$keyword_db->where(array('token'=>$this->token,'pid'=>$this->_post('set_id','intval'),'module'=>'Custom'))->save($keyword);//更新关键词表*/
                	$this->handleKeyword($this->_post('set_id','intval'),'Custom',$this->_post('keyword','trim'));	
                	$this->success('修改成功',U('Custom/set',array('token'=>$this->token,'set_id'=>$this->_get('set_id','intval'))));
				}else{
					$this->error($this->set_db->getError());
				}
            
			}else{//添加


				$limit_id = $this->limit_db->add($limit);

				$_POST['addtime']		= time(); //表单创建时间
				$_POST['limit_id']		= $limit_id;

				if($this->set_db->create()){
					$_POST['detail']	= $this->_post('detail','trim');
					$id 				= $this->set_db->add($_POST);
/*					$keyword['pid']		= $id;
                	$keyword['module']	= 'Custom';
               		$keyword['token']	= $this->token;
               		$keyword['keyword']	= $this->_post('keyword','trim');
                	$keyword_db->add($keyword);*/
                	$this->handleKeyword($id,'Custom',$this->_post('keyword','trim'));
                	$this->success('添加成功',U('Custom/index',array('token'=>$this->token)));
				}else{

					$this->error($this->set_db->getError());
				}
			}

		}else{

			if(!empty($set_info)){	//限制信息
				$limit_info	= $this->limit_db->where(array('limit_id'=>$set_info['limit_id']))->find();	
			}
			
			$now	= strtotime('+1 day');//默认截止日期value值
			$this->assign('now',$now);
			$this->assign('limit_info',$limit_info);
			$set_info['price'] = $set_info['price']/100;
			$this->assign('set',$set_info);
			$this->assign('followList',selectList(array(
				array('value'=>'0','text'=>'是'),
				array('value'=>'1','text'=>'否')
			),$set_info['follow'],'1','checked'));
			$this->assign('smsList',selectList(array(
				array('value'=>'1','text'=>'开启'),
				array('value'=>'0','text'=>'关闭')
			),$set_info['sms'],'0','checked'));
			$this->display();
		}
	
	}

	public function info()
	{
		$set_id		= $this->_get('set_id','intval');
		$customSet=M('custom_set');
		$setInfo=$customSet->where(array('set_id'=>$set_id))->find();
		if(empty($setInfo))
			$this->error('表单不存在');
		//筛选条件
		$where 		= array('info.token'=>$this->token,'info.set_id'=>$set_id);
		$tmp=array();
		if($_GET['start_time']!='')
		{
			$tmp[]= array('egt',strtotime(I('get.start_time')));
		}
		if($_GET['end_time']!='')
		{
			$tmp[] = array('lt',strtotime(I('get.end_time')));
		}
		if(!empty($tmp))
			$where['info.add_time']=$tmp;
		if($_GET['orderid']!=''&&$setInfo['is_pay']=='1')
		{
			$where['order.orderid']=I('get.orderid');
		}
		if($_GET['paid']!=''&&$setInfo['is_pay']=='1')
		{
			$where['order.paid']=I('get.paid');
		}
		if($_GET['user_name']!='')
		{
			$where['info.user_name']=array('like','%'.I('get.user_name').'%');
		}
		$prefix=C('DB_PREFIX');
		$tabs["{$prefix}custom_info"]='info';
		if($setInfo['is_pay']=='1')
		{
			$tabs["{$prefix}custom_order"]='order';
			$where['_string']='info.info_id=order.state';
		}
		$count		=$this->info_db->table($tabs)->where($where)->count();
		$Page       = new Page($count,15);
		$list		= $this->info_db->table($tabs)->where($where)->order('info.add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//解决前端表格显示不一致bug @VIFNNcms
		$fields = $this->field_db->where(array('token'=>$this->token,'set_id'=>$set_id))->order('sort desc')->limit('5')->field('field_name,field_type')->select();
		foreach($list as $key=>$value){
			$list[$key]['ex_info'] = unserialize($value['sub_info']);
			$list[$key]['valueList']=array();
			foreach($fields as $key2=>$value2)
			{
				$tmp=array('type'=>$value2['field_type'],'value'=>'未填写','name'=>$value2['field_name']);
				$tmp["value"] = $list[$key]["ex_info"][$key2]["value"];
				$tmp['value']=$tmp['type']=='image'?((empty($tmp['value'])||$tmp['value']=='未填写')?'未上传':explode(',',$tmp['value'])):$tmp['value'];
				$list[$key]['valueList'][]=$tmp;
			}
		}
		//统计
		if($setInfo['is_pay']=='1')
		{
			$where2=array('_string'=>'info.info_id=order.state');
			$where2['info.set_id']=$set_id;
			$total=$this->info_db->table($tabs)->where($where2)->count();
			$where2['order.paid']='0';
			$notPayNum=$this->info_db->table($tabs)->where($where2)->count();
			$where2['order.paid']='1';
			$payNum=$this->info_db->table($tabs)->where($where2)->count();
			if ($setInfo["set_id"] == 118) {
				echo $this->info_db->getLastSql();
			}
			$totalAmount=$this->info_db->table($tabs)->where($where2)->sum('order.price');
			$this->assign('total',$total);
			$this->assign('notPayNum',$notPayNum);
			$this->assign('payNum',$payNum);
			$this->assign('totalAmount',$totalAmount?$totalAmount:0);
		}
		$this->assign('thisForm',$setInfo);
		$this->assign('fields',$fields);
		$this->assign('page',$Page->show());
		$this->assign('set_id',$set_id);
		$this->assign('list',$list);
		$this->display();
	}

	//回复
	public function reply()
	{
		$customReply=M('custom_reply');
		if(IS_GET)
		{
			$where 		= array('info_id'=>$this->_get('info_id','intval'));
			$where['token']=$this->token;
			$result		= $customReply->where($where)->order(array('create_time'=>'desc'))->select();
			$this->assign('_list',$result);
			$this->assign('_info',array('info_id'=>$where['info_id']));
			$this->display();
		}
		elseif(IS_POST)
		{
			$info_id=$this->_post('info_id','intval');
			$content=$this->_post('reply_content','string');
			if(empty($info_id))
				$this->error('提交信息不存在');
			if(empty($content))
				$this->error('回复内容不能为空');
			$where 		= array('token'=>$this->token,'info_id'=>$info_id);
			$result=$this->info_db->where($where)->find();
			if(empty($result))
				$this->error('提交信息不存在');
			$data=array('reply_content'=>$content);
			$data['create_time']=time();
			$data['info_id']=$info_id;
			$data['token']=$this->token;
			$rid=$customReply->add($data);
			if(!$rid)
				$this->error('提交失败');
			//OPENTM203574543 认证服务号可以发送模板消息
			if($this->wxuser['winxintype']=='3'&&!empty($result['wecha_id'])&&$result['wecha_id']!='null')
			{
				$tplNes=new templateNews();
				$tplData=array('first'=>'您好！您收到一条万能表单回复消息！','keyword1'=>$this->wxuser['wxname']);
				$tplData['keyword2']=format_time(time(),'Y-m-d H:i');
				$tplData['keyword3']=short($content,20);
				$tplData['wecha_id']=$result['wecha_id'];
				$param=array('token'=>$this->token);
				$param['id']=$result['set_id'];
				$param['wecha_id']=$result['wecha_id'];
				$tplData['remark']='若以上显示不完整，请点击详情查看完整内容';
				$tplData['href']=$this->siteUrl.U('Wap/Custom/spoor',$param);
				$tplResult=$tplNes->sendTempMsg('OPENTM203574543',$tplData);
				//模板消息发送失败
				if(!$tplResult||!$tplResult['rt'])
				{
					$tplResult=$tplResult?$tplResult:array('errorno'=>'-1');
					Log::write('万能表单回复通知发送失败,errorno:'.$tplResult['errorno'].',wecha_id:'.$result['wecha_id']);
					$tplNes->sendTempMsg('OPENTM203574543',$tplData);//重试一次
				}
			}
			$this->success('提交成功');
		}
	}

	//删除回复
	public function delReply()
	{
		$customReply=M('custom_reply');
		$id=$this->_post('id','intval');
		if(empty($id))
			$this->error('请选择要删除的回复记录');
		$where 		= array('id'=>$id,'token'=>$this->token);
		$num=$customReply->where($where)->delete();
		if(!$num)
			$this->error('删除失败');
		$this->success('删除成功');
	}

	public function detail(){
		$where 		= array('token'=>$this->token,'info_id'=>$this->_get('info_id','intval'));
		$info		= $this->info_db->where($where)->find();
		$sub_info=unserialize($info['sub_info']);
		for($i=0;$i<count($sub_info);$i++)
		{
			if($sub_info[$i]['type']=='image')
			{
				$tmp=explode(',',$sub_info[$i]['value']);
				for($j=0;$j<count($tmp);$j++)
				{
					$path=str_replace($this->siteUrl,'.',$tmp[$j]);
					$tmp[$j]=array('path'=>$tmp[$j]);
					$tmp[$j]['name']=basename($path);
				}
				$sub_info[$i]['value']=$tmp;
			}
		}
		$this->assign('sub_info',$sub_info);
		$this->assign('set_id',$info['set_id']);
		$this->assign('info',$info);
		$this->display();
	}

	/*删除信息*/
	public function infoDel(){
		$info_id	= $this->_get('info_id','intval');
		$token		= $this->token;
		$where		= array('token'=>$token,'info_id'=>$info_id);
		M('custom_order')->where(array('token'=>$this->token,'state'=>$info_id))->save(array('state'=>0));
		$infoList=$this->info_db->where($where)->field('info_id,token')->select();
		$customReply=M('custom_reply');
		for($i=0;$i<count($infoList);$i++)
		{
			$customReply->where(array('info_id'=>$infoList[$i]['info_id'],'token'=>$infoList[$i]['token']))->delete();
		}
		if($this->info_db->where($where)->delete()){
			$this->success('删除信息成功');
		}	
	}
	
	/*删除设置*/
	public function del(){
		$set_id		= $this->_get('set_id','intval');
		$where		= array('token'=>$this->token,'set_id'=>$set_id);
		$limit_id 	= $this->set_db->where($where)->getField('limit_id');  //限制表id
		$customReply=M('custom_reply');
		if($this->set_db->where($where)->delete()){	
			M('keyword')->where(array('pid'=>$set_id))->delete();				//清除关键词表数据
			$this->limit_db->where(array('limit_id'=>$limit_id))->delete();  	//清除限制表数据
			$infoList=$this->info_db->where(array('token'=>$this->token,'set_id'=>$set_id))->select();
			for($i=0;$i<count($infoList);$i++)
			{
				$customReply->where(array('info_id'=>$infoList[$i]['info_id'],'token'=>$infoList[$i]['token']))->delete();
			}
			$this->info_db->where(array('token'=>$this->token,'set_id'=>$set_id))->delete();	//清除提交表单数据
			$this->field_db->where(array('token'=>$this->token,'set_id'=>$set_id))->delete();	//清除自定义表单数据
			$this->success('删除配置成功',U('Custom/index',array('token'=>$this->token)));
		}	

	}

	/*导出excel*/
	public function exportForms(){
		$set_id		= $this->_get('set_id','intval');
		$customSet=M('custom_set');
		$setInfo=$customSet->where(array('set_id'=>$set_id))->find();
		if(empty($setInfo))
			$this->error('表单不存在');
		//筛选条件
		$where 		= array('info.token'=>$this->token,'info.set_id'=>$set_id);
		$tmp=array();
		if($_GET['start_time']!='')
		{
			$tmp[]= array('egt',strtotime(I('get.start_time')));
		}
		if($_GET['end_time']!='')
		{
			$tmp[] = array('lt',strtotime(I('get.end_time')));
		}
		if(!empty($tmp))
			$where['info.add_time']=$tmp;
		if($_GET['orderid']!=''&&$setInfo['is_pay']=='1')
		{
			$where['order.orderid']=I('get.orderid');
		}
		if($_GET['paid']!=''&&$setInfo['is_pay']=='1')
		{
			$where['order.paid']=I('get.paid');
		}
		if($_GET['user_name']!='')
		{
			$where['info.user_name']=array('like','%'.I('get.user_name').'%');
		}
		$prefix=C('DB_PREFIX');
		$tabs["{$prefix}custom_info"]='info';
		if($setInfo['is_pay']=='1')
		{
			$tabs["{$prefix}custom_order"]='order';
			$where['_string']='info.info_id=order.state';
		}
		$list		= $this->info_db->table($tabs)->where($where)->order('info.add_time desc')->select();
		$fields = $this->field_db->where(array('token'=>$this->token,'set_id'=>$set_id))->order('sort desc')->field('field_name,field_type')->select();
		foreach($list as $key=>$value){
			$list[$key]['ex_info'] = unserialize($value['sub_info']);
			$list[$key]['valueList']=array();
			foreach($fields as $key2=>$value2)
			{
				$tmp=array('type'=>$value2['field_type'],'value'=>'未填写','name'=>$value2['field_name']);
				$tmp["value"] = $list[$key]["ex_info"][$key2]["value"];
				$tmp['value']=$tmp['type']=='image'?((empty($tmp['value'])||$tmp['value']=='未填写')?'未上传':count(explode(',',$tmp['value'])).'张图片'):$tmp['value'];
				$list[$key]['valueList'][]=$tmp;
			}
		}
		$titles	= array('用户昵称','提交时间');
		for($i=0;$i<count($fields);$i++)
		{
			$titles[]=$fields[$i]['field_name'];
		}
		if($setInfo['is_pay']=='1')
		{
			$titles[]=$setInfo['pay_name'].'（元）';
			$titles[]='订单号';
		}
		$data 	= array();
		foreach($list as $key=>$value){
			$data[$key][] = $value['user_name'];
			$data[$key][]  = date('Y-m-d H:i:s',$value['add_time']);
			foreach($value['valueList'] as $key2=>$value2)
			{
				$data[$key][]="".str_replace("\r\n", "", $value2['value']);
			}
			if($setInfo['is_pay']=='1')
			{
				$data[$key][]=$setInfo['price']/100;
				$data[$key][]=$value['paid']=='1'?$value['orderid']:'未支付';
			}
		}
		//dump($data);
		$exname	= $this->set_db->where(array('token'=>$this->token,'set_id'=>$set_id))->getField('title');
		//下载文件名中不能包含有逗号分隔符，下载文件名不支持双字节字符
		$exname = str_replace(array(',','，','。'), '', $exname);
		$this->exportexcel($data,$titles,$exname);
	}


    /**
        * 导出数据为excel表格
        *@param $data    一个二维数组,结构如同从数据库查出来的数组
        *@param $title   excel的第一行标题,一个数组,如果为空则没有标题
        *@param $filename 下载的文件名
        *@examlpe 
        *$stu = M ('User');
        *$arr = $stu -> select();
        *exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
    */

    function exportexcel($data=array(),$title=array(),$filename='report'){
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");  
        header("Content-Disposition:attachment;filename=".$filename.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)){
            foreach ($title as $k => $v) {
                $title[$k]=iconv("UTF-8", "GBK",$v);
            }
            $title= implode("\t", $title);
	    echo "{$title}\n";
        }
        if (!empty($data)){
            foreach($data as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck]	= "=\"".iconv("UTF-8", "GBK", $cv)."\"";
                }
                $data[$key]=implode("\t", $data[$key]);
                
            }
            echo implode("\n",$data);
        }
    }


/*--------------------------------------------表单管理项-------------------------------------------*/

	public function forms(){
		$set_id		= $this->_get('set_id','intval');
		$list 	= $this->field_db->where(array('set_id'=>$set_id,'token'=>$this->token))->order('sort desc')->select();
		$list	= $this->_createInput($list);

		$this->assign('list',$list);
		$this->display();
	}

	/*生成预览表单*/
	public function _createInput($list){

		foreach($list as $key=>$value){
			$list[$key]['input']	= $this->_getInput($value['field_type']);
		}
		return $list;
	}

	/*设置表单*/
	public function forms_set(){
		$set_id		= $this->_get('set_id','intval');
		$field_id	= $this->_get('field_id','intval');
		$field_info	= $this->field_db->where(array('field_id'=>$field_id,'token'=>$this->token))->find();

		/*POST提交*/
		if(IS_POST){
			if (get_magic_quotes_gpc()){
				$_POST['field_match'] 	= addslashes($_POST['field_match']);
			}
			if($this->field_db->create($_POST)){
				if($field_info){//修改
					$where	= array('token'=>$this->token,'field_id'=>$field_id);	
					$this->field_db->where($where)->save($_POST);
					$this->success('修改成功',U('Custom/forms',array('token'=>$this->token,'set_id'=>$set_id)));
				
				}else{//添加
					if($_POST['field_type']=='image')
					{
						$_POST['file_num']=!empty($_POST['file_num'])?$_POST['file_num']:1;
						$_POST['file_size']=!empty($_POST['file_size'])?$_POST['file_size']:2;
					}
					$_POST['item_name']	= $this->_getItemName($set_id);
					$_POST['token']		= $this->token;
					
					$id 	= $this->field_db->add($_POST);
					$this->success('添加成功',U('Custom/forms',array('token'=>$this->token,'set_id'=>$set_id)));
				}
			
			}else{
				$this->error($this->field_db->getError());
			}

		}else{//设置页
			$this->assign('set',$field_info);
			$this->assign('field_type',$this->_formConf('field_type',$field_info['field_type']));
			$this->assign('field_match',$this->_formConf('field_match',$field_info['field_match']));
			$this->assign('file_num',selectList(array(
				array('value'=>'1','text'=>'1张'),
				array('value'=>'2','text'=>'2张'),
				array('value'=>'3','text'=>'3张'),
				array('value'=>'4','text'=>'4张'),
				array('value'=>'5','text'=>'5张'),
				array('value'=>'6','text'=>'6张'),
				array('value'=>'7','text'=>'7张'),
				array('value'=>'8','text'=>'8张'),
				array('value'=>'9','text'=>'9张')
			),$field_info['file_num']));
			$this->assign('file_size',selectList(array(
				array('value'=>'1','text'=>'1M'),
				array('value'=>'2','text'=>'2M'),
				array('value'=>'3','text'=>'3M'),
				array('value'=>'4','text'=>'4M'),
				array('value'=>'5','text'=>'5M'),
			),$field_info['file_size']));
			$this->display();
		}
	}

	/*删除字段项*/

	public function forms_del(){
		$where = array('token'=>$this->token,'field_id'=>$this->_get('field_id','intval'));
		$info  = $this->info_db->where(array('token'=>$this->token,'set_id'=>$this->_get('set_id','intval')))->getField('sub_info');
		if ($info) {
			$this->error('该表单存在数据，不能删除输入项');
		}
		if($this->field_db->where($where)->delete()){
			$this->success('删除成功');
		}
	}
	/*获取表单name唯一值*/
	public function _getItemName($set_id,$length=5){

		$str 		= 'abcdefghijklmnopqrstuvwxyz0123456789';
		$str_length = strlen($str);
		$item 		= '';
		for($i=0;$i<=$length;$i++){
			$rand  	= mt_rand(0,$str_length);

			$item 	.= $str[$rand];
		}

		$item 		= $item.'_'.$set_id;
		//如果字段名称重复 重新获取
		if($this->field_db->where(array('set_id'=>$set_id,'item_name'=>$tiem))->find()){
			return $this->_getItemName($set_id);
		}else{
			return $item;
		}
	}
	/*字段预览表单*/
	public function _getInput($type){
		$str 	= '';
		switch ($type) {
			case 'text':
				$str = '<input type="text" class="px">';
				break;
			case 'textarea':
				$str = '<textarea rows="2" cols="20" style="height:35px;border:1px solid #cccccc;"></textarea>';
				break;	
			case 'select':
				$str = '<select><option value="">请选择</select>';
				break;
			case 'checkbox':
				$str = '<input type="checkbox">';
				break;	
			case 'radio':
				$str = '<input type="radio">';
				break;
			case 'date':
				$str = '<input type="text" class="px" value="2014-01-01">';
				break;				
		}
		return $str;
	}
	/*字段类型和匹配项*/
	public function _formConf($type='',$select=''){
		$conf 		= array(
			'field_type'	=> array(
				array('value'=>'','text'=>'请选择类型'),
				array('value'=>'text','text'=>'单行文本框'),
				array('value'=>'textarea','text'=>'多行文本框'),
				array('value'=>'checkbox','text'=>'多选选框'),
				array('value'=>'radio','text'=>'单选按钮'),
				array('value'=>'select','text'=>'下拉框'),
				array('value'=>'date','text'=>'日期选择'),
				array('value'=>'image','text'=>'上传图片'),
			),
			'field_match'	=> array(
				array('value'=>'','text'=>'请选择验证方式'),
				array('value'=>'^[\u4e00-\u9fa5\a-zA-Z0-9]+$','text'=>'英文数字汉字'),
				array("value" => "^[\u4e00-\u9fa5\a-zA-Z]+\$", "text" => "英文汉字"),
				array('value'=>'^[A-Za-z]+$','text'=>'英文大小写字符'),			
				array('value'=>'^[1-9]\d*|0$','text'=>'0或正整数'),
				array('value'=>'^[0-9]{1,30}$','text'=>'正整数'),
				array('value'=>'^[-\+]?\d+(\.\d+)?$','text'=>'小数'),
				array("value" => "^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?\$", "text" => "网址"),
				array('value'=>'\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*','text'=>'邮箱'),
				array('value'=>'^13[0-9]{9}$|^14[0-9]{9}$|^15[0-9]{9}$|^17[0-9]{9}$|^18[0-9]{9}$','text'=>'手机'),
			),
		);

		$str  		= '';
		foreach($conf[$type] as $key=>$value){
			if($select == $value['value']){
				$selected	= 'selected';
			}else{
				$selected	= '';
			}

			$str 	.='<option value="'.$value['value'].'" '.$selected.'>'.$value['text'].'</option>';
		}

		return $str;
	}

/*----------------------------------------------提交统计----------------------------------------------------*/

	public function record(){
		$set_id		= $this->_get('set_id','intval');
		if($set_id){
			$set_name = $this->set_db->where(array('token'=>$this->token,'set_id'=>$set_id))->getField('title');
			$this->assign('set_name',$set_name);
		}
		if($this->_get('month')==false){
			$month 	= date('m');
		}else{
			$month 	= $this->_get('month');
		}
		$thisYear=date('Y');
		if($this->_get('year')==false){
			$year 	= $thisYear;
		}else{
			$year 	= $this->_get('year');
		}
		$this->assign('month',$month);
		$this->assign('year',$year);

		$lastyear=$thisYear-1;
		if ($year == $lastyear){
			$yearOption='<option value="'.$lastyear.'" selected>'.$lastyear.'</option><option value="'.$thisYear.'">'.$thisYear.'</option>';
		}else {
			$yearOption='<option value="'.$lastyear.'">'.$lastyear.'</option><option value="'.$thisYear.'" selected>'.$thisYear.'</option>';
		}
		$this->assign('yearOption',$yearOption);
		$where		= array('token'=>$this->token);
		$times 		= $this->_mFristAndLast($month,$year);
		$where['add_time'] = array(array('gt',$times['firstday']),array('lt',$times['lastday']),'and');

		
		if($month == date('m')){
			$day_total = date('d')+2;
		}else{
			$day_total = date('t',strtotime("$year-$month-01"));
		}
		$xml		= '<chart bgColor="ffffff" outCnvBaseFontColor="666666" caption="'.$month.'月提交统计图" xAxisName="模块" yAxisName="数量" showNames="1" showValues="0" plotFillAlpha="50" numVDivLines="10" showAlternateVGridColor="1" bgAlpha="0" showBorder="0" bgColor="ffffff" AlternateVGridColor="e1f5ff" divLineColor="e1f5ff" vdivLineColor="e1f5ff" baseFontColor="666666" baseFontSize="12" borderThickness="0" canvasBorderThickness="0" showPlotBorder="0" plotBorderThickness="0" canvasBorderColor="eeeeee">';
		$categoryStr= '<categories>';
		$dataStr1	= '<dataset seriesName="真实用户" color="69C027" plotBorderColor="69C027">';
		
		for($i=1;$i<=$day_total;$i++){
			$categoryStr .='<category label="'.date('d',mktime(0,0,0,$month,$i,$year)).'"/>';
			$dataStr1	 .='<set value="'.$this->_getSub(1,$month,$i,$year,$set_id).'"/>';    //真实用户
		}
		$categoryStr.='</categories>';
		$dataStr1	.='</dataset>';

		$dataStr2	= '<dataset seriesName="分享用户" color="E9CB50" plotBorderColor="E9CB50">';
		for($i=1;$i<=$day_total;$i++){
			$dataStr2	 .='<set value="'.$this->_getSub(2,$month,$i,$year,$set_id).'"/>';  //匿名
		}

		$dataStr2	.='</dataset>';
		$xml		.= $categoryStr.$dataStr1.$dataStr2.'</chart>';

		$count 				= $this->_getSub(3,'','','',$set_id);
		$today_count 		= $this->_getSub(0,date('m'),date('d'),date('Y'),$set_id);
		$yesterday_count 	= $this->_getSub(0,date('m'),date('d')-1,date('Y'),$set_id);

		$this->assign('count',$count);
		$this->assign('today_count',$today_count);
		$this->assign('yesterday_count',$yesterday_count);
		$this->assign('set_id',$set_id);
		$this->assign('xml',$xml);
		$this->display();
	}

	//获取当天真实用户和匿名用户提交表单数量 flag  0 所有  1 微信用户 3 分享用户 3无时间限制
	public function _getSub($flag=0,$m=0,$d=0,$y=0,$set_id=0){
		
		$where 		= array('token'=>$this->token);
		if($set_id){
			$where['set_id'] 	= $set_id;
		}
		if($flag == 1){
			$where['wecha_id']	= array('NEQ','NULL');
		}
		if($flag == 2){
			$where['wecha_id']	= array('EQ','NULL');
		}
		if($flag != 3){
			$start_time = mktime( 0, 0, 0, $m, $d, $y ); 
			$end_time   = mktime( 23, 59, 59, $m, $d, $y ); 
			$where['add_time'] = array(array('gt',$start_time),array('lt',$end_time),'and');
		}

		$subNum 	= $this->info_db->where($where)->count();
		
		if(empty($subNum)){
			$subNum = 0;
		}

		return $subNum;
	}


	    /*获取指定月份起始结束时间戳*/
    public function _mFristAndLast($m = "" ,$y = "") {
		if ($y == "")
			$y = date ( "Y" );
		if ($m == "")
			$m = date ( "m" );
		$m = sprintf ( "%02d", intval ( $m ) );
		$y = str_pad ( intval ( $y ), 4, "0", STR_PAD_RIGHT );
		$m > 12 || $m < 1 ? $m = 1 : $m = $m;
		$firstday = strtotime ( $y . $m . "01000000" );
		$firstdaystr = date ( "Y-m-01", $firstday );
		$lastday = strtotime ( date ( 'Y-m-d 23:59:59', strtotime ( "$firstdaystr +1 month -1 day" ) ) );
		return array ("firstday" => $firstday, "lastday" => $lastday );
	}

}


?>