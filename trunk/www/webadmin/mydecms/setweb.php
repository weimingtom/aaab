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
	case 'action_setweb':
		$arr = array(
			'webname'           => str($_POST['webname']),
			'weburl'            => str($_POST['weburl']),
			'webtitle'          => str($_POST['webtitle']),
			'webkeywords'       => str($_POST['webkeywords']),
			'webdescription'    => str($_POST['webdescription']),
			'moban'             => str($_POST['moban']),
			'html'              => str($_POST['html']),
			'icp'               => str($_POST['icp']),
			'openspider'        => str($_POST['openspider']),
			'tongji'            => str_replace(array('"',"'"),array('+=+','-+-'),$_POST['tongji']),
			'classid'           => str($_POST['classid']),
		);
			
			if($mysql -> update("-table-setconfig",$arr)){
				$pageinfo['title'] = '�޸���վ���óɹ�!';
				$tpl -> display('header');
				msg('�޸���վ���óɹ�!','setweb.php');
			}else{
				$tpl -> display('header');
				msg('�޸���վ����ʧ��!','setweb.php');
			}
		break;
	case 'pass':
		$pageinfo['title'] = '�޸���վ�����ʺż�����!';
		$pageinfo['act']   = 'action_pass';
		$tpl -> display('header');
		$tpl -> display('pass');
		break;
	case 'action_pass':
			$old_user = str($_POST['old_user']);
			$new_user = str($_POST['new_user']);
			$sub_user = str($_POST['sub_user']);
			$old_pass = str($_POST['old_pass']);
			$new_pass = str($_POST['new_pass']);
			$sub_pass = str($_POST['sub_pass']);
			$pageinfo['title'] = '�޸Ĺ����ʺż�����';
			$tpl -> display('header');
			if(isset($old_user)){
				if(md5($new_user) !== md5($sub_user) && md5($new_user) != '') msg('���û���ȷ���û���һ��!','?action=pass');
				
			}
			if(isset($old_pass)){
				if(md5($new_pass) !== md5($sub_pass) && md5($new_pass) != '') msg('��������ȷ�����벻һ��!','?action=pass');
			}
			if(md5($old_user) == $mydecms['webadmin'] && md5($old_pass) == $mydecms['webpass']){
				$arr = array(
					'webadmin'    => md5($new_user),
					'webpass'     => md5($new_pass)
				);
				if($mysql -> update("-table-setconfig",$arr)){
					msg('�޸ĳɹ�!','?action=pass');
				}else{
					msg('�޸�ʧ��!','?action=pass');
				}
			}else{
				msg('���û����߾����벻��ȷ!','?action=pass');
			}
			break;
	case 'moren':
		$pageinfo['title'] = '��̨Ĭ��ҳ��';
		$tpl -> display('header');
		$tpl -> display('moren');
		break;
	default:
		$pageinfo['title'] = '�޸���վ����';
		$pageinfo['act']   = 'action_setweb';
		$mydecms['mobanarr'] = getAllFiles("../template/");
		$tpl -> display('header');
		$tpl -> display('setweb');
		break;
}

$tpl -> display('footer');

function getAllFiles($filedir) {
	$allfiles = array(); //�ļ�������
	if (is_dir($filedir)) {//�ж�Ҫ�������Ƿ���Ŀ¼
		if ($dh = opendir($filedir)) {//��Ŀ¼����ֵһ��Ŀ¼���(directory handle)
			while (FALSE !== ($filestring = readdir($dh))) {//��ȡĿ¼�е��ļ���
				if ($filestring != '.' && $filestring != '..' && is_dir($filedir."/".$filestring)) {//�������.��..(ÿ��Ŀ¼�¶�Ĭ����.��..)
					$mobanfile = str_replace("../","",$filedir . $filestring."/");
					$allfiles[$mobanfile] = $mobanfile; //������ļ�����һ���ļ�����Ŀ¼,ֱ�Ӹ�ֵ���ļ�������
				}
			}
		}else{//��Ŀ¼ʧ��
			echo('��ģ��Ŀ¼ʧ��');
		}
		closedir($dh);//�ر�Ŀ¼���
		return $allfiles;//�����ļ�������
	}else{//Ŀ¼������
		echo('ģ��Ŀ¼������');
	}
}


?>
