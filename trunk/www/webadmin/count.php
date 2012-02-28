<?php
header("Content-Type:text/html;charset=gb2312"); 
define('WWW.MYDECMS.COM',true);
include 'include/config.inc.php';
$id = numeric($_GET['id']);
	$sql = "select `id`,`count` from `-table-article` where `id`=".$id;
	$query = $mysql -> query($sql);
	$row = $mysql -> fetch_array($query);
	if($row){
		$mysql -> query("update `-table-article` set count=count+1 where `id`=".$id);
		echo $row['count'] + 1;
	}
?>