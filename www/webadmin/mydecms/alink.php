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


switch($action){
	case "add":
		$pageinfo['title']    = "�������";
		$pageinfo['subtitle'] = "���";
		$pageinfo['act']      = "act_add";
		$tpl -> display('header');
		$tpl -> display('alink_add');
		break;
	case "act_add":
			$arr = array(
			'name'           => str($_POST['name']),
			'url'            => str($_POST['url']),
			'sort'           => str($_POST['sort']),
			'target'         => str($_POST['target'])
			);
			$pageinfo['title'] = "�������";
			$tpl -> display('header');
			if($mysql -> insert("-table-alink",$arr)){
				msg('��ӳɹ�!','?action=');
			}else{
				msg('���ʧ��!','?action=');
			}
			break;
	case "act_edit":
		$id =  numeric($_POST['id']);
		$sql  = "select `id` from `-table-alink` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
				$arr = array(
					'name'           => str($_POST['name']),
					'url'            => str($_POST['url']),
					'sort'           => str($_POST['sort']),
					'target'         => str($_POST['target'])
				);
				$pageinfo['title'] = "�޸�����";
				$tpl -> display('header');
				if($mysql -> update("-table-alink",$arr,' id='.$id)){
					msg('�޸ĳɹ�!','?action=');
				}else{
					msg('�޸�ʧ��!','?action=');
				}
		}else{
			msg('��¼������!','?action=');
		}	
		break;
	case "edit":
		$id =  numeric($_GET['id']);
		$pageinfo['title'] = "�޸�����";
		$pageinfo['subtitle'] = "�޸�";
		$pageinfo['act']      = "act_edit";
		$tpl -> display('header');
		$sql  = "select * from `-table-alink` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
			$tpl -> display('alink_add');
		}else{
			msg('��¼������!','?action=');
		}
		break;
	case "del":
		$pageinfo['title'] = "ɾ������";
		$tpl -> display('header');
		$id = $_POST["id"];
		if(is_array($id)){
			for($i = 0 ; $i < count($id); $i++){
				$sql = "select id from `-table-alink` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-alink` where id=".numeric($id[$i]));
				}else{
					msg('��¼������!','?action=');
				}
			}
			msg('ɾ���ɹ�!','?action=');
		}else{
			if(!is_numeric($id)){
				msg("ID��������",'?action=');
			}else{
				$sql = "select id from `-table-alink` where id=".$id;
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-alink` where id=".numeric($id));
					msg('ɾ���ɹ�!','?action=');
				}else{
					msg('��¼������!','?action=');
				}
			}
		}
		break;
	case "piliang":
		$idarr    = $_POST["idd"];
		$sort = $_POST["sort"];
		$target = $_POST["target"];
		$pageinfo['title'] = '���������޸�';
		$tpl -> display('header');
		if(is_array($idarr)){
			for($i = 0 ; $i < count($idarr); $i++){
				$sql = "select `id` from `-table-alink` where id=".numeric($idarr[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$arr = array('sort'  => $sort[$i],'target'=>$target[$i]);
					$mysql -> update("-table-alink",$arr,' id='.$idarr[$i]);
				}else{
					msg('��¼������!',"?page=$page");
				}
			}
			msg('�����޸ĳɹ�!',"?page=$page");
		}
		msg('����δ֪����!',"?page=$page");
		break;
	default:
			$pageinfo['title'] = "��������";
			$tpl -> display('header');
			$pages = new PageClass($mysql -> num_rows($mysql -> query("select id  from `-table-alink`")),PAGESIZE,numeric($_GET['page']),'?page={page}');
			$sql  = "select * from `-table-alink` order by sort asc,";
			$sql .= "id Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
			$result = $mysql -> query($sql);
			$tpl -> assign(array('result','mysql','pages','page'));
			$tpl -> display('alink');
			break;
}

$tpl -> display('footer');
?>
