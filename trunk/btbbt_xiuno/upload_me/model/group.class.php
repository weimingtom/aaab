<?php

/*
 * Copyright (C) xiuno.com
 */

class group extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'group';
		$this->primarykey = array('groupid');
		$this->maxcol = 'groupid';
	}
	
	public function create($arr, $groupid = FALSE) {
		if($groupid === FALSE) {
			$groupid = $arr['groupid'] = $this->maxid('+1');
		}
		if($this->set($groupid, $arr)) {
			$this->count('+1');
			return array('groupid'=>$groupid);
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($groupid, $arr) {
		return $this->set($groupid, $arr);
	}
	
	public function read($groupid) {
		return $this->get($groupid);
	}
	
	// 取得注册用户的 groupid, 和0
	public function get_grouplist() {
		$usergroup = array();
		$usergroup[0] = $this->get(0);
		$usergroup = $this->index_fetch(array(), array('groupid'=>1), 0, 1000);
		return $usergroup;
	}
	
	public function groupid_to_name($groupid) {
		$group = $this->get($groupid);
		return $group['name'];
	}
	
	public function _delete($groupid) {
		$return = $this->delete($groupid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_groupid_by_credits($groupid, $credits) {
		// 根据用户组积分范围升级
		if($groupid > 10) {
			$grouplist = $this->get_grouplist();
			foreach($grouplist as $group) {
				if($group['groupid'] < 11) continue;
				if($credits >= $group['creditsfrom'] && $credits < $group['creditsto']) {
					return $group['groupid'];
				}
			}
		}
		return $groupid;
	}
	
	public function check_name(&$name) {
		if(empty($name)) {
			return '用户组名称不能为空。';
		}
		return '';
	}
	
	public function check_creditsfrom(&$creditsfrom) {
		if(empty($creditsfrom)) {
			return '起始积分不能为空。';
		}
		return '';
	}
	
	public function check_creditsto(&$creditsto) {
		if(empty($creditsto)) {
			return '截止积分不能为空。';
		}
		return '';
	}
	
	public function check_upfloors($v) {
		if($v >= 0 && $v < 11) {
			return '';
		} else {
			return '每次顶起的楼层必须为 0-10 之间，0为顶到最前面。';
		}
	}
	
	// 用来显示给用户
	public function format(&$group) {
		// format data here.
	}
}
?>