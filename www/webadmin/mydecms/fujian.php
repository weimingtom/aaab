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
//��ȡ����
$action=str($_GET["action"]);
$urlStr = str($_GET["urlstr"]);
$getdir = "../up-file";
$fileName=str_replace("../","",$_GET["filename"]);
$urlStr = $urlStr;

$file=$_GET["file"];


//���ļ����µ��ļ� 
function loadDir($dirName)
{
global $getdir;
	if($handle=openDir($getdir.$dirName))
	{
		echo "<tr><th>�ļ���</th><th width=\"150\">��С</th><th width=\"150\">����޸�ʱ��</th><th width=\"160\">����</th></tr>";
		while(false!==($files=readDir($handle)))
		{
			if($files!="."&&$files!="..")
			{
				$urlStrs=$dirName."/".$files;
				
				if(is_dir($getdir.$dirName."/".$files))
				{
					$cons="<a href=\"?action=open&filename=$urlStrs\">��</a> | <a href='?action=del&urlstr=$urlStrs'>ɾ��</a>";
					$className="dir";
					$fileaTime="";
					$fileSize=getSize($dirName."/".$files);
					echo "<tr><td><a href=\"?action=open&filename=$urlStrs\">$files</a></td><td class=\"text_center\">".$fileSize." bytes</td><td class=\"text_center\">$fileaTime</td><td class=\"text_center\">$cons</td></tr>";
				}
				else 
				{
					$cons="<a href=\"javascript:void(0);\" onClick=\"fillfile('$urlStrs','$files');\">�ƶ�</a> | <a href=\"?action=edit&urlstr=$urlStrs\">�༭</a> | <a href='?action=del&urlstr=$urlStrs'>ɾ��</a>";
					$className="file";
					$fileSize=getSize($getdir.$dirName."/".$files);
					$fileaTime=date("Y-m-d H:i:s",getEditTime($getdir.$dirName."/".$files));
					$ext =  strrchr($files,".");
					if($ext == ".gif" || $ext==".jpg" || $ext==".png"|| $ext==".bmp"){
					$files1 = "<img class=\"img\" src=\"".$getdir.$dirName ."/".$files."\" onload=\"if(this.width>200){this.width=200};if(this.height>300){this.height=300};\" />";
					}else{
						$files1 = $files;
					}
					$arrayfile[]="<tr><td><a href=\"".$getdir.$dirName ."/". $files."\" target=\"_blank\">$files1</a></td><td class=\"text_center\">".$fileSize." bytes</td><td class=\"text_center\">$fileaTime</td><td class=\"text_center\">$cons</td></tr>";
				}
			}
		}
		$arrayfileLen=count($arrayfile);
		for($i=0;$i<$arrayfileLen;$i++) echo $arrayfile[$i];
		closeDir($handle);
	}
}

//ɾ���ļ�
function killFile($filename)
{
	global $getdir;
	$filename = $getdir.$filename;
	if(file_exists($filename))
	{
		if(!is_dir($filename))
		{
			unlink($filename);
			echo "�ɹ�ɾ���ļ�:".$filename;
		}
		else 
		{
			rmdir($filename);
			echo "�ɹ�ɾ��Ŀ¼:".$filename;
		}
	}
	else 
	{
		echo "ָ���ļ����ļ��в�����";
	}
}
//��ȡ�ļ���С
function getSize($a)
{
	if(file_exists($a))
	{
		return fileSize($a);
	}
}

//��ȡ����޸�ʱ��
function getEditTime($a)
{
	if(file_exists($a))
	{
		return filemtime($a);
	}
}

//��ȡ�ļ�
function readFiles($a)
{
	global $getdir;
	$a = $getdir.$a;
	$exts=explode(".",$a);
	$extsNums=count($exts);
	$exts=$exts[$extsNums-1];
	if($exts=="php"||$exts=="asp"||$exts=="txt"||$exts=="html"||$exts=="aspx"||$exts=="jsp"||$exts=="htm")
	{
		$handle=@fOpen($a,"r");
		if($handle)
		{
			echo "<h3>�޸��ļ���$a</h3>";
			echo "<form action='?action=doedit&urlstr=$a' method='post'><textarea style='width:99%;height:300px;margin-left:auto;margin-right:auto;' name='content'>";
			while(!fEof($handle))
			{
				echo mb_convert_encoding(fGets($handle),"gb2312","utf-8,gb2312");
			}
			fClose($handle);
			echo "</textarea><h3><input type='submit' value='Edit' /></h3></form>";
		}
		else 
		{
			echo "�ļ������ڻ򲻿���";
		}
	}
	else 
	{
		echo "���ܱ༭���ļ�";
	}
}

