<?php
 
/**************************************************************************************************
 *
 *	【注意】：
 *		请不要使用 Windows 的记事本编辑此文件！此文件的编码为UTF-8编码，不带有BOM头！
 *		建议使用UEStudio, Notepad++ 类编辑器编辑此文件！
 *
***************************************************************************************************/

// 全局配置变量

return array (
	
	// 数据库配置， type 为默认的数据库类型，可以支持多种数据库: mysql|pdo|mongodb
	'db' => array (
		'type' => 'mysql',
		'mysql' => array (
			'master' => array (
					'host' => 'localhost',
					'user' => 'root',
					'password' => 'root',
					'name' => 'btbbt_xiuno',
					'charset' => 'utf8',
					'tablepre' => 'bbs_',
					'engine'=>'MyISAM',
			),
			'slaves' => array (
			)
		),
		'pdo' => array (
			'master' => array (
					'host' => 'localhost',
					'user' => 'root',
					'password' => 'root',
					'name' => 'btbbt_xiuno',
					'charset' => 'utf8',
					'tablepre' => '',
					'engine'=>'MyISAM',
			),
			'slaves' => array (
			)
		),
		'mongodb' => array(
			'master' => array (
					'host' => '10.0.0.253:27017',
					'user' => '',
					'password' => '',
					'name' => 'bbs',
					'tablepre' => '',
			),
			'slaves' => array (
			)
		),
	),	
	// 缓存服务器的配置，支持: memcache|ea|apc|redis
	'cache' => array (
		'enable'=>0,
		'type'=>'memcache',
		'memcache'=>array (
			'multi'=>0,
			'host'=>'10.0.0.253',
			'port'=>'11211',
		)
	),

	// 唯一识别ID
	'app_id' => 'bbs',
	
	// 站点名称
	'app_name' => 'Xiuno BBS 2',
	
	// 站点介绍
	'app_brief' => '',
	
	'app_copyright' => '© 2008-201 科技有限公司',
	
	'app_starttime' => '2012-1-12',
		
	// 应用的路径，用于多模板互相包含，需要时，填写绝对路径： 如: http://www.domain.com/bbs/
	'app_url' => 'http://xiuno.self.cn/',
	
	// CDN 缓存的静态域名，如 http://static.domain.com/
	'static_url' => 'http://xiuno.self.cn/',
	
	// 模板使用的目录，按照顺序搜索，这样可以支持风格切换,结果缓存在 tmp/bbs_xxx_control.htm.php
	'view_path' => array(BBS_PATH.'plugin/view_width_screen/', BBS_PATH.'view/'), 
	
	// 数据模块的路径，按照数组顺序搜索目录
	'model_path' => array(BBS_PATH.'model/'),
	
	// 业务控制层的路径，按照数组顺序搜索目录，结果缓存在 tmp/bbs_xxx_control.class.php
	'control_path' => array(BBS_PATH.'control/'),
	
	// 临时目录，需要可写，可以指定为 linux /dev/shm/ 目录提高速度
	'tmp_path' => BBS_PATH.'tmp/',
	
	// 上传目录，需要可写，保存用户上传数据的目录
	'upload_path' => BBS_PATH.'upload/',
	
	// 模板的URL，用作CDN时请填写绝对路径，需要时，填写绝对路径： 如: http://www.domain.com/bbs/upload/
	'upload_url' => 'http://xiuno.self.cn/upload/',
	
	// 日志目录，需要可写，如果您不需要日志，留空即可
	'log_path' => BBS_PATH.'log/',
		
	'plugin_path' => BBS_PATH.'plugin/',
	
	'cookiepre'=> 'xn_',
	
	// 是否开启 URL-Rewrite
	'urlrewrite' => 0,
	
	// 加密KEY，
	'public_key' => 'f2b59dcdd8ffaf46a3461a24795250b7',
	
	'timeoffset' => '+8',
	'pagesize' => 20,			// 帖子详情页的每页回复数，一旦定下来，不能修改！
	'cookie_keeptime' => 86400,
	
	// 积分策略
	'credits_policy_thread' => 2,		// 发主题增加的积分
	'credits_policy_post' => 1,		
	'credits_policy_digest_1' => 10,
	'credits_policy_digest_2' => 15,
	'credits_policy_digest_3' => 20,
	'gold_policy_thread' => 0,		// 发主题增加的金币 golds（积分不能消费，金币可以消费，充值）
	'gold_policy_post' => 0,		
	'gold_policy_digest_1' => 0,
	'gold_policy_digest_2' => 0,
	'gold_policy_digest_3' => 0,
	
	// 帖子多长时间后不能修改，默认为86400，一天，0不限制
	'post_update_expiry' => 86400,
	
	'cache_pid' => 1,			// 默认缓存 20个，写死了。 这是个开关
	'cache_tid' => 1,
	'cache_tid_num' => 200,			// 缓存tid的个数
	
	'site_pv' => 100000,			// PV越高CACHE更新的越慢，该值会影响系统的负载能力
	'site_runlevel' => 0,			// 0:所有人均可访问; 1: 仅会员访问; 2:仅版主可访问; 3: 仅管理员
	
	'threadlist_hotviews' => 2,		// 热门主题的阀值，浏览数
	
	// SEO
	'seo_title' => 'Xiuno BBS',		// 论坛首页的 title，如果不设置则为论坛名称
	'seo_keywords' => 'Xiuno BBS',		// 论坛首页的 keyword
	'seo_description' => 'Xiuno BBS',	// 论坛首页的 description
	'china_icp' => '',	// icp 备案号，也只有在这神奇的国度有吧。
	'footer_js' => '',			// 页脚额外的代码，放用于统计JS之类代码。
	
	// 点击服务器
	'click_server' => 'http://xiuno.self.cn/clickd/',	// 记录主题点击数，论坛点击数
	
	// 搜索相关
	'search_type' => 'title',			// title|baidu|google|bing|sphinx
	'sphinx_host' => '',			// 主机
	'sphinx_port' => '',			// 端口
	'sphinx_datasrc' => '',			// 数据源
	
	// 注册相关
	'reg_on' => 1,				// 是否开启注册
	'reg_email_on' => 0,			// 是否开启Email激活
	'reg_init_golds' => 10,			// 注册初始化金币
	
	// 支付相关
	'pay_on' => 0,				// 是否开启支付
	'pay_rate' => 1,			// 一块钱兑换的金币数
	'ebank_on' => 1,
	'alipay_on' => 1,
	'banklist_on' => 1,
	'sms_on' => 0,				// 短信网关
	
	// 友情链接
	'friendlink_on' => 1,			// 是否启用
	'iptable_on' => 0,			// IP 规则，白名单，黑名单
	
	'online_hold_time' => 900,		// 在线时间，15分钟
	
	'forumicon_width_small' => 16,		// 论坛图标的宽度:小
	'forumicon_width_middle' => 44,		// 论坛图标的宽度:中
	'forumicon_width_big' => 65,		// 论坛图标的宽度:大
	'avatar_width_small' => 16,		// 用户头像宽度:小
	'avatar_width_middle' => 54,		// 用户头像宽度:中
	'avatar_width_big' => 120,		// 用户头像宽度:大
	'thread_icon_middle' => 54,		// 主题的缩略图:中
	
	'version' => '2.0.0 RC2',
	'installed' => 1,			// 是否安装的标志位
	
	'qq_appid' => '',
	'qq_appkey' => '',
	'qq_meta' => '',
);
?>