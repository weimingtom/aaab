<?php
if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����');
date_default_timezone_set(PRC);

define('PAGESIZE',20);


/**   
+----------------------�����Ȩע����Ϣ--------------------
* �������ƣ��ͳϹٷ���վ  
* ���������http://www.mydecms.com/
* �������ߣ�С��
* ����QQ�ţ�306282268
*
* ����������1���ͳ����¹���ϵͳ��һ����ȫ��ѵ�PHP��Դ�����κ���֯���߸���û�о����������ߵ����������Գ����������׬ȡ���档
*           2���벻Ҫɾ������İ�Ȩ��Ϣ��ע����ҳ�ײ��Ĺٷ����Ӽ��������������İ�Ȩע����Ϣ���������ر��˵������ɹ���
* 		    3�������Ϊ�������©��������վ��������ɵ���ʧ���������߲���Ҫ�е��κ����Ρ�����㷢����©���뼴ʱ��֪���ߡ�
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