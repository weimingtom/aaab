<?php

/*
 * Copyright (C) xiuno.com
 */

// 简单方便的 key - value 格式的存储
class kv extends base_model {
	function __construct() {
		parent::__construct();
		$this->table = 'kv';
		$this->primarykey = array('k');
	}
	
	public function get($id1, $id2 = FALSE, $id3 = FALSE) {
		return $this->read($id1);
	}
	
	public function set($id1, $id2 = FALSE, $id3 = FALSE, $id4 = FALSE) {
		$this->update($id1, $id2, $id3);
	}
	
	public function read($k) {
		$arr = parent::get($k);
		if(!empty($arr['expiry']) && $_SERVER['time'] > $arr['expiry']) {
			return FALSE;
		}
		return !empty($arr) ? $arr['v'] : '';
	}
	
	public function update($k, $v, $expiry = 0) {
		$expiry && $expiry = $expiry + $_SERVER['time'];
		$v = array (
			'k'=>$k,
			'v'=>$v,
			'expiry'=>$expiry
		);
		return parent::set($k, $v);
	}
	
	public function _delete($k) {
		return $this->delete($k);
	}
}
?>