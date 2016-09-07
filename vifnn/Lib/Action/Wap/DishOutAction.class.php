<?php

/**
 * **外卖前台处理控制器
 * **LiHongShun
 * **2015-01-10
 * */
class DishOutAction extends WapAction {

    public $_cid = 0;
    public $mycompany;
    public $mshop;
    public $isMember = 0;

    public function _initialize() {
        parent::_initialize();
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($agent, "MicroMessenger")) {
            $this->assign('iswxbrowse', true);
        } else {
            $this->assign('iswxbrowse', false);
        }
        $this->_cid = $_SESSION["session_shop_{$this->token}"];
        $this->_cid = $this->_cid > 0 ? $this->_cid : 0;
        $this->assign('cid', $this->_cid);
        $this->shopmanage = $_SESSION["manage_shop{$this->_cid}_{$this->token}"];
        $this->shopmanage = !empty($this->shopmanage) ? unserialize($this->shopmanage) : false;
        $this->isMember = $this->getCardInfo();
        $this->isMember = !empty($this->isMember) ? 1 : 0;
    }

    /**
     * 餐厅分布
     */
    public function index() {
		$_SESSION['topaysuccessTips']=null;
        $company = M('Company')->where("token='{$this->token}' AND display=1 AND (`business_type` LIKE '%DishOut%' OR `business_type`='')  AND shortname!='Medical'")->select();
        /*         * if (count($company) == 1) {
          $this->redirect(U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $company['0']['id'], 'wecha_id' => $this->wecha_id)));//****还是要过一下，不然一个门店时获取不到经纬度
          } else {**** */
        $nowlat = $this->_get('nowlat') ? floatval($this->_get('nowlat', "trim")) : 0;
        $nowlng = $this->_get('nowlng') ? floatval($this->_get('nowlng', "trim")) : 0;
        if (($nowlat > 0) && ($nowlng > 0)) {
            $tmpe = array();
            foreach ($company as $kk => $vv) {
                $tmpd = $this->getDistance_map($nowlat, $nowlng, $vv['latitude'], $vv['longitude']);
                $tmpdstr = $tmpd > 1000 ? (round(floatval($tmpd / 1000), 2) . ' km') : (intval($tmpd) . " m");
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
            $company = !empty($newCy) ? $newCy : $company;
            $this->assign('is_dwei', true);
            if (count($company) == 1) {
                $this->redirect(U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $company['0']['id'], 'nowlat' => $nowlat, 'nowlng' => $nowlng)));
                exit();
                /*                 * **还是要过一下，不然一个门店时获取不到经纬度* */
            }
        }
        $this->assign('company', $company);
        $this->assign('companytt', count($company));
        $this->assign('metaTitle', '餐厅分布');
        $this->display();
        //}
    }

    /*     * 计算两经纬度间的距离* */

    private function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b) {
        //R是地球半径（米）
        $R = 6377830;
        $pk = doubleval(180 / 3.1415926);

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

    /**
     * 我的门店
     */
    public function MyShop() {
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        if (($cid > 0) && ($cid == $this->_cid)) {
            $outset = $this->outManage($cid);
            $company = $this->getCompany($cid);
            $imgarr = !empty($outset['shopimg']) ? unserialize($outset['shopimg']) : array();
            if (!empty($imgarr) && !empty($imgarr['tourl'])) {
                foreach ($imgarr['tourl'] as $ukk => $uvv) {
                    $imgarr['tourl'][$ukk] = $this->getLink(htmlspecialchars_decode($uvv, ENT_QUOTES));
                }
            }
            $outinfo = array();
            $outinfo['id'] = $cid;
            $outinfo['sendtime'] = $outset['sendtime'];
            $outinfo['removing'] = $outset['removing'];
            $outinfo['stype'] = $outset['stype'];
            $my_distance = session($this->wecha_id . $this->token . $cid . 'distance');
            if ($outset['overflow_radius'] == 2 && $outset['removing'] < $my_distance) {
                $pricing_jia = round((($my_distance - ($outset['removing'] * 1000)) / 1000) * ($outset['priceup'] / 100), 2);
            } else {
                $pricing_jia = 0;
            }
            $times=$outset['open_times']?json_decode($outset['open_times'],true):array();
            if(empty($outset['open_times'])){
                if(!empty($outset['zc_sdate'])||!empty($outset['zc_edate'])){
                    $zc_sdate=$outset['zc_sdate']?date('H:i',$outset['zc_sdate']):'00:00';
                    $zc_edate=$outset['zc_edate']?date('H:i',$outset['zc_edate']):'23:59';
                    $times[]=array('start'=>$zc_sdate,'end'=>$zc_edate);
                }
                if(!empty($outset['wc_sdate'])||!empty($outset['wc_edate'])){
                    $wc_sdate=$outset['wc_sdate']?date('H:i',$outset['wc_sdate']):'00:00';
                    $wc_edate=$outset['wc_edate']?date('H:i',$outset['wc_edate']):'23:59';
                    $times[]=array('start'=>$wc_sdate,'end'=>$wc_edate);
                }
            }
            $this->assign('open_times',$times);
            $pricing_jia = $pricing_jia < 0 ? 0 : $pricing_jia;
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
            unset($outset, $company);
            $this->assign('shopinfo', $outinfo);
            $this->assign('shopimg', $imgarr);
            $this->assign('metaTitle', '店面详情');
            $this->display();
        } else {
            $this->exitdisplay('没有获取到相关门店信息');
        }
    }

    /** 获取主店餐饮配置信息* */
    private function getDishMainCompany($cache = true) {
        $mDishC = $_SESSION["session_Maindish_{$this->token}"];
        $mDishC = !empty($mDishC) ? unserialize($mDishC) : false;
        if ($cache && !empty($mDishC)) {
            return $mDishC;
        } else {
            $MainC = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
            $m_cid = $MainC['id'];
            unset($MainC);
            $mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
            unset($m_cid);
            if ($cache) {
                $_SESSION["session_Maindish_{$this->token}"] = !empty($mDishC) ? serialize($mDishC) : '';
            } else {
                $_SESSION["session_Maindish_{$this->token}"] = '';
            }
            return $mDishC;
        }
    }

    /*     * **获取打印设置**** 不要了 在打印类里有判断 */

    private function getPrinter_set($cid, $cache = true) {
        $PsetC = $_SESSION["PrinterSet_{$this->token}_{$cid}"];
        $PsetC = !empty($PsetC) ? unserialize($PsetC) : false;
        if ($cache && !empty($PsetC)) {
            return $PsetC;
        } else {
            $PsetC = M('Orderprinter')->where(array('token' => $this->token, 'companyid' => $cid))->find();
            if ($cache) {
                $_SESSION["PrinterSet_{$this->token}_{$cid}"] = !empty($PsetC) ? serialize($PsetC) : '';
            } else {
                $_SESSION["PrinterSet_{$this->token}_{$cid}"] = '';
            }
            return $PsetC;
        }
    }

    /**
     * 点餐页
     */
    public function dishMenu() {
		$_SESSION['topaysuccessTips']=null;
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        $outset = $this->outManage($cid);
        $dishModel=XD('Wap/Dish');
        $dishOrderModel=XD('Wap/DishOrder');
        $isOpen=$dishModel->isOpen(array(
            'open_times'=>$outset['open_times'],
            'startTime1'=>$outset['zc_sdate'],
            'endTime1'=>$outset['zc_edate'],
            'startTime2'=>$outset['wc_sdate'],
            'endTime2'=>$outset['wc_edate'],
        ));
        $company = $this->getCompany($cid);
        if ($company && is_array($company)) {
            $_SESSION["session_shop_{$this->token}"] = $cid;
        }
        $DishC = $this->getDishCompany($cid); /*         * 餐厅设置信息* */
        $kconoff = $DishC['kconoff'];//是否开启库存管理
        $Mcompany = $this->getDishMainCompany(false);
        $discount = $outset['discount'];//折扣
        $dishofcid = $cid;
        if (($Mcompany['cid'] != $cid) && ($Mcompany['dishsame'] == 1)) {
            $dishofcid = $Mcompany['cid']; /*             * *开启分店统一主店 菜品功能** */
            $kconoff = $Mcompany['kconoff'];
            $discount = $Mcompany['discount'];
        }
        /** 分类* */
        $dish_sort = M('Dish_sort')->where(array('cid' => $dishofcid))->order("`sort` ASC")->select();
        $get=I('get.');
        $get['cid']=$dishofcid;
        $get['istakeout']='1';
        $list=$dishModel->getList($get,0,500);
        $groupList=$dishModel->getGroupList($dish_sort,$list);
        $my_distance = session($this->wecha_id . $this->token . $cid . 'distance');
        $delivery_fee=$dishOrderModel->getDeliveryFee($my_distance,$outset);
        $pricing=$dishOrderModel->getPricing($my_distance,$outset);
        $this->assign('delivery_fee',$delivery_fee);
        $this->assign('pricing', $pricing);
        $this->assign('discount', $discount);
        $this->assign('meal_fee',$outset['meal_fee']/100);
        $this->assign('isMember', $this->isMember);
        $this->assign('_group',$dish_sort);
        $this->assign('_groupList',$groupList);
        $this->assign('_json',$dishModel->getJson($list));
        $this->assign('cid', $cid);
        $this->assign('company', $company);
        $this->assign('kconoff', $kconoff);
        $this->assign('metaTitle', $company['name']);
        $this->assign('isOpen',$isOpen);
        $this->assign('outset',$outset);
        $this->display();
    }

    /**
     * 订单信息确认
     */
    public function sureOrder() {
        $_SESSION['topaysuccessTips']=null;
        $tmpcid = intval($_POST['mycid']);
        if(empty($tmpcid)||$tmpcid != $this->_cid){
            $this->exitdisplay('提交信息出错');
        }
        $outset = $this->outManage($tmpcid);
        $dishModel=XD('Wap/Dish');
        $dishOrderModel=XD('Wap/DishOrder');
        $isOpen=$dishModel->isOpen(array(
            'open_times'=>$outset['open_times'],
            'startTime1'=>$outset['zc_sdate'],
            'endTime1'=>$outset['zc_edate'],
            'startTime2'=>$outset['wc_sdate'],
            'endTime2'=>$outset['wc_edate']
        ));
        if(!$isOpen){
            $this->error('商家休息中...');
        }
        $DishC = $this->getDishCompany($tmpcid); /*             * 餐厅设置信息* */
        $kconoff = $DishC['kconoff'];
        $Mcompany = $this->getDishMainCompany(false);
        $discount = $outset['discount'];
        $dishofcid = $tmpcid;
        if (($Mcompany['cid'] != $tmpcid) && ($Mcompany['dishsame'] == 1)) {
            $dishofcid = $Mcompany['cid']; /*                 * *开启分店统一主店 菜品功能** */
            $kconoff = $Mcompany['kconoff'];
            $discount = $Mcompany['discount'];
        }
        $company = $this->getCompany($tmpcid);
        if ($company && is_array($company)) {
            $_SESSION["session_shop_{$this->token}"] = $tmpcid;
        }
        $result=$dishOrderModel->createList(I('post.'),$dishofcid,array(
            'kconoff'=>$kconoff,//是否开启库存管理
            'discount'=>$discount>0?$discount/10:1,//会员折扣
            'isMember'=>$this->isMember,//是否是会员
            'meal_fee'=>$outset['meal_fee']/100,//餐盒费
        ));
        if(!$result){
            $this->error($dishOrderModel->getError());
        }
        //联系人信息
        $contact = false;
        if ($this->wecha_id) {
            $orderinfo = $dishOrderModel->where(array('token' => $this->token, 'cid' => $tmpcid, 'wecha_id' => $this->wecha_id))->order("paid DESC,id DESC ")->find();
            if (!empty($orderinfo)) {
                $contact['youname'] = $orderinfo['name'];
                $contact['yousex'] = $orderinfo['sex'];
                $contact['youtel'] = $orderinfo['tel'];
                $contact['youaddress'] = $orderinfo['address'];
            }
        }
        $this->assign('open_times',$dishModel->deliverySchedule($outset));
        $this->assign('meal_fee',$outset['meal_fee']/100);
        $this->assign('kconoff', $kconoff);
        $this->assign('contact', $contact);
        $my_distance = session($this->wecha_id . $this->token . $tmpcid . 'distance');
        $delivery_fee=$dishOrderModel->getDeliveryFee($my_distance,$outset);
        $pricing=$dishOrderModel->getPricing($my_distance,$outset);
        $this->assign('pricing',$pricing);
        $this->assign('delivery_fee', $delivery_fee);
        $this->assign('isMember', $this->isMember);
        $this->assign('discount', $discount);
        $this->assign('cid', $tmpcid);
        $this->assign('company', $company);
        $this->assign('metaTitle', $company['name']);
        $this->assign('_info',$result);
        $this->display();
    }

    /** 错误提醒页面**
     * *$returnurl true是将返回上一url
     * *也可制定一个跳转url
     * */
    private function exitdisplay($tips = "", $returnurl = false) {
        /* //保证输出不受静态缓存影响
          C('HTML_CACHE_ON', false);
          $this->assign('tips', $tips);
          $this->assign('returnurl', $returnurl);
          $this->display('exitdisplay'); */
        $this->error($tips, $returnurl);
        exit;
    }

    /** 获取餐厅配置信息* */
    private function getDishCompany($cid, $cache = true) {
        $DishC = $_SESSION["session_dish{$cid}_{$this->token}"];
        $DishC = !empty($DishC) ? unserialize($DishC) : false;
        if ($cache && !empty($DishC)) {
            return $DishC;
        } else {
            $DishC = M('Dish_company')->where(array('cid' => $cid))->find();
            if ($cache) {
                $_SESSION["session_dish{$cid}_{$this->token}"] = !empty($DishC) ? serialize($DishC) : '';
            }
            return $DishC;
        }
    }

    /** 获取公司信息* */
    private function getCompany($cid, $cache = true) {
        $this->mycompany = $_SESSION["session_shop{$cid}_{$this->token}"];
        $this->mycompany = !empty($this->mycompany) ? unserialize($this->mycompany) : false;
        if ($cache && !empty($this->mycompany)) {
            return $this->mycompany;
        } else {
            $company = M('Company')->where(array('token' => $this->token, 'id' => $cid))->find();
            if (empty($company) || !is_array($company)) {
                $this->redirect(U('DishOut/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id)));
            }
            if ($cache) {
                $this->mycompany = $company;
                $_SESSION["session_shop{$cid}_{$this->token}"] = !empty($company) ? serialize($company) : '';
            }
            return $company;
        }
    }

    /** 获取外卖设置信息* */
    private function outManage($cid) {
        if (!empty($this->shopmanage)) {
            return $this->shopmanage;
        } else {
            $outset = M('Dishout_manage')->where(array('token' => $this->token, 'cid' => $cid))->find();
            $tmp = M('Dish_company')->where(array('cid' => $cid))->find();
            if (is_array($tmp) && ($tmp['istakeaway'] == 1)) { /*             * 支持老用户不设置也能打开* */
                $no_outset = array('token' => $this->token, 'cid' => $cid, 'stype' => 1, 'pricing' => 0, 'sendtime' => 30, 'removing' => 5, 'permin' => 15, 'zc_sdate' => 0, 'zc_edate' => 0, 'wc_sdate' => 0, 'wc_edate' => 0, 'shopimg' => '', 'area' => '');
                $outset = empty($outset) ? $no_outset : $outset;
            }
            if (empty($outset) || !is_array($outset)) {
                $this->exitdisplay('抱歉！此商家还没有设置外卖管理相关信息');
            }
            $this->shopmanage = $outset;
            $_SESSION["manage_shop{$cid}_{$this->token}"] = !empty($outset) ? serialize($outset) : '';
            return $outset;
        }
    }

    /** 地图* */
    public function companyMap() {
        if (C('baidu_map')) {
            $isamap = 0;
        } else {
            $isamap = 1;
        }
        $this->apikey = C('baidu_map_api');
        $this->assign('apikey', $this->apikey);
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        $company = $this->getCompany($cid, false);
        $this->assign('thisCompany', $company);
        if (!$isamap) {
            $this->display();
        } else {
            $this->amap = new amap();
            $link = $this->amap->getPointMapLink($company['longitude'], $company['latitude'], $company['name']);
            header('Location:' . $link);
        }
    }

	public function checknew(){
	   $_SESSION['topaysuccessTips']=null;
	   echo 1;exit;
	}

    /**
     * 保存订餐人的信息到session
     */
    public function OrderPay() {
		$topaysuccessTips=isset($_SESSION['topaysuccessTips']) ? $_SESSION['topaysuccessTips'] :null;
		$topaysuccessTips=intval($topaysuccessTips);
		if(!IS_POST){
		  $this->error('对不起，非法请求，请重新点餐下单！');
		}
		if($topaysuccessTips>0){
			/***优化iphone微信返回按钮重新重复订单问题****/
			 $myorder = M('Dish_order')->where(array('id' => $topaysuccessTips, 'token' => $this->token))->find();
			 if(!empty($myorder) && ($myorder['paid']==0) &&($myorder['price']>0)){
				 $_SESSION['topaysuccessTips']=$myorder['id'];
				 $this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $myorder['wecha_id'], 'success' => 1, 'from' => 'DishOut', 'orderName' => $myorder['orderid'], 'single_orderid' => $myorder['orderid'], 'price' => $myorder['price'])));
				   exit();
			 }
			 $this->error('对不起，非法请求，请重新点餐下单！',U('DishOut/index', array('token' => $this->token)));
			 exit();
			 
		}
		$_SESSION['topaysuccessTips']=null;
        $shopid = intval($_POST['mycid']);
        $totalmoney = floatval(trim($_POST['totalmoney']));
        $totalnum = intval(trim($_POST['totalnum']));
        $totalMealFee=floatval(trim($_POST['totalMealFee']));
        $ouserName = htmlspecialchars(trim($_POST['ouserName']), ENT_QUOTES);
        $ouserSex = intval(trim($_POST['ouserSex']));
        $ouserTel = htmlspecialchars(trim($_POST['ouserTel']), ENT_QUOTES);
        $ouserAddres = htmlspecialchars(trim($_POST['ouserAddres']), ENT_QUOTES);
        $oarrivalTime = htmlspecialchars(trim($_POST['oarrivalTime']), ENT_QUOTES);
        $omark = htmlspecialchars(trim($_POST['omark']), ENT_QUOTES);
        $Mcompany = $this->getDishMainCompany(false);
        $dishofcid = $shopid;
        $outset = $this->outManage($shopid);
        $now_dishout_manage = M('dishout_manage')->where(array('token' => $this->token, 'cid' => $shopid))->find();
        $my_distance = session($this->wecha_id . $this->token . $shopid . 'distance');
        if ($now_dishout_manage['overflow_radius'] == 3 && $my_distance > $now_dishout_manage['removing']) {
            $this->error('对不起，您已经超出了本店送餐范围。');
            exit;
        }
        $kconoff=$outset['kconoff'];
        $discount = $outset['discount'];
        if (($Mcompany['cid'] != $shopid) && ($Mcompany['dishsame'] == 1)) {
            $dishofcid = $Mcompany['cid']; /*             * *开启分店统一主店 菜品功能** */
            $kconoff = $Mcompany['kconoff'];
            $discount=$Mcompany['discount'];
        }
        if ($shopid > 0) {
            $dishOrderModel=XD('Wap/DishOrder');
            $pricing=$dishOrderModel->getPricing($my_distance,$outset);
            $result=$dishOrderModel->createList(I('post.'),$dishofcid,array(
                'kconoff'=>$kconoff,//是否开启库存管理
                'discount'=>$discount>0?$discount/10:1,//会员折扣
                'isMember'=>$this->isMember,//是否是会员
                'meal_fee'=>$outset['meal_fee']/100,//餐盒费
            ));
            if(!$result){
                $this->error($dishOrderModel->getError());
            }
            $delivery_fee=$dishOrderModel->getDeliveryFee($my_distance,$outset);
            $mealFee=$result['totalMealFee'];
            $netPrice=round($result['totalPrice']-$result['totalMealFee'],2);
            if($pricing>$netPrice){
                $this->error('您的订单尚未达到起送价');
            }
            $jumpurl = U('DishOut/dishMenu', array('token' => $this->token, 'cid' => $shopid, 'wecha_id' => $this->wecha_id));
            if($totalmoney!=$netPrice||$totalnum!=$result['totalNum']||$totalMealFee!=$mealFee){
                $this->error('订单错误，请重新提交',$jumpurl);
            }
            if (empty($ouserName) || empty($ouserTel) || empty($ouserAddres)) {
                $this->exitdisplay('订单中相关联系地址：姓名或联系电话或送货地址有没写的', $jumpurl);
            }
            if (preg_match('/\-\d{2}\s\d{2}\:/', $oarrivalTime)) { /**             * *如果选择了明日的时间 是带上日期的** */
                $oarrivalTime = strtotime($oarrivalTime);
            } else {
                $oarrivalTime = $oarrivalTime ? strtotime(date('Y-m-d ') . $oarrivalTime) : 0;
            }
            $tmparr=array();
            foreach ($result['list'] as $item){
                unset($item['last_inventory']);
                unset($item['m_sales']);
                $tmparr[]=$item;
            }
            $tmpsubmoney=round($result['totalPrice']+$delivery_fee,2);//配送费+菜品费+餐盒费
            $wecha_id = $this->wecha_id ? $this->wecha_id : 'DishOutm_' . $ouserTel;
            $orderid = substr($wecha_id, -5) . date("YmdHis");
            $Orderarr = array('cid' => $shopid, 'wecha_id' => $wecha_id, 'token' => $this->token, 'total' => $result['totalNum'],
                'price' => $tmpsubmoney, 'nums' => 1,
                'info' => serialize($tmparr), 'name' => $ouserName,
                'sex' => $ouserSex, 'tel' => $ouserTel,
                'address' => $ouserAddres, 'tableid' => 0,
                'time' => time(), 'reservetime' => $oarrivalTime,
                'stype' => $outset['stype'], 'paid' => 0, 'isuse' => 0,
                'orderid' => $orderid, 'printed' => 0,
                'des' => $omark, 'takeaway' => 1,
                'comefrom' => 'dishout',
                'delivery_fee' => $delivery_fee * 100,
                'meal_fee'=>$mealFee*100,
                'status'=>'0',
                'paid'=>'0',
                'date'=>date('Y-m-d'),
                'offer_list'=>$result['offerList']?json_encode($result['offerList']):''
            );
           $orid = M('Dish_order')->add($Orderarr);
            if ($orid) {
                $dishOrderModel->orderConsume($orid,$result['list']);//锁定库存
                $_SESSION[$this->session_dish_info] = '';
                //TODO 短信提示
                $company = $this->getCompany($shopid);
                /* Sms::sendSms($this->token, "顾客{$ouserName}刚刚叫了一份外卖，订单号：{$orderid}，请您注意查看并处理", $company['mp']); */
                //给商家发站内信
                $params = array();
                $params['site'] = array('token' => $this->token, 'from' => '微外卖消息', 'content' => "顾客{$ouserName}刚刚叫了一份外卖，订单号：{$orderid}，请您注意查看并处理");
                MessageFactory::method($params, 'SiteMessage');
//                 $printer_set = $this->getPrinter_set($shopid);
//                 if (!empty($printer_set) && ($printer_set['paid'] == 0)) {
                $op = new orderPrint();
                $msg = array('companyname' => $company['name'], 'des' => trim($_POST['omark']),
                    'companytel' => $company['tel'], 'truename' => trim($_POST['ouserName']),
                    'tel' => trim($_POST['ouserTel']), 'address' => trim($_POST['ouserAddres']),
                    'buytime' => $Orderarr['time'], 'orderid' => $Orderarr['orderid'],
                    'sendtime' => $oarrivalTime > 0 ? $oarrivalTime : '尽快送达', 'price' => $Orderarr['price'],
                    'total' => $Orderarr['total'], 'typename' => '外卖',
                    'list' => $tmparr);
                $msg = ArrayToStr::array_to_str($msg, 0);
                $op->printit($this->token, $shopid, 'DishOut', $msg, 0);

                /* 厨房打印菜单 */
                if ($kitchens_list = D('Dish_kitchen')->where(array('cid' => $this->_cid))->select()) {
                    $t_list = array();
                    foreach ($tmparr as $dish)
                        $t_list[$dish['kitchen_id']][] = $dish;

                    $kitchens = array();
                    foreach ($kitchens_list as $kit_row)
                        $kitchens[$kit_row['id']] = $kit_row;

                    $print_msg = array('des' => trim($_POST['omark']), 'truename' => trim($_POST['ouserName']), 'tel' => trim($_POST['ouserTel']), 'address' => trim($_POST['ouserAddres']), 'buytime' => $Orderarr['time'], 'orderid' => $Orderarr['orderid'], 'sendtime' => $oarrivalTime > 0 ? $oarrivalTime : '尽快送达','list'=>array());
                    foreach ($t_list as $k => $rowset) {
                        if ($k) {
                            if (isset($kitchens[$k]) && $kitchens[$k]['status']) {
                                for ($i = 0; $i < count($rowset); $i++) {
                                    $msg = $print_msg;
									$msg['kitchenname']=$kitchens[$k]['name'];
                                    $msg['list'][] = $rowset[$i];
                                    $msg = ArrayToStr::array_to_str($msg, 0);
                                    $op->printit($this->token, $this->_cid, 'DishOut', $msg, 0, '', $k);
                                }
                            } else {
                                $msg = $print_msg;
								$msg['kitchenname']=$kitchens[$k]['name'];
                                $msg['list'] = $rowset;
                                $msg = ArrayToStr::array_to_str($msg, 0);
                                $op->printit($this->token, $this->_cid, 'DishOut', $msg, 0, '', $k);
                            }
                        }
                    }
                }
                /* 厨房打印菜单 */

//                 }
                $alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();

                if ($alipayConfig['open']) {
					$_SESSION['topaysuccessTips']=$orid;
                    $this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $wecha_id, 'success' => 1, 'from' => 'DishOut', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $tmpsubmoney)));
                } /* elseif ($this->fans['balance']) {
                  $this->success('正在提交中...', U('CardPay/pay',array('token' => $this->token, 'wecha_id' => $wecha_id, 'success' => 1, 'from'=> 'DishOut', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $tmpsubmoney)));
                  } */ else {
                    $this->exitdisplay('商家尚未开启支付功能', $jumpurl);
                }
            } else {
                $this->exitdisplay('订单录入系统出错，抱歉给您的带来了不便。请重新下单吧', $jumpurl);
            }
            if (!empty($this->wecha_id)) {
                /* 保存个人信息 */
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
        } else {
            $jumpurl = U('DishOut/index', array('token' => $this->token));
            $this->exitdisplay('订单信息中店面信息出错', $jumpurl);
        }
    }

    /**
     * 订单支付
     */
    public function OrderPayAgain() {
        $orid = $this->_get('orid') ? intval($this->_get('orid', "trim")) : 0;
        $cid = $this->_get('cid') ? intval($this->_get('cid', "trim")) : 0;
        if ($orid > 0 && ($cid > 0)) {
            $Dish_order = XD('Wap/DishOrder');
            $myorder = $Dish_order->where(array('id' => $orid, 'token' => $this->token, 'cid' => $cid))->find();
            if ($myorder) {
                $dishArr = unserialize($myorder['info']);
                $Mcompany = $this->getDishMainCompany(false);
                $DishC = $this->getDishCompany($cid);
                $kconoff = $DishC['kconoff'];
                if (($Mcompany['cid'] != $cid) && ($Mcompany['dishsame'] == 1)) {
                    $kconoff = $Mcompany['kconoff'];
                }
                //已经到付或者店付则不检查库存
                if(!in_array($myorder['paytype'],array('daofu','dianfu'))&&!$Dish_order->checkList($dishArr,null,array('kconoff'=>$kconoff,'order_id'=>$orid))){
                    $this->error($Dish_order->getError());
                }
                $price = $myorder['price'] - $myorder['havepaid'];
                $alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
                if ($alipayConfig['open']) {
                    $this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $myorder['wecha_id'], 'success' => 1, 'from' => 'DishOut', 'orderName' => $myorder['orderid'], 'single_orderid' => $myorder['orderid'], 'price' => $price)));
                    exit();
                } else {
                    $this->error('商家尚未开启支付功能');
                }
            }
        }
        $this->error('订单信息出错！');
    }

    /**
     * 我的订单记录
     */
    public function myOrder() {
        $this->_cid = $this->_cid > 0 ? $this->_cid : (isset($_GET['cid']) ? intval($_GET['cid']) : 0);
        $_SESSION["session_shop_{$this->token}"] = $this->_cid;
        $where = array('wecha_id' => $this->wecha_id, 'token' => $this->token, 'cid' => $this->_cid, 'isdel' => "0", 'comefrom' => 'dishout');
        $dish_order = M('Dish_order')->where($where)->order('id DESC')->limit(7)->select(); //只查询最近7条记录
        $list = array();
        foreach ($dish_order as $row) {
            $row['info'] = unserialize($row['info']);
            $row['paytypestr']=enum_list('pay_type',strtolower($row['paytype']),'其他');
            if (!$row['paid'] && ($row['paytype'] != 'daofu') && ($row['paytype'] != 'dianfu')) {
                $row['paystatus'] = '未付款';
            } else {
                $row['paystatus'] = '';
            }
            $list[] = $row;
        }

        $company = $this->getCompany($this->_cid);
        $this->assign('company', $company);
        $this->assign('cid', $this->_cid);
        foreach ($list as $lk => &$lv) {
            if ($lv['deliverymanid'] > 0) {
                $list[$lk]['deliveryman'] = M('dishout_deliveryman')->where(array('token' => $this->token, 'cid' => $lv['cid'], 'id' => $lv['deliverymanid']))->find();
            }
            $lv['offer_list']=json_decode($lv['offer_list'],true);
        }
        $this->assign('orderList', $list);
        $this->assign('today', strtotime(date('Y-m-d') . ' 00:00:00'));
        $this->assign('metaTitle', '我的订单');
        $this->display();
    }

    public function myorder_yes() {
        M('dish_order')->where(array('token' => $this->token, 'cid' => (int) $_GET['cid'], 'id' => (int) $_GET['id']))->save(array('isyes' => 1));
        $this->success('确认收货成功', U('DishOut/myOrder', array('token' => $this->token, 'cid' => $_GET['cid'])));
    }

    /**
     * 支付成功后的回调函数
     */
    public function payReturn() {
        $orderid = trim($_GET['orderid']);

		if (isset($_GET["nohandle"])) {
			$order = M("dish_order")->where(array("orderid" => $orderid, "token" => $this->token))->find();
			$this->redirect(U("DishOut/myOrder", array("token" => $this->token, "wecha_id" => $this->wecha_id, "cid" => $order["cid"])));
		}

		$order = M("dish_order")->where(array("orderid" => $orderid, "token" => $this->token))->find();
		ThirdPayDishOut::index($order);
    }

    //取消订单
    public function cannelOrder()
    {
        $id=I('post.oid');
        $order=XD('Wap/DishOrder');
        $num=$order->cancel($this->_cid,$id);
        if(empty($num))
            $this->error($order->getError());
        $this->success('取消订单成功');
    }

}

?>