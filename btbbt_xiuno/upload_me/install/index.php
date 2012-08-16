<?php

/*
 * Copyright (C) xiuno.com
 */

// 调试模式: 1 打开，0 关闭
define('DEBUG', 0);

include './function.php';

// 站点根目录
define('BBS_PATH', xn_realpath(substr(__FILE__, 0, -9).'../'));

// 框架的物理路径
$conf = include BBS_PATH.'conf/conf.php';
if(empty($conf)) {
	message('<h3>读取配置文件失败，请检查配置文件是否存在并且有可读权限：'.BBS_PATH.'conf/conf.php'.'</h3>');
}

// PHP 版本判断
if(version_compare(PHP_VERSION, '5.0.0') == -1) {
	message('<h3>非常抱歉，您的PHP版本太低 ('.PHP_VERSION.')，达不到最低安装要求 (5.0.0)</h3>');
}

if(!DEBUG && is_file($conf['upload_path'].'install.lock')) {
	message('已经安装过，如果需要重新安装，请删除 upload/install.lock 文件。');
}

$step = isset($_GET['step']) ? $_GET['step'] : '';

if(empty($step) || $step == 'checklicense') {
	include './header.inc.php';	
	include './license.inc.php';	
	include './footer.inc.php';
	exit;
} elseif($step == 'checkenv') {
	$env = $write = array();
	get_env($env, $write);
	include './header.inc.php';	
	include './check_env.inc.php';	
	include './footer.inc.php';
	exit;
} elseif($step == 'checkdb') {
	
	define('FRAMEWORK_PATH', BBS_PATH.'framework2.3/');
	define('FRAMEWORK_TMP_PATH', $conf['tmp_path']);
	define('FRAMEWORK_LOG_PATH', $conf['log_path']);
	include FRAMEWORK_PATH.'core.php';
	core::init();
	core::ob_start();
	
	// 检测 mysql_connnect, mongodb, pdo 扩展情况
	$mysql_support = function_exists('mysql_connect');
	$mongodb_support = extension_loaded('Mongo');
	$pdo_support = extension_loaded('PDO');
	
	$type = core::gpc('type', 'G');
	empty($type) && $type = 'mysql';
	
	if(core::gpc('FORM_HASH', 'P')) {
		$host = core::gpc('host', 'P');
		$user = core::gpc('user', 'P');
		$pass = core::gpc('pass', 'P');
		$name = core::gpc('name', 'P');
		$tablepre = core::gpc('tablepre', 'P');
		$error = '';
		if($type == 'mysql') {
			$link = @mysql_connect($host, $user, $pass, TRUE);
			if(!$link) {
				$error = 'MySQL 账号密码可能有误：<span class="small">'.mysql_error().'</span>';
			} else {
				$r = mysql_select_db($name);
				if(mysql_errno() == 1049) {
					mysql_query("CREATE DATABASE $name");
					$r = mysql_select_db($name);
				}
				if(!$r) {
					$error = 'MySQL 账户权限可能受限：<span class="small">'.mysql_error().mysql_errno().'</span>';
				} else {
					$c = array(
						// 主 MySQL Server
						'master' => array (
								'host' => $host,
								'user' => $user,
								'password' => $pass,
								'name' => $name,
								'charset' => 'utf8',
								'tablepre' => $tablepre,
								'engine'=>'MyISAM',
						),
						// 从 MySQL Server
						'slaves' => array (
						)
					);
					$db = new db_mysql($c);
					
					$s = file_get_contents(BBS_PATH.'install/install_mysql.sql');
					
					$s = str_replace("\r\n", "\n", $s);
					$s = preg_replace('#\n\#[^\n]*?\n#is', "\n", $s);	// 去掉注释行
					$sqlarr = explode(";\n", $s);
					
					foreach($sqlarr as $sql) {
						if(trim($sql)) {
							$sql = str_replace('bbs_', $tablepre, $sql);
							try {
								$db->query($sql);
							} catch (Exception $e) {
								$error = $e->getMessage();
								break;
							}
						}
					}
					
					$db->truncate('framework_count');
					$db->truncate('framework_maxid');
					
				}
			}
		} elseif($type == 'mongodb') {
			include './install_mongodb.php';
			
			$c = array(
				'master' => array (
					'host' => $host,
					'user' => $user,
					'password' => $pass,
					'name' => $name,
					'charset' => 'utf8',
				),
				'slaves' => array (
				)
			);
			
			// 主要是建立索引
			try {
				$db = new db_mongodb($c);
			} catch(Exception $e) {
				$error = $e->getMessage();
			}
			if(!$error) {
				
				// 清空表
				foreach($db_index as $table=>$indexes) {
					$db->truncate($table);
				}
				
				// 插入初始化数据
				foreach($db_data as $table=>$arrlist) {
					$db->truncate($table);
					$primarykey = $db_index[$table][0];
					foreach($arrlist as $arr) {
						$key = get_key_add($primarykey, $arr);
						$keystring = $table.$key;
						$db->set($keystring, $arr);
					}
				}
				
				// 主要是建立索引
				foreach($db_index as $table=>$indexes) {
					foreach($indexes as $index) {
						$db->index_create($table, $index);
					}
				}
				
				$db->truncate('framework_count');
				$db->truncate('framework_maxid');
			}
		} elseif($type == 'pdo') {
			
			/*
			$link = mysql_connect($host, $user, $pass, TRUE);
			if(!$link) {
				$error = mysql_error($link);
			}
			*/
		}
		
		if(!$error) {
			
			// 预设 count maxid
			$db->count('group', 16);
			$db->maxid('group-groupid', 15);
			$db->count('user', 2);
			$db->maxid('user-uid', 2);
			$db->count('forum', 3);
			$db->maxid('forum-fid', 3);
			$db->count('digestcate', 6);
			$db->maxid('digestcate-cateid', 6);
			$db->truncate('kv');
			$db->truncate('runtime');
			
			// db 写入配置文件
			$configfile = BBS_PATH.'conf/conf.php';
			$replacearr = array('user'=>$user, 'host'=>$host, 'password'=>$pass, 'name'=>$name, 'tablepre'=>$tablepre);
			if($type == 'mysql') {
				file_line_replace($configfile, 18, 30, $replacearr);
			} elseif($type == 'pdo') {
				file_line_replace($configfile, 31, 43, $replacearr);
			} elseif($type == 'mongodb') {
				file_line_replace($configfile, 44, 54, $replacearr);
			}
			$typearr = array('type'=>$type);
			file_line_replace($configfile, 17, 18, $typearr);
			
			$url = misc::get_url_path();
			$public_key = md5(rand(1, 10000000).$_SERVER['ip']);
			$s = file_get_contents($configfile);
			$appurl = substr($url, 0, -8); // 带 /
			$s = preg_replace('#\'app_url\'\s*=\>\s*\'?.*?\'?,#is', "'app_url' => '$appurl',", $s);
			$s = preg_replace('#\'static_url\'\s*=\>\s*\'?.*?\'?,#is', "'static_url' => '$appurl',", $s);
			$s = preg_replace('#\'upload_url\'\s*=\>\s*\'?.*?\'?,#is', "'upload_url' => '{$appurl}upload/',", $s);
			$s = preg_replace('#\'click_server\'\s*=\>\s*\'?.*?\'?,#is', "'click_server' => '{$appurl}clickd/',", $s);
			$s = preg_replace('#\'public_key\'\s*=\>\s*\'?.*?\'?,#is', "'public_key' => '$public_key',", $s);
			$s = preg_replace('#\'installed\'\s*=\>\s*\'?.*?\'?,#is', "'installed' => 1,", $s);
			file_put_contents($configfile, $s);
			
			// 
			// 生成 public_key 写入文件
			
			!is_dir($conf['upload_path'].'forum') && mkdir($conf['upload_path'].'forum', 0777);
			!is_dir($conf['upload_path'].'avatar') && mkdir($conf['upload_path'].'avatar', 0777);
			!is_dir($conf['upload_path'].'friendlink') && mkdir($conf['upload_path'].'friendlink', 0777);
			!is_dir($conf['upload_path'].'attach') && mkdir($conf['upload_path'].'attach', 0777);
			
			// 清理 cache
			clear_cache($conf['tmp_path'], 'tidcache_');
			clear_cache($conf['tmp_path'], 'tpl_');
			clear_cache($conf['tmp_path'], 'forum_');
			clear_cache($conf['tmp_path'], 'forumlist');
			clear_cache($conf['tmp_path'], 'miscarr_');
			clear_cache($conf['tmp_path'], 'group_');
			clear_cache($conf['tmp_path'], 'grouplist_');
			clear_cache($conf['tmp_path'], 'friendlink_');
			
			is_file($conf['tmp_path'].'new_post_tids.txt') && unlink($conf['tmp_path'].'new_post_tids.txt');
			is_file($conf['tmp_path'].'four_column_cache.js') && unlink($conf['tmp_path'].'four_column_cache.js');
			
			copy('./digestcate.js', $conf['upload_path'].'digestcate.js');
			
			$clickserverfile = $conf['upload_path'].'click_server.data';
			$fp = fopen($clickserverfile, 'wb');
			fseek($fp, 4000000);
			fwrite($fp, pack("L*", 0x00), 4);
			fclose($fp);
		}
	}
	
	$master = $conf['db'][$type]['master'];
	include './header.inc.php';	
	include './check_db.inc.php';	
	include './footer.inc.php';
	exit;
	
// 选择插件
} elseif($step == 'plugin') {
	
	define('FRAMEWORK_PATH', BBS_PATH.'framework2.3/');
	define('FRAMEWORK_TMP_PATH', $conf['tmp_path']);
	define('FRAMEWORK_LOG_PATH', $conf['log_path']);
	include FRAMEWORK_PATH.'core.php';
	core::init();
	
	// plugins

	if(core::gpc('formsubmit', 'P')) {
		
		$mconf = new mconf();
		
		// 关闭所有插件
		$pluginpaths = core::get_paths($conf['plugin_path'], TRUE);
		foreach($pluginpaths as $path) {
			$conffile = $path."/conf.php";
			$mconf->set_to('enable', 0, $conffile);
			$mconf->set_to('installed', 0, $conffile);
			$mconf->save($conffile);
		}
		
		// 开启提交的插件
		$plugindir = (array)core::gpc('plugindir', 'P');
		$style = core::gpc('style', 'P');
		
		if($style == 'blue') {
			$plugindir[] = 'view_blue';
		} elseif($style == 'width_screen') {
			$plugindir[] = 'view_width_screen';
		}
		foreach($plugindir as $dir) {
			
			// 安装插件 --------------> start
			$is_view = substr($dir, 0, 4) == 'view';
			
			// 设置 installed 标记
			$conffile = $conf['plugin_path'].$dir.'/conf.php';
			if(!xn_writable($conffile)) {
				message("文件 $conffile 不可写，请设置文件权限为可写：".$conffile);
			}
			$mconf = new mconf();
			if(is_file($conffile)) {
				$mconf->set_to('enable', 1, $conffile);
				$mconf->set_to('installed', 1, $conffile);
				$mconf->save($conffile);
			}
			
			if($is_view) {
				// 如果为风格插件，则需要设置 view_path
				$conffile = BBS_PATH.'conf/conf.php';
				if(!xn_writable($conffile)) {
					message("文件 $conffile 不可写，请设置文件权限为可写：".$confile);
				}
				$mconf->set_to('view_path', 'array(BBS_PATH.\'plugin/'.$dir.'/\', BBS_PATH.\'view/\')', $conffile);
				$mconf->save($conffile);
			}
			
			// 安装插件 --------------> end
			
		}
		$error = array();
	}
	include './header.inc.php';	
	include './plugin.inc.php';	
	include './footer.inc.php';
	exit;	
} elseif($step == 'complete') {
	// 设置 cookie
	/*
	$user = new user($bbs['conf']);
	$userdb = array('uid'=>1, 'username'=>'admin', 'password'=>'d14be7f4d15d16de92b7e34e18d0d0f7', 'groupid'=>'1');
	$user->set_login_cookie($userdb);
	*/
	header('Content-Type: text/html; charset=UTF-8');
	include './header.inc.php';	
	echo '<h1>安装完成！请您登陆后及时修改密码！！！</h1>';
	echo '<br /><p style="font-size: 16px;">初始化管理账号：<b class="red">admin</b> <br />初始化密码：<b class="red">1</b> <br /> </p>';
	echo '<br /><p><a href="../" style="font-size: 18px; font-weight: 800;">【跳转到首页】</a><p>';
	echo '<script type="text/javascript">alert("安装完成，为了安全请删除 install 目录，管理员账号为：admin, 初始密码：1，请登陆后进行修改！！！");</script>';
	include './footer.inc.php';
	touch($conf['upload_path'].'install.lock');
	exit;
	

	
}
