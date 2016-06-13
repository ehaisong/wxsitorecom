<?php
class ShakeAction extends WapAction{
	
	public $shake_model;
	public $act_type;
	public function __construct()
	{
		parent::__construct();
		//普通活动或者现场活动
		$this->shake_model 	= M('Shake');
	}

	public function index()
	{
		$act_id=$this->_get('id','intval');
		$act_type=$this->_get('act_type','intval');
		if(!in_array($act_type,array('2','3')) || !$this->wecha_id)
		{
			echo '参数错误';
			exit();
		}
		if($act_type == 2)
		{
			$shake_id = $act_id;
		}
		else if($act_type == 3)
		{
			$shake_id = M('Wechat_scene')->where(array('token'=>$this->token,'id'=>$act_id))->getField('shake_id');
		}
		$shake_act_name=sha1('shake_act_'.$shake_id.'_'.$this->token);
		$shake_user_name=sha1('shake_user_'.$shake_id.'_'.$this->wecha_id);
		$user=S($shake_user_name);
		if(empty($user))
		{
			$where 		= array('wecha_id'=>$this->wecha_id,'token'=>$this->token,'act_id'=>$act_id,'act_type'=>$act_type);
			$member 	= M('wall_member')->where($where)->count();
			if (!$member)
			{
				header('location:'.U('Scene_member/index',array('token'=>$this->token,'wecha_id'=>$this->wecha_id,'id'=>$act_id,'act_type'=>$act_type,'name'=>'shake')));
				exit();
			}
		}
		$thisShake=S($shake_act_name);
		if(empty($thisShake))
		{
			$thisShake 		= $this->shake_model->where(array('token'=>$this->token,'id'=>$shake_id,'isopen'=>1))->find();
			S($shake_act_name,$thisShake);
		}
		$countdownNow=0;$shockTotal=0;$actStatus='0';$recNum=0;
		$shakeRt=M('ShakeRt');
		$rtWhere=array('wecha_id'=>$this->wecha_id,'shakeid'=>$shake_id,'round'=>(int)$thisShake['round']);
		if(!empty($act_id))
			$rtWhere['scene_id']=$act_id;
		$rtNum=$shakeRt->where($rtWhere)->count();
		if($rtNum<=0)
		{
			$data['wecha_id']=$this->wecha_id;
			$data['token']=$this->token;
			$data['count']=0;
			$data['shakeid']=$shake_id;
			$data['round']=(int)$thisShake['round'];
			$data['phone']='';
			$data['is_scene']=empty($act_id)?0:1;
			$data['scene_id']=$act_id;
			$shakeRt ->add($data);
		}
		if((int)$thisShake['round']>0)
		{
			if(empty($user)||$user['round']!=$thisShake['round'])
			{
				$user=$this->getUser($shake_id,$thisShake['round'],$act_id,$act_type);
				S($shake_user_name,$user);
			}
			$shockTotal=$user['count'];
		}
		if($thisShake['isact']==1)
		{
			$start_time=(int)$thisShake['endtime']+(int)$thisShake['starttime'];//开始时间
			$end_time=$start_time+(int)$thisShake['usetime'];//结束时间
			$recNum=$start_time-time();
			$max=S(sha1('shake_max_'.$shake_id));
			if(empty($max))
			{
				$max=$shakeRt->where(array('round'=>$thisShake['round'],'shakeid'=>$thisShake['id']))->max('count');
				S(sha1('shake_max_'.$shake_id),$max);
			}
			if($end_time>time()&&$max<$thisShake['endshake']&&time()>=$start_time)
			{
				$countdownNow=time()-$start_time;
				$actStatus='1';
			}
		}
		$this->assign('shockTotal',$shockTotal);
		$this->assign('countdownTotal',$thisShake['usetime']);
		$this->assign('countdownNow',$countdownNow);
		$this->assign('actStatus',$actStatus);
		$this->assign('info',$thisShake);
		$this->assign('act_id',$act_id);
		$this->assign('act_type',$act_type);
		$this->assign('recNum',$recNum);
		$this->display();
	}

