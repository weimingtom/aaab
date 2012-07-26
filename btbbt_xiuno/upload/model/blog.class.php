<?php

/*
 * Copyright (C) xiuno.com
 */

/*
	这是一个示例代码，用来COPY，替换。 替换 blog -> yourmodel
	
*/

class blog extends base_model {
	
	function __construct() {
		parent::__construct();
		$this->table = 'blog';
		$this->primarykey = array('blogid');
		$this->maxcol = 'blogid';
		
	}
	

	/*
		$arr = array(
			'subject'=>'aaa',
			'message'=>'bbb',
			'dateline'=>'bbb',
		);
	*/
	public function create($arr) {
		$arr['blogid'] = $this->maxid('+1');
		if($this->set($arr['blogid'], $arr)) {
			$this->count('+1');
			return $arr['blogid'];
		} else {
			$this->maxid('-1');
			return FALSE;
		}
	}
	
	public function update($blogid, $arr) {
		return $this->set($blogid, $arr);
	}
	
	public function read($blogid) {
		return $this->get($blogid);
	}

	public function _delete($blogid) {
		$return = $this->delete($blogid);
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
	
	public function check_message(&$message) {
		if(empty($message)) {
			return '内容不能为空。';
		}
		if(utf8::strlen($message) > 2000000) {
			return '内容不能超过200万个字符。';
		}
		return '';
	}
	
	// 用来显示给用户
	public function format(&$blog) {
		$blog['subject']  = htmlspecialchars($blog['subject']);
		$blog['message']  = $blog['message'];
		$blog['dateline_fmt'] = misc::date($blog['dateline'], 'Y-n-j H:i', $this->conf['timeoffset']);
	}
}
?>