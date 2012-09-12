//<?php
session_start();
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$api_url = $pconf['api_url'].'/userapi/login.php?email='.$userdb['email'].'&uname='.$userdb['username'].'&password='.$password.'&key='.md5($userdb['email'].$pconf['api_key']);

if (isset($_SESSION['is_huaban_sync_login']) && $_SESSION['is_huaban_sync_login'] == 1) {
	$error['user']['syncloginurl'] = $api_url.'&isto=1';
	unset($_SESSION['is_huaban_sync_login']);
} else {
	$error['user']['syncloginscript'] = $api_url;
}
