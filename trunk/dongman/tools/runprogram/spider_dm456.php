<?php
define('ROOT_PATH', dirname(__FILE__));

include ROOT_PATH.'/config.php';
include ROOT_PATH.'/../include/db.php';
include ROOT_PATH.'/../include/function.php';

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
$pic = isset($tpl[1]) ? $tpl[1] : '';
echo $pic."\r\n";

// ����
$tpl = array();
preg_match('/ > <strong>(.*)<\/strong><\/div><\/div><\/div>/isU', $s, $tpl);
$field['name'] = isset($tpl[1]) ? $tpl[1] : '';
echo $field['name']."\r\n";

// ���
$tpl = array();
preg_match('/<div class="introduction" id="intro1">(.*)<\/div>/isU', $s, $tpl);
$field['content'] = isset($tpl[1]) ? strip_tags($tpl[1]) : '';
echo $field['content']."\r\n";
//echo $s;

// ��Ƶ����
?>