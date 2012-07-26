<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'control/common_control.class.php';

class post_control extends common_control {
	
	function __construct() {
		parent::__construct();
		$this->_checked = array('bbs'=>' class="checked"');
	}
	
	// create ajax
	public function on_thread() {
		$this->_title[] = '发表帖子';
		$this->_nav[] = '发表帖子';
		
		$this->check_login();
		$this->check_forbidden_group();
		
		$fid = intval(core::gpc('fid'));
		
		$forum = $this->mcache->read('forum', $fid);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		
		$uid = $this->_user['uid'];
		$username = $this->_user['username'];
		$user = $this->user->read($uid);
		
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $forum, 'thread');
		$this->check_user_access($user, 'thread');
		
		$this->check_user_delete($user);
		
		if(!$this->form_submit()) {
			
			$attachlist = $this->get_attachlist_by_tmp($uid);
			$this->init_editor_attach($attachlist, '0');
		
			$this->view->assign('fid', $fid);
			
			$typeselect = form::get_select('typeid', $forum['typelist'], 0, '');
			$this->view->assign('typeselect', $typeselect);
		
			// hook post_thread_before.php
			$this->view->display('post_thread_ajax.htm');
		} else {
			$typeid = intval(core::gpc('typeid', 'P'));	// 检查合法范围
			$typename = $this->thread_type->get_typename($typeid, $fid);
			$subject = htmlspecialchars(core::gpc('subject', 'P'));
			$seo_keywords = htmlspecialchars(strip_tags(core::gpc('seo_keywords', 'P')));
			$message = core::gpc('message', 'P');
			$message = $this->post->html_safe($message);
			$message = misc::html_space($message, TRUE);
			
			// hook post_thread_after.php
			
			$thread = $post = $error = array();
		
			// -----------> 添加到 thread
			$thread = array(
				'fid'=>$fid,
				'uid'=>$uid,
				'username'=>$username,
				'subject'=>$subject,
				'dateline'=>$_SERVER['time'],
				'lastpost'=>0,
				'lastuid'=>'',
				'lastusername'=>'',
				'floortime'=>$_SERVER['time'],
				'views'=>0,
				'posts'=>1,
				'top'=>0,
				'digest'=>0,
				'imagenum'=>0,	// 需要最后更新
				'attachnum'=>0,	// 需要最后更新
				'closed'=>0,
				'firstpid'=>0,	// 需要最后更新，也就是最小的pid，冗余存储，提高速度
				'catenames'=>'',	// 
				'cateids'=>0,	//
				'typeid'=>$typeid,	//
				'typename'=>$typename,	//
				'status'=>0,
				'coverimg'=>'',
				'brief'=>'',
				'status'=>0,
				'seo_keywords'=>$seo_keywords,
				'pids'=>'',		// 第一页的缓存的pid
			);
			
			$error['subject'] = $this->thread->check_subject($thread['subject']);
			if(misc::values_empty($error)) {
				$error = array();
				
				// hook post_thread_thread_create_before.php
				$tid = $thread['tid'] = $this->thread->create($fid, $thread);
				if(!$thread['tid']) {
					$this->message('发帖过程中保存数据错误，请联系管理员。');
				}
				// hook post_thread_thread_create_after.php
				
				// -----------> 添加到 post
				$page = 1;
				$post = array (
					'tid'=>$thread['tid'],
					'uid'=>$uid,
					'dateline'=>$_SERVER['time'],
					'userip'=>ip2long($_SERVER['ip']),
					'attachnum'=>0,
					'page'=>$page,
					'username'=>$username,
					'subject'=>'',
					'message'=>$message,
				);
				
				$brief = utf8::substr(strip_tags($message), 0, 80);
				$coverimg = '';
				
				$error['message'] = $this->post->check_message($post['message']);
				if(misc::values_empty($error)) {
					$error = array();
					
					// hook post_thread_post_create_before.php
					$pid = $post['pid'] = $this->post->create($fid, $post);
					// hook post_thread_post_create_after.php
					
					// 更新 $attach 上传文件的pid
					$attachnum = $imagenum = 0;
					$aidarr = $this->get_aid_from_tmp($uid);
					foreach($aidarr as $aid) {
						$attach = $this->attach->read($aid);
						if(empty($attach)) continue;
						if($attach['uid'] != $uid) continue;
						$attach['pid'] = $post['pid'];
						if($attach['isimage'] == 1) {
							$imagenum++;
							empty($coverimg) && $coverimg = image::thumb_name($attach['filename']); // 第一张选择为封面
						} else {
							$attachnum++;
						}
						$this->attach->update($aid, $attach);
					}
					$this->clear_aid_from_tmp($uid);
					
					// 更新 $thread firstpid
					$thread['firstpid'] = $post['pid'];
					$thread['pids'] = $post['pid'];
					$thread['imagenum'] = $imagenum;
					$thread['attachnum'] = $attachnum;
					$thread['coverimg'] = $coverimg;
					$thread['brief'] = $brief;
					$this->thread->update($fid, $thread['tid'], $thread);
					
					// 更新 $post
					$post['imagenum'] = $imagenum;
					$post['attachnum'] = $attachnum;
					$this->post->update($fid, $post['pid'], $post);
					
					// 更新 $user 用户发帖数，积分
					//$user = $this->user->read($uid);
					$user['threads']++;
					$user['posts']++;
					$user['credits'] += $this->conf['credits_policy_thread'];
					$user['money'] += $this->conf['gold_policy_thread'];
					$groupid = $user['groupid'];
					$user['groupid'] = $this->group->get_groupid_by_credits($user['groupid'], $user['credits']);
					
					// 更新 cookie 如果用户组发生改变，更新用户的 cookie
					if($groupid != $user['groupid']) {
						$this->user->set_login_cookie($user);
					}
					
					// 加入 $mypost
					$this->mypost->create(array('uid'=>$uid, 'fid'=>$fid, 'tid'=>$tid, 'pid'=>$pid));
					$user['myposts']++;
					
					// 更新 user
					$this->user->update($uid, $user);
					
					// 更新 $forum 板块的总贴数
					$forum = $this->forum->read($fid);
					$forum['threads']++;
					$forum['posts']++;
					$forum['todayposts']++;
					$forum['lastpost'] = $_SERVER['time'];
					$forum['lasttid'] = $tid;
					$forum['lastuid'] = $uid;
					$forum['lastusername'] = $this->_user['username'];
					$forum['lastsubject'] = $thread['subject'];
					$this->forum->update($fid, $forum);
					$this->clear_forum_cache($fid);
					$this->runtime->update_bbs('posts', '+1');
					$this->runtime->update_bbs('threads', '+1');
					$this->runtime->update_bbs('todayposts', '+1');
					
					// $error
					$error['thread'] = $thread;
					
					// 更新tidcache
					if($this->conf['cache_tid']) {
						$this->tidcache->add_tid($fid, $tid);
					}
					
					// hook post_thread_succeed.php
				}
			}
			$this->message($error);
		}
	}
	
	public function on_post() {
		$fid = intval(core::gpc('fid'));
		$tid = intval(core::gpc('tid'));
		$quickpost = intval(core::gpc('quickpost'));
		
		$this->check_login();
		$this->check_forbidden_group();
		
		$uid = $this->_user['uid'];
		$username = $this->_user['username'];
		$user = $this->user->read($uid);
		$this->check_user_delete($user);
		
		$group = $this->group->read($user['groupid']);
		
		// 帖子存在检查
		$thread = $this->thread->get($fid, $tid);
		$this->check_thread_exists($fid, $thread);
		$this->check_user_access($user, 'post');
		
		// 帖子回复数不能超过 10000
		if($thread['posts'] > 10000) {
			$this->message('该帖子回复数已经达到10000，不能再回复了，再起话题吧！');
		}
		
		// 板块权限检查
		$forum = $this->mcache->read('forum', $fid);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $pforum, 'post');
		
		if(!$this->form_submit()) {
			
			// 附件相关
			$attachlist = $this->get_attachlist_by_tmp($uid);
			$this->init_editor_attach($attachlist, '00');
			
			$this->view->assign('fid', $fid);
			$this->view->assign('tid', $tid);
			$this->view->assign('thread', $thread);
			$this->view->assign('forum', $forum);
			// hook post_post_before.php
			$this->view->display('post_post_ajax.htm');
		} else {
			$post = $error = array();
			$subject = htmlspecialchars(core::gpc('subject', 'P')); // 废弃
			$message = core::gpc('message', 'P');
			$message = $this->post->html_safe($message);
			
			// 快速发帖。
			if($quickpost) {
				$message = misc::html_space($message);
			} else {
				$message = misc::html_space($message, TRUE);
			}
			
			// -----------> 添加到 post
			$attachnum = $imagenum = 0;
			$page = 1;
			$page = ceil(($thread['posts'] + 1) / $this->conf['pagesize']);
			$post = array (
				'tid'=>$thread['tid'],
				'uid'=>$uid,
				'dateline'=>$_SERVER['time'],
				'userip'=>ip2long($_SERVER['ip']),
				'attachnum'=>0,
				'imagenum'=>0,
				'page'=>$page,
				'username'=>$username,
				'subject'=>'',
				'message'=>$message,
			);
			
			$error['message'] = $this->post->check_message($post['message']);
			
			// hook post_post_after.php
			if(misc::values_empty($error)) {
				$error = array();
				$error['page'] = $page;
				
				// hook post_post_post_create_before.php
				$pid = $post['pid'] = $this->post->create($fid, $post);
				// hook post_post_post_create_after.php
				
				// 更新 $attach 上传文件的pid
				$aidarr = $this->get_aid_from_tmp($uid);
				foreach($aidarr as $aid) {
					$attach = $this->attach->read($aid);
					if(empty($attach)) continue;
					if($attach['uid'] != $uid) continue;
					$attach['pid'] = $post['pid'];
					if($attach['isimage'] == 1) {
						$imagenum++;
					} else {
						$attachnum++;
					}
					$this->attach->update($aid, $attach);
				}
				$this->clear_aid_from_tmp($uid);
				
				// 更新 $post
				$post['attachnum'] = $attachnum;
				$post['imagenum'] = $imagenum;
				$this->post->update($fid, $post['pid'], $post);
				
				// 更新 $user 用户发帖数，积分
				$user = $this->user->read($uid);
				$user['posts']++;
				$user['credits'] += $this->conf['credits_policy_post'];
				$user['money'] += $this->conf['gold_policy_post'];
				$groupid = $user['groupid'];
				$user['groupid'] = $this->group->get_groupid_by_credits($user['groupid'], $user['credits']);
				
				// 更新 cookie 如果用户组发生改变，更新用户的 cookie
				if($groupid != $user['groupid']) {
					$this->user->set_login_cookie($user);
				}
				
				// 加入 $mypost
				if(!$this->mypost->have_tid($uid, $fid, $tid)) {
					$this->mypost->create(array('uid'=>$uid, 'fid'=>$fid, 'tid'=>$tid, 'pid'=>$pid));
					$user['myposts']++;
				}
				
				// 更新 $user 
				$this->user->update($uid, $user);
					
				// 更新 $forum 板块的总贴数
				$forum = $this->forum->read($fid);
				$forum['posts']++;
				$forum['todayposts']++;
				$forum['lastpost'] = $_SERVER['time'];
				$forum['lasttid'] = $tid;
				$forum['lastuid'] = $uid;
				$forum['lastusername'] = $username;
				$forum['lastsubject'] = $thread['subject'];
				$this->forum->update($fid, $forum);
				$this->clear_forum_cache($fid);
				
				// 今日总的发帖数
				$this->runtime->update_bbs('posts', '+1');
				$this->runtime->update_bbs('todayposts', '+1');
				
				// 更新 $thread
				$thread['posts']++;
				$thread['lastuid'] = $uid;
				$thread['lastpost'] = $_SERVER['time'];
				$thread['lastusername'] = $username;
				$thread['floortime'] = $this->get_floortime($fid, $thread['floortime'], $group['upfloors']);
				if($thread['posts'] <= $this->conf['pagesize']) {
					$thread['pids'] .= ($thread['pids'] ? ','.$pid : $pid);
				}
				$this->thread->update($fid, $tid, $thread);
				
				// 更新 tidcache
				if($this->conf['cache_tid']) {
					$this->tidcache->add_tid($fid, $tid);
				}
				
				// hook post_post_succeed.php
			}
			$this->message($error);
		}
	}
	
	// 修改 ajax
	public function on_update() {
		$this->_title[] = '修改帖子';
		$this->_nav[] = '修改帖子';
		
		$this->check_login();
		$this->check_forbidden_group();
		
		$fid = intval(core::gpc('fid'));
		$pid = intval(core::gpc('pid'));
		
		$uid = $this->_user['uid'];
		$username = $this->_user['username'];
		$user = $this->user->read($uid);
		$this->check_user_delete($user);
		
		// 板块权限检查
		$forum = $this->mcache->read('forum', $fid);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $pforum, 'post');
		
		$post = $this->post->read($fid, $pid);
		$tid = $post['tid'];
		
		$thread = $this->thread->get($fid, $tid);
		$this->check_thread_exists($fid, $thread);
		
		$ismod = $this->is_mod($forum, $pforum, $this->_user);
		// 编辑权限检查：管理员，版主，可以编辑
		if($post['uid'] != $this->_user['uid'] && !$ismod) {
			$this->message('您没有权限对该板块进行置顶操作！', 0);
		}
		
		// 过期不能编辑
		if(!$ismod && $this->conf['post_update_expiry'] && $_SERVER['time'] - $post['dateline'] > $this->conf['post_update_expiry']) {
			$time = ceil($this->conf['post_update_expiry'] / 60);
			$this->message('您不能再继续修改该贴，已经超出了最大修改时间: (<b>'.$time.'分钟</b>)。', 0);
		}
		
		// 是否为首贴
		$isfirst = $thread['firstpid'] == $pid;
		
		$input = $error = array();
		if(!$this->form_submit()) {
			$post['message_html'] = htmlspecialchars($post['message']);;
			
			// 附件相关
			$attachlist = $this->attach->get_list_by_fid_pid($fid, $pid, 0);
			$this->init_editor_attach($attachlist, $pid);
			
			if($isfirst) {
				$typeselect = form::get_select('typeid', $forum['typelist'], $thread['typeid'], '');
				$this->view->assign('typeselect', $typeselect);
			}
			
			$this->view->assign('isfirst', $isfirst);
			$this->view->assign('fid', $fid);
			$this->view->assign('tid', $tid);
			$this->view->assign('pid', $pid);
			$this->view->assign('post', $post);
			$this->view->assign('thread', $thread);
			$this->view->assign('forum', $forum);
			$this->view->assign('input', $input);
			$this->view->assign('error', $error);
			// hook post_update_before.php
			$this->view->display('post_update_ajax.htm');
			//$this->view->display('__post_update.htm');
		} else {
			
			$typeid = intval(core::gpc('typeid', 'P'));	// 检查合法范围
			$typename = $this->thread_type->get_typename($typeid, $fid);
			
			$subject = htmlspecialchars(core::gpc('subject', 'P'));
			$message = $this->post->html_safe(core::gpc('message', 'P'));
			$message = misc::html_space($message, TRUE);
			$seo_keywords = htmlspecialchars(strip_tags(core::gpc('seo_keywords', 'P')));
			
			// 更新数据
			if($isfirst) {
				$thread['typeid'] = $typeid;
				$thread['typename'] = $typename;
				$thread['subject'] = $subject;
				$thread['seo_keywords'] = $seo_keywords;
				$error['subject'] = $this->thread->check_subject($thread['subject']);
			}
			$post['message'] = $message;
			$error['message'] = $this->post->check_message($post['message']);
			$brief = utf8::substr(strip_tags($message), 0, 80);
			$coverimg = '';
			
			// hook post_update_after.php
			
			// 如果检测没有错误，则更新
			if(misc::values_empty($error)) {
				$error = array();
				
				// 删除没有被引用的图片
				$attachlist = $this->attach->get_list_by_fid_pid($fid, $pid, 1);
				$imagenum = 0;// 在这里，图片数只会减少，附件数不会变
				$rebuild_cover = 0;
				$coverimg = $thread['coverimg'];
				foreach($attachlist as $attach) {
					$url = $this->conf['upload_url'].$attach['filename'];
					if($attach['filename'] && strpos($post['message'], $attach['filename']) === FALSE) {
						// 删除没有被引用的附件，有点粗暴，可以理解为 word 的编辑方式，删除的图片需要重新上传。
						$attachfile = $this->conf['upload_path'].$attach['filename'];
						$attachthumb = image::thumb_name($this->conf['upload_path'].$attach['filename']);
						is_file($attachfile) && unlink($attachfile);
						is_file($attachthumb) && unlink($attachthumb);
						$this->attach->_delete($attach['aid']);
						$imagenum--;
						if(image::thumb_name($attach['filename']) == $coverimg) {
							$coverimg = '';
						} else {
							empty($coverimg) && $coverimg = image::thumb_name($attach['filename']);
						}
					}
				}
				$imagenum && $this->post->update_imagenum($fid, $pid, $imagenum);
				
				// 如果为首页，则更新附件个数
				if($isfirst) {
					$thread['coverimg'] = $coverimg;
					$thread['imagenum'] += $imagenum;
					$thread['brief'] = $brief;
				}
				// hook post_update_thread_update__before.php
				$this->thread->update($fid, $tid, $thread);
				$this->post->update($fid, $pid, $post);
				// hook post_update_thread_update__after.php
				
				$this->clear_forum_cache($fid);
				
				// hook post_update_succeed.php
				$this->message('更新成功！');
			}
			$this->message($error);
		}
	}
	
	// tpdo: 删除帖子，删除主题, todayposts 未更新
	public function on_delete() {
		$this->_title[] = '删除帖子';
		$this->_nav[] = '删除帖子';
		
		$this->check_login();
		$this->check_forbidden_group();
		
		$pid = intval(core::gpc('pid'));
		$fid = intval(core::gpc('fid'));
		
		$uid = $this->_user['uid'];
		$username = $this->_user['username'];
		$user = $this->user->read($uid);
		$this->check_user_delete($user);
		
		// 板块权限检查
		$forum = $this->mcache->read('forum', $fid);
		$pforum = $this->mcache->read('forum', $forum['fup']);
		$this->check_forum_exists($forum);
		$this->check_forum_access($forum, $pforum, 'thread');
		
		$post = $this->post->read($fid, $pid);
		$this->check_post_exists($post);
		$tid = $post['tid'];
		
		$thread = $this->thread->get($fid, $tid);
		$this->check_thread_exists($fid, $thread);
		
		// 编辑权限检查：管理员，版主，可以编辑
		if($post['uid'] != $this->_user['uid']) {
			if(!$this->is_mod($forum, $pforum, $this->_user)) {
				$this->message('您没有权限对该板块主题进行删除操作！');
			}
		}
		
		$isfirst = $thread['firstpid'] == $pid;
		
		if($isfirst) {
			
			// hook post_delete_post_before.php
			$this->thread->xdelete($fid, $tid, TRUE);	// 删除 $postlist, 更新 $forum $userlist
			// hook post_delete_post_after.php
			
			$this->clear_forum_cache($fid);
			
			$this->location("?forum-index-fid-$fid.htm");
			
		} else {
			
			// hook post_delete_thread_before.php
			$this->post->xdelete($fid, $pid, TRUE);
			// hook post_delete_thread_after.php
			
			// 重建页数
			$this->post->rebuild_page($fid, $tid, $pid, $post['page']);
			
			$this->location("?thread-index-fid-$fid-tid-$tid-page-$post[page].htm");
		}
	}

	// 默认顶贴升几楼？ get_floortime($fid, $thread['floortime'], $group['upfloors']);
	private function get_floortime($fid, $floortime, $upfloors) {
		if(empty($upfloors)) {
			return $_SERVER['time'];
		}
		$tids = $this->thread->index_fetch_id(array('fid'=>$fid, 'floortime'=>array('>'=>$floortime)), array('floortime'=>1), 0, $upfloors);
		$key = array_pop($tids);
		if(empty($key)) {
			return $_SERVER['time'];
		} else {
			$thread = $this->thread->db_cache_get($key);
			return $thread['floortime'] + 1;
		}
		
	}
	
	private function get_attachlist_by_tmp($uid) {
		$file = $this->conf['tmp_path'].$uid.'_aids.tmp';
		$aids = is_file($file) ? trim(file_get_contents($file)) : '';
		$aidarr = $aids ? explode(' ', $aids) : array();
		$attachlist = array();
		foreach($aidarr as $aid) {
			$attach = $this->attach->read($aid);
			$this->attach->format($attach);
			$attachlist[$aid] = $attach;
		}
		return $attachlist;
	}
		
	private function init_editor_attach($attachlist, $pid) {
		$attachnum = count($attachlist);
		$this->view->assign('attachlist', $attachlist);
		$this->view->assign('attachnum', $attachnum);
		$this->fix_swfupload_md5_auth();
		$upload_max_filesize = $this->attach->get_upload_max_filesize();
		$this->view->assign('upload_max_filesize', $upload_max_filesize);
		$filetyps = core::json_encode($this->attach->filetypes);
		$this->view->assign('filetyps', $filetyps);
		$this->view->assign_value('pid', $pid);// 给编辑器附件列表使用
	}

	//hook post_control.php
}

?>