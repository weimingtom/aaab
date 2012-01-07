#!/usr/local/php/bin/php
<?php
//set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('61.55.142.29', 'btbbt', 'btbbt', 'btbbt', 'gbk');

// 用户表 pre_ucenter_members  pre_common_member pre_common_member_status

// 循环次数
$maxNum = 2000000;

//=======================================================
/* 半年以上的回帖全部删除，只删除回帖
$page = 1;
$threadLog = ROOT_PATH.'/thread.log';
if (file_exists($threadLog)) {
 	$page = file_get_contents($threadLog);
}
$pagesize = 50;
for ($i=$page; $i<$maxNum; $i++)
{
	$startNum = ($i-1) * $pagesize;
	$time = strtotime('-6 month'); // 半年前的时间戳

	$rows = $db->fetch_all("SELECT tid FROM pre_forum_thread WHERE lastpost<'$time' LIMIT $startNum, $pagesize");
	if ($rows) {
		foreach ($rows as $row)
		{
			$tid = $row['tid'];
			
			echo 'tid-'.$tid."\r\n";

			$tables = array(
				'pre_forum_post'
			);

			foreach ($tables as $table) {
				$db->query("DELETE FROM $table WHERE tid='{$tid}'");
			}
		}
	} else {
		break;
	}
	file_put_contents($threadLog, $i);
}
*/

//主题半年前，并且回复字数小于八个，删除帖子与回帖
$page = 1;
$thread2Log = ROOT_PATH.'/thread2.log';
if (file_exists($thread2Log)) {
 	$page = file_get_contents($thread2Log);
}

$pagesize = 50;

while (TRUE)
{
	$startNum = ($page-1) * $pagesize;
	$time = strtotime('-6 month'); // 半年前的时间戳

	$rows = $db->fetch_all("SELECT tid FROM pre_forum_thread WHERE dateline<'$time' AND replies<'5' LIMIT $startNum, $pagesize");
	if ($rows) {
		foreach ($rows as $row)
		{
			$tid = $row['tid'];

			$tables = array(
				'pre_forum_post', 'pre_forum_thread'
			);

			foreach ($tables as $table) {
				
				echo 'tid2-'.$tid."\r\n";
				
				$db->query("DELETE FROM $table WHERE tid='{$tid}'");
			}
		}
	} else {
		break;
	}
	file_put_contents($thread2Log, $page);
	$page++;
}
exit("run done.\r\n");

//$db->query("pre_ucenter_members");
//
//$userId = '';
//
//$db->query("DELETE FROM pre_forum_thread WHERE uid=''");

// 会员数 4509762
// 帖子数 850635
// 回复数 17386694