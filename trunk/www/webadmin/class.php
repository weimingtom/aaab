<?php
define('WWW.MYDECMS.COM',true);
include 'include/config.inc.php';

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


$id = numeric($_GET['id']);
$page = numeric($_GET['page']);
if($page=="") $page=1;

/*******************************ѭ�����ಢ���뵽����class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
	$class_arr2[]          = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************ѭ�����ಢ���뵽����*************************************/

$sql = "select * from `-table-dafenglei` where `id`=".$id;
$query = $mysql -> query($sql);
$row = $mysql ->fetch_array($query);
$article = array();
if($row){
	$dafenglei['name']        = $row['name'];
	$dafenglei['title']       = $row['title'];
	$dafenglei['id']          = $row['id'];
	$dafenglei['keywords']    = $row['keywords'];
	$dafenglei['description'] = $row['description'];
	$dafenglei['dir']         = $row['dir']<>"" ? $row['dir'] :"class.php?id=".$id;
	$dafenglei['classid']     = $row['classid'];
	$dafenglei['mobanname']   = $row['mobanname'];
	$idstr = dafenglei_in($id,$id);
	$dafenglei['idstr']       = $idstr <> "" ? $id.$idstr : $id;
}else{
	msg('ҳ�治����!',$config['weburl']);
}


$tpl = new templateClass($mydecms['moban'],'bianyi/');
$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page','dafenglei'));
if($dafenglei['mobanname']){
	$tpl -> display($dafenglei['mobanname']);
}else{
	$tpl -> display('class');
}


function dafenglei_in($id,$index,$idstr="")
{	
	global $class_arr2;
	global $mysql;
	for($i=0;$i<count($class_arr2);$i++){
		if($class_arr2[$i][2]==$id){
			$idstr .= ",".$class_arr2[$i][0];
			$idstr = dafenglei_in($class_arr2[$i][0],$index,$idstr);
		}
	}
	return $idstr;
}

?>
