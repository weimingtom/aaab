<?php
set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));

include ROOT_PATH.'/config.php';
include ROOT_PATH.'/../include/db.php';
include ROOT_PATH.'/../include/function.php';
include ROOT_PATH.'/../include/letter.php';

$db = new db();
$db->connect(DBHOST, DBUSER, DBPWD, DBNAME, DBCHARSET);

$domain = "http://www.dm456.com";
// ������µ��б�
//$spiderMainUrl = 'http://www.dm456.com/donghua/update.html';

// ��ȡ�б�ҳURL
//$spiderListUrl = 'http://www.dm456.com/donghua/riben/index_3.html';
//$s = getContentUrl($spiderDetailUrl);
//
//$tpl = array();
//preg_match('/<a href="(.*)" class="pic">/isU', $s, $tpl);
//$listUrl = isset($tpl[1]) ? $tpl[1] : '';
//
//print_r($listUrl);exit;

$field = array();

// ץȡ����������ϸҳ��
$spiderDetailUrl = $domain.'/donghua/4194/';
$s = getContentUrl($spiderDetailUrl);
//$s = iconv('gbk', 'utf-8', $s);

// ͼƬ
$tpl = array();
preg_match('/<img src="(.*)" class="pic" title/isU', $s, $tpl);
$vod_pic = isset($tpl[1]) ? $tpl[1] : '';
$filename = save_picture($domain.$vod_pic, dirname(dirname(PICTURE_SAVE_PATH)).'/Upload/'.date('Y-m'));
$field['vod_pic'] = date('Y-m').'/'.$filename;

// ����
$tpl = array();
preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
$field['vod_name'] = isset($tpl[1]) ? $tpl[1] : '';

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

print_r($field);
exit;

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
	if ($field['vod_play']) {
		$field['vod_play'] .= '$$$'.$payName;
		$field['vod_url'] .= '$$$'.implode("\r\n", $value);
	} else {
		$field['vod_play'] = $payName;
		$field['vod_url'] = implode("\r\n", $value);
	}
}

/*
$urlMax = 0;
foreach ($urls as $value) {
	$num = count($value);
	if ($num > $urlMax) {
		$urlMax = $num;
	}
}

$arr = array();
foreach ($urls as $payName=>$value) {
	
	if ($field['vod_play']) {
		$field['vod_play'] .= '$$$'.$payName;
	} else {
		$field['vod_play'] = $payName;
	}
	
	$count = count($value) - 1;
	for($i=0; $i<$urlMax; $i++) {
		if ($count >= $i) {
			if (isset($arr[$i])) {
				$arr[$i] .= '$$$'.$value[$i];
			} else {
				$arr[$i] = $value[$i];
			}
		} else {
			if (isset($arr[$i])) {
				$arr[$i] .= '$$$ ';
			} else {
				$arr[$i] = ' ';
			}
		}
	}
}
*/

// ���
$field['vod_addtime'] = time();
$field['vod_inputer'] = 'admin';
$db->insert('pp_vod', $field);

print_r($field);

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