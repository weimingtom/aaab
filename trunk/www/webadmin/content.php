<?php
define('WWW.MYDECMS.COM',true);
include 'include/config.inc.php';
$id = numeric($_GET['id']);

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


/*******************************ѭ�����ಢ���뵽����class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************ѭ�����ಢ���뵽����*************************************/

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
	exit('ID��������!');
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
