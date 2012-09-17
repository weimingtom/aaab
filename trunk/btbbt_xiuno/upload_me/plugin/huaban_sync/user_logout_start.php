//<?php
$ishuabanlogout = core::gpc('ishuabanlogout');
if($ishuabanlogout) {
	misc::set_cookie($this->conf['cookiepre'].'auth', '', 0, '/');
	exit;
}