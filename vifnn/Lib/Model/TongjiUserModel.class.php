<?php
class TongjiUserModel extends Model
{
	public function getMark($token, $wechaId)
	{
		if (!empty($wechaId)) {
			$info = session('tongji_self_info');
			if (!empty($info) && $info['wecha_id'] == $wechaId && $info['token'] == $token) {
				return $info['mark'];
			}
			$where['token'] = $token;
			$where['wecha_id'] = $wechaId;
			$res = $this->where($where)->field('id,mark')->find();
			if (!empty($res)) {
				session('tongji_self_info', array('mark' => $res['mark'], 'wecha_id' => $wechaId, 'token' => $token));
				return $res['mark'];
			}
			$where['mark'] = md5(uniqid() . get_ucode(8, '1Aa'));
			$where['create_time'] = time();
			$id = $this->add($where);
			if (!$id) {
				return false;
			}
			session('tongji_self_info', array('mark' => $where['mark'], 'wecha_id' => $wechaId, 'token' => $token));
			return $where['mark'];
		} else {
			$token = '^tongji_cookie_20160720$';
			$visitorKey = cookie('tongji_visitor_key');
			if (empty($visitorKey)) {
				return $this->mkCookie(md5(uniqid() . get_ucode(8, '1Aa')), $token);
			} else {
				$arr = json_decode(base64_decode($visitorKey), true);
				if (is_array($arr) && isset($arr['mark'])) {
					if (isSign($arr['sign'], $arr, $token)) {
						return $arr['mark'];
					} else {
						return $this->mkCookie(md5(uniqid() . get_ucode(8, '1Aa')), $token);
					}
				} else {
					$where['visitor_key'] = $visitorKey;
					$res = $this->where($where)->field('id,mark')->find();
					if (!empty($res)) {
						$this->where($where)->delete();
						return $this->mkCookie($res['mark'], $token);
					} else {
						return $this->mkCookie(md5(uniqid() . get_ucode(8, '1Aa')), $token);
					}
				}
			}
		}
	}
	private function mkCookie($mark, $token)
	{
		$data['mark'] = $mark;
		$data['sign'] = generate_sign($data, $token);
		cookie('tongji_visitor_key', base64_encode(json_encode($data)), 3600 * 24 * 365);
		return $mark;
	}
}