<?php

// 合并 abc/def/../ 为 abc/
function xn_realpath($path) {
	$path = str_replace('\\', '/', $path);
	$i = 0;
	while(strpos($path, '../') !== FALSE) {
		if($i++ > 10) break; // 最多 10 层，另外防止死循环，比如 ./abc/../
		$path = preg_replace('#\w+\/\.\./#', '', $path);
	}
	return $path;
}

function message($s) {
	global $conf;
	include './header.inc.php';	
	echo "<div class=\"bg1 border\" style=\"padding: 16px;\">$s</div>";
	include './footer.inc.php';
	exit;
}

function truncate_dir($dir) {
	$dh = opendir($dir);
	while(($file = readdir($dh)) !== false ) {
		if($file != "." && $file != ".." ) {
			if(is_dir( $dir . $file ) ) {
				//opendir_recursive( $dir . $file . "/", $recall);
			} else {
				unlink($dir."$file");
			}
		}
	}
	closedir($dh);
}

function clear_cache($dir, $pre) {
	$dh = opendir($dir);
	while(($file = readdir($dh)) !== false ) {
		if($file != "." && $file != ".." ) {
			if(is_dir( $dir . $file ) ) {
				//opendir_recursive( $dir . $file . "/", $recall);
			} else {
				if(substr($file, 0, strlen($pre)) == $pre) {
					unlink($dir."$file");
				}
			}
		}
	}
	closedir($dh);
}


function get_env(&$env, &$write) {
	$env['php_version']['name'] = 'PHP Version';
	$env['php_version']['must'] = TRUE;
	$env['php_version']['current'] = PHP_VERSION;
	$env['php_version']['need'] = '5.0';
	$env['php_version']['status'] = version_compare(PHP_VERSION , '5') > 0;

	$spl_autoload_register = function_exists('spl_autoload_register');
	$env['spl_autoload_register']['name'] = 'Auto Load(SPL)';
	$env['spl_autoload_register']['must'] = TRUE;
	$env['spl_autoload_register']['current'] = $spl_autoload_register ? '开启' : '<a href="http://www.php.net/spl">SPL未开启</a>';
	$env['spl_autoload_register']['need'] = '开启';
	$env['spl_autoload_register']['status'] = $spl_autoload_register;
	
	// 头像缩略需要，没有也可以。
	if(function_exists('gd_info')) {
		$gd_info = gd_info();
		preg_match('/\d(?:.\d)+/', $gd_info['GD Version'], $arr);
		$gd_version = $arr[0];
		$env['gd_version']['name'] = 'GD Version';
		$env['gd_version']['must'] = FALSE;
		$env['gd_version']['current'] = $gd_version;
		$env['gd_version']['need'] = '1.0';
		$env['gd_version']['status'] = version_compare($gd_version , '1') > 0 ? 1 : 2;
	} else {
		$env['gd_version']['name'] = 'GD Version';
		$env['gd_version']['must'] = FALSE;
		$env['gd_version']['current'] = 'None';
		$env['gd_version']['need'] = '1.0';
		$env['gd_version']['status'] = 2;
	}

	// 目录可写
	$upload_tmp_dir = ini_get('upload_tmp_dir');
	$upload_tmp_dir = $upload_tmp_dir ? $upload_tmp_dir : getenv('TEMP');
	$writedir = array(BBS_PATH.'tmp', BBS_PATH.'upload', BBS_PATH.'upload/attach', BBS_PATH.'upload/avatar', BBS_PATH.'upload/forum', 
		BBS_PATH.'upload/friendlink', BBS_PATH.'log', BBS_PATH.'plugin', BBS_PATH.'conf/conf.php', BBS_PATH.'conf/mail.php', 
		BBS_PATH.'plugin/announcement/conf.php', BBS_PATH.'plugin/anti_spam/conf.php',  BBS_PATH.'plugin/index_four_column/conf.php', 
		BBS_PATH.'plugin/vip_seo/conf.php', BBS_PATH.'plugin/ucenter/conf.php', BBS_PATH.'plugin/denglu/conf.php', 
		BBS_PATH.'plugin/view_width_screen/conf.php',  BBS_PATH.'plugin/view_blue/conf.php',  
		);//$upload_tmp_dir
	$write = array();
	foreach($writedir as &$dir) {
		//$dir = realpath($dir);
		$write[$dir] = xn_writable($dir);
	}
}

function xn_writable($file) {
	// 主要是兼容 windows
	try {
		if(is_file($file)) {
			if(strpos(strtoupper(PHP_OS), 'WIN') !== FALSE) {
				$fp = fopen($file, 'rb+');
				fclose($fp);
				return $fp;
			} else {
				return is_writable($file);
			}
		} elseif(is_dir($file)) {
			$tmpfile = $file.'/____tmp.tmp';
			$fp = fopen($tmpfile, 'wb+');
			fwrite($fp, 'a');
			fclose($fp);
			$isfile = is_file($tmpfile);
			unlink($tmpfile);
			return $fp;
		}
	} catch(Exception $e) {
		return false;
	}
}

function file_line_replace($configfile, $startline, $endline, $replacearr) {
	// 从16行-33行，正则替换
	$arr = file($configfile);
	$arr1 = array_slice($arr, 0, $startline - 1); // 此处: startline - 1 为长度
	$arr2 = array_slice($arr, $startline - 1, $endline - $startline + 1); // 此处: startline - 1 为偏移量
	$arr3 = array_slice($arr, $endline);
	
	$s = implode("", $arr2);
	foreach($replacearr as $k=>$v) { 
		$s = preg_replace('#\''.preg_quote($k).'\'\s*=\>\s*\'?.*?\'?,#ism', "'$k' => '$v',", $s);
	}
	$s = implode("", $arr1).$s.implode("", $arr3);
	file_put_contents($configfile, $s);
}

function get_key_add($primarykey, $arr) {
	$s = '';
	foreach($primarykey as $col=>$v) {
		$s .= "-$col-".$arr[$col];
	}
	return $s;
}

?>