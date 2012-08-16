<?php

/*
 * Copyright (C) xiuno.com
 */

class user_access extends base_model{
	
	function __construct() {
		parent::__construct();
		$this->table = 'user_access';
		$this->primarykey = array('uid');
		$this->maxcol = 'uid';
		$this->conf['cache']['enable'] = FALSE;
	}
	
	/*
	$arr = array(
		'uid'=>1,
		'allowread'=>0,
		'allowthread'=>0,
		'allowpost'=>0,
		'allowattach'=>0,
		'expiry'=>0
	);
	*/
	public function create($arr) {
		if($this->set($arr['uid'], $arr)) {
			$this->count('+1');
			return $arr['uid'];
		} else {
			$this->maxid('-1');
			return 0;
		}
	}
	
	// 初始化权限
	public function init($uid) {
		$arr = array(
			'uid'=>$uid,
			'allowread'=>1,
			'allowthread'=>1,
			'allowpost'=>1,
			'allowattach'=>1,
			'expiry'=>0
		);
		$user = $this->user->read($uid);
		$user['accesson'] = 1;
		$this->user->update($uid, $user);
		$this->create($arr);
	}
	
	public function update($uid, $arr) {
		// 如果都允许，则删除权限限定
		$user = $this->user->read($uid);
		if($arr['allowread'] && $arr['allowthread'] && $arr['allowpost'] && $arr['allowattach']) {
			$user['accesson'] = 0;
			$this->user->update($uid, $user);
			return $this->_delete($uid);
		} else {
			$user['accesson'] = 1;
			$this->user->update($uid, $user);
			return $this->set($uid, $arr);
		}
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
}
?>