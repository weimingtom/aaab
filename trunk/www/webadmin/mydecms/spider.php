<?php
include 'session.php';
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


include 'config.inc.php';

$pageinfo['title'] = "֩�����м�¼";
$tpl -> display('header');

switch($action){
		case "del":
			$id = $_POST["id"];
			if(is_array($id)){
				for($i = 0 ; $i < count($id); $i++){
					$sql = "select id from `-table-spider` where id=".numeric($id[$i]);
					$query = $mysql -> query($sql);
					$row = $mysql ->fetch_array($query);
					if($row){
						$mysql -> query("delete from `-table-spider` where id=".numeric($id[$i]));
					}else{
						msg('��¼������!','?action=');
					}
				}
				msg('ɾ���ɹ�!','?action=');
			}else{
				if(!is_numeric($id)){
					msg("ID��������",'?action=');
				}else{
					$sql = "select id from `-table-spider` where id=".$id;
					$query = $mysql -> query($sql);
					$row = $mysql ->fetch_array($query);
					if($row){
						$mysql -> query("delete from `-table-spider` where id=".numeric($id));
						msg('ɾ���ɹ�!','?action=');
					}else{
						msg('��¼������!','?action=');
					}
				}
			}
			break;
		case 'qingkong':
			$sql ="truncate table `-table-spider`";
			if($query = $mysql -> query($sql)){
				msg('��ճɹ�!','?action=');
			}else{
				msg('���ʧ��!','?action=');
			}
			break;
		case '':
			$zhizhu = $_GET['zhizhu'];
			if($zhizhu<>'') $where = " where `spidername`='".$zhizhu."' ";
				$pages = new PageClass($mysql -> num_rows($mysql -> query("select id  from `-table-spider`".$where)),PAGESIZE,$page,'?zhizhu='.$zhizhu.'&page={page}');
				$sql  = "select `id`,`spidername`,`url`,`date`,`ip` from `-table-spider`".$where." order by ";
				$sql .= "id Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
				$result = $mysql -> query($sql);
				$tpl -> assign(array('result','mysql','pages'));
				$tpl -> display('spider');
				break;
	}
	
$tpl -> display('footer');

?>
