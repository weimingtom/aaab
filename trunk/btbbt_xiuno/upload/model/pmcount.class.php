<?php

/*
 * Copyright (C) xiuno.com
 */

class pmcount extends base_model{
	
	function __construct() {
		parent::__construct();
		$this->table = 'pmcount';
		$this->primarykey = array('uid1', 'uid2');
		$this->conf['cache']['enable'] = FALSE;	// 关闭 Memcached，短消息直接走MYSQL
	}

	/*
		$arr = array(
			'uid1'=>1,
			'uid2'=>2,
			'count'=>100,
			'news'=>0,
			'dateline'=>1234567890,
		);
	*/
	public function create($arr) {
		$uid1 = &$arr['uid1'];
		$uid2 = &$arr['uid2'];
		if($uid1 > $uid2) {
			$t = $uid1; $uid1 = $uid2; $uid2 = $t;
		}
		if($this->set($arr['uid1'], $arr['uid2'], $arr)) {
			$this->count('+1');
			return 1;
		} else {
			return 0;
		}
	}
	
	public function update($uid1, $uid2, $arr) {
		$this->set($uid1, $uid2, $arr);
	}
	
	public function read($uid1, $uid2) {
		if($uid1 > $uid2) {
			$t = $uid1; $uid1 = $uid2; $uid2 = $t;
		}
		return $this->get($uid1, $uid2);
	}
	
	public function _delete($pmid) {
		$return = $this->delete($pmid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
}
?>