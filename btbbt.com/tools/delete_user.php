#!/usr/local/php/bin/php
<?php
//set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('61.55.142.29', 'btbbt', 'btbbt', 'btbbt', 'gbk');

// �û��� pre_ucenter_members  pre_common_member pre_common_member_status

//1��û��½�û�ȫ��ɾ����
$page = 1;
$usersLog = ROOT_PATH.'/users.log';
if (file_exists($usersLog)) {
 	$page = file_get_contents($usersLog);
 } 
 
 $userTables = array(
	// �û����
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
	
	// ��԰���
	'pre_home_album',
	'pre_home_favorite',	// �ղ�
	'pre_home_blog',	// ��־
	'pre_home_blogfield',	
	'pre_home_class',	// ����
	'pre_home_friend', 	// �û�����
	'pre_home_share',	// ����
);

// �������
$bbsTables = array(
	'pre_forum_post',
	'pre_forum_thread',
);

$pagesize = 50;
while (TRUE) 
{
	$startNum = ($page-1) * $pagesize;
	$time = strtotime('-1 year'); // һ��ǰ��ʱ���
	
	$rows = $db->fetch_all("SELECT uid FROM pre_common_member_status WHERE lastactivity<'$time' LIMIT $startNum, $pagesize");
	if ($rows) {
		foreach ($rows as $row)
		{
			$uid = $row['uid'];
			echo 'uid-'.$uid."\r\n";
			
			// ɾ�����ͼƬ
			$pics = $db->fetch_all("SELECT * FROM pre_home_pic WHERE uid='$uid'");
			foreach ($pics as $pic) {
				$filepath = $pic['filepath'];
				echo $filepath;
				exit;
			}
			exit;
			
			foreach ($userTables as $table) {
				$db->query("DELETE FROM $table WHERE uid='$uid'");
			}

			$threads = $db->fetch_all("SELECT tid FROM pre_forum_thread WHERE authorid='$uid' AND dateline<'$time' AND replies<'5'");
			foreach ($threads as $thread) {
				$db->query("DELETE FROM pre_forum_thread WHERE tid='{$thread['tid']}'");
				$db->query("DELETE FROM pre_forum_post WHERE tid='{$thread['tid']}'");
			}
		}
	} else {
		break;
	}
	
	file_put_contents($usersLog, $page);
	$page++;
}


//=======================================================
/* �������ϵĻ���ȫ��ɾ����ֻɾ������
$page = 1;
$threadLog = ROOT_PATH.'/thread.log';
if (file_exists($threadLog)) {
 	$page = file_get_contents($threadLog);
}
$pagesize = 50;
for ($i=$page; $i<$maxNum; $i++)
{
	$startNum = ($i-1) * $pagesize;
	$time = strtotime('-6 month'); // ����ǰ��ʱ���

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

//�������ǰ�����һظ�����С�ڰ˸���ɾ�����������
$page = 1;
$thread2Log = ROOT_PATH.'/thread2.log';
if (file_exists($thread2Log)) {
 	$page = file_get_contents($thread2Log);
}
$pagesize = 50;
for ($i=$page; $i<$maxNum; $i++)
{
	$startNum = ($i-1) * $pagesize;
	$time = strtotime('-6 month'); // ����ǰ��ʱ���

	$rows = $db->fetch_all("SELECT tid FROM pre_forum_thread WHERE dateline<'$time' AND replies<'8' LIMIT $startNum, $pagesize");
	if ($rows) {
		foreach ($rows as $row)
		{
			$tid = $row['tid'];

			echo 'tid2-'.$tid."\r\n";	
			
			$tables = array(
				'pre_forum_post', 'pre_forum_thread'
			);

			foreach ($tables as $table) {
				$db->query("DELETE FROM $table WHERE tid='{$tid}'");
			}
		}
	} else {
		break;
	}
	file_put_contents($thread2Log, $i);
}*/

//$db->query("pre_ucenter_members");
//
//$userId = '';
//
//$db->query("DELETE FROM pre_forum_thread WHERE uid=''");

// ��Ա�� 4509762
// ������ 850635
// �ظ��� 17386694