<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class log_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	public function on_phperror() {
		$this->_title[] = 'PHP错误日志';
		$this->_nav[] = '<a href="./">PHP错误日志</a>';
		$page = misc::page();
		$logfile = $this->conf['log_path'].'phperror.php';
		if(!is_file($logfile)) touch($logfile);
		
		$filesize = filesize($logfile);
		$totalpage = ceil($filesize / 50000);
		$pages = misc::pages('?log-phperror.htm', $totalpage * 20, $page, 20);
		
		$loglist = $this->get_loglist($logfile, $page);
		
		$logtype = 'phperror';
		$this->view->assign('logtype', $logtype);
		$this->view->assign('logfile', $logfile);
		$this->view->assign('filesize', $filesize);
		$this->view->assign('loglist', $loglist);
		$this->view->assign('pages', $pages);
		
		// hook admin_log_phperror.php
		
		$this->view->display('log_list.htm');
	}
	
	public function on_login() {
		$this->_title[] = '错误登录日志';
		$this->_nav[] = '<a href="./">错误登录日志</a>';
		$page = misc::page();
		$logfile = $this->conf['log_path'].'login.php';
		if(!is_file($logfile)) touch($logfile);
		
		$filesize = filesize($logfile);
		$totalpage = ceil($filesize / 50000);
		$pages = misc::pages('?log-login.htm', $totalpage * 20, $page, 20);
		
		$loglist = $this->get_loglist($logfile, $page);
		
		$logtype = 'login';
		$this->view->assign('logtype', $logtype);
		$this->view->assign('logfile', $logfile);
		$this->view->assign('filesize', $filesize);
		$this->view->assign('loglist', $loglist);
		$this->view->assign('pages', $pages);
		
		// hook admin_log_login.php
		
		$this->view->display('log_list.htm');
	}
	
	public function on_cron() {
		$this->_title[] = '计划任务日志';
		$this->_nav[] = '<a href="./">计划任务日志</a>';
		$page = misc::page();
		$logfile = $this->conf['log_path'].'cron.php';
		if(!is_file($logfile)) touch($logfile);
		
		$filesize = filesize($logfile);
		$totalpage = ceil($filesize / 50000);
		$pages = misc::pages('?log-cron.htm', $totalpage * 20, $page, 20);
		
		$loglist = $this->get_loglist($logfile, $page);
		
		$logtype = 'cron';
		$this->view->assign('logtype', $logtype);
		$this->view->assign('logfile', $logfile);
		$this->view->assign('filesize', $filesize);
		$this->view->assign('loglist', $loglist);
		$this->view->assign('pages', $pages);
		
		// hook admin_log_cron.php
		
		$this->view->display('log_list.htm');
	}
	
	// 清空日志文件
	public function on_truncate() {
		$file = core::gpc('file');
		!in_array($file, array('phperror', 'login', 'cron')) && $file = 'login';
		$logfile = $this->conf['log_path'].$file.'.php';
		is_file($logfile) && unlink($logfile) && touch($logfile);
		
		// hook admin_truncate.php
		
		$this->message('日志文件'.$logfile.'清空完毕。', 1, "?log-$file.htm");
	}
	
	// 每页50K！ 可能会漏掉翻页中间的半条！
	private function get_loglist($logfile, $page) {
		$offset = $page * 50000;
		$filesize = filesize($logfile);
		$offset = max(0, $filesize - $offset);
		$fp = fopen($logfile, 'r');
		fseek($fp, $offset);
		$s = fread($fp, 50000);
		$arr = explode("\r\n", $s);
		$return = array();
		foreach($arr as $v) {
			$arr2 = explode("\t", $v);
			if(isset($arr2[4])) {
				$return[] = $arr2;
			}
		}
		krsort($return);
		return $return;
	}
	
	//hook log_control.php
	
}

?>