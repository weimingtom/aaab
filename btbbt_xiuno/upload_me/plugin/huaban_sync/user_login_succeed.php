//<?php
session_start();
if (isset($_SESSION['is_huaban_sync_login']) && $_SESSION['is_huaban_sync_login'] == 1) {
	$file = BBS_PATH.'plugin/huaban_sync/conf.php';
	$pconf = include $file;
	$api_url = $pconf['api_url'].'/userapi/login.php?email='.$userdb['email'].'&uname='.$userdb['username'].'&password='.$password.'&key='.md5($userdb['email'].$pconf['api_key']);
	$error['user']['api_url'] = $api_url;
	
	unset($_SESSION['is_huaban_sync_login']);
}