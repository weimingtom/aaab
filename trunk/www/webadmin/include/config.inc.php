<?php
if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����');

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