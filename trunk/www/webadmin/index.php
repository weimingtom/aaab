<?php
define('WWW.MYDECMS.COM',true);

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
if(!file_exists("./yes.txt")){
	header("Location: ./setup.php");
	exit();
}
if(file_exists("./setup.php")){
	exit("网站已经安装完毕，请<a href='setup.php?action=delfile'>点击这里删除安装文件</a>。");
}


include 'include/config.inc.php';

/*******************************循环分类并加入到数组class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************循环分类并加入到数组*************************************/

$tpl = new templateClass($mydecms['moban']);
$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page'));
$tpl -> display('index');

?>
