<?php
error_reporting(E_ALL);
header("Content-Type:text/html; charset=utf-8");

date_default_timezone_set ( 'Asia/Shanghai' );

define ('IN_ROOT', true);
define ('VIEWSPATH', '/admin');
define ('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/' );

require ROOT_PATH . 'library/config.php';
require ROOT_PATH . 'library/function.php';

$_GET = daddslashes($_GET, 1, TRUE);
$_POST = daddslashes($_POST, 1, TRUE);
$_COOKIE = daddslashes($_COOKIE, 1, TRUE);
$_SERVER = daddslashes($_SERVER);
$_FILES = daddslashes($_FILES);
$_REQUEST = daddslashes($_REQUEST, 1, TRUE);

$c = isset($_REQUEST['c']) ? trim($_REQUEST['c']) : 'index';
$a = isset($_REQUEST['a']) ? trim($_REQUEST['a']) : 'index';

require ROOT_PATH . 'library/base.php';
require ROOT_PATH . 'application/models/admin/admin.php';

include ROOT_PATH . 'application/controllers/admin/'.ucfirst($c).'Controller.php';
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