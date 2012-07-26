<?php

/*
 * Copyright (C) xiuno.com
 */

function strext() {
	return max(1, intval(core::gpc('page', 'R')));
}

function path1_to_path2($path1, $path2) {
	$path1 = str_replace('\\', '/', realpath($path1));
	$path2 = str_replace('\\', '/', realpath($path2));
	$n1 = substr_count($path1, '/', 0, strlen($path1));
	$n2 = substr_count($path2, '/', 0, strlen($path2));
	if($n2 > $n1) {
		return str_repeat('../', abs($n1 - $n2));
	} else {
		return 1;
	}
}

// 查找字符串出现
function strpos_n($haystack, $needle, $n) {
	$offset = 0;
	while($offset !== FALSE && $n--) {
		$offset = strpos($haystack, $needle, $offset);	// 从第一个查找
		$offset !== FALSE && $offset += strlen($needle);
	}
	$offset !== FALSE && $offset -= strlen($needle);
	return $offset;
}

// 反向查找字符串出现
function strrpos_n($haystack, $needle, $n) {
	// 首先查询要查找的字符一共出现多少次
	$str_count = substr_count($haystack, $needle);
	if($n > $str_count) {
		return FALSE;
	}
	$forward = ($str_count - $n) + 1;	// (一共出现的次数 - 反向出现次数) + 1 = 正向出现的次数
	$offset = strpos_n($haystack, $needle, $forward);
	return $offset;
}

?>