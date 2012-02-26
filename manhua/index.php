<?php
error_reporting(E_ALL);
//setlocale(LC_ALL, 'zh_CN.UTF-8');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

define('IN_ROOT', true);
define('VIEWSPATH', '');
define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/' );

require ROOT_PATH.'library/config.php';
require ROOT_PATH.'library/function.php';

// 清除变量
unset($_GET, $GLOBALS, $_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);

// 初始化 $_GET
$_GET = explode('-', substr($_SERVER['REQUEST_URI'], 1, strpos($_SERVER['REQUEST_URI'], '.htm')-1));
if(strpos($_SERVER['REQUEST_URI'], '?') !== FALSE) {
	$arr = (array)parse_url($_SERVER['REQUEST_URI']);
	isset($arr['query']) && parse_str($arr['query'], $arr);
	$_GET += $arr;
	$_REQUEST += $arr;
	unset($arr);
}
$_GET['m'] = isset($_GET[0]) && !is_numeric($_GET[0]) && preg_match("/^[a-z\d]{2,22}$/", $_GET[0]) ? $_GET[0] : 'index';
$_GET['a'] = isset($_GET[1]) && preg_match("/^[a-z_\d]{2,22}$/", $_GET[1]) ? $_GET[1] : 'index';

require ROOT_PATH . 'library/base.php';

$fileControl = ROOT_PATH.'application/controllers/'.$_GET['m'].'Controller.php';
if (file_exists($fileControl)) {
	include $fileControl;
	$ClassName = $_GET['m'].'Controller';
	$control = new $ClassName();
	$method = 'action'.$_GET['a'];

	if(method_exists($control, $method)) {
		$control->$method();
	} else {
		exit('Action not found!');
	}
} else {
	exit('Controller not found!');
}
?>