<?php
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('localhost', 'root', 'root', 'ultrax');

// 用户表 pre_ucenter_members  pre_common_member pre_common_member_status

//1年没登陆用户全部删除。
//半年以上的回帖全部删除
//主题半年前，并且回复字数小于八个

$db->query("pre_ucenter_members");

$userId = '';

$db->query("DELETE FROM pre_forum_thread WHERE uid=''");