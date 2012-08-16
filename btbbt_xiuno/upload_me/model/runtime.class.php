<?php

/*
 * Copyright (C) xiuno.com
 */

// runtime 高速运行产生的数据，如果DB压力大，可以独立成服务，此表暂时只有一条数据。
class runtime extends base_model {
	public $data = array();		// 专门用来存储 bbs
	private $changed = FALSE;	// 专门用来存储 bbs changed
	function __construct() {
		parent::__construct();
		$this->table = 'runtime';
		$this->primarykey = array('k');
	}
	
	function __destruct() {
		if($this->changed) {
			$this->save_bbs();
		}
	}
	
	// threads, posts, users, todayposts, todayusers, newuid, newusername, cron_1_next_time, cron_2_next_time, toptids, cronlock
	public function read($k) {
		$arr = $this->get($k);
		return !empty($arr) ? $arr['v'] : '';
	}
	
	public function update($k, $v) {
		$v = array(
			'k'=>$k,
			'v'=>$v
		);
		return $this->set($k, $v);
	}
	
	// 合并读取，一次读取多个，增加效率
	public function &read_bbs() {
		$s = $this->read('bbs');
		$this->data = $this->unserialize_bbs($s);
		return $this->data;
	}
	
	public function update_bbs($k, $v) {
		if($v && is_string($v) && ($v[0] == '+' || $v[0] == '-')) {
			$v = intval($v);
			if($v != 0) {
				$this->data[$k] += $v;
				$this->changed = TRUE;
			}
		} else {
			$this->data[$k] = $v;
			$this->changed = TRUE;
		}
	}
	
	// 在析构函数里调用
	public function save_bbs() {
		$s = $this->serialize_bbs($this->data);
		return $this->update('bbs', $s);
	}
	
	// 读取 runtime key
	public function serialize_bbs($arr) {
		$arr['newusername'] = empty($arr['newusername']) ? '' : str_replace('|', '', $arr['newusername']);
		$s = implode("|", $arr);
		return $s;
	}
	
	// 只要 db 重新启动，读取到的 memory 表数据为空，则重新初始化 runtime
	public function unserialize_bbs($s) {
		if(!empty($s)) {
			$arr = explode("|", $s);
			$r = array (
				'onlines'=>$arr[0],
				'posts'=>$arr[1],
				'threads'=>$arr[2],
				'users'=>$arr[3],
				'todayposts'=>$arr[4],
				'todayusers'=>$arr[5],
				'cron_1_next_time'=>$arr[6],
				'cron_2_next_time'=>$arr[7],
				'newuid'=>$arr[8],
				'newusername'=>$arr[9],
				'toptids'=>$arr[10],
			);
		} else {
			$toptids = $this->kv->get('toptids');
			$r = array (
				'onlines'=>$this->online->count(),
				'posts'=>$this->post->count(),
				'threads'=>$this->thread->count(),
				'users'=>$this->user->count(),
				'todayposts'=>0,
				'todayusers'=>0,
				'cron_1_next_time'=>0,
				'cron_2_next_time'=>0,
				'newuid'=>0,
				'newusername'=>'',
				'toptids'=>$toptids,
			);
		}
		return $r;
	}

	public function _delete($k) {
		return $this->delete($k);
	}
}
?>