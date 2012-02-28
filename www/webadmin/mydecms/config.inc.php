<?php
if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作');
date_default_timezone_set(PRC);

define('PAGESIZE',20);


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


include '../include/mysql.Class.php';

include '../include/page.Class.php';

include '../include/function.inc.php';

include '../include/template.Class.php';

include '../include/db.php';

$action = $_GET['action'];
$page   = numeric($_GET['page']);
$act    = $_GET['act'];

$mysql = new mysql_Class($web['host'],$web['dbuser'],$web['dbpass']);

$mysql -> select_db($web['dbname']);

if($web['table'] <> "mydecms_") $mysql -> set_db_start($web['table']);

$pageinfo = array();

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
	$mydecms['webadmin']       = $row['webadmin'];
	$mydecms['webpass']        = $row['webpass'];
	$mydecms['tongji']         = str_replace(array('+=+','-+-'),array('"',"'"),$row['tongji']);
	$mydecms['classid']        = $row['classid'];
}

$tpl =  new templateClass();

$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page','pageinfo','action','row'));

?>