<?php
define('WWW.MYDECMS.COM',true);

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
if(!file_exists("./yes.txt")){
	header("Location: ./setup.php");
	exit();
}
if(file_exists("./setup.php")){
	exit("��վ�Ѿ���װ��ϣ���<a href='setup.php?action=delfile'>�������ɾ����װ�ļ�</a>��");
}


include 'include/config.inc.php';

/*******************************ѭ�����ಢ���뵽����class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************ѭ�����ಢ���뵽����*************************************/

$tpl = new templateClass($mydecms['moban']);
$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page'));
$tpl -> display('index');

?>
