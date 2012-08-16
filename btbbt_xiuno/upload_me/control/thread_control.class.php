<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class thread_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		
		// hook thread_index_before.php
		
		$fid = intval(core::gpc('fid'));
		$tid = intval(core::gpc('tid'));
		$uid = $this->_user['uid'];
		$page = misc::page();
		$thread = $this->thread->get($fid, $tid);
		$this->check_thread_exists($thread);
		$this->thread->format($thread);
		$fpage = intval(core::gpc($this->conf['cookiepre'].'page', 'C'));
		
		// 板块权限检查
		$forum = $this->mcache->read('forum', $fid);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $pforum, 'read');
		
		// SEO 优化，免费版本使用
		$this->_title[] = $thread['subject'];
		$this->_title[] = $pforum['name'];
		$this->_title[] = $forum['name'];
		$this->_seo_keywords = $thread['seo_keywords'] ? $thread['seo_keywords'] : $thread['subject'];
		
		// hook thread_index_2.php
		
		// 只缓存了 第一页20个pid，超出则查询 db
		if($this->conf['cache_pid'] && $this->conf['pagesize'] <= 20 && $page == 1) {
			// 包含一楼的 pid
			if($thread['pids']) {
				$pids = explode(',', $thread['pids']);
				$fids = array_pad(array(), count($pids), $fid);
				$postlist = $this->post->get($fids, $pids);
			} else {
				$postlist = array();
			}
		} else {
			$totalpage = ceil($thread['posts'] / $this->conf['pagesize']);
			$page > $totalpage && $page = $totalpage;
			$postlist = $this->post->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>$page), array(), 0, $this->conf['pagesize']);
		}
		
		// php order by pid
		//ksort($postlist);
		
		// 附件，用户
		$uids = $uid ? array($uid) : array();
		$i = ($page - 1) * $this->conf['pagesize'] + 1;
		foreach($postlist as &$post) {
			$this->post->format($post);
			if($post['attachnum'] > 0) {
				$post['attachlist'] = $this->attach->get_list_by_fid_pid($fid, $post['pid'], 0);
			}
			$post['floor'] = $i++;
			$uids[] = $post['uid'];
		}
		$uids = array_unique($uids);
		$userlist = $this->user->get($uids);
		foreach($userlist as &$user) {
			$this->user->format($user);
			$userlist[$user['uid']] = $user;
		}
		$uid && $this->_user = $userlist[$uid];
		$this->view->assign('userlist', $userlist);
		
		// 分页
		$pages = misc::pages("?thread-index-fid-$fid-tid-$tid.htm", $thread['posts'], $page, $this->conf['pagesize']);
		
		// 点击数服务器 seo notfollow
		$click_server = $this->conf['click_server']."?db=tid&w=$tid&r=$tid";
		$scrollbottom = core::gpc('scrollbottom');
		
		// 版主
		$ismod = $this->is_mod($forum, $pforum, $this->_user);
		
		// userlist
		
		$this->view->assign('click_server', $click_server);
		$this->view->assign('scrollbottom', $scrollbottom);
		$this->view->assign('fid', $fid);
		$this->view->assign('tid', $tid);
		$this->view->assign('page', $page);
		$this->view->assign('fpage', $fpage);
		$this->view->assign('pages', $pages);
		$this->view->assign('thread', $thread);
		$this->view->assign('forum', $forum);
		$this->view->assign('pforum', $pforum);
		$this->view->assign('postlist', $postlist);
		$this->view->assign('ismod', $ismod);
		// hook thread_index_after.php
		$this->view->display('thread_index.htm');
	}
	
	//hook thread_control.php
}

?>