<?php

/*
 * Copyright (C) xiuno.com
 */

// 调试模式: 1 打开，0 关闭
define('DEBUG', 1);
define('BBS_PATH', '../');
define('FRAMEWORK_PATH', BBS_PATH.'framework2.3/');

// 加载应用的配置文件
$conf = include BBS_PATH.'conf/conf.php';
$adminconf = include BBS_PATH.'admin/conf/conf.php';
$conf = array_merge($conf, $adminconf);

// 临时目录
define('FRAMEWORK_TMP_PATH', $conf['tmp_path']);

// 日志目录
define('FRAMEWORK_LOG_PATH', $conf['log_path']);

include FRAMEWORK_PATH.'core.php';
core::init();
core::ob_start();
core::run();
// 完毕

?>