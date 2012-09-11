//<?php
session_start();
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$api_url = $pconf['api_url'].'/userapi/login.php?email='.$userdb['email'].'&uname='.$userdb['username'].'&password='.$password.'&key='.md5($userdb['email'].$pconf['api_key']);

if (isset($_SESSION['is_huaban_sync_login']) && $_SESSION['is_huaban_sync_login'] == 1) {
	$error['user']['api_url'] = $api_url;
	unset($_SESSION['is_huaban_sync_login']);
} else {
	$curl = curl_init();

	$header[] = "Cache-Control: max-age=0";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: en-us,en;q=0.5";
	$header[] = "Pragma: "; // browsers keep this blank.

	curl_setopt($curl, CURLOPT_URL, $api_url);
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_REFERER, 'http://v.9dalu.com');
	//curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);

	$html = curl_exec($curl); 
	curl_close($curl); 
}
