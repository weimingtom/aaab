<?php

/*
 * Copyright (C) xiuno.com
 */

define('MAX_RECENT_USERS', 50);	// 最近联系的用户保留个数

class pmnew extends base_model{
	
	function __construct() {
		parent::__construct();
		$this->table = 'pmnew';
		$this->primarykey = array('recvuid', 'senduid');
		$this->conf['cache']['enable'] = FALSE;	// 关闭 Memcached，短消息直接走MYSQL
	}

	/*
		$arr = array(
			'recvuid'=>1,
			'senduid'=>2,
			'count'=>100,
			'dateline'=>1234567890,
		);
	*/
	public function create($arr) {
		if($this->set($arr['recvuid'], $arr['senduid'], $arr)) {
			$this->count('+1');
			return 1;
		} else {
			return 0;
		}
	}
	
	public function update($recvuid, $senduid, $arr) {
		$this->set($recvuid, $senduid, $arr);
	}
	
	public function read($recvuid, $senduid) {
		return $this->get($recvuid, $senduid);
	}
	
	// 获取最新的短消息
	public function get_list_by_uid($uid) {
		$arrlist = $this->index_fetch(array('recvuid'=>$uid, 'count'=>array('>'=>0)), array(), 0, 100);
		misc::arrlist_multisort($arrlist, 'dateline', FALSE);
		return $arrlist;
	}
	
	// 取5个最新的联系人
	public function get_new_userlist($uid) {
		$arrlist = $this->index_fetch(array('recvuid'=>$uid, 'count'=>array('>'=>0)), array(), 0, 5);
		misc::arrlist_multisort($arrlist, 'dateline', FALSE);
		
		$userlist = array();
		foreach($arrlist as $v) {
			$user = $this->user->read($v['senduid']);
			$this->user->format($user);
			$user2 = array(
				'uid'=>$user['uid'],
				'username'=>$user['username'],
				'avatar'=>$user['avatar'],
				'avatar_small'=>$user['avatar_small'],
				'newpms'=>$v['count'],
			);
			$userlist[$v['senduid']] = $user2;
		}
		return $userlist;
	}
	
	// 取最近联系人，默认40个
	public function get_recent_userlist($uid) {
		$arrlist = $this->index_fetch(array('recvuid'=>$uid), array(), 0, MAX_RECENT_USERS + 100);
		misc::arrlist_multisort($arrlist, 'dateline', FALSE);
		
		// 清理过多的最近联系人。
		if(count($arrlist) >= MAX_RECENT_USERS + 100) {
			$dellist = array_slice($arrlist, MAX_RECENT_USERS);
			foreach($dellist as $v) {
				$this->_delete($v['recvuid'], $v['senduid']);
			}
			$arrlist = array_slice($arrlist, 0, MAX_RECENT_USERS);
		}
		
		$userlist = array();
		foreach($arrlist as $v) {
			$user = $this->user->read($v['senduid']);
			$this->user->format($user);
			$userlist[$v['senduid']] = $user;
		}
		return $userlist;
	}
	
	public function _delete($recvuid, $senduid) {
		$return = $this->delete($recvuid, $senduid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
}
?>