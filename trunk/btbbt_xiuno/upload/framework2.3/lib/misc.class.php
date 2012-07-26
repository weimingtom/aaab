<?php

/*
 * Copyright (C) xiuno.com
 */

class misc {

	public static function page($key = 'page') {
		return max(1, intval(core::gpc($key, 'R')));
	}
	
	/*
		misc::page('?thread-index.htm');
		misc::page('thread-index.htm');
		misc::page('index.php');
		misc::page('index.php?a=b');
	*/
	public static function pages($url, $totalnum, $page, $pagesize = 20) {
		// ?xxx.htm 认为也是支持 rewrite 格式的
		$urladd = '';
		if(strpos($url, '.htm') !== FALSE) {
			list($url, $urladd) = explode('.htm', $url);
			$urladd = '.htm'.$urladd;
			$rewritepage = '-page-';
		} else {
			$url .= strpos($url, '?') === FALSE ? '?' : '&';
			$rewritepage = 'page=';
		}

		$totalpage = ceil($totalnum / $pagesize);
		if($totalpage < 2) return '';
		$page = min($totalpage, $page);
		$shownum = 5;	// 显示多少个页 * 2
		
		$start = max(1, $page - $shownum);
		$end = min($totalpage, $page + $shownum);
		
		// 不足 $shownum，补全左右两侧
		$right = $page + $shownum - $totalpage;
		$right > 0 && $start = max(1, $start -= $right);
		$left = $page - $shownum;
		$left < 0 && $end = min($totalpage, $end -= $left);
		
		$s = '';
		$page != 1 && $s .= '<a href="'.$url.$rewritepage.($page - 1).$urladd.'">上一页</a>';
		if($start > 1) $s .= '<a href="'.$url.$rewritepage.'1'.$urladd.'">1 '.($start > 2 ? '... ' : '').'</a>';
		for($i=$start; $i<=$end; $i++) {
			if($i == $page) {
				$s .= '<b>'.$i.'</b>';// checked
			} else {
				$s .= '<a href="'.$url.$rewritepage.$i.$urladd.'">'.$i.'</a>';
			}
		}
		if($end != $totalpage) $s .= '<a href="'.$url.$rewritepage.$totalpage.$urladd.'">'.($totalpage - $end > 1 ? '... ' : '').$totalpage.'</a>';
		$page != $totalpage && $s .= '<a href="'.$url.$rewritepage.($page + 1).$urladd.'">下一页</a>';
		return $s;
	}
	
	// 简单的上一页，下一页，比较省资源，不用count(), 推荐使用。
	public static function simple_page($url, $totalnum, $page, $pagesize) {
		// ?xxx.htm 认为也是支持 rewrite 格式的
		$urladd = '';
		if(strpos($url, '.htm') !== FALSE) {
			list($url, $urladd) = explode('.htm', $url);
			$urladd = '.htm'.$urladd;
			$rewritepage = '-page-';
		} else {
			$url .= strpos($url, '?') === FALSE ? '?' : '&';
			$rewritepage = 'page=';
		}
		
		$s = '';
		$page > 1 && $s .= '<a href="'.$url.$rewritepage.($page - 1).$urladd.'">上一页</a>';
		$totalnum >= $pagesize && $s .= '<a href="'.$url.$rewritepage.($page + 1).$urladd.'">下一页</a>';
		return $s;
	}
	
	public static function set_cookie($key, $value, $time = 0, $path = '', $httponly = FALSE) {
		if($value != NULL) {
			if(version_compare(PHP_VERSION, '5.2.0') >= 0) {
				setcookie($key, $value, $time, $path, '', FALSE, $httponly);
			} else {
				setcookie($key, $value, $time, $path, '', FALSE);
			}
		} else {
			if(version_compare(PHP_VERSION, '5.2.0') >= 0) {
				setcookie($key, '', $time, $path, '', FALSE, $httponly);
			} else {
				setcookie($key, '', $time, $path, '', FALSE);
			}
		}
	}
	
	public static function form_hash($public_key) {
		return substr(md5(substr($_SERVER['time'], 0, -5).$public_key), 16);
	}
	
