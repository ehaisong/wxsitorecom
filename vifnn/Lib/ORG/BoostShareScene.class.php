<?php


class BoostShareScene extends BaseScene
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
			$uBoostId = $keywordData['pid'];
			$thisBoost = M('Boosts')->where(array('id' => $uBoostId))->find();
			if (!empty($thisBoost)) {
				$userInfo = $this->getUserInfo();
				$uid = $userInfo['uid'];
				$boostId = $thisBoost['boostid'];
				$title = $thisBoost['title'];
				$desc = $thisBoost['intro'];
				$picUrl = $thisBoost['picurl'];
				$link = $this->siteUrl . '/index.php?g=Wap&m=Boost&a=jump&token=' . $this->token . '&id=' . $boostId . '&uid=' . $uid . '&uboostid=' . $uBoostId . '&wid=' . $this->wecha_id . '&ffid=' . $fromId;
				$obj = array(array(array($title, $desc, $picUrl, $link)), 'news');
			}
		}
		return $obj;
	}
}