<?php

/*
 * @author:huyao
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class medal_control extends admin_control {
	
	function __construct() {
		parent::__construct();
	}
	
	// 列表
	public function on_index() {
		$this->on_list();
	}	
	
	public function on_list() {
		$this->_title[] = '勋章列表';
		$this->_nav[] = '<a href="">友情链接列表</a>';
		
		$pagesize = 10;
		$page = misc::page();
		$num = $this->medal->count();
		$medallist = $this->medal->index_fetch(array(), array(), ($page - 1) * $pagesize, $pagesize);
		//print_r($medallist);
		$pages = misc::pages("?medal-list.htm", $num, $page, $pagesize);
		
		$this->view->assign('medallist', $medallist);
		$this->view->assign('pages', $pages);
		$this->view->display('medal_list.htm');
	}
	
	public function on_update() {
		$this->_title[] = '勋章修改';
		
		$medalId = intval(core::gpc('medalId'));
		$medal = $this->medal->get($medalId);
		
		$this->view->assign('medal', $medal);
		$this->view->display('medal_update.htm');
	}
}