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
	
	// 勋章中心
	public function on_list() 
	{
		$user = $this->user->get($this->_user['uid']);
		$medallist = $medallist = $this->medal->index_fetch(array(), array('seq'=>1));
		foreach ($medallist as &$medal) {
			$medal['receive'] = '';
			if ($medal['receivetype'] == 1 && $user) {
				$isreceive = $this->medal->is_receive($user, $medal);
				if ($isreceive) {
					$medal['receive'] = '<a href="?medal-receive.htm?uid='.$user['uid'].'&medalid='.$medal['medalid'].'">领取</a>';
				}
			}
		}
		$this->medal->medallist_form($medallist);
		
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
	
	// 领取
	public function on_receive() {
		$uid = core::gpc('uid');
		$medalid = core::gpc('medalid');
		if ($this->_user['uid'] == $uid && $medalid) {
			$user = $this->user->get($uid);
			$medal = $this->medal->get($medalid);
			$medaluser = $this->medal_user->get_medaluser_by_uid_medalid($uid, $medalid);
			if (!$medaluser && $medal && $user) {
				$this->medal_user->create(array(
					'medalid'=>$medalid,
					'uid'=>$uid,
					'username'=>$user['username'],
					'receivetype'=>$medal['receivetype'],
					'autogrant'=>$medal['autogrant'],
					'expiredtime'=>0,
					'isapply'=>0,
					'fuid'=>$uid,
					'createdtime'=>time(),
				));
			}
			$this->message('领取成功，请等待管理员审核。', true, "?medal-list.htm");
		} else {
			$this->message('领取失败，请联系管理员。', true, "?medal-list.htm");
		}
	}
}

?>