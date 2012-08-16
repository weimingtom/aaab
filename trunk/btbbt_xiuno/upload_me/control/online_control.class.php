<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class online_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	public function on_list() {
		
		// hook online_list_before.php
		$onlinelist = $this->online->get_onlinelist();
		$users = count($onlinelist);
		$onlines = $this->online->count();
		
		$this->view->assign('users', $users);
		$this->view->assign('onlines', $onlines);
		$this->view->assign('onlinelist', $onlinelist);
		// hook online_list_after.php
		$this->view->display('online_ajax.htm');
	}
	
	//hook online_control_after.php
	
}

?>