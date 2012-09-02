<?php

/*
 * Copyright (C) xiuno.com
 */

class post extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'post';
		$this->primarykey = array('fid', 'pid');
		$this->maxcol = 'pid';
		
	}

	public function create($fid, $arr) {
		empty($arr['pid']) && $arr['pid'] = $this->maxid('+1');
		if($this->set($fid, $arr['pid'], $arr)) {
			$this->count('+1');
			return $arr['pid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($fid, $pid, $arr) {
		return $this->set($fid, $arr['pid'], $arr);
	}
	
	// 附件数计数
	public function update_attachnum($fid, $pid, $num) {
		$post = $this->read($fid, $pid);
		$post['attachnum'] += $num;
		$this->update($fid, $pid, $post);
	}
	
	// 图片数计数
	public function update_imagenum($fid, $pid, $num) {
		$post = $this->read($fid, $pid);
		$post['imagenum'] += $num;
		$this->update($fid, $pid, $post);
	}
	
	public function read($fid, $pid) {
		return $this->get($fid, $pid);
	}

	public function get_list_by_page() {
	
	}
	
	public function check_message(&$message) {
		$message = trim($message);
		if(empty($message) || str_replace(array('<br>', '<br/>', '<br />', '&nbsp;', ' ', "\r", "\n", "\t"), '', $message) == '') {
			return '内容不能为空。';
		}
		if(utf8::strlen($message) > 2000000) {
			return '内容不能超过200万个字符。';
		}
		return '';
	}
	
	// 用来显示给用户
	public function format(&$post) {
		//$post['subject']  = htmlspecialchars($post['subject']);
		//isset($post['message']) && $post['message']  = nl2br(htmlspecialchars($post['message']));
		isset($post['dateline']) && $post['dateline'] = misc::minidate($post['dateline'], $this->conf['timeoffset']);
	}
	
	public function _delete($fid, $pid) {
		$return = $this->delete($fid, $pid);
		if($return) {
			$this->count('-1');
		}
		// 删除附件 ?
		return $return;
	}
	
	// 删除回帖，非主题帖。相对比较简单，是相对！万恶的删除和缓存啊！不过现在终于可以把它封起来了，稳定了。
	public function xdelete($fid, $pid, $updatestat = TRUE) {
		$post = $this->read($fid, $pid);
		$tid = $post['tid'];
		$uid = $post['uid'];
		$return = array (
			'forum'=>array($fid => array('posts'=>0)),
			'user' => array($uid => array('posts'=>0, 'credits'=>0, 'golds'=>0, 'myposts'=>0)),
			'thread' => array("$fid-$tid" => array('posts'=>0)),
			'fidtidpid' => array("$fid-$tid-$pid" => $post['page'])	// 最小 page
		);
		$rforum = &$return['forum'][$fid];
		$ruser = &$return['user'][$uid];
		$rthread = &$return['thread']["$fid-$tid"];
		
		// 删除 $attach
		$post['attachnum'] && $this->attach->xdelete($fid, $pid);
		
		// 删除 mypost，有可能空删，因为记录的时候根据 tid 去重了
		$r = $this->mypost->_delete($post['uid'], $post['fid'], $post['pid']);
		$r && $ruser['myposts']++;
		
		// 删除 $post
		$this->_delete($fid, $pid);
		
		// 更新 $forum 板块的总贴数
		$rforum['posts']++;
		
		// 更新 $user
		$ruser['posts']++;
		$ruser['credits'] += $this->conf['credits_policy_post'];
		$ruser['golds'] += $this->conf['gold_policy_post'];
		
		// 更新 $thread
		$rthread['posts']++;
		
		if($updatestat) {
			$this->xdelete_update($return);
		}
		
		// 更新 runtime
		$this->runtime->update_bbs('posts', '+1');
		
		return $return;
	}
	
	// 合并返回值，用户删除板块时候，合并主题。
	public function xdelete_merge_return(&$return, &$return2) {
		foreach($return2['user'] as $uid=>$arr) {
			if(!$uid) continue;
			if(!isset($return['user'][$uid])) { $return['user'][$uid] = $arr; continue; }
			$return['user'][$uid]['posts'] += $arr['posts'];
			$return['user'][$uid]['credits'] += $arr['credits'];
			$return['user'][$uid]['golds'] += $arr['golds'];
			$return['user'][$uid]['myposts'] += $arr['myposts'];
		}
		foreach($return2['forum'] as $fid=>$arr) {
			if(!$fid) continue;
			if(!isset($return['forum'][$fid])) { $return['forum'][$fid] = $arr; continue; }
			$return['forum'][$fid]['posts'] += $arr['posts'];
		}
		foreach($return2['thread'] as $tid=>$arr) {
			if(!$tid) continue;
			if(!isset($return['thread'][$tid])) { $return['thread'][$tid] = $arr; continue; }
			$return['thread'][$tid]['posts'] += $arr['posts'];
		}
		// 这里~~~ 万恶的数组合并，复杂的重现，浪费老夫几个小时的生命，应该做个记号吧。
		foreach($return2['fidtidpid'] as $fidtidpid=>$page) {
			if(!$fidtidpid) continue;
			if(!isset($return['fidtidpid'][$fidtidpid])) {$return['fidtidpid'][$fidtidpid] = $page; continue;}
			if($return['fidtidpid'][$fidtidpid] > $page) { 
				$return['fidtidpid'][$fidtidpid] = $page;
			}
		}
	}
	
	// 关联删除后的更新，会涉及到楼层整理，非常麻烦。
	public function xdelete_update($return) {
		// 更新回复用户的积分
		if(isset($return['user'])) {
			foreach($return['user'] as $uid=>$arr) {
				if(!$uid) continue;
				$user = $this->user->read($uid);
				$user['posts'] -= $arr['posts'];
				$user['credits'] -= $arr['credits'];
				$user['golds'] -= $arr['golds'];
				$user['myposts'] -= $arr['myposts'];
				$this->user->update($uid, $user);
			}
		}
		if(isset($return['forum'])) {
			foreach($return['forum'] as $fid=>$arr) {
				if(!$fid) continue;
				$forum = $this->forum->read($fid);
				$forum['posts'] -= $arr['posts'];
				$this->forum->update($fid, $forum);
				$this->mcache->clear('forum', $fid);
			}
		}
		
		// todo: lastuid, lastusername 貌似没有更新
		if(isset($return['thread'])) {
			foreach($return['thread'] as $tid=>$arr) {
				if(!$tid) continue;
				list($fid, $tid) = explode('-', $tid);
				$fid = intval($fid);
				$tid = intval($tid);
				$thread = $this->thread->read($fid, $tid);
				$thread['posts'] -= $arr['posts'];
				$this->thread->update($fid, $tid, $thread);
			}
		}
		if(isset($return['fidtidpid'])) {
			foreach($return['fidtidpid'] as $fidtidpid=>$page) {
				if(!$fidtidpid) continue;
				list($fid, $tid, $pid) = explode('-', $fidtidpid);
				$fid = intval($fid);
				$tid = intval($tid);
				$pid = intval($pid);
				$this->rebuild_page($fid, $tid, $pid, $page);
			}
		}
	}
	
	// 重建帖子，传入最小的 $startpage
	public function rebuild_page($fid, $tid, $pid, $startpage) {
		$thread = $this->thread->read($fid, $tid);
		$tid = $thread['tid'];
	
		// 如果回帖数小于100， 重建所在页之后的帖子
		$totalpage = ceil($thread['posts'] / $this->conf['pagesize']);
		
		// 如果需要重建的页数过多，则放弃，超过200页，不整理
		if($totalpage - $startpage > 200) {
			$this->recache_pids($fid, $tid);
			return FALSE;
		}
		
		$k = 0; // 翻页计数，到20则清零，并且 $kpage+1
		$kpage = $startpage;
		for($i = $startpage; $i <=  $totalpage; $i++) {
			// 翻页查找所有id,逐个更新
			$postlist = $this->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>$i),  array(), 0, $this->conf['pagesize']);
			//ksort($postlist);
			foreach($postlist as $_post) {
				if($kpage != $_post['page']) {
					$_post['page'] = $kpage;
					$this->update($fid, $_post['pid'], $_post);
				}
				if(++$k == $this->conf['pagesize']) {
					$k = 0;
					$kpage++;
				}
			}
		}
		$this->recache_pids($fid, $tid);
		return TRUE;
	}
	
	public function recache_pids($fid, $tid) {
		$postlist = $this->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>1),  array(), 0, $this->conf['pagesize']);
		$pids = '';
		foreach($postlist as $post) {
			$pids .= $post['pid'].',';
		}
		$pids = substr($pids, 0, -1);
		$thread = $this->thread->read($fid, $tid);
		$thread['pids'] = $pids;
		
		$this->thread->update($fid, $tid, $thread);
	}
	
	/*
	public function html_safe($s) {
		include_once FRAMEWORK_PATH.'lib/kses.class.php';
		$allowed = array('b' => array(),
		                 'i' => array(),
		                 'a' => array('href'  => array('minlen' => 3, 'maxlen' => 50),
		                              'title' => array('valueless' => 'n')),
		                 'p' => array('align' => 1,
		                              'dummy' => array('valueless' => 'y')),
		                 'img' => array('src' => 1), # FIXME
		                 'font' => array('size' =>
		                                         array('minval' => 4, 'maxval' => 20)),
		                 'br' => array(), 
		                 'span' => array('style'=>array()), 
		                 'h1' => array(), 'h2'=> array(), 'h3'=> array(), 'h4'=> array(), 'h5'=> array(), 
		                 'div' => array(),
		                 'table' => array('width'=> array('maxval'=>800)), 'tr' => array(), 'td' => array('maxval'=>800), 'th' => array('maxval'=>800),'tbody' => array(),'tfoot' => array(),'thead' => array(),
		                 );
		$s = kses($s, $allowed, array('http', 'https'));
		return $s;
	}
	*/
	
	public function html_safe($doc) {
		include_once FRAMEWORK_PATH.'lib/html_safe.class.php';
		$safehtml = new HTML_Safe();
		$result = $safehtml->parse($doc);
		return $result;
	}
	
}
?>