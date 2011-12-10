<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 16388 2010-09-06 04:08:21Z liulanbo $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'artist.php');

require './source/class/class_core.php';

$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init();
require_once './config/config_ucenter.php';

$uid = max(0, intval($_G['gp_uid']));

$user = DB::fetch_first("SELECT * FROM ".DB::table('common_member')." WHERE uid='$uid'");
if($user['groupid'] != 21) {
	showmessage('页面不存在，或者禁止访问');
}

$do = in_array($_G['gp_do'], array('profile', 'album', 'pic', 'friend')) ? $_G['gp_do'] : 'profile';
$user = DB::fetch_first('SELECT m.*, mp.*, mc.* FROM '.DB::table('common_member').' m
			LEFT JOIN '.DB::table('common_member_profile').' mp ON m.uid=mp.uid
			LEFT JOIN '.DB::table('common_member_count')." mc ON mc.uid=m.uid
			LEFT JOIN ".DB::table('common_member_status')." ms ON m.uid=ms.uid
			WHERE m.uid='$uid'");

if($do === 'profile') {
	include template('artist/profile');
} elseif($do === 'album') {
	$albums = array();
	$query = DB::query("SELECT a.*, p.filepath, p.thumb, count(p.click4) AS lights FROM ".DB::table('home_album')." a
				LEFT JOIN ".DB::table('home_pic')." p ON a.albumid=p.albumid 
				WHERE a.uid='$uid' GROUP BY a.`albumid` ORDER BY a.`albumid` DESC");
	while($row = DB::fetch($query)) {
		$row['image'] = $_G['setting']['attachurl'].'album/'.$row['filepath'];
		$row['thumb'] && $row['image'] = $row['image'].'.thumb.jpg';
		$albums[] = $row;
	}
	$newPics = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_pic')." WHERE uid='$uid' ORDER BY picid DESC LIMIT 10");
	while($row = DB::fetch($query)) {
		$row['image'] = $_G['setting']['attachurl'].'album/'.$row['filepath'];
		$row['thumb'] && $row['image'] = $row['image'].'.thumb.jpg';
		$newPics[] = $row;	
	}
	include template('artist/album');
} elseif($do === 'friend') {
	
	include template('artist/friend');
} elseif($do === 'pic') {
	$albumid = $_G['gp_albumid'];
	$album = DB::fetch_first('SELECT * FROM '.DB::table('home_album')." WHERE `albumid`='$albumid'");
	if(!$album) {
		showmessage('相册不存在');
	}
	$pics = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_pic')."	WHERE albumid='$albumid' ORDER BY dateline DESC");
	while($row = DB::fetch($query)) {
		$row['image'] = $_G['setting']['attachurl'].'album/'.$row['filepath'];
		$pics[$row['picid']] = $row;
	}

	$newAlbums = array();
	$query = DB::query("SELECT a.*, p.filepath, p.thumb, count(p.click4) AS lights FROM ".DB::table('home_album')." a
				LEFT JOIN ".DB::table('home_pic')." p ON a.albumid=p.albumid 
				WHERE a.uid='$uid' GROUP BY a.`albumid` ORDER BY a.`albumid` DESC");
	while($row = DB::fetch($query)) {
		$row['image'] = $_G['setting']['attachurl'].'album/'.$row['filepath'];
		$row['thumb'] && $row['image'] = $row['image'].'.thumb.jpg';
		$newAlbums[] = $row;
	}

	$commentId = 0;
	if($pics && $ids = array_keys($pics)) {
		$commentId = $ids[0];
		$ids = implode(',', $ids);
		$coments = array();
		$query = DB::query("SELECT * FROM ".DB::table('home_comment')." WHERE `id` IN ($ids) AND `idtype`='picid' ORDER BY dateline DESC");
	}

	include template('artist/pic');
	
}

?>