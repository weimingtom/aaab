//<?php
session_start();
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$api_url = $pconf['api_url'].'/userapi/login.php?email='.$userdb['email'].'&uname='.$userdb['username'].'&password='.$password.'&key='.md5($userdb['email'].$pconf['api_key']);

if (isset($_SESSION['is_huaban_sync_login']) && $_SESSION['is_huaban_sync_login'] == 1) {
	$error['user']['api_url'] = $api_url;
	unset($_SESSION['is_huaban_sync_login']);
} else {
//	file_get_contents($api_url);
//	//exit($api_url);
//	exit($api_url);
	$ch = curl_init(); 
	//curl_setopt($ch,CURLOPT_ENCODING ,'gb2312'); 
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_PORT , 80);
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, false) ; // 获取数据返回 
	$s = curl_exec($ch); 
	curl_close($ch);
//	print_r($s);exit;
}
