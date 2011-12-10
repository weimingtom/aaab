<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'star.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
require_once './config/config_ucenter.php';

$type = in_array($_G['gp_type'], array('lady', 'man', 'actor', 'model', 'singer', 'industry', 'newvip')) ? $_G['gp_type'] : 'lady';

$page = max(1, $_G['page']);
$num = 50;
$start = ($page - 1) * $num;
$cache_key = 'star_'.$type.$page;
$cururl = 'star.php';
if(!$data = memory('get', $cache_key)) {
	$data = array();
	if($type == 'lady') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.gender=2 ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.gender=2';
		$cururl = 'star.php';
		$data['navtitle'] = '��ţ��Ů';
	} elseif($type == 'man') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.gender=1 ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.gender=1';
		$cururl = 'star.php?type=man';
		$data['navtitle'] = '��ţ����';
	} elseif($type == 'actor') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation='��Ա' ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation='��Ա'";
		$cururl = 'star.php?type=actor';
		$data['navtitle'] = '��Ա';
	} elseif($type == 'model') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation='ģ��' ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation='ģ��'";
		$cururl = 'star.php?type=model';
		$data['navtitle'] = 'ģ��';
	} elseif($type == 'singer') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation='����' ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation='����'";
		$cururl = 'star.php?type=singer';
		$data['navtitle'] = '����';
	} elseif($type == 'industry') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('��Ա', 'ģ��', '����') ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('��Ա', 'ģ��', '����')";
		$cururl = 'star.php?type=industry';
		$data['navtitle'] = 'ҵ��';
	} elseif($type == 'newvip') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				WHERE m.groupid=21 ORDER BY m.regdate DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				WHERE m.groupid=21';
		$cururl = 'star.php?type=newvip';
		$data['navtitle'] = '����VIP';
	} else {
		showmessage('δ�������');
	}

	$data['data'] = array();
	$query = DB::query($sql);
	while($row = DB::fetch($query)) {
		$data['data'][$row['uid']] = $row;
	}
	$count = DB::result_first($count_sql);
	$data['multi'] = multi($count, $num, $page, $cururl);
	memory('set', $cache_key, $data, 3600);
}
//error_reporting(E_ALL);
extract($data);
$tips = '��������ʽΪ�������¶�����VIP������ʾ�����磺��΢������־����Ƭ��';
include template('star/index');


?>