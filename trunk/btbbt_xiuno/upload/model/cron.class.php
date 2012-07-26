<?php

/*
 * Copyright (C) xiuno.com
 */

class cron extends base_model {

	function __construct() {
		parent::__construct();
		//set_time_limit(600);
		ignore_user_abort(true);
	}
	
	public function run() {
		$runtime = $this->conf['runtime'];
		$cron_1_next_time = $runtime['cron_1_next_time'];
		$cron_2_next_time = $runtime['cron_2_next_time'];
		
		$time = $_SERVER['time'];
		
		// 跨多台WEB的锁。
		if($this->runtime->read('cronlock') == 1) {
			// 判断锁是否过期?
			if($time > $cron_1_next_time + 30) {
				// 过期则解锁
				//$this->runtime->update('cronlock', 0);
			} else {
				// 否则表示其他进程正在执行
				return;
			}
		}
		$this->runtime->update('cronlock', 1);
	
		// 5 分钟执行一次
		if($time > $cron_1_next_time) {
			$nexttime = $time + 300;
			$this->runtime->update_bbs('cron_1_next_time', $nexttime);
			log::write('cron_1_next_time:'.misc::date($nexttime), 'cron.php');
			
			// gc online table
			$this->online->gc();
			
			// 删除 forumarr, forumlist
			$this->mcache->clear('forumarr');
			$this->mcache->clear('forumlist');
		}

		// execute on 0:00 perday.
		if($time > $cron_2_next_time) {
			// update the next time of cron
			$nexttime = $_SERVER['time_today'] + 86400;
			$this->runtime->update_bbs('cron_2_next_time', $nexttime);
			log::write('cron_2_next_time:'.misc::date($nexttime), 'cron.php');
			
			// set todayposts zero.
			$forumlist = $this->forum->get_all_forum();
			foreach($forumlist as $forum) {
				$forum['todayposts'] = 0;
				$this->forum->update($forum['fid'], $forum);
				$this->mcache->clear('forum', $forum['fid']);
			}
			
			// 统计
			$arr = explode(' ', $_SERVER['time_fmt']);
			list($y, $n, $d) = explode('-', $arr[0]);
			
			// windows 下的锁有可能会出问题。保险起见，判断一下。
			$stat = $this->stat->read($y, $n, $d);
			if(empty($stat)) {
				$threads = $this->thread->count();
				$posts = $this->post->count();
				$users = $this->user->count();
				$stat = array (
					'year'=>$y,
					'month'=>$n,
					'day'=>$d,
					'threads'=>$threads,
					'posts'=>$posts,
					'users'=>$users,
					'newthreads'=>$runtime['todayposts'],
					'newposts'=>$runtime['todayposts'],
					'newusers'=>$runtime['todayusers'],
				);
				$this->stat->create($stat);
			}
			
			// 清空
			$this->runtime->update_bbs('todayposts', 0);
			$this->runtime->update_bbs('todaythreads', 0);
			$this->runtime->update_bbs('todayusers', 0);
			$this->runtime->save_bbs(); //析构函数会自动执行
		}
		
		// 释放锁
		$this->runtime->update('cronlock', 0);
		
	}
}
?>