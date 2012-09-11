<?php
error_reporting(E_ALL);

$sync_mid = isset($_COOKIE['mid']) ? $_COOKIE['mid'] : '';
$sync_uname = isset($_COOKIE['uname']) ? $_COOKIE['uname'] : '';
//print_r($_COOKIE);
if ($sync_mid && $sync_uname) {
	session_start();
	$_SESSION['mid'] = $sync_mid;
	$_SESSION['uname'] = $sync_uname;
	
	setcookie('mid', null, 0, '/');
	setcookie('uname', null, 0, '/');
}