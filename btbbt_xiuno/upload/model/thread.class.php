<?php

/*
 * Copyright (C) xiuno.com
 */

class thread extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'thread';
		$this->primarykey = array('fid', 'tid');
		$this->maxcol = 'tid';
	}
	

	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($fid, $arr) {
		empty($arr['tid']) && $arr['tid'] = $this->maxid('+1');
		if($this->set($fid, $arr['tid'], $arr)) {
			$this->count('+1');
			return $arr['tid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($fid, $tid, $arr) {
		return $this->set($fid, $tid, $arr);
	}
	
	public function read($fid, $tid) {
		return $this->get($fid, $tid);
	}

	// 基本删除
	public function _delete($fid, $tid) {
		$return = $this->delete($fid, $tid);
		// count 为强关联
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	// ------------------> 杂项
	public function check_subject(&$subject) {
		if(empty($subject)) {
			return '标题不能为空。';
		}
		if(utf8::strlen($subject) > 200) {
			return '标题不能超过 200 字，当前长度：'.strlen($subject);
		}
		return '';
	}
	
	// 用来显示给用户
	public function format(&$thread) {
		if(empty($thread)) return;
		$thread['subject']  = htmlspecialchars($thread['subject']);
		isset($thread['message']) && $thread['message']  = nl2br(htmlspecialchars($thread['message']));
		$thread['isnew'] = $_SERVER['time_today'] < max($thread['dateline'], $thread['lastpost']);	// 今日
		$thread['dateline_fmt'] = misc::minidate($thread['dateline'], $this->conf['timeoffset']);
		$cateidarr = explode(' ', $thread['cateids']);
		$catenamearr = explode(' ', $thread['catenames']);
		$thread['catelist_full'] = count($cateidarr) > 0 &&  count($catenamearr) > 0 ? array_combine($cateidarr, $catenamearr) : array();
		$thread['catelist'] = array_slice($thread['catelist_full'], 0, 1, true); // todo: 只保留2个主题分类
		$thread['posts_fmt'] = max(0, $thread['posts'] - 1);	// 用来前端显示
		$thread['lastpost_fmt'] = misc::minidate($thread['lastpost'], $this->conf['timeoffset']);
	}
	
	// 调用别的model->xdelete 时要特别小心，确定 xdelete 不会相互依赖！
	// 调用次函数以后，最好再调用 $this->clear_forum_cache();
	public function xdelete($fid, $tid, $updatestat = TRUE) {
		
		$runtime = $this->conf['runtime'];
		$forum = $this->forum->read($fid);
		$thread = $this->thread->read($fid, $tid);
		$user = $this->user->read($thread['uid']);
		$uid = $thread['uid'];
		
		// 受影响的值。
		$return = array(
			'forum'=>array($fid=>array('threads'=>0, 'posts'=>0, 'digests'=>0)),
			'user' => array($uid=>array('threads'=>0, 'posts'=>0, 'myposts'=>0, 'digests'=>0, 'credits'=>0, 'money'=>0))
		);
		$rforum = &$return['forum'][$fid];
		$ruser = &$return['user'];
		
		// 算出分页，一页一页的删除
		$pagesize = $this->conf['pagesize'];
		$pagenum = ceil($thread['posts'] / $pagesize);
		for($i = 1; $i <= $pagenum; $i++) {
			$postlist = $this->post->index_fetch(array('fid'=>$fid, 'tid'=>$tid, 'page'=>$i), array(), 0, $pagesize);
			foreach($postlist as $post) {
				
				!isset($ruser[$post['uid']]) && $ruser[$post['uid']] = array('threads'=>0, 'posts'=>0, 'credits'=>0, 'money'=>0, 'digests'=>0, 'myposts'=>0);
				
				// 删除 attach
				$post['attachnum'] && $this->attach->xdelete($post['fid'], $post['pid']);
				
				// 删除 mypost，删除主题一定不会空删除
				$this->mypost->_delete($post['uid'], $post['fid'], $post['pid']);
				
				$ruser[$post['uid']]['myposts']++;
				
				// 删除 $post
				$this->post->_delete($post['fid'], $post['pid']);
				
				$ruser[$post['uid']]['posts']++;
				$ruser[$post['uid']]['credits'] += $this->conf['credits_policy_post'];
				$ruser[$post['uid']]['money'] += $this->conf['gold_policy_post'];
			}
		}
		
		$rforum['threads']++;
		$rforum['posts'] += $thread['posts'];
		
		// 删除精华
		if($thread['digest'] > 0) {
			$rforum['digests']++;
			$this->digest->delete_by_fid_tid($fid, $tid);
			
			// 用户积分，精华数
			$ruser[$uid]['digests']++;
			$ruser[$uid]['credits'] += $this->conf['credits_policy_digest_'.$thread['digest']];
			$ruser[$uid]['money'] += $this->conf['gold_policy_digest_'.$thread['digest']];
		}
		
		// 删除置顶
		if($thread['top']) {
			$pforum = $this->forum->read($forum['fup']);
			$thread['top'] == 1 && $this->thread_top->delete_top_1($forum, array("$fid-$tid"));
			$thread['top'] == 2 && $this->thread_top->delete_top_2($forum, array("$fid-$tid"), $pforum);
			$thread['top'] == 3 && $this->thread_top->delete_top_3($forum, array("$fid-$tid"), $runtime);
		}
		
		// 删除主题
		$this->thread->_delete($fid, $tid);
		
		// 删除 tidcache
		$this->tidcache->delete_tid($fid, $tid);
		
		// 更新 runtime
		$this->runtime->update_bbs('threads', '+1');
		
		if($updatestat) {
			$this->xdelete_update($return);
			
			// 更新最后发帖，直接清零
			if($forum['lasttid'] == $tid) {
				$forum['lasttid'] = 0;
				$forum['lastuid'] = 0;
				$forum['lastusername'] = '';
				$forum['lastsubject'] = '';
			}
		}
		
		
		return $return;
	}
	
	// 合并返回值，用户删除板块时候，合并主题。
	public function xdelete_merge_return(&$return, &$return2) {
		foreach($return2['user'] as $uid=>$arr) {
			if(!$uid) continue;
			if(!isset($return['user'][$uid])) { $return['user'][$uid] = $arr; continue; }
			$return['user'][$uid]['threads'] += $arr['threads'];
			$return['user'][$uid]['posts'] += $arr['posts'];
			$return['user'][$uid]['myposts'] += $arr['myposts'];
			$return['user'][$uid]['digests'] += $arr['digests'];
			$return['user'][$uid]['credits'] += $arr['credits'];
			$return['user'][$uid]['money'] += $arr['money'];
		}
		foreach($return2['forum'] as $fid=>$arr) {
			if(!$fid) continue;
			if(!isset($return['forum'][$fid])) { $return['forum'][$fid] = $arr; continue; }
			$return['forum'][$fid]['threads'] += $arr['threads'];
			$return['forum'][$fid]['posts'] += $arr['posts'];
			$return['forum'][$fid]['digests'] += $arr['digests'];
		}
	}
	
	// 关联删除后的更新
	public function xdelete_update($return) {
		// 更新回复用户的积分
		if(isset($return['user'])) {
			foreach($return['user'] as $uid=>$arr) {
				if(!$uid) continue;
				$user = $this->user->read($uid);
				$user['threads'] -= $arr['threads'];
				$user['posts'] -= $arr['posts'];
				$user['myposts'] -= $arr['myposts'];
				$user['digests'] -= $arr['digests'];
				$user['credits'] -= $arr['credits'];
				$user['money'] -= $arr['money'];
				$this->user->update($uid, $user);
			}
		}
		
		if(isset($return['forum'])) {
			foreach($return['forum'] as $fid=>$arr) {
				if(!$fid) continue;
				$forum = $this->forum->read($fid);
				$forum['threads'] -= $arr['threads'];
				$forum['posts'] -= $arr['posts'];
				$forum['digests'] -= $arr['digests'];
				$this->forum->update($fid, $forum);
				$this->forum->update_last($fid);
				$this->mcache->clear('forum', $fid);
				$this->mcache->clear('forumlist');
			}
		}
	}
}
?>