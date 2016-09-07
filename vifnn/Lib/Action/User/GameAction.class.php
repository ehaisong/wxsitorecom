<?php

	class GameAction extends UserAction
	{
		public $config;
		public $cats;
		public $industyCats;
	public $industyCatInfo;
		public $game;

		/**
		 * init the game action class
		 * require the game class and set the game cats
		 */
		public function _initialize ()
		{
			//进入时初始化 @by linduwu
			if (C('IS_MEIHUA')) {
				$id = $this->_get('id', 'intval');
				if ($id && (ACTION_NAME == 'gameLibrary')) {
					$info = M('Wxuser')->find($id);
					$token = $this->_get('token', 'trim');
					if (empty($info) || $info['token'] != $token) {
						$this->error('非法操作', U('Home/Index/index'));
					}
					session('token', $token);
					session('wxid', $info['id']);
					session('companyid', NULL);
					$this->token = $token;
				}
			}
			parent::_initialize();
			$this->canUseFunction('Gamecenter');
			$this->game = new game();
			$this->cats = $this->game->gameCats();
			$this->industyCats = $this->game->gameIndustyCats();
		$this->industyCatInfo = $this->game->gameIndustyCatInfo();
			$this->industyCats[0] = array('name' => '全部');
			ksort($this->industyCats);
			$this->staticPath = 'http://s.404.cn';
            $this->assign('staticPath', $this->staticPath);
		$this->assign("cats", $this->industyCats);
		}

		/**
		 * set the user game config into vifnn and game database
		 */
	private function saveConfig($config, $data)
	{
		if (!$config) {
			D("Game_config")->add($data);
		}
		else {
			D("Game_config")->where(array("id" => $config["id"]))->save($data);
		}

		$data["link"] = $this->convertLink($data["link"]);
		$userType = (C("IS_MEIHUA") ? 1 : 0);
		$rt = $this->game->config($this->token, $data["wxname"], $data["wxid"], $data["wxlogo"], $data["link"], $data["attentionText"], $userType);
		D("Game_config")->where(array("token" => $this->token))->save(array("uid" => $rt["id"], "key" => $rt["key"]));
	}

		 
		public function config ()
		{
			$config = M('Game_config')->where(array('token' => $this->token))->find();
			if (IS_POST) {
			$data = array("token" => $this->token, "wxid" => $this->_post("wxid"), "wxname" => $this->_post("wxname"), "wxlogo" => $this->_post("wxlogo"), "link" => $this->_post("link"), "attentionText" => $this->_post("attentionText"));
			$this->saveConfig($config, $data);
			$this->success("设置成功");
				
			} else {
				if (!$config) {
					$config = $this->wxuser;
					$config['wxlogo'] = $config['headerpic'];
				}
				$this->assign('info', $config);
				$this->display();
			}
	}

	public function mhQuick()
	{
		$qrImg = $this->wxuser["qr"];
		$quickUrl = $this->wxuser["hurl"];

		if (IS_POST) {
			$qrImg = $_POST["qr_img"];
			$quickUrl = $_POST["quick_url"];
			M("wxuser")->where(array("token" => $this->token))->save(array("qr" => $qrImg, "hurl" => $quickUrl));
		}

		$this->assign("qrImg", $qrImg);
		$this->assign("quickUrl", $quickUrl);
		$this->display();
	}

		public function index ()
		{
			$config = $this->_toConfig();
			//
			$where = array('token' => $this->token);

			$count = M('Games')->where($where)->count();
			$Page = new Page($count, 15);
			$list = M('Games')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			$wxUser = M('Game_config')->where(array('token' => $this->token))->find();
			$this->assign('wxUser', $wxUser);
			$this->assign('count', $count);
			$this->assign('page', $Page->show());
			$this->assign('list', $list);

		$totalStatistics = array();
		$isShare = array();
		$sendType = array();
		$isNewGame = array();
			foreach ($list as $game) {
				$thisGame = $this->game->getGame(intval($game['gameid']));

				$isNewGame[$game['id']] = $thisGame['is_banben'];
			       $data = array("uid" => $config["uid"], "game_id" => $game["gameid"], "u_game_id" => $game["id"]);

				$totalStatistics[$game['id']] = $this->game->statistics($data);

				$gameSet = S("game_index_game_set_" . $config['uid'] . '_' . $game['gameid'] . '_' . $game['id']);
				if (empty($gameSet)) {
					$gameSet = $this->game->gameSet($config['uid'], $game['gameid'], $game['id'], $config['key']);

					S("game_index_game_set_" . $config['uid'] . '_' . $game['gameid'] . '_' . $game['id'], $gameSet);
				}

				$isShare[$game['id']] = 0;
				if ($game['is_share'] == 1) {
					$isShare[$game['id']] = 1;
					if ($game['share_callback'] == 1) {
						$game['share_value'] = unserialize($game['share_value']);

						$sendMoney = $gameSet['money'];
						$totalMoney = $game['share_value']['totalmoney'];
						$minMoney = $game['share_value']['minmoney'];
						$leftMoney = $totalMoney - $sendMoney;

						$sendType[$game['id']] = $leftMoney < $minMoney;
					}

					if ($game['share_callback'] == 2) {
						$game['share_value'] = unserialize($game['share_value']);
						$cardId = $game['share_value']['coupon_id'];

					$couponData = M("Member_card_coupon")->where(array("card_id" => $cardId))->find();

					$useCounpons = $this->game->getTotalCoupon(array("uid" => $config["uid"], "u_game_id" => $game["id"]));
						$totalCoupons = $couponData['total'];

						$sendType[$game['id']] = $useCounpons >= $totalCoupons;
					}
				}


			}

			$this->assign('totalStatistics', $totalStatistics);
			$this->assign('sendType', $sendType);
			$this->assign('isShare', $isShare);
			$this->assign('isNewGame', $isNewGame);
		$showId = (isset($_GET["showId"]) ? intval($_GET["showId"]) : 0);
		$this->assign("showId", $showId);
		$showReleaseId = (isset($_GET["showReleaseId"]) ? intval($_GET["showReleaseId"]) : 0);
		$this->assign("showReleaseId", $showReleaseId);
			$this->display();
		}

		public function delGame ()
		{
			$config = $this->_toConfig();
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$thisItem = M('games')->where(array('id' => $id, 'token' => $this->token))->find();
			$data['uid'] = $config['uid'];
			$data['gameid'] = $thisItem['gameid'];
			$data['ugameid'] = $id;
			$rt = $this->game->deleteGame($data);
			//$thisGame=$this->game->getGame(intval($gameid));
			//$this->game->gameSelfSet($config['uid'],$thisGame['id'],$id,'game',$config['key'],'');
			M('games')->where(array('id' => $id, 'token' => $this->token))->delete();
			$this->success('删除成功', U('Game/index'));
		}

		public function gameSet ()
		{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$this->assign('id', $id);
			if ($id) {
				$thisItem = M('games')->where(array('id' => $id, 'token' => $this->token))->find();

				$gameid = intval($thisItem['gameid']);
			} else {
				$gameid = intval($_GET['gameid']);
			}
			$config = $this->_toConfig();
			$thisGame = $this->game->getGame(intval($gameid));
		//	dump($thisGame);
		//	dump(unserialize($thisGame["time_set"])["type"]);
			$gameSet = $this->game->gameSet($config['uid'], $thisGame['id'], $id, $config['key']);

			$this->assign('gameCate', $thisGame['catid']);
		    $this->assign("timeSet", unserialize($thisGame["time_set"])["type"]);

			if ($gameSet) {
				$thisItem['rule'] = htmlspecialchars_decode(base64_decode($gameSet['rule']));
				$thisItem['awards'] = htmlspecialchars_decode(base64_decode($gameSet['awards']));
				$thisItem['awards_value'] = unserialize($gameSet['awards_value']);
				$thisItem['attention_url'] = $gameSet['attention_url'];
				$thisItem['is_phone'] = $gameSet['is_phone'];
				$thisItem['is_attention'] = $gameSet['is_attention'];
				$thisItem['start_time'] = $gameSet['start_time'];
				$thisItem['end_time'] = $gameSet['end_time'];
				$thisItem['star_bg'] = $gameSet['star_bg'];
				$thisItem['star_btn'] = $gameSet['star_btn'];
				$thisItem['limit_num'] = $gameSet['limit_num'];
				$thisItem['limit_fx'] = $gameSet['limit_fx'];
				$thisItem['rank_limit'] = $gameSet['rank_limit'];
				$thisItem['wechat_set'] = $gameSet['wechat_set'];
				$thisItem['join_set'] = $gameSet['join_set'];
				$thisItem['office_value'] = $gameSet['office_value'];
				$thisItem['notice_set'] = $gameSet['notice_set'];
			$thisItem["time_set"] = $gameSet["time_set"];
				$thisItem['bg_music'] = $gameSet['bg_music'];
				$thisItem['bg_music_value'] = $gameSet['bg_music_value'];
			$thisItem["game_rank"] = $gameSet["game_rank"];
				$thisItem['game_playing'] = $gameSet['game_playing'];
				$thisItem['game_over'] = $gameSet['game_over'];
				$thisItem['game_result'] = $gameSet['game_result'];
			}

			$hasGameRank = $thisGame['game_rank'];
			$this->assign('hasGameRank', $hasGameRank);

			$hasGamePlaying = 0;
			if ($thisGame['game_playing'] == 1) {
				$hasGamePlaying = 1;

				$gamePlayingSet = S("game_index_game_playing_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $id);

				if (empty($gamePlayingSet)) {
					$gamePlayingSet = $this->game->gamePlayingSet($config['uid'], $thisGame['id'], $id);

					S("game_index_game_playing_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $id, $gamePlayingSet);
				}

				$gamePlayingSet['prize_value'] = unserialize($gamePlayingSet['prize_value']);
				$gamePlayingSetAwardsValues = $gamePlayingSet['prize_value']['awards_values'];

				$this->assign('gamePlayingSet', $gamePlayingSet);
				$this->assign('playingAwardsValues', $gamePlayingSetAwardsValues);
			}
			$this->assign('hasGamePlaying', $hasGamePlaying);

			$hasGameOver = 0;
			if ($thisGame['game_over'] == 1) {
				$hasGameOver = 1;

				$gameOverSet = S("game_index_game_over_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $id);

				if (empty($gameOverSet)) {
					$gameOverSet = $this->game->gameOverSet($config['uid'], $thisGame['id'], $id);

					S("game_index_game_over_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $id, $gameOverSet);
				}

				$gameOverSet['prize_value'] = unserialize($gameOverSet['prize_value']);
				$gameOverSetAwardsValues = $gameOverSet['prize_value']['awards_values'];

				$this->assign('gameOverSet', $gameOverSet);
				$this->assign('overAwardsValues', $gameOverSetAwardsValues);
			}
			$this->assign('hasGameOver', $hasGameOver);

			$hasGameShare = $thisGame['game_share'];
			$this->assign('hasGameShare', $hasGameShare);

			$selfs = $this->game->gameSelfs($thisGame['id'], $config['uid'], $id, $config['key']);
			if (IS_POST) {
			$wxtype = M("wxuser")->field("`winxintype`,`qr`, `is_syn`, `encode`")->where(array("token" => $this->token))->find();
				if ($wxtype['winxintype'] == 3) {
					$gameqr = $this->getQrcode($this->_post('keyword'));
				} else {
					if ($wxtype['is_syn'] != 2) {
						if (empty($wxtype['qr'])) {
					             if (C("IS_MEIHUA") && ($wxtype["encode"] == 0)) {
						        $this->error("请在编辑公众号里，上传您的二维码", U("Game/mhQuick", array("token" => $this->token)));
				       	             }
					              else {
						         $this->error("请在编辑公众号里，上传您的二维码", U("Index/index", array("id" => session("wxid"))));
					             }
						} else {
							$gameqr = $wxtype['qr'];
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
					'gameid'         => $thisGame['id'],
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
					$usergameid = M('Games')->add($data);
				} else {
					$usergameid = intval($_POST['id']);
					M('Games')->where(array('id' => $usergameid))->save($data);
				}

				$gameSet['title'] = $this->_post('title');
				$gameSet['intro'] = $this->_post('intro');
				$gameSet['picurl'] = $this->_post('picurl');
				$gameSet['star_btn'] = $_POST['star_btn'];
				$gameSet['star_btn_coordinate'] = isset($_POST['star_btn_coordinate']) ? serialize($_POST['star_btn_coordinate']) : '';
				$gameSet['star_bg'] = $_POST['star_bg'];
				$gameSet['rule'] = $_POST['gameSet_rule'];
				$gameSet['awards'] = $_POST['gameSet_awards'];

				$awardsValue = '';
				if (isset($_POST['gameSet_awards_value'])) {
					$_POST['gameSet_awards_value']['awards_start_time'] = strtotime($_POST['gameSet_awards_value']['awards_start_time']);
					$_POST['gameSet_awards_value']['awards_end_time'] = strtotime($_POST['gameSet_awards_value']['awards_end_time']);

					$awardsValue = serialize($_POST['gameSet_awards_value']);
				}

				$gameSet['awards_value'] = $awardsValue;
				$gameSet['is_phone'] = $this->_post('is_phone');
				$gameSet['is_attention'] = $this->_post('is_attention');
				$gameSet['limit_num'] = $_POST['limit_num'];
				$gameSet['limit_fx'] = $_POST['limit_fx'];
				$gameSet['start_time'] = strtotime($_POST['start_time']);
				$gameSet['end_time'] = strtotime($_POST['end_time']);
				$gameSet['gameqr'] = $gameqr;
				$gameSet['keyword'] = $this->_post('keyword');
				$gameSet['share_callback'] = $this->_post('share_callback');
				$gameSet['share_value'] = isset($_POST['share']) ? serialize($_POST['share']) : '';
				$gameSet['is_share'] = $_POST['is_share'];
			$gameSet["game_rank"] = $_POST["game_rank"];
				$gameSet['game_playing'] = $_POST['game_playing'];
				$gameSet['game_over'] = $_POST['game_over'];
				$gameSet['game_result'] = $_POST['game_result'];
				$gameSet['bg_music'] = $_POST['bg_music'];
				$gameSet['bg_music_value'] = isset($_POST['bg_music_value']) ? serialize($_POST['bg_music_value']) : '';
				$gameSet['join_set'] = isset($_POST['join_set']) ? serialize($_POST['join_set']) : '';
				$gameSet['wechat_set'] = isset($_POST['wechat_set']) ? serialize($_POST['wechat_set']) : '';
			$gameSet["time_set"] = (isset($_POST["time_set"]) ? serialize($_POST["time_set"]) : "");
				$gameSet['rank_limit'] = intval($_POST['rank_limit']);
				$gameSet['office_value'] = isset($_POST['office_value']) ? serialize($_POST['office_value']) : '';
				$gameSet['notice_set'] = isset($_POST['notice_set']) ? serialize($_POST['notice_set']) : '';

				$home_set = M('Wxuser')->field('id,hurl')->where(array('token' => $this->token))->find();
				if ($gameSet['is_attention'] == 1 && (!isset($home_set['hurl']) || $home_set['hurl'] == '')) {
					$this->error('需要关注，请先设置快捷关注配置中的一键关注链接', U('Game/index'));
				}
				$gameSet['attention_url'] = $home_set['hurl'];

				$this->game->gameSet($config['uid'], $thisGame['id'], $usergameid, $config['key'], $gameSet, 'game');
				$this->handleKeyword($usergameid, 'Game', $data['keyword'], $precisions = 0, $delete = 0);
				$this->game->gameSelfSet($config['uid'], $thisGame['id'], $usergameid, 'game', $config['key'], $selfValues);

				// 更新游戏过程中中奖信息
				if ($hasGamePlaying && $_POST['game_playing'] == 1 && isset($_POST['game_playing_set'])) {
					$data = array();
					$data['prize_pic'] = $_POST['game_playing_set']['prize_pic'];
					$data['prize_limit'] = $_POST['game_playing_set']['prize_limit'];
					$data['prize_total_limit'] = $_POST['game_playing_set']['prize_total_limit'];
					$data['prize_percent'] = $_POST['game_playing_set']['prize_percent'] / 100;
					$data['prize_type'] = '';
					$data['prize_value'] = isset($_POST['game_playing_set']['prize_value']) ? serialize($_POST['game_playing_set']['prize_value']) : '';

					if (isset($_POST['game_playing_set']['prize_value'])) {
						$prizeType = array();
						$prizeCount = $_POST['game_playing_set']['prize_value']['awards_count'];

						for ($i = 0; $i < $prizeCount; $i++) {
							$prizeValueType = $_POST['game_playing_set']['prize_value']['awards_values'][$i]['awards_cate'];

							if (!in_array($prizeValueType, $prizeType)) {
								array_push($prizeType, $prizeValueType);
							}
						}

						$data['prize_type'] = serialize($prizeType);
					}

					$this->game->gamePlayingSet($config['uid'], $thisGame['id'], $usergameid, $data);

					S("game_index_game_playing_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $usergameid, NULL);
				}

				// 更新游戏结束后抽奖中奖信息
				if ($hasGameOver && $_POST['game_over'] == 1 && isset($_POST['game_over_set'])) {
					$data = array();
					$data['prize_pic'] = $_POST['game_over_set']['prize_pic'];
					$data['prize_limit'] = $_POST['game_over_set']['prize_limit'];
					$data['prize_total_limit'] = $_POST['game_over_set']['prize_total_limit'];
				        $data["prize_one_limit"] = $_POST["game_over_set"]["prize_one_limit"];
					$data['prize_percent'] = $_POST['game_over_set']['prize_percent'] / 100;
					$data['prize_type'] = '';
					$data['prize_value'] = isset($_POST['game_over_set']['prize_value']) ? serialize($_POST['game_over_set']['prize_value']) : '';
					$data['prize_min_score'] = $_POST['game_over_set']['prize_min_score'];

					if (isset($_POST['game_over_set']['prize_value'])) {
						$prizeType = array();
						$prizeCount = $_POST['game_over_set']['prize_value']['awards_count'];

						for ($i = 0; $i < $prizeCount; $i++) {
							$prizeValueType = $_POST['game_over_set']['prize_value']['awards_values'][$i]['awards_cate'];

							if (!in_array($prizeValueType, $prizeType)) {
								array_push($prizeType, $prizeValueType);
							}
						}

						$data['prize_type'] = serialize($prizeType);
					}

					$this->game->gameOverSet($config['uid'], $thisGame['id'], $usergameid, $data);

					S("game_index_game_over_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $usergameid, NULL);
				}

				// 清空游戏缓存
				S("game_index_game_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $usergameid, NULL);

			if (isset($thisItem)) {
				$isRelease = $thisItem["is_release"] == 1;
			}
			else {
				$isRelease = false;
			}

			if ($isRelease) {
				$this->success("设置成功", U("Game/index", array("token" => $this->token)));
			}
			else {
				$this->success("设置成功", U("Game/index", array("token" => $this->token, "showReleaseId" => $usergameid)));
			}
		}
		else {
				$url = $_SERVER['SERVER_NAME'];
				$this->assign('url', $url);
				$this->assign('thisGame', $thisGame);
				if ($this->_get('gameid')) {
					$gameid = intval($this->_get('gameid'));
				} else {
					$gameid = intval($thisGame['id']);
				}

				$this->assign('gameid', $gameid);

				if (!$id) {
					$thisItem = array();
					$thisItem['title'] = $thisGame['title'];
					$thisItem['intro'] = $thisGame['intro'];
					$thisItem['keyword'] = $thisGame['title'];
					$thisItem['rule'] = $thisGame['catid'] != 4 ? '活动结束后，会根据排行榜成绩进行派奖。' : '每人只有一次竞猜机会。活动结束后，系统将自动匹配出答对者，答对者将获得随机抽奖的机会。';
					$thisItem['picurl'] = $thisGame['picurl'];
				$thisItem["time_set"] = $thisGame["time_set"];
					$thisItem['is_release'] = 0;
					$thisItem['awards_value'] = unserialize($gameSet['awards_value']);
				$thisItem["game_rank"] = ($hasGameRank == 1 ? 1 : 0);
				}

				if ($id) {
					if ($selfs) {
						$selfValues = json_decode($thisItem['selfinfo'], 1);

						foreach ($selfs as $i => $s) {
							// 判断是否为新的游戏设置
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
				}
				$thisItem['rule'] = stripslashes($thisItem['rule']);
				$thisItem['awards'] = stripslashes($thisItem['awards']);
				if ($thisItem['id'] == 79) {
					$thisItem['star_bg'] = "{vifnn::$staticPath}/tpl/static/game/star_sd.png";
				}

				//判断是否服务号
				$account_type = M('Wxuser')->where(array('token' => $this->token))->find();
				$serverType = 0;
				if ($account_type['winxintype'] > 2) {
					$serverType = 1;
					$share_options[1] = "发红包";
					$share_options[2] = "发卡券";
				}

				//游戏分享回调选项
				$share_options[3] = "送积分";
				$share_options[4] = "送次数";
			$meihuaServerType = 0;
			if (C("IS_MEIHUA") && ($account_type["encode"] == 0)) {
				$meihuaServerType = 1;
			}

			$this->assign("meihuaServerType", $meihuaServerType);
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

				if ($this->_get('gameid') == 79) {
					$thisItem['star_bg'] = "/tpl/static/game/star_sd.png";
					$thisItem['star_btn'] = "8";
				}

				$thisItem['share_value'] = unserialize($thisItem['share_value']);
				$thisItem['star_btn_coordinate'] = unserialize($thisItem['star_btn_coordinate']);
				$thisItem['bg_music_value'] = unserialize($thisItem['bg_music_value']);
				$thisItem['join_set'] = unserialize($thisItem['join_set']);
				$thisItem['wechat_set'] = unserialize($thisItem['wechat_set']);
				$thisItem['office_value'] = unserialize($thisItem['office_value']);
				$thisItem['notice_set'] = unserialize($thisItem['notice_set']);
			$thisItem["time_set"] = unserialize($thisItem["time_set"]);
				$this->assign('selfs', $selfs);
				$this->assign('info', $thisItem);
				$this->assign('awardsValues', $thisItem['awards_value']['awards_values']);
				$this->assign('serverType', $serverType);

				if ($thisGame['is_banben'] == 0) {
					$this->display();
				} else {
					$this->display('game_set_new');
				}
			}
		}

		public function gamePage ()
		{
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

			if ($id) {
				$thisItem = M('games')->where(array('id' => $id, 'token' => $this->token))->find();

				$gameid = intval($thisItem['gameid']);
			} else {
				$gameid = intval($_GET['gameid']);
			}

			$config = $this->_toConfig();
			$thisGame = $this->game->getGame(intval($gameid));
			$gameSet = $this->game->gameSet($config['uid'], $thisGame['id'], $id, $config['key']);
		    $hasGameRank = $thisGame["game_rank"];
		    $this->assign("hasGameRank", $hasGameRank);
			$this->assign('gameCate', $thisGame['catid']);
		    $this->assign("timeSet", unserialize($thisGame["time_set"])["type"]);
            $this->assign("rankUnit", $thisGame["unit"]);
			if ($gameSet) {
				$thisItem['rule'] = htmlspecialchars_decode(base64_decode($gameSet['rule']));
				$thisItem['awards'] = htmlspecialchars_decode(base64_decode($gameSet['awards']));
				$thisItem['awards_value'] = unserialize($gameSet['awards_value']);
				$thisItem['attention_url'] = $gameSet['attention_url'];
				$thisItem['is_phone'] = $gameSet['is_phone'];
				$thisItem['is_attention'] = $gameSet['is_attention'];
				$thisItem['start_time'] = $gameSet['start_time'];
				$thisItem['end_time'] = $gameSet['end_time'];
				$thisItem['star_bg'] = $gameSet['star_bg'];
				$thisItem['star_btn'] = $gameSet['star_btn'];
				$thisItem['limit_num'] = $gameSet['limit_num'];
				$thisItem['limit_fx'] = $gameSet['limit_fx'];
				$thisItem['rank_limit'] = $gameSet['rank_limit'];
				$thisItem['wechat_set'] = $gameSet['wechat_set'];
				$thisItem['join_set'] = $gameSet['join_set'];
				$thisItem['office_value'] = $gameSet['office_value'];
				$thisItem['notice_set'] = $gameSet['notice_set'];
			$thisItem["time_set"] = $gameSet["time_set"];
				$thisItem['bg_music'] = $gameSet['bg_music'];
				$thisItem['bg_music_value'] = $gameSet['bg_music_value'];
			$thisItem["game_rank"] = $gameSet["game_rank"];
				$thisItem['game_playing'] = $gameSet['game_playing'];
				$thisItem['game_over'] = $gameSet['game_over'];
			}

			$selfs = $this->game->gameSelfs($thisGame['id'], $config['uid'], $id, $config['key']);

			if (!$id) {
				$thisItem = array();
				$thisItem['title'] = $thisGame['title'];
				$thisItem['intro'] = $thisGame['intro'];
				$thisItem['keyword'] = $thisGame['title'];
			$thisItem["time_set"] = $thisGame["time_set"];
				$thisItem['rule'] = '活动结束后，会根据排行榜成绩进行派奖。';
				$thisItem['awards_value'] = unserialize($gameSet['awards_value']);;
			$thisItem["game_rank"] = ($hasGameRank == 1 ? 1 : 0);
			}

			if ($id) {
				if ($selfs) {
					$selfValues = json_decode($thisItem['selfinfo'], 1);

					foreach ($selfs as $i => $s) {
						// 判断是否为新的游戏设置
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
			}

			$thisItem['rule'] = stripslashes($thisItem['rule']);
			$thisItem['awards'] = stripslashes($thisItem['awards']);
			$thisItem['share_value'] = unserialize($thisItem['share_value']);
			$thisItem['star_btn_coordinate'] = unserialize($thisItem['star_btn_coordinate']);
			$thisItem['bg_music_value'] = unserialize($thisItem['bg_music_value']);
			$thisItem['join_set'] = unserialize($thisItem['join_set']);
			$thisItem['wechat_set'] = unserialize($thisItem['wechat_set']);
			$thisItem['office_value'] = unserialize($thisItem['office_value']);
			$thisItem['notice_set'] = unserialize($thisItem['notice_set']);
		$thisItem["time_set"] = unserialize($thisItem["time_set"]);
			$hasGamePlaying = 0;
			if ($thisGame['game_playing'] == 1) {
				$hasGamePlaying = 1;

				$gamePlayingSet = S("game_index_game_playing_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $id);

				if (empty($gamePlayingSet)) {
					$gamePlayingSet = $this->game->gamePlayingSet($config['uid'], $thisGame['id'], $id);
				}

				$gamePlayingSet['prize_value'] = unserialize($gamePlayingSet['prize_value']);
				$gamePlayingSetAwardsValues = $gamePlayingSet['prize_value']['awards_values'];

				$this->assign('gamePlayingSet', $gamePlayingSet);
				$this->assign('playingAwardsValues', $gamePlayingSetAwardsValues);
			}
			$this->assign('hasGamePlaying', $hasGamePlaying);

			$hasGameOver = 0;
			if ($thisGame['game_over'] == 1) {
				$hasGameOver = 1;

				$gameOverSet = S("game_index_game_over_set_" . $config['uid'] . '_' . $thisGame['id'] . '_' . $id);

				if (empty($gameOverSet)) {
					$gameOverSet = $this->game->gameOverSet($config['uid'], $thisGame['id'], $id);
				}

				$gameOverSet['prize_value'] = unserialize($gameOverSet['prize_value']);
				$gameOverSetAwardsValues = $gameOverSet['prize_value']['awards_values'];

				$this->assign('gameOverSet', $gameOverSet);
				$this->assign('overAwardsValues', $gameOverSetAwardsValues);
			}
			$this->assign('hasGameOver', $hasGameOver);

			$hasGameShare = $thisGame['game_share'];
			$this->assign('hasGameShare', $hasGameShare);

			$this->assign('selfs', $selfs);
			$this->assign('info', $thisItem);
			$this->assign('awardsValues', $thisItem['awards_value']['awards_values']);

			$this->display();
		}

		public function gameDelete ()
		{

		}

		public function gameResults ()
		{

		}

		/**
		 * get game library all or by catid
		 */
		public function gameLibrary ()
		{
			$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 1;
			$search = '';
			if (IS_POST) {
				$search = $_POST['search'];
			}

			$this->assign('search', $search);
			$games = $this->game->gameList($catid, $search);

			// 分页计算逻辑
			$count = count($games);
			$ps = $this->_get('p', 'intval');
			if ($ps < 1) {
				$ps = 1;
			}

			// 每页显示的信息数
			$num = 8;

			//当前页数显示内容
			if ($this->_get('p') > 0) {
				$star = ($this->_get('p') - 1) * $num;
				$gameList = array_slice($games, $star, $num);
			} else {
				$gameList = array_slice($games, 0, $num);
			}

			foreach ($gameList as $key => $value) {
				if (!empty($value['intropic'])) {
					$intropic = explode(",", $value['intropic']);
					foreach ($intropic as $k => $v) {
						$value['rule_pic'] .= '<img src="' . $v . '" style="width:48%;margin-right:1%;float:left;margin-top:10px;height: 500px;">';
					}
				}

			$value["cat_id"] = $this->addCatInfoList($value, 6, $catId);
				$gameList[$key] = $value;
			}

			$Page = new Page($count, $num);
			$this->assign('page', $Page->show());

			$this->assign('games', $gameList);
			$this->assign('catid', $catid);
			$this->display();
	}
	private function addCatInfoList($value, $dep, $industryType)
	{
		$industryCatInfo = $this->industyCatInfo;
		$industryCatInfo = $industryCatInfo["info"];
		$temArr = explode(",", $value["industry_cate_info"]);
		$iCatsArr = array();
		$i = 0;

		foreach ($temArr as $k => $v ) {
			if ($v && ($i < $dep)) {
				array_push($iCatsArr, $industryCatInfo[$v]["name"]);
				$i++;
			}
		}

		if (empty($iCatsArr)) {
			if ($industryType == 0) {
				$temArr = explode(",", $value["industry_cate"]);
				$i = 0;

				foreach ($temArr as $k => $v ) {
					if ($v && ($i < $dep)) {
						array_push($iCatsArr, $this->industyCats[$v]["name"]);
						$i++;
					}
				}
			}
			else {
				array_push($iCatsArr, $this->industyCats[$industryType]["name"]);
			}
		}

		return $iCatsArr;
	}

		function _toConfig ()
		{
			$config = M('Game_config')->where(array('token' => $this->token))->find();
			if (!$config) {
 	                     if (C("IS_MEIHUA")) {
				$data = array("token" => $this->token, "wxid" => $this->wxuser["wxid"], "wxname" => $this->wxuser["wxname"], "wxlogo" => $this->wxuser["headerpic"], "link" => "", "attentionText" => "");
				$this->saveConfig($config, $data);
				$this->_toConfig();
			    }
			     else {
				$this->success("请先配置游戏相关信息", U("Game/config"));
				exit();
			    }
		
			} else {
				return $config;
			}
		}


		function record ()
		{
			$uGameId = $this->_get('id', 'intval');
			$gameId = $this->_get('gid', 'intval');
		$config = $this->_toConfig();
		        $thisItem = M("games")->where(array("id" => $uGameId, "token" => $this->token))->find();
		$thisItem["share_value"] = unserialize($thisItem["share_value"]);
			$gameSet = $this->game->gameSet($config['uid'], $gameId, $uGameId, $config['key']);
			$isPhone = $gameSet['is_phone'];

			$thisGame = $this->game->getGame($gameId);
			$this->assign('gameCate', $thisGame['catid']);


			$data['uid'] = $config['uid'];
			$data['id'] = $gameId;
			$data['gid'] = $uGameId;
			$list = $this->game->RankingList($data);
	  	        $fansIdData = array(
			   "fansId" => array()
			    );

			foreach ($list as $rank) {
				array_push($fansIdData['fansId'], $rank['fans_id']);
			}

			$fansIdData['fansId'] = implode(', ', $fansIdData['fansId']);

			// 获取粉丝信息
			$fansData = $this->game->getUserInfo($fansIdData);
			$fansData = $this->valtokey($fansData, 'id');

			// 获取粉丝成绩信息
			$data = $fansIdData;
			$data['uid'] = $config['uid'];
			$data['game_id'] = $gameId;
			$data['u_game_id'] = $uGameId;
			$scoreData = $this->game->getUserScoreData($data);
			$scoreData = $this->valtokey($scoreData, 'fans_id');

			// 分享次数及邀请人数统计
			$shareData = $this->game->getShareData($data);

			// 奖励情况
			$prizeData = $this->game->getPrizeData($data);

			// 最后一次登录时间
			$lastTimeData = $this->game->getLastTime($data);

			// 分页计算逻辑
			$count = count($list);
			$ps = $this->_get('p', 'intval');
			if ($ps < 1) {
				$ps = 1;
			}

			$num = 15;
			//页数
			if (is_int($count / $num)) {
				$gamePage = $count / $num;
			} else {
				$gamePage = floor($count / $num + 1);
			}
			//当前页数显示内容
			if ($this->_get('p') > 0) {
				$star = ($this->_get('p') - 1) * $num;
				$dataList = array_slice($list, $star, $num);
			} else {
				$dataList = array_slice($list, 0, $num);
			}

			if ($gamePage > 9) {
				if ($ps < 5) {
					$pageStar = 1;
					$pageEnd = 10;
				} else {
					$pageStar = $ps - 4;
					$pageEnd = $ps + 5;
				}
				if ($pageEnd > $gamePage) {
					$pageEnd = $gamePage;
					$pageStar = $pageEnd - 9;
				}
			} else {
				$pageStar = 1;
				$pageEnd = $gamePage;
			}
			$this->assign('id', $uGameId);
			$this->assign('gid', $gameId);
			$this->assign('ps', $ps);
		        $this->assign("num", $num);
			$this->assign('pageStar', $pageStar);
			$this->assign('pageEnd', $pageEnd);

			$this->assign('isPhone', $isPhone);
			$this->assign('records', $dataList);
			$this->assign('fans', $fansData);
			$this->assign('shareData', $shareData);
			$this->assign('prizeData', $prizeData);
			$this->assign('lastTimeData', $lastTimeData);
			$this->assign('game', $thisItem);
			$this->assign('scores', $scoreData);
			$this->assign('gamePage', $gamePage);

			$this->display();
		}

		function record_del ()
		{
			$config = $this->_toConfig();
			$wecha_id = $this->_get('wecha');
			$uid = $config['uid'];
			$gid = $this->_get('gid');
			$uGameId = $this->_get('ugid');
			$fansId = $this->_get('fansid');
			$scoreId = $this->_get('id');

			$data['game_id'] = $gid;
			$data['uid'] = $uid;
			$data['u_game_id'] = $uGameId;
			$data['fans_id'] = $fansId;
			$data['score_id'] = $scoreId;
			$this->game->scoredel($data);
//			M('Game_records')->where(array('game' => $gid, 'wecha_id' => $wecha_id, 'token' => $this->token))->delete();
			$this->success('删除成功', U('Game/record', array('token' => $this->token, 'id' => $uGameId, 'gid' => $gid)));

		}

		// 游戏发布
		function release ()
		{
			$id = $this->_get('id');
			$config = $this->_toConfig();
		        $game = M("games")->where(array("id" => $id, "token" => $this->token))->find();

			$now = time();

			if (!empty($game)) {
				if ($game['is_release'] == 1) {
				       $this->error("游戏已发布，请勿重复发布！", U("Game/index", array("token" => $this->token)));
			
				} elseif ($game['end_time'] <= $now) {
					$this->error('游戏结束时间应大于当前时间，暂不能发布！', U('Game/index', array('token' => $this->token)));
				} else {
					$uid = $config['uid'];
					$uGameId = $id;
					$gameId = $game['gameid'];

					try {
						// 游戏平台端
						$status = $this->game->release($uid, $gameId, $uGameId);

						if ($status['status']) {
							// 营销端
						M("games")->where(array("id" => $id, "token" => $this->token))->save(array("is_release" => 1));
						M("Game_records")->where(array("gameid" => $gameId, "token" => $this->token, "uid" => $uid, 'u_game_id' => $uGameId))->delete();

						$this->success("游戏发布成功！", U("Game/index", array("token" => $this->token)));
						} else {
						$this->error($status["msg"], U("Game/index", array("token" => $this->token)));
						}
					} catch (Exception $e) {
					$this->error("游戏：" . $game["title"] . " 发布失败", U("Game/index", array("token" => $this->token)));
					}
				}
			} else {
			$this->error("游戏不存在", U("Game/index", array("token" => $this->token)));
			}

		}

		/**
		 * 兑换管理逻辑
		 */
		function convert ()
		{
			$uGameId = $this->_get('id', 'intval');
		$gameId = $this->_get("gid", "intval");
			$convertType = $this->_get('convert_type', 'intval');
			$thisItem = M('games')->where(array('id' => $uGameId, 'token' => $this->token))->find();

			$gameId = intval($thisItem['gameid']);
			$this->assign('gameId', $gameId);

			$thisGame = $this->game->getGame($gameId);
			$this->assign('gameCate', $thisGame['catid']);
			if ($thisGame['catid'] == 4) {
				$convertType = 2;
			}

			$config = $this->_toConfig();
			$uid = $config['uid'];
			$this->assign('uid', $uid);
		$this->assign("id", $uGameId);
		$this->assign("gid", $gameId);
			$this->assign('convertType', $convertType);

			$gameSet = $this->game->gameSet($uid, $gameId, $uGameId, $config['key']);
			$hasGamePlaying = $gameSet['game_playing'];
			$hasGameOver = $gameSet['game_over'];
			$this->assign('hasGamePlaying', $hasGamePlaying);
			$this->assign('hasGameOver', $hasGameOver);

			// 获得排名获奖的获奖信息
			if ($convertType == 0) {
				// 判断活动是否结束
				if ($thisItem['end_time'] < time()) {
					$this->assign('finish', 1);
					$this->assign('userKey', $config['key']);

					$thisGame = S('game_convert_game_' . $uid . '_' . $gameId . '_' . $uGameId);
					if (empty($thisGame)) {
						$thisGame = $this->game->getGame(intval($gameId));

						S('game_convert_game_' . $uid . '_' . $gameId . '_' . $uGameId, $thisGame);
					}

					$gameSet = S('game_convert_game_set_' . $uid . '_' . $gameId . '_' . $uGameId);
					if (empty($gameSet)) {
						$gameSet = $this->game->gameSet($config['uid'], $thisGame['id'], $uGameId, $config['key']);

						S('game_convert_game_set_' . $uid . '_' . $gameId . '_' . $uGameId, $gameSet);
					}

					// 获取活动奖品信息
					$awardsData = unserialize($gameSet['awards_value']);
					$awardsValue = $awardsData['awards_values'];
					$awardsCount = $awardsData['awards_count'];

					$this->assign('awardsValue', $awardsValue);
					$this->assign('awardsCount', $awardsCount);
				$convertCodeData = D("Game_convert")->getConvertUseData($uid, $uGameId);
				if (empty($convertCodeData) && ($gameSet["end_time"] < time())) {
					$convertEnd = 0;

					for ($i = 0; $i < $awardsCount; $i++) {
						$convertEnd += $awardsValue[$i]["awards_num"];
					}

					$data = array("uid" => $uid, "game_id" => $gameId, "u_game_id" => $uGameId, "convert_end" => $convertEnd);
					$convertCodeStatus = $this->game->getConvertDataFromCms($data);

					if ($convertCodeStatus["status"] == 200) {
						$convertCodeData = D("Game_convert")->getConvertUseData($uid, $uGameId);
					}
				}

					$this->assign('codeData', $convertCodeData);
		  	        $where = array("uid" => $uid, "u_game_id" => $uGameId);
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
						$fansIds = S('game_convert_search_' . $keyword . '_' . $uid . '_' . $gameId . '_' . $uGameId);

						if (empty($fansIds)) {
				        $fansIds = $this->game->getFansIdsByName(array("name" => $keyword));
							S('game_convert_search_' . $keyword . '_' . $uid . '_' . $gameId . '_' . $uGameId, $fansIds);
						}

						if ($fansIds) {
					       $where["fans_id"] = array("in", $fansIds);
						} else {
							$this->assign('empty', 1);

							$this->display();

							return false;
						}
					}

					$convertData = D('Game_convert')->where($where)->order('awards_level ASC, id ASC')->select();

					if (!empty($convertData)) {
				       $fansIdData = array(
					     "fansId" => array()
				      	    );

						foreach ($convertData as $convert) {
							array_push($fansIdData['fansId'], $convert['fans_id']);
						}

						$fansString = implode('_', $fansIdData['fansId']);
						$fansIdData['fansId'] = implode(', ', $fansIdData['fansId']);

						// 获取粉丝信息
						$fansData = S('game_convert_fans_data_' . $uid . '_' . $gameId . '_' . $uGameId . '_' . $fansString);
						if (empty($fansData)) {
							$fansData = $this->game->getUserInfo($fansIdData);
							$fansData = $this->valtokey($fansData, 'id');

							S('game_convert_fans_data_' . $uid . '_' . $gameId . '_' . $uGameId . '_' . $fansString, $fansData);
						}

						// 获取粉丝成绩信息
						$scoreData = S('game_convert_score_data_' . $uid . '_' . $gameId . '_' . $uGameId . '_' . $fansString);
						if (empty($scoreData)) {
							$data = $fansIdData;
							$data['uid'] = $uid;
							$data['game_id'] = $gameId;
							$data['u_game_id'] = $uGameId;
							$scoreData = $this->game->getUserScoreData($data);
							$scoreData = $this->valtokey($scoreData, 'fans_id');

							S('game_convert_score_data_' . $uid . '_' . $gameId . '_' . $uGameId . '_' . $fansString, $scoreData);
						}

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
							$gamePage = $count / $num;
						} else {
							$gamePage = floor($count / $num + 1);
						}

						//当前页数显示内容
						if ($this->_get('p') > 0) {
							$star = ($this->_get('p') - 1) * $num;
							$dataList = array_slice($convertData, $star, $num);
						} else {
							$dataList = array_slice($convertData, 0, $num);
						}

						// 页面底部页码显示逻辑
						if ($gamePage > 9) {
							if ($ps < 5) {
								$pageStar = 1;
								$pageEnd = 10;
							} else {
								$pageStar = $ps - 4;
								$pageEnd = $ps + 5;
							}
							if ($pageEnd > $gamePage) {
								$pageEnd = $gamePage;
								$pageStar = $pageEnd - 9;
							}
						} else {
							$pageStar = 1;
							$pageEnd = $gamePage;
						}

						$this->assign('empty', 0);

						$this->assign('converts', $dataList);
						$this->assign('fans', $fansData);
						$this->assign('scores', $scoreData);
					        $this->assign("ps", $ps);
					        $this->assign("p", $this->_get("p"));
						$this->assign('num', $num);
						$this->assign('pageStar', $pageStar);
						$this->assign('pageEnd', $pageEnd);
						$this->assign('gamePage', $gamePage);
					} else {
						$this->assign('empty', 1);
					}
				} else {
					$this->assign('finish', 0);
				}
			}

			// 获取游戏过程中获奖信息
			if ($convertType == 1) {
				$data = array(
					'uid'       => $uid,
					'game_id'   => $gameId,
					'u_game_id' => $uGameId,
				);
				$playingPrizeData = $this->game->getGamePlayingPrize($data);

				$this->assign('playingConverts', $playingPrizeData);
			}

			// 获取游戏结束后抽奖获奖信息
			if ($convertType == 2) {
				$data = array(
					'uid'       => $uid,
					'game_id'   => $gameId,
					'u_game_id' => $uGameId,
				);
				$overPrizeData = $this->game->getGameOverPrize($data);

				$this->assign('overConverts', $overPrizeData);
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
			$gameId = $this->_get('game_id', 'intval');
			$uGameId = $this->_get('id', 'intval');
			$key = $_GET['key'];
			$searchType = $this->_get('type', 'intval');
			$useType = $this->_get('use_type', 'intval');
			$awardsType = $this->_get('awards_type', 'intval');
			$keyword = htmlspecialchars(trim($_GET['keyword']));

			$gameSet = $this->game->gameSet($uid, $gameId, $uGameId, $key);

			// 获取活动奖品信息
			$awardsData = unserialize($gameSet['awards_value']);
			$awardsValue = $awardsData['awards_values'];
		        $where = array("uid" => $uid, "u_game_id" => $uGameId);

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
			    $fansIds = $this->game->getFansIdsByName(array("name" => $keyword));

			    $where["fans_id"] = array("in", $fansIds);
			}

			$convertData = D('Game_convert')->where($where)->select();
		        $fansIdData = array(
			        "fansId" => array()
		 	         );

			foreach ($convertData as $convert) {
				array_push($fansIdData['fansId'], $convert['fans_id']);
			}

			$fansIdData['fansId'] = implode(', ', $fansIdData['fansId']);

			// 获取粉丝信息
			$fansData = $this->game->getUserInfo($fansIdData);
			$fansData = $this->valtokey($fansData, 'id');

			// 获取粉丝成绩信息
			$data = $fansIdData;
			$data['uid'] = $uid;
			$data['game_id'] = $gameId;
			$data['u_game_id'] = $uGameId;
			$scoreData = $this->game->getUserScoreData($data);
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

			// 开始填充头部
			$objActSheet->setCellValue('A1', '参与者信息');
			$objActSheet->setCellValue('C1', '获奖信息');
			$objActSheet->setCellValue('D1', '兑换码');
			$objActSheet->setCellValue('E1', '兑换状态');
			$objActSheet->setCellValue('F1', '兑换时间');

			// 设置头部格式
			$objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objActSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objActSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objActSheet->getStyle('F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

			// 设置头部文字样式
			$objActSheet->getStyle('A1')->getFont()->setBold(true);
			$objActSheet->getStyle('C1')->getFont()->setBold(true);
			$objActSheet->getStyle('D1')->getFont()->setBold(true);
			$objActSheet->getStyle('E1')->getFont()->setBold(true);
			$objActSheet->getStyle('F1')->getFont()->setBold(true);

			$objActSheet->getColumnDimension('A1')->setAutoSize(true);
			$objActSheet->getColumnDimension('C1')->setAutoSize(true);
			$objActSheet->getColumnDimension('D1')->setAutoSize(true);
			$objActSheet->getColumnDimension('E1')->setAutoSize(true);
			$objActSheet->getColumnDimension('F1')->setAutoSize(true);

			$objActSheet->getColumnDimension('A')->setWidth(8);
			$objActSheet->getColumnDimension('B')->setWidth(20);
			$objActSheet->getColumnDimension('C')->setWidth(22);
			$objActSheet->getColumnDimension('D')->setWidth(22);
			$objActSheet->getColumnDimension('E')->setWidth(17);
			$objActSheet->getColumnDimension('F')->setWidth(22);

			$i = 2;
			foreach ($convertData as $key => $convert) {
				$start = $i;
				$end = $i + 1;
				$objActSheet->mergeCells('A' . $start . ':A' . $end);
				$objActSheet->mergeCells('C' . $start . ':C' . $end);
				$objActSheet->mergeCells('D' . $start . ':D' . $end);
				$objActSheet->mergeCells('E' . $start . ':E' . $end);
				$objActSheet->mergeCells('F' . $start . ':F' . $end);
				//				$objActSheet->mergeCells('G' . $start . ':G' . $end);

				$rank = $key + 1;
			$objActSheet->setCellValue("A" . $start, "# " . $rank . " ");
				//				$objActSheet->setCellValue('B' . $start, $fansData[$convert['fans_id']]['portrait']);
			$objActSheet->setCellValue("B" . $start, " " . $fansData[$convert["fans_id"]]["wechaname"] . " ");
				$objActSheet->setCellValue('B' . $end, '玩了' . $scoreData[$convert['fans_id']]['counts'] . '次，' . $scoreData[$convert['fans_id']]['fraction'] . '分');
				$objActSheet->setCellValue('C' . $start, $awardsValue[$convert['awards_level'] - 1]['awards_type'] . '：' . $awardsValue[$convert['awards_level'] - 1]['awards_name']);
				$objActSheet->setCellValue('D' . $start, 'NO. ' . $convert['convert_code']);
				$objActSheet->setCellValue('E' . $start, $convert['is_use'] == 0 ? '未兑换' : '已兑换');
			$objActSheet->setCellValue("F" . $start, $convert["is_use"] == 0 ? "——" : " " . $convert["convert_time"] . " ");

				// 设置数据对齐方式
				$objActSheet->getStyle('A' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('C' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('D' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('E' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objActSheet->getStyle('F' . $start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$objActSheet->getStyle('A' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('C' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('D' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('E' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objActSheet->getStyle('F' . $start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
			header('Content-Disposition:attachment;filename="convert_info.xls"');
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
			$data = array(
				'status' => false,
				'msg'    => '',
			);

			$convertData = D('Game_convert')->where(array('id' => $convertId))->find();
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
					D('Game_convert')->where(array('id' => $convertId))->save(array('has_send' => $has_send));

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
		        $p = $this->_get("p", "intval");
			$convertId = $this->_get('convert_id', 'intval');
			$convertCate = $this->_get('convert_cate', 'intval');
			$errorMsg = '';

			$convertData = D('Game_convert')->where(array('id' => $convertId))->find();
			$isUse = $convertData['is_use'];

			  if (!$isUse) {
				$goConvert = true;
				if ($convertCate == 1) {
					$convertCounts = $_GET['convert_counts'];

					$data = array('fans_id' => $convertData['fans_id'], 'token' => $this->token);
					$openIdData = $this->game->getTokenFansInfo($data);
					$openId = $openIdData['wecha_id'];

					$sendStatus = $this->sendRedPacket($convertId, $openId, $convertCounts);
					$goConvert = $sendStatus['status'];
					$errorMsg = $sendStatus['msg'];
				} elseif ($convertCate == 2) {
					$cardId = $_GET['card_id'];

					$data = array('fans_id' => $convertData['fans_id'], 'token' => $this->token);
					$openIdData = $this->game->getTokenFansInfo($data);
					$openId = $openIdData['wecha_id'];

					$wechaSend = new wechatSend();
				$sendStatus = $wechaSend->sendCard($this->token, $openId, $cardId);
				$goConvert = $sendStatus["status"];
				$errorMsg = $sendStatus["msg"];
					}
				} elseif ($convertCate == 3) {
					$points = $_GET['points'];

					$data = array('fans_id' => $convertData['fans_id'], 'token' => $this->token);
					$openIdData = $this->game->getTokenFansInfo($data);
					$openId = $openIdData['wecha_id'];

					$wechaSend = new wechatSend();
					if (!$wechaSend->sendScore($this->token, $openId, $points)) {
						$goConvert = false;
					}
				}

				if ($goConvert) {
					if (D('Game_convert')->where(array('id' => $convertId))->save(array('is_use' => 1, 'convert_time' => date('Y-m-d H:i:s', time())))) {
						$this->success('兑换成功', U('Game/convert', array('id' => $id, 'token' => $this->token, "p" => $p)));
					} else {
						$this->error('兑换失败', U('Game/convert', array('id' => $id, 'token' => $this->token, "p" => $p)));
					}
				} else {
					$this->error('兑换失败 ' . $errorMsg, U('Game/convert', array('id' => $id, 'token' => $this->token, "p" => $p)));
				}
			
		}

		public function otherConvert ()
		{
		        $action = $_GET["act"];
		        $p = $this->_get("p", "intval");
			$uGameId = $this->_get('id', 'intval');
			$fansId = $this->_get('fans_id', 'intval');
			$prizeId = $this->_get('convert_id', 'intval');
			$prizeType = $this->_get('type', 'intval');
			$convertType = $this->_get('convert_type', 'intval');

			$data = array(
				'u_game_id'    => $uGameId,
				'fans_id'      => $fansId,
				'prize_id'     => $prizeId,
				'prize_type'   => $prizeType,
				'convert_type' => $convertType,
			);
			$result = $this->game->convertFansPrize($data);

		if ($result["status"]) {
			$this->success("兑换成功", U("Game/convert" . $action, array("id" => $uGameId, "token" => $this->token, "fans_id" => $fansId, "convert_type" => $convertType)));
		}
		else {
			$this->error("兑换失败", U("Game/convert" . $action, array("id" => $uGameId, "token" => $this->token, "fans_id" => $fansId, "convert_type" => $convertType)));
			}
		}

		public function qrConvert ()
		{
		$uGameId = $this->_get("id", "intval");
		$gameId = $this->_get("gid", "intval");
			$url = $_POST['url'];
		$this->assign("id", $uGameId);
		$this->assign("gid", $gameId);

			if (!empty($url)) {
				$url = htmlspecialchars_decode($url);
				header('Location:' . $url);
				exit;
			} else {
				$this->display();
			}
		}
	public function refreshConvert()
	{
		$uGameId = $this->_get("id", "intval");
		$config = $this->_toConfig();
		$uid = $config["uid"];
		$thisItem = M("games")->where(array("id" => $uGameId, "token" => $this->token))->find();

		if (!empty($thisItem)) {
			if ($thisItem["end_time"] < time()) {
				$whereGet = array("uid" => $uid, "u_game_id" => $uGameId, "is_use" => 1);
				$convertGet = D("Game_convert")->where($whereGet)->select();

				if (!empty($convertGet)) {
					$this->error("操作失败，该活动已有人兑换过奖品", U("Game/convert", array("id" => $uGameId, "token" => $this->token)));
				}
				else {
					$where = array("uid" => $uid, "u_game_id" => $uGameId);
					$back = D("Game_convert")->where($where)->delete();

					if ($back == true) {
						$this->success("操作成功", U("Game/convert", array("id" => $uGameId, "token" => $this->token)));
					}
					else {
						$this->error("操作失败，请重新操作", U("Game/convert", array("id" => $uGameId, "token" => $this->token)));
					}
				}
			}
			else {
				$this->error("活动还没有结束，不能操作", U("Game/convert", array("id" => $uGameId, "token" => $this->token)));
			}
		}
		else {
			$this->error("操作失败，该活动不存在", U("Game/convert", array("id" => $uGameId, "token" => $this->token)));
		}
	}

		public function fansPrize ()
		{
			$fansPrizeList = array();
			$uGameId = $this->_get('id', 'intval');
			$fansId = $this->_get('fans_id', 'intval');

			$thisItem = M('games')->where(array('id' => $uGameId, 'token' => $this->token))->find();

			if (!empty($thisItem)) {
				$gameId = $thisItem['gameid'];

				$config = $this->_toConfig();
				$uid = $config['uid'];

				$data = array(
					'uid'       => $uid,
					'game_id'   => $gameId,
					'u_game_id' => $uGameId,
					'fans_id'   => $fansId,
				);
				$fansPrizeList = $this->game->getUserPrizeList($data);
			}

			$this->assign('id', $uGameId);
			$this->assign('fansId', $fansId);
			$this->assign('prizeList', $fansPrizeList);
			$this->display();
		}
	public function blacklist()
	{
		$uGameId = $this->_get("id", "intval");
		$gameId = $this->_get("gid", "intval");
		$config = $this->_toConfig();
		$uid = $config["uid"];
		$thisItem = M("games")->where(array("id" => $uGameId, "token" => $this->token))->find();
		$thisItem["share_value"] = unserialize($thisItem["share_value"]);
		$gameSet = $this->game->gameSet($uid, $gameId, $uGameId, $config["key"]);
		$isPhone = $gameSet["is_phone"];
		$thisGame = $this->game->getGame($gameId);
		$this->assign("gameCate", $thisGame["catid"]);
		$blackData = array("uid" => $uid, "game_id" => $gameId, "u_game_id" => $uGameId);
		$blacklistData = $this->game->blackList($blackData);
		$fansIdData = array(
			"fansId" => array()
			);

		foreach ($blacklistData as $blacklist ) {
			array_push($fansIdData["fansId"], $blacklist["fans_id"]);
		}

		$fansIdData["fansId"] = implode(", ", $fansIdData["fansId"]);
		$fansData = $this->game->getUserInfo($fansIdData);
		$fansData = $this->valtokey($fansData, "id");
		$data = $fansIdData;
		$data["uid"] = $config["uid"];
		$data["game_id"] = $gameId;
		$data["u_game_id"] = $uGameId;
		$scoreData = $this->game->getUserScoreData($data);
		$scoreData = $this->valtokey($scoreData, "fans_id");
		$shareData = $this->game->getShareData($data);
		$prizeData = $this->game->getPrizeData($data);
		$lastTimeData = $this->game->getLastTime($data);
		$count = count($blacklistData);
		$ps = $this->_get("p", "intval");

		if ($ps < 1) {
			$ps = 1;
		}

		$num = 15;

		if (is_int($count / $num)) {
			$gamePage = $count / $num;
		}
		else {
			$gamePage = floor(($count / $num) + 1);
		}

		if (0 < $this->_get("p")) {
			$star = ($this->_get("p") - 1) * $num;
			$dataList = array_slice($blacklistData, $star, $num);
		}
		else {
			$dataList = array_slice($blacklistData, 0, $num);
		}

		if (9 < $gamePage) {
			if ($ps < 5) {
				$pageStar = 1;
				$pageEnd = 10;
			}
			else {
				$pageStar = $ps - 4;
				$pageEnd = $ps + 5;
			}

			if ($gamePage < $pageEnd) {
				$pageEnd = $gamePage;
				$pageStar = $pageEnd - 9;
			}
		}
		else {
			$pageStar = 1;
			$pageEnd = $gamePage;
		}

		$this->assign("id", $uGameId);
		$this->assign("gid", $gameId);
		$this->assign("ps", $ps);
		$this->assign("pageStar", $pageStar);
		$this->assign("pageEnd", $pageEnd);
		$this->assign("isPhone", $isPhone);
		$this->assign("records", $dataList);
		$this->assign("fans", $fansData);
		$this->assign("shareData", $shareData);
		$this->assign("prizeData", $prizeData);
		$this->assign("lastTimeData", $lastTimeData);
		$this->assign("game", $thisItem);
		$this->assign("scores", $scoreData);
		$this->assign("gamePage", $gamePage);
		$this->display();
	}

	public function addToBlacklist()
	{
		$uGameId = $_POST["id"];
		$gameId = $_POST["gid"];
		$fansId = $_POST["fansId"];
		$config = $this->_toConfig();
		$uid = $config["uid"];
		$thisItem = M("games")->where(array("id" => $uGameId, "token" => $this->token))->find();

		if (!empty($thisItem)) {
			if ($thisItem["end_time"] < time()) {
				$whereGet = array("uid" => $uid, "u_game_id" => $uGameId, "is_use" => 1);
				$convertGet = D("Game_convert")->where($whereGet)->select();

				if (empty($convertGet)) {
					$whereUser = array("uid" => $uid, "u_game_id" => $uGameId, "fans_id" => $fansId);
					$cfin = D("Game_convert")->where($whereUser)->find();

					if (!empty($cfin)) {
						$where = array("uid" => $uid, "u_game_id" => $uGameId);
						$back = D("Game_convert")->where($where)->delete();

						if (!$back) {
							exit(json_encode(array("status" => 41004)));
						}
					}
				}
			}
		}

		echo $this->game->addToBlacklist(array("uid" => $uid, "game_id" => $gameId, "u_game_id" => $uGameId, "fans_id" => $fansId));
	}

	public function deleteFromBlacklist()
	{
		$uGameId = $_POST["id"];
		$gameId = $_POST["gid"];
		$fansId = $_POST["fansId"];
		$config = $this->_toConfig();
		$uid = $config["uid"];
		echo $this->game->deleteFromBlacklist(array("uid" => $uid, "game_id" => $gameId, "u_game_id" => $uGameId, "fans_id" => $fansId));
	}

		public function statistics ()
		{
			$uGameId = $this->_get('id', 'intval');
		$gameId = $this->_get("gid", "intval");
		        $thisItem = M("games")->where(array("id" => $uGameId, "token" => $this->token))->find();

			$gameId = intval($thisItem['gameid']);

			$config = $this->_toConfig();
			$uid = $config['uid'];

			$thisGame = $this->game->getGame(intval($gameId));
			$gameSet = $this->game->gameSet($uid, $gameId, $uGameId, $config['key']);
			$hasGamePlaying = $gameSet['game_playing'];
			$hasGameOver = $gameSet['game_over'];
			$this->assign('gameCate', $thisGame['catid']);
			$this->assign('hasGamePlaying', $hasGamePlaying);
			$this->assign('hasGameOver', $hasGameOver);
			$this->assign('id', $uGameId);
		$this->assign("gid", $gameId);
			$this->assign('thisGame', $thisGame);
			$this->assign('game', $gameSet);

			// 获取活动奖品信息
			$awardsData = unserialize($gameSet['awards_value']);
			$awardsValue = $awardsData['awards_values'];
			$awardsCount = $awardsData['awards_count'];

			$this->assign('awardsValue', $awardsValue);
			$this->assign('awardsCount', $awardsCount);

			// 获取奖品兑换信息
			$convertCodeData = D('Game_convert')->getConvertUseData($uid, $uGameId);

			$this->assign('codeData', $convertCodeData);

			// 获得过程中奖和随机抽奖的奖品信息
			$data = array(
				'uid'       => $uid,
				'game_id'   => $gameId,
				'u_game_id' => $uGameId,
			);

			if ($hasGamePlaying || $hasGameOver) {
				$otherPrizeData = $this->game->getOtherPrize($data);
				$this->assign('otherPrizeData', $otherPrizeData);
			}

			// 获取游戏统计数据
			$totalStatistics = $this->game->statistics($data);
			$this->assign('totalStatistics', $totalStatistics);

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

			$checkStatistics = $this->game->statistics($data);
			$this->assign('checkStatistics', $checkStatistics);

		        $dateList = array();

			for ($i = strtotime($startTime); $i <= strtotime($endTime); $i += 24 * 60 * 60) {
				array_push($dateList, date('Y-m-d', $i));
			}

			$checkStatisticsList = $this->game->statisticsList($data);
		        $statisticsList = array("xAxis" => "", "open" => "", "join" => "", "share" => "", "shareCount" => "");

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

		function gamearr ()
		{
			S('game_' . $this->_get('token') . '_' . $this->_get('id'), $_POST);
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
