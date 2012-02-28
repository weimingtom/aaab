<?php
define('WWW.MYDECMS.COM',true);
include 'include/config.inc.php';
$id = numeric($_GET['id']);

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


/*******************************循环分类并加入到数组class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************循环分类并加入到数组*************************************/

$article = array();
$sql = "select * from `-table-article` where `id`=".$id;
$query = $mysql -> query($sql);
$row = $mysql -> fetch_array($query);
if($row){
	$article['id']          = $row['id'];
	$article['title']       = $row['title'];
	$article['keywords']    = $row['keywords'];
	$article['description'] = $row['description'];
	$article['type']        = $row['type'];
	$article['content']     = $row['content'];
	$article['date']        = $row['date'];
	$article['dafenglei']   = $row['dafenglei'];
	$article['count']       = $row['count'];
	$article['tag']         = $row['tag'];
	$sql = "select * from `-table-dafenglei` where `id`=".$row['dafenglei'];
	$query = $mysql -> query($sql);
	$rs = $mysql -> fetch_array($query);
	if($rs){
		$article['class_name']      = $rs['name'];
		$article['class_dir']              = (trim($rs['dir']) <> "" ? $rs['dir'] : 'class.php?id='.$rs['id']);
	}else{
		$article['class_name']      = '';
		$article['class_dir']       = '';
	}
}else{
	exit('ID参数错误!');
}
$mydecms['NextType'] = false;
$sql  = "select * from `-table-alink` order by sort asc";
$result = $mysql -> query($sql);
while($row = $mysql -> fetch_array($result)){
	$alink[$row['name']] = array($row['url'],$row['target']);
	$mydecms['NextType'] = true;
}

if($mydecms['NextType']){
	if(count($alink)>1){
		foreach($alink as $k => $v){
			$article['content'] = str_replace_once($k, '<a href="'.$v[0].'" title="'.$k.'"'.($v[1] <> "NULL" && $v[1] <> "" ? ' target="'.$v[1].'"' :'').'>'.$k.'</a>', $article['content']);
		}
	}else{
		$k = key($alink);
		$v = $alink[0];
		$article['content'] = str_replace_once($k, '<a href="'.$v[0].'" title="'.$k.'"'.($v[1] != "NULL" && $v[1] != "" ? ' target="'.$v[1].'"' :'').'>'.$k.'</a>', $article['content']);
	}
}

$tpl = new templateClass($mydecms['moban'],'bianyi/');
$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page','article'));
$tpl -> display('content');

?>
