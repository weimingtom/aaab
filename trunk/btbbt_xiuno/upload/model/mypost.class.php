<?php

/*
 * Copyright (C) xiuno.com
 */

class mypost extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'mypost';
		$this->primarykey = array('uid', 'fid', 'pid');
		
	}

	/*
		$arr = array(
			'uid'=>1,
			'fid'=>123,
			'tid'=>123,
			'pid'=>123,
		);
	*/
	public function create($arr) {
		if($this->set($arr['uid'], $arr['fid'], $arr['pid'], $arr)) {
			$this->count('+1');
			return TRUE;
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function read($uid, $fid, $pid) {
		return $this->get($uid, $fid, $pid);
	}
	
	public function read_by_tid($uid, $fid, $tid) {
		$mypost = $this->index_fetch(array('uid'=>$uid, 'fid'=>$fid, 'tid'=>$tid), array(), 0, 1);
		return count($mypost) > 0 ? array_pop($mypost) : array();
	}
	
	// 判断某个 tid 是否已经被加入过了
	public function have_tid($uid, $fid, $tid) {
		$havetid = $this->index_fetch(array('uid'=>$uid, 'fid'=>$fid, 'tid'=>$tid), array(), 0, 1);
		return count($havetid);
	}

	public function _delete($uid, $fid, $pid) {
		$return = $this->delete($uid, $fid, $pid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_list_by_uid($uid, $page = 1, $pagesize = 30) {
		$mypostlist = $this->index_fetch(array('uid'=>$uid), array('pid'=>-1), ($page - 1) * $pagesize, $pagesize);
		return $mypostlist;
	}
	
	/*
	foreach($mypostlist as &$mypost) {
		$this->format($mypost);
	}
	*/
	public function format(&$mypost) {
		$thread = $this->thread->read($mypost['fid'], $mypost['tid']);
		$post = $this->post->read($mypost['fid'], $mypost['pid']);
		$this->thread->format($thread);
		$this->post->format($post);
		$mypost['thread'] = $thread;
		$mypost['post'] = $post;
	}
}
?>