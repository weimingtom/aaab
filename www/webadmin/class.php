<?php
define('WWW.MYDECMS.COM',true);
include 'include/config.inc.php';

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


$id = numeric($_GET['id']);
$page = numeric($_GET['page']);
if($page=="") $page=1;

/*******************************循环分类并加入到数组class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
	$class_arr2[]          = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************循环分类并加入到数组*************************************/

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
	msg('页面不存在!',$config['weburl']);
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
