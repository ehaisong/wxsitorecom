<?php
/**
 * 推广素材/海报管理类
 */
class AffiliateBannerAction extends UserAction
{
    /**
     * INIT 初始化
     */
    public function _initialize(){
        parent::_initialize();

        // 登录超时后未刷新框架页面时会导致公众号信息丢失
        if (!is_array($this->wxuser) || empty($this->wxuser) || empty($this->wxuser['id'])) {
            return $this->error('公众号参数丢失，请刷新当前框架页面并重新登录！');
        }
    }

    /**
     * VIEW 首页
     */
    public function index()
    {
        $model = M('affiliate_banner');

        $where = array();
        $where['wxuser_id'] = $this->wxuser['id'];
        
        $count = $model->where($where)->count();
        $page = new Page($count, 20);

        $banner = $model->where($where)->order('`id` desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('page', $page->show());
        $this->assign('banner', $banner);
        
        $this->display();
    }

    /**
     * VIEW 添加/编辑/制作推广海报
     */
    public function make()
    {
        if (IS_POST) {
            return $this->smartysave();
        }

        // 如果传入ID，则编辑对应的海报
        $banner = array(
            'id' => 0,
            'name' => '',
            'filepath' => '',
            'x1' => 0,
            'y1' => 0,
            'x2' => 100,
            'y2' => 100,
            'is_enable' => 0,
        );
        $id = $this->_get('id','intval');
        if (!empty($id)) {
            $where = array(
                'id' => $id,
                'wxuser_id' => $this->wxuser['id'],
            );
            $banner = M('affiliate_banner')->where($where)->find();
            if (empty($banner)) {
                return $this->error('没有找到您要编辑的海报！');
            }
        }

        $this->assign('wxuser', $this->wxuser);
        $this->assign('banner', $banner);
        $this->display();
    }

    /**
     * 禁用海报
     * @return boolean
     */
    public function disable()
    {
        $id = $this->_get('id', 'intval');

        $sql = 'update `vifnn_affiliate_banner` set `is_enable` = 0 where `wxuser_id` = ' . $this->wxuser['id'];
        $sql .= ' and `id` = ' . $id;

        M('affiliate_banner')->query($sql);
        $this->success('禁用海报成功', U(MODULE_NAME . '/index', array('_rand' => time())));
    }

    /**
     * 启用海报
     * @return boolean
     */
    public function enable()
    {
        $id = $this->_get('id', 'intval');

        $sql = 'update `vifnn_affiliate_banner` set `is_enable` = 1 where `wxuser_id` = ' . $this->wxuser['id'];
        $sql .= ' and `id` = ' . $id;

        M('affiliate_banner')->query($sql);
        $this->disableAll($id);
        $this->success('启用海报成功', U(MODULE_NAME . '/index', array('_rand' => time())));
    }

    /**
     * 添加或更新海报数据
     * @return boolean
     */
    private function smartysave()
    {
        $data = array();
        $data['name'] = $this->_post('name', 'string');
        $data['filepath'] = $this->_post('filepath', 'string');
        $data['x1'] = $this->_post('x1', 'integer');
        $data['y1'] = $this->_post('y1', 'integer');
        $data['x2'] = $this->_post('x2', 'integer');
        $data['y2'] = $this->_post('y2', 'integer');
        $data['is_enable'] = $this->_post('is_enable', 'integer');
        if (empty($data['is_enable'])) {
            $data['is_enable'] = 0;
        }

        if (preg_match('/^http(s)?:/\/\/', $data['filepath'])) {
            $data['filepath'] = preg_replace('/^http(s)?:\/\//', '', $data['filepath']);
            $pathinfo = explode('/', $data['filepath']);
            array_shift($pathinfo);
            $data['filepath'] = join('/', $pathinfo);
        }

        $abspath = THINK_PATH . $data['filepath'];
        if (!file_exists($abspath)) {
            return $this->error('素材文件不存在！' . $abspath);
        }
        $im = getimagesize($abspath);
        $data['origin_width'] = $im[0];
        $data['origin_height'] = $im[1];
        if (!in_array($im[2], array(2, 3))) {    // 2 jpg, 3 png
            return $this->error('素材文件必须是JPG或PNG格式！');
        }

        // 添加或更新判断
        $id = $this->_post('id', 'integer');
        if (empty($id)) {
            $data['wxuser_id'] = $this->wxuser['id'];
            $save_id = M('affiliate_banner')->add($data);
            if ($save_id) {
                // 禁用所有其他素材
                if ($data['is_enable'] == 1) {
                    $this->disableAll($save_id);
                }
                return $this->success('恭喜，发布海报成功！', U(MODULE_NAME . '/index'));
            } else {
                return $this->error('发布失败！');
            }
        } else {
            $data['id'] = $id;
            if (M('affiliate_banner')->save($data)) {
                // 禁用所有其他素材
                if ($data['is_enable'] == 1) {
                    $this->disableAll($data['id']);
                }
                return $this->success('恭喜，编辑海报成功！', U(MODULE_NAME . '/index'));
            } else {
                return $this->error('您未修改海报或发布失败！');
            }
        }
    }

    /**
     * 禁用所有海报，通过传入 $exclude 可以用于开启某一个海报
     * @param integer $exclude 排除的ID
     * @return boolean
     */
    private function disableAll($exclude = 0)
    {
        $sql = 'update `vifnn_affiliate_banner` set `is_enable` = 0 where `wxuser_id` = ' . $this->wxuser['id'];
        if (!empty($exclude)) {
            $sql .= ' and `id` != ' . $exclude;
        }
        return M('affiliate_banner')->query($sql);
    }

    /**
     * ACTION 删除推广素材
     */
    public function delete()
    {
        $where = array();
        $where['id'] = $this->_get('id', 'intval');
        $where['wxuser_id'] = $this->wxuser['id'];

        if(M('affiliate_banner')->where($where)->delete()) {
            $this->success('删除成功', U(MODULE_NAME . '/index', array('_rand' => time())));
        }else{
            $this->error('删除失败', U(MODULE_NAME . '/index'), array('_rand' => time()));
        }
    }

}
