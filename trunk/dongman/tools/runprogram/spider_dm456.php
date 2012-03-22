<?php
set_time_limit(0);
define('ROOT_PATH', dirname(__FILE__));
define('RUNPROGRAM_ID', $value);

include ROOT_PATH.'/config.php';
include ROOT_PATH.'/../include/db.php';
include ROOT_PATH.'/../include/function.php';
include ROOT_PATH.'/../include/letter.php';

$locked = process_islocked(PROCESSLOCK_LOCKID);
if ($locked === TRUE) {
	echo 'process '.PROCESSLOCK_LOCKID.' is running.';
	exit();
} else {
	process_lock(PROCESSLOCK_LOCKID);
}

// 判断是否锁定
mkdirs(ROOT_PATH.'/syslog');
$lockFile =  ROOT_PATH.'/syslog/dm456.lock';
if(file_exists($lockFile)) {
	exit;
}

$db = new db();
$db->connect(DBHOST, DBUSER, DBPWD, DBNAME, DBCHARSET);

$domain = "http://www.dm456.com";

// 最近更新的列表
//$spiderMainUrl = 'http://www.dm456.com/donghua/update.html';

$categorys = array(
	2=>array('donghua/riben',	97),		// 日本动漫片
	1=>array('donghua/dalu',	25),		// 国产动画片
	3=>array('donghua/oumei',	17),		// 欧美动画片
	23=>array('donghua/tv',		91),		// TV
	24=>array('donghua/ova',	16),		// ova
	25=>array('donghua/juchang',	25),		// 剧场
);

foreach ($categorys as $cateName=>$countPage) 
{
	for ($i=1; $i<=$countPage; $i++) 
	{
		// 获取列表页URL
		$spiderListUrl = $domain.'/donghua/riben/index_'.$i.'.html';
		$s = getContentUrl($spiderListUrl);

		$tpl = array();
		preg_match_all('/<dt><a href="(.*)" title="(.*)">(.*)<\/a><\/dt>/isU', $s, $tpl);
		$listUrls = isset($tpl[1]) ? $tpl[1] : '';

		foreach ($listUrls as $listUrl) 
		{
			$field = array();

			// 抓取动漫内容详细页的
			$spiderDetailUrl = $domain.$listUrl;
			$s = getContentUrl($spiderDetailUrl);
			//$s = iconv('gbk', 'utf-8', $s);

			// 图片
			$tpl = array();
			preg_match('/<img src="(.*)" class="pic" title/isU', $s, $tpl);
			$vod_pic = isset($tpl[1]) ? $tpl[1] : '';
			$filename = save_picture($domain.$vod_pic, dirname(dirname(PICTURE_SAVE_PATH)).'/Upload/'.date('Y-m'));
			$field['vod_pic'] = date('Y-m').'/'.$filename;

			// 标题
			$tpl = array();
			preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
			$field['vod_name'] = isset($tpl[1]) ? $tpl[1] : '';

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

			print_r($field);
			exit;

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

			// 入库
			$field['vod_addtime'] = time();
			$field['vod_inputer'] = 'admin';
			$db->insert('pp_vod', $field);

			print_r($field);
		}
	}
}
touch($lockFile);

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