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
$mobanarrfile = "../".$mydecms['moban'].'mobanfilearr.php';
if(file_exists($mobanarrfile)) include $mobanarrfile;

$mobandir = "../".$mydecms['moban']; //������������·��
$filename = str($_GET['filename']);
if($filename == 'mobanfilearr') msg('ģ���ļ�����Ϊ <font color="red">mobanfilearr</font>',"?action=");
$tpl -> assign(array('filearr','mobanfilearr','action','pageinfo','filename','fileinfo','mydecms'));

switch($action){
	case 'add':
		$pageinfo['title'] = '���ģ��';
		$pageinfo['subtitle'] = '���';
		$pageinfo['act']   = 'act_add';
		$tpl -> display('header');
		$tpl -> display('moban_add');
		break;
	case 'edit':
		$pageinfo['title']    = '�޸�ģ��';
		$pageinfo['subtitle'] = '�޸�';
		$pageinfo['act']      = 'act_edit';
		$tpl -> display('header');
		$fileinfo['filename']    = $filename;
		$filedir = $mobandir.$filename.".php";
		if(!file_exists($filedir)){
			exit("ģ���ļ������ڣ�");
		}

		$fp = fopen($filedir,"r");
		$content = fread($fp,filesize($filedir));//���ļ�
		fclose($fp);
		$fileinfo['filecontent'] = str_replace("&","&amp;",$content);
		$tpl -> display('moban_add');
		break;
	case 'act_add':
		$pageinfo['title'] = '���ģ��';
		$tpl -> display('header');
		$filename  = str_replace("'","",$_POST['filename']);
		$content   = $_POST['content'];
		$mobantext = str_replace(array("'","\\"),"",str($_POST['mobantext']));
		$filedir = $mobandir.$filename.".php";
		if(file_exists($filedir)) msg('<span class="red">'.$filename.'</span>ģ���ļ��Ѿ����ڣ����޸�ģ�����ƣ�',1);
		if(array_key_exists($filename,$mobanfilearr)){
			$mobanfilearr[$filename] = $mobantext;
		}else{
			$mobanfilearr = array_merge_recursive($mobanfilearr,array("$filename"=>$mobantext));
		}
		
		$str  = '<?php 
';
		$str .= '$mobanfilearr = array('."\n";
		if(count($mobanfilearr)>1){
			$i = 0;
			foreach($mobanfilearr as $k => $v){
				$str .= ($i==0 ? '' : ",")."'$k'=>'$v'";
				$i++;
			}
		}else{
			$str .= "'$filename'=>'$mobantext'";
		}
		$str .= ');';
		$str .='
?>';
		
		
		if($fp = fopen($filedir,"w") or dir($filedir."�ļ���ʧ��! ")){
				fwrite($fp,trim($content));
				/*�ر��ļ�*/
				fclose($fp);
				msg('��ӳɹ���',"?action=");
		}else{
				msg('���ʧ�ܣ�',"?action=");
		}
		break;
	case 'act_edit':
		$pageinfo['title'] = '�޸�ģ��';
		$tpl -> display('header');
		$filename  = str_replace("'","",$_POST['filename']);
		$content   = $_POST['content'];
		$mobantext = str_replace(array("'","\\"),"",str($_POST['mobantext']));
		$filedir = $mobandir.$filename.".php";
		if(array_key_exists($filename,$mobanfilearr)){
			$mobanfilearr[$filename] = $mobantext;
		}else{
			$mobanfilearr = array_merge_recursive($mobanfilearr,array("$filename"=>$mobantext));
		}
		
		$str  = '<?php 
';
		$str .='$mobanfilearr = array(';
		if(count($mobanfilearr)>1){
			$i = 0;
			foreach($mobanfilearr as $k => $v){
				$str .= ($i==0 ? '' : ",")."'$k'=>'$v'";
				$i++;
			}
		}else{
			$str .= "'$filename'=>'$mobantext'";
		}
		$str .= ');';
		$str .='
?>';
		
		$fp = fopen($mobanarrfile,"w") or dir($mobanarrfile." �ļ���ʧ��! ");
		fwrite($fp,trim($str));
		/*�ر��ļ�*/
		fclose($fp);
		
		
		if($fp = fopen($filedir,"w") or dir($filedir."�ļ���ʧ��! ")){
				fwrite($fp,trim($content));
				/*�ر��ļ�*/
				fclose($fp);
				msg('�޸ĳɹ���',"?action=");
		}else{
				msg('�޸�ʧ�ܣ�',"?action=");
		}
		break;
	case 'del':
		$pageinfo['title'] = 'ɾ��ģ��';
		$tpl -> display('header');
		$id = $_POST["id"];
		if(is_array($id)){
			for($i = 0 ; $i < count($id); $i++){
				$file = $mobandir.$id[$i].".php";
				if(file_exists($file)){
					unlink($file);
				}
			}
			msg('ɾ���ɹ�!',"?page=$page");
		}
		break;
	default:
		$filearr = foreach_moban($mobandir);
		$pageinfo['title'] = 'ģ���б�';
		$tpl -> display('header');
		$tpl -> display('moban');
		break;
}


/**********************����Ŀ¼�µ�ȫ��html�ļ�����������****************************/
function foreach_moban($mobandir){
	$mobanfilearr = array();
	$handle=opendir($mobandir); 
	while (false !== ($file = readdir($handle))){//PHP�����ļ����������ļ�
		if ($file != "." && $file != "..") {
			$zfwz = strrpos($file,".");
			$filename = substr($file,0,$zfwz);
			$kzm = substr($file,$zfwz,strlen($file)-$zfwz);
			if($kzm == '.php' && $filename <> 'mobanfilearr'){
				$mobanfilearr[] = $filename; //����ļ���
			}
		}
	}
	closedir($handle); 
	return $mobanfilearr;
}
?> 
</div>
</body>
</html>
