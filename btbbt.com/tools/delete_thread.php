<?php
set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('localhost', 'root', 'root', 'ultrax');

// �û��� pre_ucenter_members  pre_common_member pre_common_member_status

//1��û��½�û�ȫ��ɾ����
$page = 1;
$pagesize = 50;
for ($i=1; $i<100; $i++) 
{
	$startNum = ($page-1) * $pagesize;
	$yearTime = strtotime('-1 year'); // һ��ǰ��ʱ���
	
	$row = $db->fetch_all("SELECT uid FROM pre_common_member_status WHERE lastactivity<'$yearTime' LIMIT $startNum, $pagesize");
	$uid = $row['uid'];
	
	
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
		'pre_home_appcreditlog',
		'pre_home_blog',
		'pre_home_blogfield',
		'pre_home_class',
	);
	
	foreach ($userTables as $table) {
		$db->query("DELETE FROM $table WHERE uid='$uid'");
	}
	
	// �������
	$bbsTables = array(
		'pre_forum_post',
		'pre_forum_thread',
	);
	foreach ($userTables as $table) {
		$db->query("DELETE FROM $table WHERE authorid='$uid'");
	}
}


//=======================================================
//�������ϵĻ���ȫ��ɾ��
//�������ǰ�����һظ�����С�ڰ˸�

//$db->query("pre_ucenter_members");
//
//$userId = '';
//
//$db->query("DELETE FROM pre_forum_thread WHERE uid=''");