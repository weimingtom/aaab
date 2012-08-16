<?php

/*
 * Copyright (C) xiuno.com
 */

/*
	点击服务器，需要解决参数合法性判断，范围，存储空间不足等问题！

	GET 参数:
	/?r=1,2,3&w=1,2,3
	
	返回 JSON 格式：
	var xn_json = {"1":"100","2":"101","3":"1000"}
	
*/

// 调试模式: 1 打开，0 关闭
define('DEBUG', 1);

// 站点根目录
define('BBS_PATH', '../');

// check robot
$robots = array('robot', 'spider', 'slurp');
foreach($robots as $robot) {
	if(strpos($_SERVER['HTTP_USER_AGENT'], $robot) !== FALSE) {
		header("HTTP/1.0 403 Forbidden");
		exit;
	}
}

$conf = include BBS_PATH.'conf/conf.php';

$tmpfile = $conf['tmp_path'].'click_server.data';
$datafile = $conf['upload_path'].'click_server.data';

if(!is_file($tmpfile)) {
	copy($datafile, $tmpfile);
}

// read
$r = isset($_GET['r']) ? $_GET['r'] : '';
$r && $rs = explode(',', $r);
$fp = NULL;
if(!empty($rs)) {
	$fp = fopen($tmpfile, 'rb+');
	$s = '';
	foreach($rs as $tid) {
		$tid = intval($tid);
		fseek($fp, $tid * 4);
		$data = fread($fp, 4);
		$arr = unpack("L*", $data);	// unpack 出来的数组，下标从1开始。
		isset($arr[1]) && $s .= ',"'.$tid.'":'.'"'.$arr[1].'"';
	}
	$s = '{'.substr($s, 1).'}';
	$s = "var xn_json = $s;";
	echo $s;
} else {
	echo "var xn_json = {};";
}

$w = isset($_GET['w']) ? $_GET['w'] : '';
$w && $ws = explode(',', $w);
if(!empty($ws)) {
	empty($fp) && $fp = fopen($tmpfile, 'rb+');
	$tmpsize = filesize($tmpfile);
	$tidarr = array();
	foreach($ws as $tid) {
		$tid = intval($tid);
		if($tid > 100000000) continue;
		fseek($fp, $tid * 4);
		$data = fread($fp, 4);
		$arr = unpack("L*", $data);
		isset($arr[1]) && $tidarr[$tid] = $arr[1] + 1 ;
	}
	foreach($tidarr as $tid=>$views) {
		$tidpos = $tid * 4;
		
		// tid 暴增，不保存，直接中断。
		if($tidpos > $tmpsize * 2) {
			break;
		}
		
		// 扩容 2 倍
		if($tidpos > $tmpsize) {
			fseek($fp, $tmpsize * 2);
			fwrite($fp, pack("L*", 0x00), 4);
		}
		
		fseek($fp, $tidpos);
		$data = pack("L*", $views);
		fwrite($fp, $data, 4);
	}
	
	// 10分钟存盘一次，估算，每日 100w pv, 每秒点击次数10次，10分钟内点击次数为 6000 次，随机数设定为 1/1000 比较合适，10分钟内总有人会点击到。
	$time = time();
	if(substr($time, -2) == '000' && $time - filemtime($datafile) > 600) {
		copy($tmpfile, $datafile);
	}
}

$fp && fclose($fp);