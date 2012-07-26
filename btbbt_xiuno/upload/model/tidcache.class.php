<?php

/*
 * Copyright (C) xiuno.com
 */

class tidcache extends base_model {
	
	function __construct() {
		parent::__construct();
	}

	/*
	public function init($fid) {
		if(!$this->conf['cache_tid']) {
			return false;
		}
		$filename = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		touch($filename);
	}
	*/

	public function get_tidarr($fid, $start, $limit) {
		if(!$this->conf['cache_tid'] || !$limit) {
			return array();
		}
		$filename = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		
		// 此处检测消耗一点IO，多余？
		if(!is_file($filename)) {
			$this->rebuild($fid);
		}
		
		$fp = fopen($filename, 'rb');
		if(!$fp) {
			return array();
		}
		fseek($fp, $start * 4);
		$data = fread($fp, $limit * 4);
		$tidarr = (array)unpack("L*", $data);	// 数组偏移从 1 开始
		$tidarr = array_values($tidarr);
		return $tidarr;
	}

	// 只针对一级缓存，考虑顶贴楼层，每次增加的楼层，by 用户组中设置 $group['upfloors']
	public function add_tid($fid, $tid, $upfloors = 0) {
		if(!$this->conf['cache_tid']) {
			return false;
		}

		// 获取缓存数据
		$filename = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		if(!is_file($filename)) {
			$this->rebuild($fid);
		}
		$s = file_get_contents($filename);

		// 转化为数组,// 寻找是否在其中
		$arr = (array)unpack("L*", $s);
		$arr = array_merge($arr);	// 去掉 key 值, unpack 出来的数组起始值为 1
		$pos = array_search($tid, $arr);
		if($upfloors == 0) {
			if($pos === FALSE) {
				array_unshift($arr, $tid);
			} else {
				// 添加新元素
				unset($arr[$pos]);
				array_unshift($arr, $tid);
			}
		} else {
			// 如果在已有（回复帖子）
			if($pos !== FALSE && $pos != 0) {
				// 前移
				$frontpos = $pos - $upfloors;
				if($frontpos > 0) {
					$arr = array_merge(array_slice($arr, 0, $frontpos) , array($tid) ,  array_slice($arr, $frontpos, $upfloors) , array_slice($arr, $pos + 1));
				} else {
					$frontpos = max(0, $frontpos);
					$arr = array_merge(array($tid) ,  array_slice($arr, $frontpos, $upfloors) , array_slice($arr, $pos + 1));
				}
			} else {
				// todo: 可能在第十一页，暂时不管
			}
			
		}
		$this->save_tids($arr, $filename);
	}

	// 删除一个，会漏掉
	public function delete_tid($fid, $tid) {
		if(!$this->conf['cache_tid']) {
			return false;
		}
		// 获取缓存数据
		$filename = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		if(!is_file($filename)) return;
		$s = file_get_contents($filename);

		// 转化为数组
		$arr = (array)unpack("L*", $s);

		$pos = array_search($tid, $arr);
		if($pos !== FALSE) {
			unset($arr[$pos]);
		}
		
		// 写入缓存
		$this->save_tids($arr, $filename);
	}

	// 批量删除
	public function delete_tids($fid, $tids) {
		if(!$this->conf['cache_tid']) {
			return false;
		}
		if(!is_file($filename)) {
			$this->rebuild($fid);
		}
		// 获取缓存数据
		$filename = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		$s = file_get_contents($filename);

		// 转化为数组
		$arr = (array)unpack("L*", $s);

		// 删除其中的元素
		foreach($arr as $k=>$tid) {
			if(in_array($tid, $tids)) {
				unset($arr[$k]);
			}
		}

		// 写入缓存
		$this->save_tids($arr, $filename);
	}

	public function rebuild($fid) {
		if(!$this->conf['cache_tid']) {
			return false;
		}
		$filename = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		$tids = $this->thread->index_fetch_id(array('fid'=>$fid), array('floortime'=>-1), 0, $this->conf['cache_tid_num']);
		$tidarr = array();
		foreach($tids as $key) {
			$arr = explode('-', $key);
			$tidarr[] = array_pop($arr);
		}
		$this->save_tids($tidarr, $filename);
	}

	public function rebuild_all() {
		if(!$this->conf['cache_tid']) {
			return false;
		}
		$forumlist = $this->forum->get_all_forum();
		foreach($forumlist as $forum) {
			if($forum['fup'] == 0) continue;
			$this->rebuild($forum['fid']);
		}
	}

	// 删除论坛时调用
	public function delete_cache($fid) {
		$file1 = $this->conf['tmp_path']."tidcache_{$fid}.txt";
		is_file($file1) && unlink($file1);
	}

	// 写入缓存
	private function save_tids($arr, $filename) {
		
		// 只保存 2000 个 tid
		if(count($arr) > $this->conf['cache_tid_num']) {
			array_pop($arr);
		}
		
		$tids = '';
		foreach($arr as $k=>$_tid) {
			$tids .= pack("L", $_tid);
		}
		file_put_contents($filename, $tids);
	}
}
?>