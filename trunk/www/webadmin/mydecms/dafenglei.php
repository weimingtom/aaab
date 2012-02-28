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


$classid=numeric($_GET['classid']);

$tpl -> assign(array('mysql','pages','class_arr','page','classid','dafengle_str','class_arr1','row','pageinfo','action'));

if($classid<>""){
	$geturl = " where `classid`=".$classid;
}else{
	$geturl = " where `classid`=0";
}
	switch($action){
		case "add":
			$pageinfo['title']    = "添加分类";
			$pageinfo['subtitle'] = "添加";
			$pageinfo['act']      = "act_add";
			$tpl -> display('header');
			$class_arr=array();
			$sql = "select * from `-table-dafenglei` order by sort asc, id Desc";
			$query = $mysql -> query($sql);
			while($row = $mysql -> fetch_array($query)){
				$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['type'],$row['dir'],$row['mobanname']);
			}
			$tpl -> display('dafenglei_add');
			break;
		case "act_add":
				$pageinfo['title']    = "添加分类";
				$tpl -> display('header');
				$arr = array(
					'name'           => str($_POST['name']),
					'classid'        => numeric($_POST['classid']),
					'type'           => str($_POST['type']),
					'sort'           => str($_POST['sort']),
					'title'          => str($_POST['title']),
					'keywords'       => str($_POST['keywords']),
					'description'    => str($_POST['description']),
					'dir'            => str($_POST['dir']),
					'mobanname'      => str($_POST['mobanname'])
				);
				$mysql -> insert("-table-dafenglei",$arr);
				msg('添加成功!','?action=&page='.$page."&classid=".numeric($_POST['classid']));
				break;
		case "act_edit":
			$id =  numeric($_POST['id']);
			$pageinfo['title']    = "修改分类";
			$tpl -> display('header');
			if(numeric($_POST['id'])==str($_POST['classid'])) msg('不能自己是自己的分类!','?action=edit&page='.$page.'&id='.$id);
			$sql  = "select `id` from `-table-dafenglei` where id=".$id;
			$query = $mysql -> query($sql);
			$row = $mysql -> fetch_array($query);
			if($row){
					$arr = array(
					'name'           => str($_POST['name']),
					'classid'        => numeric($_POST['classid']),
					'type'           => str($_POST['type']),
					'sort'           => str($_POST['sort']),
					'title'          => str($_POST['title']),
					'keywords'       => str($_POST['keywords']),
					'description'    => str($_POST['description']),
					'dir'            => str($_POST['dir']),
					'mobanname'      => str($_POST['mobanname'])
					);
					$mysql -> update("-table-dafenglei",$arr,' id='.$id);
					msg('修改成功!','?action=&page='.$page.'&classid='.numeric($_POST['classid']));
			}else{
				msg('记录不存在!','?action=&page='.$page.'&classid='.numeric($_POST['classid']));
			}	
			break;
		case "edit":
			$id =  numeric($_GET['id']);
			$pageinfo['title']    = "修改分类";
			$pageinfo['subtitle'] = "修改";
			$pageinfo['act']      = "act_edit";
			$tpl -> display('header');
			$class_arr=array();
			$sql = "select * from `-table-dafenglei` order by sort asc, id Desc";
			$query = $mysql -> query($sql);
			while($row = $mysql -> fetch_array($query)){
				$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['type'],$row['dir'],$row['mobanname']);
			}
			
			$sql  = "select * from `-table-dafenglei` where id=".$id;
			$query = $mysql -> query($sql);
			$row = $mysql -> fetch_array($query);
			if($row){
				$tpl -> display('dafenglei_add');
			}else{
				msg('记录不存在!','?action=&page='.$page."&classid=".$classid);
			}
			break;
		case "del":
			$id = $_POST["id"];
			$pageinfo['title']    = "删除分类";
			$tpl -> display('header');
			if(is_array($id)){
				for($i = 0 ; $i < count($id); $i++){
					$sql = "select `id`,`dir` from `-table-dafenglei` where id=".numeric($id[$i]);
					$query = $mysql -> query($sql);
					$row = $mysql ->fetch_array($query);
					if($row){
						$mysql -> query("delete from `-table-dafenglei` where id=".numeric($id[$i]));
					}else{
						msg('记录不存在!','?action=&page='.$page."&classid=".$classid);
					}
				}
				msg('删除成功!','?action=&page='.$page."&classid=".$classid);
			}else{
				if(!is_numeric($id)){
					msg("ID参数错误",'?action=&page='.$page."&classid=".$classid);
				}else{
					$sql = "select `id`,`dir` from `-table-dafenglei` where id=".$id;
					$query = $mysql -> query($sql);
					$row = $mysql ->fetch_array($query);
					if($row){
						$mysql -> query("delete from `-table-dafenglei` where id=".numeric($id));
						msg('删除成功!','?action=&page='.$page."&classid=".$classid);
					}else{
						msg('记录不存在!','?action=&page='.$page."&classid=".$classid);
					}
				}
			}
			break;
		default:
				$pageinfo['title']    = "分类列表";
				$pages = new PageClass($mysql -> num_rows($mysql -> query("select id  from `-table-dafenglei`".$geturl)),30,numeric($_GET['page']),'?page={page}');

				$class_arr=array();
				$sql = "select * from `-table-dafenglei`".$geturl." order by sort asc, id Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
				$query = $mysql -> query($sql);
				while($row = $mysql -> fetch_array($query)){
					$class_arr[] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['type'],$row['dir'],$row['mobanname']);
				}
				
				$class_arr1=array();
				$sql = "select * from `-table-dafenglei`";
				$query = $mysql -> query($sql);
				while($row = $mysql -> fetch_array($query)){
					$class_arr1[] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['type'],$row['dir'],$row['mobanname']);
				}
				$tpl -> display('header');
				$tpl -> display('dafenglei');
				break;
	}
	
