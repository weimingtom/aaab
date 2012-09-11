<?php
/**
 * xiuno论坛和本系统同步登录接口
 */
$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
$uname = isset($_GET['uname']) ? $_GET['uname'] : '';

if ($uid && $uname) {
	session_start();
	$_SESSION['mid'] = $uid;
	$_SESSION['uname'] = $uname;
}