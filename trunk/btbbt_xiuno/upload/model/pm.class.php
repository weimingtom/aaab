<?php

/*
 * Copyright (C) xiuno.com
 */

class pm extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'pm';
		$this->primarykey = array('pmid');
		$this->maxcol = 'pmid';
		$this->conf['cache']['enable'] = FALSE;	// 关闭 Memcached，短消息直接走MYSQL
	}
	
	function read($pmid) {
		return $this->get($pmid);
	}
	
	// 带有关联关系的创建，uid1 为创建者, $uid2 为接受者
	public function xcreate($uid1, $uid2, $username1, $username2, $message) {
		if(empty($uid1) || empty($uid2)) {
			return FALSE;
		}
		$senduid = $uid1;
		$recvuid = $uid2;
		$recvuser = $this->user->read($recvuid);
		if(empty($recvuser)) {
			return FALSE;
		}
		
		// 交换变量，最小的在前。
		if($uid1 > $uid2) {
			$t = $uid1; $uid1 = $uid2; $uid2 = $t;
			$t = $username1; $username1 = $username2; $username2 = $t;
		}
		
		// pmcount.count++
		$pmcount = $this->pmcount->read($uid1, $uid2);
		if(empty($pmcount)) {
			$pmcount = array(
				'uid1'=>$uid1,
				'uid2'=>$uid2,
				'count'=>0,
				'dateline'=>$_SERVER['time'],
			);
			$page = 1;	
		} else {
			$count = $pmcount ? $pmcount['count'] : 0;
			$pagesize = 20;
			$page = ceil(($count + 1) / $pagesize);
		}
		$pmcount['count']++;
		$pmcount['dateline'] = $_SERVER['time'];
		$this->pmcount->update($uid1, $uid2, $pmcount);
		
		// pm
		$pm = array(
			'uid1'=>$uid1,
			'uid2'=>$uid2,
			'uid'=>$senduid,
			'username1'=>$username1,
			'username2'=>$username2,
			'message'=>$message,
			'dateline'=>$_SERVER['time'],
			'page'=>$page
		);
		$pmid = $this->create($pm);
		$pm['pmid'] = $pmid;
		
		// pmnew.count++
		$pmnew = $this->pmnew->read($recvuid, $senduid);
		if(empty($pmnew)) {
			$pmnew = array(
				'recvuid'=>$recvuid,
				'senduid'=>$senduid,
				'count'=>0,
				'dateline'=>$_SERVER['time'],
			);
		}
		
		// 如果为两人的某轮第一条短消息
		if($pmnew['count'] == 0) {
			// user.newpms
			$recvuser['newpms']++;
			$this->user->update($recvuid, $recvuser);
		}
		$pmnew['count']++;
		$pmnew['dateline'] = $_SERVER['time'];
	
		$this->pmnew->update($recvuid, $senduid, $pmnew);
		return $pm;
	}
	
	/*
		$arr = array(
			'uid1'=>1,
			'uid2'=>2,
			'username1'=>'admin',
			'username2'=>'test',
			'message'=>'this is a message',
			'page'=>1
		);
	
	*/
	public function create($arr) {
		$arr['pmid'] = $this->maxid('+1');
		$arr['dateline'] = $_SERVER['time'];
		if($this->set($arr['pmid'], $arr)) {
			$this->count('+1');
			return $arr['pmid'];
		} else {
			$this->maxid('-1');
			return 0;
		}
	}
	
	// 标记已经读过
	public function markread($senduid, $recvuid) {
		$pmnew = $this->pmnew->read($recvuid, $senduid);
		if($pmnew['count'] > 0) {
			// pmnew
			$pmnew['count'] = 0;
			$this->pmnew->update($recvuid, $senduid, $pmnew);
			
			// pmcount 不变
			/*
			if($recvuid > $senduid) {
				$uid1 = $senduid; $uid2 = $recvuid;
			} else {
				$uid1 = $recvuid; $uid2 = $senduid;
			}
			$pmcount = $this->pmcount->read($uid1, $uid2);
			$pmcount['count'] = 0;
			$this->pmcount->update($uid1, $uid2, $pmcount);
			*/
			
			// user.newpms
			$user = $this->user->read($recvuid);
			$user['newpms']--;
			$this->user->update($recvuid, $user);
		}
		// pmnew.count = 0
		// pmcount.count = 0
		// user.newpms = 0
	}
	
	/*
	public function update($pmid, $arr) {
		return $this->set($pmid, $arr);
	}
	*/
	
	public function get_list_by_uid($uid1, $uid2, $page) {
		if($uid1 > $uid2) {
			$t = $uid1; $uid1 = $uid2; $uid2 = $t;
		}
		$pmlist = $this->index_fetch(array('uid1'=>$uid1, 'uid2'=>$uid2, 'page'=>$page), array(), 0, 100);
		foreach($pmlist as &$pm) {
			if(!empty($pm)) $this->format($pm);
		}
		misc::arrlist_multisort($pmlist, 'pmid', TRUE);
		return $pmlist;
	}
	
	public function format(&$pm) {
		$pm['dateline'] = misc::humandate($pm['dateline']);
	}

	// 关联删除，先 markread, 再删除。
	public function xdelete($pmid) {
		// 总数--
		$this->_delete($pmid);
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