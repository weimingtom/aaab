<?php

/*
 * Copyright (C) xiuno.com
 */

class medal_user extends base_model {
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal_user';
		$this->primarykey = array('muid');
		$this->maxcol = 'muid';
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['muid'] = $this->maxid('+1');
		if($this->set($arr['muid'], $arr)) {
			$this->count('+1');
			return $arr['muid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($muid, $arr) {
		return $this->set($muid, $arr);
	}
	
	public function read($muid) {
		return $this->get($muid);
	}

	public function _delete($muid) {
		$return = $this->delete($muid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_medaluser_by_uid_medalid($uid, $medalid) {
		// 根据非主键取数据
		$medaluser = $this->index_fetch($cond = array('uid'=>$uid, 'medalid'=>$medalid), $orderby = array(), $start = 0, $limit = 1);
		return $medaluser ? array_pop($medaluser) : array();
	}
	
	public function get_user_medal($uid) {
		$medal_user_list = $this->index_fetch(array('uid'=>$uid, 'isapply'=>1), array('muid'=>-1));
		$s = '';
		foreach ($medal_user_list as $v) {
			$medal = $this->medal->get($v['medalid']);
			if ($medal) {
				$s .= '<img src="'.$this->conf['app_url'].'upload/'.$medal['picture'].'" title="'.$medal['medalname'].'" style="margin:3px"/>';
			}
		}
		return $s;
	}
	
	public function medaluserlist_form(&$medaluserlist) {
		foreach ($medaluserlist as &$v) {
			$medal = $this->medal->get($v['medalid']);
			if ($medal) {
				$v['medalname'] = $medal['medalname'];
				$v['picture'] = $medal['picture'] ? $this->conf['upload_url'].$medal['picture'] : '';
				$v['createdtime'] = date('Y-m-d H:i:s', $v['createdtime']);
				$v['expiredtime'] = $v['expiredtime'] ? '有效期至 '.date('Y-m-d', $v['expiredtime']) : '永久有效';
				
				$v['receivetype'] = $this->medal->receivetypelist[$medal['receivetype']];
			}
		}
	}
	
	//--------------------------------------------------------------------以下用于计划任务
	
	// 用于计划任务，清除过期的用户勋章
	public function cron_clear_expiredtime() {
		$medal_userlist = $this->index_fetch(array('expiredtime'=>array('<'=>time())));
		foreach ($medal_userlist as $medal_user) {
			$this->_delete($medal_user['muid']);
		}
	}
	
	// 计划任务，勋章发放
	public function cron_medal_grant() {
		foreach ($this->medal->autograntlist as $k=>$v) {
			switch ($k){
				case 1: // 积分大于5000分
					$this->user_grant($k, array('credits'=>array('>'=>500)));
					break;
				case 2: // 发帖数量超过100
					$this->user_grant($k, array('threads'=>array('>'=>100)));
					break;
				case 3: // 回帖数量超过1000
					$this->user_grant($k, array('posts'=>array('>'=>1000)));
					break;
				case 4: // 精华帖子数量超过50
					$this->user_grant($k, array('digests'=>array('>'=>1000)));
					break;
			}
		}
	}
	
	private function user_grant($grantid, $cond) {
		$medal = $this->medal->get_medal(array('autogrant'=>$grantid));
		if ($medal) {
			$userlist = $this->user_active->index_fetch($cond);
			if ($userlist) {
				foreach ($userlist as $user) {
					$arr = array(
						'medalid'=>$medal['medalid'],
						'uid'=>$user['uid'],
						'username'=>$user['username'],
						'receivetype'=>$medal['receivetype'],
						'autogrant'=>$k,
						'expiredtime'=>time() + $medal['expiredtime'] * 86400,
						'isapply'=>1,
					);
					$this->create($arr);
					$this->user_active->_delete($user['uid']);
				}
			} 
		}
	}
}
?>