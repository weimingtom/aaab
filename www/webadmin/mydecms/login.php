<?php
session_start();
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
	case "act_login":
		$pageinfo['title']    = "登录贤诚文章管理系统后台";
		$tpl -> display('header');
		if($_POST["webadmin"]== "" || $_POST["webpass"] == "" || $_POST["nzm"] == ""){
			msg("您还没有填写管理帐号或者管理密码或者验证码!",1,1);
		}else{
			if($_POST["nzm"] <> $_SESSION["WWW_MYDECMS_COM_NZM"]){
				msg("验证码错误!",1,1);
			}else{
				if($mydecms['webadmin'] == md5($_POST["webadmin"]) && $mydecms['webpass'] == md5($_POST["webpass"])){
					$_SESSION["MYDECMSADMIN_ADMIN"] = md5($_POST["webpass"]);
					echo "<script type=\"text/javascript\">window.location='index.php';</script>";
					$mysql -> close();
				}else{
					$mysql -> close();
					msg("管理帐号或者管理密码错误!","login.php",1);
				}
			}
		}
		msg('成功失败!','?');
		$tpl -> display('footer');
		break;
	case "out":
		$tpl -> display('header');
		session_unset($_SESSION["WWW_MYDECMS_COM_NZM"]);
		session_destroy();
		msg("退出成功!","../",1);
		$tpl -> display('footer');
		break;
	default:
		$tpl -> display('login');
		break;
}
?>
