<?php

/*
 * Copyright (C) xiuno.com
 */

class pay extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'pay';
		$this->primarykey = array('payid');
		$this->maxcol = 'payid';
		
	}
	
	public function create($arr) {
		$arr['payid'] = $this->maxid('+1');
		if($this->set($arr['payid'], $arr)) {
			$this->count('+1');
			return $arr['payid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($payid, $arr) {
		return $this->set($payid, $arr);
	}
	
	public function read($payid) {
		return $this->get($payid);
	}

	public function _delete($payid) {
		$return = $this->delete($payid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_list_by_uid($uid, $page, $pagesize) {
		$start = ($page - 1) * $pagesize;
		$paylist = $this->index_fetch(array('uid'=>$uid), array(), $start, $pagesize);
		foreach($paylist as &$pay) {
			$this->format($pay);
		}
		misc::arrlist_multisort($paylist, 'payid', TRUE);
		return $paylist;
	}
	
	// 用来显示给用户
	public function format(&$pay) {
		$paytypes = array('线下支付', '支付宝', '网银');
		$pay['dateline_fmt'] = misc::date($pay['dateline'], 'Y-n-j H:i', $this->conf['timeoffset']);
		$pay['paytype_fmt'] = $paytypes[$pay['paytype']];
		$pay['status_fmt'] = $pay['status'] ? '支付成功' : '未支付';
	}
}
?>