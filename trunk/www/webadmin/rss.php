<?php
header("Content-Type: text/xml; charset=gb2312");
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
if($id <> ""){
	$sql = "select * from `-table-dafenglei` where `id`=".$id;
	$query = $mysql -> query($sql);
	$row = $mysql -> fetch_array($query);
	if($row){
		$title       = $row['title'] <>"" ? $row['title'] : $row['name'];
		$link        = $row['dir'] <>"" ? $row['dir'] : 'class.php?id='.$id;
		$description = $row['description'] <>"" ? $row['description'] : $title;
		$Rss = new Rss($title,$mydecms['weburl']."/".$link,$description,$mydecms['webname']);
	}else{
		$Rss = new Rss($mydecms['webtitle'],$mydecms['weburl'],$mydecms['webdescription'],$mydecms['webname']);
	}
	$sql  = "select `id`,`title`,`date`,`content`,`dafenglei`,`type` from `-table-article` where `type`=5 and `dafenglei`=".$id." order by `id` desc limit 0,100";
}else{
	$sql  = "select `id`,`title`,`date`,`content`,`dafenglei`,`type` from `-table-article` where `type`=5 order by `id` desc limit 0,100";
	$Rss = new Rss($mydecms['webtitle'],$mydecms['weburl'],$mydecms['webdescription'],$mydecms['webname']);
}
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$Rss -> AddItem($row['title'], $mydecms['weburl']."/".$mydecms['html'].date("Ym",strtotime($row['date'])).'/'.$row['id'].'.html', $row['content'], $row['date']);
}
$Rss -> Display();
?>
