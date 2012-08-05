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
				
				if ($medal['receivetype'] == 1) {
					$v['receivetype'] = '自动发放';
				} elseif ($v['receivetype'] == 2) {
					$v['receivetype'] = '手动发放';
				} else {
					$v['receivetype'] = '未定义';
				}
			}
		}
	}
}
?>