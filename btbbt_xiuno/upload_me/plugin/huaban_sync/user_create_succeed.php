//<?php
session_start();
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$api_url = $pconf['api_url'].'/userapi/login.php?email='.$email.'&uname='.$username.'&key='.md5($email.$pconf['api_key']);

if (isset($_SESSION['is_huaban_sync_register']) && $_SESSION['is_huaban_sync_register'] == 1) {
	$error['user']['syncregisterurl'] = $api_url.'&isto=1';;
	unset($_SESSION['is_huaban_sync_register']);
} else {
	$error['user']['syncregisterscript'] = $api_url;
}