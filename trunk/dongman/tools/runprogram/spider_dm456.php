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

$field = array();

// ץȡ����������ϸҳ��
$spiderDetailUrl = 'http://www.dm456.com/donghua/4194/';
$s = getContentUrl($spiderDetailUrl);
//$s = iconv('gbk', 'utf-8', $s);

// ͼƬ
$tpl = array();
preg_match('/<img src="(.*)" class="pic" title/isU', $s, $tpl);
$field['vod_pic'] = isset($tpl[1]) ? $tpl[1] : '';
save_picture($domain.$field['vod_pic'], PICTURE_SAVE_PATH);

// ����
$tpl = array();
preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
$field['vod_name'] = isset($tpl[1]) ? $tpl[1] : '';

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

print_r($field);

exit;
// ���
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

print_r($urls);

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
	$name = '';
	if (strrpos($sourceUrl, '/') !== FALSE) {
		$name = substr($sourceUrl, strrpos($sourceUrl, '/')+1);
		if ($name) {
			$s = file_get_contents($sourceUrl);
			$w = $toPath.'/'.$name;
			file_put_contents($w, $s);
		}
	}
}
?>