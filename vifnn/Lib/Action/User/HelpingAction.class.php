<?php

class HelpingAction extends UserAction {

	public function _initialize() {
		parent::_initialize();
		
		$this->canUseFunction("Helping");
	}

	public function index() {
		$search = $this->_post('search', 'trim');
		$where = array('token' => $this->token);
		if ($search) {
			$where['title|keyword'] = array('like', '%' . $search . '%');
		}

		$count = M('Helping')->where($where)->count();
		$Page = new Page($count, 15);

		$list = M('Helping')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $k => $v) {
			$share_count = 0;
			$user_list = M('helping_user')->where(array('token' => $this->token, 'pid' => $v['id']))->field('share_num')->select();
			foreach ($user_list as $vo) {
				$share_count = $share_count + $vo['share_num'];
			}
			$list[$k]['share_count'] = $share_count;
			$where = array('a.token' => $this->token, 'a.pid' => $v['id'], 'a.is_join2' => 1, 'a.help_count' => array('gt', 0));
			if ($v['is_reg'] == 1) {
				$where['b.tel'] = array('neq', "");
			}
			$user_list = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel info_tel')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->group('a.id')->order('a.help_count desc,a.share_num desc')->select();
			$user_count = count($user_list);
			//$user_count = M('helping_user')->where($where)->count();
			$list[$k]['user_count'] = $user_count;
		}

