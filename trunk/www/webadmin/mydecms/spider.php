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

$pageinfo['title'] = "蜘蛛爬行记录";
$tpl -> display('header');

switch($action){
		case "del":
			$id = $_POST["id"];
			if(is_array($id)){
				for($i = 0 ; $i < count($id); $i++){
					$sql = "select id from `-table-spider` where id=".numeric($id[$i]);
					$query = $mysql -> query($sql);
					$row = $mysql ->fetch_array($query);
					if($row){
						$mysql -> query("delete from `-table-spider` where id=".numeric($id[$i]));
					}else{
						msg('记录不存在!','?action=');
					}
				}
				msg('删除成功!','?action=');
			}else{
				if(!is_numeric($id)){
					msg("ID参数错误",'?action=');
				}else{
					$sql = "select id from `-table-spider` where id=".$id;
					$query = $mysql -> query($sql);
					$row = $mysql ->fetch_array($query);
					if($row){
						$mysql -> query("delete from `-table-spider` where id=".numeric($id));
						msg('删除成功!','?action=');
					}else{
						msg('记录不存在!','?action=');
					}
				}
			}
			break;
		case 'qingkong':
			$sql ="truncate table `-table-spider`";
			if($query = $mysql -> query($sql)){
				msg('清空成功!','?action=');
			}else{
				msg('清空失败!','?action=');
			}
			break;
		case '':
			$zhizhu = $_GET['zhizhu'];
			if($zhizhu<>'') $where = " where `spidername`='".$zhizhu."' ";
				$pages = new PageClass($mysql -> num_rows($mysql -> query("select id  from `-table-spider`".$where)),PAGESIZE,$page,'?zhizhu='.$zhizhu.'&page={page}');
				$sql  = "select `id`,`spidername`,`url`,`date`,`ip` from `-table-spider`".$where." order by ";
				$sql .= "id Desc limit ".$pages -> page_limit.",".$pages -> myde_size;
				$result = $mysql -> query($sql);
				$tpl -> assign(array('result','mysql','pages'));
				$tpl -> display('spider');
				break;
	}
	
$tpl -> display('footer');

?>
