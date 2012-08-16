<?php

/*
 * Copyright (C) xiuno.com
 */

class thread_type extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'thread_type';
		$this->primarykey = array('typeid');
		$this->maxcol = 'typeid';
		
	}
	
	public function create($arr) {
		$arr['typeid'] = $this->maxid('+1');
		if($this->set($arr['typeid'], $arr)) {
			$this->count('+1');
			return $arr['typeid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($typeid, $arr) {
		return $this->set($typeid, $arr);
	}
	
	// 对 tid +1 -1
	public function count_threads($typeid, $n) {
		$arr = $this->read($typeid);
		if(empty($arr)) return;
		$arr['threads'] += $n;
		$this->update($typeid, $arr);
	}
	
	public function read($typeid) {
		return $this->get($typeid);
	}

	public function _delete($typeid) {
		$return = $this->delete($typeid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 返回简单格式 typeid=>typename
	public function get_list_by_fid($fid) {
		$typelist = $this->index_fetch(array('fid'=>$fid), array(), 0, 1000);
		misc::arrlist_multisort($typelist, 'rank', FALSE);
		$arr = array();
		foreach($typelist as $type) {
			$arr[$type['typeid']] = $type['typename'];
		}
		return $arr;
	}
	
	// 返回全格式 typeid=>array()
	public function get_typelist_by_fid($fid) {
		$typelist = $this->index_fetch(array('fid'=>$fid), array(), 0, 1000);
		misc::arrlist_multisort($typelist, 'rank', FALSE);
		$arr = array();
		foreach($typelist as $type) {
			$arr[$type['typeid']] = $type;
		}
		return $arr;
	}
	
	
	// 检查 typeid 是否合法，防止外部构造非法typeid, 虽然都是int，防止notice
	public function get_typename(&$typeid, $fid) {
		$type = $this->read($typeid);
		if(empty($type) || $type['fid'] != $fid) $typeid = 0;
		return $type['typename'];
	}

	
	// ------------------> 杂项
	
	// 用来显示给用户
	public function format(&$type) {
		
	}
}
?>