		$this->assign('page', $Page->show());
		$this->assign('list', $list);
		$this->display();
	}
	public function delagain() {
		$id = $this->_get('id', 'intval');
		$againlist = M('helping_user')->where(array('token' => $this->token, 'pid' => $id))->order('help_count desc')->select();
		$users_array = array();
		$trueusers_array = array();
		$trueusers_array2 = array();
		$trueusers_array3 = array();
		$again = false;
		foreach ($againlist as $avo3) {
			if (strlen($avo3['share_key']) <= 16) {
				$again = true;
			}
		}
		if ($again) {
			foreach ($againlist as $avo) {
				if (strlen($avo['share_key']) > 16) {
					$trueusers_array[] = $avo['wecha_id'];
					$trueusers_array2[$avo['wecha_id']] = $avo['id'];
					$trueusers_array3[$avo['wecha_id']] = $avo['help_count'];
				}
			}
			foreach ($againlist as $avo2) {
				if (strlen($avo2['share_key']) <= 16) {
					if (in_array($avo2['wecha_id'], $trueusers_array)) {
						$trueusers_array3[$avo2['wecha_id']] = $trueusers_array3[$avo2['wecha_id']] + $avo2['help_count'];
						$update_helping_user = M('helping_user')->where(array('token' => $this->token, 'pid' => $id, 'id' => $trueusers_array2[$avo2['wecha_id']]))->save(array('help_count' => $trueusers_array3[$avo2['wecha_id']]));
						$del_helping_user = M('helping_user')->where(array('token' => $this->token, 'pid' => $id, 'id' => $avo2['id']))->delete();
					}
				}
			}
		}
	}
	public function set() {
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'id' => $id);
		$help_info = M('Helping')->where($where)->find();
		if (IS_POST) {
			$set['token'] = $this->token;
			$set['keyword'] = $_POST['keyword'];
			$set['reply_pic'] = $_POST['reply_pic'];
			$set['title'] = $_POST['title'];
			$set['fxtitle'] = $_POST['fxtitle'];
			$set['fxinfo'] = $_POST['fxinfo'];
			$set['rank_num'] = $_POST['rank_num'];
			$set['intro'] = $_POST['intro'];
			$set['info'] = $_POST['info'];
			$set['is_newtp'] = intval($_POST['is_newtp']);
			$set['is_sms'] = intval($_POST['is_sms']);
			$set['is_attention'] = intval($_POST['is_attention']);
			$set['is_reg'] = intval($_POST['is_reg']);
			$set['is_open'] = intval($_POST['is_open']);
			$set['is_help'] = intval($_POST['is_help']);
			$set['start'] = strtotime($_POST['start']);
			$set['end'] = strtotime($_POST['end']);
			$set['add_time'] = time();
			if (intval($_POST['is_newtp']) == 1) {
				$news_imgurl = $_POST['news_imgurl'];
				$news_title = $_POST['news_title'];
				$news_url = $_POST['news_url'];
				$prize_title = $_POST['prize_title'];
				$prize_imgurl = $_POST['prize_imgurl'];
				$prize_starttime = $_POST['prize_starttime'];
				$prize_stoptime = $_POST['prize_stoptime'];
			}

			if ($help_info) {
				$del_news = M('helping_news')->where(array('token' => $this->token, 'pid' => $id))->delete();
				$del_prize = M('helping_prize')->where(array('token' => $this->token, 'pid' => $id))->delete();
				if (intval($_POST['is_newtp']) == 1) {
					foreach ($news_imgurl as $nk => $nv) {
						$add_news['token'] = $this->token;
						$add_news['pid'] = $id;
						$add_news['imgurl'] = $nv;
						$add_news['title'] = $news_title[$nk];
						$add_news['url'] = $news_url[$nk];
						$id_news = M('helping_news')->add($add_news);
					}
					foreach ($prize_title as $pk => $pv) {
						$add_prize['token'] = $this->token;
						$add_prize['pid'] = $id;
						$add_prize['title'] = $pv;
						$add_prize['imgurl'] = $prize_imgurl[$pk];
						$add_prize['starttime'] = strtotime($prize_starttime[$pk]);
						$add_prize['stoptime'] = strtotime($prize_stoptime[$pk]);
						$add_prize['num'] = $pk + 1;
						$id_prize = M('helping_prize')->add($add_prize);
					}
				}
				$update_helping = M('helping')->where($where)->save($set);
				$this->handleKeyword($id, 'Helping', $this->_post('keyword', 'trim'));
				S($id . 'helping' . $this->token, null);
				S($id . 'helping' . $this->token . 'news', null);
				S($id . 'helping' . $this->token . 'prize', null);
				$this->success('修改成功', U('Helping/index', array('token' => $this->token)));
			} else {
				$id = M('helping')->add($set);
				if (intval($_POST['is_newtp']) == 1) {
					foreach ($news_imgurl as $nk => $nv) {
						$add_news['token'] = $this->token;
						$add_news['pid'] = $id;
						$add_news['imgurl'] = $nv;
						$add_news['title'] = $news_title[$nk];
						$add_news['url'] = $news_url[$nk];
						$id_news = M('helping_news')->add($add_news);
					}
					foreach ($prize_title as $pk => $pv) {
						$add_prize['token'] = $this->token;
						$add_prize['pid'] = $id;
						$add_prize['title'] = $pv;
						$add_prize['imgurl'] = $prize_imgurl[$pk];
						$add_prize['starttime'] = strtotime($prize_starttime[$pk]);
						$add_prize['stoptime'] = strtotime($prize_stoptime[$pk]);
						$add_prize['num'] = $pk + 1;
						$id_prize = M('helping_prize')->add($add_prize);
					}
				}
				$this->handleKeyword($id, 'Helping', $this->_post('keyword', 'trim'));
				$this->success('添加成功', U('Helping/index', array('token' => $this->token)));
			}

		} else {

			$this->assign('start_date', date('Y-m-d', time()));
			$this->assign('end_date', date('Y-m-d', strtotime('+1 month')));
			$this->assign('set', $help_info);
			if ($help_info['is_newtp'] == 1) {
				$news_list = M('helping_news')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();
				$prize_list = M('helping_prize')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();
				$newsnum = count($news_list);
				$prizenum = count($prize_list);
				$this->assign('news_list', $news_list);
				$this->assign('prize_list', $prize_list);
				$this->assign('newsnum', $newsnum);
				$this->assign('prizenum', $prizenum);
			}
			$this->display();
		}
	}

	public function rank() {
		//$this->delagain();
		$id = $this->_get('id', 'intval');
		$helping = M('Helping')->where(array('token' => $this->token, 'id' => $id, 'is_open' => 1))->find();
		$where = array('a.token' => $this->token, 'a.pid' => $id, 'a.is_join2' => 1, 'a.help_count' => array('gt', 0));
		if ($helping['is_reg'] == 1) {
			//$where['b.tel'] = array('neq', "");
		}

		$where_page['a.token'] = $this->token;
		$where_page['a.id'] = $id;
		if (!empty($_GET['search'])) {
			$where['a.wecha_id'] = M('userinfo')->where(array('token' => $this->token, 'tel' => $_GET['search']))->getField('wecha_id');
			$where_page['a.search'] = $_GET['search'];
		}
		$list2 = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel info_tel')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->group('a.id')->order('a.help_count desc,a.share_num desc')->select();

		$count = count($list2);

		//$count = M('Helping_user')->where($where)->count();
		$Page = new Page($count, 15);
		foreach ($where_page as $key => $val) {
			$page->parameter .= "$key=" . urlencode($val) . '&';
		}
		$list = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel,b.portrait')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->order('a.help_count desc,a.share_num desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		//$list = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel,b.portrait')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->group('a.id')->order('a.help_count desc,a.share_num desc')->

		//$list2 = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel info_tel')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->group('a.id')->order('a.help_count desc,a.share_num desc')->select();

		foreach ($list2 as $k => $v) {
			$paiming[$v['id']] = $k + 1;
		}
		foreach ($list as $key => $val) {
			//$user_info = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->find();
			$list[$key]['nickname'] = $val['wechaname']?$val['wechaname']:'匿名';
			$list[$key]['mobile'] = $val['tel'] ? $val['tel'] : '无';
		}

		$this->assign('paiming', $paiming);
		$this->assign('list', $list);
		$this->assign('page', $Page->show());
		$this->assign('firstRow',$Page->firstRow);
		$this->display();
	}

	public function helpinfo() {
		$id = $this->_get('id', 'intval');
		$user = M('Helping_user')->where(array('token' => $this->token, 'id' => $id))->find();
		$where = array('token' => $this->token, 'pid' => intval($user['pid']), 'share_key' => $user['share_key']);
		$count = M('helping_record')->where($where)->count();
		$Page = new Page($count, 15);
		$helps_list = M('helping_record')->where($where)->order('addtime desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($helps_list as $hk => $hv) {
			$helps_list[$hk]['userinfo'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $hv['wecha_id']))->find();
		}
		$user['userinfo'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->find();
		$this->assign('list', $helps_list);
		$this->assign('user', $user);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function del() {
		$id = $this->_get('id', 'intval');
		$keyword = M('Helping')->where(array('token' => $this->token, 'id' => $id))->getField('keyword');
		$this->handleKeyword($id, 'Helping', $keyword, 0, 1);
		S($id . 'helping' . $this->token, null);
		if (M('Helping')->where(array('token' => $this->token, 'id' => $id))->delete()) {
			M('Helping_user')->where(array('token' => $this->token, 'pid' => $id))->delete();
			M('Helping_news')->where(array('token' => $this->token, 'pid' => $id))->delete();
			M('Helping_prize')->where(array('token' => $this->token, 'pid' => $id))->delete();
			$this->success('删除成功');
		}
	}
	public function rank_del() {
		$id = $this->_get('id', 'intval');
		if (M('Helping_user')->where(array('token' => $this->token, 'id' => $id))->delete()) {
			$this->success('删除成功');
		}
	}
	public function excel() {
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=users.xls");
		$id = $this->_get('id', 'intval');
		$arr = array(
			array('en' => 'num', 'cn' => '排名'),
			array('en' => 'nickname', 'cn' => '昵称'),
			array('en' => 'mobile', 'cn' => '手机号码'),
			array('en' => 'share_num', 'cn' => '转发数'),
			array('en' => 'pv', 'cn' => '浏览量'),
			array('en' => 'help_count', 'cn' => '助力值'),
			array('en' => 'add_time', 'cn' => '参与时间'),
		);
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		foreach ($arr as $f) {
			if ($s < $fieldCount - 1) {
				echo iconv('utf-8', 'gbk', $f['cn']) . "\t";
			} else {
				echo iconv('utf-8', 'gbk', $f['cn']) . "\n";
			}
			$s++;
		}
		$helping = M('Helping')->where(array('token' => $this->token, 'id' => $id, 'is_open' => 1))->find();
		$where = array('a.token' => $this->token, 'a.pid' => $id, 'a.is_join2' => 1, 'a.help_count' => array('gt', 0));
		if ($helping['is_reg'] == 1) {
		}
		$sns = M('Helping_user')->alias('a')->field('a.*,b.wechaname,b.truename,b.tel info_tel')->join(' __USERINFO__ b ON a.token = b.token and a.wecha_id = b.wecha_id ')->where($where)->group('a.id')->order('a.help_count desc,a.share_num desc')->select();
		//$sns = M('Helping_user')->where(array('token' => $this->token, 'pid' => $id, 'is_join2' => 1, 'help_count' => array('gt', 0)))->order('help_count desc,share_num desc')->select();
		foreach ($sns as $k => $v) {
			$paiming[$v['id']] = $k + 1;
		}
		foreach ($sns as $key => $val) {
			//$user_info = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->find();

			$sns[$key]['nickname'] = $val['wechaname'] ? $val['wechaname'] : $val['truename'];
			$sns[$key]['nickname'] = $sns[$key]['nickname'] ? $sns[$key]['nickname'] : '匿名';
			$sns[$key]['mobile'] = $val['tel'] ? $val['tel'] : $val['info_tel'];
			$sns[$key]['mobile'] = $sns[$key]['mobile'] ? $sns[$key]['mobile'] : '无';
			$sns[$key]['num'] = $paiming[$val['id']];
		}
		foreach ($sns as $sn) {
			$j = 0;
			foreach ($arr as $field) {
				$fieldValue = $sn[$field['en']];
				switch ($field['en']) {
				default:
					break;
				case 'add_time':
					if ($fieldValue) {
						$fieldValue = date('Y-m-d H:i:s', $fieldValue);
					} else {
						$fieldValue = '';
					}
					break;
				case 'nickname':
					$fieldValue = iconv('utf-8', 'gbk//IGNORE', $fieldValue);
					$fieldValue = str_replace("\"", "", $fieldValue);
					break;
				case 'mobile':
					$fieldValue = iconv('utf-8', 'gbk', $fieldValue);
					break;
				}
				if ($j < $fieldCount - 1) {
					echo $fieldValue . "\t";
				} else {
					echo $fieldValue . "\n";
				}
				$j++;
			}
			$i++;
		}
	}
}
?>