<?php
header("Content-Type: text/xml; charset=gb2312");
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
