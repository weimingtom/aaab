<?php
set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('localhost', 'root', 'root', 'ultrax');

// 用户表 pre_ucenter_members  pre_common_member pre_common_member_status

//1年没登陆用户全部删除。
$page = 1;
$pagesize = 50;
for ($i=1; $i<100; $i++) 
{
	$startNum = ($page-1) * $pagesize;
	$yearTime = strtotime('-1 year'); // 一年前的时间戳
	
	$row = $db->fetch_all("SELECT uid FROM pre_common_member_status WHERE lastactivity<'$yearTime' LIMIT $startNum, $pagesize");
	$uid = $row['uid'];
	
	
	$userTables = array(
		// 用户相关
		'pre_common_member',
		'pre_common_member_action_log',
		'pre_common_member_connect',
		'pre_common_member_count',
		'pre_common_member_field_forum',
		'pre_common_member_field_home',
		'pre_common_member_grouppm',
		'pre_common_member_log',
		'pre_common_member_magic',
		'pre_common_member_profile',
		'pre_common_member_status',
		'pre_common_member_validate',
		'pre_common_member_verify',
		'pre_common_member_verify_info',
		
		// 家园相关
		'pre_home_album',
		'pre_home_appcreditlog',
		'pre_home_blog',
		'pre_home_blogfield',
		'pre_home_class',
	);
	
	foreach ($userTables as $table) {
		$db->query("DELETE FROM $table WHERE uid='$uid'");
	}
	
	// 帖子相关
	$bbsTables = array(
		'pre_forum_post',
		'pre_forum_thread',
	);
	foreach ($userTables as $table) {
		$db->query("DELETE FROM $table WHERE authorid='$uid'");
	}
}


//=======================================================
//半年以上的回帖全部删除
//主题半年前，并且回复字数小于八个

//$db->query("pre_ucenter_members");
//
//$userId = '';
//
//$db->query("DELETE FROM pre_forum_thread WHERE uid=''");