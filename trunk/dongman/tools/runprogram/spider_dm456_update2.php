#!/usr/local/php/bin/php
<?php
//header("Content-Type:text/html; charset=gb2312");
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL);

set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
define('LOCK_PATH', ROOT_PATH.'/syslog');
define('RUNPROGRAM_ID', 'dm456_update');

include ROOT_PATH.'/config.php';
include ROOT_PATH.'/../include/db.php';
include ROOT_PATH.'/../include/function.php';
include ROOT_PATH.'/../include/letter.php';

mkdirs(ROOT_PATH.'/syslog');

// 判断是否锁定
$lockFile =  ROOT_PATH.'/syslog/'.RUNPROGRAM_ID.'.file.lock';
if(file_exists($lockFile)) {
	exit('Already lock file exit program');
} else {
	touch($lockFile);
}

$db = new db();
$db->connect(DBHOST, DBUSER, DBPWD, DBNAME, DBCHARSET);

$domain = "http://www.dm456.com";
$tpl = array();
$s = getContentUrl($domain.'/donghua/update.html', $domain);
preg_match_all('/<strong>(\d+)<\/strong><a href="(.*)" class="video" i="(.*)">(.*)<\/a>/isU', $s, $tpl);
$updateUrls = $tpl[2];
//$updateTitles = $tpl[4];

foreach ($updateUrls as $updateUrl) 
{	
//	sleep(rand(0, 5));
	// 抓取动漫内容详细页的
	$spiderDetailUrl = $domain.$updateUrl;
	
	spiderVod($db, $spiderDetailUrl, $domain);
}

// 创建锁文件
@unlink($lockFile);

