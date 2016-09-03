<?php
/**
 * 推广参数设置
 * @see [生成呆参数的二维码] https://mp.weixin.qq.com/wiki/18/167e7d94df85d8389df6c94a7a8f78ba.html
 */
class AffiliateOptionAction extends UserAction
{
    public $affiliate_option;

    public function _initialize()
    {
        parent::_initialize();
        
        // 登录超时后未刷新框架页面时会导致公众号信息丢失
        if (!is_array($this->wxuser) || empty($this->wxuser) || empty($this->wxuser['id'])) {
            return $this->error('公众号参数丢失，请刷新当前框架页面并重新登录！');
        }

        // 初始化推广参数
        $this->affiliate_option = M('Affiliate_option')->where(array('wxuser_id' => $this->wxuser['id']))->find();
        if (empty($this->affiliate_option)) {
            $data = array(
                'wxuser_id' => $this->wxuser['id'],
                'sale_commission' => '',
            );
            M('Affiliate_option')->add($data);
            $this->affiliate_option = M('Affiliate_option')->where(array('wxuser_id' => $this->wxuser['id']))->find();
        }
    }

    public function index()
    {
        if (IS_POST) {
            return $this->updateOptions();
        }

        $groups = M('Wechat_group')->where(array('token' => $this->token))->order('id ASC')->select();
        $validity = array(
            array(0, '永久'),
            array(864000, '10天'),
            array(1728000, '20天'),
            array(2592000, '30天'),
            array(5184000, '60天'),
            array(7776000, '90天'),
            array(15552000, '180天'),
            array(31104000, '360天'),
        );
        $this->assign('validity', $validity);
        $this->assign('groups', $groups);
        $this->assign('option', $this->affiliate_option);
        $this->display();
    }

    private function updateOptions()
    {
        $data2save = array();
        $exists_fields = array(
            'status', 'qrcode_type', 'auto_group_id', 'validity', 'min_cash_amount', 'register_commission', 'sale_commission'
        );
        foreach ($_POST as $key => $value) {
            if (in_array($key, $exists_fields)) {
                $data2save[$key] = $value;
            }
        }

        if (!empty($data2save)) {
            if (isset($data2save['sale_commission'])) {
                if (!preg_match('/^\d+(.\d{1,2})?%?$/', $data2save['sale_commission'])) {
                    return $this->error('订单佣金错误，请输入数字或百分比！');
                }
            }

            $data2save['wxuser_id'] = $this->wxuser['id'];
            if (M('Affiliate_option')->save($data2save)) {
                return $this->success('恭喜，设置成功！');
            } else {
                return $this->success('您未更新或保存失败！');
            }
        } else {
            return $this->error('您没有提交可用的数据。');
        }
    }

}
