<?php

/*
 * Copyright (C) xiuno.com
 */

class attach extends base_model {
	
	// view/image/filetype/xxx.gif
	// 以下文件名后缀将直接存放于服务器上。白名单机制，避免安全风险。doc 不知道有没有可能造成跨站。
	public $filetypes = array(
		'av' => array('av', 'wmv', 'wav', 'wma', 'avi'),
		'real' => array('rm', 'rmvb'),
		'mp3' => array('mp3','mp4'),
		'binary' => array('dat', 'bin'),
		'flash' => array('swf', 'fla', 'as'),
		'html' => array('html', 'htm'),
		'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
		'office' => array('doc', 'xls', 'ppt'),
		'pdf' => array('pdf'),
		'text' => array('txt', 'c', 'cpp', 'cc'),
		'zip' => array('tar', 'zip', 'gz', 'tar.gz', 'rar', '7z', 'bz'),
		'book' => array('chm'),
		'torrent' => array('bt', 'torrent')
	);
	
	public $safe_exts = array(
		'av', 'wmv', 'wav', 'wma', 'avi', 
		'rm', 'rmvb',
		'mp3','mp4',
		'dat', 'bin',
		'fla', 'as',
		'gif', 'jpg', 'jpeg', 'png', 'bmp',
		'txt', 'c', 'cpp', 'cc',
		'tar', 'zip', 'gz', 'tar.gz', 'rar', '7z', 'bz',
		'bt', 'torrent'
	);
	function __construct() {
		parent::__construct();
		$this->table = 'attach';
		$this->primarykey = array('aid');
		$this->maxcol = 'aid';
	}
	
	public function get_allow_filetypes() {
		$arr = array();
		foreach($this->filetypes as $v) {
			$arr = array_merge($arr, $v);
		}
		return implode(' ', $arr);
	}
	
	public function create($arr) {
		$aid = $arr['aid'] = $this->maxid('+1');
		if($this->set($aid, $arr)) {
			$this->count('+1');
			return $aid;
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($aid, $arr) {
		return $this->set($aid, $arr);
	}
	
	public function read($aid) {
		return $this->get($aid);
	}

	public function _delete($aid) {
		$return = $this->delete($aid);
		if($return) {
			$this->count('-1');
		}
		return $return;
	}
	
	public function get_list_by_fid_pid($fid, $pid, $isimage = FALSE) {
		$attachlist = $this->index_fetch(array('fid'=>$fid, 'pid'=>$pid), array(), 0, 1000);
		foreach($attachlist as $k=>$attach) {
			if($isimage === 1 && $attach['isimage'] == 0) {
				unset($attachlist[$k]);
				continue;
			}
			if($isimage === 0 && $attach['isimage'] == 1) {
				unset($attachlist[$k]);
				continue;
			} 
			$this->format($attachlist[$k]);
		}
		misc::arrlist_multisort($attachlist, 'aid', FALSE);
		return $attachlist;
	}
	
	public function get_list_by_uid($uid, $page = 1, $pagesize = 20) {
		$start = ($page - 1) * $pagesize;
		$attachlist = $this->index_fetch(array('uid'=>$uid, 'isimage'=>0), array(), $start, $pagesize);
		foreach($attachlist as &$attach) {
			$this->format($attach);
		}
		misc::arrlist_multisort($attachlist, 'aid', FALSE);
		return $attachlist;
	}
	
	public function get_imagelist_by_uid($uid, $page = 1, $pagesize = 20) {
		$start = ($page - 1) * $pagesize;
		$attachlist = $this->index_fetch(array('uid'=>$uid, 'isimage'=>1), array(), $start, $pagesize);
		foreach($attachlist as &$attach) {
			$this->format($attach);
		}
		misc::arrlist_multisort($attachlist, 'aid', FALSE);
		return $attachlist;
	}
	
	public function xdelete($fid, $pid) {
		$attachlist = $this->index_fetch(array('fid'=>$fid, 'pid'=>$pid), array(), 0, 10000);
		foreach($attachlist as $attach) {
			$filepath = $this->conf['upload_path'].$attach['filename'];
			is_file($filepath) && unlink($filepath);
			$this->_delete($attach['aid']);
		}
		return count($attachlist);
	}
	
	// 如果文件名为不合法的文件名，则用 aid 来代替
	/*
	public function safe_filename($filename, $aid) {
		$ext = strrchr($file['name'], '.');
		if(preg_match('#^([\w\-]+\.)+[\w+\-]$#', $filename)) {
			return $filename;
		} else {
			
			return $aid.$ext;
		}
	}
	*/
	
	// 根据文件名判断文件类型
	public function get_filetype($filename) {
		$filename = strtolower($filename);
		$ext = substr(strrchr($filename, '.'), 1);
		foreach($this->filetypes as $type=>$arr) {
			if(in_array($ext, $arr)) {
				return $type;
			}
		}
		return 'unknown';
	}
	
	public function get_filehtml($filename, $filetype, $fileurl) {
		$filename = htmlspecialchars(substr($fileurl, strrpos($fileurl, '/') + 1));
	}
	
	// 用来显示给用户
	public function format(&$attach) {
		// format data here.
		if(empty($attach)) return;
		$attach['filesize_fmt'] = misc::humansize($attach['filesize']);
		$attach['dateline_fmt'] = misc::humandate($attach['dateline']);
		$attach['forumname'] = $_SERVER['miscarr']['forum'][$attach['fid']];
		$attach['incomes'] = $attach['golds'] * $attach['downloads'];
		if($attach['isimage']) {
			$attach['filename_thumb'] = substr($attach['filename'], 0, -4).'_thumb.jpg';
		}
	}
	
	// 返回安全的后缀名: .php .jsp 返回 ._php ._jsp
	public function safe_ext($ext) {
		if(in_array(substr($ext, 1), $this->safe_exts)) {
			return $ext;
		}
		$s = preg_replace('#[^\w]#i', '_', substr($ext, 1));
		$s = '._'.substr($s, 0, 3);
		return $s;
	}
	
	public function get_upload_max_filesize() {
		if(function_exists('ini_get') ) {
			$m = ini_get('upload_max_filesize');
			$n = strlen($m);
			if($n > 0) {
				$m[$n - 1] == 'M' && $m = intval($m) * 1000000;
				$m[$n - 1] == 'K' && $m = intval($m) * 1000;
			} else {
				$m = 2000000;
			}
		} else {
			$m = 2000000;
		}
		return $m;
	}
}
?>