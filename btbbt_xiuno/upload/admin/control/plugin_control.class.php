<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class plugin_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	// 已经安装的插件列表
	public function on_index() {
		$this->on_list();
	}
	
	// 已经安装的插件列表
	public function on_list() {
		
		$pluginlist = $this->get_plugin_dirs($this->conf['plugin_path']);
		$this->view->assign('pluginlist', $pluginlist);
		$this->view->display('plugin_list.htm');
	}
	
	// 插件商城
	/*
	public function on_shop() {
		// 获取插件列表
		$file = $this->conf['tmp_path'].'plugin_last_update.js';
		if(!is_file($file) || $_SERVER['time'] - filemtime($file) > 86400) {
			try {
				$pluginlist = misc::get_url('http://plugin.xiuno.com/shop/', FALSE);
				file_put_contents($pluginlist, $file);
			} catch (Exception $e) {
				throw new Exception('获取插件数据失败，可能官方服务器忙，请稍后再尝试。');
			}
		}
		$pluginlist = json_decode(file_get_contents($file));
		
		$this->view->assign('pluginlist', $pluginlist);
		$this->view->display('plugin_shop.htm');
	}
	*/
	
	// 安装
	public function on_install() {
		
		// 判断插件类型
		$dir = trim(core::gpc('dir'));
		$this->check_dir($dir);
		$is_view = $this->is_view($dir);
		
		$install = $this->conf['plugin_path'].$dir.'/install.php';
		if(is_file($install)) {
			try {
				include $install;
			} catch(Exception $e) {
				log::write("安装插件 $dir 可能发生错误:".$e->getMessage());
			}
		}
		
		// 设置 installed 标记
		$conffile = $this->conf['plugin_path'].$dir.'/conf.php';
		$this->check_writable($conffile);
		if(is_file($conffile)) {
			$this->mconf->set_to('enable', 1, $conffile);
			$this->mconf->set_to('installed', 1, $conffile);
			$this->mconf->save($conffile);
		}
		
		// 如果为风格插件，则需要设置 view_path
		$conffile = BBS_PATH.'conf/conf.php';
		$this->check_writable($conffile);
		if($is_view) {
			$this->mconf->set_to('view_path', 'array(BBS_PATH.\'plugin/'.$dir.'/\', BBS_PATH.\'view/\')', $conffile);
			$this->mconf->save($conffile);
			
			// 卸载其他 view，只允许一个风格插件启用。
			$pluginlist = $this->get_plugin_dirs($this->conf['plugin_path']);
			foreach($pluginlist as $_dir=>$plugin) {
				if(substr($_dir, 0, 5) == 'view_' && $_dir != $dir) {
					$conffile = $this->conf['plugin_path'].$_dir.'/conf.php';
					$this->check_writable($conffile);
					if(is_file($conffile)) {
						$this->mconf->set_to('enable', 0, $conffile);
						$this->mconf->set_to('installed', 0, $conffile);
						$this->mconf->save($conffile);
					}
				}
			}
		}
		
		// 清空 tmp 目录下的 bbs_* bbsadmin_*
		$this->clear_cache($this->conf['tmp_path'], 'bbs_');
		$this->clear_cache($this->conf['tmp_path'], 'bbsadmin_');
		$this->message('安装成功。');
	}
	
	// 卸载
	public function on_unstall() {
		// 判断插件类型
		$dir = trim(core::gpc('dir'));
		$this->check_dir($dir);
		$is_view = $this->is_view($dir);
		
		// 开始寻找 install，这里非常的危险！需要过滤一下，只允许字母数字下划线的目录名
		$unstall = $this->conf['plugin_path'].$dir.'/unstall.php';
		if(is_file($unstall)) {
			try {
				include $unstall;
			} catch(Exception $e) {
				log::write("卸载插件 $dir 可能发生错误:".$e->getMessage());
			}
		}
		
		// 设置 installed 标记
		$conffile = $this->conf['plugin_path'].$dir.'/conf.php';
		$this->check_writable($conffile);
		if(is_file($conffile)) {
			$this->mconf->set_to('enable', 0, $conffile);
			$this->mconf->set_to('installed', 0, $conffile);
			$this->mconf->save($conffile);
		}
		
		// 如果为风格插件，则需要设置 view_path
		$conffile = BBS_PATH.'conf/conf.php';
		$this->check_writable($conffile);
		if($is_view) {
			$this->mconf->set_to('view_path', 'array(BBS_PATH.\'view/\')', $conffile);
			$this->mconf->save($conffile);
		}
		
		// 清空 tmp 目录下的 bbs_* bbsadmin_*
		$this->clear_cache($this->conf['tmp_path'], 'bbs_');
		$this->clear_cache($this->conf['tmp_path'], 'bbsadmin_');
		
		if($is_view) {
			$this->message('卸载该风格成功，已经还原为系统默认风格。');
		} else {
			$this->message('卸载成功。');
		}
	}
	
	// 禁用
	
	// 插件的设置，一般是修改配置文件。
	public function on_setting() {
		// 判断插件类型
		$dir = trim(core::gpc('dir'));
		$this->check_dir($dir);
		$is_view = $this->is_view($dir);
		$this->conf['view_path'][] = $this->conf['plugin_path'].$dir.'/';	// 增加 view 目录
		$this->view->assign('dir', $dir);
		
		// 开始寻找 install，这里非常的危险！需要过滤一下，只允许字母数字下划线的目录名
		$setting = $this->conf['plugin_path'].$dir.'/setting.php';
		if(is_file($setting)) {
			try {
				
				include $setting;
			} catch(Exception $e) {
				log::write("设置插件 $dir 可能发生错误:".$e->getMessage());
				echo $e->getMessage();
			}
		} else {
			echo " $setting 文件不存在。";
		}
	}
	
	// 是否为风格插件
	private function is_view($dir) {
		return substr($dir, 0, 4) == 'view';
	}
	
	// path to plugin dirname
	private function get_dirname($path) {
		preg_match('#plugin/(\w+)/#i', $path, $m);
		return empty($m[1]) ? '' : $m[1];
	}
	
	// copy from conf_control.class.php
	private function clear_cache($dir, $pre) {
		$dh = opendir($dir);
		while(($file = readdir($dh)) !== false ) {
			if($file != "." && $file != ".." ) {
				if(is_dir( $dir . $file ) ) {
					//opendir_recursive( $dir . $file . "/", $recall);
				} else {
					if(substr($file, 0, strlen($pre)) == $pre) {
						unlink($dir."$file");
					}
				}
			}
		}
		closedir($dh);
	}
	
	private function check_writable($path) {
		if(!is_writable($path)) {
			$this->message("错误：$path 不可写！您可以通过FTP或者命令行设置 $path 为可写权限。");
		}
	}
	
	// 获取 plugin dirname
	private static function get_plugin_dirs($path) {
		$arr = core::get_paths($path, FALSE);
		$dirs = array();
		foreach($arr as $k=>$v) {
			$conffile = $path."$v/conf.php";
			$settingfile = $path."$v/setting.php";
			$pconf = is_file($conffile) ? include($conffile) : array();
			$pconf['have_setting'] = is_file($settingfile);
			$dirs[$v] = $pconf;
		}
		return $dirs;
	}
	
	// 检查是否为合法的 dir
	private function check_dir($dir) {
		$r = preg_match('#^\w+$#', $dir) && is_dir($this->conf['plugin_path'].$dir);
		if(!$r) {
			$dir = htmlspecialchars($dir);
			$this->message("插件 $dir 不存在。");
		}
	}
	
}

?>