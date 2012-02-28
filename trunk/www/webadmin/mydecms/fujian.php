<?php
include 'session.php';
define('WWW.MYDECMS.COM',true);

/**   
+----------------------程序版权注释信息--------------------
* 程序名称：贤诚官方网站  
* 程序官网：http://www.mydecms.com/
* 程序作者：小贤
* 作者QQ号：306282268
*
* 程序声明：1、贤诚文章管理系统是一套完全免费的PHP开源程序，任何组织或者个人没有经过程序作者的允许，不得以出售其程序来赚取利益。
*           2、请不要删除程序的版权信息（注：网页底部的官方链接及你现在所看到的版权注释信息），请尊重别人的劳力成果。
* 		    3、如果因为程序存在漏洞导致网站被入侵造成的损失，程序作者不需要承担任何责任。如果你发现有漏洞请即时告知作者。
+----------------------------------------------------------   
*/ 


include 'config.inc.php';
//获取参数
$action=str($_GET["action"]);
$urlStr = str($_GET["urlstr"]);
$getdir = "../up-file";
$fileName=str_replace("../","",$_GET["filename"]);
$urlStr = $urlStr;

$file=$_GET["file"];


//读文件夹下的文件 
function loadDir($dirName)
{
global $getdir;
	if($handle=openDir($getdir.$dirName))
	{
		echo "<tr><th>文件名</th><th width=\"150\">大小</th><th width=\"150\">最后修改时间</th><th width=\"160\">操作</th></tr>";
		while(false!==($files=readDir($handle)))
		{
			if($files!="."&&$files!="..")
			{
				$urlStrs=$dirName."/".$files;
				
				if(is_dir($getdir.$dirName."/".$files))
				{
					$cons="<a href=\"?action=open&filename=$urlStrs\">打开</a> | <a href='?action=del&urlstr=$urlStrs'>删除</a>";
					$className="dir";
					$fileaTime="";
					$fileSize=getSize($dirName."/".$files);
					echo "<tr><td><a href=\"?action=open&filename=$urlStrs\">$files</a></td><td class=\"text_center\">".$fileSize." bytes</td><td class=\"text_center\">$fileaTime</td><td class=\"text_center\">$cons</td></tr>";
				}
				else 
				{
					$cons="<a href=\"javascript:void(0);\" onClick=\"fillfile('$urlStrs','$files');\">移动</a> | <a href=\"?action=edit&urlstr=$urlStrs\">编辑</a> | <a href='?action=del&urlstr=$urlStrs'>删除</a>";
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

//删除文件
function killFile($filename)
{
	global $getdir;
	$filename = $getdir.$filename;
	if(file_exists($filename))
	{
		if(!is_dir($filename))
		{
			unlink($filename);
			echo "成功删除文件:".$filename;
		}
		else 
		{
			rmdir($filename);
			echo "成功删除目录:".$filename;
		}
	}
	else 
	{
		echo "指定文件或文件夹不存在";
	}
}
//获取文件大小
function getSize($a)
{
	if(file_exists($a))
	{
		return fileSize($a);
	}
}

//获取最后修改时间
function getEditTime($a)
{
	if(file_exists($a))
	{
		return filemtime($a);
	}
}

//读取文件
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
			echo "<h3>修改文件：$a</h3>";
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
			echo "文件不存在或不可用";
		}
	}
	else 
	{
		echo "不能编辑该文件";
	}
}

//修改文件
function editFiles($a)
{
	global $getdir;
	$a = $getdir.$a;
	$contents=get_magic_quotes_gpc()?stripslashes($_POST["content"]):$_POST["content"];
	
	if(is_writeable($a))
	{
		if(!$handle=@fopen($a,"wb"))
		{
			echo "不能打开文件：$a";
			exit;
		}
		if(@fwrite($handle,$contents)===FALSE)
		{
			echo "不能写入到文件：$a";
		}
		else 
		{
			echo "修改文件成功";
		}
		@fclose($handle);
	}
	else 
	{
		echo "不能修改文件";
	}
}

//字符转换
function ubb($str)
{
	$str=str_replace("<","&lt;",$str);
	$str=str_replace(">","&gt;",$str);
	return $str;
}

//上传处理
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
					echo "上传成功";
				}
				else 
				{
					echo "上传失败";
				}
			}else{
				echo "文件格式不对,不能上传";
			}
		}
	}
	else 
	{
		echo "所在路径不是一个目录，不能上传";
	}
}

//新建目录
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
			echo "创建目录成功";
		}
		else 
		{
			echo "创建目录失败";
		}
	}
	else 
	{
		echo "所在路径不是一个目录，不能创建目录";
	}
}

//移动文件
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
			echo "移动文件成功";
		}
		else 
		{
			echo "移动文件失败";
		}
	}
}

$tpl -> display('header');
$tpl -> display('fujian');
//输出表格
echo '<table style="width:100%;" class="table" border="0" cellpadding="0" cellspacing="1" align="center">';
//具体操作
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