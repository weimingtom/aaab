<?php
session_start();
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
	case "act_login":
		$pageinfo['title']    = "��¼�ͳ����¹���ϵͳ��̨";
		$tpl -> display('header');
		if($_POST["webadmin"]== "" || $_POST["webpass"] == "" || $_POST["nzm"] == ""){
			msg("����û����д�����ʺŻ��߹������������֤��!",1,1);
		}else{
			if($_POST["nzm"] <> $_SESSION["WWW_MYDECMS_COM_NZM"]){
				msg("��֤�����!",1,1);
			}else{
				if($mydecms['webadmin'] == md5($_POST["webadmin"]) && $mydecms['webpass'] == md5($_POST["webpass"])){
					$_SESSION["MYDECMSADMIN_ADMIN"] = md5($_POST["webpass"]);
					echo "<script type=\"text/javascript\">window.location='index.php';</script>";
					$mysql -> close();
				}else{
					$mysql -> close();
					msg("�����ʺŻ��߹����������!","login.php",1);
				}
			}
		}
		msg('�ɹ�ʧ��!','?');
		$tpl -> display('footer');
		break;
	case "out":
		$tpl -> display('header');
		session_unset($_SESSION["WWW_MYDECMS_COM_NZM"]);
		session_destroy();
		msg("�˳��ɹ�!","../",1);
		$tpl -> display('footer');
		break;
	default:
		$tpl -> display('login');
		break;
}
?>
