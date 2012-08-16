<?php

/*
 * Copyright (C) xiuno.com
 */

class user_denglu extends base_model{
	
	function __construct() {
		parent::__construct();
		$this->table = 'user_denglu';
		$this->primarykey = array('muid');
	}
	
	public function create($arr) {
		if($this->set($arr['muid'], $arr)) {
			$this->count('+1');
			return $arr['uid'];
		} else {
			return 0;
		}
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
}
?>