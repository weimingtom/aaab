<?php
$file = BBS_PATH.'plugin/huaban_sync/conf.php';
include BBS_PATH.'plugin/huaban_sync/class.db.php';
$pconf = include $file;
$huabandb = new db();
$huabandb->connect($pconf['db_host'], $pconf['db_user'], $pconf['db_password'], $pconf['db_name']);
$tablepre = $pconf['db_host'];

$huaban_user = $db->fetch_first("SELECT * FROM {$tablepre}user WHERE uname=''");
if(empty($huaban_user)){
    $huabandb->insert("{$tablepre}user", array(
        'email'=>$email,
        'uname'=>$email,
        'password'=>$password,
    ));
}