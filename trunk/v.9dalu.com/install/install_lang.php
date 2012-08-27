<?php
if (!defined('THINKSNS_INSTALL'))
{
	exit ('Access Denied');
}

$i_message['install_lock'] = '您已安装过ThinkSNS 2.3，如果需要重新安装，请先删除install目录下的install.lock文件';
$i_message['install_title'] = 'ThinkSNS 2.3 安装向导';
$i_message['install_wizard'] = '安装向导';
$i_message['install_warning'] = '<strong>注意 </strong>这个安装程序仅仅用在你首次安装ThinkSNS。如果你已经在使用 ThinkSNS 或者要更新到一个新版本，请不要运行这个安装程序。';
$i_message['install_intro'] = '<h4>安装须知</h4><p>一、运行环境需求：PHP(5.2.0+)+MYSQL(4.1+)</p><p>二、安装步骤：<br /><br />1、使用ftp工具以二进制模式，将该软件包里的 thinksns 目录及其文件上传到您的空间，假设上传后目录仍旧为 thinksns。<br /><br />2、如果您使用的是Linux 或 Freebsd 服务器，先确认以下目录或文件属性为 (777) 可写模式。<br /><br />目录: data<br />目录: _runtime<br />目录: install<br />文件: config.inc.php<br />文件: define.inc.php<br />3、运行 http://yourwebsite/thinksns/install/install.php 安装程序，填入安装相关信息与资料，完成安装！<br />4、运行 http://yourwebsite/thinksns/cleancache.php 清除系统缓存文件！<br />5、运行 http://yourwebsite/thinksns/index.php 开始体验ThinkSNS2.3！</p>';
$i_message['install_start'] = '开始安装ThinkSNS';
$i_message['install_license_title'] = '安装许可协议';
$i_message['install_license'] = '版权所有 (C) 2007-2009，ThinkSNS.com 保留所有权利。

ThinkSNS是由ThinkSNS项目组独立开发的SNS程序，基于PHP脚本和MySQL数据库。本程序源码开放的，任何人都可以从互联网上免费下载，并可以在不违反本协议规定的前提下进行使用而无需缴纳程序使用费。

官方网址： www.ThinkSNS.com 交流社区： t.ThinkSNS.com

为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：

一、本授权协议适用且仅适用于ThinkSNS任何版本，ThinkSNS官方拥有对本授权协议的最终解释权和修改权。

二、协议许可的权利和限制
1、您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用，但我们也不承诺对个人用户提供任何形式的技术支持。
2、您可以在协议规定的约束和限制范围内修改ThinkSNS源代码或界面风格以适应您的网站要求，但不可以公开对外发布。
3、您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。
4、未经商业授权，不得将本软件用于商业用途(企业网站或以盈利为目的经营性网站)，否则我们将保留追究的权力。

三、免责声明
1、本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
2、用户出于自愿而使用本软件，您必须了解使用本软件的风险，任何情况下，程序的质量风险和性能风险完全由您承担。有可能证实该程序存在漏洞，您需要估算与承担所有必需服务，恢复，修正，甚至崩溃所产生的代价！在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。
3、请务必仔细阅读本授权协议，在您同意授权协议的全部条件后，即可继续ThinkSNS的安装。即：您一旦开始安装ThinkSNS，即被视为完全同意本授权协议的全部内容，如果出现纠纷，我们将根据相关法律和协议条款追究责任。

