<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'pianyue.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
require_once './config/config_ucenter.php';

if($_G['adminid'] != 1) {
	showmessage('您没有权限访问该地址', NULL, array(), array('login' => 1));
}

$page = max(1, $_G['page']);
$num = 50;
$start = ($page - 1) * $num;
$cururl = 'pianyue.php';

$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('stonephp_pianyue'));
$query = DB::query("SELECT p.*, m.username FROM ".DB::table('stonephp_pianyue')." p
					LEFT JOIN ".DB::table('common_member')." m ON p.uid=m.uid
					ORDER BY p.id DESC LIMIT $start, $num");
$pianyue = array();
while($row = DB::fetch($query)) {
	$row['dateline'] = date('Y-m-d H:i:s', $row['dateline']);
	$pianyue[] = $row;
}
$multi = multi($count, $num, $page, $cururl);

include template('pianyue/index');


?>