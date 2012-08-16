<?php
// mail发送方式, 0:PHP内置函数 mail(), 1: SMTP 方式
return array(
	'sendtype' => 1,

	'smtplist' => array (
  0 => 
  array (
    'email' => 'www@qq.com',
    'host' => 'smtp.qq.com',
    'port' => '25',
    'user' => '12222111',
    'pass' => '1111',
  ),
)
);

?>