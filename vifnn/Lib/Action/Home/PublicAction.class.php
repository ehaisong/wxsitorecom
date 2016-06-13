<?php
class PublicAction extends BaseAction{
	public function footer(){
		$where['status']=1;
		$links=D('Links')->where($where)->select();
		$this->assign('links',$links);
	}
    public function qrcode($content = ''){
        $content = base64_decode($_GET['content']);
        //引入核心库文件
        include './vifnn/Lib/ORG/phpqrcode.php';
        //定义纠错级别
        $errorLevel = "L";
        //定义生成图片宽度和高度;默认为3
        $size = "4";
        QRcode::png($content, false, $errorLevel, $size);
    }
}