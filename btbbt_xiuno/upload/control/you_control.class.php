<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class you_control extends common_control {
	
	private $you;
	function __construct() {
		parent::__construct();
		
		// 初始化用户信息
		$uid = intval(core::gpc('uid'));
		empty($uid) && $uid = $this->_user['uid'];
		$user = $this->user->get($uid);
		$this->check_user_exists($user);
		$this->user->format($user);
		$this->user->format_follow($user, $this->_user['uid'], $uid);
		$user['groupname'] = $_SERVER['miscarr']['group'][$user['groupid']];
		
		$this->you = $user;
		$this->view->assign('you', $this->you);
	}
	
	// 我看别人的空间首页，个人资料
	public function on_index() {
		$this->_checked['you_index'] = 'class="checked"';
		$user = $this->you;
		
		$this->_title[] = $user['username'].'的个人资料';
		$this->_nav[] = '个人资料';
		
		// hook you_index.php
		$this->view->display('you_index.htm');
	}
	
	// ajax
	public function on_profile() {

		//$this->view->assign('mypostlist', $mypostlist);
		// hook you_profile.php
		$this->view->display('you_profile_ajax.htm');
	}
	
	// 别人的发帖
	public function on_post() {
		$this->_checked['you_post'] = 'class="checked"';
		
		$user = $this->you;
		$uid = $user['uid'];

		$this->_title[] = '参与主题';
		$this->_nav[] = '参与主题';
		
		// hook you_post_before.php
		$page = misc::page();
		$pagesize = 40;
		$mypostlist = $this->mypost->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-follow.htm", count($mypostlist), $page, $pagesize);
		
		foreach($mypostlist as &$post) {
			$post['forumname'] = isset($_SERVER['miscarr']['forum'][$post['fid']]) ? $_SERVER['miscarr']['forum'][$post['fid']] : '';
			$this->mypost->format($post);
		}
		
		$this->view->assign('pages', $pages);
		$this->view->assign('mypostlist', $mypostlist);
		
		// hook you_post_after.php
		$this->view->display('you_post.htm');
	}
	
	// 别人的关注，一次全部取出。最多100个。
	public function on_follow() {
		$this->_checked['you_follow'] = 'class="checked"';
		
		$user = $this->you;
		$uid = $user['uid'];
		
		$this->_title[] = '他的关注';
		$this->_nav[] = '他的关注';
		
		// hook you_follow_before.php
		$page = misc::page();
		$pagesize = 64;
		$followlist = $this->follow->get_list_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-follow.htm", count($followlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('userlist', $followlist);
		
		// hook you_follow_after.php
		$this->view->display('you_follow.htm');
		
	}
	
	// 别人的粉丝 100个
	public function on_followed() {
		$this->_checked['you_followed'] = 'class="checked"';
		
		$user = $this->you;
		$uid = $user['uid'];
		
		$this->_title[] = '他的粉丝';
		$this->_nav[] = '他的粉丝';
		
		// hook you_followed_before.php
		
		$page = misc::page();
		$pagesize = 64;
		$followedlist = $this->follow->get_followedlist_by_uid($uid, $page, $pagesize);
		$pages = misc::simple_page("?my-follow.htm", count($followedlist), $page, $pagesize);
		$this->view->assign('pages', $pages);
		$this->view->assign('userlist', $followedlist);
		
		// hook you_followed_after.php
		$this->view->display('you_followed.htm');
	}
	
	//hook you_control.php
}

?>