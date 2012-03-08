<?php
function getContentUrl($url, $post=array()) {
	$ch = curl_init($url);
	
	//curl_setopt($ch,CURLOPT_ENCODING ,'gb2312'); 
	// 设置来路 
	//curl_setopt($curl, CURLOPT_REFERER, 'http://google.com/'); 
	// 不直接输入内容 
	//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回 
	if($post) {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$s = curl_exec($ch); 
	curl_close($ch); 
	return $s;
}

// 获取get,post,request,cookie,server的数据
function getgpc($k, $var='G') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
		case 'V': $var = &$_SERVER; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

function checkIpFormat($ip) {
	$ipArr = explode('.', $ip);
	$isRight = 1;	// 1正确，0错误
	if(count($ipArr) == 4) {
		foreach($ipArr as $n) {
			if(!is_numeric($n) || strlen($n)>3) {
				$isRight = 0;
			}
		}
	} else {
		$isRight = 0;
	}
	return $isRight;
}

// 获取指定范围的IP地址
function getRandIp($startIp, $endIp) {
	
	if (!$startIp || !$endIp) {
		return ;
	}
	
	$startIpArr = explode('.', $startIp);
	$endIpArr = explode('.', $endIp);
	
	if (count($startIpArr)!=4 || count($endIpArr)!=4) {
		return ;
	}
	
	$ip = '';
	foreach ($startIpArr as $k=>$v) 
	{
		if($endIpArr[$k] != $v) {
			foreach($startIpArr as $k2=>$v2) {
				if($k2>=$k) {
					if ($v2 < $endIpArr[$k2]) {
						$ip .= '.'.rand($v2, $endIpArr[$k2]);
					} else {
						$ip .= '.'.rand(0, $endIpArr[$k2]);
					}
					
				} else {
					if($ip) {
						$ip .= ".{$v2}";
					} else {
						$ip = $v2;
					}
				}
			}
			break;
		}
	}
	return $ip;
}

//记录当日系统运行错误log日志信息
function logSysError($log)
{
	$today = date('Ymd');
	$path = ROOT_PATH."/syslog/";
	$filename = $today."sys.log";
	if(!is_dir($path)) {
		mkdirs($path);
	}
	logFile($path.$filename, date("Y-m-d H:i:s").' '.$log."\r\n");
}

//写log 文件，格式如下：错误日志格式 log/pid/年月日e.log  用户日志格式 log/pid/年月日u.log
function logFile($logfile,$content,$mode="a+")
{
	if($logfile)
	{
		$handle = fopen($logfile, $mode);
		if($handle)
		{
			fwrite($handle, $content);
	    fclose($handle);
		}
	}
}

/*生成目录*/
function mkdirs($path , $mode = 0777 )
{    
  if(!is_dir($path))
  {    
      mkdirs(dirname($path),$mode);    
      mkdir($path,$mode);    
  }
  return true;    
}

//检查IP是否正确
function checkIp($ip) {
	$isRight = 1;	//1正确		0错误
	$preg = '/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/';
	if(preg_match($preg, $ip)) {
		$isRight = 1;
	} else {
		$isRight = 0;
	}	
	return $isRight;
}