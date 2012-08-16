<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class forum_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// 列表
	public function on_index() {
		
		// hook forum_index_before.php
		
		// 主题分类, typeid 将决定 fid，优先级高于 fid
		$typeid = intval(core::gpc('typeid'));
		$thread_type = $typeid ? $this->thread_type->read($typeid) : array();
		
		// fid
		$fid = $thread_type ? $thread_type['fid'] : intval(core::gpc('fid'));
		$forum = $this->mcache->read('forum', $fid);
		$this->check_forum_exists($forum);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		$this->check_forum_exists($pforum);
		$this->check_forum_access($forum, $pforum, 'read');
		
		// orderby
		$orderby = core::gpc('orderby', 'C');
		$orderby = $orderby === NULL ? $forum['orderby'] : intval($orderby);
		$this->_checked['orderby'][$orderby] = ' checked';
		$this->_checked['typeid'][$typeid] = ' class="checked"';
		
		$this->_title[] = $forum['seo_title'] ? $forum['seo_title'] : $forum['name'];
		//$this->_title[] = $pforum['seo_title'] ? $pforum['seo_title'] : $pforum['name'];
		$this->_seo_keywords = $forum['seo_keywords'] ?  $forum['seo_keywords'] : $forum['name'];
		//$this->_seo_keywords .= ' '.($pforum['seo_keywords'] ?  $pforum['seo_keywords'] : $pforum['name']);
		
		// hook forum_index_1.php
		
		$pagesize = $this->conf['pagesize'];
		$page = misc::page();
		misc::set_cookie($this->conf['cookiepre'].'page', $page, $_SERVER['time'] + 86400 * 7, '/');
		
		// hook forum_index_get_list_before.php
		
		$start = ($page - 1) * $pagesize;
		$limit = $pagesize;
		$threads = $typeid ? ($thread_type ? $thread_type['threads'] : 0) : $forum['threads'];
		
		// 从 tidcache 取数据
		if(empty($orderby) && empty($typeid) && $this->conf['cache_tid'] && ($page * $pagesize <= $this->conf['cache_tid_num'])) {
			$tids = $this->tidcache->get_tidarr($fid, $start, $limit);
			$fids = array_pad(array(), count($tids), $fid);
			$threadlist = $this->thread->get($fids, $tids);
		// 从 db 取数据
		} else {
			$condition = $typeid ? array('typeid'=>$typeid) : array('fid'=>$fid);
			$orderbyadd = $orderby == 0 ? array('floortime'=>-1) : array('tid'=>-1);
			$threadlist = $this->thread->index_fetch($condition, $orderbyadd, $start, $limit);
		}
		
		// 合并置顶的数据
		$toplist = $page == 1 ? $this->get_toplist($forum, $pforum) : array();
		$threadlist = array_merge($toplist, $threadlist);
		
		$readtids = '';
		foreach($threadlist as &$thread) {
			$readtids .= ','.$thread['tid'];
			$this->thread->format($thread);
		}
		$readtids = substr($readtids, 1); 
		$click_server = $this->conf['click_server']."?db=tid&r=$readtids";
		
		// hook forum_index_get_list_after.php
		
		$typeidadd = $typeid ? "-typeid-$typeid" : '';
		$pages = misc::pages("?forum-index-fid-$fid$typeidadd.htm", $threads, $page, $pagesize);
		
		$ismod = $this->is_mod($forum, $pforum, $this->_user);
		
		$this->view->assign('fid', $fid);
		$this->view->assign('typeid', $typeid);
		$this->view->assign('pforum', $pforum);
		$this->view->assign('forum', $forum);
		$this->view->assign('page', $page);
		$this->view->assign('pages', $pages);
		$this->view->assign('limit', $limit);
		$this->view->assign('threadlist', $threadlist);
		$this->view->assign('ismod', $ismod);
		$this->view->assign('orderby', $orderby);
		$this->view->assign('click_server', $click_server);
		// hook forum_index_after.php
		$this->view->display('forum_index.htm');
	}
	
	private function get_toplist($forum, $pforum) {
		$fids = $tids = array();
		// 3 级置顶
		$this->get_fids_tids($fids, $tids, $this->conf['runtime']['toptids']);
		
		// 2 级置顶
		$this->get_fids_tids($fids, $tids, $pforum['toptids']);
		
		// 1 级置顶
		$this->get_fids_tids($fids, $tids, $forum['toptids']);
		
		$toplist = $this->thread->get($fids, $tids);
		
		return $toplist;
	}
	
	// index_control.class copyed
	private function get_fids_tids(&$fids, &$tids, $s) {
		if($s) {
			$fidtidlist = explode(' ', trim($s));
			foreach($fidtidlist as $fidtid) {
				list($fid, $tid) = explode('-', $fidtid);
				$fids[] = $fid;
				$tids[] = $tid;
			}
		}
	}
	
	//hook forum_control.php
}

?>