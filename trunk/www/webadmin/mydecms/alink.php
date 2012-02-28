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


switch($action){
	case "add":
		$pageinfo['title']    = "添加内链";
		$pageinfo['subtitle'] = "添加";
		$pageinfo['act']      = "act_add";
		$tpl -> display('header');
		$tpl -> display('alink_add');
		break;
	case "act_add":
			$arr = array(
			'name'           => str($_POST['name']),
			'url'            => str($_POST['url']),
			'sort'           => str($_POST['sort']),
			'target'         => str($_POST['target'])
			);
			$pageinfo['title'] = "添加内链";
			$tpl -> display('header');
			if($mysql -> insert("-table-alink",$arr)){
				msg('添加成功!','?action=');
			}else{
				msg('添加失败!','?action=');
			}
			break;
	case "act_edit":
		$id =  numeric($_POST['id']);
		$sql  = "select `id` from `-table-alink` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
				$arr = array(
					'name'           => str($_POST['name']),
					'url'            => str($_POST['url']),
					'sort'           => str($_POST['sort']),
					'target'         => str($_POST['target'])
				);
				$pageinfo['title'] = "修改内链";
				$tpl -> display('header');
				if($mysql -> update("-table-alink",$arr,' id='.$id)){
					msg('修改成功!','?action=');
				}else{
					msg('修改失败!','?action=');
				}
		}else{
			msg('记录不存在!','?action=');
		}	
		break;
	case "edit":
		$id =  numeric($_GET['id']);
		$pageinfo['title'] = "修改内链";
		$pageinfo['subtitle'] = "修改";
		$pageinfo['act']      = "act_edit";
		$tpl -> display('header');
		$sql  = "select * from `-table-alink` where id=".$id;
		$query = $mysql -> query($sql);
		$row = $mysql -> fetch_array($query);
		if($row){
			$tpl -> display('alink_add');
		}else{
			msg('记录不存在!','?action=');
		}
		break;
	case "del":
		$pageinfo['title'] = "删除内链";
		$tpl -> display('header');
		$id = $_POST["id"];
		if(is_array($id)){
			for($i = 0 ; $i < count($id); $i++){
				$sql = "select id from `-table-alink` where id=".numeric($id[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-alink` where id=".numeric($id[$i]));
				}else{
					msg('记录不存在!','?action=');
				}
			}
			msg('删除成功!','?action=');
		}else{
			if(!is_numeric($id)){
				msg("ID参数错误",'?action=');
			}else{
				$sql = "select id from `-table-alink` where id=".$id;
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$mysql -> query("delete from `-table-alink` where id=".numeric($id));
					msg('删除成功!','?action=');
				}else{
					msg('记录不存在!','?action=');
				}
			}
		}
		break;
	case "piliang":
		$idarr    = $_POST["idd"];
		$sort = $_POST["sort"];
		$target = $_POST["target"];
		$pageinfo['title'] = '内链批量修改';
		$tpl -> display('header');
		if(is_array($idarr)){
			for($i = 0 ; $i < count($idarr); $i++){
				$sql = "select `id` from `-table-alink` where id=".numeric($idarr[$i]);
				$query = $mysql -> query($sql);
				$row = $mysql ->fetch_array($query);
				if($row){
					$arr = array('sort'  => $sort[$i],'target'=>$target[$i]);
					$mysql -> update("-table-alink",$arr,' id='.$idarr[$i]);
				}else{
					msg('记录不存在!',"?page=$page");
				}
			}
			msg('批量修改成功!',"?page=$page");
		}
		msg('发生未知错误!',"?page=$page");
		break;
	default:
			$pageinfo['title'] = "内链管理";
			$tpl -> display('header');
			$pages = new PageClass($mysql -> num_rows($mysql -> query("select id  from `-table-alink`")),PAGESIZE,numeric($_GET['page']),'?page={page}');
			$sql  = "select * from `-table-alink` order by sort asc,";
			$sql .= "id Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
			$result = $mysql -> query($sql);
			$tpl -> assign(array('result','mysql','pages','page'));
			$tpl -> display('alink');
			break;
}

$tpl -> display('footer');
?>
