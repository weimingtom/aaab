<?php

/*
 * Copyright (C) xiuno.com
 */

class attach_download extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'attach_download';
		$this->primarykey = array('uid' ,'aid');
		$this->maxcol = 'aid';
	}
	
	public function create($arr) {
		$uid = $arr['uid'];
		$aid = $arr['aid'];
		if($this->set($uid, $aid, $arr)) {
			return TRUE;
		} else {
			//$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($uid, $aid, $arr) {
		return $this->set($uid, $aid, $arr);
	}
	
	public function read($uid, $aid) {
		return $this->get($uid, $aid);
	}

	public function _delete($uid, $aid) {
		$return = $this->delete($uid, $aid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_list_by_aid($aid, $page = 1, $pagesize = 20) {
		$start = ($page -1) * $pagesize;
		$downlist = $this->index_fetch(array('aid'=>$aid), array(), $start, $pagesize);
		foreach($downlist as &$down) {
			$this->format($down);
		}
		misc::arrlist_multisort($downlist, 'aid', FALSE);
		return $downlist;
	}
	
	public function get_list_by_uid($uid, $page = 1, $pagesize = 20) {
		$start = ($page -1) * $pagesize;
		$downlist = $this->index_fetch(array('uid'=>$uid), array(), $start, $pagesize);
		foreach($downlist as &$down) {
			$this->format($down);
		}
		misc::arrlist_multisort($downlist, 'aid', FALSE);
		return $downlist;
	}
	
	public function get_list_by_uploaduid($uploaduid, $page = 1, $pagesize = 20) {
		$start = ($page -1) * $pagesize;
		$downlist = $this->index_fetch(array('uploaduid'=>$uploaduid), array('dateline'=>0), $start, $pagesize);
		foreach($downlist as &$down) {
			$this->format($down);
		}
		misc::arrlist_multisort($downlist, 'dateline', TRUE);
		return $downlist;
	}
	
	// 扣除积分？
	/*
	public function xdelete($fid, $pid) {
		$attachlist = $this->index_fetch(array('fid'=>$fid, 'pid'=>$pid), array(), 0, 10000);
		foreach($attachlist as $attach) {
			$filepath = $this->conf['upload_path'].$attach['filename'];
			is_file($filepath) && unlink($filepath);
			$this->_delete($attach['aid']);
		}
		return count($attachlist);
	}
	*/
	
	
	// 用来显示给用户
	public function format(&$down) {
		// format data here.
		$down['attach'] = $this->attach->read($down['aid']);
		$down['user'] = $this->user->read($down['uid']);
		$down['dateline_fmt'] = misc::humandate($down['dateline']);
		$this->attach->format($down['attach']);
	}

}
?>