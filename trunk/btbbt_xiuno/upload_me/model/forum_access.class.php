<?php

/*
 * Copyright (C) xiuno.com
 */

class forum_access extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'forum_access';
		$this->primarykey = array('fid', 'groupid');
		$this->maxcol = 'fid';
	}
	
	
	public function create($fid, $groupid, $arr) {
		if($this->set($fid, $groupid, $arr)) {
			$this->count('+1');
			return array('fid'=>$fid, 'groupid'=>$groupid);
		} else {
			return FALSE;
		}
	}
	
	public function update($fid, $groupid, $arr) {
		return $this->set($fid, $groupid, $arr);
	}
	
	public function read($fid, $groupid) {
		return $this->get($fid, $groupid);
	}

	public function _delete($fid, $groupid) {
		$return = $this->delete($fid, $groupid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function delete_by_fid($fid) {
		$accesslist = $this->get_list_by_fid($fid);
		foreach($accesslist as $access) {
			$this->_delete($access['fid'], $access['groupid']);
		}
		return TRUE;
	}
	
	public function get_list_by_fid($fid) {
		$arr = array();
		$accesslist = $this->index_fetch(array('fid' => $fid), array('groupid'=>1), 0, 1000);
		foreach($accesslist as $v) {
			$arr[$v['groupid']] = $v;
		}
		return $arr;
	}
	
	// 将游客调到最后一组
	/*
	public function judge_accesslist(&$accesslist) {
		list($access) = $accesslist;
		if($access['groupid'] == 0) {
			$access = array_shift($accesslist);
			array_push($accesslist, $access);
		}
	}
	*/
	
	public function set_default(&$accesslist, $grouplist) {
		foreach($grouplist as $group) {
			$access = &$accesslist[$group['groupid']];
			// guest groupid
			if($group['groupid'] == 0) {
				$access['allowread'] = 1;
				$access['allowpost'] = 0;
				$access['allowthread'] = 0;
				$access['allowattach'] = 0;
				$access['allowdown'] = 0;
			} else {
				$access['allowread'] = 1;
				$access['allowpost'] = 1;
				$access['allowthread'] = 1;
				$access['allowattach'] = 1;
				$access['allowdown'] = 1;
			}
		}
	}
	
	// 用来显示给用户
	public function format(&$forum_access) {
		// format data here.
	}
}
?>