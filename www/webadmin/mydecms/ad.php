<?php
include 'session.php';
define('WWW.MYDECMS.COM',true);
include 'config.inc.php';

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

$typeid = $_GET['typeid'];
if(!is_numeric($typeid)) $typeid ="";
$tpl -> assign(array('result','mysql','pages','page','class_arr','pageinfo','mydecms','key','type','classid','row','typeid','action'));
switch($action){
	case "add":
		$pageinfo['title']    = "��ӹ��";
		$pageinfo['subtitle'] = "���";
		$pageinfo['act']      = "act_add";
		$tpl -> display('header');
		$tpl -> display('ad_add');
		break;
	case "act_add":
			$arr = array(
					'name'           => str($_POST['name']),
					'beizhu'         => str($_POST['beizhu']),
					'sort'           => numeric($_POST['sort']),
					'content'        => str_replace(array('"',"'"),array('+=+','-+-'),str($_POST['content'])),
					'type'           => numeric($_POST['type'])
			);
			$pageinfo['title'] = "��ӹ��";
			$tpl -> display('header');
			if($mysql -> insert("-table-ad",$arr)){
				msg('��ӳɹ�!','?action=');
			}else{
				msg('���ʧ��!','?action=');
			}
			break;
	case "act_edit":
		$id =  numeric($_POST['id']);
		$sql  = "select `id` from `-table-ad` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
				$arr = array(
					'name'           => str($_POST['name']),
					'beizhu'         => str($_POST['beizhu']),
					'sort'           => numeric($_POST['sort']),
					'content'        => str_replace(array('"',"'"),array('+=+','-+-'),str($_POST['content'])),
					'type'           => numeric($_POST['type'])
				);
				$pageinfo['title'] = "�޸Ĺ��";
				$tpl -> display('header');
				if($mysql -> update("-table-ad",$arr,' id='.$id)){
					msg('�޸ĳɹ�!','?action=&typeid='.$typeid);
				}else{
					msg('�޸�ʧ��!','?action=&typeid='.$typeid);
				}
		}else{
			msg('��¼������!','?action=&typeid='.$typeid);
		}	
		break;
	case "edit":
		$id =  numeric($_GET['id']);
		$pageinfo['title'] = "�޸Ĺ��";
		$pageinfo['subtitle'] = "�޸�";
		$pageinfo['act']      = "act_edit";
		$tpl -> display('header');
		$sql  = "select * from `-table-ad` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
			$tpl -> display('ad_add');
		}else{
			msg('��¼������!','?action=&typeid='.$typeid);
		}
		break;
	case "del":
		$pageinfo['title'] = "ɾ�����";
		$tpl -> display('header');
		$id = $_POST["id"];
		if(is_array($id)){
			for($i = 0 ; $i < count($id); $i++){
				$sql = "select id from `-table-ad` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-ad` where id=".numeric($id[$i]));
				}else{
					msg('��¼������!','?action=');
				}
			}
			msg('ɾ���ɹ�!','?action=');
		}else{
			if(!is_numeric($id)){
				msg("ID��������",'?action=');
			}else{
				$sql = "select id from `-table-ad` where id=".$id;
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-ad` where id=".numeric($id));
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
		$type  = $_POST["type"];
		$pageinfo['title'] = '��������޸�';
		$tpl -> display('header');
		if(is_array($idarr)){
			for($i = 0 ; $i < count($idarr); $i++){
				$sql = "select `id` from `-table-ad` where id=".numeric($idarr[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$arr = array('sort'  => $sort[$i],'type'=>$type[$i]);
					$mysql -> update("-table-ad",$arr,' id='.$idarr[$i]);
				}else{
					msg('��¼������!',"?page=$page&typeid=".$typeid);
				}
			}
			msg('�����޸ĳɹ�!',"?page=$page&typeid=".$typeid);
		}
		msg('����δ֪����!',"?page=$page");
		break;
	default:
			$pageinfo['title'] = "������";
			$tpl -> display('header');
			$pages = new PageClass($mysql -> num_rows($mysql -> query("select id  from `-table-ad`".($typeid<>"" ? ' where `type`='.$typeid : ''))),PAGESIZE,numeric($_GET['page']),'?page={page}');
			$sql  = "select * from `-table-ad`".($typeid<>"" ? ' where`type`='.$typeid : '')." order by sort asc,";
			$sql .= "id Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
			$result = $mysql -> query($sql);
			$tpl -> display('ad');
			break;
}

$tpl -> display('footer');
?>
