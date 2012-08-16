<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class group_control extends admin_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		$this->on_list();
	}	
	
	public function on_list() {
		$this->_title[] = '管理用户组';
		$this->_nav[] = '<a href="./">管理用户组</a>';
		
		if($this->form_submit()) {
			$ids = core::gpc('ids', 'P');
			if(!empty($ids)) {
				foreach($ids as $id) {
					list($groupid) = explode('-', $id);
					$groupid = intval($groupid);
					$this->group->delete($groupid);
					$this->mcache->clear('group', $groupid);
				}
				$this->mcache->clear('grouplist');
				$this->mcache->clear('miscarr');
				
				// hook admin_group_list_submit_after.php
			}
		}
		
		$page = misc::page();
		$groups = $this->group->count();
		$grouplist = $this->group->index_fetch(array(), array(), ($page -1) * $this->conf['pagesize'], $this->conf['pagesize']);
		
		$grouplist1 = $grouplist2 = $grouplist3 = array();
		$newgroupid = $newgroupid2 = 0;
		foreach($grouplist as &$group) {
			if($group['groupid'] < 8) {
				$grouplist1[$group['groupid']] = $group;
			} elseif($group['groupid'] >= 8 && $group['groupid'] < 11) {
				$grouplist2[$group['groupid']] = $group;
			} else {
				$grouplist3[$group['groupid']] = $group;
			}
			$this->group->format($group);
			$group['groupid'] > $newgroupid && $newgroupid = $group['groupid'];
			$group['groupid'] >= 7 && $group['groupid'] < 11 && $group['groupid'] > $newgroupid2 && $newgroupid2 =  $group['groupid'];
		}
		$newgroupid++;
		$newgroupid2++;
		
		$pages = misc::pages("?group-list.htm", $groups, $page, $this->conf['pagesize']);
		
		$this->view->assign('newgroupid', $newgroupid);
		$this->view->assign('newgroupid2', $newgroupid2);	// 可升级用户组最大值，在 3-10之间
		$this->view->assign('grouplist1', $grouplist1);
		$this->view->assign('grouplist2', $grouplist2);
		$this->view->assign('grouplist3', $grouplist3);
		$this->view->assign('grouplist', $grouplist);
		
		// hook admin_group_list_view_before.php
		
		$this->view->display('group_list.htm');
	}

	// 添加
	public function on_create() {
		$this->_title[] = '创建用户组';
		$this->_nav[] = '创建用户组';
		
		$group = $error = array();
		if($this->form_submit()) {
			$post = array();
			
			// todo: 最多只能有 > 10 的 8个用户组
			
			$post['groupid'] = intval(core::gpc('groupid', 'P'));
			$post['name'] = core::gpc('name', 'P');
			$post['creditsfrom'] = intval(core::gpc('creditsfrom', 'P'));
			$post['creditsto'] = intval(core::gpc('creditsto', 'P'));
			$post['upfloors'] = intval(core::gpc('upfloors', 'P'));
			$post['color'] = core::gpc('color', 'P');

			$error['name'] = $this->group->check_name($post['name']);
			if($post['groupid'] > 10) {
				//$error['creditsfrom'] = $this->group->check_creditsfrom($post['creditsfrom']);
				$error['creditsto'] = $this->group->check_creditsto($post['creditsto']);
			}
			$error['upfloors'] = $this->group->check_upfloors($post['upfloors']);

			if($post['groupid'] > 18) {
				$this->message('用户组最多只能有18个。', 0);
			}
			if(misc::values_empty($error)) {
				$error = array();
				$this->group->create($post, $post['groupid']);
				
				// hook admin_group_create_after.php
				
				$this->mcache->clear('grouplist');
				$this->mcache->clear('miscarr');
				$this->location("?group-list.htm");
			} else {
				$this->message($error, 0);
			}
		}
	}
	
	// 修改
	public function on_update() {
		$this->_title[] = '修改用户组';
		$this->_nav[] = '修改用户组';
		
		$groupid = intval(core::gpc('groupid'));

		$group = $this->group->get($groupid);
		$this->check_group_exists($group);
		
		$input = $error = array();
		if($this->form_submit()) {
			
			// 准备更新数据
			$post = array();
			
			$post['name'] = core::gpc('name', 'P');
			$post['creditsfrom'] = intval(core::gpc('creditsfrom', 'P'));
			$post['creditsto'] = intval(core::gpc('creditsto', 'P'));
			$post['upfloors'] = intval(core::gpc('upfloors', 'P'));
			$post['color'] = core::gpc('color', 'P');

			$error['name'] = $this->group->check_name($post['name']);
			if($groupid > 10) {
				//$error['creditsfrom'] = $this->group->check_creditsfrom($post['creditsfrom']);
				$error['creditsto'] = $this->group->check_creditsto($post['creditsto']);
			}
			$error['upfloors'] = $this->group->check_upfloors($post['upfloors']);

			if(misc::values_empty($error)) {
				$error = array();
				$group = array_merge($group, $post);
				
				// hook admin_group_update_before.php
				
				$this->group->update($groupid, $group);
			
				$this->mcache->clear('group', $groupid);
				$this->mcache->clear('grouplist');
				$this->mcache->clear('miscarr');
				$this->location("?group-list.htm");
			} else {
				$this->message($error, 0);
			}
		}
	}
	
	// 预留
	public function on_read() {
		$this->_title[] = '查看用户组';
		$this->_nav[] = '查看用户组';
		
		$groupid = intval(core::gpc('groupid'));

		$group = $this->group->get($groupid);
		$this->check_group_exists($group);
		$this->group->format($group);
		
		$this->view->assign('group', $group);
		
		// hook admin_group_read_view_before.php
		
		$this->view->display('group_read.htm');
	}
		
	public function on_delete() {
		$this->_title[] = '删除用户组';
		$this->_nav[] = '删除用户组';
		
		$groupid = intval(core::gpc('groupid'));
		if($groupid < 3 || $groupid == 11) {
			$this->message('该用户组不能删除。');
		}

		$group = $this->group->get($groupid);
		$this->check_group_exists($group);
		
		$this->group->_delete($groupid);
		
		$this->mcache->clear('group', $groupid);
		$this->mcache->clear('grouplist');
		$this->mcache->clear('miscarr');
		
		// 调整用户组所有用户，自动升级的时候调整，此处不调整。
		// $uids = $this->user->fetch_index_id();
		
		// hook admin_group_delete_after.php
		
		$this->location("?group-list.htm");
	}

	private function check_group_exists($group) {
		if(empty($group)) {
			$this->message('group不存在！可能已经被删除。');
		}
	}
	
	//hook group_control_after.php
}
?>