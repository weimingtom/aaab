<?php
/*
 * author:huyao
 * email:252288762@qq.com
 * qq:252288762
 * description:勋章插件和xiuno一起努力，做到最好
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class medal_control extends common_control {
	
	function __construct() {
		parent::__construct();
		
		//$plugin_url = $this->conf['app_url'].'plugin/medal/';
		//$this->view->assign('plugin_url', $plugin_url);
	}
	
	// 勋章中心
	public function on_list() 
	{
		$user = $this->user->get($this->_user['uid']);
		$medallist = $this->medal->get_medallist_orderby_seq(1);
		
		$this->medal->medallist_format($medallist);
		
		$this->view->assign('medallist', $medallist);
		$this->view->display('medal_list.htm');
	}
	
	public function on_my() {
		//我在 2009-5-19 11:17 被授予了 助人为乐勋章 勋章,永久有效
		$medaluserlist = $this->medal_user->get_medaluserlist_by_uid_isapply($this->_user['uid'], 1);
		$this->medal_user->medaluserlist_format($medaluserlist);
		
		$this->view->assign('medaluserlist', $medaluserlist);
		$this->view->display('medal_my.htm');
	}

}

?>