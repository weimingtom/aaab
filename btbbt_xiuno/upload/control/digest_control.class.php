<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class digest_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('digest'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		$this->_title[] = '精华主题';
		$this->_nav[] = '<a href="?digest.htm">精华主题</a>';
		
		$fid = intval(core::gpc('fid'));
		$tid = intval(core::gpc('tid'));
		$cateid = intval(core::gpc('cateid'));
		
		// hook digest_index_before.php
		$jstime = filemtime($this->conf['upload_path'].'digestcate.js');
		$this->view->assign('jstime', $jstime);
		$this->view->assign('cateid', $cateid);
		$this->view->assign('fid', $fid);
		$this->view->assign('tid', $tid);
		// hook digest_index_after.php
		$this->view->display('digest.htm');
	}
	
	public function on_main() {
		$fid = intval(core::gpc('fid'));
		$tid = intval(core::gpc('tid'));
		$cateid = intval(core::gpc('cateid'));
		
		if(empty($fid) && empty($tid) && empty($cateid)) {
			
			// 最新精华
			// hook digest_main_default_before.php
			$digestlist = $this->digest->get_newlist(60);
			
			// hook digest_main_default_after.php
			$this->view->assign('digestlist', $digestlist);
		} else {
			
			// hook digest_main_before.php
			
			/* copy from post_control.class.php */
			$page = misc::page();
			$thread = $this->thread->get($fid, $tid);
			$this->check_thread_exists($thread);
			$this->thread->format($thread);
			
			// 板块权限检查
			//$forum = $this->forum->read($fid);
			$forum = $this->mcache->read('forum', $fid);
			$pforum = $this->mcache->read('forum', $forum['fup']);
			$this->check_forum_exists($forum);
			$this->check_forum_access($forum, $pforum, 'read');
			
			// 只缓存了 第一页20个pid，超出则查询 db
			if($this->conf['cache_pid'] && $this->conf['pagesize'] <= 20 && $page == 1) {
				// 这里至少有一个元素，楼主的pid
				$pids = explode(',', $thread['pids']);
				$fids = array_pad(array(), count($pids), $fid);
				$postlist = $this->post->get($fids, $pids);
			} else {
				$totalpage = ceil($thread['posts'] / $this->conf['pagesize']);
				$page > $totalpage && $page = $totalpage;
				$postlist = $this->post->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>$page), array(), 0, $this->conf['pagesize']);
				
				// php order by pid
				// ...
			}
			
			$pages = misc::pages("?digest-main-fid-$fid-tid-$tid.htm", $thread['posts'], $page, $this->conf['pagesize']);
			// copy end.
				
			$ismod = $this->is_mod($forum, $pforum, $this->_user);
			$this->view->assign('fid', $fid);
			$this->view->assign('tid', $tid);
			$this->view->assign('page', $page);
			$this->view->assign('pages', $pages);
			$this->view->assign('thread', $thread);
			$this->view->assign('forum', $forum);
			$this->view->assign('pforum', $pforum);
			$this->view->assign('postlist', $postlist);
			// hook digest_main_after.php
			$this->view->assign('ismod', $ismod);
		}
		$this->view->display('digest_main.htm');
	}
	
	// cateid
	
	public function on_list() {
		$page = misc::page();
		$pagesize = 34;
		$cateid = intval(core::gpc('cateid'));
		$cate = $this->digestcate->read($cateid);
		
		// hook digest_list_before.php
		$digestlist = $this->digest->get_list_by_cateid($cateid, $page, $pagesize);
		// hook digest_list_after.php
		$this->message(array($cate['threads'], $digestlist));
	}

	private function get_pagesize($tops) {
		$window_height = core::gpc('window_height', 'C');
		if(empty($window_height)) {
			return $this->conf['pagesize'];
		}
		$header_height = 175;
		$line_height = 31;
		$pagesize = ceil((($window_height - $header_height) / $line_height)) * 2;
		return max(10, $pagesize);
	}
	
	private function tidkeys_to_keys(&$keys, $tidkeys) {
		if($tidkeys) {
			$fidtidlist = explode(' ', trim($tidkeys));
			foreach($fidtidlist as $fidtid) {
				list($fid, $tid) = explode('-', $fidtid);
				$tid && $keys[] = "thread-fid-$fid-tid-$tid";
			}
		}
	}
	
	//hook digest_control.php
}

?>