';
$i_message['install_agree'] = '我已看过并同意安装许可协议';
$i_message['install_disagree'] = '不同意';
$i_message['install_disagree_license'] = '您必须在同意授权协议的全部条件后，方可继续ThinkSNS的安装';
$i_message['install_prev'] = '上一步';
$i_message['install_next'] = '下一步';
$i_message['dirmod'] = '目录和文件属性';
$i_message['install_dirmod'] = '目录和文件是否可写，如果发生错误，请更改文件/目录属性 777';
$i_message['install_env'] = '服务器配置';
$i_message['php_os'] = '操作系统';
$i_message['php_version'] = 'PHP版本';
$i_message['file_upload'] = '附件上传';
$i_message['support'] = '支持';
$i_message['unsupport'] = '不支持';
$i_message['php_extention'] = 'PHP扩展';
$i_message['php_extention_unload_gd'] = '您的服务器没有安装这个PHP扩展：gd';
$i_message['php_extention_unload_mbstring'] = '您的服务器没有安装这个PHP扩展：mbstring';
$i_message['php_extention_unload_mysql'] = '您的服务器没有安装这个PHP扩展：mysql';
$i_message['php_extention_unload_curl'] = '您的服务器没有安装这个PHP扩展：curl';
$i_message['mysql'] = 'MySQL数据库';
$i_message['mysql_unsupport'] = '您的服务器不支持MYSQL数据库，无法安装ThinkSNS。';
$i_message['install_setting'] = '数据库资料与管理员账号设置';
$i_message['install_mysql_host'] = '数据库服务器';
$i_message['install_mysql_host_intro'] = '格式：地址(:端口)，一般为 localhost';
$i_message['install_mysql_username'] = '数据库用户名';
$i_message['install_mysql_password'] = '数据库密码';
$i_message['install_mysql_name'] = '数据库名';
$i_message['install_mysql_prefix'] = '表名前缀';
$i_message['install_mysql_prefix_intro'] = '同一数据库安装多ThinkSNS程序时可改变默认值';
$i_message['site_url'] = ' 站点地址';
$i_message['site_url_intro'] = '';
$i_message['founder'] = '超级管理员资料';
$i_message['install_founder_name'] = '管理员账号';
$i_message['install_founder_password'] = '密码';
$i_message['install_founder_rpassword'] = '重复密码';
$i_message['install_founder_email'] = '电子邮件';
$i_message['install_mysql_host_empty'] = '数据库服务器不能为空';
$i_message['install_mysql_username_empty'] = '数据库用户名不能为空';
$i_message['install_mysql_name_empty'] = '数据库名不能为空';
$i_message['install_founder_name_empty'] = '超级管理员用户名不能为空';
$i_message['install_founder_password_length'] = '超级管理员密码必须大于6位';
$i_message['install_founder_rpassword_error'] = '两次输入管理员密码不同';
$i_message['install_founder_email_empty'] = '超级管理员Email不能为空';
$i_message['mysql_invalid_configure'] = '数据库配置信息不完整';
$i_message['mysql_invalid_prefix'] = '您指定的数据表前缀包含点字符(".")，请返回修改。';
$i_message['forbidden_character'] = '用户名包含非法字符';
$i_message['founder_invalid_email'] = '电子邮件格式不正确';
$i_message['founder_invalid_configure'] = '超级管理员信息不完整';
$i_message['founder_invalid_password'] = '密码长度必须大于6位';
$i_message['founder_invalid_rpassword'] = '两次输入的密码不一致';
$i_message['founder_intro'] = '网站创始人，拥有最高权限';
$i_message['config_log_success']	= '数据库配置信息写入完成';
$i_message['define_log_success']	= '网站全局配置信息写入完成';
$i_message['config_read_failed'] = '数据库配置文件写入错误，请检查config.inc.php文件是否存在或属性是否为777';
$i_message['define_read_failed'] = '网站全局配置文件写入错误，请检查define.inc.php文件是否存在或属性是否为777';
$i_message['error'] = '错误';
$i_message['database_errno_2003'] = '无法连接数据库，请检查数据库是否启动，数据库服务器地址是否正确';
$i_message['database_errno_1045'] = '无法连接数据库，请检查数据库用户名或者密码是否正确';
$i_message['database_errno_1044'] = '无法创建新的数据库，请检查数据库名称填写是否正确';
$i_message['database_errno_1064'] = 'SQL执行错误，请检查数据库名称填写是否正确';
$i_message['database_errno'] = '程序在执行数据库操作时发生了一个错误，安装过程无法继续进行。';
$i_message['configure_read_failed'] = '数据库配置失败';
$i_message['mysql_version_402'] = '您的 MYSQL 版本低于 4.1.0，安装无法继续进行！';
$i_message['thinksns_rebuild'] = '数据库中已经安装过 ThinkSNS，继续安装会清空原有数据！';
$i_message['mysql_import_data'] = '点击下一步开始导入数据';
$i_message['import_processing'] = '导入数据库';
$i_message['import_processing_error'] = '导入数据库失败';
$i_message['create_table'] = '创建表';
$i_message['create_founder'] = '创建超级管理员帐户';
$i_message['create_founder_success'] = '超级管理员帐户创建成功';
$i_message['create_founder_error']	= '超级管理员帐户创建失败';
$i_message['create_founderpower_success'] = '超级管理员权限设置成功';
$i_message['create_founderpower_error']	= '超级管理员权限设置失败';
$i_message['create_cache'] = '创建缓存';
$i_message['create_cache_success'] = '创建缓存成功';
$i_message['auto_increment'] = '用户的起始ID';
$i_message['set_auto_increment_success'] = '用户起始ID设置成功';
$i_message['set_auto_increment_error'] = '用户起始ID设置失败';
$i_message['install_success'] = '安装成功';
$i_message['install_success_intro'] = '<p>安装程序执行完毕，请尽快删除整个 install 目录，以免被他人恶意利用。如要重新安装，请删除本目录的 install.lock 文件！</p><p><a href="../index.php">请点击这里开始体验ThinkSNS吧！</a></p>';
$i_message['install_dbFile_error'] = '数据库文件无法读取，请检查/install/t_thinksns_com.sql是否存在。';

?>