<?php 
/**
* 抽奖项目中奖概率计算基类
**/
class LotteryPrizeBaseAction extends WapAction{
	public function __construct(){
		parent::_initialize();
		
		$this->action_id = $this->_get('id','intval');
		//$this->wecha_id = 'ov0kxt9jbuhuq_cbcnqMItcn3r6c';
	}
	public function shakePrize($shakePrize = ''){
		$prizetype = '';
		if(empty($shakePrize) || empty($shakePrize['prizelist'])){
			$prizeid = 0;
		}
		$actual_join_number = (int)$shakePrize['actual_join_number'];
		$join_number = (int)$shakePrize['join_number'];
		$totaltimes = (int)$shakePrize['totaltimes'];
		$prize_arr = array();
		$startnum = 0;
		$endnum=0;
		foreach ($shakePrize['prizelist'] as $key => $value) {
			$leftnum = ($value['prizenum'] > $value['expendnum']) ? intval($value['prizenum'] - $value['expendnum']) : 0;
			$endnum += $leftnum;
			$prize_arr[$value['id']] = array('id'=>$value['id'],'prize'=>$value['prizename'],'v'=>$leftnum,'start'=>$startnum,'end'=>$endnum);
			$startnum += $leftnum;
		}
		$prize_arr[0] = array('id'=>0,'prize'=>'谢谢参与','v'=>$join_number*$totaltimes-$endnum,'start'=>$endnum,'end'=>$join_number*$totaltimes);
		if ($join_number == 1) {
			foreach ($shakePrize['prizelist'] as $key => $value) {
				if($value['prizenum'] - $value['expendnum'] > 0){
					$prizeid = $value['id'];//如果预计参与人数设为1,则在还有剩余的奖品等级里取等级高的
					break;
				}else{
					$prizeid = 0;
				}
			}		 
		}else{
			//预计参与人数*每人参与次数-实际参与人数
			$prizeid = $this->get_rand($prize_arr,($join_number*$totaltimes)-$actual_join_number); 
		}
		return $prizeid;
	}
	//抽奖
	private function get_rand($proArr,$total) {
		$result = 0; 
		$randNum = mt_rand(1, $total); 
		foreach ($proArr as $k => $v) {
			if ($v['v']>0){
				if ($randNum>$v['start']&&$randNum<=$v['end']){
					$result=$k;
					break;
				}
			}
		}
		return $result; 
	}
	//获取奖品名称
	protected function getPrizeName($shakePrize = '',$prizetype = ''){
		$prizeinfo = array(); 
		switch($prizetype){
			case 1:
				$prizeinfo['prizename'] =  $shakePrize['first_prize'];
				$prizeinfo['prizepic'] =  $shakePrize['first_img'];
				break;
			case 2:
				$prizeinfo['prizename'] =  $shakePrize['second_prize'];
				$prizeinfo['prizepic'] =  $shakePrize['second_img'];
				break;
			case 3:
				$prizeinfo['prizename'] =  $shakePrize['third_prize'];
				$prizeinfo['prizepic'] =  $shakePrize['third_img'];
				break;
			case 4:
				$prizeinfo['prizename'] =  $shakePrize['fourth_prize'];
				$prizeinfo['prizepic'] =  $shakePrize['fourth_img'];
				break;
			case 5:
				$prizeinfo['prizename'] =  $shakePrize['fifth_prize'];
				$prizeinfo['prizepic'] =  $shakePrize['fifth_img'];
				break;
			case 6:
				$prizeinfo['prizename'] =  $shakePrize['sixth_prize'];
				$prizeinfo['prizepic'] =  $shakePrize['sixth_img'];
				break;
			default:
				$prizeinfo['prizename'] = '谢谢参与';
				break;
		}
		return $prizeinfo;
	}
}
?>