	// 校验 formhash
	public static function form_submit($public_key) {
		$hash = core::gpc('FORM_HASH', 'R');
		return $hash == self::form_hash($public_key);
	}
	
	// 当前的URL，包含路径，格式如 http://www.domain.com/blog/
	public static function get_url_path() {
		$port = core::gpc('SERVER_PORT', 'S');
		//$portadd = ($port == 80 ? '' : ':'.$port);
		$host = core::gpc('HTTP_HOST', 'S');	// host 里包含 port
		//$schme = self::gpc('SERVER_PROTOCOL', 'S');
		$path = substr(core::gpc('PHP_SELF', 'S'), 0, strrpos(core::gpc('PHP_SELF', 'S'), '/'));
		return  "http://$host$path/";
	}
	
	//
	public static function get_script_uri() {
		$port = core::gpc('SERVER_PORT', 'S');
		//$portadd = $port == 80 ? '' : ':80';
		$host = core::gpc('HTTP_HOST', 'S');
		//$schme = self::gpc('SERVER_PROTOCOL', 'S');
		
		// [SERVER_SOFTWARE] => Microsoft-IIS/6.0
		// [REQUEST_URI] => /index.php
		// [HTTP_X_REWRITE_URL] => /?a=b
		// iis
		if(isset($_SERVER['HTTP_X_REWRITE_URL'])) {
			$request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
		} else {
			$request_uri = $_SERVER['REQUEST_URI'];
		}
		return  "http://$host".$request_uri;
		//if(isset($_SERVER['SCRIPT_URI']) && 0) {
		//	return $_SERVER['SCRIPT_URI'];// 会漏掉 query_string, .core::gpc('QUERY_STRING', 'S');
		//}
	}
	
	public static function date($time, $format = 'Y-n-j H:i', $timeoffset = '+8') {
		return gmdate($format, $time + $timeoffset * 3600);
	}
	
	// 依赖于 $_SERVER['time_today']
	public static function minidate($time, $timeoffset = 8) {
		$sub = $_SERVER['time_today'] - $time;
		if($sub < 0) {
			$format = 'H:i';
		// todo: 此处可能会有BUG，一年最后一个月
		} elseif($sub > 31536000) {
			$format = 'Y-n';
		} elseif($sub > 86400) {
			$format = 'n-j';
		} else {
			$format = 'n-j';
		}
		return gmdate($format, $time + $timeoffset * 3600);
	}
	
	public static function humandate($timestamp) {
		$seconds = $_SERVER['time'] - $timestamp;
		if($seconds > 31536000) {
			return self::date($timestamp, 'Y-n-j');
		} elseif($seconds > 2592000) {
			return ceil($seconds / 2592000).'月前';
		} elseif($seconds > 86400) {
			return ceil($seconds / 86400).'天前';
		} elseif($seconds > 3600) {
			return ceil($seconds / 3600).'小时前';
		} elseif($seconds > 60) {
			return ceil($seconds / 60).'分钟前';
		} else {
			return $seconds.'秒前';
		}
	}
	
	public static function humannumber($num) {
		$num > 100000 && $num = ceil($num / 10000).'万';
		return $num;
	}
	
	public static function humansize($num) {
		if($num > 1000000) {
			return number_format($num / 1000000, 2, '.', '').'M';
		} elseif($num > 1000) {
			return number_format($num / 1000, 2, '.', '').'K';
		} else {
			return $num.'B';
		}
	}
	
	// 判断数组的值是否为空，如果有一项为空那么就为空
	public static function values_empty($arr) {
		return implode('', $arr) == '' ? TRUE : FALSE;
	}
	
	public static function array_to_urladd($arr) {
		$s = '';
		foreach((array)$arr as $k=>$v) {
			$s .= "-$k-".urlencode($v);
		}
		return $s;
	}
	
	// 从一个二维数组中取出一个 key=>value 格式的一维数组
	public static function arrlist_key_values(&$arrlist, $key, $value) {
		$return = array();
		if($key) {
			foreach($arrlist as $arr) {
				$return[$arr[$key]] = $arr[$value];
			}
		} else {
			foreach($arrlist as $arr) {
				$return[] = $arr[$value];
			}
		}
		return $return;
	}
	
