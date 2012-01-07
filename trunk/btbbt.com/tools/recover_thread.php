#!/usr/local/php/bin/php
<?php
/* 恢复2012.01.05之前的主题帖子 */
//set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH.'/db.php');

$db = new db();
$db->connect('61.55.142.29', 'btbbt', 'btbbt', 'btbbt', 'gbk', 1);

$db2 = new db();
$db2->connect('61.55.142.29', 'btbbt20120101', 'btbbt20120101', 'btbbt20120101', 'gbk', 1);

// 循环次数
$maxNum = 2000000;

//半年以上的回帖全部删除，全部恢复
$page = 1;
$threadLog = ROOT_PATH.'/recover_thread.log';
if (file_exists($threadLog)) {
 	$page = file_get_contents($threadLog);
}
$pagesize = 50;
for ($i=$page; $i<$maxNum; $i++)
{
	$startNum = ($i-1) * $pagesize;
	$time = strtotime('-6 month'); // 半年前的时间戳

	$rows = $db->fetch_all("SELECT tid FROM pre_forum_thread WHERE lastpost<'$time' LIMIT $startNum, $pagesize");
	//print_r($rows);exit;
	if ($rows) {
		foreach ($rows as $row)
		{
			//usleep(10000);
			$tid = $row['tid'];
			echo 'tid-'.$tid."\r\n";
			
			$post = $db->fetch_first("SELECT pid FROM pre_forum_post WHERE tid='$tid' AND first='1'");
			if(empty($post)) {
				$arr = $db2->fetch_first("SELECT * FROM pre_forum_post WHERE tid='$tid' AND first='1'");
				if ($arr) {
					echo 'pid----'.$arr['pid']."\r\n";
					$arr['author'] = addslashes($arr['author']);
					$arr['subject'] = addslashes($arr['subject']);
					$arr['message'] = addslashes($arr['message']);
					$db->insert('pre_forum_post', $arr);
				}
			}
		}
	} else {
		break;
	}
	file_put_contents($threadLog, $i);
	usleep(10000);
}
$db->close();
$db2->close();
exit('run done.');