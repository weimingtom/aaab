<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class medal_control extends common_control {
	
	function __construct() {
		parent::__construct();
	}
	
	public function on_list() {
		$medallist = $this->medal->get_medallist();
		$this->view->assign('medallist', $medallist);
		$this->view->display('medal_list.htm');
	}
	
	public function on_my() {
		//我在 2009-5-19 11:17 被授予了 助人为乐勋章 勋章,永久有效
		$medaluserlist = $this->medal_user->index_fetch(array('uid'=>$this->_user['uid'], 'isapply'=>1), array('muid'=>-1));
		$this->medal_user->medaluserlist_form($medaluserlist);
		
		$this->view->assign('medaluserlist', $medaluserlist);
		$this->view->display('medal_my.htm');
	}
}

?>