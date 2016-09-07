<?php

/**
 * **外卖后台处理控制器
 * **LiHongShun
 * **2015-01-10
 * */
class DishOutAction extends UserAction {

    public $_cid = 0;
    protected $company;
    protected $shop_id;

    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction('DishOut');
        $this->_cid = session('companyid') > 0 ? session('companyid') : intval($_GET['cid']);
        $this->_cid = $this->_cid > 0 ? $this->_cid : 0;
        if (empty($this->token)) {
            $this->error('不合法的操作', U('Index/index'));
        }
        if (empty($this->_cid)) {
            $company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
            $this->company=$company;
            if ($company) {
                $this->_cid = $company['id'];
                //主店的k存session
                session('companyk', md5($this->_cid . session('uname')));
            } else {
                $this->error('您还没有添加您的商家信息', U('Company/index', array('token' => $this->token)));
            }
        } else {
            $k = session('companyk');
            $company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
            $this->company=$company;
            if (empty($company)) {
                $this->error('非法操作', U('Repast/index', array('token' => $this->token)));
            } else {
                $username = $company['isbranch'] ? $company['username'] : session('uname');
                if (md5($this->_cid . $username) != $k) {
                    $this->error('非法操作', U('Repast/index', array('token' => $this->token)));
                }
            }
        }
        if($this->company['isbranch']!='1'){
            $shop_id=isset($_REQUEST['shop_id'])?$_REQUEST['shop_id']:'';
            if($shop_id!=''){
                $branch=M('company')->where(array('token'=>$this->token,'id'=>I('get.shop_id')))->field('id')->find();
                if(empty($branch)){
                    $this->error('门店不存在');
                }
            }
            $companyList=M('company')->where(array('token'=>$this->token))->order('isbranch asc')->field('id,name,isbranch')->select();
            $this->assign('company_list',$companyList);
        }else{
            $shop_id=$this->_cid;
        }
        $this->shop_id=$shop_id;
        $this->assign('ischild', session('companyLogin'));
        $this->assign('cid', $this->_cid);
        $this->assign('shop_id',$shop_id);
    }

    /**
     * 外卖菜的列表
     */
    public function index() {
        $data = M('Dish');
        $where = array('cid' => $this->_cid, 'istakeout' => 1);
        $count = $data->where($where)->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $dish = $data->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $list = $sortList = array();
        $sort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();
        foreach ($sort as $row) {
            $sortList[$row['id']] = $row;
        }
        foreach ($dish as $r) {
            $r['sortName'] = isset($sortList[$r['sid']]['name']) ? $sortList[$r['sid']]['name'] : '';
            $list[] = $r;
        }
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 对菜品的操作
     */
    public function dishedit() {
        $dataBase = D('Dish');
        $dish_sort = M('Dish_sort');
        $dishSkuModel=XD('User/DishSku');
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $_POST['ishot'] = isset($_POST['ishot']) ? intval($_POST['ishot']) : 0;
            $_POST['isopen'] = isset($_POST['isopen']) ? intval($_POST['isopen']) : 0;
            $_POST['istakeout'] = isset($_POST['istakeout']) ? intval($_POST['istakeout']) : 0;
			$_POST['isdiscount'] = isset($_POST['isdiscount']) ? intval($_POST['isdiscount']) : 0;
            $spec=I('post.spec');
            if(!empty($spec)){
                $specInfo=M('dish_spec')->where(array('id'=>$spec,'_string'=>"token='' OR token='{$this->token}'"))->find();
                if(empty($specInfo))
                    $this->error('规格类型不存在');
                $opts=explode(',',$specInfo['opts']);
                $obj=$dishSkuModel->checkSku($opts,I('post.sku'));
                if($obj===false){
                    $this->error($dishSkuModel->getError());
                }
                $_POST['price']=$obj['price'];
                $_POST['instock']=$obj['instock'];
                $_POST['refreshstock']=0;
            }
            if ($id) {//edit
                if ($dataBase->create() !== false) {
                    $temp = M('Dish')->where(array('cid' => $this->_cid, 'id' => $id))->find();
                    $action = $dataBase->save();
                    if($action){
                        if(!empty($spec)&&!empty($info['spec'])){
                            $dishSkuModel->updateSku($id,$opts,I('post.sku'));
                        }elseif(!empty($spec)&&empty($info['spec'])){
                            $dishSkuModel->addSku($id,$opts,I('post.sku'));
                        }elseif(!empty($info['spec'])&&empty($spec)){
                            $dishSkuModel->delSku($id);
                        }
                    }
                    if ($action != false) {
                        if ($temp['sid'] != $_POST['sid']) {
                            $dish_sort->where(array('id' => $_POST['sid'], 'cid' => $this->_cid))->setInc('num', 1);
                            $dish_sort->where(array('id' => $temp['sid'], 'cid' => $this->_cid))->setDec('num', 1);
                        }
                        $this->success('修改成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->success('修改成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
                        //$this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            } else {
                $this->error('操作失败');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $dishSort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();
            if (empty($dishSort)) {
                $this->redirect(U('Repast/sortadd', array('token' => $this->token, 'cid' => $this->_cid)));
            }
            $spec=M('dish_spec');
            $where['_string']="token='' OR token='{$this->token}'";
            $list=$spec->where($where)->order('create_time desc')->select();
            $this->assign('spec_list',$list);
            $findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
            if(!empty($findData)&&!empty($findData['spec'])){
                $sku=$dishSkuModel->getSku($id,$findData['spec']);
                $this->assign('sku_json',json_encode($sku));
            }
            $this->assign('tableData', $findData);
            $this->assign('dishSort', $dishSort);
            $this->display();
        }
    }

    /**
     * 外送时间管理
     */
    public function manageTime() {
        $db_dotime = M('dishout_manage');
        if (IS_POST) {
            $times=json_decode($_POST['open_times'],true);
            $openTimes=array();
            foreach ($times as $key=>$value){
                $start=preg_replace('/\s/','',$value['start']['time']);
                $end=preg_replace('/\s/','',$value['end']['time']);
                if(str_replace(':','',$start)<=str_replace(':','',$end)){
                    $openTimes[]=$value;
                }
            }
            $openTimes=$openTimes?$openTimes:array(array('start'=>'00:00','end'=>'23:59'));
            $permin = intval($this->_post("permin", 'trim'));
            $removing = $this->_post("removing", 'trim');
            $area = htmlspecialchars($this->_post("area", 'trim'), ENT_QUOTES);
            $sendtime = intval($this->_post("sendtime", 'trim'));
            $simage = $this->_post("simage");
            $tourl = $this->_post("tourl");
            $simage = array('img' => $simage, 'tourl' => $tourl);
            $tid = intval($this->_post("tid", 'trim'));
            $data['permin'] = ($permin > 0) ? $permin : 15;
            if ($data['permin'] < 5 || $data['permin'] > 60) {
                $this->error('请将外卖送达时间间隔值设置在5-60范围内!');
            }
            $data['removing'] = $removing < 250 ? $removing : 250;
            $data['sendtime'] = $sendtime < 250 ? $sendtime : 250;
            $data['area'] = $area;
            $data['shopimg'] = serialize($simage);
            $data['overflow_radius'] =  isset($_POST['overflow_radius']) ?  intval($_POST['overflow_radius']) :1;
            $data['priceup'] = isset($_POST['priceup']) ? intval(($_POST['priceup']*100)) :0;
            $data['delivery_fee'] = intval(($_POST['delivery_fee']*100));
            $data['meal_fee'] = intval(($_POST['meal_fee']*100));
            $data['discount'] = round(($_POST['discount']),1);
            $data['announcement']=I('post.announcement');
            $data['open_times']=json_encode($openTimes);
            if ($tid > 0) {//edit
                $action = $db_dotime->where(array('id' => $tid, 'cid' => $this->_cid, 'token' => $this->token))->save($data);
                //if ($action != false) {
                $this->success('修改成功', U('DishOut/manageTime', array('token' => $this->token, 'cid' => $this->_cid)));
                //}
            } else {//add
                $data['cid'] = $this->_cid;
                $data['token'] = $this->token;
                $action = $db_dotime->add($data);
                if ($action != false) {
                    $this->success('保存成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
                } else {
                    $this->error('保存失败', U('DishOut/manageTime', array('token' => $this->token, 'cid' => $this->_cid)));
                }
            }
        } else {
            $tmp = $db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
            $shopimg = unserialize($tmp['shopimg']);
            unset($tmp['shopimg']);
            //兼容旧版
            if(empty($tmp['open_times'])){
                $times=array();
                if(!empty($tmp['zc_sdate'])||!empty($tmp['zc_edate'])){
                    $zc_sdate=$tmp['zc_sdate']?date('H:i',$tmp['zc_sdate']):'00:00';
                    $zc_edate=$tmp['zc_edate']?date('H:i',$tmp['zc_edate']):'23:59';
                    $times[]=array('start'=>$zc_sdate,'end'=>$zc_edate);
                }
                if(!empty($tmp['wc_sdate'])||!empty($tmp['wc_edate'])){
                    $wc_sdate=$tmp['wc_sdate']?date('H:i',$tmp['wc_sdate']):'00:00';
                    $wc_edate=$tmp['wc_edate']?date('H:i',$tmp['wc_edate']):'23:59';
                    $times[]=array('start'=>$wc_sdate,'end'=>$wc_edate);
                }
                $times=$times?$times:array(array('start'=>'00:00','end'=>'23:59'));
                $tmp['open_times']=json_encode($times);
            }
            $this->assign('mtime', $tmp);
            $this->assign('simage', $shopimg);
            $this->display();
        }
    }

    /**
     * 外卖起送管理
     */
    public function basePrice() {
        $db_dotime = M('dishout_manage');
        if (IS_POST) {
            $stype = intval($this->_post("stype", 'trim'));
            $pricing = intval($this->_post("pricing", 'trim'));
            $tid = intval($this->_post("tid", 'trim'));
            $data['stype'] = $stype > 0 ? $stype : 1;
            $data['pricing'] = $pricing > 0 ? $pricing : 0;
            if ($tid > 0) {//edit
                $action = $db_dotime->where(array('id' => $tid, 'cid' => $this->_cid, 'token' => $this->token))->save($data);
                //if ($action != false) {
                $this->success('修改成功', U('DishOut/basePrice', array('token' => $this->token, 'cid' => $this->_cid)));
                //}
            } else {//add
                $data['cid'] = $this->_cid;
                $data['token'] = $this->token;
                $action = $db_dotime->add($data);
                if ($action != false) {
                    $this->success('保存成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
                } else {
                    $this->error('保存失败', U('DishOut/basePrice', array('token' => $this->token, 'cid' => $this->_cid)));
                }
            }
        } else {
            $tmp = $db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
            $this->assign('mtime', $tmp);
            $this->display();
        }
    }

    /**
     * 自定义回复设置
     */
    public function myReply() {
        $db_dotime = M('dishout_manage');
        if (IS_POST) {
            $data['keyword'] = htmlspecialchars($this->_post("keyword", 'trim'), ENT_QUOTES);
            $data['rtitle'] = htmlspecialchars($this->_post("rtitle", 'trim'), ENT_QUOTES);
            $data['rinfo'] = htmlspecialchars($this->_post("rinfo", 'trim'), ENT_QUOTES);
            $data['picurl'] = htmlspecialchars($this->_post("picurl", 'trim'), ENT_QUOTES);
            $tid = intval($this->_post("tid", 'trim'));
            if ($tid > 0) {//edit
                $action = $db_dotime->where(array('id' => $tid, 'cid' => $this->_cid, 'token' => $this->token))->save($data);
                $this->handleKeyword($tid, 'DishOut', $data['keyword']);
                //if ($action != false) {
                $this->success('修改成功', U('DishOut/myReply', array('token' => $this->token, 'cid' => $this->_cid)));
                //}
            } else {//add
                $data['cid'] = $this->_cid;
                $data['token'] = $this->token;
                $insert_id = $db_dotime->add($data);
                if ($insert_id > 0) {
                    $this->handleKeyword($insert_id, 'DishOut', $data['keyword']);
                    $this->success('保存成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
                } else {
                    $this->error('保存失败', U('DishOut/myReply', array('token' => $this->token, 'cid' => $this->_cid)));
                }
            }
        } else {
            $tmp = $db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
            if (is_array($tmp) && !empty($tmp)) {
                $tmp['keyword'] = htmlspecialchars_decode($tmp['keyword'], ENT_QUOTES);
                $tmp['rtitle'] = htmlspecialchars_decode($tmp['rtitle'], ENT_QUOTES);
                $tmp['rinfo'] = htmlspecialchars_decode($tmp['rinfo'], ENT_QUOTES);
                $tmp['picurl'] = htmlspecialchars_decode($tmp['picurl'], ENT_QUOTES);
            } else {
                $tmp['keyword'] = '外卖';
                $tmp['rtitle'] = '微信外卖';
                $tmp['rinfo'] = '';
                $tmp['picurl'] = $this->staticPath . '/tpl/static/dishout/image/wxdishout.jpg';
            }
            $this->assign('mtime', $tmp);
            $this->display();
        }
    }

    /**
     * 订单列表
     */
    public function orders(){
        $_GET=array_merge(array('create_time'=>'desc', 'date' => date('Y-m-d')),$_GET);
        $_GET['cid']=$this->shop_id;
        $_GET['token']=$this->token;
        $dishOrderModel=XD('DishOrder');
        $total=$dishOrderModel->getTotal(I('get.'));
        $page       = new Page($total,20);
        $orders=$dishOrderModel->getList(I('get.'),$page->firstRow,$page->listRows);
        $this->assign('orders',$orders);
        $this->assign('page',$page->show());
        $this->display();
    }

    /*     * **************
     * *保存菜品销售情况
     * *Dishout_salelog表
     * ************** */

    public function saleLog($data) {
        $log_db = M('Dishout_salelog');
        $tmplog = $log_db->where(array('order_id' => $data['oid']))->find();
        if (!empty($tmplog)) {
            return false;
        }
        $Dishcompany = M('Dish_company')->where(array('cid' => $data['cid']))->find();
        $kconoff = $Dishcompany['kconoff'];
        unset($Dishcompany);
        $tmparr = array('token' => $this->token, 'cid' => $data['cid'], 'order_id' => $data['oid'], 'paytype' => $data['paytype']);
        $mDishSet = $this->getDishMainCompany($this->token);
        if (!empty($data['dish'])) {
            $DishDb = M('Dish');
            foreach ($data['dish'] as $kk => $vv) {
                $did = isset($vv['did']) ? $vv['did'] : $kk;
                $dishofcid = $data['cid'];
                if (($mDishSet['cid'] != $data['cid']) && ($mDishSet['dishsame'] == 1)) {
                    $dishofcid = $mDishSet['cid'];
                    $kconoff = $mDishSet['kconoff'];
                }
                $tmpdish = $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->find();
                if ($kconoff && !empty($tmpdish) && ($tmpdish['instock'] > 0)) {
                    $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->setDec('instock', $vv['num']);
                }
                $logarr = array(
                    'did' => $did, 'nums' => $vv['num'],
                    'unitprice' => $vv['price'], 'money' => $vv['num'] * $vv['price'], 'dname' => $vv['name'],
                    'addtime' => $data['time'], 'addtimestr' => date('Y-m-d H:i:s', $data['time']), 'comefrom' => 0
                );
                $savelogarr = array_merge($tmparr, $logarr);
                $log_db->add($savelogarr);
            }
        }
    }

    /** 获取主餐厅配置信息* */
    private function getDishMainCompany($token) {
        $MainC = M('Company')->where(array('token' => $token, 'isbranch' => 0))->find();
        $m_cid = $MainC['id'];

        unset($MainC);
        $mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
        unset($m_cid);
        return $mDishC;
    }

    /**
     * 订单详情
     */
    public function orderInfo() {
		$cid = $this->shop_id;
        $id = $this->_get('id') ? intval($this->_get('id', 'trim')) : 0;
        $dishOrder = M('Dish_order');
        $thisOrder = $dishOrder->where(array('id' => $id, 'cid' => $cid, 'token' => $this->token, 'comefrom' => 'dishout'))->find();
		if ($thisOrder) {
            if (IS_POST) {
                exit();
                $isyes = isset($_POST['isyes']) ? intval($_POST['isyes']) : 0;
                $deliverymanid = $_POST['deliverymanid'];
                $paid = isset($_POST['paid']) ? intval($_POST['paid']) : 0;
                $isuse = isset($_POST['isuse']) ? intval($_POST['isuse']) : 0; /*                 * 是否发货* */
                $dishOrder->where(array('id' => $thisOrder['id']))->save(array('paid' => $paid, 'isuse' => $isuse,'isyes'=>$isyes,'deliverymanid'=>$deliverymanid));
                $company = M('Company')->where(array('token' => $this->token, 'id' => $thisOrder['cid']))->find();

                if ($paid) {
                    $temp = unserialize($thisOrder['info']);
                    $this->saleLog(array('cid' => $cid, 'oid' => $thisOrder['id'], 'paytype' => $thisOrder['paytype'], 'dish' => $temp, 'time' => $thisOrder['time']));
                    /*$op = new orderPrint();
                    $msg = array('companyname' => $company['name'], 'des' => htmlspecialchars_decode($thisOrder['des'], ENT_QUOTES),
                        'companytel' => $company['tel'], 'truename' => htmlspecialchars_decode($thisOrder['name'], ENT_QUOTES),
                        'tel' => $thisOrder['tel'], 'address' => htmlspecialchars_decode($thisOrder['address'], ENT_QUOTES),
                        'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'],
                        'sendtime' => $thisOrder['reservetime'] > 0 ? $thisOrder['reservetime'] : '尽快送达', 'price' => $thisOrder['price'],
                        'total' => $thisOrder['total'], 'typename' => '外卖',
                        'ptype' => $thisOrder['paytype'], 'list' => $temp);
                    $msg = ArrayToStr::array_to_str($msg, 1);
                    $op->printit($this->token, $this->_cid, 'DishOut', $msg, 1);*/
                }
                if ($isuse) {  //此处就注释掉了 工单编号: 17478 的修改 减少打印次数 省纸
                    /*Sms::sendSms($this->token, "{$company['name']}欢迎您，您的外卖订单号为：{$thisOrder['orderid']}的订单已经从本店出发送货了！", $thisOrder['tel']);*/
                    $model = new templateNews();
                    $model->sendTempMsg('OPENTM202521011', array('href' => $this->siteUrl . U('Wap/DishOut/myOrder', array('token' => $this->token, 'wecha_id' => $thisOrder['wecha_id'], 'cid' => $thisOrder['cid'])), 'wecha_id' => $thisOrder['wecha_id'], 'first' => '外卖送菜提醒', 'keyword1' => $thisOrder['orderid'], 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '您订的外卖已送出，请注意接收！'));
                }
                $this->success('修改成功', U('DishOut/orderInfo', array('token' => session('token'), 'id' => $thisOrder['id'])));
            } else {
                $dishList = unserialize($thisOrder['info']);
                $coupon = D('Coupon_pay_record')->where(array('orderid' => $thisOrder['orderid'], 'token' => $this->token, 'wechat_id' => $thisOrder['wecha_id'], 'from' => 'DishOut', 'dateline' => array('gt', 0)))->find();
                $thisOrder['reduce_cost'] = 0;
                if ($coupon) {
                    $thisOrder['reduce_cost'] = $coupon['reduce_cost'];
                }
                $thisOrder['offer_list']=json_decode($thisOrder['offer_list'],true);
                if(!empty($thisOrder['deliverymanid'])){
                    $thisOrder['deliveryman']=M('DishoutDeliveryman')->where(array('id'=>$thisOrder['deliverymanid']))->find();
                }
                $this->assign('thisOrder', $thisOrder);
                $this->assign('dishList', $dishList);
                $this->display();
            }
        }else {
			$this->error('订单不存在');
		}
    }

    /**
     * 打印订单
     */
    public function toPrint() {
        $id = $this->_post('oid') ? intval($this->_post('oid', 'trim')) : 0;
        $dishOrder = M('Dish_order');
        if ($thisOrder = $dishOrder->where(array('id' => $id, 'cid' => $this->_cid, 'token' => $this->token, 'comefrom' => 'dishout'))->find()) {
            $company = M('Company')->where(array('token' => $this->token, 'id' => $thisOrder['cid']))->find();
            $temp = unserialize($thisOrder['info']);
            $op = new orderPrint();

            $msg = array('companyname' => $company['name'], 'des' => htmlspecialchars_decode($thisOrder['des'], ENT_QUOTES),
                'companytel' => $company['tel'], 'truename' => htmlspecialchars_decode($thisOrder['name'], ENT_QUOTES),
                'tel' => $thisOrder['tel'], 'address' => htmlspecialchars_decode($thisOrder['address'], ENT_QUOTES),
                'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'],
                'sendtime' => $thisOrder['reservetime'], 'price' => $thisOrder['price'],
                'total' => $thisOrder['total'], 'typename' => '外卖',
                'list' => $temp);
            $msg = ArrayToStr::array_to_str($msg, $thisOrder['paid']);
            $op->printit($this->token, $this->_cid, 'DishOut', $msg, 1);
            echo json_encode(array('error' => 0));
        }
    }

    /**
     * 删除订单
     */
    public function deleteOrder() {
        $order=XD('DishOrder');
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $num=$order->del($this->shop_id,$id);
        if(empty($num))
            $this->error('关闭订单失败');
        $this->success('关闭订单成功');
    }

    /**
     * 菜品统计
     */
    public function Statistics() {
        $starttime = $this->_get('stime', 'trim');
        $starttime = !empty($starttime) ? strtotime($starttime) : 0;
        $endtime = $this->_get('etime', 'trim');
        $endtime = !empty($endtime) ? strtotime($endtime) : 0;
        $starttime = $starttime > 0 ? $starttime : strtotime(date('Y-m-d') . '00:00:00'); /*         * 默认统计今天的* */
        $endtime = $endtime > 0 ? $endtime : strtotime(date('Y-m-d H:i:s')); /*         * 默认统计今天的* */
        //$salelog = M('Dishout_salelog');
        $Model = new Model();
        $sqlstr = "select *,sum(money) as tmoney,sum(nums) as tnums from " . C('DB_PREFIX') . "dishout_salelog where comefrom='0' AND cid=" . $this->_cid . " AND token='" . $this->token . "' AND addtime>=" . $starttime . " AND addtime<=" . $endtime . " group by did";
        $tmp = $Model->query($sqlstr);
        $caiarr = array();
        $numsarr = array();
        $moneyarr = array();
        $tnums = 0;
        $tmoney = 0;
        if (!empty($tmp)) {
            foreach ($tmp as $kk => $vv) {
                $caiarr[] = "'" . $vv['dname'] . "'";
                $numsarr[] = $vv['tnums'];
                $tnums+=$vv['tnums'];
                $moneyarr[] = $vv['tmoney'];
                $tmoney+=$vv['tmoney'];
            }
        } else {
            $this->assign('nodata', true);
        }
        if (!empty($caiarr)) {
            $caistr = implode(',', $caiarr);
        } else {
            $caistr = "";
        }
        if (!empty($numsarr)) {
            $numsstr = implode(',', $numsarr);
        } else {
            $numsstr = '';
        }
        if (!empty($moneyarr)) {
            $moneystr = implode(',', $moneyarr);
        } else {
            $moneystr = '';
        }
        $this->assign('stime', date('Y-m-d H:i:s', $starttime));
        $this->assign('etime', date('Y-m-d H:i:s', $endtime));
        $this->assign('caistr', $caistr);
        $this->assign('numsstr', $numsstr);
        $this->assign('moneystr', $moneystr);
        $this->assign('tnums', $tnums);
        $this->assign('tmoney', $tmoney);
        $this->display();
    }
    
    public function deliveryman(){
        $where['token'] = $this->token;
        $where['cid'] = $this->_cid;
        $count = M('dishout_deliveryman')->where($where)->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $list = M('dishout_deliveryman')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $wgl=M('wechat_group_list');
        foreach ($list as $key=>&$value){
            $value['weixin']=$wgl->where(array('token'=>$value['token'],'openid'=>$value['openid']))->find();
        }
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }


    public function deliveryman_set(){
        $id = $_GET['id'];
        $tableData = M('dishout_deliveryman')->where(array('token'=>$this->token,'id'=>$id))->find();
        if(IS_POST){
            if(empty($_POST['name'])){
                $this->error('姓名不能为空');
            }
            if(empty($_POST['tel'])){
                $this->error('手机号不能为空');
            }elseif(!$tableData||$tableData['tel']!=I('post.tel')){
                $num=M('dishout_deliveryman')->where(array('tel'=>I('post.tel')))->count();
                if($num){
                    $this->error('手机号已经存在');
                }
            }
            if(!validate_regex($_POST['tel'],'mobile')){
                $this->error('手机号格式不正确');
            }
            if(empty($_POST['openid'])){
                $this->error('微信openid不能为空');
            }elseif(!$tableData||$tableData['openid']!=I('post.openid')){
                $num=M('dishout_deliveryman')->where(array('openid'=>I('post.openid')))->count();
                if($num){
                    $this->error('微信号已被其他送餐员绑定');
                }
                $wgl=M('wechat_group_list');
                $wx=$wgl->where(array('token'=>$this->token,'openid'=>I('post.openid')))->find();
                if(!$wx){
                    $this->error('微信openid不正确');
                }
                $_POST['avatar']=!empty($_POST['avatar'])?$_POST['avatar']:$wx['headimgurl'];
            }
            $data=array('name'=>I('post.name'),'tel'=>I('post.tel'));
            $data['openid']=I('post.openid');
            $data['avatar']=I('post.avatar');
            if($tableData){
                M('dishout_deliveryman')->where(array('token'=>$this->token,'cid'=>$this->_cid,'id'=>$id))->save($data);
            }else{
                $data['token']=$this->token;
                $data['cid']=$this->_cid;
                $data['addtime']=time();
                M('dishout_deliveryman')->add($data);
            }
            $this->success('设置成功',U('DishOut/deliveryman',array('token'=>$this->token,'cid'=>$this->_cid)));
        }else{
            $this->assign('tableData',$tableData);
            $this->display();
        }
    }

    public function deliveryman_del(){
        $id = $_GET['id'];
        M('dishout_deliveryman')->where(array('token'=>$this->token,'id'=>$id))->delete();
        $this->success('删除成功');
    }

    //接单
    public function agree()
    {
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->agree($this->shop_id,$id);
        if(empty($num))
            $this->error('接单失败');
        $this->success('接单成功');
    }

    //取消订单
    public function cancel()
    {
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->cancel($this->shop_id,$id);
        if(empty($num))
            $this->error('取消订单失败');
        $this->success('取消订单成功');
    }

    public function getDman()
    {
        $dman=XD('DishoutDeliveryman');
        $get=array('cid'=>$this->shop_id);
        $list=$dman->getList($get,0,30);
        $this->ajaxReturn(array('status'=>'1','data'=>$list));
    }

    //分配订单
    public function assignOrder()
    {
        $id=I('get.id');
        $did=I('get.did');
        $order=XD('DishOrder');
        $num=$order->assign($this->shop_id,$id,$did);
        if(empty($num))
            $this->error('分配失败:'.$order->getError());
        $this->success('分配成功');
    }

    public function complete()
    {
        $id=I('get.id');
        $order=XD('DishOrder');
        $num=$order->complete($this->shop_id,$id);
        if(empty($num))
            $this->error('处理失败：'.$order->getError());
        $this->success('处理成功');
    }
    
    
    
    
    
    
    
    
    
    
}

?>