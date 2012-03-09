<?php
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
$tpl = array();
preg_match('/<div class="introduction" id="intro1">(.*)<\/div>/isU', $s, $tpl);
$field['content'] = isset($tpl[1]) ? strip_tags($tpl[1]) : '';
echo $field['content']."\r\n";
//echo $s;

// 视频集数
?>