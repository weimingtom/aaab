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


$mydecms['searchkey'] = str_len(str($_GET['key']),50,1);
$page = numeric($_GET['page']);
if($page=="") $page=1;

/*******************************ѭ�����ಢ���뵽����class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************ѭ�����ಢ���뵽����*************************************/


$tpl = new templateClass($mydecms['moban'],'bianyi/');
$tpl -> assign(array('mysql','mydecms','tpl','page','class_arr'));
$tpl -> display('search');

?>
