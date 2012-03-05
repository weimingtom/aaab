<?php
if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作');

/**   
+----------------------程序版权注释信息--------------------
* 程序名称：贤诚官方网站  
* 程序官网：http://www.mydecms.com/
* 程序作者：小贤
* 作者QQ号：306282268
*
* 程序声明：1、贤诚文章管理系统是一套完全免费的PHP开源程序，任何组织或者个人没有经过程序作者的允许，不得以出售其程序来赚取利益。
*           2、请不要删除程序的版权信息（注：网页底部的官方链接及你现在所看到的版权注释信息），请尊重别人的劳力成果。
* 		    3、如果因为程序存在漏洞导致网站被入侵造成的损失，程序作者不需要承担任何责任。如果你发现有漏洞请即时告知作者。
+----------------------------------------------------------   
*/ 

include 'mysql.Class.php';

include 'page.Class.php';

include 'function.inc.php';

include 'template.Class.php';

include 'rss.class.php';

include 'db.php';

include 'spider.php';

define('PAGESIZE',20);

$mysql = new mysql_Class($web['host'],$web['dbuser'],$web['dbpass']);

$mysql -> select_db($web['dbname']);

if($web['table'] <> "mydecms_") $mysql -> set_db_start($web['table']);

$mydecms = array();
$sql  = "select * from `-table-setconfig`";
$query = $mysql -> query($sql);
$row = $mysql -> fetch_array($query);

if($row){
	$mydecms['webname']        = $row['webname'];
	$mydecms['weburl']         = $row['weburl'];
	$mydecms['webtitle']       = $row['webtitle'];
	$mydecms['webkeywords']    = $row['webkeywords'];
	$mydecms['webdescription'] = $row['webdescription'];
	$mydecms['moban']          = $row['moban'];
	$mydecms['openspider']     = $row['openspider'];
	$mydecms['html']           = $row['html'];
	$mydecms['icp']            = $row['icp'];
	$mydecms['classid']        = $row['classid'];
	$mydecms['tongji']         = str_replace(array('+=+','-+-'),array('"',"'"),$row['tongji']);
}

$mydecms['NextType'] = false;

$mydecms['LinkType'] = false;

if($mydecms['openspider']==5 || $mydecms['openspider'] =="5"){
	$url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$spider = get_naps_bot();
	
	if($spider) {
		$arr = array(
			"spidername"=>$spider,
			"url"=>$url,
			"date"=>date("Y-m-d H:i:s"),
			"ip"=>get_ip1()
			);
		$mysql -> insert("-table-spider",$arr);
	}
}

/*
$sql  = "select * from `-table-ad` where `type`=5 order by `sort` asc,`id` desc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$mydecms['webad'][$row['id']] = str_replace(array('+=+','-+-'),array('"',"'"),$row['content']);
}*/

function get_ad_rows($id) {
	global $mysql;
	$s = $mysql->result_first("SELECT content FROM -table-ad WHERE id='$id' AND `type`='5'");
	if ($s) {
		$s = stripslashes(str_replace(array('+=+','-+-'),array('"',"'"), $s));
	} 
	return $s;
}
?>