<?php

	class BoostAction extends UserAction
	{
		public $config;
		public $cats;
		public $industyCats;
		public $boost;

		/**
		 * init the game action class
		 * require the game class and set the game cats
		 */
		public function _initialize ()
		{
			parent::_initialize();
			$this->canUseFunction('Boost');
			$this->boost = new boost();
			$this->cats = $this->boost->boostCats();
			//$this->industyCats = $this->game->gameIndustyCats();
	                $this->industyCats[0] = array('name' => '全部');
			ksort($this->industyCats);
//			$this->assign('cats', $this->cats);
			$this->assign('cats', $this->industyCats);
		        $this->staticPath = 'http://s.404.cn';
                        $this->assign('staticPath', $this->staticPath);
		}

		public function index ()
		{
			$config = $this->_toConfig();
			$where = array('token' => $this->token);
			$count = M('Boosts')->where($where)->count();
			$Page = new Page($count, 15);
			$list = M('Boosts')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($list as $k => $v){
		  	       $whdata = array('uid' => $config['uid'], 'boost_id' => $v['boostid'], 'u_boost_id' => $v['id']);
				
				$temptotal= $this->boost->statistics($whdata);
				$list[$k]['people'] = $temptotal['people'];
				$list[$k]['share'] = $temptotal['share'];
				$list[$k]['join'] = $temptotal['join'];
			}
			$wxUser = M('Game_config')->where(array('token' => $this->token))->find();
			$this->assign('wxUser', $wxUser);
			$this->assign('count', $count);
			$this->assign('page', $Page->show());
			$this->assign('list', $list);
			$this->display();
		}

		public function delBoost ()
		{
			$config = $this->_toConfig();
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$thisItem = M('boosts')->where(array('id' => $id, 'token' => $this->token))->find();
			$data['uid'] = $config['uid'];
			$data['boostid'] = $thisItem['boostid'];
			$data['uboostid'] = $id;
			$rt = $this->boost->deleteBoost($data);
			M('boosts')->where(array('id' => $id, 'token' => $this->token))->delete();
			$this->success('删除成功', U('Boost/index'));
		}

		public function boostSet ()
		{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$this->assign('id', $id);
			if ($id) {
				$thisItem = M('boosts')->where(array('id' => $id, 'token' => $this->token))->find();
				$boostid = intval($thisItem['boostid']);
			} else {
				$boostid = intval($_GET['boostid']);
			}
			$config = $this->_toConfig();
			$thisBoost = $this->boost->getBoost(intval($boostid));
			$boostSet = $this->boost->boostSet($config['uid'], $thisBoost['id'], $id, $config['key']);
			if ($boostSet) {
				$thisItem['rule'] = htmlspecialchars_decode(base64_decode($boostSet['rule']));
				$thisItem['awards'] = htmlspecialchars_decode(base64_decode($boostSet['awards']));
				$thisItem['awards_value'] = unserialize($boostSet['awards_value']);
				$thisItem['boostlimit'] = unserialize($boostSet['boostlimit']);
				$thisItem['boostval'] = unserialize($boostSet['boostval']);
				$thisItem['standard'] = unserialize($boostSet['standard']);
				$thisItem['regions'] = json_decode($boostSet['regions'],true);
				$thisItem['attention_url'] = $boostSet['attention_url'];
				$thisItem['is_phone'] = $boostSet['is_phone'];
				$thisItem['is_attention'] = $boostSet['is_attention'];
				$thisItem['ishelp_attention'] = $boostSet['ishelp_attention'];
				$thisItem['start_time'] = $boostSet['start_time'];
				$thisItem['end_time'] = $boostSet['end_time'];
				$thisItem['star_bg'] = $boostSet['star_bg'];
				$thisItem['star_btn'] = $boostSet['star_btn'];
				$thisItem['limit_num'] = $boostSet['limit_num'];
				$thisItem['limit_fx'] = $boostSet['limit_fx'];
				$thisItem['rank_limit'] = $boostSet['rank_limit'];
				$thisItem['wechat_set'] = $boostSet['wechat_set'];
				$thisItem['join_set'] = $boostSet['join_set'];
				$thisItem['office_value'] = $boostSet['office_value'];
				$thisItem['playtop_set'] = $boostSet['playtop_set'];
			$thisItem["mainprogress_set"] = $boostSet["mainprogress_set"];
			$thisItem["mainboostinfo_set"] = $boostSet["mainboostinfo_set"];
			$thisItem["maintimearea_set"] = $boostSet["maintimearea_set"];
				$thisItem['bg_music'] = $boostSet['bg_music'];
				$thisItem['bg_music_value'] = $boostSet['bg_music_value'];
				$thisItem['unit'] = $boostSet['unit'];
				$thisItem['boost_name'] = $boostSet['boost_name'];
				$thisItem['boost_area'] = $boostSet['boost_area'];
			$thisItem["need_tel"] = $boostSet["need_tel"];
			}

			$selfs = $this->boost->boostSelfs($thisBoost['id'], $config['uid'], $id, $config['key']);
			if (IS_POST) {
				$wxtype = M('wxuser')->field("`winxintype`,`qr`")->where(array('token' => $this->token))->find();
				if ($wxtype['winxintype'] == 3) {
					$boostqr = $this->getQrcode($this->_post('keyword'));
				} else {
					if($wxtype['is_syn'] != 2){
						if (empty($wxtype['qr'])) {
							$this->error('请在编辑公众号里，上传您的二维码', U('Index/index', array('id' => session('wxid'))));
						} else {
							$boostqr = $wxtype['qr'];
						}
					}
				}
				$data = array(
					'token'          => $this->token,
					'title'          => $this->_post('title'),
					'intro'          => $this->_post('intro'),
					'keyword'        => $this->_post('keyword'),
					'picurl'         => $this->_post('picurl'),
					'limit_num'      => $this->_post('limit_num'),
					'star_bg'        => $this->_post('star_bg'),
					'star_btn'       => $this->_post('star_btn'),
					'start_time'     => strtotime($this->_post('start_time')),
					'end_time'       => strtotime($this->_post('end_time')),
					'time'           => time(),
					'boostid'         => $thisBoost['id'],
					'share_callback' => $this->_post('share_callback'),
					'share_value'    => isset($_POST['share']) ? serialize($_POST['share']) : '',
					'is_share'       => $_POST['is_share']
				);

				$selfValues = array();
				$jsonStr = '{}';
				if ($selfs) {
				        $jsonData = array();
					foreach ($selfs as $s) {
						$selfValues['self_' . $s['id']] = is_array($this->_post('self_' . $s['id'])) ? serialize($this->_post('self_' . $s['id'])) : $this->_post('self_' . $s['id']);
						$jsonData['self_' . $s['id']] = $this->_post('self_' . $s['id']);
					}
					$jsonStr = json_encode($jsonData);
				}

				$data['selfinfo'] = $jsonStr;

				if (!isset($_POST['id'])) {
					$userboostid = M('Boosts')->add($data);
				} else {
					$userboostid = intval($_POST['id']);
					M('Boosts')->where(array('id' => $userboostid))->save($data);
				}

				$boostSet['title'] = $this->_post('title');
				$boostSet['intro'] = $this->_post('intro');
				$boostSet['picurl'] = $this->_post('picurl');
				$boostSet['star_btn'] = $_POST['star_btn'];
				$boostSet['star_btn_coordinate'] = isset($_POST['star_btn_coordinate']) ? serialize($_POST['star_btn_coordinate']) : '';
				$boostSet['star_bg'] = $_POST['star_bg'];
				$boostSet['rule'] = $_POST['boostSet_rule'];
				$boostSet['awards'] = $_POST['boostSet_awards'];
				$boostSet['unit'] = $this->_post('unit');
				$awardsValue = '';
				if (isset($_POST['boostSet_awards_value'])) {
					$_POST['boostSet_awards_value']['awards_start_time'] = strtotime($_POST['boostSet_awards_value']['awards_start_time']);
					$_POST['boostSet_awards_value']['awards_end_time'] = strtotime($_POST['boostSet_awards_value']['awards_end_time']);

					$awardsValue = serialize($_POST['boostSet_awards_value']);
				}

				$boostSet['awards_value'] = $awardsValue;
				$boostSet['is_phone'] = $this->_post('is_phone');
				$boostSet['is_attention'] = $this->_post('is_attention');
				$boostSet['ishelp_attention'] = $this->_post('ishelp_attention');
				$boostSet['limit_num'] = $_POST['limit_num'];
				$boostSet['limit_fx'] = $_POST['limit_fx'];
			$boostSet["need_tel"] = $_POST["need_tel"];
				$boostSet['start_time'] = strtotime($_POST['start_time']);
				$boostSet['end_time'] = strtotime($_POST['end_time']);
				$boostSet['boostqr'] = $boostqr;
				$boostSet['keyword'] = $this->_post('keyword');
				$boostSet['share_callback'] = $this->_post('share_callback');
				$boostSet['share_value'] = isset($_POST['share']) ? serialize($_POST['share']) : '';
				$boostSet['boostlimit'] = !empty($_POST['boostlimit']) ? serialize($_POST['boostlimit']) : '';
				$boostSet['boostval'] = !empty($_POST['boostval']) ? serialize($_POST['boostval']) : '';
			foreach ($_POST["regions"]["values"] as $key => $values ) {
				if ($values["province"] == "--请选择省份--") {
					unset($_POST["regions"]["values"][$key]);
				}
			}
				$_POST['regions']['values'] = array_values($_POST['regions']['values']);
				$boostSet['regions'] = json_encode($_POST['regions']);
				$boostSet['standard'] = !empty($_POST['standard']) ? serialize($_POST['standard']) : '';
				$boostSet['is_share'] = $_POST['is_share'];
				$boostSet['bg_music'] = $_POST['bg_music'];
				$boostSet['bg_music_value'] = isset($_POST['bg_music_value']) ? serialize($_POST['bg_music_value']) : '';
				$boostSet['join_set'] = isset($_POST['join_set']) ? serialize($_POST['join_set']) : '';
				$boostSet['wechat_set'] = isset($_POST['wechat_set']) ? serialize($_POST['wechat_set']) : '';
				$boostSet['rank_limit'] = intval($_POST['rank_limit']);
				$boostSet['office_value'] = isset($_POST['office_value']) ? serialize($_POST['office_value']) : '';
				$boostSet['playtop_set'] = isset($_POST['playtop_set']) ? serialize($_POST['playtop_set']) : '';
			$boostSet["mainprogress_set"] = (isset($_POST["mainprogress_set"]) ? serialize($_POST["mainprogress_set"]) : "");
			$boostSet["mainboostinfo_set"] = (isset($_POST["mainboostinfo_set"]) ? serialize($_POST["mainboostinfo_set"]) : "");
			$boostSet["maintimearea_set"] = (isset($_POST["maintimearea_set"]) ? serialize($_POST["maintimearea_set"]) : "");
				$boostSet['boost_name'] = $_POST['boostName'];
				$boostSet['boost_area'] = $_POST['boostArea'];

				$home_set = M('Wxuser')->field('id,hurl')->where(array('token' => $this->token))->find();
				if ($boostSet['is_attention'] == 1 && (!isset($home_set['hurl']) || $home_set['hurl'] == '')) {
					$this->error('需要关注，请先设置快捷关注配置中的一键关注链接', U('Boost/index'));
				}
				$boostSet['attention_url'] = $home_set['hurl'];
				$this->boost->boostSet($config['uid'], $thisBoost['id'], $userboostid, $config['key'], $boostSet, 'boost');
				$this->handleKeyword($userboostid, 'Boost', $data['keyword'], $precisions = 0, $delete = 0);
				$this->boost->boostSelfSet($config['uid'], $thisBoost['id'], $userboostid, 'boost', $config['key'], $selfValues);

				$this->success('设置成功', U('Boost/index'));
				//
			} else {
				$url = $_SERVER['SERVER_NAME'];
				$this->assign('url', $url);
				$this->assign('thisBoost', $thisBoost);
				if ($this->_get('boostid')) {
					$boostid = intval($this->_get('boostid'));
				} else {
					$boostid = intval($thisBoost['id']);
				}

				$this->assign('boostid', $boostid);

				if (!$id) {
					if ($boostid == 2) {
						$boostKeyword = '筹钱';
					} elseif ($boostid == 3) {
						$boostKeyword = '送粽子';
					} else {
						$boostKeyword = $thisBoost['keyword'];
					}
					$thisItem = array();
					$thisItem['title'] = $thisBoost['title'];
					$thisItem['intro'] = $thisBoost['intro'];
					$thisItem['keyword'] = $boostKeyword;
				$thisItem["unit"] = $thisBoost["unit"];
				$thisItem["boost_name"] = $thisBoost["boost_name"];
					$thisItem['rule'] = '活动结束后，会根据排行榜成绩进行派奖。';
					$thisItem['picurl'] = $thisBoost['picurl'];
					$thisItem['is_release'] = 0;
					$thisItem['boostlimit'] = array('type' => 1, 'times' => 1);
					$thisItem['boostval'] = array('type' => 0, 'number' => 1, 'minnumber' => 1, 'maxnumber' => 5);
					$thisItem['standard'] = array('type' => 0, 'value' => 0);
					$thisItem['regions'] = array('type' => 0, 'values' =>'' );
				$thisItem["join_set"] = $thisBoost["join_set"];
				$thisItem["playtop_set"] = $thisBoost["playtop_set"];
				$thisItem["maintimearea_set"] = $thisBoost["maintimearea_set"];
				$thisItem["mainprogress_set"] = $thisBoost["mainprogress_set"];
				$thisItem["mainboostinfo_set"] = $thisBoost["mainboostinfo_set"];
					$thisItem['awards_value'] = unserialize($boostSet['awards_value']);
				$thisItem["need_tel"] = 1;
				}
		  	        else if ($selfs) {
						$selfValues = json_decode($thisItem['selfinfo'], 1);

						foreach ($selfs as $i => $s) {
							if (is_array($selfValues['self_' . $s['id']])) {
								$selfs[$i]['value'] = $selfValues['self_' . $s['id']]['value'];

								// 元素自定义数值
								if ($selfs[$i]['value']) {
									$selfs[$i]['defaultvalue'] = $selfs[$i]['value'];
								}

								// 元素自定义宽度
								if (isset($selfValues['self_' . $s['id']]['width'])) {
									$selfs[$i]['width'] = $selfValues['self_' . $s['id']]['width'];
								}

								// 元素自定义高度
								if (isset($selfValues['self_' . $s['id']]['height'])) {
									$selfs[$i]['height'] = $selfValues['self_' . $s['id']]['height'];
								}

								// 元素横坐标
								if (isset($selfValues['self_' . $s['id']]['x'])) {
									$selfs[$i]['x'] = $selfValues['self_' . $s['id']]['x'];
								}

								// 元素纵坐标
								if (isset($selfValues['self_' . $s['id']]['y'])) {
									$selfs[$i]['y'] = $selfValues['self_' . $s['id']]['y'];
								}

								// 是否显示
								if (isset($selfValues['self_' . $s['id']]['visible'])) {
									$selfs[$i]['visible'] = $selfValues['self_' . $s['id']]['visible'];
								}

								// 自定义元素
								if (isset($selfValues['self_' . $s['id']]['element_attribute'])) {
									$selfs[$i]['element_attribute'] = $selfValues['self_' . $s['id']]['element_attribute'];
								}
							} else {
								$selfs[$i]['value'] = $selfValues['self_' . $s['id']];
								if ($selfs[$i]['value']) {
									$selfs[$i]['defaultvalue'] = $selfs[$i]['value'];
								}
							}
						
					}
				}
				$thisItem['rule'] = stripslashes($thisItem['rule']);
				$thisItem['awards'] = stripslashes($thisItem['awards']);

				//判断是否服务号
				$account_type = M('Wxuser')->where(array('token' => $this->token))->find();
				$serverType = 0;
				if ($account_type['winxintype'] > 2) {
					$serverType = 1;
					$share_options[1] = "发红包";
					$share_options[2] = "发卡券";
				}

				//分享回调选项
				$share_options[3] = "送积分";
				$share_options[4] = "送次数";

				//微信卡券
				$coupon = M('Member_card_coupon')->where(array('token' => $this->token, 'is_delete' => 0, 'is_weixin' => 1))->select();

				//获取支付配置
				$pay_config = M('Alipay_config')->where(array('token' => $this->token))->find();
				$pay_info = unserialize($pay_config['info']);

				//支付功能是否开启
				$is_open = $pay_info['is_open'];

				//微信支付是否开启
				$wx_open = $pay_info['weixin']['open'];

				//判断微信支付证书
				$wx_cert = M('wxcert')->where(array('token' => $this->token))->find();
				if (empty($wx_cert) || !$is_open || !$wx_open) {
					$this->assign('is_wxopen', 0);
				} else {
					$this->assign('is_wxopen', 1);
				}

				$this->assign('coupon', $coupon);
				$this->assign('share_options', $share_options);

				if ($this->_get('boostid') == 79) {
					$thisItem['star_bg'] = "/tpl/static/game/star_sd.png";
					$thisItem['star_btn'] = "8";
				}

				$thisItem['share_value'] = unserialize($thisItem['share_value']);
				$thisItem['star_btn_coordinate'] = unserialize($thisItem['star_btn_coordinate']);
				$thisItem['bg_music_value'] = unserialize($thisItem['bg_music_value']);
				$thisItem['join_set'] = unserialize($thisItem['join_set']);
				$thisItem['wechat_set'] = unserialize($thisItem['wechat_set']);
				$thisItem['office_value'] = unserialize($thisItem['office_value']);
				$thisItem['playtop_set'] = unserialize($thisItem['playtop_set']);
			$thisItem["maintimearea_set"] = unserialize($thisItem["maintimearea_set"]);
			$thisItem["mainprogress_set"] = unserialize($thisItem["mainprogress_set"]);
			$thisItem["mainboostinfo_set"] = unserialize($thisItem["mainboostinfo_set"]);
				$this->assign('selfs', $selfs);
				$this->assign('info', $thisItem);
				$this->assign('awardsValues', $thisItem['awards_value']['awards_values']);
				$this->assign('serverType', $serverType);
				$this->display();
			}
		}

		public function boostDelete ()
		{

		}

		public function boostResults ()
		{

		}

		/**
		 * 活动库
		 */
		public function boostLibrary ()
		{
			$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		        $boosts = $this->boost->boostList($catid, "", 2, 2);

			// 分页计算逻辑
			$count = count($boosts);
			$ps = $this->_get('p', 'intval');
			if ($ps < 1) {
				$ps = 1;
			}

			// 每页显示的信息数
			$num = 20;

			//当前页数显示内容
			if ($this->_get('p') > 0) {
				$star = ($this->_get('p') - 1) * $num;
				$boostList = array_slice($boosts, $star, $num);
			} else {
				$boostList = array_slice($boosts, 0, $num);
			}

			$Page = new Page($count, 20);
			$this->assign('page', $Page->show());

			$this->assign('boosts', $boostList);
			$this->assign('catid', $catid);
			$this->display();
		}

		//和游戏模块公用对接梅花用户信息
		function _toConfig ()
		{
			$config = M('Game_config')->where(array('token' => $this->token))->find();
			if (!$config) {
				$this->success('请先配置相关信息', U('Game/config'));
				exit();
			} else {
				return $config;
			}
		}


		function record ()
		{
			$uBoostId = $this->_get('id', 'intval');
			$thisItem = M('boosts')->where(array('id' => $uBoostId, 'token' => $this->token))->find();
			$config = $this->_toConfig();
			$data['uid'] = $config['uid'];
			$data['bid'] = $uBoostId;
			$list = $this->boost->RankingList($data);
			// 分页计算逻辑
			$count = count($list);
			$ps = $this->_get('p', 'intval');
			if ($ps < 1) {
				$ps = 1;
			}

			$num = 15;
			//页数
			if (is_int($count / $num)) {
				$boostPage = $count / $num;
			} else {
				$boostPage = floor($count / $num + 1);
			}
			//当前页数显示内容
			if ($this->_get('p') > 0) {
				$star = ($this->_get('p') - 1) * $num;
				$dataList = array_slice($list, $star, $num);
			} else {
				$dataList = array_slice($list, 0, $num);
			}

			if ($boostPage > 9) {
				if ($ps < 5) {
					$pageStar = 1;
					$pageEnd = 10;
				} else {
					$pageStar = $ps - 4;
					$pageEnd = $ps + 5;
				}
				if ($pageEnd > $boostPage) {
					$pageEnd = $boostPage;
					$pageStar = $pageEnd - 9;
				}
			} else {
				$pageStar = 1;
				$pageEnd = $boostPage;
			}
			$this->assign('id', $uBoostId);
			$this->assign('ps', $ps);
			$this->assign('pageStar', $pageStar);
			$this->assign('pageEnd', $pageEnd);

			$this->assign('records', $dataList);
			$this->assign('boostPage', $boostPage);

			$this->display();
		}

		function record_del ()
		{
			$wecha_id = $this->_get('wecha');
			$uid = $this->_get('uid');
			$gid = $this->_get('rid');
			$data['wecha_id'] = $wecha_id;
			$data['uid'] = $uid;
			$data['gid'] = $gid;
			$this->boost->scoredel($data);
			M('Boost_records')->where(array('game' => $gid, 'wecha_id' => $wecha_id, 'token' => $this->token))->delete();
			$this->success('删除成功', U('Boost/record', array('token' => $this->token, 'id' => $this->_get('rid', 'intval'))));

		}

		// 活动发布
		function release ()
		{
			$id = $this->_get('id');
			$config = $this->_toConfig();
		        $boost = M('boosts')->where(array('id' => $id, 'token' => $this->token))->find();

			$now = time();

			if (!empty($boost)) {
				if ($boost['is_release'] == 1) {
				$this->error('活动已发布，请勿重复发布！', U('Boost/index', array('token' => $this->token)));
				}  elseif ($boost['end_time'] <= $now) {
						$this->error('活动结束时间应大于当前时间，暂不能发布！', U('Boost/index', array('token' => $this->token)));
				} else {
					$uid = $config['uid'];
					$uBoostId = $id;
					$boostId = $boost['boostid'];

					try {
						// 游戏平台端
						$status = $this->boost->release($uid, $boostId, $uBoostId);

						if ($status['status']) {
							// 营销端
								M('boosts')->where(array('id' => $id, 'token' => $this->token))->save(array('is_release' => 1));
								$this->error('活动发布成功！', U('Boost/index', array('token' => $this->token)));
							} else {
								$this->error($status['msg'], U('Boost/index', array('token' => $this->token)));
							}
						} catch (Exception $e) {
							$this->error('活动：' . $boost['title'] . ' 发布失败', U('Boost/index', array('token' => $this->token)));
					}
				}
			} else {
			$this->error('活动不存在', U('Boost/index', array('token' => $this->token)));
			}

		}

		/**
		 * 兑换管理逻辑
		 */
		function convert ()
		{
			$uBoostId = $this->_get('id', 'intval');
		$thisItem = M('boosts')->where(array('id' => $uBoostId, 'token' => $this->token))->find();

			// 判断活动是否结束
			if ($thisItem['end_time'] < time()) {
				$this->assign('finish', 1);
				$this->assign('id', $uBoostId);

				$boostId = intval($thisItem['boostid']);
				$this->assign('boostId', $boostId);

				$config = $this->_toConfig();
				$uid = $config['uid'];
				$this->assign('uid', $uid);
				$this->assign('key', $config['key']);

				$thisBoost = $this->boost->getBoost(intval($boostId));
				$boostSet = $this->boost->boostSet($config['uid'], $thisBoost['id'], $uBoostId, $config['key']);

				// 获取活动奖品信息
				$awardsData = unserialize($boostSet['awards_value']);
				$awardsValue = $awardsData['awards_values'];
				$awardsCount = $awardsData['awards_count'];

				$this->assign('awardsValue', $awardsValue);
				$this->assign('awardsCount', $awardsCount);
			$convertCodeData = D("Boost_convert")->getConvertUseData($uid, $uBoostId);
			if (empty($convertCodeData) && ($boostSet["end_time"] < time())) {
				$convertEnd = 0;

				for ($i = 0; $i < $awardsCount; $i++) {
					$convertEnd += $awardsValue[$i]["awards_num"];
				}

				$data = array("uid" => $uid, "boost_id" => $boostId, "u_boost_id" => $uBoostId, "convert_end" => $convertEnd);
				$convertCodeStatus = $this->boost->getConvertDataFromCms($data);

				if ($convertCodeStatus["status"] == 200) {
				$convertCodeData = D('Boost_convert')->getConvertUseData($uid, $uBoostId);
				}
			}

				$this->assign('codeData', $convertCodeData);
			$where = array('uid' => $uid, 'u_boost_id' => $uBoostId);
				$searchType = 0;
				$useType = 2;
				$awardsType = 0;
				$keyword = '';
				if (IS_POST) {
					$searchType = $this->_POST('type', 'intval');
					$useType = $this->_POST('use_type', 'intval');
					$awardsType = $this->_POST('awards_type', 'intval');
					$keyword = htmlspecialchars(trim($_POST['keyword']));
				}

				$this->assign('type', $searchType);
				$this->assign('keyword', $keyword);
				$this->assign('useType', $useType);
				$this->assign('awardsType', $awardsType);

				if ($useType != 2) {
					$where['is_use'] = $useType;
				}

				if ($awardsType != 0) {
					$where['awards_level'] = $awardsType;
				}

				if ($searchType == 0 && !empty($keyword)) {
					$where['convert_code'] = $keyword;
				}

				if ($searchType == 1 && !empty($keyword)) {
				$fansIds = $this->boost->getFansIdsByName(array('name' => $keyword));
					if ($fansIds) {
					$where['fans_id'] = array('in', $fansIds);
					} else {
						$this->assign('empty', 1);

						$this->display();

						return false;
					}
				}

				$convertData = D('Boost_convert')->where($where)->order('awards_level ASC, id ASC')->select();

				if (!empty($convertData)) {
				$fansIdData = array('fansId' => array());

					foreach ($convertData as $convert) {
						array_push($fansIdData['fansId'], $convert['fans_id']);
					}

					$fansIdData['fansId'] = implode(', ', $fansIdData['fansId']);

					// 获取粉丝信息
					$fansData = $this->boost->getUserInfo($fansIdData);
					$fansData = $this->valtokey($fansData, 'id');

					// 获取粉丝成绩信息
					$data = $fansIdData;
					$data['uid'] = $uid;
					$data['boost_id'] = $boostId;
					$data['u_boost_id'] = $uBoostId;
					$scoreData = $this->boost->getUserScoreData($data);
					$scoreData = $this->valtokey($scoreData, 'fans_id');

					// 分页计算逻辑
					$count = count($convertData);
					$ps = $this->_get('p', 'intval');
					if ($ps < 1) {
						$ps = 1;
					}

					// 每页显示的信息数
					$num = 10;

					//页数
					if (is_int($count / $num)) {
						$boostPage = $count / $num;
					} else {
						$boostPage = floor($count / $num + 1);
					}

					//当前页数显示内容
					if ($this->_get('p') > 0) {
						$star = ($this->_get('p') - 1) * $num;
						$dataList = array_slice($convertData, $star, $num);
					} else {
						$dataList = array_slice($convertData, 0, $num);
					}

					// 页面底部页码显示逻辑
					if ($boostPage > 9) {
						if ($ps < 5) {
							$pageStar = 1;
							$pageEnd = 10;
						} else {
							$pageStar = $ps - 4;
							$pageEnd = $ps + 5;
						}
						if ($pageEnd > $boostPage) {
							$pageEnd = $boostPage;
							$pageStar = $pageEnd - 9;
						}
					} else {
						$pageStar = 1;
						$pageEnd = $boostPage;
					}

					$this->assign('empty', 0);

					$this->assign('converts', $dataList);
					$this->assign('fans', $fansData);
					$this->assign('scores', $scoreData);

					$this->assign('ps', $ps);
					$this->assign('num', $num);
					$this->assign('pageStar', $pageStar);
					$this->assign('pageEnd', $pageEnd);
					$this->assign('boostPage', $boostPage);
				} else {
					$this->assign('empty', 1);
				}
			} else {
				$this->assign('finish', 0);
			}

			$this->display();
		}

		/**
		 * @throws PHPExcel_Exception
		 *
		 * 导出数据
		 */
		public function exportConvert ()
		{
			$uid = $this->_get('uid', 'intval');
			$boostId = $this->_get('boost_id', 'intval');
			$uBoostId = $this->_get('id', 'intval');
			$key = $_GET['key'];
			$searchType = $this->_get('type', 'intval');
			$useType = $this->_get('use_type', 'intval');
			$awardsType = $this->_get('awards_type', 'intval');
			$keyword = htmlspecialchars(trim($_GET['keyword']));

			$boostSet = $this->boost->boostSet($uid, $boostId, $uBoostId, $key);

			// 获取活动奖品信息
			$awardsData = unserialize($boostSet['awards_value']);
			$awardsValue = $awardsData['awards_values'];
		$where = array('uid' => $uid, 'u_boost_id' => $uBoostId);
			if ($useType != 2) {
				$where['is_use'] = $useType;
			}

			if ($awardsType != 0) {
				$where['awards_level'] = $awardsType;
			}

			if ($searchType == 0 && !empty($keyword)) {
				$where['convert_code'] = $keyword;
			}

			if ($searchType == 1 && !empty($keyword)) {
			$fansIds = $this->boost->getFansIdsByName(array('name' => $keyword));

			$where['fans_id'] = array('in', $fansIds);
			}

			$convertData = D('Boost_convert')->where($where)->select();

		$fansIdData = array('fansId' => array());

			foreach ($convertData as $convert) {
				array_push($fansIdData['fansId'], $convert['fans_id']);
			}

			$fansIdData['fansId'] = implode(', ', $fansIdData['fansId']);

			// 获取粉丝信息
			$fansData = $this->boost->getUserInfo($fansIdData);
			$fansData = $this->valtokey($fansData, 'id');

			// 获取粉丝成绩信息
			$data = $fansIdData;
			$data['uid'] = $uid;
			$data['boost_id'] = $boostId;
			$data['u_boost_id'] = $uBoostId;
			$scoreData = $this->boost->getUserScoreData($data);
			$scoreData = $this->valtokey($scoreData, 'fans_id');

			// 开始设置excel
			import('@.ORG.phpexcel.PHPExcel');
			$objExcel = new PHPExcel();
			$objProps = $objExcel->getProperties();
			// 设置文档基本属性
			$objProps->setCreator('兑换管理信息');
			$objProps->setTitle('兑换管理信息');
			$objProps->setSubject('兑换管理信息');
			$objProps->setDescription('兑换管理信息');

			// 设置当前的sheet
			$objExcel->setActiveSheetIndex(0);
			$objActSheet = $objExcel->getActiveSheet();

			// 设置sheet名称
			$objActSheet->setTitle('兑换管理信息');

			// 合并单元格
			$objActSheet->mergeCells('A1:B1');
		$objActSheet->setCellValue("A1", "参与者信息");
			// 开始填充头部
		$objActSheet->setCellValue("C1", "电话号码");
		$objActSheet->setCellValue("D1", "获奖信息");
		$objActSheet->setCellValue("E1", "兑换码");
		$objActSheet->setCellValue("F1", "兑换状态");
		$objActSheet->setCellValue("G1", "兑换时间");

			// 设置头部格式
			$objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		        $objActSheet->getStyle("G1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		        $objActSheet->getStyle("G1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			// 设置头部文字样式
			$objActSheet->getStyle('A1')->getFont()->setBold(true);
			$objActSheet->getStyle('C1')->getFont()->setBold(true);
			$objActSheet->getStyle('D1')->getFont()->setBold(true);
			$objActSheet->getStyle('E1')->getFont()->setBold(true);
			$objActSheet->getStyle('F1')->getFont()->setBold(true);
		$objActSheet->getStyle("G1")->getFont()->setBold(true);
			$objActSheet->getColumnDimension('A1')->setAutoSize(true);
			$objActSheet->getColumnDimension('C1')->setAutoSize(true);
			$objActSheet->getColumnDimension('D1')->setAutoSize(true);
			$objActSheet->getColumnDimension('E1')->setAutoSize(true);
			$objActSheet->getColumnDimension('F1')->setAutoSize(true);
		$objActSheet->getColumnDimension("G1")->setAutoSize(true);
			$objActSheet->getColumnDimension('A')->setWidth(8);
			$objActSheet->getColumnDimension('B')->setWidth(20);
		$objActSheet->getColumnDimension("C")->setWidth(20);
		$objActSheet->getColumnDimension("D")->setWidth(30);
		$objActSheet->getColumnDimension("E")->setWidth(22);
		$objActSheet->getColumnDimension("F")->setWidth(17);
		$objActSheet->getColumnDimension("G")->setWidth(22);
			$i = 2;
			foreach ($convertData as $key => $convert) {
				$start = $i;
				$end = $i + 1;
				$objActSheet->mergeCells('A' . $start . ':A' . $end);
				$objActSheet->mergeCells('C' . $start . ':C' . $end);
				$objActSheet->mergeCells('D' . $start . ':D' . $end);
				$objActSheet->mergeCells('E' . $start . ':E' . $end);
				$objActSheet->mergeCells('F' . $start . ':F' . $end);
				$objActSheet->mergeCells('G' . $start . ':G' . $end);

				$rank = $key + 1;
				$objActSheet->setCellValue('A' . $start, '#' . $rank);
				//				$objActSheet->setCellValue('B' . $start, $fansData[$convert['fans_id']]['portrait']);
				$objActSheet->setCellValue('B' . $start, $fansData[$convert['fans_id']]['wechaname']);
				$objActSheet->setCellValue('B' . $end, '被助力' . $scoreData[$convert['fans_id']]['people'] . '次，' . $scoreData[$convert['fans_id']]['score'] . '分');
			        $objActSheet->setCellValue("C" . $start, $fansData[$convert["fans_id"]]["phone"]);
				$objActSheet->setCellValue('D' . $start, $awardsValue[$convert['awards_level'] - 1]['awards_type'] . '：' . $awardsValue[$convert['awards_level'] - 1]['awards_name']);
				$objActSheet->setCellValue('E' . $start, 'NO. ' . $convert['convert_code']);
				$objActSheet->setCellValue('F' . $start, $convert['is_use'] == 0 ? '未兑换' : '已兑换');
				$objActSheet->setCellValue('G' . $start, $convert['is_use'] == 0 ? '——' : $convert['convert_time']);

				// 设置数据对齐方式
				$objActSheet->getStyle('A' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('C' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('D' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('E' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('F' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle("G" . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('A' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('C' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('D' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('E' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('F' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle("G" . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$i += 2;
			}


			//输出
			$objWriter = new PHPExcel_Writer_Excel5($objExcel);
			ob_end_clean();
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
			header("Content-Type:application/force-download");
			header("Content-Type:application/vnd.ms-execl");
			header("Content-Type:application/octet-stream");
			header("Content-Type:application/download");
			header('Content-Disposition:attachment;filename="助力兑换管理.xls"');
			header("Content-Transfer-Encoding:binary");
			$objWriter->save('php://output');
			exit();

		}

		/**
		 * @param $convertId
		 * @param $openId
		 * @param $totalMoney
		 *
		 * @return bool
		 */
		private function sendRedPacket ($convertId, $openId, $totalMoney)
		{
		$data = array('status' => false, 'msg' => '');
		$convertData = D('boost_convert')->where(array('id' => $convertId))->find();
			$leftMoney = $totalMoney - $convertData['has_send'];

			if ($leftMoney > 0) {
				$needMore = false;
				$sendMoney = $leftMoney;

				if ($sendMoney > 200) {
					$sendMoney = 200;
					$needMore = true;
				}

				$has_send = $convertData['has_send'] + $sendMoney;

				$options = $sendMoney . '#' . $sendMoney;

				$wechaSend = new wechatSend();
				$sendStatus = $wechaSend->sendRedPacket($this->token, $openId, $options);
				if ($sendStatus['status']) {
					$data['status'] = true;
				D('boost_convert')->where(array('id' => $convertId))->save(array('has_send' => $has_send));

					if ($needMore) {
						$data = $this->sendRedPacket($convertId, $openId, $totalMoney);
					}
				} else {
					$data['msg'] = $sendStatus['msg'];
				}
			} else {
				$data['status'] = true;
			}

			return $data;
		}

		/**
		 * 兑换
		 */
     public function useConvert ()
        {
            $id = $this->_get('id', 'intval');
            $convertId = $this->_get('convert_id', 'intval');
            $convertCate = $this->_get('convert_cate', 'intval');
            $errorMsg = '';

        $convertData = D('Boost_convert')->where(array('id' => $convertId))->find();
            $isUse = $convertData['is_use'];

            if (!$isUse) {
                $goConvert = true;
                if ($convertCate == 1) {
                    $convertCounts = $_GET['convert_counts'];

                $data = array('fans_id' => $convertData['fans_id'], 'token' => $this->token);
                    $openIdData = $this->boost->getTokenFansInfo($data);
                    $openId = $openIdData['wecha_id'];

                    $sendStatus = $this->sendRedPacket($convertId, $openId, $convertCounts);
                    $goConvert = $sendStatus['status'];
                    $errorMsg = $sendStatus['msg'];
                } elseif ($convertCate == 2) {
                    $cardId = $_GET['card_id'];

                    $data = array('fans_id' => $convertData['fans_id'], 'token' => $this->token);
                    $openIdData = $this->boost->getTokenFansInfo($data);
                    $openId = $openIdData['wecha_id'];

                    $wechaSend = new wechatSend();
                $sendStatus = $wechaSend->sendCard($this->token, $openId, $cardId);
                $goConvert = $sendStatus["status"];
                $errorMsg = $sendStatus["msg"];
                    
                } elseif ($convertCate == 3) {
                    $points = $_GET['points'];

                        $data = array('fans_id' => $convertData['fans_id'], 'token' => $this->token);
                    $openIdData = $this->boost->getTokenFansInfo($data);
                    $openId = $openIdData['wecha_id'];

                    $wechaSend = new wechatSend();
                    if (!$wechaSend->sendScore($this->token, $openId, $points)) {
                        $goConvert = false;
                    }
                }

                if ($goConvert) {
                if (D('Boost_convert')->where(array('id' => $convertId))->save(array('is_use' => 1, 'convert_time' => date('Y-m-d H:i:s', time())))) {
                    $this->success('兑换成功', U('Boost/convert', array('id' => $id, 'token' => $this->token)));
                    } else {
                    $this->error('兑换失败', U('Boost/convert', array('id' => $id, 'token' => $this->token)));
                    }
                } else {
                $this->error('兑换失败 ' . $errorMsg, U('Boost/convert', array('id' => $id, 'token' => $this->token)));
                }
        
            } else {
            $this->error('兑换失败', U('Boost/convert', array('id' => $id, 'token' => $this->token)));
            }
        }
        
   public function qrConvert()
	{
		$url = $_POST["url"];

		if (!empty($url)) {
			$url = htmlspecialchars_decode($url);
			header("Location:" . $url);
			exit();
		}
		else {
			$this->display();
		}
	}

		public function fansPrize ()
		{
		$fansPrizeList = array();
			$uBoostId = $this->_get('id', 'intval');
			$fansId = $this->_get('fans_id', 'intval');

			$thisItem = M('boosts')->where(array('id' => $uBoostId, 'token' => $this->token))->find();

			if (!empty($thisItem)) {
				$boostId = $thisItem['boostid'];

				$config = $this->_toConfig();
				$uid = $config['uid'];
			$data = array('uid' => $uid, 'boost_id' => $boostId, 'u_boost_id' => $uBoostId, 'fans_id' => $fansId);
				$fansPrizeList = $this->boost->getUserPrizeList($data);
			}

			$this->assign('id', $uBoostId);
			$this->assign('fansId', $fansId);
			$this->assign('prizeList', $fansPrizeList);
			$this->display();
		}

		//活动统计
		public function statistics ()
		{
			$uBoostId = $this->_get('id', 'intval');
		$thisItem = M('boosts')->where(array('id' => $uBoostId, 'token' => $this->token))->find();

			$boostId = intval($thisItem['boostid']);

			$config = $this->_toConfig();
			$uid = $config['uid'];

			$thisBoost = $this->boost->getBoost(intval($boostId));
			$boostSet = $this->boost->boostSet($uid, $thisBoost['id'], $uBoostId, $config['key']);

			$this->assign('id', $uBoostId);
			$this->assign('thisBoost', $thisBoost);
			$this->assign('boost', $boostSet);
			// 获取活动奖品信息
			$awardsData = unserialize($boostSet['awards_value']);
			$awardsValue = $awardsData['awards_values'];
			$awardsCount = $awardsData['awards_count'];

			$this->assign('awardsValue', $awardsValue);
			$this->assign('awardsCount', $awardsCount);

			// 获取奖品兑换信息
			$convertCodeData = D('Boost_convert')->getConvertUseData($uid, $uBoostId);

			$this->assign('codeData', $convertCodeData);
		$data = array('uid' => $uid, 'boost_id' => $boostId, 'u_boost_id' => $uBoostId);
			$total= $this->boost->statistics($data);
			$total['open'] = $boostSet['open_counts'];
			$this->assign('total', $total);

			// -1：昨天； 0：今天；1：7天；2：30天；3：本月；4：自定义；
			$dateType = isset($_GET['date_type']) ? intval($_GET['date_type']) : 2;

			$startTime = '';
			$endTime = '';

			if (IS_POST) {
				$startTime = $_POST['start_time'];
				$endTime = $_POST['end_time'];
				$dateType = 4;

				$this->assign('startTime', $startTime);
				$this->assign('endTime', $endTime);
			}

			$this->assign('dateType', $dateType);

			$dateUtil = new DateUtil();
			switch ($dateType) {
				case -1:
					$startTime = $dateUtil->getPreviousYMD();
					$endTime = $dateUtil->getPreviousYMD();

					break;
				case 0:
					$startTime = $dateUtil->getCurrentYMD();
					$endTime = $dateUtil->getCurrentYMD();
					break;
				case 1:
					$startTime = $dateUtil->getPreviousYMD(NULL, 6);
					$endTime = $dateUtil->getCurrentYMD();

					break;
				case 2:
					$startTime = $dateUtil->getPreviousYMD(NULL, 29);
					$endTime = $dateUtil->getCurrentYMD();

					break;
				case 3:
					$startTime = $dateUtil->getFirstDayInMonth();
					$endTime = $dateUtil->getLastDayInMonth();
					break;
			}

			$data['start_time'] = $startTime;
			$data['end_time'] = $endTime;

			$checkStatistics = $this->boost->statistics($data);
			$this->assign('checkStatistics', $checkStatistics);

		$dateList = array();

			for ($i = strtotime($startTime); $i <= strtotime($endTime); $i += 24 * 60 * 60) {
				array_push($dateList, date('Y-m-d', $i));
			}

			$checkStatisticsList = $this->boost->statisticsList($data);
		$statisticsList = array('xAxis' => '', 'open' => '', 'join' => '', 'share' => '', 'shareCount' => '');
			foreach ($dateList as $dateKey) {
				$openText = isset($checkStatisticsList['open'][$dateKey]) ? $checkStatisticsList['open'][$dateKey] : 0;
				$joinText = isset($checkStatisticsList['join'][$dateKey]) ? $checkStatisticsList['join'][$dateKey] : 0;
				$shareText = isset($checkStatisticsList['share'][$dateKey]) ? $checkStatisticsList['share'][$dateKey] : 0;
				$shareCountText = isset($checkStatisticsList['shareCount'][$dateKey]) ? $checkStatisticsList['shareCount'][$dateKey] : 0;

				$statisticsList['xAxis'] .= '"' . date('m-d', strtotime($dateKey)) . '", ';
				$statisticsList['open'] .= '"' . $openText . '", ';
				$statisticsList['join'] .= '"' . $joinText . '", ';
				$statisticsList['share'] .= '"' . $shareText . '", ';
				$statisticsList['shareCount'] .= '"' . $shareCountText . '", ';
			}

			$this->assign('statisticsList', $statisticsList);

			$this->display();
		}

		function valtokey ($data, $field)
		{
			$return = array();
			foreach ($data as $key => $val) {
				$return[$val[$field]] = $val;
			}

			return $return;
		}

		function boostarr ()
		{
			S('boost_' . $this->_get('token') . '_' . $this->_get('id'), $_POST);
		}

		public function getQrcode ($kword)
		{
			$recdb = M('Recognition');
			$recognis = $recdb->where(array('keyword' => $kword, 'token' => $this->token))->find();
			$this->thisWxUser = M('Wxuser')->where(array('token' => $this->token))->find();
			$apiOauth = new apiOauth();
			$this->access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
			if ($recognis != '') {
				if ($recognis['code_url'] == '' || ($recognis['expire_seconds'] + $recognis['create_time']) < time()) {
					$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->access_token;
					$data['expire_seconds'] = 2592000;
					$data['action_name'] = 'QR_SCENE';
					$data['action_info']['scene']['scene_id'] = $recognis['id'];
					$post = $this->api_notice_increment($qrcode_url, json_encode($data));
					$recdb->where(array_merge(array('id' => $recognis['id'])))->save(array('code_url' => $post, 'expire_seconds' => 2592000));
					$wxqr = ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $recognis['code_url']);
				} else {
					$wxqr = ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $recognis['code_url']);
				}
			} else {
				$dataz['keyword'] = $kword;
				$dataz['title'] = $kword;
				$dataz['token'] = $this->token;
				$dataz['create_time'] = time();
				$xid = $recdb->add($dataz);
				$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->access_token;
				$data['expire_seconds'] = 2592000;
				$data['action_name'] = 'QR_SCENE';
				$data['action_info']['scene']['scene_id'] = $xid;
				$post = $this->api_notice_increment($qrcode_url, json_encode($data));
				$recdb->where(array_merge(array('id' => $xid)))->save(array('code_url' => $post, 'expire_seconds' => 2592000));
				$wxqr = ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $post);
			}

			return $wxqr;
		}

		function api_notice_increment ($url, $data)
		{
			$ch = curl_init();
			$header = "Accept-Charset: utf-8";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$tmpInfo = curl_exec($ch);
			$errorno = curl_errno($ch);
			if ($errorno) {
				$this->error('发生错误：curl error' . $errorno);

			} else {

				$js = json_decode($tmpInfo, true);

				if (isset($js['ticket'])) {
					return $js['ticket'];
				} else {
					$this->error('发生错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);
				}
			}
		}
	}

?>