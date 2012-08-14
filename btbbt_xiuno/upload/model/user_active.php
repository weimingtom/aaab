<?php

/*
 * Copyright (C) xiuno.com
 */

class user_active extends base_model {
		
	function __construct() {
		parent::__construct();
		$this->table = 'user_active';
		$this->primarykey = array('uid');
		$this->maxcol = 'uid';
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['uid'] = $this->maxid('+1');
		if($this->set($arr['uid'], $arr)) {
			$this->count('+1');
			return $arr['uid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($uid, $arr) {
		return $this->set($uid, $arr);
	}
	
	public function read($uid) {
		return $this->get($uid);
	}

	public function _delete($uid) {
		$return = $this->delete($uid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 计划任务，移除30天内的以外的活跃用户
	public function cron_clearusers() {
		$time = strtotime('-30 day');
		$userlist = $this->index_fetch(array('activetime'=>array('<'=>$time)));
		foreach ($userlist as $user) {
			$this->_delete($user['uid']);
			$this->count('-1');
		}
	}
}
?>