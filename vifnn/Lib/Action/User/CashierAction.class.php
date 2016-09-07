<?php

class CashierAction extends UserAction {
    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction("Cashier");
    }

    public function index(){
		$siteurl=isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$siteurl=strtolower($siteurl);
		if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
		$siteurl=rtrim($siteurl,'/');
		$postdata=array('account'=>$this->token,'userid'=>$this->token,'username'=>$this->wxuser['wxname'],'appid'=>$this->wxuser['appid'],'appsecret'=>$this->wxuser['appsecret'],'wxid'=>$this->wxuser['wxid'],'weixin'=>$this->wxuser['weixin'],'logo'=>$this->wxuser['headerpic'],'domain'=>$_SERVER['HTTP_HOST'],'source'=>1,'email'=>$this->user['email']);
		$postdata['sign'] = $this->getSign($postdata);
		$postdataStr=json_encode($postdata);
		$postdataStr=$this->Encryptioncode($postdataStr,'ENCODE');
		$postdataStr=urlencode($postdataStr);
		$request_url=$siteurl.'/merchants.php?m=Index&c=auth&a=getIdentifier';
		$responsearr = $this->httpRequest($request_url, 'POST', $postdataStr);
		$tmpdata = trim($responsearr['1']);
        if (empty($tmpdata)) {
            $responsearr = $this->httpRequest($request_url, 'POST', $postdataStr);
            $tmpdata = trim($responsearr['1']);
        }
		$tmpdata=json_decode($tmpdata,true);
		if(isset($tmpdata['code'])){
		  header('Location: '.$siteurl.'/merchants.php?m=Index&c=auth&a=login&code='.$tmpdata['code']);
		}else{
			//print_r($responsearr);
		    $this->error($tmpdata['message']);
		}
    }

   private function getSign($data) {
	foreach ($data as $key => $value) {
		if (is_array($value)) {
			$validate[$key] = $this->getSign($value);
		} else {
			$validate[$key] = $value;
		}			
	}
	$validate['salt'] = 'pigcmso2oCashier';	//salt
	sort($validate, SORT_STRING);
	return sha1(implode($validate));
  }

	/**
	 * ���ܺͽ��ܺ���
	 *
	 * <code>
	 * // �����û�ID���û���
	 * $auth = authcode("{$uid}\t{$username}", 'ENCODE');
	 * // �����û�ID���û���
	 * list($uid, $username) = explode("\t", authcode($auth, 'DECODE'));
	 * </code>
	 *
	 * @access public
	 * @param  string  $string    ��Ҫ���ܻ���ܵ��ַ���
	 * @param  string  $operation Ĭ����DECODE������ ENCODE�Ǽ���
	 * @param  string  $key       ���ܻ���ܵ���Կ ����Ϊ�յ������ȡȫ������encryption_key
	 * @param  integer $expiry    ���ܵ���Ч��(��)0��������Ч ע�������������Ҫ��ʱ���
	 * @return string
	 */
	public function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4;
		$key = md5($key != '' ? $key : 'lhs_simple_encryption_code_87063');
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya . md5($keya . $keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if ($operation == 'DECODE') {
			if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc . str_replace('=', '', base64_encode($result));
		}
	}
	
	
	
	//����CMS��ѯ����
	public function orderlist(){
		$whowhere      = array('thirduserid'=>$this->token,'salt'=>'pigcmso2oCashier');
		$minfo 	= M('Cashier_merchants')->where($whowhere)->order('mid desc')->find();
		if (IS_POST) {
			
			$key = $this->_post('search','trim');

			$map['token'] = $this->token;
			$map['mid'] = $minfo['mid'];

			if($_POST['ispay']){
				$map['ispay'] = $_POST['ispay'];
			}
			
			if($key){
				$map2['username'] = array('like','%'.$key.'%');
				$dianyuan = M('Cashier_employee')->where(array('username'=>$map2['username'],'mid'=>$map['mid']))->find();
				if($dianyuan){
					$map['eid'] = $dianyuan['eid'];
				}
			}
			
			if(empty($dianyuan)){
				$map1['branch_name'] = array('like','%'.$key.'%');
				$mendian = M('Cashier_mendian')->where(array('branch_name'=>$map1['branch_name'],'mid'=>$map['mid']))->find();
				if($mendian){
					$map['menid'] = $mendian['id'];
				}
			}
			
			if(empty($map['menid']) && empty($map['eid'])){
				$map['truename|openid|goods_name|transaction_id|order_id|username']  = array('like','%'.$key.'%');
			}
			
			if($_POST['starttime']){
				$starttime = strtotime($_POST['starttime']);
			}
			
			if($_POST['endtime']){
				$endtime = strtotime($_POST['endtime']);
			}
			
			if (empty($endtime)) {
				$endtime = $starttime+7200;
			}
			
			if($starttime){
				$map['add_time'] = array('between', "$starttime,$endtime");
			}
			

			$count      = M('Cashier_order')->where($map)->count();
			$Page       = new Page($count,15);
			$list 	= M('Cashier_order')->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach ($list as $key => $val) {
				$user_info = M('Cashier_fans')->where(array('openid'=>$val['openid']))->find();
				$list[$key]['truename'] 	= $user_info['nickname']?$user_info['nickname']:'��';
				$list[$key]['is_subscribe'] = $user_info['is_subscribe']?$user_info['is_subscribe']:'��';
				$list[$key]['headurl'] 		= $user_info['headimgurl']?$user_info['headimgurl']:'��';
			}
			
			$this->assign('key',$_POST['search']);
			$this->assign('starttime',$starttime);
			$this->assign('endtime',$endtime);

		} else {
			$where      = array();
			$where['mid'] = $minfo['mid'];
			$where['thirduserid'] = array('neq','');
			$where['ispay'] = array('eq','1');
			$where['goods_price'] = array('gt','0');
			$count      = M('Cashier_order')->where($where)->count();
			$Page       = new Page($count,15);
			$list 	= M('Cashier_order')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach ($list as $key => $val) {
				$user_info = M('Cashier_fans')->where(array('openid'=>$val['openid']))->find();

				$list[$key]['truename'] 	= $user_info['nickname']?$user_info['nickname']:'��';
				$list[$key]['is_subscribe'] = $user_info['is_subscribe']?$user_info['is_subscribe']:'��';
				$list[$key]['headurl'] 		= $user_info['headimgurl']?$user_info['headimgurl']:'��';
			}
		}
		
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function kaquanlist(){
		$whowhere      = array('thirduserid'=>$this->token,'salt'=>'pigcmso2oCashier');
		$minfo 	= M('Cashier_merchants')->where($whowhere)->order('mid desc')->find();
		if (IS_POST) {
			$key = $this->_post('search','trim');

			if (empty($key)) {
				$this->error('�ؼ��ʲ���Ϊ��');
			}
			$map['Outerid'] = $minfo['mid'];
			$map['cardtitle|openid|cardid|cardcode|cardtype']  = array('like',"%$key%");
			$count      = M('Cashier_wxcoupon_receive')->where($map)->count();
			$Page       = new Page($count,15);
			$list 	= M('Cashier_wxcoupon_receive')->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach ($list as $key => $val) {
				$user_info = M('Cashier_fans')->where(array('openid'=>$val['openid']))->find();
				$coupon_info = M('Cashier_wxcoupon')->where(array('card_id'=>$val['cardid']))->find();
				$list[$key]['truename'] 	= $user_info['nickname']?$user_info['nickname']:'��';
				$list[$key]['kaname'] 	= $coupon_info['card_title']?$coupon_info['card_title']:'��';
				$list[$key]['is_subscribe'] = $user_info['is_subscribe']?$user_info['is_subscribe']:'��';
				$list[$key]['headurl'] 		= $user_info['headimgurl']?$user_info['headimgurl']:'��';
			}

			$this->assign('page',$Page->show());
			$this->assign('key',$key);
		} else {
			$where      = array();
			$where['outerid'] = $minfo['mid'];
			$count      = M('Cashier_wxcoupon_receive')->where($where)->count();
			$Page       = new Page($count,15);
			$list 	= M('Cashier_wxcoupon_receive')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach ($list as $key => $val) {
				$user_info = M('Cashier_fans')->where(array('openid'=>$val['openid']))->find();
				$coupon_info = M('Cashier_wxcoupon')->where(array('card_id'=>$val['cardid']))->find();
				$list[$key]['truename'] 	= $user_info['nickname']?$user_info['nickname']:'��';
				$list[$key]['kaname'] 	= $coupon_info['card_title']?$coupon_info['card_title']:'��';
				$list[$key]['is_subscribe'] = $user_info['is_subscribe'];
				$list[$key]['headurl'] 		= $user_info['headimgurl'] ? $user_info['headimgurl'] : '��';
			}
		}
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function fans(){
		$search     = $this->_post('search','trim');
		$whowhere      = array('thirduserid'=>$this->token,'salt'=>'pigcmso2oCashier');
		$minfo 	= M('Cashier_merchants')->where($whowhere)->order('mid desc')->find();
		$where      = array('mid'=>$minfo['mid']);
		if($search){
			$where['truename|openid']  = array('like','%'.$search.'%');
		}
		$count      = M('Cashier_fans')->where($where)->count();
		$Page       = new Page($count,15);
		$list 	= M('Cashier_fans')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}

	public function rank_del(){
		$id		= $this->_get('id','intval');
		if(M('Cashier_fans')->where(array('mid'=>$minfo['mid'],'id'=>$id))->delete()){
			$this->success('ɾ���ɹ�');
		}
	}
	
	/*
	������Ʒ�б���ϸ����
	*/
	public function kaquanxls(){
		set_time_limit(1000); 
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=kaquanxls.xls");
		
		$arr = array(
			array('en'=>'id','cn'=>'���'),
			array('en'=>'cardtitle','cn'=>'��ȯ����'),
			array('en'=>'cardtype','cn'=>'����'),
			array('en'=>'cardid','cn'=>'��ȯID'),
			array('en'=>'openid','cn'=>'openid'),
			array('en'=>'truename','cn'=>'��ȡ��'),
			array('en'=>'cardcode','cn'=>'������'),
			array('en'=>'addtime','cn'=>'��ȡʱ��'),
			array('en'=>'consumetime','cn'=>'����ʱ��'),
			array('en'=>'status','cn'=>'����״̬'),
		);
		
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}
		//data
		$db = M('Cashier_wxcoupon_receive');
		$whowhere      = array('thirduserid'=>$this->token,'salt'=>'pigcmso2oCashier');
		$minfo 	= M('Cashier_merchants')->where($whowhere)->order('mid desc')->find();
		
		$where = array('Outerid'=>$minfo['mid']);

		$count		= $db->where($where)->count();
		$page_size 	= 1000;
		$page_num 	= ceil($count / $page_size);
		
		for($i=0; $i<$page_num; $i++){
		    $start 		= $i*$page_size;
		    $limit 		= $start.','.$page_size;
		    $list 		= $db->where($where)->order('id DESC')->limit($limit)->select();
			foreach ($list as $key=>$val) {
				if($list[$key]['openid'] != ''){
					$set = M("Cashier_fans")->where(array('openid' => $val['openid']))->find();
					$list[$key]['truename'] = $set['nickname'];
				}
			}
			if($list){
				foreach ($list as $product){
					$pro 	= M('Cashier_wxcoupon')->where(array('mid'=>$minfo['Outerid']))->getField('cardtitle');
					$list['cardtitle']	= $pro;
					$j = 0;
					foreach ($arr as $field){
						$fieldValue = $product[$field['en']];
						switch($field['en']){
							case 'id':
								$fieldValue = iconv('utf-8','gbk',"���".$fieldValue);
								break;
								
							case 'cardtitle':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',$fieldValue);
								}
								break;
							
							case 'cardtype':
								if($fieldValue == 1){
									$fieldValue = iconv('utf-8','gbk','�Ź�ȯ');
								}else if($fieldValue == 2){
									$fieldValue = iconv('utf-8','gbk','�ۿ�ȯ');
								}else if($fieldValue == 3){
									$fieldValue = iconv('utf-8','gbk','��Ʒȯ');
								}else if($fieldValue == 4){
									$fieldValue = iconv('utf-8','gbk','����ȯ');
								}else if($fieldValue == 5){
									$fieldValue = iconv('utf-8','gbk','��Ա��');
								}else if($fieldValue == 0){
									$fieldValue = iconv('utf-8','gbk','�Ż�ȯ');
								}
								break;
								
							case 'openid':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',$fieldValue);
								}
								break;
								
							case 'truename':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',$fieldValue);
								}
								break;
								
							case 'cardcode':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',"�����룺".$fieldValue);
								}
								break;
								
							case 'addtime':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',date('Y��m��d�� Hʱi��s��',$fieldValue));
								}
								break;
							
							case 'consumetime':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',date('Y��m��d�� Hʱi��s��',$fieldValue));
								}
								break;
							
							case 'status':
								if($fieldValue == '0'){
									$fieldValue = iconv('utf-8','gbk','δ����');
								}else if($fieldValue == '1'){
									$fieldValue = iconv('utf-8','gbk','�Ѻ���');
								}
								break;

							default:
								break;
						}
						
						if ($j < ($fieldCount - 1)) {
							echo $fieldValue . '	';
						}
						else {
							echo $fieldValue . "\n";
						}
						$j++;
					}
					$i++;
				}	
			}
			
			usleep(1000); //����ͣ��
		}
		exit();
	}
	
}

?>