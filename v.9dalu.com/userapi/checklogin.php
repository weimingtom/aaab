<?php
error_reporting(E_ALL);
session_start();

$sync_mid = isset($_COOKIE['dalu_mid']) ? $_COOKIE['dalu_mid'] : '';
$sync_uname = isset($_COOKIE['dalu_uname']) ? $_COOKIE['dalu_uname'] : '';

if ($sync_mid && $sync_uname) {
	$_SESSION['mid'] = $sync_mid;
	$_SESSION['uname'] = $sync_uname;
}