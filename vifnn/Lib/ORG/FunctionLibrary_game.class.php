<?php


class FunctionLibrary_game
{
	public $sub;
	public $token;
	public function __construct($token, $sub)
	{
		$this->sub = $sub;
		$this->token = $token;
	}
	public function index()
	{
		if (!$this->sub) {
			return array('name' => '游戏互动营销', 'subkeywords' => 1, 'sublinks' => 1);
		} else {
			$game = new game();
			$items = M('Games')->where(array('token' => $this->token))->select();
			$arr = array('name' => '游戏互动营销', 'subkeywords' => array(), 'sublinks' => array());
			if ($items) {
				foreach ($items as $v) {
					$link = $game->getLink($v, '{wechat_id}');
					$arr['subkeywords'][$v['id']] = array('name' => $v['title'], 'keyword' => $v['keyword']);
					$arr['sublinks'][$v['id']] = array('name' => $v['title'], 'link' => $link);
				}
			}
			return $arr;
		}
	}
}


?>
