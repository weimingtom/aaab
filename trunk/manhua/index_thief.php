<?php
$fromURL = 'http://baike.baidu.com/';
$targetURL = 'http://www.thief.com/';

$requestURL = $_SERVER['REQUEST_URI'];
if($requestURL == '/') {
	$requestURL = $fromURL;
} elseif(strpos($requestURL, '$$$') !== false) {
	$r = explode('$$$', $requestURL);
	$requestURL = $r[1].$r[0];
	unset($r);
} else {
	$requestURL = $fromURL.substr($requestURL, 1);
}

$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $requestURL);        
curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$tmpInfo = curl_exec($curl);  
curl_close($curl);

$tpl = array();
preg_match_all("/<a href=\"([^\"]*?)\"/is", $tmpInfo, $tpl);
$urlArr = $tpl[1];
$newUrlArr = array();
foreach($urlArr as $v) {
	if(strpos(strtolower($v), 'http://')!==false) {
		$position = strpos($v, '/', 8);
		if($position !== false) {
			$domain = substr($v, 0, $position+1);
			if($domain != $fromURL) {
				$newUrlArr[] = $targetURL.substr($v, $position+1).'$$$'.substr($v, 0, $position);
			} else {
				$newUrlArr[] = str_replace($fromURL, $targetURL, $v);
			}
		} else {
			$newUrlArr[] = $targetURL;
		}
	} else{
		if($v == '/') {
			$newUrlArr[] = $targetURL;
		} elseif($v && $v{0}=='/') {
			$newUrlArr[] = $targetURL.substr($v, 1);
		} else {
			$newUrlArr[] = $targetURL;
		}
	}
}

//$urlArr = array_values($urlArr);
//$newUrlArr = array_values($newUrlArr);
$tmpInfo = str_replace(replaceHref($urlArr), replaceHref($newUrlArr), $tmpInfo);
echo $tmpInfo;
//print_r(replaceHref($newUrlArr));
//print_r($tpl);
//preg_replace("", "", $tmpInfo);

//echo $tmpInfo;

function replaceHref($arr) {
	$r = array();
	if ($arr && is_array($arr)) {
		foreach($arr as $v) {
			$r[] = '<a href="'.$v.'"';
		}
	}
	return $r;
}

function replacecode($getStr) {
        $getStr = str_ireplace(array("\r","\n","\t"),"",$getStr);
        $getStr = preg_replace( '@<!--(.+?)-->@is', '', $getStr );
        $getStr = preg_replace( '@\/\*(.+?)\*\/@is', '', $getStr );
        $getStr = preg_replace( '/\s+/', ' ', $getStr );
        return $getStr;
}