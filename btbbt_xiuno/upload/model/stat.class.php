<?php

/*
 * Copyright (C) xiuno.com
 */

class stat extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'stat';
		$this->primarykey = array('year', 'month', 'day');
		
	}

	public function create($arr) {
		if($this->set($arr['year'], $arr['month'], $arr['day'], $arr)) {
			$this->count('+1');
			return TRUE;
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($year, $month, $day, $arr) {
		return $this->set($year, $month, $day, $arr);
	}
	
	public function read($year, $month, $day) {
		return $this->get($year, $month, $day);
	}

	public function _delete($year, $month, $day) {
		$return = $this->delete($year, $month, $day);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 取时间段
	public function get_list_by_date_range($startdate, $enddate) {
		
	}
}
?>