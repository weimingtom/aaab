<?php
error_reporting(E_ALL);
/**
 * xiuno论坛和本系统同步登录接口
 */
$email = isset($_GET['email']) ? $_GET['email'] : '';
$uname = isset($_GET['uname']) ? $_GET['uname'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

if ($email && $uname) {
	$file = 'conf.php';
	include_once 'class.db.php';
	$pconf = include $file;
	
	// 权限验证
	$key = isset($_GET['key']) ? $_GET['key'] : '';
	$localkey = md5($email.$pconf['api_key']);
	if ($localkey != $key) {
		exit('//401');
	}
	
	// 数据处理
	$huabandb = new huabandb();
	$huabandb->connect($pconf['db_host'], $pconf['db_user'], $pconf['db_password'], $pconf['db_name'], $pconf['db_charset']);
	$tablepre = $pconf['db_tablepre'];
	
	$sqladd = "uname='$uname' AND email='$email'";
		
	$huabanuid = 0;
	$huaban_user = $huabandb->fetch_first("SELECT * FROM {$tablepre}user WHERE $sqladd");
	if(empty($huaban_user)){
		$huabanuid = $huabandb->insert("{$tablepre}user", array(
			'email'=>$email,
			'uname'=>$uname,
			'password'=>md5($password),
		));
	} else {
		$huabanuid = $huaban_user['uid'];
		$uname = $huaban_user['uname'];
	}
	
	setcookie('mid', $huabanuid, time()+86400*365, '/');
	setcookie('uname', $uname, time()+86400*365, '/');
	
	$isto = isset($_GET['isto']) ? $_GET['isto'] : '';
	if ($isto) {
		header("Location:/index.php");
	} else {
		echo '//200';
	}
} else {
	echo '//400';
}
?>