//�޸��ļ�
function editFiles($a)
{
	global $getdir;
	$a = $getdir.$a;
	$contents=get_magic_quotes_gpc()?stripslashes($_POST["content"]):$_POST["content"];
	
	if(is_writeable($a))
	{
		if(!$handle=@fopen($a,"wb"))
		{
			echo "���ܴ��ļ���$a";
			exit;
		}
		if(@fwrite($handle,$contents)===FALSE)
		{
			echo "����д�뵽�ļ���$a";
		}
		else 
		{
			echo "�޸��ļ��ɹ�";
		}
		@fclose($handle);
	}
	else 
	{
		echo "�����޸��ļ�";
	}
}

//�ַ�ת��
function ubb($str)
{
	$str=str_replace("<","&lt;",$str);
	$str=str_replace(">","&gt;",$str);
	return $str;
}

//�ϴ�����
function upLoadFile($a)
{
	global $getdir;
	$a = $getdir.$a;
	if(is_dir($a)||$a==""||$a==".")
	{
		if($_FILES)
		{
			$dest=$a?$a."/":$a;
			$image_type = array('jpg', 'gif', 'bmp', 'jpeg', 'png','doc','txt','rar','zip','xls');
			if (($pos = strrpos($_FILES['upload_file']['name'], '.')) !== false) {
    			$file_ext = strtolower(substr($_FILES['upload_file']['name'], $pos + 1));
			}
	if (in_array($file_ext, $image_type)){
				if(move_uploaded_file($_FILES['upload_file']['tmp_name'],$dest.$_FILES['upload_file']['name']))
				{
					echo "�ϴ��ɹ�";
				}
				else 
				{
					echo "�ϴ�ʧ��";
				}
			}else{
				echo "�ļ���ʽ����,�����ϴ�";
			}
		}
	}
	else 
	{
		echo "����·������һ��Ŀ¼�������ϴ�";
	}
}

//�½�Ŀ¼
function newDir($a)
{
	global $getdir;
	$a = $getdir.$a;
	if(is_dir($a)||$a==""||$a==".")
	{
		$dest=$a?$a."/":$a;
		$dirName=$_POST['dir_name'];
		if(mkdir($dest.$dirName))
		{
			echo "����Ŀ¼�ɹ�";
		}
		else 
		{
			echo "����Ŀ¼ʧ��";
		}
	}
	else 
	{
		echo "����·������һ��Ŀ¼�����ܴ���Ŀ¼";
	}
}

//�ƶ��ļ�
function moveFile()
{
	global $getdir;
	$todirname=$getdir.$_POST['todirname'];
	$fileurlstr=$getdir.$_POST['urlstr'];
	$file=$_POST['file'];
	if(!is_dir($todirname))
	{
		mkdir($todirname);
	}
	if(!is_dir($fileurlstr))
	{
		if(rename($fileurlstr,$todirname."/".$file))
		{
			echo "�ƶ��ļ��ɹ�";
		}
		else 
		{
			echo "�ƶ��ļ�ʧ��";
		}
	}
}

$tpl -> display('header');
$tpl -> display('fujian');
//������
echo '<table style="width:100%;" class="table" border="0" cellpadding="0" cellspacing="1" align="center">';
//�������
switch($action)
{
	case "del":
		killFile($urlStr);
		break;
	case "edit":
		readFiles($urlStr);
		break;
	case "doedit":
		editFiles($urlStr);
		break;
	case "upload":
		upLoadFile($fileName);
		break;
	case "newdir":
		newDir($fileName);
		break;
	case  "move":
		moveFile();
		break;
	default:
		
		loadDir($fileName);
		break;
}
echo '  </table>';
$tpl -> display('footer');
?>