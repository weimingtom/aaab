//<?php
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
$pconf = include $file;
$s = file_get_contents($pconf['api_url'].'/userapi/login.php?email='.$email.'&uname='.$username.'&key='.md5($email.$pconf['api_key']));
file_put_contents('2.txt', $s);