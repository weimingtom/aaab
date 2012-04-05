<?php
function getContentUrl($url, $referer='', $post=array()) {
	$ch = curl_init($url);
	
//	curl_setopt($ch, CURLOPT_ENCODING, 'gb2312'); 
	// ������· 
	if ($referer) {
		curl_setopt($ch, CURLOPT_REFERER, $referer); 
	}
	// ��ֱ���������� 
	//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ���ݷ��� 
	if($post) {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$s = curl_exec($ch); 
	curl_close($ch); 
	return $s;
}

// ��ȡget,post,request,cookie,server������
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
	$isRight = 1;	// 1��ȷ��0����
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

// ��ȡָ����Χ��IP��ַ
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

//��¼����ϵͳ���д���log��־��Ϣ
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

//дlog �ļ�����ʽ���£�������־��ʽ log/pid/������e.log  �û���־��ʽ log/pid/������u.log
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

/*����Ŀ¼*/
function mkdirs($path , $mode = 0777 )
{    
  if(!is_dir($path))
  {    
      mkdirs(dirname($path),$mode);    
      mkdir($path,$mode);    
  }
  return true;    
}

//���IP�Ƿ���ȷ
function checkIp($ip) {
	$isRight = 1;	//1��ȷ		0����
	$preg = '/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([0-9]|([0-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))$/';
	if(preg_match($preg, $ip)) {
		$isRight = 1;
	} else {
		$isRight = 0;
	}	
	return $isRight;
}

function saveFileInfo($fileid, $info) {
	file_put_contents(ROOT_PATH.'/syslog/'.$fileid.'.info', serialize($info));
}

function getFileInfo($fileid) {
	if (file_exists(ROOT_PATH.'/syslog/'.$fileid.'.info')) {
		$s = file_get_contents(ROOT_PATH.'/syslog/'.$fileid.'.info');
		return unserialize($s);
	} else {
		return NULL;
	}
}