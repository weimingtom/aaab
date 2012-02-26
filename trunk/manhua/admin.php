<?php
error_reporting(E_ALL);

header("Content-Type:text/html; charset=utf-8");
//setlocale(LC_ALL, 'zh_CN.UTF-8');

date_default_timezone_set ( 'Asia/Shanghai' );

define ('IN_ROOT', true);
define ('VIEWSPATH', '/admin');
define ('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/' );

require ROOT_PATH . 'library/config.php';
require ROOT_PATH . 'library/function.php';

$_GET = daddslashes ( $_GET, 1, TRUE );
$_POST = daddslashes ( $_POST, 1, TRUE );
$_COOKIE = daddslashes ( $_COOKIE, 1, TRUE );
$_SERVER = daddslashes ( $_SERVER );
$_FILES = daddslashes ( $_FILES );
$_REQUEST = daddslashes ( $_REQUEST, 1, TRUE );

$c = isset ( $_REQUEST ['c'] ) ? trim ( $_REQUEST ['c'] ) : 'index';
$a = isset ( $_REQUEST ['a'] ) ? trim ( $_REQUEST ['a'] ) : 'index';

require ROOT_PATH . 'library/base.php';
require ROOT_PATH . 'application/models/admin/admin.php';

$isarr = array ('index' => 1, 'login' => 1, 'user' => 1, 'cate' => 1, 'getdata' => 1, 'create' => 1, 'spider'=>1, 'novel'=>1, 'spiderCustom'=>1, 'data'=>1);

if(isset($isarr[$c]))
{
	include ROOT_PATH . 'application/controllers/admin/'.$c.'Controller.php';
	$ClassName = $c . 'Controller';
	$control = new $ClassName();
	$method = $a . 'Action';
	if (method_exists( $control, $method ))
	{
		$control->$method();
	}
	else
	{
		exit ( 'Action not found!' );
	}	
}
else
{
	exit ( 'Module not found!' );
}
?>