	//检查活动状态
	public function shakeActivityStatus()
	{
		$act_id=I('post.act_id');
		$act_type=I('post.act_type');
		$shake_id=I('post.shake_id');
		$round=I('post.round');
		$shake_act_name=sha1('shake_act_'.$shake_id.'_'.$this->token);
		$shake_user_name=sha1('shake_user_'.$shake_id.'_'.$this->wecha_id);
		$act=S($shake_act_name);
		if(empty($act))
		{
			$act 		= $this->shake_model->where(array('token'=>$this->token,'id'=>$shake_id,'isopen'=>1))->find();
			S($shake_act_name,$act);
		}
		$countdownNow=0;$shockTotal=0;$actStatus='0';$recNum=0;
		$shakeRt=M('ShakeRt');
		if((int)$act['round']>0)
		{
			$user=S($shake_user_name);
			if(empty($user)||$user['round']!=$act['round'])
			{
				$user=$this->getUser($shake_id,$act['round'],$act_id,$act_type);
				S($shake_user_name,$user);
			}
			$shockTotal=$user['count'];
		}
		if($act['isact']==1)
		{
			$max=S(sha1('shake_max_'.$shake_id));
			if(empty($max))
			{
				$max=$shakeRt->where(array('round'=>$act['round'],'shakeid'=>$act['id']))->max('count');
				S(sha1('shake_max_'.$shake_id),$max);
			}
			$start_time=(int)$act['endtime']+(int)$act['starttime'];//开始时间
			$end_time=$start_time+(int)$act['usetime'];
			$recNum=$start_time-time();
			if($end_time>time()&&$max<$act['endshake']&&time()>=$start_time)
			{
				$countdownNow=time()-$start_time;
				$actStatus='1';
			}
		}
		$result=array('status'=>'1','round'=>$act['round'],'countdownTotal'=>$act['usetime']);
		$result['actStatus']=$actStatus;
		$result['round']=$act['round'];
		$result['countdownNow']=$countdownNow;
		$result['shockTotal']=$shockTotal;
		$result['recNum']=$recNum;
		$this->ajaxReturn($result);
	}

    public function refreshScreen()
	{
		$act_id=I('post.act_id');
		$act_type=I('post.act_type');
		$shake_id=I('post.shake_id');
		$num=I('post.num');
		$shake_act_name=sha1('shake_act_'.$shake_id.'_'.$this->token);
		$shake_user_name=sha1('shake_user_'.$shake_id.'_'.$this->wecha_id);
		$shake_ranks_name=sha1('shake_ranks_'.$shake_id);
		$shake_max_name=sha1('shake_max_'.$shake_id);
		$act=S($shake_act_name);
		if(empty($act))
		{
			$act 		= $this->shake_model->where(array('token'=>$this->token,'id'=>$shake_id,'isopen'=>1))->find();
			S($shake_act_name,$act);
		}
		$countdownNow=0;$actStatus='0';$writeUser=false;$recNum=0;
		$shakeRt=M('ShakeRt');
		$user=S($shake_user_name);
		if(empty($user)||$user['round']!=$act['round'])
		{
			$user=$this->getUser($shake_id,$act['round'],$act_id,$act_type);
			$writeUser=true;
		}
		$shockTotal=$user['count'];
		if($act['isact']==1)
		{
			$max=S($shake_max_name);
			if(empty($max))
			{
				$max=$shakeRt->where(array('round'=>$act['round'],'shakeid'=>$act['id']))->max('count');
			}
			$start_time=(int)$act['endtime']+(int)$act['starttime'];//开始时间
			$end_time=$start_time+(int)$act['usetime'];
			$recNum=$start_time-time();
			if($end_time>time()&&$max<$act['endshake']&&time()>=$start_time)
			{
				$user['count']=(int)$user['count']+(int)$num;
				if($user['count']>$act['endshake'])
				{
					$user['count']=$act['endshake'];
				}
				$max=max((int)$user['count'],(int)$max);
				$ranks=S($shake_ranks_name);
				if(empty($ranks))
				{
					$ranks=array();
				}
				$ranks[$this->wecha_id]=$user['count'];
				S($shake_max_name,$max);
				S($shake_ranks_name,$ranks);
				$writeUser=true;
				$actStatus=$max>=$act['endshake']?'0':'1';
				$shockTotal=$user['count'];
				$countdownNow=time()-$start_time;
			}
		}
		if($writeUser)
		{
			S($shake_user_name,$user);
		}
		$result=array('status'=>'1','round'=>$act['round'],'countdownTotal'=>$act['usetime']);
		$result['actStatus']=$actStatus;
		$result['round']=$act['round'];
		$result['countdownNow']=$countdownNow;
		$result['shockTotal']=$shockTotal;
		$result['recNum']=$recNum;
		$this->ajaxReturn($result);
	}

	private function getUser($shake_id,$round,$act_id,$act_type)
	{
		$shakeRt=M('ShakeRt');
		$rt=$shakeRt->where(array('round'=>$round,'shakeid'=>$shake_id,'wecha_id'=>$this->wecha_id))->field('count')->find();
		$memberModel=M('wall_member');
		$memberWhere=array('wecha_id'=>$this->wecha_id,'act_id'=>$act_id,'act_type'=>$act_type);
		$userInfo=$memberModel->where($memberWhere)->field('nickname,portrait,truename')->find();
		$user=array('count'=>(int)$rt['count'],'wecha_id'=>$this->wecha_id);
		$user['name']=$userInfo['nickname'];
		$user['portrait']=$userInfo['portrait'];
		$user['round']=$round;
		return $user;
	}
}
?>