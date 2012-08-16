<?php

/*
 * Copyright (C) xiuno.com
 */

class friendlink extends base_model {
	
	public $typearr = array(0=>'文字连接', 1=>'图片链接');
	
	function __construct() {
		parent::__construct();
		$this->table = 'friendlink';
		$this->primarykey = array('linkid');
		$this->maxcol = 'linkid';
		
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['linkid'] = $this->maxid('+1');
		if($this->set($arr['linkid'], $arr)) {
			$this->count('+1');
			return $arr['linkid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($linkid, $arr) {
		return $this->set($linkid, $arr);
	}
	
	public function read($linkid) {
		return $this->get($linkid);
	}

	public function _delete($linkid) {
		$return = $this->delete($linkid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// ------------------> 杂项
	
	// 用来显示给用户
	public function format(&$friendlink) {
		$friendlink['typeword']  = $this->typearr[$friendlink['type']];
		$friendlink['logourl']  = $friendlink['logo'] ? $this->conf['upload_url'].$friendlink['logo'] : '';
	}
}
?>