	// 从一个二维数组中取出一个 values() 格式的一维数组，某一列key
	public static function arrlist_values(&$arrlist, $key) {
		$return = array();
		foreach($arrlist as &$arr) {
			$return[] = $arr[$key];
		}
		return $return;
	}
	
	/* 对多维数组排序
		$data = array();
		$data[] = array('volume' => 67, 'edition' => 2);
		$data[] = array('volume' => 86, 'edition' => 1);
		$data[] = array('volume' => 85, 'edition' => 6);
		$data[] = array('volume' => 98, 'edition' => 2);
		$data[] = array('volume' => 86, 'edition' => 6);
		$data[] = array('volume' => 67, 'edition' => 7);
		arrlist_multisort($data, 'edition', TRUE);
	*/
	public static function arrlist_multisort(&$arrlist, $col, $asc = TRUE) {
		$colarr = array();
		foreach($arrlist as $k=>$arr) {
			$colarr[$k] = $arr[$col];
		}
		$asc = $asc ? SORT_ASC : SORT_DESC;
		array_multisort($colarr, $asc, $arrlist);
	}
	
	/*
		功能：将两个以空格隔开的字符串合并
		实例：echo str_merge('a b c', 'a1 a2');
		结果：a b c a1 a2
	*/
	public static function key_str_merge($haystack, $needle) {
		$haystack .= ' '.$needle;
		$arr = explode(' ', $haystack);
		$arr = array_unique($arr);
		return trim(implode(' ', $arr));
	}
	
	/*
		功能：将字符 $s2 从 $haystack 中去掉
		实例：echo key_str_strip('a b c', 'a b');
		结果：c
	*/
	public static function key_str_strip($haystack, $needle) {
		$haystack = " {$haystack} ";
		$arr = explode(' ', trim($needle));
		foreach($arr as $v) {
			$haystack = str_replace(' '.$v.' ', ' ', $haystack);
		}
		return trim($haystack);
	}
	
	public static function in_key_str($needle, $haystack) {
		return strpos(" {$needle} ", " {$haystack} ") !== FALSE;
	}
	
	// 安全过滤，过滤掉所有特殊字符，仅保留英文下划线，中文。其他语言需要修改U的范围
	public static function safe_str(&$s, $ext = '') {
		$ext = preg_quote($ext);
		$s = preg_replace('#[^'.$ext.'\w\x{4e00}-\x{9fa5}]+#u', '', $s);
	}
	
	// 转换空白字符, $onlytab 仅仅转换 \t
	public static function html_space($s, $onlytab = FALSE) {
		if($onlytab) {
			$s = str_replace("\t", '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ', $s);
			return $s;
		} else {
			$s = str_replace(' ', '&nbsp;', $s);
			$s = str_replace("\t", '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ', $s);
			$s = nl2br($s);
			return $s;
		}
	}
		
	public static function get_url($url, $timeout = 5) {
		if(ini_get('allow_url_fopen')) {
			// 尝试连接
			$opts = array ('http'=>array('method'=>'GET', 'timeout'=>$timeout)); 
			$context = stream_context_create($opts);  
			$html = file_get_contents($url, false, $context);  
			return $html;
		} else {
			$limit = 500000;
			$ip = '';
			$return = '';
			$matches = parse_url($url);
			$host = $matches['host'];
			$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
			$port = !empty($matches['port']) ? $matches['port'] : 80;
		
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie:\r\n\r\n";
			$host == 'localhost' && $ip = '127.0.0.1';
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
			if(!$fp) {
				return '';
			} else {
				stream_set_blocking($fp, TRUE);
				stream_set_timeout($fp, $timeout);
				@fwrite($fp, $out);
				$status = stream_get_meta_data($fp);
				if(!$status['timed_out']) {
					while (!feof($fp)) {
						if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
							break;
						}
					}
		
					$stop = false;
					while(!feof($fp) && !$stop) {
						$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
						$return .= $data;
						if($limit) {
							$limit -= strlen($data);
							$stop = $limit <= 0;
						}
					}
				}
				@fclose($fp);
				return $return;
			}
		}
	}
}

?>