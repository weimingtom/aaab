<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class index_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
			
	public function on_index() {
		$this->view->display('index.htm');
	}
	
	function on_menu() {
		$this->view->display('index_menu.htm');
	}
	
	function on_main() {
		$info = array();
		$info['disable_functions'] = ini_get('disable_functions');
		$info['allow_url_fopen'] = ini_get('allow_url_fopen') ? '是' : '否';
		$info['safe_mode'] = ini_get('safe_mode') ? '是' : '否';
		empty($info['disable_functions']) && $info['disable_functions'] = '无';
		$info['upload_max_filesize'] = ini_get('upload_max_filesize');
		$info['post_max_size'] = ini_get('post_max_size');
		$info['memory_limit'] = ini_get('memory_limit');
		$info['max_execution_time'] = ini_get('max_execution_time');
		$info['dbversion'] = $this->user->db->version();
		$info['SERVER_SOFTWARE'] = core::gpc('SERVER_SOFTWARE', 'S');
		$lastversion = $this->get_last_version();
		
		$this->view->assign('info', $info);
		$this->view->assign('lastversion', $lastversion);
		
		// hook admin_index_main.php
		
		$this->view->display('index_main.htm');
	}
	
	
        private function get_last_version() {
        	$lastfile = $this->conf['tmp_path'].'last_version.lock';
        	!is_file($lastfile) && touch($lastfile);
        	$last_version = filemtime($lastfile);
		if(empty($last_version) || ($_SERVER['time'] - $last_version > 86400)) {
			@unlink($lastfile);	
			@touch($lastfile);
			$sitename = urlencode($this->conf['app_name']);
			$sitedomain = urlencode($this->conf['app_url']);
			$version = urlencode($this->conf['version']);
			$users = $this->user->count();
			$threads = $this->thread->count();
			$posts = $this->post->count();
			return '<'.'sc'.'ri'.'pt src="htt'.'p:'.'/'.'/c'.'ust'.'om'.'.xi'.'u'.'no.'.'co'.'m/version.htm'.'?'.'&sitename='.$sitename.'&sitedomain='.$sitedomain.'&users='.$users.'&threads='.$threads.'&posts='.$posts.'&version='.$version.'">'.'<'.'/s'.'cr'.'ip'.'t>';
		} else {
			return '';
		}
        }
        
        //hook index_control.php

}

?>