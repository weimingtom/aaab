<?php
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('localhost', 'root', 'root', 'ultrax');

// ÓÃ»§±í pre_ucenter_members  pre_common_member pre_common_member_status

$db->query("pre_ucenter_members");

$userId = '';

$db->query("DELETE FROM pre_forum_thread WHERE uid=''");