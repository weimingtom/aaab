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
$tpl -> display('header');
$tpl -> display('gotohtml');
function replace($str){
	echo "<script type=\"text/javascript\">return_msg.innerHTML='$str';</script>";
}

switch($action){
	case 'index':
		$url = str_replace("gotohtml.php","../index.php","http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']);
		$art_html = new geturl($url,"../","index.html");
		replace('首页');
		break;
	case 'Classhtml':
		$id = $_POST["id"];
		$class_arr=array();
			$sql = "select * from `-table-dafenglei` order by sort asc, id Desc";
			$query = $mysql -> query($sql);
			while($row = $mysql -> fetch_array($query)){
				$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
			}
		if(is_array($id)){
			$count = count($id);
			for($i = $count ; $i >= 0; $i--){
				$arr_baobei ="";
				dafenglei_baobei($id[$i],$id[$i]);
				 if($arr_baobei<>""){
					$arr_baobei=$id[$i].$arr_baobei;
				 }else{
					$arr_baobei=$id[$i];
				 }
				$sqll  = "select `id`,`type`,`date`,`dafenglei` from `-table-article` where `dafenglei` in (".$arr_baobei.")";
				$sqll .= " order by `id` Desc ";
				$query = $mysql -> query($sqll);
				while($row = $mysql -> fetch_array($query)){
					tohtmlarticle($row['id'],date("Ym",strtotime($row['date'])));
				}
			}
			
		}
		break;
	case 'Articletype':
		$sqll  = "select `id`,`type`,`date` from `-table-article` where `type`=10 or `type`=5 order by `id` Desc ";
		$query = $mysql -> query($sqll);
		$a=0;
		$zs = $mysql -> affected_rows();
		while($row = $mysql -> fetch_array($query)){
			$a++;
			tohtmlarticle($row['id'],date("Ym",strtotime($row['date'])));
			echo "<script type=\"text/javascript\">document.getElementById('bfb').innerHTML='进度:<font color=red>".ceil($a/$zs) * 100 ."%</font>';</script>";
		}
		break;
	case 'Articlehtml':
		$id = $_POST["id"];
		if(is_array($id)){
			$count = count($id);
			$a=0;
			$zs = $count+1;
			for($i = $count; $i >= 0; $i--){
				$a++;
				$sql = "select `id`,`type`,`date` from `-table-article` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					tohtmlarticle($id[$i],date("Ym",strtotime($row['date'])));
				}
				echo "<script type=\"text/javascript\">document.getElementById('bfb').innerHTML='进度:<font color=red>". ceil($a/$zs) * 100 ."%</font>';</script>";
			}
		}
		break;
		
	case 'quyu_html':
		$act = 'quyu_html';
		$title = '将指定区域的文章生成HTML';
		$pageinfo['act'] ='gotohtml.php?action=quyu_html';
		$ks_id = numeric($_POST['ks_id']);
		$js_id = numeric($_POST['js_id']);
		$tpl = new templateClass();
		$tpl -> assign(array('act','title','js_id','pageinfo'));
		$tpl -> display('quyu_html');
		if($ks_id <> "" && $js_id <>""){
			$sql  = "select `id`,`type`,`date` from `-table-article` where `id`>=".$ks_id." and `id`<=".$js_id." order by `id` asc ";
			$query = $mysql -> query($sql);
			$a=0;
			$zs = $mysql -> affected_rows();
			while($row = $mysql -> fetch_array($query)){
				$a++;
				echo "<script type=\"text/javascript\">document.getElementById('bfb').innerHTML='进度:<font color=red>". ceil($a/$zs) * 100 ."%</font>';</script>";
				tohtmlarticle($row['id'],date("Ym",strtotime($row['date'])));
			}
		}else{
			msg("开始ID或者结束ID您没有填写！","article.php?action=html");
		}
		break;
	case 'articleid':
		$id = numeric($_GET['id']);
		$sql = "select `id`,`date` from `-table-article` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql ->fetch_array($query);
		if($row){
			tohtmlarticle($row['id'],date("Ym",strtotime($row['date'])));
		}
		break;
	case 'dafenglei':
		$id = $_POST["id"];
		if(is_array($id)){
			$count = count($id);
			for($i = $count ; $i >= 0; $i--){
				$sql = "select `id`,`name`,`dir` from `-table-dafenglei` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					if($row['dir']<>""){
						$url = str_replace("gotohtml.php","../class.php?id=".$id[$i],"http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']);
						$art_html = new geturl($url,"../".$row['dir']."/","index.html");
						replace('分类页面');
						if(!$art_html -> type()){
							echo $row['name']."===><font color=red>生成失败</font>";
						}
					}
				}
			}
		}
		break;
	case 'dafengleiall':
		$sql = "select `id`,`name`,`dir` from `-table-dafenglei` order by id desc";
		$query = $mysql -> query($sql);
		while($row = $mysql -> fetch_array($query)){
			if($row['dir']<>""){
				$url = str_replace("gotohtml.php","../class.php?id=".$row['id'],"http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']);
				$art_html = new geturl($url,"../".$row['dir']."/","index.html");
				replace('分类页面');
				if(!$art_html -> type()){
					echo $row['name']."===><font color=red>生成失败</font>";
				}
			}
		}
		break;
}

function tohtmlarticle($id,$date){
		global $mydecms;
		$url = str_replace("gotohtml.php","../content.php?id=".$id,"http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']);
		$art_html = new geturl($url,"../".$mydecms['html'].$date."/",$id.".html");
		replace('文章页');
		if($art_html -> type()){
			return true;
		}else{
			return false;
		}
}


function dafenglei_baobei($id,$index)
{	
	global $class_arr;
	global $mysql;
	global $arr_baobei;
	for($i=0;$i<count($class_arr);$i++){
		if($class_arr[$i][2]==$id){
			$arr_baobei .= ",".$class_arr[$i][0];
			dafenglei_baobei($class_arr[$i][0],$index);
			
		}
		
	}
	
}

function createFolder($path){
	
		if(!file_exists($path)){
		
			createFolder(dirname($path));
			
			mkdir($path,0777);
			
		}
		return $path;
	}

$tpl -> display('footer');
?>
