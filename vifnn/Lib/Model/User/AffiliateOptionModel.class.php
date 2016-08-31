<?php
/**
 * 推广设置模型类
 * @see [验证规则] http://doc.thinkphp.cn/manual/auto_validate.html
 */
class AffiliateOptionModel extends Model
{
    // 表名
    protected $tableName = 'affiliate_option';

    // 设置验证规则
    // 当使用系统的 create() 方法创建数据对象的时候会自动进行数据验证操作
    // 更新则不会自动验证，坑！！！
    protected $_validate = [
        ['wxuser_id', 'number', '公众号ID错误', Model::MUST_VALIDATE],
        ['status', [0, 1], '状态错误', Model::MUST_VALIDATE, 'in'],
        ['qrcode_type', [0, 1], '二维码类别错误', Model::MUST_VALIDATE, 'in'],
        ['auto_group_id', 'number', '推广员自动分组错误', Model::EXISTS_VALIDATE],
        ['validity', 'number', '推广有效期错误', Model::EXISTS_VALIDATE],
        ['min_cash_amount', 'currency', '最低提现金额错误', Model::EXISTS_VALIDATE],
        ['register_commission', 'currency', '关注佣金错误', Model::EXISTS_VALIDATE],
        ['sale_commission', 'checkSaleCommission', '销售佣金错误', Model::MUST_VALIDATE],
    ];

    // 销售佣金可以是 1.2 或 1.2%
    protected function checkSaleCommission($data)
    {
        return preg_match('/^\d+(.\d{1,2})?%?$/', $data);
    }
}