function dafenglei_arr($m,$id)
{
	global $class_arr;
	global $page;
	global $classid;
	global $arr_baobei;
	global $mysql;
	if($id=="") $id=0;
	$n = str_pad('',$m,'-',STR_PAD_RIGHT);
	$n = str_replace("-","&nbsp;&nbsp;",$n);
	for($i=0;$i<count($class_arr);$i++){
		if($class_arr[$i][2]==$id){
			$arr_baobei="";
			dafenglei_baobei($class_arr[$i][0],$class_arr[$i][0]);
			$arr_baobei = $class_arr[$i][0].$arr_baobei;
		echo "<tr onmouseover=\"this.style.backgroundColor='#EEF6FB'\" onmouseout=\"this.style.backgroundColor=''\">\n";
		echo "      <td><div align=\"center\"><input type=\"checkbox\" name=\"id[]\" id=\"id[]\" value=\"".$class_arr[$i][0]."\">\n";
		echo "        </div></td>\n";
		echo "	  <td>".$n."|--<a href=\"?page=$page&classid=".$class_arr[$i][0]."\">".$class_arr[$i][1]."</a></td>\n";
		echo "	  <td><div align=\"center\">";
		echo $class_arr[$i][5];
		echo "</div></td>\n";
		echo "	  <td><div align=\"center\">".$class_arr[$i][6]."</div></td>\n";
		echo "	  <td><div align=\"center\">";
		if($class_arr[$i][4] == '5'){
			echo '显示';
		}else{
			echo '<span class="red">不显示</span>';
		}
		echo "</div></td>\n";
		echo "	  <td><div align=\"center\">".$mysql -> num_rows($mysql -> query("select `id`,`classid`  from `-table-dafenglei` where `classid` in (".$arr_baobei.")"))."</div></td>\n";
		echo "	  <td><div align=\"center\">".$class_arr[$i][3]."</div></td>\n";
		echo "	  <td><div align=\"center\"><a href=\"?action=edit&amp;id=".$class_arr[$i][0]."&classid=".$class_arr[$i][0]."\">修改</a></div></td>\n";
		echo "	</tr>\n";		
			dafenglei_arr($m+1,$class_arr[$i][0]);
		}
		
	}
	
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


function dafenglei_location($id,$index)
{	
	global $class_arr1;
	global $dafengle_str;
	for($i=0;$i<count($class_arr1);$i++){
		if($class_arr1[$i][0]==$id){
			if($class_arr1[$i][0]==$index){
			$dafengle_str = " → ".$class_arr1[$i][1].$dafengle_str;
			}else{
			$dafengle_str = " → <a href=\"?classid=".$class_arr1[$i][0]."\">".$class_arr1[$i][1]."</a>".$dafengle_str;
			}
			dafenglei_location($class_arr1[$i][2],$index);
			
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
