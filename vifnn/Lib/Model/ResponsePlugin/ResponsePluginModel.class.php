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
     * 目前插件只支持 text, event 两类消息进行插件回复
     * @return mixed
     */
    public function run()
    {
        if (empty($this->wxuser) || empty($this->data)) {
            return false;
        }

        // 文本消息回复
        if ($this->data['MsgType'] == 'text') {
            $plugin = M('affiliate_response_plugin')->where(array(
                'wxuser_id' => $this->wxuser['id'],
                'is_enable' => 1,
                'keyword' => $this->data['Content'],
            ))->order('`plugin_id` asc')->limit(0, 1)->find();

            if (empty($plugin)) {
                return false;
            }

            $plugin_model_name = $plugin['model_name'] . 'Model';
            import('@.Model.ResponsePlugin.' . $plugin_model_name);
            if (class_exists($plugin_model_name)) {
                $model = new $plugin_model_name($this->wxuser, $this->data);
                return $model->reply();
            } else {
                return array('插件【' . $plugin['plugin_name'] . '】核心响应文件不存在，请联系客服处理！', 'text');
            }
        }

        // 事件消息回复
        if ($this->data['MsgType'] == 'event') {
            // ...
        }
    }

}
