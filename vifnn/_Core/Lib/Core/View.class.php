<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * ThinkPHP 视图类
 * @category   Think
 * @package  Think
 * @subpackage  Core
 * @author liu21st <liu21st@gmail.com>
 */
class View {
    /**
     * 模板输出变量
     * @var tVar
     * @access protected
     */       
    protected $tVar        =  array();

    /**
     * 模板变量赋值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function assign($name,$value=''){
        if(is_array($name)) {
            $this->tVar   =  array_merge($this->tVar,$name);
        }else {
            $this->tVar[$name] = $value;
        }
    }

    /**
     * 取得模板变量的值
     * @access public
     * @param string $name
     * @return mixed
     */
    public function get($name=''){
        if('' === $name) {
            return $this->tVar;
        }
        return isset($this->tVar[$name])?$this->tVar[$name]:false;
    }

    /**
     * 加载模板和页面输出 可以返回输出内容
     * @access public
     * @param string $templateFile 模板文件名
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀
     * @return mixed
     */
    public function display($templateFile='',$charset='',$contentType='',$content='',$prefix=''){
        G('viewStartTime');
        // 视图开始标签
        tag('view_begin',$templateFile);
        // 解析并获取模板内容
        $content = $this->fetch($templateFile,$content,$prefix);
		//敏感词过滤
		$is_class = class_exists('Susceptible');
		if($is_class){
			$Susceptible = new Susceptible();
			$content = $Susceptible->index($content);
		}
        // 输出模板内容
        $this->render($content,$charset,$contentType);
        // 视图结束标签
        tag('view_end');
    }
	
	public function showTpl($action){
        G('viewStartTime');
		$templateFile = '';
        // 视图开始标签
        tag('view_begin',$templateFile);
        // 解析并获取模板内容
		if($action == 'index' && strlen(tpl_path)==32){
			$tpl_arr = S('web_index_html_'.token);
			if(empty($tpl_arr)){
				$tpl_arr = $this->web_get_tpl($action);
				S('web_index_html_'.token,$tpl_arr,0);
			}
			$tpl_con = $tpl_arr['html'];
		}else{
			$tpl_arr = $this->web_get_tpl($action);
			$tpl_con = $tpl_arr['html'];
		}
        $content = $this->fetch('',$tpl_con);
		$content = str_replace('{vifnn:WEB_VISIT_URL}','http://'.now_host,$content);
		$content = str_replace('{vifnn:WEB_STATIC_URL}',$tpl_arr['static'],$content);
        // 输出模板内容
        $this->render($content);
        // 视图结束标签
        tag('view_end');
    }
	protected function web_get_tpl($action){
		$tpl_arr = json_decode(urlgettpl($action),true);
		if(is_array($tpl_arr)){
			if(!empty($tpl_arr['error_code'])){
				exit('错误原因：'.$tpl_arr['error_msg'].'<br/>错误码：'.$tpl_arr['error_code']);
			}
		}else{
			exit('模板未获取成功！请重试。');
		}
		return $tpl_arr;
	}

    /**
     * 输出内容文本可以包括Html
     * @access private
     * @param string $content 输出内容
     * @param string $charset 模板输出字符集
     * @param string $contentType 输出类型
     * @return mixed
     */
    private function render($content,$charset='',$contentType=''){
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        header('Content-Type:'.$contentType.'; charset='.$charset);
        header('Cache-control: '.C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
		$str2='LmNvbQ==';
		$str=base64_decode('UGlnQ21z'.$str2);
        header('X-Powered-By:'.$str);
        // 输出模板文件
        echo $content;
    }

    /**
     * 解析和获取模板内容 用于输出
     * @access public
     * @param string $templateFile 模板文件名
     * @param string $content 模板输出内容
     * @param string $prefix 模板缓存前缀
     * @return string
     */
    public function fetch($templateFile='',$content='',$prefix='') {
        if(empty($content)) {
            // 模板文件解析标签
            tag('view_template',$templateFile);
            // 模板文件不存在直接返回
            if(!is_file($templateFile)) return NULL;
        }
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
        if('php' == strtolower(C('TMPL_ENGINE_TYPE'))) { // 使用PHP原生模板
            // 模板阵列变量分解成为独立变量
            extract($this->tVar, EXTR_OVERWRITE);
            // 直接载入PHP模板
            empty($content)?include $templateFile:eval('?>'.$content);
        }else{
            // 视图解析标签
            $params = array('var'=>$this->tVar,'file'=>$templateFile,'content'=>$content,'prefix'=>$prefix);
            tag('view_parse',$params);
        }
        // 获取并清空缓存
        $content = ob_get_clean();
        // 内容过滤标签
        tag('view_filter',$content);
        // 输出模板文件
        return $content;
    }
}