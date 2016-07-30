<?php

class DishOutAction extends WapAction
{
	public $session_dish_info;
	public $_cid = 0;
	public $mycompany;
	public $mshop;
	public $isMember = 0;

	public function _initialize()
	{
		parent::_initialize();
		$agent = $_SERVER['HTTP_USER_AGENT'];

		if (stripos($agent, 'MicroMessenger')) {
			$this->assign('iswxbrowse', true);
		}
		else {
			$this->assign('iswxbrowse', false);
		}

		$this->_cid = $_SESSION['session_shop_' . $this->token];
		$this->_cid = 0 < $this->_cid ? $this->_cid : 0;
		$this->assign('cid', $this->_cid);
		$this->shopmanage = $_SESSION['manage_shop' . $this->_cid . '_' . $this->token];
		$this->shopmanage = !empty($this->shopmanage) ? unserialize($this->shopmanage) : false;
		$this->session_dish_info = 'session_dish_' . $this->_cid . '_info_' . $this->token;
		$this->isMember = $this->getCardInfo();
		$this->isMember = !empty($this->isMember) ? 1 : 0;
	}

	public function index()
	{
		$company = M('Company')->where('token=\'' . $this->token . '\' AND display=1 AND (`business_type` LIKE \'%DishOut%\' OR `business_type`=\'\')  AND shortname!=\'Medical\'')->select();
		$nowlat = ($this->_get('nowlat') ? floatval($this->_get('nowlat', 'trim')) : 0);
		$nowlng = ($this->_get('nowlng') ? floatval($this->_get('nowlng', 'trim')) : 0);
		if ((0 < $nowlat) && (0 < $nowlng)) {
			$tmpe = array();

			foreach ($company as $kk => $vv) {
				$tmpd = $this->getDistance_map($nowlat, $nowlng, $vv['latitude'], $vv['longitude']);
				$tmpdstr = (1000 < $tmpd ? round(floatval($tmpd / 1000), 2) . ' km' : intval($tmpd) . ' m');
				$vv['distance'] = $tmpd;
				session($this->wecha_id . $this->token . $vv['id'] . 'distance', $tmpd);
				$vv['distancestr'] = $tmpdstr;
				$company[$kk] = $vv;
				$tmpe[$kk] = $tmpd;
				$company[$kk]['dishout'] = M('dishout_manage')->where(array('token' => $this->token, 'cid' => $vv['id']))->find();
			}

			asort($tmpe);
			$newCy = array();

			foreach ($tmpe as $tk => $tv) {
				$newCy[] = $company[$tk];
			}

			$company = (!empty($newCy) ? $newCy : $company);
			$this->assign('is_dwei', true);

			if (count($company) == 1) {
				$this->redirect(U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $company[0]['id'], 'nowlat' => $nowlat, 'nowlng' => $nowlng)));
				exit();
			}
		}

		$this->assign('company', $company);
		$this->assign('companytt', count($company));
		$this->assign('metaTitle', '餐厅分布');
		$this->display();
	}

	private function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b)
	{
		$R = 6377830;
		$pk = doubleval(180 / 3.1415926000000001);
		$a1 = doubleval($lat_a / $pk);
		$a2 = doubleval($lng_a / $pk);
		$b1 = doubleval($lat_b / $pk);
		$b2 = doubleval($lng_b / $pk);
		$t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
		$t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
		$t3 = doubleval(sin($a1) * sin($b1));
		$tt = doubleval(acos($t1 + $t2 + $t3));
		return round($R * $tt);
	}

	public function MyShop()
	{
		$cid = ($this->_get('cid') ? intval($this->_get('cid', 'trim')) : 0);
		if ((0 < $cid) && ($cid == $this->_cid)) {
			$outset = $this->outManage($cid);
			$company = $this->getCompany($cid);
			$imgarr = (!empty($outset['shopimg']) ? unserialize($outset['shopimg']) : array());
			if (!empty($imgarr) && !empty($imgarr['tourl'])) {
				foreach ($imgarr['tourl'] as $ukk => $uvv) {
					$imgarr['tourl'][$ukk] = $this->getLink(htmlspecialchars_decode($uvv, ENT_QUOTES));
				}
			}

			$outinfo = array();
			$outinfo['id'] = $cid;
			$timestr = '';
			$timestr = $outset['stimestr'] . ' ~ ' . $outset['etimestr'];
			$timestr = (!empty($outset['stime2str']) ? $timestr . '&nbsp;&nbsp;' . $outset['stime2str'] . ' ~ ' . $outset['etime2str'] : $timestr);
			$outinfo['timestr'] = $timestr;
			$outinfo['sendtime'] = $outset['sendtime'];
			$outinfo['removing'] = $outset['removing'];
			$outinfo['stype'] = $outset['stype'];
			$my_distance = session($this->wecha_id . $this->token . $cid . 'distance');
			if (($outset['overflow_radius'] == 2) && ($outset['removing'] < $my_distance)) {
				$pricing_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
			}
			else {
				$pricing_jia = 0;
			}

			$pricing_jia = ($pricing_jia < 0 ? 0 : $pricing_jia);
			$outinfo['pricing'] = $outset['pricing'] + $pricing_jia;
			$outinfo['tel'] = $company['tel'];
			$outinfo['address'] = $company['address'];
			$outinfo['latitude'] = $company['latitude'];
			$outinfo['longitude'] = $company['longitude'];
			$outinfo['intro'] = $company['intro'];
			$outinfo['logourl'] = $company['logourl'];
			$outinfo['name'] = $company['name'];
			$outinfo['mp'] = $company['mp'];
			$outinfo['area'] = $outset['area'] ? htmlspecialchars_decode($outset['area'], ENT_QUOTES) : '';
			unset($outset);
			unset($company);
			$this->assign('shopinfo', $outinfo);
			$this->assign('shopimg', $imgarr);
			$this->assign('metaTitle', '店面详情');
			$this->display();
		}
		else {
			$this->exitdisplay('没有获取到相关门店信息');
		}
	}

	private function getDishMainCompany($cache = true)
	{
		$mDishC = $_SESSION['session_Maindish_' . $this->token];
		$mDishC = (!empty($mDishC) ? unserialize($mDishC) : false);
		if ($cache && !empty($mDishC)) {
			return $mDishC;
		}
		else {
			$MainC = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			$m_cid = $MainC['id'];
			unset($MainC);
			$mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
			unset($m_cid);

			if ($cache) {
				$_SESSION['session_Maindish_' . $this->token] = !empty($mDishC) ? serialize($mDishC) : '';
			}
			else {
				$_SESSION['session_Maindish_' . $this->token] = '';
			}

			return $mDishC;
		}
	}

	private function getPrinter_set($cid, $cache = true)
	{
		$PsetC = $_SESSION['PrinterSet_' . $this->token . '_' . $cid];
		$PsetC = (!empty($PsetC) ? unserialize($PsetC) : false);
		if ($cache && !empty($PsetC)) {
			return $PsetC;
		}
		else {
			$PsetC = M('Orderprinter')->where(array('token' => $this->token, 'companyid' => $cid))->find();

			if ($cache) {
				$_SESSION['PrinterSet_' . $this->token . '_' . $cid] = !empty($PsetC) ? serialize($PsetC) : '';
			}
			else {
				$_SESSION['PrinterSet_' . $this->token . '_' . $cid] = '';
			}

			return $PsetC;
		}
	}

	public function dishMenu()
	{
		$cid = ($this->_get('cid') ? intval($this->_get('cid', 'trim')) : 0);
		$outset = $this->outManage($cid);
		$nows = strtotime(date('Y-m-d H:i'));
		$timeerrorstr = '';

		if (0 < $outset['zc_sdate']) {
			$sf = date('H:i', $outset['zc_sdate']);
			$setime = strtotime(date('Y-m-d ') . $sf);

			if ($nows < $setime) {
				$this->exitdisplay('抱歉！尚未到营业时间！');
			}
		}

		$fetime = 0;

		if (0 < $outset['zc_edate']) {
			$ef = date('H:i', $outset['zc_edate']);
			$fetime = $setime = strtotime(date('Y-m-d ') . $ef);

			if ($setime < $nows) {
				$timeerrorstr = '抱歉！已经过了营业时间了！';
			}
			else {
				if (isset($outset['wc_sdate']) && (0 < $outset['wc_sdate'])) {
					$timeerrorstr = '';
				}
			}
		}

		if (!empty($timeerrorstr) && (!isset($outset['wc_sdate']) || !(0 < $outset['wc_sdate']))) {
			$this->exitdisplay($timeerrorstr);
		}

		if (isset($outset['wc_sdate']) && (0 < $outset['wc_sdate'])) {
			$sf = date('H:i', $outset['wc_sdate']);
			$setime = strtotime(date('Y-m-d ') . $sf);

			if ($nows < $setime) {
				$timeerrorstr = '抱歉！尚未到营业时间！';
			}

			if (empty($outset['zc_edate']) && !empty($outset['zc_sdate']) && ($nows < $setime)) {
				$timeerrorstr = '';
			}

			if (empty($outset['zc_sdate']) && ($setime < $nows)) {
				$timeerrorstr = '';
			}

			if ($setime < $nows) {
				$timeerrorstr = '';
			}

			if ($nows < $fetime) {
				$timeerrorstr = '';
			}
		}

		if (isset($outset['wc_edate']) && (0 < $outset['wc_edate'])) {
			$ef = date('H:i', $outset['wc_edate']);
			$setime = strtotime(date('Y-m-d ') . $ef);

			if ($setime < $nows) {
				$timeerrorstr = '抱歉！已经过了营业时间了！';
			}
		}

		if (!empty($timeerrorstr)) {
			$this->exitdisplay($timeerrorstr);
		}

		$company = $this->getCompany($cid);
		if ($company && is_array($company)) {
			$_SESSION['session_shop_' . $this->token] = $cid;
		}

		$DishC = $this->getDishCompany($cid);
		$kconoff = $DishC['kconoff'];
		$Mcompany = $this->getDishMainCompany(false);
		$discount = $outset['discount'];
		$dishofcid = $cid;
		if (($Mcompany['cid'] != $cid) && ($Mcompany['dishsame'] == 1)) {
			$dishofcid = $Mcompany['cid'];
			$kconoff = $Mcompany['kconoff'];
			$discount = $Mcompany['discount'];
		}

		$dish_sort = M('Dish_sort')->where(array('cid' => $dishofcid))->order('`sort` ASC')->select();
		$dish = M('Dish')->where(array('cid' => $dishofcid, 'isopen' => 1, 'istakeout' => 1))->order('`sort` ASC')->select();
		$starttime = strtotime(date('Y-m') . '-01 00:00:00');
		$t = date('t');
		$endtime = strtotime(date('Y-m') . '-' . $t . ' 23:59:59');
		$Model = new Model();
		$sqlstr = 'select cid,did,sum(nums) as tnums from ' . C('DB_PREFIX') . 'dishout_salelog where cid=' . $cid . ' AND token=\'' . $this->token . '\' AND addtime>=' . $starttime . ' AND addtime<=' . $endtime . ' group by did';
		$tmp = $Model->query($sqlstr);
		$newtmp = array();

		if (!empty($tmp)) {
			foreach ($tmp as $vv) {
				$newtmp[$vv['did']] = $vv['tnums'];
			}
		}

		$fenleiarr = array();

		if (is_array($dish_sort)) {
			foreach ($dish_sort as $sk => $sv) {
				$fenleiarr[$sv['id']] = $sv['name'];
			}
		}

		$isHave = $_SESSION[$this->session_dish_info];
		$isHave = ($isHave && !empty($isHave) ? unserialize($isHave) : array());
		$isHavePt = (!empty($isHave) ? $isHave['pt'] : array());
		$isHaveTj = (!empty($isHave) ? $isHave['tj'] : array());
		$disharr = $dztjtmp = array();

		if (is_array($dish)) {
			foreach ($dish as $dk => $dv) {
				$dv['sortname'] = $fenleiarr[$dv['sid']];
				$dv['sortname'] = $dv['sortname'] ? $dv['sortname'] : '无';
				if ((0 < $discount) && $this->isMember && $dv['isdiscount']) {
					$dv['zkprice'] = ($dv['price'] * $discount) / 10;
				}

				if (array_key_exists($dv['id'], $isHavePt)) {
					$dv['ornum'] = $isHavePt[$dv['id']]['ornum'];
				}

				if (array_key_exists($dv['id'], $newtmp)) {
					$dv['m_sale'] = $newtmp[$dv['id']];
				}
				else {
					$dv['m_sale'] = 0;
				}

				if (array_key_exists($dv['sid'], $disharr)) {
					$disharr[$dv['sid']][] = $dv;
				}
				else {
					$disharr[$dv['sid']] = array();
					$disharr[$dv['sid']][] = $dv;
				}

				if ($dv['ishot'] == 1) {
					if (array_key_exists($dv['id'], $isHaveTj)) {
						$dv['ornum'] = $isHaveTj[$dv['id']]['ornum'];
					}
					else {
						$dv['ornum'] = 0;
					}

					$dztjtmp[] = $dv;
					$dztj = true;
				}
			}
		}

		$newtmpdisharr = array();

		foreach ($fenleiarr as $ssk => $ssv) {
			if (!empty($disharr[$ssk])) {
				$newtmpdisharr[$ssk] = $disharr[$ssk];
			}
			else {
				unset($fenleiarr[$ssk]);
			}
		}

		$disharr = $newtmpdisharr;
		$disharr['dztj'] = !empty($dztjtmp) ? $dztjtmp : array();
		$this->assign('kconoff', $kconoff);
		$this->assign('dz_tj', $dztj);
		$this->assign('stype', $outset['stype']);
		$my_distance = session($this->wecha_id . $this->token . $cid . 'distance');
		if (($outset['overflow_radius'] == 2) && ($outset['removing'] < $my_distance)) {
			$pricing_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
		}
		else {
			$pricing_jia = 0;
		}

		$pricing_jia = ($pricing_jia < 0 ? 0 : $pricing_jia);
		$this->assign('pricing', $outset['pricing'] + $pricing_jia);
		if (($outset['overflow_radius'] == 5) && ($outset['removing'] < $my_distance)) {
			$delivery_fee_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
		}
		else {
			$delivery_fee_jia = 0;
		}

		$delivery_fee_jia = ($delivery_fee_jia < 0 ? 0 : $delivery_fee_jia);
		$this->assign('delivery_fee', ($outset['delivery_fee'] / 100) + $delivery_fee_jia);
		$this->assign('discount', $discount);
		$this->assign('isMember', $this->isMember);
		$this->assign('cid', $cid);
		$this->assign('fenleiarr', $fenleiarr);
		$this->assign('disharr', $disharr);
		$this->assign('company', $company);
		$this->assign('metaTitle', $company['name']);
		$this->display();
	}

	public function sureOrder()
	{
		$dishtmp = $_POST['dish'];
		$dzdish = $_POST['dzdish'];
		$tmpcid = intval($_POST['mycid']);
		$disharr = array();
		$outset = $this->outManage($tmpcid);
		$tmpdisharr = $dzdisharr = array();
		if ((0 < $tmpcid) && ($tmpcid == $this->_cid)) {
			foreach ($dishtmp as $kk => $vv) {
				$vv = ($vv ? intval($vv) : 0);

				if (0 < $vv) {
					$tmpdisharr[$kk] = array('id' => $kk, 'ornum' => $vv);
				}

				$dztjvv = (isset($dzdish[$kk]) && !empty($dzdish[$kk]) ? intval($dzdish[$kk]) : 0);

				if (0 < $dztjvv) {
					$dzdisharr[$kk] = array('id' => $kk, 'ornum' => $dztjvv);
				}

				$vv = $vv + $dztjvv;

				if (0 < $vv) {
					$disharr[$kk] = array('id' => $kk, 'ornum' => $vv);
				}
			}

			if (empty($disharr)) {
				$this->exitdisplay('您尚未点菜！');
			}

			$this->checkInstock($disharr, $tmpcid);
			$DishC = $this->getDishCompany($tmpcid);
			$kconoff = $DishC['kconoff'];
			$Mcompany = $this->getDishMainCompany(false);
			$discount = $outset['discount'];
			$dishofcid = $tmpcid;
			if (($Mcompany['cid'] != $tmpcid) && ($Mcompany['dishsame'] == 1)) {
				$dishofcid = $Mcompany['cid'];
				$kconoff = $Mcompany['kconoff'];
				$discount = $Mcompany['discount'];
			}

			$_SESSION[$this->session_dish_info] = serialize(array('pt' => $tmpdisharr, 'tj' => $dzdisharr));
			unset($tmpdisharr);
			unset($dzdisharr);
			$idarr = array_keys($disharr);
			sort($idarr);
			$idstr = implode(',', $idarr);
			$db_dish = M('Dish');
			$dish = $db_dish->where('id in(' . $idstr . ') and cid="' . $dishofcid . '" and isopen="1" and istakeout="1"')->order('`sort` ASC')->select();
			$totl = $ornum = 0;

			foreach ($dish as $val) {
				$index = $val['id'];
				if ((0 < $discount) && $this->isMember && $val['isdiscount']) {
					$val['zkprice'] = ($val['price'] * $discount) / 10;
				}

				$disharr[$index] = array_merge($disharr[$index], $val);
				$totl += $disharr[$index]['price'] * $disharr[$index]['ornum'];
				$ornum += $disharr[$index]['ornum'];
			}

			$permin = (0 < $outset['permin'] ? $outset['permin'] : 15);
			$sendtime = (0 < $outset['sendtime'] ? $outset['sendtime'] : $permin);
			$starttime = $current = time();
			$tomorrowtime = $timearr = $time2arr = array();
			$starttime = date('Y-m-d') . ' ' . $outset['stimestr'];
			$starttime = strtotime($starttime);
			if (($starttime < $current) && ($outset['stimestr'] != '00:00') && ($outset['etime2str'] != '23:59')) {
				$starttime = $current;
			}

			$endtime = date('Y-m-d') . ' ' . $outset['etimestr'];
			$endtime = strtotime($endtime) + 60;
			$starttime = $starttime + ($sendtime * 60);
			$t1 = strtotime(date('Y-m-d H', $starttime) . ':00:00');
			$t2 = $starttime - $t1;
			$t3 = $permin * 60;
			$t4 = $sendtime * 60;

			if ($t2 < $t3) {
				$starttime = $t1 + $t3;
			}
			else if ($t4 < $t2) {
				$starttime = $t1 + $t4 + $t3;
			}
			else {
				$starttime = $t1 + (2 * $t3);
			}

			$tmptime = $endtime - $starttime;

			if (0 < $tmptime) {
				$mins = floor($tmptime / 60);
				$timearr[] = date('H:i', $starttime);

				if ($permin < $mins) {
					for ($i = $permin; $i <= $mins; $i = $i + $permin) {
						$timearr[] = date('H:i', ($i * 60) + $starttime);
					}
				}
			}
			else {
				$timearr[] = date('H:i', $endtime);
			}

			if (!empty($outset['stime2str']) && !empty($outset['etime2str'])) {
				$start2time = date('Y-m-d') . ' ' . $outset['stime2str'];
				$start2time = strtotime($start2time);

				if ($start2time < $current) {
					$start2time = $current;
				}

				$end2time = date('Y-m-d') . ' ' . $outset['etime2str'];
				$end2time = strtotime($end2time) + 60;
				$start2time = $start2time + ($sendtime * 60);
				$t1 = strtotime(date('Y-m-d H', $start2time) . ':00:00');
				$t2 = $start2time - $t1;
				$t3 = $permin * 60;
				$t4 = $sendtime * 60;

				if ($t2 < $t3) {
					$start2time = $t1 + $t3;
				}
				else if ($t4 < $t2) {
					$start2time = $t1 + $t4 + $t3;
				}
				else {
					$start2time = $t1 + (2 * $t3);
				}

				$tmptime = $end2time - $start2time;

				if (0 < $tmptime) {
					$mins = floor($tmptime / 60);
					$time2arr[] = date('H:i', $start2time);

					if ($permin < $mins) {
						for ($i = $permin; $i <= $mins; $i = $i + $permin) {
							$time2arr[] = date('H:i', ($i * 60) + $start2time);
						}
					}
				}
				else {
					$time2arr[] = date('H:i', $end2time);
				}
			}

			$tomorrowday = date('Y-m-d', strtotime('+1 day'));
			if (($outset['stimestr'] == '00:00') && ($outset['etime2str'] == '23:59')) {
				$tomorrowtime = $timearr;
			}

			if ($current < $endtime) {
				$timearr = array_merge($timearr, $time2arr);
			}
			else {
				$timearr = $time2arr;
			}

			$lastkey = count($timearr) - 1;
			if ((0 < $lastkey) && ($timearr[$lastkey] == '00:00')) {
				$timearr[$lastkey] = $tomorrowday . ' 00:00';
			}

			$Dish_order = M('Dish_order');
			$contact = false;

			if ($this->wecha_id) {
				$orderinfo = $Dish_order->where(array('token' => $this->token, 'cid' => $tmpcid, 'wecha_id' => $this->wecha_id))->order('paid DESC,id DESC ')->find();

				if (!empty($orderinfo)) {
					$contact['youname'] = $orderinfo['name'];
					$contact['yousex'] = $orderinfo['sex'];
					$contact['youtel'] = $orderinfo['tel'];
					$contact['youaddress'] = $orderinfo['address'];
				}
			}

			$company = $this->getCompany($tmpcid);
			if ($company && is_array($company)) {
				$_SESSION['session_shop_' . $this->token] = $tmpcid;
			}

			$this->assign('kconoff', $kconoff);
			$this->assign('tomorrowday', $tomorrowday);
			$this->assign('tomorrowtime', $tomorrowtime);
			$this->assign('timearr', $timearr);
			$this->assign('contact', $contact);
			$this->assign('stype', $outset['stype']);
			$my_distance = session($this->wecha_id . $this->token . $tmpcid . 'distance');
			if (($outset['overflow_radius'] == 2) && ($outset['removing'] < $my_distance)) {
				$pricing_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
			}
			else {
				$pricing_jia = 0;
			}

			$pricing_jia = ($pricing_jia < 0 ? 0 : $pricing_jia);
			$this->assign('pricing', $outset['pricing'] + $pricing_jia);
			if (($outset['overflow_radius'] == 5) && ($outset['removing'] < $my_distance)) {
				$delivery_fee_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
			}
			else {
				$delivery_fee_jia = 0;
			}

			$delivery_fee_jia = ($delivery_fee_jia < 0 ? 0 : $delivery_fee_jia);
			$this->assign('delivery_fee', ($outset['delivery_fee'] / 100) + $delivery_fee_jia);
			$this->assign('isMember', $this->isMember);
			$this->assign('discount', $discount);
			$this->assign('ortotl', $totl);
			$this->assign('ornum', $ornum);
			$this->assign('cid', $tmpcid);
			$this->assign('ordish', $disharr);
			$this->assign('company', $company);
			$this->assign('metaTitle', $company['name']);
			$this->display();
		}
		else {
			$this->exitdisplay('提交信息出错');
		}
	}

	private function exitdisplay($tips = '', $returnurl = false)
	{
		$this->error($tips, $returnurl);
		exit();
	}

	private function getDishCompany($cid, $cache = true)
	{
		$DishC = $_SESSION['session_dish' . $cid . '_' . $this->token];
		$DishC = (!empty($DishC) ? unserialize($DishC) : false);
		if ($cache && !empty($DishC)) {
			return $DishC;
		}
		else {
			$DishC = M('Dish_company')->where(array('cid' => $cid))->find();

			if ($cache) {
				$_SESSION['session_dish' . $cid . '_' . $this->token] = !empty($DishC) ? serialize($DishC) : '';
			}

			return $DishC;
		}
	}

	private function getCompany($cid, $cache = true)
	{
		$this->mycompany = $_SESSION['session_shop' . $cid . '_' . $this->token];
		$this->mycompany = !empty($this->mycompany) ? unserialize($this->mycompany) : false;
		if ($cache && !empty($this->mycompany)) {
			return $this->mycompany;
		}
		else {
			$company = M('Company')->where(array('token' => $this->token, 'id' => $cid))->find();
			if (empty($company) || !is_array($company)) {
				$this->redirect(U('DishOut/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
			}

			if ($cache) {
				$this->mycompany = $company;
				$_SESSION['session_shop' . $cid . '_' . $this->token] = !empty($company) ? serialize($company) : '';
			}

			return $company;
		}
	}

	private function outManage($cid)
	{
		if (!empty($this->shopmanage)) {
			return $this->shopmanage;
		}
		else {
			$outset = M('Dishout_manage')->where(array('token' => $this->token, 'cid' => $cid))->find();
			$tmp = M('Dish_company')->where(array('cid' => $cid))->find();
			if (is_array($tmp) && ($tmp['istakeaway'] == 1)) {
				$no_outset = array('token' => $this->token, 'cid' => $cid, 'stype' => 1, 'pricing' => 0, 'sendtime' => 30, 'removing' => 5, 'permin' => 15, 'zc_sdate' => 0, 'zc_edate' => 0, 'wc_sdate' => 0, 'wc_edate' => 0, 'shopimg' => '', 'area' => '');
				$outset = (empty($outset) ? $no_outset : $outset);
			}

			if (empty($outset) || !is_array($outset)) {
				$this->exitdisplay('抱歉！此商家还没有设置外卖管理相关信息');
			}

			$stimestr = (0 < $outset['zc_sdate'] ? date('H:i', $outset['zc_sdate']) : '00:00');
			$etimestr = '23:59';
			$stime2str = $etime2str = '';
			if ((0 < $outset['wc_sdate']) && (0 < $outset['zc_edate'])) {
				$etimestr = date('H:i', $outset['zc_edate']);
				$stime2str = date('H:i', $outset['wc_sdate']);
				$etime2str = (0 < $outset['wc_edate'] ? date('H:i', $outset['wc_edate']) : '23:59');
			}

			if ((0 < $outset['wc_sdate']) && !(0 < $outset['zc_edate'])) {
				if (!(0 < $outset['zc_sdate'])) {
					$stimestr = date('H:i', $outset['wc_sdate']);
					$etimestr = (0 < $outset['wc_edate'] ? date('H:i', $outset['wc_edate']) : '23:59');
				}
				else {
					$etimestr = date('H:i', $outset['wc_sdate']);
					$stime2str = date('H:i', $outset['wc_sdate']);
					$etime2str = (0 < $outset['wc_edate'] ? date('H:i', $outset['wc_edate']) : '23:59');
				}
			}

			if (!(0 < $outset['wc_sdate'])) {
				if (0 < $outset['zc_edate']) {
					$etimestr = date('H:i', $outset['zc_edate']);
				}
				else {
					$etimestr = (0 < $outset['wc_edate'] ? date('H:i', $outset['wc_edate']) : '23:59');
				}
			}

			$outset['stimestr'] = $stimestr;
			$outset['etimestr'] = $etimestr;
			$outset['stime2str'] = $stime2str;
			$outset['etime2str'] = $etime2str;
			unset($stimestr);
			unset($etimestr);
			unset($stime2str);
			unset($etime2str);
			$this->shopmanage = $outset;
			$_SESSION['manage_shop' . $cid . '_' . $this->token] = !empty($outset) ? serialize($outset) : '';
			return $outset;
		}
	}

	public function companyMap()
	{
		if (C('baidu_map')) {
			$isamap = 0;
		}
		else {
			$isamap = 1;
		}

		$this->apikey = C('baidu_map_api');
		$this->assign('apikey', $this->apikey);
		$cid = ($this->_get('cid') ? intval($this->_get('cid', 'trim')) : 0);
		$company = $this->getCompany($cid, false);
		$this->assign('thisCompany', $company);

		if (!$isamap) {
			$this->display();
		}
		else {
			$this->amap = new amap();
			$link = $this->amap->getPointMapLink($company['longitude'], $company['latitude'], $company['name']);
			header('Location:' . $link);
		}
	}

	public function OrderPay()
	{
		$disharr = $_POST['dish'];
		$shopid = intval($_POST['mycid']);
		$totalmoney = floatval(trim($_POST['totalmoney']));
		$totalnum = intval(trim($_POST['totalnum']));
		$ouserName = htmlspecialchars(trim($_POST['ouserName']), ENT_QUOTES);
		$ouserSex = intval(trim($_POST['ouserSex']));
		$ouserTel = htmlspecialchars(trim($_POST['ouserTel']), ENT_QUOTES);
		$ouserAddres = htmlspecialchars(trim($_POST['ouserAddres']), ENT_QUOTES);
		$oarrivalTime = htmlspecialchars(trim($_POST['oarrivalTime']), ENT_QUOTES);
		$omark = htmlspecialchars(trim($_POST['omark']), ENT_QUOTES);
		$Mcompany = $this->getDishMainCompany(false);
		$dishofcid = $shopid;
		$now_dishout_manage = M('dishout_manage')->where(array('token' => $this->token, 'cid' => $shopid))->find();
		$my_distance = session($this->wecha_id . $this->token . $shopid . 'distance');
		if (($now_dishout_manage['overflow_radius'] == 3) && ($now_dishout_manage['removing'] < $my_distance)) {
			$this->error('对不起，您已经超出了本店送餐范围。');
			exit();
		}

		if (($Mcompany['cid'] != $shopid) && ($Mcompany['dishsame'] == 1)) {
			$dishofcid = $Mcompany['cid'];
			$kconoff = $Mcompany['kconoff'];
		}

		if (0 < $shopid) {
			$jumpurl = U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $shopid, 'wecha_id' => $this->wecha_id));
			if (empty($disharr) || !(0 < $totalmoney) || !(0 < $totalnum)) {
				$this->exitdisplay('订单信息出错！', $jumpurl);
			}

			if (empty($ouserName) || empty($ouserTel) || empty($ouserAddres)) {
				$this->exitdisplay('订单中相关联系地址：姓名或联系电话或送货地址有没写的', $jumpurl);
			}

			if (preg_match('/\\-\\d{2}\\s\\d{2}\\:/', $oarrivalTime)) {
				$oarrivalTime = strtotime($oarrivalTime);
			}
			else {
				$oarrivalTime = ($oarrivalTime ? strtotime(date('Y-m-d ') . $oarrivalTime) : 0);
			}

			$tmparr = array();
			$tmpsubnum = 0;
			$tmpsubmoney = 0;
			$disharr && ($idarr = array_keys($disharr));

			if ($idarr) {
				$dishs = M('Dish')->where(array(
	'id'     => array('in', $idarr),
	'cid'    => $dishofcid,
	'isopen' => 1
	))->select();

				foreach ($dishs as $dh) {
					if (isset($disharr[$dh['id']]['num']) && $disharr[$dh['id']]['num']) {
						$tmpnum = $disharr[$dh['id']]['num'];
						$discount = trim($disharr[$dh['id']]['discount']);

						if (0 < $discount) {
							$tmpprice = ($discount * $dh['price']) / 10;
						}
						else {
							$tmpprice = $dh['price'];
						}

						if ((0 < $tmpprice) && ($tmpprice < 0.01)) {
							$tmpprice = 0.01;
						}

						$tmparr[$dh['id']] = array();
						$tmparr[$dh['id']]['did'] = $dh['id'];
						$tmparr[$dh['id']]['num'] = $tmpnum;
						$tmparr[$dh['id']]['discount'] = $discount;
						$tmparr[$dh['id']]['price'] = $tmpprice;
						$tmparr[$dh['id']]['name'] = $dh['name'];
						$tmparr[$dh['id']]['kitchen_id'] = $dh['kitchen_id'];
						$tmparr[$dh['id']]['omark'] = htmlspecialchars(trim($disharr[$dh['id']]['omark']), ENT_QUOTES);
						$tmpsubnum += $tmpnum;
						$tmpsubmoney += $tmpprice * $tmpnum;
					}
				}
			}

			if (empty($tmparr)) {
				$this->exitdisplay('没有订单信息', $jumpurl);
			}

			if (stripos($tmpsubmoney, '.')) {
				$mtmpArr = explode('.', $tmpsubmoney);
				$prxiaoshu = (string) $mtmpArr[1];
				$tmpsubmoney = $mtmpArr[0] . '.' . substr($prxiaoshu, 0, 2);
			}

			$t_tmpsubmoney = (int) ($tmpsubmoney * 1000);
			$t_totalmoney = (int) ($totalmoney * 1000);
			if (($tmpsubnum != $totalnum) || ($t_tmpsubmoney != $t_totalmoney)) {
				$this->error('订单的金额或点的菜的份数不对', $jumpurl);
			}

			$this->checkInstock($tmparr, $shopid, true);
			$outset = $this->outManage($shopid);
			$my_distance = session($this->wecha_id . $this->token . $shopid . 'distance');
			if (($outset['overflow_radius'] == 2) && ($outset['removing'] < $my_distance)) {
				$pricing_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
			}
			else {
				$pricing_jia = 0;
			}

			$pricing_jia = ($pricing_jia < 0 ? 0 : $pricing_jia);
			$outset['pricing'] = $outset['pricing'] + $pricing_jia;
			if (($outset['stype'] == 2) && ($tmpsubmoney < $outset['pricing'])) {
				$tmpsubmoney = $outset['pricing'];
			}

			if (($outset['overflow_radius'] == 5) && ($outset['removing'] < $my_distance)) {
				$delivery_fee_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
			}
			else {
				$delivery_fee_jia = 0;
			}

			$delivery_fee_jia = ($delivery_fee_jia < 0 ? 0 : $delivery_fee_jia);
			$delivery_fee = ($outset['delivery_fee'] / 100) + $delivery_fee_jia;
			$tmpsubmoney = $tmpsubmoney + $delivery_fee;
			$wecha_id = ($this->wecha_id ? $this->wecha_id : 'DishOutm_' . $ouserTel);
			$orderid = substr($wecha_id, -5) . date('YmdHis');
			$Orderarr = array('cid' => $shopid, 'wecha_id' => $wecha_id, 'token' => $this->token, 'total' => $tmpsubnum, 'price' => $tmpsubmoney, 'nums' => 1, 'info' => serialize($tmparr), 'name' => $ouserName, 'sex' => $ouserSex, 'tel' => $ouserTel, 'address' => $ouserAddres, 'tableid' => 0, 'time' => time(), 'reservetime' => $oarrivalTime, 'stype' => $outset['stype'], 'paid' => 0, 'isuse' => 0, 'orderid' => $orderid, 'printed' => 0, 'des' => $omark, 'takeaway' => 1, 'comefrom' => 'dishout', 'delivery_fee' => $delivery_fee * 100);
			$orid = D('Dish_order')->add($Orderarr);

			if ($orid) {
				$_SESSION[$this->session_dish_info] = '';
				$company = $this->getCompany($shopid);
				$params = array();
				$params['site'] = array('token' => $this->token, 'from' => '微外卖消息', 'content' => '顾客' . $ouserName . '刚刚叫了一份外卖，订单号：' . $orderid . '，请您注意查看并处理');
				MessageFactory::method($params, 'SiteMessage');
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'des' => trim($_POST['omark']), 'companytel' => $company['tel'], 'truename' => trim($_POST['ouserName']), 'tel' => trim($_POST['ouserTel']), 'address' => trim($_POST['ouserAddres']), 'buytime' => $Orderarr['time'], 'orderid' => $Orderarr['orderid'], 'sendtime' => 0 < $oarrivalTime ? $oarrivalTime : '尽快送达', 'price' => $Orderarr['price'], 'total' => $Orderarr['total'], 'typename' => '外卖', 'list' => $tmparr);
				$msg = ArrayToStr::array_to_str($msg, 0);
				$op->printit($this->token, $shopid, 'DishOut', $msg, 0);
/*
				if ($kitchens_list = D('Dish_kitchen')->where(array('cid' => $this->_cid))->select()) {
					$t_list = array();

					foreach ($tmparr as $dish) {
						$t_list[$dish['kitchen_id']][] = $dish;
					}

					$kitchens = array();

					foreach ($kitchens_list as $kit_row) {
						$kitchens[$kit_row['id']] = $kit_row;
					}

					$print_msg = array('des' => trim($_POST['omark']), 'truename' => trim($_POST['ouserName']), 'tel' => trim($_POST['ouserTel']), 'address' => trim($_POST['ouserAddres']), 'buytime' => $Orderarr['time'], 'orderid' => $Orderarr['orderid'], 'sendtime' => 0 < $oarrivalTime ? $oarrivalTime : '尽快送达');

					foreach ($t_list as $k => $rowset) {
						if ($k) {
							if (isset($kitchens[$k]) && $kitchens[$k]['status']) {
								for ($i = 0; $i < count($rowset); $i++) {
									$msg = $print_msg;
									$msg["kitchenname"] = $kitchens[$k]["name"];
									$msg['list'][] = $rowset[$i];
									$msg = ArrayToStr::array_to_str($msg, 0);
									$op->printit($this->token, $this->_cid, 'DishOut', $msg, 0, '', $k);
								}
							}
							else {
								$msg = $print_msg;
								$msg["kitchenname"] = $kitchens[$k]["name"];
								$msg['list'] = $rowset;
								$msg = ArrayToStr::array_to_str($msg, 0);
								$op->printit($this->token, $this->_cid, 'DishOut', $msg, 0, '', $k);
							}
						}
					}
				}
*/
				$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();

				if ($alipayConfig['open']) {
					$this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $wecha_id, 'success' => 1, 'from' => 'DishOut', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $tmpsubmoney)));
				}
				else {
					$this->exitdisplay('商家尚未开启支付功能', $jumpurl);
				}
			}
			else {
				$this->exitdisplay('订单录入系统出错，抱歉给您的带来了不便。请重新下单吧', $jumpurl);
			}

			if (!empty($this->wecha_id)) {
				$userinfo_model = M('Userinfo');
				$thisUser = $userinfo_model->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();

				if (empty($thisUser)) {
					$userRow = array('tel' => $ouserTel, 'truename' => $ouserName, 'address' => $ouserAddres);
					$userRow['token'] = $this->token;
					$userRow['wecha_id'] = $this->wecha_id;
					$userRow['wechaname'] = '';
					$userRow['qq'] = 0;
					$userRow['sex'] = $ouserSex;
					$userRow['age'] = 0;
					$userRow['birthday'] = '';
					$userRow['info'] = '';
					$userRow['total_score'] = 0;
					$userRow['sign_score'] = 0;
					$userRow['expend_score'] = 0;
					$userRow['continuous'] = 0;
					$userRow['add_expend'] = 0;
					$userRow['add_expend_time'] = 0;
					$userRow['live_time'] = 0;
					$userinfo_model->add($userRow);
				}
			}
		}
		else {
			$jumpurl = U('DishOut/index', array('token' => $this->token));
			$this->exitdisplay('订单信息中店面信息出错', $jumpurl);
		}
	}

	private function checkInstock($dishArr, $tcid = 0, $isadd = false)
	{
		if (empty($dishArr)) {
			return true;
		}

		$tcid = (0 < $tcid ? $tcid : $this->_cid);

		if (!(0 < $tcid)) {
			return true;
		}

		$DishC = $this->getDishCompany($tcid);
		$tkconoff = $DishC['kconoff'];
		$Mcompany = $this->getDishMainCompany();
		$tdishofcid = $tcid;
		if (($Mcompany['cid'] != $tcid) && ($Mcompany['dishsame'] == 1)) {
			$tdishofcid = $Mcompany['cid'];
			$tkconoff = $Mcompany['kconoff'];
		}

		$dishDb = M('dish');

		if (0 < $tkconoff) {
			$nowtime = time();

			foreach ($dishArr as $kkd => $vvd) {
				$did = (isset($vvd['did']) ? $vvd['did'] : $kkd);
				$tmpdish = $dishDb->where(array('id' => $did, 'cid' => $tdishofcid))->find();
				$tmpinstock = $tmpdish['tmpinstock'] + $vvd['num'];

				if (!(0 < $tmpdish['instock'])) {
					$this->exitdisplay('抱歉！' . $vvd['name'] . ' 这道菜今日已经买完了，请重新点菜吧！', U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $tcid)));
				}

				if ($tmpdish['instock'] < $tmpinstock) {
					$locktime = $tmpdish['locktime'] + 420;

					if ($locktime < $nowtime) {
						$dishDb->where(array('id' => $did, 'cid' => $tdishofcid))->save(array('tmpinstock' => 0, 'locktime' => 0));

						if ($tmpdish['instock'] < $vvd['num']) {
							$this->exitdisplay('抱歉！' . $vvd['name'] . ' 这道菜库存不足请少点几份吧！', U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $tcid)));
						}
					}
					else {
						$this->exitdisplay('抱歉！' . $vvd['name'] . ' 库存不足请稍等几分钟重新点菜吧！', U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $tcid)));
					}
				}

				if ($isadd) {
					$dishDb->where(array('id' => $did, 'cid' => $tdishofcid))->save(array('tmpinstock' => $tmpinstock, 'locktime' => $nowtime));
				}
			}
		}
		else {
			return true;
		}

		return true;
	}

	public function OrderPayAgain()
	{
		$orid = ($this->_get('orid') ? intval($this->_get('orid', 'trim')) : 0);
		$cid = ($this->_get('cid') ? intval($this->_get('cid', 'trim')) : 0);
		if ((0 < $orid) && (0 < $cid)) {
			$Dish_order = M('Dish_order');
			$myorder = $Dish_order->where(array('id' => $orid, 'token' => $this->token, 'cid' => $cid))->find();

			if ($myorder) {
				$dishArr = unserialize($myorder['info']);

				if (!empty($dishArr)) {
					$this->checkInstock($dishArr, $cid);
				}

				$price = $myorder['price'] - $myorder['havepaid'];
				$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();

				if ($alipayConfig['open']) {
					$this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $myorder['wecha_id'], 'success' => 1, 'from' => 'DishOut', 'orderName' => $myorder['orderid'], 'single_orderid' => $myorder['orderid'], 'price' => $price)));
					exit();
				}
				else {
					$this->error('商家尚未开启支付功能');
				}
			}
		}

		$this->error('订单信息出错！');
	}

	public function myOrder()
	{
		$this->_cid = 0 < $this->_cid ? $this->_cid : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
		$_SESSION['session_shop_' . $this->token] = $this->_cid;
		$where = array('wecha_id' => $this->wecha_id, 'token' => $this->token, 'cid' => $this->_cid, 'isdel' => '0', 'comefrom' => 'dishout');
		$dish_order = M('Dish_order')->where($where)->order('id DESC')->limit(7)->select();
		$list = array();
		$payarr = array('alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpaycomputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线');

		foreach ($dish_order as $row) {
			$row['info'] = unserialize($row['info']);
			$paystr = strtolower($row['paytype']);
			$row['paytypestr'] = !empty($paystr) && array_key_exists($paystr, $payarr) ? $payarr[$paystr] : '其他';
			if (!$row['paid'] && ($row['paytype'] != 'daofu') && ($row['paytype'] != 'dianfu')) {
				$row['paystatus'] = '未付款';
			}
			else {
				$row['paystatus'] = '';
			}

			$list[] = $row;
		}

		$company = $this->getCompany($this->_cid);
		$this->assign('company', $company);
		$this->assign('cid', $this->_cid);

		foreach ($list as $lk => $lv) {
			if (0 < $lv['deliverymanid']) {
				$list[$lk]['deliveryman'] = M('dishout_deliveryman')->where(array('token' => $this->token, 'cid' => $lv['cid'], 'id' => $lv['deliverymanid']))->find();
			}
		}

		$this->assign('orderList', $list);
		$this->assign('today', strtotime(date('Y-m-d') . ' 00:00:00'));
		$this->assign('metaTitle', '我的订单');
		$this->display();
	}

	public function myorder_yes()
	{
		M('dish_order')->where(array('token' => $this->token, 'cid' => (int) $_GET['cid'], 'id' => (int) $_GET['id']))->save(array('isyes' => 1));
		$this->success('确认收货成功', U('DishOut/myOrder', array('token' => $this->token, 'cid' => $_GET['cid'])));
	}

	public function payReturn()
	{
		$orderid = trim($_GET['orderid']);

		if (isset($_GET['nohandle'])) {
			$order = M('dish_order')->where(array('orderid' => $orderid, 'token' => $this->token))->find();
			$this->redirect(U('DishOut/myOrder', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $order['cid'])));
		}
		else {
			ThirdPayDishOut::index($orderid);
		}
	}
}

?>
