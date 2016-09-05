<?php
/**
 * 回复插件管理类
 */
class AffiliateResponsePluginAction extends UserAction
{
    /**
     * @var 可用的插件列表
     */
    public $plugin_available;

    public function _initialize()
    {
        parent::_initialize();
        
        // 登录超时后未刷新框架页面时会导致公众号信息丢失
        if (!is_array($this->wxuser) || empty($this->wxuser) || empty($this->wxuser['id'])) {
            return $this->error('公众号参数丢失，请刷新当前框架页面并重新登录！');
        }

        // 当新制作插件的时候在此配置新插件的参数
        // 插件模型存放目录 ./vifnn/Lib/Model/ResponsePlugin/
        $this->plugin_available = array(
            'banner' => array(
                // 非数据库字段
                'icon_cls' => 'glyphicon-picture',
                'desc' => '粉丝发送特定的关键字获取带参数的推广海报二维码',

                // 数据库字段
                'plugin_id' => 'banner',
                'plugin_name' => '推广海报',
                'action_name' => 'AffiliateBanner',
                'model_name' => 'AffiliateBanner',
                'keyword' => '',
                'is_enable' => '0',
            ),
        );
    }

    /**
     * VIEW 首页
     */
    public function index()
    {
        $model = M('affiliate_response_plugin');

        $where = array();
        $where['wxuser_id'] = $this->wxuser['id'];
        
        $count = $model->where($where)->count();
        $page = new Page($count, 2000);  // 插件较少，暂不考虑分页列表

        $data_provider = $model->where($where)->order('`plugin_id` asc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('data_provider', $data_provider);
        $this->assign('page', $page->show());

        $this->display();
    }

    /**
     * VIEW 添加插件
     */
    public function add()
    {
        $model = M('affiliate_response_plugin');

        // 添加插件到数据库
        if (IS_POST) {
            $plugin_id = $this->_post('plugin_id', 'string');
            if (!in_array($plugin_id, array_keys($this->plugin_available))) {
                return $this->error('您要添加的插件不存在！');
            }
            
            $rs = $model->where(array(
                    'wxuser_id' => $this->wxuser['id'],
                    'plugin_id' => $plugin_id,
                ))->find();
            if (!empty($rs)) {
                return $this->error('该插件已添加，请勿重复添加！');
            }

            $data = array(
                'wxuser_id' => $this->wxuser['id'],
                'plugin_id' => $plugin_id,
                'plugin_name' => $this->plugin_available[$plugin_id]['plugin_name'],
                'action_name' => $this->plugin_available[$plugin_id]['action_name'],
                'model_name' => $this->plugin_available[$plugin_id]['model_name'],
                'keyword' => $this->plugin_available[$plugin_id]['keyword'],
                'is_enable' => $this->plugin_available[$plugin_id]['is_enable'],
            );

            if ($model->add($data)) {
                return $this->success('添加成功', U(MODULE_NAME . '/index', array('_rand' => time())));
            } else {
                return $this->error('添加插件失败，请稍候重试！');
            }
        }

        $exists_plugins = array();
        $sql = 'select `plugin_id` from `vifnn_affiliate_response_plugin` where `wxuser_id` = ' . $this->wxuser['id'];
        $rs = $model->query($sql);
        if (!empty($rs)) {
            foreach ($rs as $row) {
                $exists_plugins[] = $row['plugin_id'];
            }
        }

        $this->assign('plugin_available', $this->plugin_available);
        $this->assign('exists_plugins', $exists_plugins);
        $this->display();
    }

    /**
     * ACTION 启用插件
     */
    public function enable()
    {
        $plugin_id = $this->_get('id', 'string');
        $data = array(
            'wxuser_id' => $this->wxuser['id'],
            'plugin_id' => $plugin_id,
            'is_enable' => 1,
        );
        M('affiliate_response_plugin')->save($data);
        $this->success('启用成功');
    }

    /**
     * ACTION 禁用插件
     */
    public function disable()
    {
        $plugin_id = $this->_get('id', 'string');
        $data = array(
            'wxuser_id' => $this->wxuser['id'],
            'plugin_id' => $plugin_id,
            'is_enable' => 0,
        );
        M('affiliate_response_plugin')->save($data);
        $this->success('禁用成功');
    }

    /**
     * ACTION 设置关键字
     */
    public function setkeyword()
    {
        $plugin_id = $this->_post('plugin_id', 'string');
        $keyword = $this->_post('keyword', 'string');
        $data = array(
            'wxuser_id' => $this->wxuser['id'],
            'plugin_id' => $plugin_id,
            'keyword' => $keyword,
        );
        M('affiliate_response_plugin')->save($data);
        $this->success('设置关键字成功');
    }

    /**
     * ACTION 删除插件
     */
    public function delete()
    {
        $plugin_id = $this->_get('id', 'string');
        $data = array(
            'wxuser_id' => $this->wxuser['id'],
            'plugin_id' => $plugin_id,
        );
        M('affiliate_response_plugin')->where($data)->delete();
        $this->success('删除成功');
    }
}
