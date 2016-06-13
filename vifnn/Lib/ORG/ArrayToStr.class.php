<?php
class ArrayToStr 
{

	public static function array_to_str($array, $paid = 0)
	{
		if (is_array($array)) {
			
			$msg = '';
			
			if( strlen($array['companyname']) > 0 && strlen($array['companyname'])<=24 ){
							
							$msg.="^B2";
							$name_length=strlen($array['companyname']);
							for( $i = 0 ; $i < ((8-$name_length/3)) ; $i++ )
								$msg.=" ";
							$msg.=$array['companyname'];
						}
					else if( strlen($array['companyname']) > 8 ){
							$msg ="\n";
							$msg.=$array['companyname'];
					}
			
			if ($array['truename']) $msg .= chr(10).'姓名：'. $array['truename'];
			if ($array['tel']) $msg .= chr(10).'电话：'.$array['tel'];
			if ($array['address']) $msg .= chr(10).'地址：'. $array['address'];
			$msg .= chr(10).'下单时间：' . date('Y-m-d H:i:s', $array['buytime']);
			if (isset($array['sendtime']) && $array['sendtime']) {
				if($array['sendtime']>0){
				  $msg .= chr(10).'配送时间:' . date('Y-m-d H:i:s', $array['sendtime']);
				}else{
				  $msg .= chr(10).'配送时间:' .$array['sendtime'];
				}
			}
			
			 if (isset($array['advancepay']) && $array['advancepay']) {
				$msg .= chr(10).'预付就餐订金：'. $array['advancepay'].'元';;
			}
			if (isset($array['realpay']) && $array['realpay']) {
				$msg .= chr(10).'此次实际支付：'. $array['realpay'].'元';;
			}
			$msg .= chr(10).'订单编号：'.$array['orderid'];
			if ($paid) {
				$msg .= chr(10).'订单状态：已付款';
			} else {
				$msg .= chr(10).'订单状态：未付款';
			}
		    isset($array['ptype'])&& array_key_exists($array['ptype'],$payarr)&& $msg.=chr(10)."支付方式：".$payarr[$array['ptype']];
			
			
			if (isset($array['typename']) && $array['typename']) {
				$msg .= chr(10).'就餐形式：'. $array['typename'];
			}
			if (isset($array['reservestr']) && $array['reservestr']) {
				$msg .= chr(10).'预约时间：'. $array['reservestr'];
			}		
			if (isset($array['takeAwayPrice']) && $array['takeAwayPrice']) {
				$msg .= chr(10).'送餐费：'. $array['takeAwayPrice'];
			}
			if (isset($array['bookTable']) && $array['bookTable']) {
				$msg .= chr(10).'餐桌预订金：'. $array['bookTable'];
			}

			if (isset($array['tablename']) && $array['tablename']) {
				$msg .= chr(10).'餐桌号：'. $array['tablename'];
			}
			if (isset($array['des']) && $array['des']) {
				$msg .= chr(10).'备注信息：'. $array['des'];
			}
			$num = 0;
			if (isset($array['list']) && is_array($array['list'])) {
				$msg .= chr(10).'...............................';
				foreach ($array['list'] as $row) {
					if (isset($row['day'])) {
						$msg .= chr(10). $row['name'] . ": ￥" . $row['price'] . " * " . $row['num'] . "间  * " . $row['day'] . "天";
					} else {
						$num++;
						$msg .= chr(10). $num.").".$row['name'] ;
						$msg .= chr(10).  "单价：" . $row['price'] . "     数量：" . $row['num'];
						if (isset($row['omark']) && !empty($row['omark'])) $msg .=  chr(10)."备注：" . htmlspecialchars_decode($row['omark'],ENT_QUOTES);
						if (isset($row['colorName'])) $msg .=  "," . $row['colorName'];
						if (isset($row['formatName'])) $msg .= "," . $row['formatName'];
					}
					if (isset($row['startdate']) && isset($row['enddate'])) {
						$msg .= chr(10). '预订时间从' . date('Y年m月d日', strtotime($row['startdate'])) . '到' . date('Y年m月d日', strtotime($row['enddate']));
					}
				}
				$msg .= chr(10).'...............................';
			}
			
			
			$msg .= chr(10).'^B2数量：'.$array['total'];
			$msg .= chr(10).'^B2合计：'. $array['price'].'元';
		   
			
			return $msg;
		}
	}
}
