//<?php
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$api_url = $pconf['api_url'].'/userapi/login.php?email='.$userdb['email'].'&uname='.$userdb['username'].'&password='.$password.'&key='.md5($userdb['email'].$pconf['api_key']);
$error['user']['api_url'] = $api_url;
//header("location:$apiurl");
file_put_contents('1.txt', $apiurl);
//$s = file_get_contents(
//header("location:".$pconf['api_url']);