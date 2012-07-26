<?php

/*
 * Copyright (C) xiuno.com
 */

class thread_top extends base_model {
	
	function __construct() {
		parent::__construct();
	}
	
	// 保存一级/二级置顶，合并到 $forum
	public function add_top_1($forum, $tidarr) {
		$tidkeys = $this->tidarr_to_fidtid($tidarr);
		$forum['toptids'] = misc::key_str_merge($forum['toptids'], $tidkeys);
		$this->forum->update($forum['fid'], $forum);
		$this->mcache->clear('forum', $forum['fid']);
		
		// 更新到 $thread
		$this->update_thread_top($forum, $tidarr, 1);
	}
	
	/*
		$tidarr 格式: array('123-12345', '123-23456')
	*/
	public function delete_top_1($forum, $tidarr) {
		$tidkeys = $this->tidarr_to_fidtid($tidarr);
		$forum['toptids'] = misc::key_str_strip($forum['toptids'], $tidkeys);
		$this->forum->update($forum['fid'], $forum);
		$this->mcache->clear('forum', $forum['fid']);
		
		// 更新到 $thread
		$this->update_thread_top($forum, $tidarr, 0);
	}
	
	// 保存一级/二级置顶，合并到 $forum
	public function add_top_2($forum, $tidarr, $pforum) {
		$tidkeys = $this->tidarr_to_fidtid($tidarr);
		$pforum['toptids'] = misc::key_str_merge($pforum['toptids'], $tidkeys);
		$this->forum->update($pforum['fid'], $pforum);
		$this->mcache->clear('forum', $pforum['fid']);
		
		// 更新到 $thread
		$this->update_thread_top($forum, $tidarr, 2);
	}
	
	public function delete_top_2($forum, $tidarr, $pforum) {
		$tidkeys = $this->tidarr_to_fidtid($tidarr);
		$pforum['toptids'] = misc::key_str_strip($pforum['toptids'], $tidkeys);
		$this->forum->update($pforum['fid'], $pforum);
		$this->mcache->clear('forum', $pforum['fid']);
		
		// 更新到 $thread
		$this->update_thread_top($forum, $tidarr, 0);
	}
	
	// $tidkeys 
	public function add_top_3($forum, $tidarr) {
		$runtime = $this->conf['runtime'];
		$tidkeys = $this->tidarr_to_fidtid($tidarr);
		$toptids = misc::key_str_merge($runtime['toptids'], $tidkeys);
		$this->kv->set('toptids', $toptids);
		$this->runtime->update_bbs('toptids', $toptids);
		
		// 更新到 $thread
		$this->update_thread_top($forum, $tidarr, 3);
	}
	
	public function delete_top_3($forum, $tidarr) {
		$runtime = $this->conf['runtime'];
		$tidkeys = $this->tidarr_to_fidtid($tidarr);
		$toptids = misc::key_str_strip($runtime['toptids'], $tidkeys);
		$this->kv->set('toptids', $toptids);
		$this->runtime->update_bbs('toptids', $toptids);
		
		// 更新到 $thread
		$this->update_thread_top($forum, $tidarr, 0);
	}
	
	// 获取 三级置顶的 fid, tid，全表扫描！还好是定长表，仅仅在mysql重启以后需要，节约一个索引。
	public function get_top_3_fid_tid() {
		$threadlist = $this->thread->index_fetch(array('top'=>3), array(), 0, 100);
		$tidkeys = '';
		foreach($threadlist as $thread) {
			$tidkeys .= " $thread[fid]-$thread[tid]";
		}
		$tidkeys = trim($tidkeys);
		return $tidkeys;
	}
	
	private function update_thread_top($forum, $tidarr, $top) {
		// 更新到 $thread
		foreach($tidarr as $fid_tid) {
			list($fid, $tid) = explode('-', $fid_tid);
			$thread = $this->thread->read($fid, $tid);
			$thread['top'] = $top;
			$this->thread->update($fid, $tid, $thread);
		}
	}
	
	// 返回格式 "123-12345 123-12346 123-12347"
	private function tidarr_to_fidtid($tidarr) {
		$fidtids = '';
		foreach($tidarr as $fid_tid) {
			list($fid, $tid) = explode('-', $fid_tid);
			$fidtids .= " $fid-$tid";
		}
		$fidtids = trim($fidtids);
		return $fidtids;
	}
	
}
?>