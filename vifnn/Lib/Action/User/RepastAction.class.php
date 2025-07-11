<?php

class RepastAction extends UserAction {

    public $_cid = 0;
    protected $company;
    protected $shop_id;

    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction('dx');
       
        $this->_cid = isset($_GET['cid']) ? intval($_GET['cid']) : session('companyid');
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
        $ischild = session('companyLogin');
        $mDishSet = $this->getDishMainCompany($this->token);
        if (($mDishSet['cid'] != $this->_cid) && ($mDishSet['dishsame'] == 1) && ($ischild == 1)) {
            $this->assign('dishsame', 1);
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
        $this->assign('ischild', $ischild);
        $this->assign('cid', $this->_cid);
    }

    /**
     * 餐台列表
     */
    public function index() {
        $Diningdb = M('Dining_table');
        $where = array('cid' => $this->_cid);
        $count = $Diningdb->where($where)->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $list = $Diningdb->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $todaytime = strtotime(date('Y-m-d'));
        $tabledb = M('Dish_table');
        $jointable = C('DB_PREFIX') . 'dish_order';
        $tabledb->join('as d_t LEFT JOIN ' . $jointable . ' as d_o on d_t.orderid=d_o.id');
        $orderTable = $tabledb->where("d_t.cid=" . $this->_cid . " AND (d_t.reservetime >" . $todaytime . " OR d_t.creattime >" . $todaytime . ")  AND d_t.isuse !=2 AND d_o.takeaway=0")->order("d_o.reservetime ASC")->field('d_t.id,d_o.id as orid,d_t.tableid,d_t.reservetime,d_t.isuse,d_t.dn_id,d_o.name,d_o.sex,d_o.tel,d_o.paid,d_o.price,d_o.takeaway')->select();

        $tmpdata = array();
        if ($orderTable) {
            foreach ($orderTable as $vv) {
                if (array_key_exists($vv['tableid'], $tmpdata)) {
                    continue;
                } else {
                    $isusestr = $vv['isuse'] == 1 ? '正在就餐...' : '未就餐';
                    if ($vv['dn_id'] > 0) {
                        $dnamearr = $this->GetCanName($this->_cid, $vv['dn_id']);
                        $tmp = date('Y-m-d', $vv['reservetime']) . " " . $dnamearr['name'] . "&nbsp;&nbsp;<span style='color:red'>" . $vv['name'] . " </span>预定了 ";
                    } else {
                        $tmp = date('Y-m-d H:i', $vv['reservetime']) . " <span style='color:red'>" . $vv['name'] . " </span>预定了";
                    }
                    $tmpdata[$vv['tableid']] = array('id' => $vv['id'], 'tableid' => $vv['tableid'], 'reservestr' => $tmp, 'reservetime' => $vv['reservetime'], 'orid' => $vv['orid'], 'isuse' => $vv['isuse'], 'isusestr' => $isusestr);
                }
            }
        }

        $this->assign('reserve', $tmpdata);
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 对餐台的操作
     * @see UserAction::add()
     */
    public function add() {
        $dataBase = D('Dining_table');
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id) {//edit
                if ($dataBase->create() !== false) {
                    $action = $dataBase->save();
                    if ($action != false) {
                        $this->success('修改成功', U('Repast/index', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            } else {//add
                if ($dataBase->create() !== false) {
                    $action = $dataBase->add();
                    if ($action > 0) {
                        $db_dish_reply = M('Dish_reply');
                        $keyword = $this->_cid . '餐台' . $action;
                        $inser_id = $db_dish_reply->add(array('cid' => $this->_cid, 'token' => $this->token, 'tableid' => $action, 'keyword' => $keyword, 'cf' => 'dish', 'addtime' => time()));
                        if ($inser_id > 0) {
                            $this->handleKeyword($inser_id, 'MicroRepast', $keyword);
                        }
                        $this->success('添加成功', U('Repast/index', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
            $this->assign('tableData', $findData);
            $this->display();
        }
    }

    /*     * 切换餐桌状态* */

    public function toSwitchStatus() {
        $typ = $this->_post('typ') ? intval($this->_post('typ', "trim")) : 0;
        $tid = $this->_post('tid') ? intval($this->_post('tid', "trim")) : 0;
        $vv = $this->_post('vv') ? intval($this->_post('vv', "trim")) : 0;
        $dtid = $this->_post('dtid') ? intval($this->_post('dtid', "trim")) : 0;
        $Diningdb = M('Dining_table');
        switch ($typ) {
            case 1:
                if ($vv == 1) {
                    $Diningdb->where(array('id' => $tid, 'cid' => $this->_cid))->save(array('status' => 0));
                } else {
                    $Diningdb->where(array('id' => $tid, 'cid' => $this->_cid))->save(array('status' => 1));
                }
                break;
            case 2:
                if ($vv == 1) {
                    M('Dish_table')->where(array('id' => $dtid, 'tableid' => $tid, 'cid' => $this->_cid))->save(array('isuse' => 2));
                    $Diningdb->where(array('id' => $tid, 'cid' => $this->_cid))->save(array('status' => 0));
                } else {
                    M('Dish_table')->where(array('id' => $dtid, 'tableid' => $tid, 'cid' => $this->_cid))->save(array('isuse' => 1));
                    $Diningdb->where(array('id' => $tid, 'cid' => $this->_cid))->save(array('status' => 1));
                }
                break;
            default:
                break;
        }
        echo json_encode(array('error' => 0));
    }

    /**
     * 删除餐台
     */
    public function del() {
        $diningTable = M('Dining_table');
        if (IS_GET) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $where = array('id' => $id, 'cid' => $this->_cid);
            $check = $diningTable->where($where)->find();
            if ($check == false)
                $this->error('非法操作');
            $back = $diningTable->where($where)->delete();
            $tmparr = array('tableid' => $id, 'cid' => $this->_cid, 'token' => $this->token);
            $db_Dish_reply = M('Dish_reply');
            $tmps = $db_Dish_reply->where($tmparr)->find();
            $db_Dish_reply->where($tmparr)->delete();
            M('Recognition')->where(array('id' => $tmps['reg_id']))->delete();
            $this->handleKeyword($tmps['id'], 'MicroRepast', $tmps['keyword'], 0, true);
            if ($back == true) {
                $this->success('操作成功', U('Repast/index', array('token' => $this->token, 'cid' => $this->_cid)));
            } else {
                $this->error('服务器繁忙,请稍后再试', U('Repast/index', array('token' => $this->token, 'cid' => $this->_cid)));
            }
        }
    }

    /**
     * 分类管理
     */
    public function sort() {
        $data = M('Dish_sort');
        $where = array('cid' => $this->_cid);
        $count = $data->where($where)->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $list = $data->where($where)->order("`sort` ASC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 对餐台的操作
     */
    public function sortadd() {
        $dataBase = D('Dish_sort');
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id) {//edit
                if ($dataBase->create() !== false) {
                    $action = $dataBase->save();
                    if ($action != false) {
                        $this->success('修改成功', U('Repast/sort', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            } else {//add
                if ($dataBase->create() !== false) {
                    $action = $dataBase->add();
                    if ($action != false) {
                        $this->success('添加成功', U('Repast/sort', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
            $this->assign('tableData', $findData);
            $this->display();
        }
    }

    /**
     * 删除分类
     */
    public function sortdel() {
        $dishSort = M('Dish_sort');
        if (IS_GET) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $where = array('id' => $id, 'cid' => $this->_cid);
            $check = $dishSort->where($where)->find();
            if ($check == false)
                $this->error('非法操作');
            $back = $dishSort->where($where)->delete();
            if ($back == true) {
                $this->success('操作成功', U('Repast/sort', array('token' => $this->token, 'cid' => $this->_cid)));
            } else {
                $this->error('服务器繁忙,请稍后再试', U('Repast/sort', array('token' => $this->token, 'cid' => $this->_cid)));
            }
        }
    }

    /**
     * 餐桌预定情况
     */
    public function detail() {
        $list = M('Dining_table')->where(array('cid' => $this->_cid))->select();
        $dinings = array();
        foreach ($list as $l) {
            $dinings[$l['id']] = $l;
        }
        $tabledb = M('Dish_table');
        $jointable = C('DB_PREFIX') . 'dish_order';
        $reservetime = isset($_GET['time']) ? strtotime($_GET['time']) : strtotime(date('Y-m-d'));
        $tabledb->join('as d_t LEFT JOIN ' . $jointable . ' as d_o on d_t.orderid=d_o.id');
        $orderTable = $tabledb->where("d_t.cid=" . $this->_cid . " AND d_t.reservetime >=" . $reservetime . " AND d_t.reservetime <" . ($reservetime + 86400) . " AND d_o.takeaway=0")->order("d_t.id DESC")->field('d_t.id,d_o.id as orid,d_t.tableid,d_t.reservetime,d_t.creattime    ,d_t.isuse,d_t.dn_id,d_o.name,d_o.sex,d_o.tel,d_o.paid,d_o.price,d_o.takeaway')->select();
        $list = array();
        //echo M('Dish_table')->getLastSql();die;
        if ($orderTable) {
            foreach ($orderTable as $t) {
                $t['tname'] = isset($dinings[$t['tableid']]['name']) ? $dinings[$t['tableid']]['name'] : '未选订';
                if ($t['dn_id'] > 0) {
                    $dnamearr = $this->GetCanName($this->_cid, $t['dn_id'], false);
                    $t['reservetimestr'] = date('Y-m-d', $t['reservetime']) . " " . $dnamearr['name'];
                }
                $list[] = $t;
            }
        }
        $dates = array();
        $dates[] = array('k' => date("Y-m-d", strtotime("-2 days")), 'v' => date("m月d日", strtotime("-2 days")));
        $dates[] = array('k' => date("Y-m-d", strtotime("-1 days")), 'v' => date("m月d日", strtotime("-1 days")));
        $dates[] = array('k' => date("Y-m-d"), 'v' => date("m月d日"));
        for ($i = 1; $i <= 60; $i ++) {
            $dates[] = array('k' => date("Y-m-d", strtotime("+{$i} days")), 'v' => date("m月d日", strtotime("+{$i} days")));
        }
        $this->assign('reservetime', isset($_GET['time']) ? trim($_GET['time']) : date('Y-m-d'));
        $this->assign('dates', $dates);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 删除Dish_name表数据
     */
    public function delDname() {
        $name_id = intval(trim($_POST['bqid']));
        $name_id = $name_id > 0 ? $name_id : 0;
        if ($name_id > 0) {
            M('Dish_name')->where(array('id' => $name_id, 'cid' => $this->_cid, 'token' => $this->token))->delete();
            echo json_encode(array('error' => 0));
            exit();
        }
        echo json_encode(array('error' => 1));
        exit();
    }

    public function company() {
        $dataBase = D('Dish_company');
        $findData = $dataBase->where(array('cid' => $this->_cid))->find();
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
            $_POST['open_times']=json_encode($openTimes);
            $nameofcan = $_POST['biaoq'];
            $nameofcan_id = $_POST['bqid'];
            unset($_POST['biaoq'], $_POST['bqid']);
            if (!empty($nameofcan)) {
                $dnamedb = M('Dish_name');
                foreach ($nameofcan as $kk => $vv) {
                    $dcname = trim($vv);
                    $dcid = intval(trim($nameofcan_id[$kk]));
                    if ($dcid > 0) {
                        $dnamedb->where(array('id' => $dcid, 'cid' => $this->_cid))->save(array('name' => $dcname));
                    } else {
                        $dnamedb->add(array('cid' => $this->_cid, 'token' => $this->token, 'name' => $dcname));
                    }
                }
            }
            if ($findData) {//edit
                if ($dataBase->create() !== false) {
                    $action = $dataBase->save();
                    if ($action != false) {
                        $this->success('修改成功', U('Repast/company', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->success('修改成功!');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            } else {//add
                if ($dataBase->create() !== false) {
                    $action = $dataBase->add();
                    if ($action != false) {
                        $this->success('添加成功', U('Repast/company', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            }
        } else {
            if ($this->wxuser['winxintype'] == 3) {
                $erwm = $this->_get('erwm') ? intval($this->_get('erwm', 'trim')) : 0;
                $db_dish_reply = M('Dish_reply');
                if ($erwm == 1 && $this->_cid > 0) {
                    $keyword1 = '本餐厅' . $this->_cid;
                    $keyword2 = '餐桌后台' . $this->_cid;
                    $tmp1 = $db_dish_reply->where(array('cid' => $this->_cid, 'keyword' => $keyword1, 'cf' => 'dish'))->find();
                    if (empty($tmp1) || !is_array($tmp1)) {
                        $inser_id = $db_dish_reply->add(array('cid' => $this->_cid, 'token' => $this->token, 'tableid' => 0, 'keyword' => $keyword1, 'cf' => 'dish', 'addtime' => time(), 'type' => 1));
                        if ($inser_id > 0) {
                            $this->handleKeyword($inser_id, 'MicroRepast', $keyword1);
                        }
                    }
                    $tmp2 = $db_dish_reply->where(array('cid' => $this->_cid, 'keyword' => $keyword2, 'cf' => 'dish'))->find();
                    if (empty($tmp2) || !is_array($tmp2)) {
                        $inser_id = $db_dish_reply->add(array('cid' => $this->_cid, 'token' => $this->token, 'tableid' => 0, 'keyword' => $keyword2, 'cf' => 'dish', 'addtime' => time(), 'type' => 2));
                        if ($inser_id > 0) {
                            $this->handleKeyword($inser_id, 'MicroRepast', $keyword2);
                        }
                    }
                }

                $jointable = C('DB_PREFIX') . 'recognition';
                $db_dish_reply->join('as d_r LEFT JOIN ' . $jointable . ' as rg on d_r.reg_id=rg.id');
                $tmps = $db_dish_reply->where('d_r.cid=' . $this->_cid . ' AND d_r.token="' . $this->token . '" AND d_r.cf="dish" AND d_r.type!=0')->field('d_r.*,rg.code_url,rg.ticket,rg.status')->select();
                $erweima = array();
                if (!empty($tmps)) {
                    foreach ($tmps as $vv) {
						$vv['code_url']=$vv['ticket']?$vv['ticket']:$vv['code_url'];
                        $erweima['c' . $vv['type']] = $vv;
                    }
                    $this->assign('erweima', $erweima);
                } else {
                    $this->assign('erweima', false);
                }
            }
            $tmp = M('Dish_name')->where(array('cid' => $this->_cid, 'token' => $this->token))->select();
            $this->assign('Dcname', $tmp);
            //兼容旧版
            if(empty($findData['open_times'])){
                $times=array();
                if(!empty($tmp['starttime'])||!empty($tmp['endtime'])){
                    $starttime=$tmp['starttime']?date('H:i',$tmp['starttime']):'00:00';
                    $endtime=$tmp['endtime']?date('H:i',$tmp['endtime']):'23:59';
                    $times[]=array('start'=>$starttime,'end'=>$endtime);
                }
                if(!empty($tmp['starttime2'])||!empty($tmp['endtime2'])){
                    $starttime2=$tmp['starttime2']?date('H:i',$tmp['starttime2']):'00:00';
                    $endtime2=$tmp['endtime2']?date('H:i',$tmp['endtime2']):'23:59';
                    $times[]=array('start'=>$starttime2,'end'=>$endtime2);
                }
                $times=$times?$times:array(array('start'=>'00:00','end'=>'23:59'));
                $findData['open_times']=json_encode($times);
            }
            $this->assign('company', $findData);
            $this->display();
        }
    }

    /**
     * 菜的列表
     */
    public function dish() {
        $data = M('Dish');
		//自行增加
		$dataBase = D('Dish');
		$p = $_GET['p'];  //获取当前页码
			$dishSort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();
			if (empty($dishSort)) {
				$this->redirect(U('Repast/sortadd',array('token' => $this->token, 'cid' => $this->_cid)));
			}
			$findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
			$this->assign('tableData', $findData);
			$this->assign('dishSort', $dishSort);
		
		
		
		if(IS_POST){
		    $dishname = $_POST['dishname'];
		    $sid = $_POST['sid'];
			
		    if (empty($sid) and empty($dishname)){
	            $where = array('cid' => $this->_cid);
		    }elseif (!empty($dishname) and !empty($sid)){
			   // $where = array('cid' => $this->_cid,'name' => $dishname,'sid' => $sid);
				$where['cid'] = $this->_cid;
				$where['sid'] = $sid;
				$where['_string'] = '(name like "%'.$dishname.'%")';
				
		    }elseif (empty($dishname)){
				 // $where = array('cid' => $this->_cid,'sid' => $sid);
				  $where['cid'] = $this->_cid;
				  $where['sid'] = $sid;	
	        }else{
				$where['cid'] = $this->_cid;
				$where['_string'] = '(name like "%'.$dishname.'%")';
			//  $where = array('cid' => $this->_cid,'name' => $dishname);
		    }
			$this->assign('tabled', $sid);  //记录分类
			$this->assign('dishab', $dishname);//记录菜品
		}else{
			$where = array('cid' => $this->_cid);
		}
		//自行增加结束
		//$where = array('cid' => $this->_cid);   //使用上面的方式后，注销此方式
        $count = $data->where($where)->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $dish = $data->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order("`sort` ASC,id desc")->select();
        $list = $sortList = array();
        $sort = M('Dish_sort')->where(array('cid' => $this->_cid))->order("`sort` ASC")->select();
        foreach ($sort as $row) {
            $sortList[$row['id']] = $row;
        }
        $klist = array();
        $kitchens = M('Dish_kitchen')->where(array('cid' => $this->_cid))->select();
        foreach ($kitchens as $k) {
            $klist[$k['id']] = $k;
        }
        foreach ($dish as $r) {
            $r['sortName'] = isset($sortList[$r['sid']]['name']) ? $sortList[$r['sid']]['name'] : '';
            $r['kitchenName'] = isset($klist[$r['kitchen_id']]['name']) ? $klist[$r['kitchen_id']]['name'] : '';
            $list[] = $r;
        }
        $this->assign('page', $show);
        $this->assign('list', $list);
		$this->assign('pa', $p); //自行增加 记录当前页码的
        $this->display();
    }

    /** 获取主餐厅配置信息* */
    private function getDishMainCompany($token, $cache = true) {
        $mDishC = $_SESSION["session_MaindishSet_{$token}"];
        $mDishC = !empty($mDishC) ? unserialize($mDishC) : false;
        if ($cache && !empty($mDishC)) {
            return $mDishC;
        } else {
            $MainC = M('Company')->where(array('token' => $token, 'isbranch' => 0))->find();
            $m_cid = $MainC['id'];
            unset($MainC);
            $mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
            unset($m_cid);
            if ($cache) {
                $_SESSION["session_MaindishSet_{$token}"] = !empty($mDishC) ? serialize($mDishC) : '';
            } else {
                $_SESSION["session_MaindishSet_{$token}"] = '';
            }
            return $mDishC;
        }
    }

    /**
     * 对菜品的操作
     */
    public function dishadd() {
        $dataBase = D('Dish');
        $dish_sort = M('Dish_sort');
        $dishSkuModel=XD('User/DishSku');
        //自行增加
	$p = $_GET['p'];  //获取当前面码
	//自行增加结束
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $_POST['ishot'] = isset($_POST['ishot']) ? intval($_POST['ishot']) : 0;
            $_POST['isopen'] = isset($_POST['isopen']) ? intval($_POST['isopen']) : 0;
            $_POST['istakeout'] = isset($_POST['istakeout']) ? intval($_POST['istakeout']) : 0;
            $_POST['isdiscount'] = isset($_POST['isdiscount']) ? intval($_POST['isdiscount']) : 0;
            $_POST['kitchen_id'] = isset($_POST['kitchen_id']) ? intval($_POST['kitchen_id']) : 0;
            $_POST['is_meal']=isset($_POST['is_meal'])?$_POST['is_meal']:'0';
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
                $info=M('dish')->where(array('id'=>$id,'cid'=>$this->_cid))->find();
                $_POST['update_time']=time();
                if ($dataBase->create() !== false) {
                    $temp = M('Dish')->where(array('cid' => $this->_cid, 'id' => $id))->find();
                    $action = $dataBase->save();
                    if($action){
                        if(!empty($spec)&&!empty($info['spec'])){
				$dishSkuModel->updateSku($id, $opts, I("post.sku"), $spec, $info["spec"]);
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
                        $this->success('修改成功', U('Repast/dish', array('token' => $this->token, 'cid' => $this->_cid, 'p' => $p)));//自行增加页码
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            } else {//add
                if ($dataBase->create() !== false) {
                    $action = $dataBase->add();
                    if($action&&!empty($spec)){
                        $dishSkuModel->addSku($action,$opts,I('post.sku'));
                    }
                    if ($action != false) {
                        $dish_sort->where(array('id' => $_POST['sid'], 'cid' => $this->_cid))->setInc('num', 1);
                        if ($action > 0) {
                        }
                        $this->success('添加成功', U('Repast/dish', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dataBase->getError());
                }
            }
        } else {
            $db_dotime = M('dishout_manage');
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $dishSort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();
            if (empty($dishSort)) {
                $this->success('您还没有建立菜品分类哦,<br/>请先去建立个菜品分类吧！',U('Repast/sortadd', array('token' => $this->token, 'cid' => $this->_cid)));
                /*$this->redirect(U('Repast/sortadd', array('token' => $this->token, 'cid' => $this->_cid)));*/
            }
            $spec=M('dish_spec');
            $where['_string']="token='' OR token='{$this->token}'";
            $list=$spec->where($where)->order('create_time desc')->select();
            $this->assign('spec_list',$list);
            $kitchens = M('Dish_kitchen')->where(array('cid' => $this->_cid))->select();
            $findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
            $this->assign('tableData', $findData);
            $this->assign('dishSort', $dishSort);
	    $this->assign('pa', $p); //自行增加 记录当前页码的
            $this->assign('kitchens', $kitchens);
            $manage=$db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
            $this->assign('manage',$manage);
            if(!empty($findData)&&!empty($findData['spec'])){
                $sku=$dishSkuModel->getSku($id,$findData['spec']);
                $this->assign('sku_json',json_encode($sku));
            }
            $this->display();
        }
    }

    /**
     * 当菜品instock字段值为零时，并且对应refreshstock字段值大于零时
      用对应refreshstock字段的值修改对应instock字段
     * */
    public function refreshStock() {
        $token = $this->_get('token', 'trim');
        $msg = '刷新失败！';
        if ($this->token == $token) {
            $tmp = M('Dish_company')->where(array('cid' => $this->_cid))->find();
            if (!empty($tmp) && ($tmp['kconoff'] == 1)) {
                $dishModel=M('dish');
                $specModel=M('DishSpec');
                $skuModel=D('DishSku');
                $list=$dishModel->where(array('cid'=>$this->_cid))->field('id,spec,refreshstock,instock')->select();
                foreach ($list as $item){
                    if(empty($item['spec'])){
                        if(($item["instock"] <= 0) && ($item['refreshstock']>0)){
                            $dishModel->where(array('id'=>$item['id']))->save(array('instock'=>$item['refreshstock']));
                        }
                    }else{
                        $opts=$specModel->where(array('id'=>$item['spec']))->getField('opts');
                        $opts=explode(',',$opts);
                        $total=0;
                        foreach ($opts as $opt){
                            $skuInfo=$skuModel->where(array('product_id'=>$item['id'],'sku'=>sha1($opt)))->field('id,refresh_inve,inventory')->find();
                            if(($skuInfo["inventory"] <= 0) && ($skuInfo['refresh_inve']>0)){
                                $skuModel->where(array('id'=>$skuInfo['id']))->save(array('inventory'=>$skuInfo['refresh_inve']));
                                $total+=$skuInfo['refresh_inve'];
                            }else {
								$total += $skuInfo["inventory"];
							}
						}

						if ($total != $item["instock"]) {
                            $dishModel->where(array('id'=>$item['id']))->save(array('instock'=>$total));
                        }
                    }
                }
                $msg = '刷新成功！';
            } else {
                $msg = '您尚未开启菜品库存管理！';
            }
        }
        $msg.="<br/> <span style='font-size:14px;'>当菜品库存为零且刷新库存大于零时库存会被刷新！</span>";
        $this->success($msg);
    }

    /**
     * 删除菜
     */
    public function dishdel() {
		if (IS_GET) {
			$id = (isset($_GET["id"]) ? intval($_GET["id"]) : 0);
			$model = XD("User/Dish");
			$res = $model->delDish($this->_cid, $id);

			if (!$res) {
				$this->error("删除失败");
			}

			$this->success("删除成功");
		}
    }

    /**
     * 订单列表
     */
    public function orders() {
        $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
        $dish_order = M('Dish_order');

        if (IS_POST) {
            $where = array('token' => $this->_session('token'), 'cid' => $this->_cid, 'comefrom' => 'dish');
            $key = $this->_post('searchkey');
            if (empty($key)) {
                $this->error("关键词不能为空");
            }
            $where['name|address'] = array('like', "%$key%");
            $orders = $dish_order->where($where)->select();
            $count = $dish_order->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->count();
            $Page = new Page($count, 20);
            $show = $Page->show();
        } else {
            $fd = $this->_get('fd') ? $this->_get('fd', 'trim') : '';
            $ischild = session('companyLogin');
            $ischild = $ischild ? intval($ischild) : 0;
            $falg = false;
            if (($ischild != 1) && ($fd == 'on')) {
                $companys = M('Company')->where("token ='{$this->token}' AND (`isbranch`=1 AND `display`=1)")->field('id,token,name')->select();
                $Cyarrs = array();
                $cidsarr = '';
                if (!empty($companys)) {
                    foreach ($companys as $vv) {
                        $Cyarrs[$vv['id']] = $vv;
                    }
                    $cidsarr = array_keys($Cyarrs);
                    $where = array('token' => $this->_session('token'), 'cid' => array('in', $cidsarr), 'comefrom' => 'dish');
                }
                $falg = true;
                $this->assign('companys', $Cyarrs);
            } else {
                $where = array('token' => $this->_session('token'), 'cid' => $this->_cid, 'comefrom' => 'dish', 'isdel' => 0);
            }
            switch ($status) {
                case 4 :
                    $where['isuse'] = 1;
                    $where['paid'] = 1;
                    break;
                case 3 :
                    $where['isuse'] = 0;
                    $where['paid'] = 1;
                    break;
                case 2:
                    $where['isuse'] = 1;
                    $where['paid'] = 0;
                    break;
                case 1 :
                    $where['isuse'] = 0;
                    $where['paid'] = 0;
                default:
                    break;
            }

            $count = !empty($where) ? $dish_order->where($where)->count() : 0;
            $Page = new Page($count, 20);
            $show = $Page->show();
            $jointable = C('DB_PREFIX') . 'dish_table';
            $dish_order->join('as d_o LEFT JOIN ' . $jointable . ' as d_t on d_o.id=d_t.orderid');
            $newwhere = array();
            foreach ($where as $kk => $vv) {
                $newwhere['d_o.' . $kk] = $vv;
            }
            $orders = !empty($where) ? $dish_order->where($newwhere)->order('d_o.id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->field('d_o.*,d_t.dn_id,d_t.reservetime as rrtime')->select() : false;
        }
        $diningTable = M('Dining_table')->where(array('cid' => $this->_cid))->select();
        $list = array();
        foreach ($diningTable as $row) {
            $list[$row['id']] = $row;
        }
        $neworders = array();
        foreach ($orders as $kk => $vv) {
            if (isset($vv['dn_id']) && ($vv['dn_id'] > 0)) {
                $dnamearr = $this->GetCanName($this->_cid, $vv['dn_id']);
                $vv['rrtimestr'] = date('Y-m-d', $vv['rrtime']) . " " . $dnamearr['name'];
            }
            $neworders[$kk] = $vv;
        }
        unset($orders);
        $this->assign('diningTable', $list);
        $this->assign('orders', $neworders);
        $this->assign('status', $status);

        $this->assign('page', $show);
        if ($falg) {
            $this->display('fdorders');
        } else {
            $this->display();
        }
    }

    /*     * **************
     * *保存菜品销售情况
     * *Dishout_salelog表
     * ************** */

    public function saleLog($data) {
        $log_db = M('Dishout_salelog');
        /* $tmplog = $log_db->where(array('order_id' => $data['oid']))->find();
          if (!empty($tmplog)) {
          return false;
          } */
        $Dishcompany = M('Dish_company')->where(array('cid' => $data['cid']))->find();
        $kconoff = $Dishcompany['kconoff'];
        unset($Dishcompany);
        $tmparr = array('token' => $this->token, 'cid' => $data['cid'], 'order_id' => $data['oid'], 'paytype' => $data['paytype']);
        $mDishSet = $this->getDishMainCompany($this->token);
        if (!empty($data['dish']) && is_array($data['dish'])) {
            $log_db = M('Dishout_salelog');
            $DishDb = M('Dish');
            foreach ($data['dish'] as $kk => $vv) {
                $did = isset($vv['did']) ? $vv['did'] : $kk;
                if ($did > 0) {
                    $flag = isset($vv['flag']) ? $vv['flag'] : '';
                    $newk = $flag . 'jc' . $did;
                    if (!($data['paycount'] > 0) || ($kk == $newk)) {

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
                            'addtime' => $data['time'], 'addtimestr' => date('Y-m-d H:i:s', $data['time']), 'comefrom' => 1
                        );
                        $savelogarr = array_merge($tmparr, $logarr);
                        $log_db->add($savelogarr);
                    }
                }
            }
            M('dish_order')->where(array('id' => $data['oid'], 'cid' => $data['cid']))->setInc('paycount', 1);
        }
    }

    /**
     * 订单详情
     */
    public function orderInfo() {
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $fd = $this->_get('fd') ? $this->_get('fd', 'trim') : '';
        $cidd = $this->_get('cidd') ? intval($this->_get('cidd', 'trim')) : 0;
        $cid = $this->_cid;
        if (($fd == 'on') && ($cidd > 0)) {
            $cid = $cidd;
            $this->assign('isfd', true);
        }
        $dishOrder = M('Dish_order');
        if ($thisOrder = $dishOrder->where(array('id' => $id, 'cid' => $cid, 'token' => $this->token, 'comefrom' => 'dish'))->find()) {
            if (IS_POST) {
                $isuse = isset($_POST['isuse']) ? intval($_POST['isuse']) : 0;
                $paid = isset($_POST['paid']) ? intval($_POST['paid']) : 0;
                $dishOrder->where(array('id' => $thisOrder['id']))->save(array('isuse' => $isuse, 'paid' => $paid));
                if ($thisOrder['tableid'] && $isuse) {
                    D('Dish_table')->where(array('orderid' => $thisOrder['id']))->save(array('isuse' => 1));
                }
                $company = M('Company')->where(array('token' => $this->token, 'id' => $thisOrder['cid']))->find();

                if ($paid && empty($thisOrder['paid'])) {
                    $temp = unserialize($thisOrder['info']);
                    $temp = isset($temp['list']) ? $temp['list'] : $temp;
                    $this->saleLog(array('cid' => $cid, 'oid' => $thisOrder['id'], 'paytype' => $thisOrder['paytype'], 'dish' => $temp, 'time' => $thisOrder['time'], 'paycount' => $thisOrder['paycount']));
                    $takeAwayPrice = isset($temp['takeAwayPrice']) ? $temp['takeAwayPrice'] : '';
                    $bookTable = false;
                    if (isset($temp['table']) && !empty($temp['table'])) {
                        $bookTable = $temp['table']['price'];
                        unset($temp['table']);
                    }
                    $op = new orderPrint();

                    $msg = array('companyname' => $company['name'], 'des' => $thisOrder['des'], 'companytel' => $company['tel'], 'truename' => $thisOrder['name'], 'tel' => $thisOrder['tel'], 'takeAwayPrice' => $takeAwayPrice, 'address' => $thisOrder['address'], 'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'], 'price' => $thisOrder['price'], 'total' => $thisOrder['nums'], 'ptype' => $thisOrder['paytype'], 'list' => $temp, 'bookTable' => $bookTable);
                    $msg['typename'] = $thisOrder['takeaway'] == 1 ? '外卖' : ($thisOrder['takeaway'] == 2 ? '现在点餐' : '预约点餐');
                    if ($thisOrder['takeaway'] == 0) {
                        $tmpstr = $this->GetCanTimeByoid($cid, $thisOrder['id'], $thisOrder['tableid']);
                        $msg['reservestr'] = $tmpstr ? date('Y-m-d', $order['reservetime']) .' '. $tmpstr: date('Y-m-d H:i', $thisOrder['reservetime']);
                    }
                    if ($thisOrder['tableid']) {
                        $t_table = M("Dining_table")->where(array('id' => $thisOrder['tableid']))->find();
                        $msg['tablename'] = isset($t_table['name']) ? $t_table['name'] : '';
                    }
                    $msg = ArrayToStr::array_to_str($msg, 1);
                    $op->printit($this->token, $this->_cid, 'Repast', $msg, 1);
                }
                if(in_array($thisOrder['paytype'],array('daofu','dianfu'))&&$thisOrder['paid']!='1'){
                    $dishOrderModel=XD('User/DishOrder');
                    $infoList=unserialize($thisOrder['info']);
                    if(!empty($infoList)){
                        $dishOrderModel->payConsume($thisOrder['id'],$infoList);
                    }
                }
                Sms::sendSms($this->token, "你好！{$company['name']}欢迎您，本店对您的订单号为：{$thisOrder['orderid']}的订单状态进行了修改，如有任何疑意，请您及时联系本店！", $thisOrder['tel']);
                $this->success('修改成功', U('Repast/orderInfo', array('token' => session('token'), 'id' => $thisOrder['id'])));
            } else {
                $payarr = array('alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpaycomputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线');
                $paystr = strtolower($thisOrder['paytype']);
                $thisOrder['paystr'] = !empty($paystr) && array_key_exists($paystr, $payarr) ? $payarr[$paystr] : '其他';

                $dishList = unserialize($thisOrder['info']);
                $dishList = isset($dishList['list']) ? $dishList['list'] : $dishList;
                $this->assign('thisOrder', $thisOrder);
                $this->assign('dishList', $dishList);
                $this->display();
            }
        }
    }

    /** 获取预定餐的时间描述* */
    private function GetCanTimeByoid($cid, $oid, $tableid) {
        $tmp = M('Dish_table')->where(array('orderid' => $oid, 'cid' => $cid, 'tableid' => $tableid))->find();
        if (!empty($tmp) && ($tmp['dn_id'] > 0)) {
            $NameC = M('Dish_name')->where(array('id' => $tmp['dn_id'], 'cid' => $cid))->find();
            if (!empty($NameC)) {
                return $NameC['name'];
            }
        }
        return false;
    }

    /**
     * 删除订单
     */
    public function deleteOrder() {
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $dishOrder = M('Dish_order');
        if ($thisOrder = $dishOrder->where(array('id' => $id, 'token' => $this->token, 'comefrom' => 'dish'))->find()) {
            $dishOrder->where(array('id' => $id))->save(array('isdel' => 1));
            if ($thisOrder['tableid']) {
                D('Dish_table')->where(array('orderid' => $thisOrder['id']))->delete();

                D('Dining_table')->where(array('id' => $thisOrder['tableid'], 'cid' => $thisOrder['cid']))->save(array('status' => 0));
            }
            $this->success('操作成功', U('Repast/orders', array('token' => session('token'), 'cid' => $this->_cid)));
        }
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
        $sqlstr = "select *,sum(money) as tmoney,sum(nums) as tnums from " . C('DB_PREFIX') . "dishout_salelog where comefrom='1' AND cid=" . $this->_cid . " AND token='" . $this->token . "' AND addtime>=" . $starttime . " AND addtime<=" . $endtime . " group by did";
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

    /*     * 获取dish_name表信息* */

    private function GetCanName($cid, $id = 0, $cache = true) {
        $NameC = $_SESSION["session_nameC{$cid}_{$this->token}"];
        $NameC = !empty($NameC) ? unserialize($NameC) : false;
        if ($cache && !empty($NameC)) {
            if (($id > 0) && array_key_exists($id, $NameC)) {
                return $NameC[$id];
            } elseif (($id > 0) && !array_key_exists($id, $NameC)) {
                return false;
            }
            return $NameC;
        } else {
            $NameC = M('Dish_name')->where(array('cid' => $cid, 'token' => $this->token))->select();
            if (!empty($NameC)) {
                $tmparr = array();
                foreach ($NameC as $vv) {
                    $tmparr[$vv['id']] = $vv;
                }
                $NameC = $tmparr;
            }
            if ($cache) {
                $_SESSION["session_nameC{$cid}_{$this->token}"] = !empty($NameC) ? serialize($NameC) : '';
            } else {
                $_SESSION["session_nameC{$cid}_{$this->token}"] = '';
            }
            if (($id > 0) && array_key_exists($id, $NameC)) {
                return $NameC[$id];
            } elseif (($id > 0) && !array_key_exists($id, $NameC)) {
                return false;
            }
            return $NameC;
        }
    }

    public function tableEwm() {
        $tableid = $this->_get('tid') ? intval($this->_get('tid')) : 0;
        if (($this->wxuser['winxintype'] == 3) && ($tableid > 0)) {
            $db_dish_reply = M('Dish_reply');
            $jointable = C('DB_PREFIX') . 'recognition';
            $db_dish_reply->join('as d_r LEFT JOIN ' . $jointable . ' as rg on d_r.reg_id=rg.id');
            $tmps = $db_dish_reply->where('d_r.cid=' . $this->_cid . ' AND d_r.token="' . $this->token . '" AND d_r.cf="dish" AND d_r.type=0 AND tableid=' . $tableid)->field('d_r.*,rg.code_url,rg.status,rg.ticket')->find();
            if (empty($tmps)) {
                $keyword = $this->_cid . '餐台' . $tableid;
                $tmps = array('cid' => $this->_cid, 'token' => $this->token, 'tableid' => $tableid, 'keyword' => $keyword, 'cf' => 'dish', 'addtime' => time());
                $inser_id = $db_dish_reply->add(array('cid' => $this->_cid, 'token' => $this->token, 'tableid' => $tableid, 'keyword' => $keyword, 'cf' => 'dish', 'addtime' => time()));
                if ($inser_id > 0) {
                    $tmps['id'] = $inser_id;
                    $tmps['code_url'] = '';
                    $tmps['reg_id'] = 0;
                    $tmps['type'] = 0;
                    $this->handleKeyword($inser_id, 'MicroRepast', $keyword);
                }
            }
            $tmps['code_url']=$tmps['ticket']?$tmps['ticket']:$tmps['code_url'];//兼容
            $this->assign('erweima', $tmps);
        }
        $this->assign('tid', $tableid);
        $this->display();
    }

    /**
     * 二维码处理
     */
    public function QRcode() {
        $isdown = $this->_get('down') ? intval($this->_get('down')) : false;
        $tableid = $this->_get('tid') ? intval($this->_get('tid')) : 0;
        $typ = $this->_get('typ') ? $this->_get('typ', 'trim') : false;

        include './vifnn/Lib/ORG/phpqrcode.php';
        if ($tableid > 0) {
            $viewUrl = C('site_url') . U('Wap/Repast/dishMenu', array('token' => $this->token, 'cid' => $this->_cid, 'tid' => $tableid));
        } else {
            $viewUrl = C('site_url') . U('Wap/Repast/ShopPage', array('token' => $this->token, 'cid' => $this->_cid));
        }
        if ($typ == 'mtable') {
            $viewUrl = C('site_url') . U('User/RepastStaff/mtlogin', array('token' => $this->token, 'cid' => $this->_cid));
        }
        $url = urldecode($viewUrl);
        if ($isdown) {
            $fname = $tableid > 0 ? 'fendian_' . $this->_cid . "-table_" . $tableid . ".png" : 'fendian_' . $this->_cid . ".png";
            header("Pragma: public"); // required 
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header('Content-type: image/png');
            header("Content-Type:application/download");
            header("Content-Disposition: attachment; filename={$fname}");
            header("Content-Transfer-Encoding: binary");
            QRcode::png($url, false, 3, 10);
        } else {
            QRcode::png($url, false, 1, 8);
        }
    }

    /**
     * 下载微信二维码
     */
    public function DownWxewm() {
        $ticket = $this->_get('ticket') ? $this->_get('ticket', 'trim') : false;
        if ($ticket) {
            $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket;
            $tmps = $this->httpRequest($url, 'GET');
            header("Pragma: public"); // required 
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header('Content-type: image/png');
            header("Content-Type:application/download");
            header("Content-Disposition: attachment; filename=you_weixin_ticket.png");
            header("Content-Transfer-Encoding: binary");
            echo $tmps['1'];
        }
    }

    /**
     * 厨房列表
     */
    public function kitchen() 
    {
        $list = M('Dish_kitchen')->where(array('cid' => $this->_cid))->select();
        $this->assign('list', $list);
        $this->display();
    }
    
    /**
     * 对厨房的操作
     */
    public function addkitchen() 
    {
        $dishkitchen = M('Dish_kitchen');
        if (IS_POST) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id) {//edit
                if ($dishkitchen->create() !== false) {
                    $action = $dishkitchen->save();
                    if ($action != false) {
                        $this->success('修改成功', U('Repast/kitchen', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dishkitchen->getError());
                }
            } else {//add
                if ($dishkitchen->create() !== false) {
                    if ($dishkitchen->add()) {
                        $this->success('添加成功', U('Repast/kitchen', array('token' => $this->token, 'cid' => $this->_cid)));
                    } else {
                        $this->error('操作失败');
                    }
                } else {
                    $this->error($dishkitchen->getError());
                }
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $kitchen = $dishkitchen->where(array('id' => $id, 'cid' => $this->_cid))->find();
            $this->assign('kitchen', $kitchen);
            $this->display();
        }
    }
    
    
    /**
     * 删除厨房
     */
    public function delkitchen() 
    {
        $dishkitchen = M('Dish_kitchen');
        if (IS_GET) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $where = array('id' => $id, 'cid' => $this->_cid);
            $check = $dishkitchen->where($where)->find();
            if ($check == false) $this->error('非法操作');
            $back = $dishkitchen->where($where)->delete();
            if ($back == true) {
                $this->success('操作成功', U('Repast/kitchen', array('token' => $this->token, 'cid' => $this->_cid)));
            } else {
                $this->error('服务器繁忙,请稍后再试', U('Repast/kitchen', array('token' => $this->token, 'cid' => $this->_cid)));
            }
        }
    }

    public function spec()
    {
        $spec=M('dish_spec');
        $where['_string']="token='' OR token='{$this->token}'";
        $where['cid']=$this->_cid;
        $list=$spec->where($where)->order('create_time desc')->select();
        $this->assign('_list',$list);
        $this->display();
    }

    public function specAdd()
    {
        if(IS_POST){
            if(empty($_POST['name'])){
                $this->error('名称不能为空');
            }
            if(empty($_POST['opts'])){
                $this->error('选项不能为空');
            }
            $data['token']=$this->token;
            $data['cid']=$this->_cid;
            $data['name']=I('post.name');
            $opts=explode(',',I('post.opts'));
            $tmp=array();
            foreach ($opts as $opt){
                if(!empty($opt)&&!in_array($opt,$tmp)){
                    $tmp[]=$opt;
                }
            }
            $data['opts']=$tmp?implode(',',$tmp):'';
            $data['create_time']=time();
            $spec=M('dish_spec');
            $id=$spec->add($data);
            if(!$id)
                $this->error('添加规格失败');
            $this->success('添加规格成功',U('spec'));
        }else{
            $this->display();
        }
    }

    public function specEdit()
    {
        $spec=M('dish_spec');
        if(IS_POST){
            if(empty($_POST['name'])){
                $this->error('名称不能为空');
            }
            if(empty($_POST['opts'])){
                $this->error('选项不能为空');
            }
            $res=M('dish')->where(array('cid'=>$this->_cid,'spec'=>array('eq',I('post.id'))))->find();
            if($res)
                $this->error('请先删除关联的菜品');
            $data['name']=I('post.name');
            $opts=explode(',',I('post.opts'));
            $tmp=array();
            foreach ($opts as $opt){
                if(!empty($opt)&&!in_array($opt,$tmp)){
                    $tmp[]=$opt;
                }
            }
            $data['opts']=$tmp?implode(',',$tmp):'';
            $num=$spec->where(array('id'=>I('post.id'),'cid'=>$this->_cid))->save($data);
            if(!$num)
                $this->error('编辑规格失败');
            $this->success('编辑规格成功',U('spec'));
        }else{
            $info=$spec->where(array('id'=>I('get.id')))->find();
            if(empty($info))
                $this->error('规格不存在');
            $this->assign('_info',$info);
            $this->display('specAdd');
        }
    }

    //删除
    public function specDel()
    {
        $ids=get_request_ids();
        if(empty($ids)){
            $this->error('请选择要删除的规格');
        }
        $res=M('dish')->where(array('spec'=>array('in',$ids)))->find();
        if($res)
            $this->error('请先删除关联的菜品');
        $spec=M('dish_spec');
        $num=$spec->where(array('id'=>array('in',$ids),'cid'=>$this->_cid))->delete();
        if($num!=count($ids))
            $this->error('删除失败');
        $this->success('删除成功');
    }

    public function bind()
    {
        $bindModel=XD('User/DishBind');
        $where=array('cid'=>$this->_cid);
        $count = $bindModel->where($where)->count();
        $Page = new Page($count, 20);
        $show = $Page->show();
        $list=$bindModel->where($where)->order('bind_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $companyModel=D('Company');
        $groupList=D('WechatGroupList');
        foreach ($list as &$item){
            $info=$companyModel->where(array('id'=>$item['cid']))->field('name')->find();
            $item['name']=$info['name'];
            $wx=$groupList->where(array('openid'=>$item['wecha_id']))->field('id,nickname,openid,headimgurl')->find();
            $item['nickname']=$wx['nickname'];
            $item['headimgurl']=$wx['headimgurl'];
        }
        $this->assign('_list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function bindAdd()
    {
        $companyModel=D('Company');
        if(IS_POST){
            if(empty($_POST['cid'])){
                $this->error('请选择门店');
            }
            if(empty($_POST['wecha_id'])){
                $this->error('请选择粉丝');
            }
            if($this->company['isbranch']=='1'){
                $_POST['cid']=$this->_cid;
            }else{
                $num=$companyModel->where(array('token'=>$this->token,'cid'=>I('post.cid')))->count();
                if(!$num) $this->error('门店不存在');
            }
            $bindModel=D('DishBind');
            $has=$bindModel->where(array('cid'=>I('post.cid'),'wecha_id'=>I('post.wecha_id'),'token'=>$this->token))->count();
            if($has) $this->error('不能重复绑定');
            $id=$bindModel->add(array('cid'=>I('post.cid'),'wecha_id'=>I('post.wecha_id'),'bind_time'=>time(),'token'=>$this->token));
            if(!$id)
                $this->error('绑定失败');
            $this->success('绑定成功',U('bind'));
        }else{
            $list=$companyModel->where(array('token'=>$this->token))->field('id,name,isbranch')->select();
            $tmp=array();
            foreach ($list as $key=>$value){
                if($value['isbranch']!='1'){
                    $tmp=$value;
                    unset($list[$key]);
                    break;
                }
            }
            $this->assign('company_main',$tmp);
            $this->assign('company_list',$list);
            $this->display();
        }
    }

    public function bindEdit()
    {
        $companyModel=D('Company');
        if(IS_POST){
            if(empty($_POST['cid'])){
                $this->error('请选择门店');
            }
            if(empty($_POST['wecha_id'])){
                $this->error('请选择粉丝');
            }
            $num=$companyModel->where(array('token'=>$this->token,'cid'=>I('post.cid')))->count();
            if(!$num) $this->error('门店不存在');
            $bindModel=D('DishBind');
            $has=$bindModel->where(array('cid'=>I('post.cid'),'wecha_id'=>I('post.wecha_id'),'token'=>$this->token,'id'=>array('neq',I('post.id'))))->count();
            if($has) $this->error('不能重复绑定');
            $res=$bindModel->where(array('id'=>I('post.id'),'cid'=>$this->_cid))->save(array('cid'=>I('post.cid'),'wecha_id'=>I('post.wecha_id')));
            if(!$res)
                $this->error('编辑失败');
            $this->success('编辑成功',U('bind'));
        }else{
            if(empty($_GET['id'])){
                $this->error('管理员不存在');
            }
            $bindModel=D('DishBind');
            $info=$bindModel->where(array('id'=>I('get.id'),'cid'=>$this->_cid))->find();
            if(empty($info)){
                $this->error('管理员不存在');
            }
            $this->assign('_info',$info);
            $list=$companyModel->where(array('token'=>$this->token))->field('id,name,isbranch')->select();
            $tmp=array();
            foreach ($list as $key=>$value){
                if($value['isbranch']!='1'){
                    $tmp=$value;
                    unset($list[$key]);
                    break;
                }
            }
            $this->assign('company_main',$tmp);
            $this->assign('company_list',$list);
            $this->display('bindAdd');
        }
    }

    public function bindDel()
    {
        $ids=get_request_ids();
        if(empty($ids)){
            $this->error('请选择要删除的管理员');
        }
        $bindModel=D('DishBind');
        $num=$bindModel->where(array('id'=>array('in',$ids),'cid'=>$this->_cid))->delete();
        if($num!=count($ids))
            $this->error('删除失败');
        $this->success('删除成功');
    }





    
}

?>