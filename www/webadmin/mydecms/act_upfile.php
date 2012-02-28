<?php
include 'session.php';
define('WWW.MYDECMS.COM',true);
include 'config.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>上传图片</title>
<style type="text/css">
*{
padding:0;
margin:0;}
body{
	font-size:12px;
}
</style>
<script type="text/javascript">
var isIE = (document.all && window.ActiveXObject && !window.opera) ? true : false;
</script>
</head>

<body>
<?php
if(!isset($_FILES['upload_file']) || trim($_FILES['upload_file']['name']) =="") exit('请不要非法操作！</body>
</html>');
$url = $_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
$urlarr = explode("/",$url);
$c = count($urlarr);
if($c>2){
	$str = $urlarr[$c-2]."/".$urlarr[$c-1];
}
$url = str_replace($str,"","http://".$url);
$a = "../up-file/".date("Ymd");
upLoadFile($url,$a);
function upLoadFile($url,$a)
{
	$url .= str_replace("../","",$a);
	createFolder($a);
	if(is_dir($a)||$a==""||$a==".")
	{
		if($_FILES)
		{
			$dest=$a?$a."/":$a;
			$image_type = array('jpg', 'gif', 'bmp', 'jpeg', 'png','rar','swf','mp3','wma','wmv','doc','xls','wav','rmvb','rm','css','xml','js');
			if (($pos = strrpos($_FILES['upload_file']['name'], '.')) !== false) {
    			$file_ext = strtolower(substr($_FILES['upload_file']['name'], $pos + 1));
			}
	if (in_array($file_ext, $image_type)){
				if(move_uploaded_file($_FILES['upload_file']['tmp_name'],$dest.$_FILES['upload_file']['name']))
				{
					switch($_POST['lx']){
						case '1':
							echo "<script type=\"text/javascript\">\n";
							echo "if(isIE){";
							echo "parent.".$_POST['form_id'].".".$_POST['input_id'].".value='".$url."/".$_FILES['upload_file']['name']."';\n";
							echo "}else{";
							echo "window.parent.document.getElementById(\"".$_POST['input_id']."\").value='".$url."/".$_FILES['upload_file']['name']."';\n";
							echo "}";
							echo "</script>\n";
							break;
						case 'content':
							echo "<script type=\"text/javascript\">\n";
							switch($file_ext){
								case ($file_ext == 'jpg' or $file_ext == 'gif' or $file_ext == 'bmp' or $file_ext == 'jpeg' or $file_ext == 'png'):
								echo "if(isIE){";
									echo "parent.HtmlEditor.document.body.innerHTML+='<img src=\"".$url."/".$_FILES['upload_file']['name']."\" />';\n";
								echo "\n}else{\n";
									echo "parent.document.getElementById(\"HtmlEditor\").contentWindow.document.body.innerHTML+='<img src=\"".$url."/".$_FILES['upload_file']['name']."\" />';\n";
								echo "}";
									break;
								case 'swf':
									echo "if(isIE){";
									echo 'parent.HtmlEditor.document.body.innerHTML+=\'<embed src="'.$url."/".$_FILES['upload_file']['name'].'"  type="application/x-shockwave-flash" allowfullscreen="true" loop="true" play="true" menu="false" quality="high" wmode="opaque" classid="clsid:d27cdb6e-ae6d-11cf-96b8-4445535400000" />\';';
									echo "\n}else{\n";
									echo 'parent.document.getElementById("HtmlEditor").contentWindow.document.body.innerHTML+=\'<embed src="'.$url."/".$_FILES['upload_file']['name'].'"  type="application/x-shockwave-flash" allowfullscreen="true" loop="true" play="true" menu="false" quality="high" wmode="opaque" classid="clsid:d27cdb6e-ae6d-11cf-96b8-4445535400000" style="width:80%;height:200px;" />\';';
									echo "}";
									break;
								case ($file_ext == 'mp3' or $file_ext =='wma' or $file_ext =='wmv' or $file_ext =='wav' or $file_ext =='rmvb' or $file_ext =='rm'):
									echo "if(isIE){";
									echo 'parent.HtmlEditor.document.body.innerHTML+=\'<embed src="'.$url."/".$_FILES['upload_file']['name'].'" type="application/x-mplayer2" autostart="false" enablecontextmenu="false" classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" />\';';
									echo "\n}else{\n";
									echo "parent.document.getElementById(\"HtmlEditor\").contentWindow.document.body.innerHTML+='<embed src=\"".$url."/".$_FILES['upload_file']['name']."\" type=\"application/x-mplayer2\" autostart=\"false\" enablecontextmenu=\"false\" classid=\"clsid:6bf52a52-394a-11d3-b153-00c04f79faa6\" style=\"width:80%;height:200px;\" />';";
									echo "}";
									break;
								default:
									echo "if(isIE){";
									echo "parent.HtmlEditor.document.body.innerHTML+='".$url."/".$_FILES['upload_file']['name']."';\n";
									echo "}else{";
									echo "parent.document.getElementById(\"HtmlEditor\").contentWindow.document.body.innerHTML+='".$url."/".$_FILES['upload_file']['name']."';\n";
									echo "}";
									break;
							}
							echo "</script>\n";
							break;
					
					}
					
					echo "上传成功,<a href=\"upfile.php?lx=".$_POST['lx']."&input_id=".$_POST['input_id']."&iframe_id=".$_POST['iframe_id']."\">返回继续上传!</a>";
					echo "<input type=\"text\" value=\"".$url."/".$_FILES['upload_file']['name']."\" onclick=\"oCopy(this)\" />";
					echo "<script type=\"text/javascript\">\n"; 
					echo "function oCopy(obj){ \n"; 
					echo "obj.select();\n";  
					echo "js=obj.createTextRange();\n";  
					echo "js.execCommand(\"Copy\")\n"; 
					echo "window.status=\"复制成功!\";\n";          
					echo "setTimeout(\"window.status=''\",1780);\n"; 
					echo "}\n";  
					echo "</script>\n";  
				}
				else 
				{
					echo "上传失败,<a href=\"upfile.php?lx=".$_POST['lx']."&input_id=".$_POST['input_id']."\">返回重新上传!</a>";
				}
			}else{
				echo "文件格式不对,不能上传";
				echo ", <a href=\"upfile.php?lx=".$_POST['lx']."&input_id=".$_POST['input_id']."\">返回重新上传!</a>";
			}
		}
	}
	else 
	{
		echo "所在路径不是一个目录，不能上传";
	}
}

function createFolder($path){
	if(!file_exists($path)){
		createFolder(dirname($path));
		mkdir($path,0777);
	}
}

?>
</body>
</html>
<?php
unset($mysql);
?>
