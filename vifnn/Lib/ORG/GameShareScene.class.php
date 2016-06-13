<?php

class GameShareScene extends BaseScene
{
	private function getUserInfo()
	{
		$config = M('Game_config')->where(array('token' => $this->token))->find();
		return $config;
	}

	public function getShare()
	{
		$obj = '';
		$keyword = $this->data['keyword'];
		$fromId = $this->data['from_id'];
		$like['keyword'] = $keyword;
		$like['precisions'] = 1;
		$like['token'] = $this->token;
		$keywordData = M('keyword')->where($like)->order('id desc')->find();

		if (!$keywordData) {
			$like['keyword'] = array('like', '%' . $keyword . '%');
			$like['precisions'] = 0;
			$keywordData = M('keyword')->where($like)->order('id desc')->find();
		}

		if (!empty($keywordData)) {
			$uGameId = $keywordData['pid'];
			$thisGame = M('Games')->where(array('id' => $uGameId))->find();

			if (!empty($thisGame)) {
				$userInfo = $this->getUserInfo();
				$uid = $userInfo['uid'];
				$gameId = $thisGame['gameid'];
				$title = $thisGame['title'];
				$desc = $thisGame['intro'];
				$picUrl = $thisGame['picurl'];
				$link = $this->siteUrl . '/index.php?g=Wap&m=Game&a=jump&token=' . $this->token . '&id=' . $gameId . '&uid=' . $uid . '&ugameid=' . $uGameId . '&wid=' . $this->wecha_id . '&ffid=' . $fromId;
				$obj = array(
					array(
						array($title, $desc, $picUrl, $link)
						),
					'news'
					);
			}
		}

		return $obj;
	}
}

?>