// 采集详细页内容
function spiderVod(&$db, $spiderDetailUrl, $domain) 
{
	$field = array();

	$field['vod_reurl'] = $spiderDetailUrl;

	$s = getContentUrl($spiderDetailUrl, $domain);
	//$s = iconv('gbk', 'utf-8', $s);
//	print_r($s);exit;

	// 分类
	$cates = array(
		'日本动画片'=>2,
		'国产动画片'=>1,
		'大陆动画片'=>1,
		'欧美动画片'=>3,
		'TV版'=>23,
		'OVA版'=>24,
		'剧场版'=>25,
	);
	$tpl = array();
	preg_match('/首页<\/a> > <a href="(.*)">(.*)<\/a> > <strong>/isU', $s, $tpl);
	$field['vod_cid'] = isset($tpl[2]) ? isset($cates[$tpl[2]]) ? $cates[$tpl[2]] : 29 : 29;

	// 标题
	$tpl = array();
	preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
	$field['vod_name'] = isset($tpl[1]) ? $tpl[1] : '';
	if (empty($field['vod_name'])) {
		continue;
	}

	$vod = $db->fetch_first("SELECT vod_id FROM pp_vod WHERE vod_name='{$field['vod_name']}'");
	if(empty($vod)) {
		// 图片
		$tpl = array();
		preg_match('/<img src="(.*)" class="pic" title/isU', $s, $tpl);
		$vod_pic = isset($tpl[1]) ? $tpl[1] : '';
		if ($vod_pic) {
			$filename = save_picture($domain.$vod_pic, dirname(dirname(PICTURE_SAVE_PATH)).'/Upload/'.date('Y-m'));
			$field['vod_pic'] = date('Y-m').'/'.$filename;
		} else {
			continue;
		}
	}

	// 是否完结
	$tpl = array();
	preg_match('/<span>&nbsp;更新至: <a href="(.*)" target="_blank" title="(.*)" class="red">(.*)<\/a>(.*)<\/span>/isU', $s, $tpl);
	$field['vod_continu'] = isset($tpl[3]) && isset($tpl[4]) ? $tpl[3].$tpl[4] : '';

	// 获取首字母
	$field['vod_letter'] = '';
	if ($field['vod_name']) {
		$py = new PYInitials();
		$letter = $py->getInitials($field['vod_name']);
		$field['vod_letter'] = substr($letter, 0, 1);
	}

	// 演员
	$tpl = array();
	preg_match('/<p><em>主　　角：<\/em>(.*)<\/p>/isU', $s, $tpl);
	$field['vod_actor'] = isset($tpl[1]) ? $tpl[1] : '';

	// 剧情类别
	$tpl = array();
	preg_match('/<p><em>剧情类别：<\/em>(.*)<\/p>/isU', $s, $tpl);
	$field['vod_keywords'] = isset($tpl[1]) ? strip_tags($tpl[1]) : '';

	// 年代
	$tpl = array();
	preg_match('/<p class="w260"><em>出品年份：<\/em>(\d{4})<\/p>/isU', $s, $tpl);
	$field['vod_year'] = isset($tpl[1]) ? $tpl[1] : '';

	// 语言
	$tpl = array();
	preg_match('/<p class="w260"><em>对白语言：<\/em>(.*)<\/p>/isU', $s, $tpl);
	$field['vod_language'] = isset($tpl[1]) ? $tpl[1] : '';

	// 简介
	$tpl = array();
	preg_match('/<div class="introduction" id="intro1">(.*)<\/div>/isU', $s, $tpl);
	$field['vod_content'] = isset($tpl[1]) ? strip_tags($tpl[1]) : '';

	// 获取播放器数据
	$urls = array();
	$tpl = array();
	preg_match_all('/<div class="w980_b1px mt10 clearfix">(.*)<div class="blank_8"><\/div>/isU', $s, $tpl);
	$pays = isset($tpl[1]) ? $tpl[1] : '';
	
	foreach ($pays as $pay) {
		// 播放器类型
		$payTyps = array();
		preg_match('/<script>dm456\.listTip\.get\(\'(.*)\'\);<\/script>/isU', $pay, $payTyps);

		// 集数与网址
		if (isset($payTyps[1])) {
			$r = array();
			preg_match_all('/<a href="(.*)" title="(.*)" target="_blank">(.*)<\/a>/isU', $pay, $r);
			if (isset($r[1])) {
				foreach ($r[1] as $url) {
					$payvodurl = $spiderDetailUrl.$url;
					if(strpos('/', $url) !== false) {
						$payvodurl = $domain.'/'.$url;
					}
					$args = get_pay_vod_id($payvodurl, $domain);
					$urls[$payTyps[1]][] = $args[2];
				}
			}
		}
	}

	$field['vod_server'] = '';
	$field['vod_play'] = '';
	$field['vod_url'] = '';

	foreach ($urls as $payName=>$value) {

		switch ($payName) {
			case 'youku':
				$payName = 'yuku';
				break;
			case 'baidu':
				$payName = 'bdhd';
				break;
			case 'sina':
				$payName = 'sinahd';
				break;
		}

		if ($field['vod_play']) {
			$field['vod_play'] .= '$$$'.$payName;
			$field['vod_url'] .= '$$$'.implode("\r\n", $value);
		} else {
			$field['vod_play'] = $payName;
			$field['vod_url'] = implode("\r\n", $value);
		}
	}

	// 入库	
	$field['vod_inputer'] = 'admin';
	if ($vod) {
		$field['vod_updatetime'] = time();
		$db->update('pp_vod', $field, "vod_id='{$vod['vod_id']}'");
	} else {
		$field['vod_addtime'] = time();
		$field['vod_updatetime'] = time();
		$db->insert('pp_vod', $field);
	}	
}

// 获取影片ID
function get_pay_vod_id($url, $domain) {
	$s = getContentUrl($url, $domain);
	$tpl = array();
	$result = preg_match('/ps:\'(.*)\',pv:\'(.*)\'/isU', $s, $tpl);
	if($result) {
		return $tpl;
	}
	return NULL;
}

// 保存图片
function save_picture($sourceUrl, $toPath) {
	if (!file_exists($toPath)) {
		mkdir($toPath, 0777);
	}
	$filename = '';
	if (strrpos($sourceUrl, '/') !== FALSE) {
		$name = substr($sourceUrl, strrpos($sourceUrl, '/')+1);
		if ($name) {
			$s = file_get_contents($sourceUrl);
			$filename = time().rand(100, 999).'.'.substr($sourceUrl, strrpos($sourceUrl, '.')+1);
			$w = $toPath.'/'.$filename;
			file_put_contents($w, $s);
		}
	}
	return $filename;
}
?>