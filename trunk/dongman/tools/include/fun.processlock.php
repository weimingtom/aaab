<?php
function process_lock($pid) {
	$filename = md5($pid).".lock";

	$process_name = "php";
	$cur_pid = posix_getpid();

	$status_detail[0] = date("Y-m-d H:i:s");
	$status_detail[1] = $cur_pid;
	$status_detail[2] = $pid;

	$tmp_content = implode("|",$status_detail);

	if ($fp = fopen(LOCK_PATH."/".$filename, "wb"))
	{
		fputs($fp, $tmp_content);
		ftruncate($fp, strlen($tmp_content));
		fclose($fp);
	}
}

function process_unlock($pid) {
	$filename = md5($pid).".lock";
	unlink(LOCK_PATH."/".$filename);
}

function process_islocked($pid) {
	$filename = md5($pid).".lock";

	$process_name = "php";

	if (!file_exists(LOCK_PATH."/".$filename))
	{
		return false;
	}
	elseif ($fp = fopen(LOCK_PATH."/".$filename, "rb"))
	{
		flock($fp, LOCK_SH);
		$content = fread($fp, 512);
		fclose($fp);
	}
	else
	{
		echo "lock status file (".LOCK_PATH."/".$filename.") error!\n";
		return false;
	}

	$status_detail = explode("|", $content);
	$cur_stamp = $status_detail[0];
	$last_pid = $status_detail[1];

	$cur_pids = system("ps -p $last_pid -o pid= -o comm=");

	$array_pid = explode(" ", trim($cur_pids));
	//echo $last_pid;
	//print_r($array_pid);
	//print_r(!empty($array_pid[0]) && $array_pid[0] == $last_pid);
	if (!empty($array_pid[0]) && $array_pid[0] == $last_pid)
	{
		//echo "1";
		return true;
	}
	else
	{
		//echo "0";
		return $status_detail;
	}
}

function process_savelock($pid,$file,$location) {
	$filename = md5($pid).".lock";
	$content = file_get_contents(LOCK_PATH."/".$filename);
	$data = str_pad(substr($content, 0, 64), 64)."|".$file."|".$location;
	file_put_contents(LOCK_PATH."/".$filename, $data);
}

function process_getlock($pid) {
	$filename = md5($pid).".lock";
	//echo $filename;
	$content = file_get_contents(LOCK_PATH."/".$filename);
	$data = substr($content, 64);
	return $data;
}
?>