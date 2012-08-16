<?php

/*
 * Copyright (C) xiuno.com
 */

class digest extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'digest';
		$this->primarykey = array('digestid');
		$this->maxcol = 'digestid';
	}
	
	public function create($arr) {
		$arr['digestid'] = $this->maxid('+1');
		if($this->set($arr['digestid'], $arr)) {
			$this->count('+1');
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function update($digestid, $arr) {
		return $this->set($digestid, $arr);
	}
	
	public function read($digestid) {
		return $this->get($digestid);
	}

	public function _delete($digestid) {
		$return = $this->delete($digestid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function delete_by_fid_tid($fid, $tid) {
		$arrlist = $this->index_fetch(array('fid'=>$fid, 'tid'=>$tid), array(), 0, 100);
		foreach($arrlist as $arr) {
			$this->_delete($arr['digestid']);
		}
	}
	
	/*
	// 关联删除，接受批量
	public function xdelete($arrlist) {
		foreach($arrlist as $arr) {
			$fid = $arr['fid'];
			$tid = $arr['tid'];
		}
	}
	
	// 批量的请
	public function xdelete_after($arg) {
	
	}
	
	// 关联添加
	public function xcreate($fid, $tid) {
	
	}
	*/
	
	public function delete_by_cateid($cateid) {
		$digestlist = $this->get_list_by_cateid($cateid, 1, 1000);
		foreach($digestlist as $digest) {
			// 扣除积分？
			$this->_delete($digest['digetstid']);
		}
	}
	
	public function get_list_by_cateid($cateid, $page, $pagesize) {
		return $this->index_fetch(array('cateid'=>$cateid), array(), ($page - 1) * $pagesize, $pagesize);
	}
	
	public function get_list_by_fid_tid($fid, $tid) {
		return $this->index_fetch(array('fid'=>$fid, 'tid'=>$tid), array(), 0, 1000);
	}
	
	// 去重
	public function get_newlist($limit) {
		$limit2 = $limit * 3;	// 去重
		$digestlist = $this->index_fetch(array(), array('digestid'=>-1), 0, $limit2);
		$forums = $_SERVER['miscarr']['forum'];
		$newlist = array();
		foreach($digestlist as $k=>$digest) {
			if(isset($newlist[$digest['tid']])) continue;
			$digest['forumname'] = $forums[$digest['fid']];
			$newlist[$digest['tid']] = $digest;
		}
		$newlist = array_slice($newlist, 0, $limit);
		return $newlist;
	}
	
}
?>