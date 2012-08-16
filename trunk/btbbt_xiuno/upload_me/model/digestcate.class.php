<?php

/*
 * Copyright (C) xiuno.com
 */

class digestcate extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'digestcate';
		$this->primarykey = array('cateid');
		$this->maxcol = 'cateid';
	}
	
	/*
		$arr = array(
			'cateid'=>'1',
			'parentid'=>'0',
			'name'=>'bbb',
			'threads'=>'0',
			'rank'=>'0',
			'uid'=>'0',
		);
	*/
	public function create($arr) {
		$arr['cateid'] = $this->maxid('+1');
		if($this->set($arr['cateid'], $arr)) {
			$this->count('+1');
			return $arr['cateid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($cateid, $arr) {
		return $this->set($cateid, $arr);
	}
	
	public function read($cateid) {
		return $this->get($cateid);
	}

	public function _delete($cateid) {
		$return = $this->delete($cateid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// ------------------> 杂项
	public function check_name(&$name) {
		if(empty($name)) {
			return '分类名称不能为空。';
		}
		if(utf8::strlen($name) > 12) {
			return '标题不能超过 12 字符，当前长度：'.utf8::strlen($name);
		}
		return '';
	}
}
?>