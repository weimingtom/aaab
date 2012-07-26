<?php

/*
 * Copyright (C) xiuno.com
 */

class mcache extends base_model {
	
	private $vars;// 缓存已经加载的数据
	
	function __construct() {
		parent::__construct();
	}

	public function read($cachename, $arg = NULL) {
		$key = $cachename.'_'.$arg;
		if(isset($this->vars[$key])) {
			return $this->vars[$key];//避免重复加载。
		}
		
		$cachefile = $this->conf['tmp_path'].($arg ? "{$cachename}_{$arg}_cache.php" : "{$cachename}_cache.php");
		if(!is_file($cachefile)) {
			$r = $this->update($cachename, $arg);
			if(!$r) {
				return array();
			}
		}
		if(!($data = include $cachefile)) {
			throw new Exception('cache_model:'.$cachename.'does not exists');
		} else {
			$this->vars[$key] = $data;
			return $data;
		}
	}
	
	public function clear($cachename, $arg = NULL) {
		$cachefile = $this->conf['tmp_path'].($arg ? "{$cachename}_{$arg}_cache.php" : "{$cachename}_cache.php");
		return is_file($cachefile) && unlink($cachefile);
	}

	public function update($cachename, $arg) {
		$cachefile = $this->conf['tmp_path'].($arg ? "{$cachename}_{$arg}_cache.php" : "{$cachename}_cache.php");
		$method = "get_$cachename";
		if(method_exists($this, $method)) {
			$data = $this->$method($arg);
			if(empty($data)) {
				return array();// 强行返回，不保存到文件
			}
		} else {
			throw new Exception('cache_model: '.$method.' does not exists');
		}
		return $this->save($data, $cachefile);
	}
	
	private function save($var, $cachefile) {
		$s = "<?php\r\n";
		$s .= 'return '.var_export($var, TRUE).";";
		$s .= "\r\n?>";
		if(!($fp = fopen($cachefile, 'wb'))) {
			throw new Exception('cache_model: cache unwritable');
		}
		if(function_exists('flock') && !flock($fp, LOCK_EX)) {
			fclose($fp);
			return FALSE;
		}
		fwrite($fp, $s, strlen($s));
		fclose($fp);
		return TRUE;
	}
	
	// 格式化以后的数据存入 cache
	private function get_forum($fid) {
		$forum = $this->forum->read($fid);
		$forum && $this->forum->format($forum);
		return $forum;
	}
	
	private function get_forumarr() {
		$arr = array();
		$catelist = $this->forum->get_forum_list();
		foreach($catelist as &$cate) {
			if(!$cate['status']) {
				continue;
			}
			$arr[$cate['fid']]['name'] = $cate['name'];
			$arr2 = array();
			foreach($cate['forumlist'] as &$forum) {
				if(!$forum['status']) {
					continue;
				}
				$arr2[$forum['fid']] = $forum['name'];
				
			}
			$arr[$cate['fid']]['forumlist'] = $arr2;
		}
		return $arr;
	}
	
	// 只能获取非隐藏版块
	private function get_forumlist() {
		$catelist = $this->forum->get_forum_list();
		foreach($catelist as $catekey=>&$cate) {
			if(!$cate['status']) {
				unset($catelist[$catekey]);
				continue;
			}
			$this->forum->format($cate);
			foreach($cate['forumlist'] as $k=>&$forum) {
				if(!$forum['status']) {
					unset($cate['forumlist'][$k]);
					continue;
				}
				$forum && $this->forum->format($forum);
			}
		}
		return $catelist;
	}
	
	private function get_group($groupid) {
		$group = $this->group->read($groupid);
		$group && $this->group->format($group);
		return $group;
	}
	
	private function get_grouplist() {
		return $this->group->get_grouplist();
	}
	
	// 获取常见的几种 id name 格式
	private function get_miscarr() {
		// forum fid=>name
		$arr = array();
		$forumlist = $this->forum->get_all_forum();
		foreach($forumlist as $forum) {
			$arr['forum'][$forum['fid']] = $forum['name'];
		}
		
		// group groupid=>name
		$grouplist = $this->group->get_grouplist();
		foreach($grouplist as $group) {
			$arr['group'][$group['groupid']] = !empty($group['color']) ? "<span style=\"color: $group[color]\">$group[name]</span>" : $group['name'];
		}
		
		return $arr;
	}
	
	private function get_friendlink() {
		$friendlinklist = array();
		$friendlinklist[0] = $this->friendlink->index_fetch(array('type'=>0), array('rank'=>-1), 0, 1000);
		foreach($friendlinklist[0] as &$friendlink) {
			$this->friendlink->format($friendlink);
		}
		
		$friendlinklist[1] = $this->friendlink->index_fetch(array('type'=>1), array('rank'=>-1), 0, 1000);
		foreach($friendlinklist[1] as &$friendlink) {
			$this->friendlink->format($friendlink);
		}
		return $friendlinklist;
	}
	
}

?>