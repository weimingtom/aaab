<?php
function get_naps_bot()
{
	if (!isset($_SERVER['HTTP_USER_AGENT'])) {
		return false;
	}
	
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	
	if (strpos($useragent, 'googlebot') !== false){
		return 'Googlebot';
	}
	
	if (strpos($useragent, 'Googlebot') !== false){
		return 'Googlebot';
	}
	
	if (strpos($useragent, 'msnbot') !== false){
		return 'MSNbot';
	}
	
	if (strpos($useragent, 'slurp') !== false){
		return 'Yahoobot';
	}
	
	if (strpos($useragent, 'baiduspider') !== false){
		return 'Baiduspider';
	}
	
	if (strpos($useragent, 'Baiduspider') !== false){
		return 'Baiduspider';
	}
	
	if (strpos($useragent, 'sogou') !== false){ 
		return 'sogou spider';
	}
	
	if (strpos($useragent, 'soso') !== false){ 
		return 'sosospider';
	}
	
	if (strpos($useragent, 'lycos') !== false){
		return 'Lycos';
	}
	
	if (strpos($useragent, 'robozilla') !== false){
		return 'Robozilla';
	} 
	return false;
}


//获取当前登陆用户IP
function get_ip1(){
	if ($_SERVER['REMOTE_ADDR']){
		$ip = $_SERVER['REMOTE_ADDR'];
	}elseif (getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	} elseif (getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	} else {
		$ip = "未知";
	}
return $ip;
}

?>