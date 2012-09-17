<?php
/**
 * xiuno论坛和本系统同步登录接口
 */
session_start();
$_SESSION['mid'] = '';
$_SESSION['uname'] = '';
$_SESSION['userInfo'] = '';

unset($_SESSION['userInfo']);
unset($_SESSION['mid']);
unset($_SESSION['uname']);

setcookie('dalu_mid', '', time()-1, '/');
setcookie('dalu_uname', '', time()-1, '/');

unset($_COOKIE['dalu_mid']);
unset($_COOKIE['dalu_uname']);