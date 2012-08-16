<?php

/*
 * Copyright (C) xiuno.com
 */

class modlog extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'modlog';
		$this->primarykey = array('logid');
		$this->maxcol = 'logid';
		
	}
	
	public function create($arr) {
		$arr['logid'] = $this->maxid('+1');
		if($this->set($arr['logid'], $arr)) {
			$this->count('+1');
			return $arr['logid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($logid, $arr) {
		return $this->set($logid, $arr);
	}
	
	public function read($logid) {
		return $this->get($logid);
	}

	public function _delete($logid) {
		$return = $this->delete($logid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function delete_by_fid($fid) {
		// 最多支持 100000
		$keys = $this->index_fetch_id(array('fid'=>$fid), array(), 0, 100000);
		foreach($keys as $key) {
			$this->delete($key);
		}
		return count($keys);
	}
	
	public function get_list_by_uid($uid, $page, $pagesize) {
		$start = ($page - 1) * $pagesize;
		$modloglist = $this->index_fetch(array('uid'=>$uid), array('logid'=>0), $start, $pagesize);
		foreach($modloglist as &$modlog) {
			$this->format($modlog);
		}
		return $modloglist;
	}
	
	// 用来显示给用户
	public function format(&$modlog) {
		$arr = array('digest'=>'加精华', 'undigest'=>'取消精华', 'top'=>'置顶', 'untop'=>'取消置顶', 'delete'=>'删除', 'move'=>'移动', 'type'=>'主题分类');
		$modlog['action_fmt'] = $arr[$modlog['action']];
		$modlog['dateline_fmt'] = misc::humandate($modlog['dateline']);
		$modlog['forumname'] = $_SERVER['miscarr']['forum'][$modlog['fid']];
		$modlog['thread'] = $this->thread->read($modlog['fid'], $modlog['tid']);
	}
}
?>