<?php

/*
 * Copyright (C) xiuno.com
 */

class medal extends base_model {
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal';
		$this->primarykey = array('medalId');
		$this->maxcol = 'medalId';
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['medalId'] = $this->maxid('+1');
		if($this->set($arr['medalId'], $arr)) {
			$this->count('+1');
			return $arr['medalId'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($medalId, $arr) {
		return $this->set($medalId, $arr);
	}
	
	public function read($medalId) {
		return $this->get($medalId);
	}

	public function _delete($medalId) {
		$return = $this->delete($medalId);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
}
?>