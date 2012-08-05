<?php

/*
 * Copyright (C) xiuno.com
 */

class medal extends base_model {
		
	function __construct() {
		parent::__construct();
		$this->table = 'medal';
		$this->primarykey = array('medalid');
		$this->maxcol = 'medalid';
	}
	
	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['medalid'] = $this->maxid('+1');
		if($this->set($arr['medalid'], $arr)) {
			$this->count('+1');
			return $arr['medalid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($medalid, $arr) {
		return $this->set($medalid, $arr);
	}
	
	public function read($medalid) {
		return $this->get($medalid);
	}

	public function _delete($medalid) {
		$return = $this->delete($medalid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// 获取勋章所有列表
	public function get_medallist() {
		$medallist = $this->medal->index_fetch(array(), array('seq'=>1));
		$this->medallist_form($medallist);
		return $medallist;
	}
	
	// 格式化数据
	public function medallist_form(&$medallist) {
		foreach ($medallist as &$v) {
			$v['picture'] = $this->conf['upload_url'].$v['picture'];
			if ($v['receivetype'] == 1) {
				$v['receivetype'] = '自动发放';
			} elseif ($v['receivetype'] == 2) {
				$v['receivetype'] = '手动发放';
			} else {
				$v['receivetype'] = '未定义';
			}
		}
		return $medallist;
	}
}
?>