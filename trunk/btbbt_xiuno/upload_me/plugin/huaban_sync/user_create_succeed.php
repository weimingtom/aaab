//<?php
if (isset($_SESSION['is_huaban_sync_register']) && $_SESSION['is_huaban_sync_register'] == 1) {
	$file = BBS_PATH.'plugin/huaban_sync/conf.php';
	$pconf = include $file;
	$api_url = $pconf['api_url'].'/userapi/login.php?email='.$email.'&uname='.$username.'&key='.md5($email.$pconf['api_key']);
	$error['user']['api_url'] = $api_url;
}