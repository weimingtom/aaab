<?php
@session_start();

header('Content-type: text/html;charset=utf-8');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if ($_SESSION['mid']!=1){

header("Location: /index.php?app=admin");
	exit;
}


define('SITE_PATH', getcwd());

$_config=include('../../../../config.inc.php');
mysql_connect($_config['DB_HOST'], $_config['DB_USER'], $_config['DB_PWD']);
mysql_select_db($_config['DB_NAME']);
mysql_query("SET NAMES 'utf8'");

function cut_str($sourcestr, $cutlength = 12, $etc = ''){
	$returnstr = '';
	$i = 0;
	$n = 0.0;
	$str_length = strlen($sourcestr);
	while ( ($n<$cutlength) and ($i<$str_length) ){
		$temp_str = substr($sourcestr, $i, 1);
		$ascnum = ord($temp_str);
		if ( $ascnum >= 252){
			$returnstr = $returnstr . substr($sourcestr, $i, 6);
			$i = $i + 6;
			$n++;
		}elseif ( $ascnum >= 248 ){
			$returnstr = $returnstr . substr($sourcestr, $i, 5);
			$i = $i + 5;
			$n++;
		}elseif ( $ascnum >= 240 ){
			$returnstr = $returnstr . substr($sourcestr, $i, 4);
			$i = $i + 4;
			$n++;
		}elseif ( $ascnum >= 224 ){
			$returnstr = $returnstr . substr($sourcestr, $i, 3);
			$i = $i + 3 ;
			$n++;
		}elseif ( $ascnum >= 192 ){
			$returnstr = $returnstr . substr($sourcestr, $i, 2);
			$i = $i + 2;
			$n++;
		}elseif ( !(array_search($ascnum, array(37, 38, 64, 109 ,119)) === FALSE) ){
			$returnstr = $returnstr . substr($sourcestr, $i, 1);
			$i = $i + 1;
			$n++;
		}else{
			$returnstr = $returnstr . substr($sourcestr, $i, 1);
			$i = $i + 1;
			$n = $n + 0.5;
		}
	}
	if ( $i < $str_length ){
		$returnstr = $returnstr . $etc;
	}
	return $returnstr;
}
?>
