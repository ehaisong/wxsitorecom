<?php
/**
 * 回复插件核心类
 * @see ./vifnn/Lib/ORG/SCANEventReply.class.php 扫码事件插口
 * @see ./vifnn/Lib/Action/Home/Weixin.class.php 关键字回复插口
 */
class ResponsePluginModel extends Model
{
    /**
     * @var 是否开启日志记录
     */
    public $log_open = false;
    /**
     * @var 当前微信公众号
     */
    public $wxuser;
    /**
     * @var 微信端发送来的数据
     */
    public $data;

    /**
     * 初始化
     */
    public function __construct($wxuser, $data)
    {
        $this->wxuser = $wxuser;
        $this->data = $data;
    }

    /**
     * 运行插件回复
     * @return mixed
     */
    public function run()
    {
        if (empty($wxuser) || empty($data)) {
            return false;
        }

        // 根据关键字找出可用的插件
        if ($data['MsgType'] == 'text') {
            $plugin = M('affiliate_response_plugin')->where(array(
                'wxuser_id' => $this->wxuser['id'],
                'is_enable' => 1,
                'keyword' => $data['Content'],
            ))->order('`plugin_id` asc')->find();

            return array(join(',', $plugin), 'text');
        }
    }

}
