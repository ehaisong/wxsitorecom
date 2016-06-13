<?php

class HelpingScene extends BaseScene
{
	public function add_share()
	{
		$get = $this->data['get'];
		$now = time();
		$share_key = $get['share_key'];
		$id = $get['id'];
		$helping = S($id . 'helping' . $this->token);

		if ($helping == '') {
			$helping = M('Helping')->where(array('token' => $this->token, 'id' => $id, 'is_open' => 1))->find();
			S($id . 'helping' . $this->token, $helping);
		}

		if (empty($helping)) {
			return false;
		}

		$shareUser = M('Helping_user')->where(array('token' => $this->token, 'share_key' => $share_key))->find();
		$autoError = '';
		if (empty($shareUser) || ($shareUser['wecha_id'] == $this->wecha_id)) {
			$autoError = '助力失败！分享参数错误';
		}
		else if ($now < $helping['start']) {
			$autoError = '助力失败！活动还没开始';
		}
		else if ($helping['end'] < $now) {
			$autoError = '助力失败！活动已结束';
		}
		else {
			$is_share = M('Helping_record')->where(array('pid' => $shareUser['pid'], 'wecha_id' => $this->wecha_id, 'share_key' => $share_key))->count();

			if (0 < $is_share) {
				$autoError = '助力失败！您已经助力过了';
			}
		}

		if ($autoError == '') {
			$record = array('token' => $this->token, 'pid' => $shareUser['pid'], 'share_key' => $share_key, 'addtime' => time(), 'wecha_id' => $this->wecha_id);

			if (M('Helping_record')->add($record)) {
				M('Helping_user')->where(array('token' => $this->token, 'pid' => $helping['id'], 'share_key' => $share_key))->setInc('help_count', 1);
				if (($shareUser['add_time'] == 0) || ($shareUser['add_time'] == '')) {
					M('Helping_user')->where(array('token' => $this->token, 'pid' => $helping['id'], 'share_key' => $share_key))->save(array('add_time' => time()));
				}
			}
			else {
				$autoError = '助力失败！';
			}
		}

		$desc = ($autoError == '' ? '助力成功！你的好友成功增加了1点助力值' : $autoError);
		$url = $this->siteUrl . $this->data['url'];
		$obj[0] = array(
	array($helping['title'], $desc, img_url($helping['reply_pic'], '', '', $this->siteUrl), $url)
	);
		$obj[1] = 'news';
		return $obj;
	}
}

?>
