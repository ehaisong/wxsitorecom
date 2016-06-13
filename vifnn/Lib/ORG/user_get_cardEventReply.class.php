<?php

class user_get_cardEventReply {

    public $token;
    public $wecha_id;
    public $thisWxUser = array();
    public $data;

    public function __construct($token, $wecha_id, $data, $siteurl) {

        $this->token = $token;
        $this->wecha_id = $wecha_id;
        $this->thisWxUser = M('Wxuser')->field('appid,appsecret')->where(array('token' => $token))->find();
        $this->data = $data;
    }

    public function index() {
        $card = array(
            'token' => $this->token,
            'wecha_id' => $this->data['FromUserName'],
            'card_id' => $this->data['CardId'],
        );

        $info = M('Member_card_coupon')->where(array('token' => $this->token, 'card_id' => $this->data['CardId']))->find();
        if (!empty($info)) {
            if ($info['type'] == 1) {
                $card['coupon_type'] = '1';
            } else if ($info['type'] == 2) {
                $card['coupon_type'] = '3';
            } else {
                $card['coupon_type'] = '2';
            }
            $card['add_time'] = $this->data['CreateTime'];
            $card['coupon_id'] = $info['id'];
            $card['cardid'] = $info['cardid'];
            $card['token'] = $this->token;
            $card['wecha_id'] = $this->data['FromUserName'];
            $card['cancel_code'] = $this->data['UserCardCode'];
            $card['company_id'] = $info['company_id'];

            if ($info['type'] == 1) {
                $card['coupon_attr'] = serialize(array('coupon_name' => $info['title']));
            } else if ($info['type'] == 2) {
                $card['coupon_attr'] = serialize(array('coupon_name' => $info['title'], 'gift_name' => $info['gift_name'], 'integral' => $info['integral']));
            } else {
                $card['coupon_attr'] = serialize(array('coupon_name' => $info['title'], 'least_cost' => $info['least_cost'], 'reduce_cost' => $info['reduce_cost']));
            }

            if (M('Member_card_coupon_record')->add($card)) {
                if ($info['type'] == 2) {
                    $arr['itemid'] = $info['id'];
                    $arr['wecha_id'] = $this->data['FromUserName'];
                    $arr['time'] = time();
                    $arr['token'] = $this->token;
                    $arr['cat'] = 2;
                    $arr['staffid'] = 0;
                    $arr['usecount'] = 1;
                    $arr['score'] = $info['integral'] * -1;
                    $arr['notes'] = '积分兑换礼品券';
                    $arr['company_id'] = $info['company_id'];
                    $arr['cardid'] = $info['cardid'];
                    //更新记录
                    if (M('Member_card_use_record')->add($arr)) {
                        M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->setDec('total_score', $info['integral']);
                    }
                }
                M('Member_card_coupon')->where(array('token' => $this->token, 'id' => $info['id']))->setDec('total', 1);
            }
        }

        $wxcouponDb = M('cashier_wxcoupon');
        $wherearr = array('card_id' => $this->data['CardId']);

        $storeid = isset($this->data['OuterId']) && !empty($this->data['OuterId']) ? $this->data['OuterId'] : 0;

        $wx_coupon = $wxcouponDb->where($wherearr)->find();
        $cardtype = isset($wx_coupon['card_type']) ? $wx_coupon['card_type'] : 0;
        !empty($wx_coupon['kqexpand']) && $wx_coupon['kqexpand'] = unserialize($wx_coupon['kqexpand']);
        if ($cardtype == 5 && $wx_coupon['kqexpand']['auto_activate']) {
            if ($wx_coupon['kqexpand']['supply_bonus']) {
                $jsonData = '{"code": "' . $this->data['UserCardCode'] . '","card_id":"' . $this->data['CardId'] . '","add_bonus": ' . $wx_coupon['kqexpand']['bonus_rule']['init_increase_bonus'] . '}';
                $apiOauth = new apiOauth();
                $access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
                $apiOauth->update_user_card($access_token, $jsonData);
            }
        }
        $outerid = 0;
        if ($wx_coupon) {
            $cardtype = 0;
            $cardtype = $wx_coupon['card_type'];
            $outerid = $wx_coupon['mid'];
            $cardtitle = $wx_coupon['card_title'];
            $receivenum = $wx_coupon['receivenum'] + 1;
            $wxcouponDb->where(array('id' => $wx_coupon['id']))->save(array('receivenum' => $receivenum));

            $insertData = array('openid' => $this->data['FromUserName'],
                'give_openId' => isset($this->data['FromUserName']) ? $this->data['FromUserName'] : '',
                'cardid' => $this->data['CardId'], 'cardtype' => $cardtype, 'cardtitle' => $cardtitle, 'isgivebyfriend' => $this->data['IsGiveByFriend'], 'cardcode' => $this->data['UserCardCode'], 'oldcardcode' => $this->data['OldUserCardCode'], 'outerid' => $outerid, 'storeid' => $storeid, 'addtime' => time());

            $wxcoupon_receiveDb = M('cashier_wxcoupon_receive');
            $wherearr = array('cardid' => $this->data['CardId'], 'openid' => $this->data['FromUserName'], 'cardcode' => $this->data['UserCardCode']);

            $receivetmp = $wxcoupon_receiveDb->where($wherearr)->find();
            if (empty($receivetmp)) {
                $wxcoupon_receiveDb->add($insertData);
            } else {
                return array('', 'text');
            }
            $fansDb = M('cashier_fans'); /*             * *加入粉丝表** */
            $tmpfans = $fansDb->where(array('mid' => $wx_coupon['mid'], 'openid' => $this->data['FromUserName']))->find();
            if (empty($tmpfans)) {
                $userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->find();
                $fansData = array('mid' => $wx_coupon['mid'], 'openid' => $this->data['FromUserName']);
                if (!empty($userinfo)) {
                    $fansData['nickname'] = !empty($userinfo['wechaname']) ? $userinfo['wechaname'] : $userinfo['truename'];
                    $fansData['sex'] = $userinfo['sex'];
                    $fansData['province'] = $userinfo['province'];
                    $fansData['city'] = $userinfo['city'];
                    $fansData['headimgurl'] = $userinfo['portrait'];
                }
                $fansDb->add($fansData);
            }
        }
        return array('', 'text');
    }

}
