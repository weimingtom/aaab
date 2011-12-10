<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'rank.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
require_once './config/config_ucenter.php';

$type = in_array($_G['gp_type'], array('general', 'light', 'friends', 'views')) ? $_G['gp_type'] : 'general';

$page = max(1, $_G['page']);
$num = 40;
$start = ($page - 1) * $num;
$cache_key = 'ranks_'.$type.$page;
$cururl = 'rank.php';
if(!$data = memory('get', $cache_key)) {
	$data = array();
	if($type == 'general') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 ORDER BY m.credits DESC LIMIT 50";
	} elseif($type == 'light') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 ORDER BY mc.albums DESC LIMIT 50";
	} elseif($type == 'friends') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 ORDER BY mc.friends DESC LIMIT 50";
	} elseif($type == 'views') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				WHERE m.groupid=21 ORDER BY mc.views DESC LIMIT 50";
	} else {
		showmessage('未定义操作');
	}

	$data['data'] = array();
	$query = DB::query($sql);
	while($row = DB::fetch($query)) {
		$data['data'][] = $row;
	}

	memory('set', $cache_key, $data, 3600);
}
//error_reporting(E_ALL);
extract($data);
$tips = '以下排序方式为“有最新动作的VIP优先显示”比如：发微博，日志，照片等';
include template('rank/index');


?>