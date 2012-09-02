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
		
		$stat = array();
		$stat['threads'] = $this->thread->count();
		$stat['posts'] = $this->post->count();
		$stat['users'] = $this->user->count();
		$stat['attachs'] = $this->attach->count();
		$stat['disk_free_space'] = misc::humansize(disk_free_space('./'));
		
		$this->view->assign('info', $info);
		$this->view->assign('stat', $stat);
		$this->view->assign('lastversion', $lastversion);
		
		// hook admin_index_main_view_before.php
		
		$this->view->display('index_main.htm');
	}
	
	
        private function get_last_version() {
        	$lastfile = $this->conf['tmp_path'].'last_version.lock';
        	$isfile = is_file($lastfile);
        	!$isfile && touch($lastfile);
        	$last_version = filemtime($lastfile);
		if(!$isfile || ($_SERVER['time'] - $last_version > 86400)) {
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
        
        //hook index_control_after.php

}

?>