<?php
set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));

include ROOT_PATH.'/config.php';
include ROOT_PATH.'/../include/db.php';
include ROOT_PATH.'/../include/function.php';

$db = new db();
$db->connect(DBHOST, DBUSER, DBPWD, DBNAME, DBCHARSET);

$domain = "http://www.dm456.com";
// 最近更新的列表
//$spiderMainUrl = 'http://www.dm456.com/donghua/update.html';

$field = array();

// 抓取动漫内容详细页的
$spiderDetailUrl = 'http://www.dm456.com/donghua/4194/';
$s = getContentUrl($spiderDetailUrl);
//$s = iconv('gbk', 'utf-8', $s);

// 图片
$tpl = array();
preg_match('/<img src="(.*)" class="pic" title/isU', $s, $tpl);
$pic = isset($tpl[1]) ? $tpl[1] : '';
echo $pic."\r\n";

// 标题
$tpl = array();
preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
$field['name'] = isset($tpl[1]) ? $tpl[1] : '';
echo $field['name']."\r\n";

// 简介
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
				$args = get_pay_vod_id($spiderDetailUrl.'/'.$url);
				$urls[$payTyps[1]][] = $args[2];
			}
		}
	}
}

print_r($urls);

// 获取影片ID
function get_pay_vod_id($url) {
	$s = getContentUrl($url);
	$tpl = array();
	$result = preg_match('/ps:\'(.*)\',pv:\'(.*)\'/isU', $s, $tpl);
	if($result) {
		return $tpl;
	}
	return NULL;
}
?>