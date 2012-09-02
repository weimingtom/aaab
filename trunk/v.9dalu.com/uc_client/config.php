<?php
/* UC的用户接口地址 */
define('UC_KEY', '123456');				// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
define('UC_API', 'http://v.9dalu.com/uc');		// UCenter 的 URL 地址, 在调用头像时依赖此常量
define('UC_CHARSET', 'utf8');				// UCenter 的字符集
define('UC_IP', '');					// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
define('UC_APPID', 3);					// 当前应用的 ID
define('UC_CLIENT_VERSION', '1.5.0');
define('UC_CLIENT_RELEASE', '20081212');