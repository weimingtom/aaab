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
	case 'action_setweb':
		$arr = array(
			'webname'           => str($_POST['webname']),
			'weburl'            => str($_POST['weburl']),
			'webtitle'          => str($_POST['webtitle']),
			'webkeywords'       => str($_POST['webkeywords']),
			'webdescription'    => str($_POST['webdescription']),
			'moban'             => str($_POST['moban']),
			'html'              => str($_POST['html']),
			'icp'               => str($_POST['icp']),
			'openspider'        => str($_POST['openspider']),
			'tongji'            => str_replace(array('"',"'"),array('+=+','-+-'),$_POST['tongji']),
			'classid'           => str($_POST['classid']),
		);
			
			if($mysql -> update("-table-setconfig",$arr)){
				$pageinfo['title'] = '修改网站设置成功!';
				$tpl -> display('header');
				msg('修改网站设置成功!','setweb.php');
			}else{
				$tpl -> display('header');
				msg('修改网站设置失败!','setweb.php');
			}
		break;
	case 'pass':
		$pageinfo['title'] = '修改网站管理帐号及密码!';
		$pageinfo['act']   = 'action_pass';
		$tpl -> display('header');
		$tpl -> display('pass');
		break;
	case 'action_pass':
			$old_user = str($_POST['old_user']);
			$new_user = str($_POST['new_user']);
			$sub_user = str($_POST['sub_user']);
			$old_pass = str($_POST['old_pass']);
			$new_pass = str($_POST['new_pass']);
			$sub_pass = str($_POST['sub_pass']);
			$pageinfo['title'] = '修改管理帐号及密码';
			$tpl -> display('header');
			if(isset($old_user)){
				if(md5($new_user) !== md5($sub_user) && md5($new_user) != '') msg('新用户与确认用户不一致!','?action=pass');
				
			}
			if(isset($old_pass)){
				if(md5($new_pass) !== md5($sub_pass) && md5($new_pass) != '') msg('新密码与确认密码不一致!','?action=pass');
			}
			if(md5($old_user) == $mydecms['webadmin'] && md5($old_pass) == $mydecms['webpass']){
				$arr = array(
					'webadmin'    => md5($new_user),
					'webpass'     => md5($new_pass)
				);
				if($mysql -> update("-table-setconfig",$arr)){
					msg('修改成功!','?action=pass');
				}else{
					msg('修改失败!','?action=pass');
				}
			}else{
				msg('旧用户或者旧密码不正确!','?action=pass');
			}
			break;
	case 'moren':
		$pageinfo['title'] = '后台默认页面';
		$tpl -> display('header');
		$tpl -> display('moren');
		break;
	default:
		$pageinfo['title'] = '修改网站设置';
		$pageinfo['act']   = 'action_setweb';
		$mydecms['mobanarr'] = getAllFiles("../template/");
		$tpl -> display('header');
		$tpl -> display('setweb');
		break;
}

$tpl -> display('footer');

function getAllFiles($filedir) {
	$allfiles = array(); //文件名数组
	if (is_dir($filedir)) {//判断要遍历的是否是目录
		if ($dh = opendir($filedir)) {//打开目录并赋值一个目录句柄(directory handle)
			while (FALSE !== ($filestring = readdir($dh))) {//读取目录中的文件名
				if ($filestring != '.' && $filestring != '..' && is_dir($filedir."/".$filestring)) {//如果不是.和..(每个目录下都默认有.和..)
					$mobanfile = str_replace("../","",$filedir . $filestring."/");
					$allfiles[$mobanfile] = $mobanfile; //如果该文件名是一个文件不是目录,直接赋值给文件名数组
				}
			}
		}else{//打开目录失败
			echo('打开模板目录失败');
		}
		closedir($dh);//关闭目录句柄
		return $allfiles;//返回文件名数组
	}else{//目录不存在
		echo('模板目录不存在');
	}
}


?>
