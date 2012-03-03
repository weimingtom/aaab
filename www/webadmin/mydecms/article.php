<?php
include 'session.php';
define('WWW.MYDECMS.COM',true);
include 'config.inc.php';

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


$classid=numeric($_GET['classid']);
$key  = glsql($_GET['key']);
$type = glsql($_GET['type']);
$tpl -> assign(array('result','mysql','pages','page','class_arr','pageinfo','mydecms','key','type','classid','row','action'));
switch($action){
	case "add":
		$pageinfo['title']    = "添加文章";
		$pageinfo['subtitle'] = "添加";
		$pageinfo['act']      = "act_add";
		$class_arr=array();
		$sql = "select * from `-table-dafenglei` order by sort asc, id Desc";
		$query = $mysql -> query($sql);
		while($row = $mysql -> fetch_array($query)){
			$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
		}
		$tpl -> display('header');
		$tpl -> display('article_add');
		break;
	case "act_add":
			$pageinfo['title'] = '添加文章';
			$pageinfo['act']   = 'act_add';
			$pageinfo['subtitle'] = "添加";
			$tpl -> display('header');
			$arr = array(
				'title'         => str($_POST['title']),
				'url'         	=> str($_POST['url']),
				'keywords'      => str($_POST['keywords']),
				'description'   => str($_POST['description']),
				'type'          => numeric($_POST['type']),
				'content'       => str($_POST['content']),
				'date'          => date("Y-m-d H:i:s"),
				'editdate'      => date("Y-m-d H:i:s"),
				'dafenglei'     => numeric($_POST['dafenglei']),
				'count'         => '1',
				'tag'           => str($_POST['tag']),
				'top'           => numeric($_POST['top']),
				'img'           => str($_POST['img']),
				'sort'          => numeric($_POST['sort'])
			);
			if($mysql -> insert("-table-article",$arr)){
				msg('添加成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
			}else{
				msg('添加失败!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
			}
			break;
	case "act_edit":
		$id =  numeric($_POST['id']);
		$pageinfo['title'] = '修改文章';
		$tpl -> display('header');
		$sql  = "select `id` from `-table-article` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
			$arr = array(
				'title'         => str($_POST['title']),
				'url'         	=> str($_POST['url']),
				'keywords'      => str($_POST['keywords']),
				'description'   => str($_POST['description']),
				'type'          => numeric($_POST['type']),
				'content'       => str($_POST['content']),
				'editdate'      => str($_POST['editdate']),
				'dafenglei'     => str($_POST['dafenglei']),
				'tag'           => str($_POST['tag']),
				'top'           => numeric($_POST['top']),
				'img'           => str($_POST['img']),
				'sort'          => numeric($_POST['sort'])
			);
				if($mysql -> update("-table-article",$arr,' id='.$id)){
					msg('修改成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
				}else{
					msg('修改失败!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
				}
		}else{
			msg('记录不存在!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		}	
		break;
	case "edit":
		$id =  numeric($_GET['id']);
		$pageinfo['title'] = '修改文章';
		$pageinfo['act']   = 'act_edit';
		$pageinfo['subtitle'] = "修改";
		$tpl -> display('header');
		$class_arr=array();
		$sql = "select * from `-table-dafenglei` order by sort asc, id Desc";
		$query = $mysql -> query($sql);
		while($row = $mysql -> fetch_array($query)){
			$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
		}
		$sql  = "select * from `-table-article` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){

			$tpl -> display('article_add');
		}else{
			msg('记录不存在!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		}
		break;
	case "del":
		$id = $_POST["id"];
		$pageinfo['title'] = '删除文章';
		$tpl -> display('header');
		if(is_array($id)){
			for($i = 0 ; $i < count($id); $i++){
				$sql = "select id from `-table-article` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-article` where id=".numeric($id[$i]));
				}else{
					msg('记录不存在!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
				}
			}
			msg('删除成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		}else{
			if(!is_numeric($id)){
				msg("ID参数错误","?page=$page&classid=".$classid."&type=".$type."&key=".$key);
			}else{
				$sql = "select id from `-table-article` where id=".$id;
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-article` where id=".numeric($id));
					msg('删除成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
				}else{
					msg('记录不存在!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
				}
			}
		}
		break;
	case 'qingkong':
		$pageinfo['title'] = '清空文章';
		$tpl -> display('header');
		$sql ="TRUNCATE TABLE `-table-article`";
		if($query = $mysql -> query($sql)){
			msg('清空成功!',"?page=1&classid=".$classid."&type=".$type."&key=".$key);
		}else{
			msg('清空失败!',"?page=1&classid=".$classid."&type=".$type."&key=".$key);
		}
		break;
	case 'article'://修改所选文章属性
	$id = $_POST["id"];
	$pageinfo['title'] = '将所选文章属性设置为已生成';
	$tpl -> display('header');
	if(is_array($id)){
		$count = count($id);
		for($i = 0 ; $i < $count; $i++){
			$sql = "select `id` from `-table-article` where id=".numeric($id[$i]);
			$query = $mysql -> query($sql);
			$row = $mysql ->fetch_array($query);
			if($row){
				$mysql -> query("update `-table-article` set `type`=5 where `id`=".$id[$i]);
			}
		}
		msg('设置成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
	}
	break;
	case 'articleto'://修改所选文章属性2
		$id = $_POST["id"];
		$pageinfo['title'] = '将所选文章属性设置为未生成';
		$tpl -> display('header');
		if(is_array($id)){
			$count = count($id);
			for($i = 0 ; $i < $count; $i++){
				$sql = "select `id` from `-table-article` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("update `-table-article` set `type`=1 where `id`=".$id[$i]);
				}
			}
			msg('设置成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		}
		break;
	case 'articleall'://修改所有文章属性
		$pageinfo['title'] = '将全部文章设置为已生成';
		$tpl -> display('header');
		$sql = "select `id` from `-table-article` order by id Desc";
		$query = $mysql -> query($sql);
		$count = $mysql -> affected_rows();
		while($row = $mysql ->fetch_array($query)){
					$mysql -> query("update `-table-article` set `type`=5 where `id`=".$row['id']);
		}
		msg('设置成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		break;
	case "quyu_html":
		$pageinfo['title'] = '将指定区域的文章生成HTML';
		$pageinfo['act'] ='gotohtml.php?action=quyu_html';
		$tpl -> display('header');
		$tpl -> display('quyu_html');
		break;
	case "piliang":
		$idarr    = $_POST["idd"];
		$dafengleiarr = $_POST["dafenglei"];
		$typearr = $_POST["type"];
		$toparr  = $_POST["top"];
		$tagarr  = $_POST["tag"];
		$sort    = $_POST["sort"];
		$pageinfo['title'] = '文章批量修改';
		$tpl -> display('header');
		if(is_array($idarr)){
			for($i = 0 ; $i < count($idarr); $i++){
				$sql = "select `id` from `-table-article` where id=".numeric($idarr[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$arr = array(
						'dafenglei'  => $dafengleiarr[$i],
						'tag'        => $tagarr[$i],
						'top'        => $toparr[$i],
						'type'       => $typearr[$i],
						'sort'       => $sort[$i]
					);
					$mysql -> update("-table-article",$arr,' id='.$idarr[$i]);
				}else{
					msg('记录不存在!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
				}
			}
			msg('批量修改成功!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		}
		msg('发生未知错误!',"?page=$page&classid=".$classid."&type=".$type."&key=".$key);
		break;
	case '':
			$class_arr=array();
			$sql = "select * from `-table-dafenglei` order by sort asc, id Desc";
			$query = $mysql -> query($sql);
			while($row = $mysql -> fetch_array($query)){
				$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort']);
			}
			if($classid==""){
				$sql_class =" where `dafenglei`>=0";
			}else{
				$sql_class =" where `dafenglei`=".$classid;
			}
			
			if($key<>"") $sql_class .=" and `title` like '%".$key."%'";
			
			if($type<>"") $sql_class .=" and `type`=".$type;
			
			$pages = new PageClass($mysql -> num_rows($mysql -> query("select `id`,`type`,`dafenglei`,`title`  from `-table-article`".$sql_class)),PAGESIZE,numeric($page),'?page={page}&classid='.$classid.'&type='.$type."&key=".$key);
			$sql  = "select * from `-table-article`".$sql_class." order by ";
			$sql .= " `id` Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
			$result = $mysql -> query($sql);
			$pageinfo['title'] = '文章列表';
			$tpl -> display('header');
			$tpl -> display('article');
			break;
}

function dafenglei_select($m,$id,$index)
{	
	global $class_arr;
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	for($i=0;$i<count($class_arr);$i++){
	
		if($class_arr[$i][2]==$id){
			if($class_arr[$i][0]==$index){
				echo "        <option value=\"".$class_arr[$i][0]."\" selected=\"selected\">".$n."|--".$class_arr[$i][1]."</option>\n";
			}else{
				echo "        <option value=\"".$class_arr[$i][0]."\">".$n."|--".$class_arr[$i][1]."</option>\n";
			}
			dafenglei_select($m+1,$class_arr[$i][0],$index);
			
		}
		
	}
	
}


function seleted($arr,$value){
	$str = "";
	if(is_array($arr)){
		foreach($arr as $k => $v){
			if($k == $value && $value <> ""){
				$str .= "  <option value=\"$k\" selected=\"selected\">$v</option>\n";
			}else{
				$str .= "  <option value=\"$k\">$v</option>\n";
			}
		}
	}
	echo $str;
}

function echoseleted($arr,$value){
	if(is_array($arr)){
		foreach($arr as $k => $v){
			if($k == $value && $value <> ""){
				$str = "  $v";
			}
		}
	}
	echo $str;
}

$tpl -> display('footer');
?>
