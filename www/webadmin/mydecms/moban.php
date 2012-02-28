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
$mobanarrfile = "../".$mydecms['moban'].'mobanfilearr.php';
if(file_exists($mobanarrfile)) include $mobanarrfile;

$mobandir = "../".$mydecms['moban']; //这里输入其它路径
$filename = str($_GET['filename']);
if($filename == 'mobanfilearr') msg('模板文件不能为 <font color="red">mobanfilearr</font>',"?action=");
$tpl -> assign(array('filearr','mobanfilearr','action','pageinfo','filename','fileinfo','mydecms'));

switch($action){
	case 'add':
		$pageinfo['title'] = '添加模板';
		$pageinfo['subtitle'] = '添加';
		$pageinfo['act']   = 'act_add';
		$tpl -> display('header');
		$tpl -> display('moban_add');
		break;
	case 'edit':
		$pageinfo['title']    = '修改模板';
		$pageinfo['subtitle'] = '修改';
		$pageinfo['act']      = 'act_edit';
		$tpl -> display('header');
		$fileinfo['filename']    = $filename;
		$filedir = $mobandir.$filename.".php";
		if(!file_exists($filedir)){
			exit("模板文件不存在！");
		}

		$fp = fopen($filedir,"r");
		$content = fread($fp,filesize($filedir));//读文件
		fclose($fp);
		$fileinfo['filecontent'] = str_replace("&","&amp;",$content);
		$tpl -> display('moban_add');
		break;
	case 'act_add':
		$pageinfo['title'] = '添加模板';
		$tpl -> display('header');
		$filename  = str_replace("'","",$_POST['filename']);
		$content   = $_POST['content'];
		$mobantext = str_replace(array("'","\\"),"",str($_POST['mobantext']));
		$filedir = $mobandir.$filename.".php";
		if(file_exists($filedir)) msg('<span class="red">'.$filename.'</span>模板文件已经存在，请修改模板名称！',1);
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
		
		
		if($fp = fopen($filedir,"w") or dir($filedir."文件打开失败! ")){
				fwrite($fp,trim($content));
				/*关闭文件*/
				fclose($fp);
				msg('添加成功！',"?action=");
		}else{
				msg('添加失败！',"?action=");
		}
		break;
	case 'act_edit':
		$pageinfo['title'] = '修改模板';
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
		
		$fp = fopen($mobanarrfile,"w") or dir($mobanarrfile." 文件打开失败! ");
		fwrite($fp,trim($str));
		/*关闭文件*/
		fclose($fp);
		
		
		if($fp = fopen($filedir,"w") or dir($filedir."文件打开失败! ")){
				fwrite($fp,trim($content));
				/*关闭文件*/
				fclose($fp);
				msg('修改成功！',"?action=");
		}else{
				msg('修改失败！',"?action=");
		}
		break;
	case 'del':
		$pageinfo['title'] = '删除模板';
		$tpl -> display('header');
		$id = $_POST["id"];
		if(is_array($id)){
			for($i = 0 ; $i < count($id); $i++){
				$file = $mobandir.$id[$i].".php";
				if(file_exists($file)){
					unlink($file);
				}
			}
			msg('删除成功!',"?page=$page");
		}
		break;
	default:
		$filearr = foreach_moban($mobandir);
		$pageinfo['title'] = '模板列表';
		$tpl -> display('header');
		$tpl -> display('moban');
		break;
}


/**********************返回目录下的全部html文件并存入数组****************************/
function foreach_moban($mobandir){
	$mobanfilearr = array();
	$handle=opendir($mobandir); 
	while (false !== ($file = readdir($handle))){//PHP遍历文件夹下所有文件
		if ($file != "." && $file != "..") {
			$zfwz = strrpos($file,".");
			$filename = substr($file,0,$zfwz);
			$kzm = substr($file,$zfwz,strlen($file)-$zfwz);
			if($kzm == '.php' && $filename <> 'mobanfilearr'){
				$mobanfilearr[] = $filename; //输出文件名
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
