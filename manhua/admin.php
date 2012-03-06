<?php
error_reporting(E_ALL);
header("Content-Type:text/html; charset=utf-8");

date_default_timezone_set ( 'Asia/Shanghai' );

define ('IN_ROOT', true);
define ('VIEWSPATH', '/admin');
define ('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));

require ROOT_PATH.'/library/config.php';
require ROOT_PATH.'/library/function.php';

$_GET = daddslashes($_GET, 1, TRUE);
$_POST = daddslashes($_POST, 1, TRUE);
$_COOKIE = daddslashes($_COOKIE, 1, TRUE);
$_SERVER = daddslashes($_SERVER);
$_FILES = daddslashes($_FILES);
$_REQUEST = daddslashes($_REQUEST, 1, TRUE);

$modules = array('admin'=>1);

$m = $c = $a = '';

$rule = isset($_GET['r']) ? $_GET['r'] : '';
if ($rule) {
	if($rule{0} == '/') {
		$rule = substr($rule, 1);
	}
	
	$rs = explode('/', $rule);
	if (count($rs)>2) {
		$m = $rs[0];
		$c = $rs[1];
		$a = $rs[2];
	} else {
		if(isset($modules[$rs[0]])) {
			$m = $rs[0];
			$c = $rs[1];
			$a = 'index';
		} else {
			$c = $rs[0];
			$a = $rs[1];
		}
	}
	
	$_GET['m'] = $m;
	$_GET['c'] = $c;
	$_GET['a'] = $a;
}

require ROOT_PATH.'/library/base.php';

$controller = '';
if ($m) {
	require ROOT_PATH.'/application/components/'.ucfirst($m).'Controller.php';
	$controller = 'application/controllers/'.$m.'/'.ucfirst($c).'Controller.php';
} else {
	$controller = 'application/controllers/'.ucfirst($c).'Controller.php';
}

include(ROOT_PATH.'/'.$controller);
$ClassName = $c . 'Controller';
$control = new $ClassName();
$method = 'action'.ucfirst($a);
if (method_exists( $control, $method))
{
	$control->$method();
} else {
	exit('Action not found!' );
}
?>