<?php
header("Content-type: text/html; charset=utf-8");
if (get_magic_quotes_gpc()) {
 function stripslashes_deep($value){
  $value = is_array($value) ?
  array_map('stripslashes_deep', $value) :
  stripslashes($value);
  return $value;
 }

 $_POST = array_map('stripslashes_deep', $_POST);
 $_GET = array_map('stripslashes_deep', $_GET);
 $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
define('APP_DEBUG',1);
define('APP_NAME', 'cms');
define('CONF_PATH','./../Datavifnn/conf/');
define('RUNTIME_PATH','./../Datavifnn/logs/');
define('TMPL_PATH','./../tpl/');
define('HTML_PATH','./../Datavifnn/html/');
define('APP_PATH','./../vifnn/');
define('CORE','./../vifnn/_Core');
require(CORE.'/vifnn.php');
?>