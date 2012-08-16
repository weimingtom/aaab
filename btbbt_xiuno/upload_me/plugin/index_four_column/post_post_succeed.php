	$allowread = 1;
	// 查看游客的访问权限
	if($forum['accesson']) {
		$access = $this->forum_access->read($fid, 0);
		$allowread = $access['allowread'];
	}
	if($allowread) {
		$file = $this->conf['tmp_path'].'new_post_tids.txt';
		$s = is_file($file) ? trim(file_get_contents($file)) : '';
		if($s) {
			strpos(' '.$s.' ', " thread-fid-$fid-tid-$tid ") === FALSE && $s .= " thread-fid-$fid-tid-$tid";
			// 只保留10个
			$arr = explode(' ', $s);
			if(count($arr) > 10) $arr = array_slice($arr, -10, 10);
			$s = implode(' ', $arr);
		} else {
			$s = " thread-fid-$fid-tid-$tid";
		}
		file_put_contents($file, $s);
	}