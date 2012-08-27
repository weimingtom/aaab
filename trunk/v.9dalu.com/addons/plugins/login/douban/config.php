<?php
$key = model('Xdata')->lget('platform');
define('DOUBAN_KEY',	$key['douban_key']);
define('DOUBAN_SECRET',	$key['douban_secret']);