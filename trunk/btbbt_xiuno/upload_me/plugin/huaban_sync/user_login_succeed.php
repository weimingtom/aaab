//<?php
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
include_once BBS_PATH.'plugin/huaban_sync/class.db.php';
$pconf = include $file;

$huabandb = new huabandb();
$huabandb->connect($pconf['db_host'], $pconf['db_user'], $pconf['db_password'], $pconf['db_name'], $pconf['db_charset']);
$tablepre = $pconf['db_tablepre'];

$sqladd = '';
if(strpos($email, '@') !== FALSE) {
    $sqladd = "email='$email'";
} else {
    $sqladd = "uname='$email'";
}

$huabanuid = 0;
$huaban_user = $huabandb->fetch_first("SELECT * FROM {$tablepre}user WHERE $sqladd");
if(empty($huaban_user)){
    $huabanuid = $huabandb->insert("{$tablepre}user", array(
        'email'=>$email,
        'uname'=>$email,
        'password'=>md5($password),
    ));
} else {
	$huabanuid = $huaban_user['uid'];
}

// session 赋值
file_get_contents('http://v.9dalu.com/api/9dalu.php?mid='.$huabanuid.'&uname='.$email);