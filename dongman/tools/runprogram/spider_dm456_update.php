#!/usr/local/php/bin/php
<?php
header("Content-Type:text/html; charset=gb2312");
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

// �ж��Ƿ�����
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
$s = getContentUrl($domain.'/donghua/update.html');
preg_match_all('/<strong>(\d+)<\/strong><a href="(.*)" class="video" i="(.*)">(.*)<\/a>/isU', $s, $tpl);
$updateUrls = $tpl[2];
$updateTitles = $tpl[4];

// �Ƿ��һ������
$cates = array(
	'�ձ�����Ƭ'=>2,
	'��������Ƭ'=>1,
	'��½����Ƭ'=>1,
	'ŷ������Ƭ'=>3,
	'TV��'=>23,
	'OVA��'=>24,
	'�糡��'=>25,
);

foreach ($updateUrls as $k=>$updateUrl) 
{	
	sleep(rand(0, 3));
	
	$field = array();	

	// ץȡ����������ϸҳ��
	$spiderDetailUrl = $domain.$updateUrl;
	echo $spiderDetailUrl."\r\n";
	$field['vod_reurl'] = $spiderDetailUrl;

	$s = getContentUrl($spiderDetailUrl);
	//$s = iconv('gbk', 'utf-8', $s);
	//print_r($s);exit;
	
	// ����
	$tpl = array();
	preg_match('/<a href="\/">��ҳ<\/a> > <a href="(.*)">(.*)<\/a> >/isU', $s, $tpl);
	$field['vod_cid'] = isset($tpl[1]) ? isset($cates[$tpl[1]]) ? $cates[$tpl[1]] : 29 : 29;

	// ����
	$tpl = array();
	preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
	$field['vod_name'] = isset($tpl[1]) ? $tpl[1] : '';
	if (!$field['vod_name']) {
		continue;
	}
	
	$vod = $db->fetch_first("SELECT vod_id FROM pp_vod WHERE vod_name='{$field['vod_name']}'");
	if(empty($vod)) {
		// ͼƬ
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

	// �Ƿ����
	$tpl = array();
	preg_match('/<span>&nbsp;������: <a href="(.*)" target="_blank" title="(.*)" class="red">(.*)<\/a>(.*)<\/span>/isU', $s, $tpl);
	$field['vod_continu'] = isset($tpl[3]) && isset($tpl[4]) ? $tpl[3].$tpl[4] : '';

	// ��ȡ����ĸ
	$field['vod_letter'] = '';
	if ($field['vod_name']) {
		$py = new PYInitials();
		$letter = $py->getInitials($field['vod_name']);
		$field['vod_letter'] = substr($letter, 0, 1);
	}

	// ��Ա
	$tpl = array();
	preg_match('/<p><em>�������ǣ�<\/em>(.*)<\/p>/isU', $s, $tpl);
	$field['vod_actor'] = isset($tpl[1]) ? $tpl[1] : '';

	// �������
	$tpl = array();
	preg_match('/<p><em>�������<\/em>(.*)<\/p>/isU', $s, $tpl);
	$field['vod_keywords'] = isset($tpl[1]) ? strip_tags($tpl[1]) : '';

	// ���
	$tpl = array();
	preg_match('/<p class="w260"><em>��Ʒ��ݣ�<\/em>(\d{4})<\/p>/isU', $s, $tpl);
	$field['vod_year'] = isset($tpl[1]) ? $tpl[1] : '';

	// ����
	$tpl = array();
	preg_match('/<p class="w260"><em>�԰����ԣ�<\/em>(.*)<\/p>/isU', $s, $tpl);
	$field['vod_language'] = isset($tpl[1]) ? $tpl[1] : '';

	// ���
	$tpl = array();
	preg_match('/<div class="introduction" id="intro1">(.*)<\/div>/isU', $s, $tpl);
	$field['vod_content'] = isset($tpl[1]) ? strip_tags($tpl[1]) : '';

	// ��ȡ����������
	$urls = array();
	$tpl = array();
	preg_match_all('/<div class="w980_b1px mt10 clearfix">(.*)<div class="blank_8"><\/div>/isU', $s, $tpl);
	$pays = isset($tpl[1]) ? $tpl[1] : '';
	foreach ($pays as $pay) {
		// ����������
		$payTyps = array();
		preg_match('/<script>dm456\.listTip\.get\(\'(.*)\'\);<\/script>/isU', $pay, $payTyps);

		// ��������ַ
		if (isset($payTyps[1])) {
			$r = array();
			preg_match_all('/<a href="(.*)" title="(.*)" target="_blank">(.*)<\/a>/isU', $pay, $r);
			if (isset($r[1])) {
				foreach ($r[1] as $url) {
					$args = get_pay_vod_id($spiderDetailUrl.'/'.$url);
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
		}

		if ($field['vod_play']) {
			$field['vod_play'] .= '$$$'.$payName;
			$field['vod_url'] .= '$$$'.implode("\r\n", $value);
		} else {
			$field['vod_play'] = $payName;
			$field['vod_url'] = implode("\r\n", $value);
		}
	}

	// ���
	$field['vod_addtime'] = time();
	$field['vod_updatetime'] = time();
	$field['vod_inputer'] = 'admin';

	if ($field['vod_name']) {
		if ($vod) {
			$db->update('pp_vod', $field, "vod_id='{$vod['vod_id']}'");
		} else {
			$db->insert('pp_vod', $field);
		}
	}
}

// �������ļ�
@unlink($lockFile);

// ��ȡӰƬID
function get_pay_vod_id($url) {
	$s = getContentUrl($url);
	$tpl = array();
	$result = preg_match('/ps:\'(.*)\',pv:\'(.*)\'/isU', $s, $tpl);
	if($result) {
		return $tpl;
	}
	return NULL;
}

// ����ͼƬ
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