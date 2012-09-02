<?php

/*
 * Copyright (C) xiuno.com
 */

class online extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'online';
		$this->primarykey = array('sid');
	}
	
	// 重载, 因为 online 表为 Memory 类型，重启后消失，count 不准确。
	public function count($val = FALSE) {
		return $this->native_count();
	}
	
	public function create($arr) {
		$sid = $arr['sid'];
		$online = $this->read($sid);
		if(empty($online)) {
			$this->runtime->update_bbs('onlines', '+1');
		}
		if($this->set($sid, $arr)) {
			return $sid;
		}
		return NULL;
	}
	
	public function update($sid, $arr) {
		return $this->set($sid, $arr);
	}
	
	public function read($sid) {
		return $this->get($sid);
	}
	
	// 最多400个会员，再多没啥意义了，耗费带宽
	public function get_onlinelist($limit = 400) {
		$onlinelist = $this->index_fetch(array('uid'=>array('>'=>0)), array(), 0, $limit);
		return $onlinelist;
	}
	
	public function _delete($sid) {
		$return = $this->delete($sid);
		if($return) {
			$this->runtime->update_bbs('onlines', '-1');
		}
		return $return;
	}
	
	// 用来显示给用户
	public function format(&$online) {
		// format data here.
	}
	
	// cron_1_next_time，每隔5分钟执行一次，首页缓存也会被刷新。
	public function gc() {
		// 15 分钟算离线
		$expiry = $_SERVER['time'] - $this->conf['online_hold_time'];
		
		// 典型的不依赖求和的分页算法。
		// 更新 2000*40 = 8w 在线用户, mysql处理极限大约在每秒1w条，8秒快要超时了。
		$page = 1;
		$pagesize = 2000;
		$n = 0;
		while($page < 40) {
			$onlinelist = $this->index_fetch(array('lastvisit'=>array('<'=>$expiry)), array(), ($page - 1) * $pagesize, $pagesize);
			$page++;
			foreach($onlinelist as $online) {
				$online && $this->delete($online['sid']);
				$n++;
			}
			if(count($onlinelist) < $pagesize) break; // 如果不足，则停止
		}
		
		// 搜索引擎等无cookie浏览者会导致这种情况，需要处理，否则在线人数会变成负数。
		if($n > $this->conf['runtime']['onlines']) {
			$n = $this->online->count();
		}
		$this->runtime->update_bbs('onlines', "-$n");
		
	}
}
?>