#
#	SQL + 注释用来定义自动生成的代码
#	除了数据库外， conf/conf.php 保存配置数据，
#

DROP TABLE IF EXISTS bbs_group;
CREATE TABLE bbs_group (				# 字段中文名			# 控件属性					# 字段描述
  groupid smallint(6) unsigned NOT NULL,		#				#						#
  name char(20) NOT NULL default '',			# 用户组名称			# type="text"					#
  creditsfrom int(10) NOT NULL default '0',		# 起始积分			# type="text" default="0"			# 积分范围（从）
  creditsto int(10) NOT NULL default '0',		# 截止积分			# type="text" default="0"			# 积分范围（到）
  upfloors int(10) NOT NULL default '0',		# 每次回帖顶起的楼层		# type="text" default="0"			# 默认每回复一次，帖子上升一格
  color char(7) NOT NULL default '',			# 颜色				# type="text" default=""			#
  PRIMARY KEY (groupid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO bbs_group SET groupid='0', name="游客组", creditsfrom='0', creditsto='0', upfloors='1', color='';
INSERT INTO bbs_group SET groupid='1', name="管理员组", creditsfrom='0', creditsto='0', upfloors='0', color='';
INSERT INTO bbs_group SET groupid='2', name="超级版主组", creditsfrom='0', creditsto='0', upfloors='0', color='';
INSERT INTO bbs_group SET groupid='3', name="大区版主组", creditsfrom='0', creditsto='0', upfloors='0', color='';
INSERT INTO bbs_group SET groupid='4', name="版主组", creditsfrom='0', creditsto='0', upfloors='0', color='';
INSERT INTO bbs_group SET groupid='5', name="VIP组", creditsfrom='0', creditsto='0', upfloors='10', color='';
INSERT INTO bbs_group SET groupid='6', name="待验证用户组", creditsfrom='0', creditsto='0', upfloors='0', color='';
INSERT INTO bbs_group SET groupid='7', name="禁止用户组", creditsfrom='0', creditsto='0', upfloors='0', color='';
INSERT INTO bbs_group SET groupid='11', name="一级用户组", creditsfrom='0', creditsto='50', upfloors='1', color='';
INSERT INTO bbs_group SET groupid='12', name="二级用户组", creditsfrom='50', creditsto='200', upfloors='2', color='';
INSERT INTO bbs_group SET groupid='13', name="三级用户组", creditsfrom='200', creditsto='1000', upfloors='3', color='';
INSERT INTO bbs_group SET groupid='14', name="四级用户组", creditsfrom='1000', creditsto='10000', upfloors='4', color='';
INSERT INTO bbs_group SET groupid='15', name="五级级用户组", creditsfrom='10000', creditsto='10000000', upfloors='5', color='';

# 用户表，根据 uid 范围进行分区
DROP TABLE IF EXISTS bbs_user;
CREATE TABLE bbs_user (					# 字段中文名			# 控件属性					# 字段描述
  uid int(11) unsigned NOT NULL auto_increment,		# 用户id				#						#
  regip int(11) NOT NULL default '0',			# 注册ip				#						#
  regdate int(11) unsigned NOT NULL default '0',	# 注册日期			# type="time"					#
  username char(16) NOT NULL default '',		# 用户名				# type="text"					#
  password char(32) NOT NULL default '',		# 密码				# type="password"				# md5(md5() + salt)
  salt char(8) NOT NULL default '',			# 随机干扰字符，用来混淆密码	#						#
  email char(40) NOT NULL default '',			# EMAIL				# type="text"					#
  groupid tinyint(3) unsigned NOT NULL default '0',	# 用户组				# type="select"					#
  threads mediumint(8) unsigned NOT NULL default '0',	# 主题数				#						#
  posts mediumint(8) unsigned NOT NULL default '0',	# 回帖数				#						#
  myposts mediumint(8) unsigned NOT NULL default '0',	# 参与过的主题数			#						#
  digests mediumint(8) unsigned NOT NULL default '0',	# 精华数				#						#
  avatar int(11) unsigned NOT NULL default '0',		# 头像最后更新的时间，0为默认头像	#						#
  credits int(11) unsigned NOT NULL default '0',	# 用户积分，不可以消费		#						#
  golds int(11) unsigned NOT NULL default '0',		# 虚拟金币，可以消费，充值可以增加	#						#
  money int(11) unsigned NOT NULL default '0',		# 站点支持的货币（人民币），可以消费可以由RMB兑换	#						#
  follows smallint(3) unsigned NOT NULL default '0',	# 关注数				#						#
  followeds int(11) unsigned NOT NULL default '0',	# 被关注数			#						#
  newpms int(11) unsigned NOT NULL default '0',		# 新短消息（x人）			#						#
  newfeeds int(11) NOT NULL default '0',		# 新的事件（x条）todo:预留		#						#
  homepage char(40) NOT NULL default '',		# 主页的URL（外链）		# type="text"					#
  signature char(32) NOT NULL default '',		# 一首七言格律诗句的长度		# type="text"					#
  accesson tinyint(1) NOT NULL default '0',		# 是否启用了权限控制		#						#
  lastactive int(1) NOT NULL default '0',		# 上次活动时间，用来判断在线	#						#
  UNIQUE KEY username(username),
  UNIQUE KEY email(email),
  PRIMARY KEY (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO bbs_user SET uid='1', regip='12345554', regdate=UNIX_TIMESTAMP(), username='admin', password='d14be7f4d15d16de92b7e34e18d0d0f7', salt='99adde', email='admin@admin.com', groupid='1', golds='0', money='0';
#INSERT INTO bbs_user SET uid='2', regip='12345554', regdate=UNIX_TIMESTAMP(), username='test', password='d14be7f4d15d16de92b7e34e18d0d0f7', salt='99adde', email='test@test.com', groupid='11', golds='0', money='0';

# 用户访问权限，全局的。一般用来设置禁止用户。黑名单机制。
DROP TABLE IF EXISTS bbs_user_access;
CREATE TABLE bbs_user_access (				# 字段中文名			# 控件属性					# 字段描述
  uid int(11) unsigned NOT NULL default '0',		# uid				#						#
  allowread tinyint(1) unsigned NOT NULL default '0',	# 允许查看			# type="radio" default="0"			#
  allowthread tinyint(1) unsigned NOT NULL default '0',	# 允许发主题			# type="radio" default="0"			# 允许发主题
  allowpost tinyint(1) unsigned NOT NULL default '0',	# 允许发帖			# type="radio" default="0"			# 允许发帖
  allowattach tinyint(1) unsigned NOT NULL default '0',	# 允许附件			# type="radio" default="0"			#
  expiry int(10) unsigned NOT NULL default '0',		# 过期时间，0永不过期		# type="text" default="0"			#
  PRIMARY KEY (uid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 板块表，结合 forum_access 控制权限，所有版块名均可见。
DROP TABLE IF EXISTS bbs_forum;
CREATE TABLE bbs_forum (				# 字段中文名			# 控件属性					# 字段描述
  fid int(11) unsigned NOT NULL auto_increment,		# fid				#						#
  fup smallint(6) unsigned NOT NULL default '0',	# 版块父id			# type="select"
  name char(16) NOT NULL default '',			# 用户名				# type="text"					#
  rank tinyint(3) unsigned NOT NULL default '0',	# 显示，倒序			# type="text"
  threads mediumint(8) unsigned NOT NULL default '0',	# 主题数	
  posts mediumint(8) unsigned NOT NULL default '0',	# 回帖数				
  digests mediumint(8) unsigned NOT NULL default '0',	# 精华数				
  todayposts mediumint(8) unsigned NOT NULL default '0',# 今日发帖，计划任务每日凌晨０点清空为０
  tops mediumint(8) unsigned NOT NULL default '0',	# 置顶帖的总数
  lastpost int(11) NOT NULL default '0',		# 最后发表的时间，用来标示活跃论坛 class="newforum"
  lasttid int(11) NOT NULL default '0',			# 最后发表的tid
  lastsubject char(32) NOT NULL default '0',		# 最后发表的主题
  lastuid int(16) NOT NULL default '0',			# 最后发表人
  lastusername char(16) NOT NULL default '0',		# 最后发表人
  brief char(200) NOT NULL default '',			# 版块简介 允许HTML		# type="text"
  rule char(255) NOT NULL default '',			# 版规 允许HTML			# type="text"
  icon tinyint NOT NULL default '0',			# 版块 icon, url upload/forum/123.gif，默认 view/image/forum.gif forum_new.gif 37 * 37	# type="text"
  accesson tinyint(1) NOT NULL default '0',		# 是否启用访问规则
  modids char(47) NOT NULL default '',			# 版主 uid，最多四个，逗号隔开
  modnames char(67) NOT NULL default '',		# 版主 username，最多四个，逗号隔开
  toptids char(240) NOT NULL default '',		# 置顶主题，分区可以置顶，板块可以置顶，格式：2-5 2-10 ，全局置顶放在 tmp/top_3.txt 
  lastcachetime int(11) NOT NULL default '0',		# 最后一次刷新缓存的时间，每隔5分钟刷新一次：forum-index.htm 左侧数据
  status tinyint(11) NOT NULL default '0',		# 是否显示，默认为1，显示，0不显示
  listtype tinyint(11) NOT NULL default '0',		# 默认列表展示模式，0: 文字， 1: 图片（类似网易新闻）
  orderby tinyint(11) NOT NULL default '0',		# 默认列表排序，0: 顶贴时间 floortime， 1: 发帖时间 dateline
  indexforums tinyint(11) NOT NULL default '0',		# 首页是否启用横排,0 不启用，>1, 一行排几列
  seo_title char(64) NOT NULL default '',		# SEO 标题，如果设置会代替版块名称
  seo_keywords char(64) NOT NULL default '',		# SEO keyword
  PRIMARY KEY (fid),
  KEY fup (fup)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO bbs_forum SET fid='1', fup='0', name='默认分类', rank='0', threads='0', posts='0', todayposts='0', lastpost='', lasttid='0', lastsubject='', lastuid='', lastusername='', brief='', rule='', icon='', status='1', listtype='0', orderby='0', indexforums='0', seo_title='', seo_keywords='';
INSERT INTO bbs_forum SET fid='2', fup='1', name='默认版块一', rank='0', threads='0', posts='0', todayposts='0', lastpost='', lasttid='0', lastsubject='', lastuid='', lastusername='', brief='默认版块介绍', rule='', icon='', status='1', listtype='0', orderby='0', indexforums='0', seo_title='', seo_keywords='';
INSERT INTO bbs_forum SET fid='3', fup='1', name='默认版块二', rank='0', threads='0', posts='0', todayposts='0', lastpost='', lasttid='0', lastsubject='', lastuid='', lastusername='', brief='默认版块介绍', rule='', icon='', status='1', listtype='0', orderby='0', indexforums='0', seo_title='', seo_keywords='';

# 版块访问规则 fid * groupid
DROP TABLE IF EXISTS bbs_forum_access;
CREATE TABLE bbs_forum_access (				# 字段中文名			# 控件属性					# 字段描述
  fid int(11) unsigned NOT NULL default '0',		# fid				#						#
  groupid int(11) unsigned NOT NULL default '0',	# fid				#						#
  allowread tinyint(1) unsigned NOT NULL default '0',	# 允许查看			# type="radio" default="0"			#
  allowthread tinyint(1) unsigned NOT NULL default '0',	# 允许发主题			# type="radio" default="0"			# 允许发主题
  allowpost tinyint(1) unsigned NOT NULL default '0',	# 允许发帖			# type="radio" default="0"			# 允许发帖
  allowattach tinyint(1) unsigned NOT NULL default '0',	# 允许附件			# type="radio" default="0"			# 允许发帖
  allowdown tinyint(1) unsigned NOT NULL default '0',	# 允许下载			# type="radio" default="0"			# 允许下载
  PRIMARY KEY  (fid, groupid),
  KEY  (fid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS bbs_thread_type;
CREATE TABLE bbs_thread_type (
  typeid int(11) unsigned NOT NULL auto_increment,	# 主题分类id
  fid smallint(6) NOT NULL default '0',			# 版块id
  threads int(11) NOT NULL default '0',			# 该主题分类下有多少主题数
  typename char(16) NOT NULL default '',		# 主题分类
  rank tinyint(3) unsigned NOT NULL default '0',	# 排序，越大越靠前，最大255
  PRIMARY KEY (typeid),
  KEY (fid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 论坛主题 fid->tid, 根据fid分区
DROP TABLE IF EXISTS bbs_thread;
CREATE TABLE bbs_thread (
  fid smallint(6) NOT NULL default '0',			# 版块id
  tid int(11) unsigned NOT NULL auto_increment,		# 主题id
  username char(16) NOT NULL default '',		# 用户名
  uid int(11) unsigned NOT NULL default '0',		# 用户id
  subject char(80) NOT NULL default '',			# 主题
  dateline int(10) unsigned NOT NULL default '0',	# 发帖时间
  lastpost int(10) unsigned NOT NULL default '0',	# 最后回复时间
  lastuid int(10) unsigned NOT NULL default '0',	# 最后回复uid
  lastusername char(16) NOT NULL default '',		# 最后回复用户名
  floortime int(10) unsigned NOT NULL default '0',	# 被顶起来的时间戳，默认等于 lastpost  
  views int(10) unsigned NOT NULL default '0',		# 查看次数, 剥离出去，单独的服务，避免 cache 失效
  posts mediumint(8) unsigned NOT NULL default '0',	# 回帖数
  top tinyint(1) NOT NULL default '0',			# 置顶级别: 0: 默认不置顶, 1-3 置顶的顺序
  digest tinyint(1) NOT NULL default '0',		# 精华级别: 0: 默认非精华 1-3 精华级别
  typeid int(10) unsigned NOT NULL default '0',		# 主题分类id
  typename char(16) NOT NULL default '0',		# 主题分类名
  cateids char(47) NOT NULL default '0',		# 精华分类，冗余存储, 4个id 空格隔开
  catenames char(67) NOT NULL default '0',		# 精华分类名称，冗余存储, 4个名字 空格隔开
  attachnum tinyint(3) NOT NULL default '0',		# 附件总数
  imagenum tinyint(3) NOT NULL default '0',		# 附件总数
  closed tinyint(1) unsigned NOT NULL default '0',	# 是否关闭，关闭以后不能再回帖。
  firstpid int(11) unsigned NOT NULL default '0',	# 首贴pid
  status tinyint(1) NOT NULL default '0',		# 状态 [未使用]
  seo_keywords char(64) NOT NULL default '',		# SEO 关键词
  pids char(240) NOT NULL default '',			# 第一页的 20 个pid，用来缓存 [可以减少一次mysql索引集合查询，命中率高，效果应该比较好]
  coverimg char(64) NOT NULL default '',		# 封面图片
  brief char(80) NOT NULL default '',			# 简介, 两行文字，80个字符，由post.message 截取
  PRIMARY KEY (fid, tid),				# 按照发帖时间排序
  KEY (fid, floortime),					# 按照顶贴时间排序
  KEY (typeid, tid),					# 主题分类，排序按照发帖时间，主题分类改名，删除的时候需要，按照 typeid 过滤需要。
  KEY (typeid, floortime),				# 主题分类，排序按照顶帖时间
  KEY (tid)						# 给Sphinx用，不能使用联合索引
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 论坛帖子数据 fid->tid->pid, 根据fid分区
DROP TABLE IF EXISTS bbs_post;
CREATE TABLE bbs_post (
  fid smallint(6) unsigned NOT NULL default '0',	# 版块id
  pid int(10) unsigned NOT NULL auto_increment,		# 帖子id
  tid int(11) unsigned NOT NULL default '0',		# 主题id
  uid int(11) unsigned NOT NULL default '0',		# 用户id
  dateline int(10) unsigned NOT NULL default '0',	# 发贴时间
  userip int(11) NOT NULL default '0',			# 发帖时用户ip ip2long()
  attachnum tinyint(3) unsigned NOT NULL default '0',	# 上传的附件数
  imagenum tinyint(3) unsigned NOT NULL default '0',	# 上传的图片数
  page smallint(6) unsigned NOT NULL default '0',	# 第几页
  username char(16) NOT NULL default '',		# 用户名
  subject varchar(255) NOT NULL default '',		# 主题，不允许使用html标签
  message longtext NOT NULL default '',			# 内容，存放的过滤后的html内容
  PRIMARY KEY  (fid, pid),
  KEY page (fid, tid, page)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 精华分类，加入精华时，选择分类（全局！管理员和版主有权限），方便制作出在线CHM
DROP TABLE IF EXISTS bbs_digestcate;
CREATE TABLE bbs_digestcate (
  cateid int(11) unsigned NOT NULL auto_increment,	# 分类id
  parentid int(11) unsigned NOT NULL,			# 上一级cateid
  name char(32) NOT NULL default '0',			# 分类名称
  threads int(1) NOT NULL default '0',			# 该分类下的主题数
  rank int(1) NOT NULL default '0',			# 分类显示顺序
  uid int(1) NOT NULL default '0',			# 哪个用户添加的分类，一般为管理员和版主
  KEY  (rank),
  PRIMARY KEY  (cateid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO bbs_digestcate SET cateid='1', parentid='0', name='精华分类一', threads='0', rank='1', uid='1';
INSERT INTO bbs_digestcate SET cateid='2', parentid='0', name='精华分类二', threads='0', rank='2', uid='1';
INSERT INTO bbs_digestcate SET cateid='3', parentid='0', name='精华分类三', threads='0', rank='3', uid='1';
INSERT INTO bbs_digestcate SET cateid='4', parentid='3', name='精华分类3.1', threads='0', rank='4', uid='1';
INSERT INTO bbs_digestcate SET cateid='5', parentid='3', name='精华分类3.3', threads='0', rank='5', uid='1';
INSERT INTO bbs_digestcate SET cateid='6', parentid='3', name='精华分类3.3', threads='0', rank='6', uid='1';

# 精华主题，用小表来代替大索引
DROP TABLE IF EXISTS bbs_digest;
CREATE TABLE bbs_digest (
  digestid int(11) unsigned NOT NULL auto_increment,	# digest id
  fid smallint(6) NOT NULL default '0',			# 版块id
  tid int(11) unsigned NOT NULL,			# 主题id
  cateid tinyint(1) NOT NULL default '0',		# 属于哪个分类
  digest tinyint(1) NOT NULL default '0',		# 精华级别
  subject char(80) NOT NULL default '',			# 主题
  rank int(1) NOT NULL default '0',			# 显示顺序
  PRIMARY KEY (digestid),
  KEY (fid, tid),
  KEY cateid (cateid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

#论坛附件表  fid->tid->pid->aid，只能按照从上往下的方式查找和删除！
DROP TABLE IF EXISTS bbs_attach;
CREATE TABLE bbs_attach (
  fid smallint(6) unsigned NOT NULL default '0',	# fid
  aid int(10) unsigned NOT NULL auto_increment ,	# 附件id
  pid int(11) NOT NULL default '0',			# 帖子id
  uid int(10) NOT NULL default '0',			# 用户id
  filesize mediumint(8) unsigned NOT NULL default '0',	# 文件尺寸，单位字节
  width mediumint(8) unsigned NOT NULL default '0',	# width
  height mediumint(8) unsigned NOT NULL default '0',	# height
  filename char(120) NOT NULL default '',		# 文件名称，会过滤，并且截断，保存后的文件名，不包含URL前缀 upload_url
  orgfilename char(60) NOT NULL default '',		# 上传的原文件名
  filetype char(7) NOT NULL default '',			# 文件类型: image/txt/zip，小图标显示
  dateline int(10) unsigned NOT NULL default '0',	# 文件上传时间 UNIX时间戳
  comment char(100) NOT NULL default '',		# 文件注释 方便于搜索
  downloads int(10) NOT NULL default '0',		# 下载次数
  isimage tinyint(1) NOT NULL default '0',		# 图片|文件，跟 filetype 含义不同，这个主要为了区分是否为可下载的附件。
  golds int(10) NOT NULL default '0',			# 金币
  PRIMARY KEY (aid),
  KEY fidpid (fid, pid),
  KEY uid (uid, isimage)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 收费附件的下载历史，用来查账，分页算法为上一页，下一页
DROP TABLE IF EXISTS bbs_attach_download;
CREATE TABLE bbs_attach_download (
  aid int(10) unsigned NOT NULL default '0',		# 下载的附件id
  uid int(10) NOT NULL default '0',			# 下载的用户id
  uploaduid int(10) NOT NULL default '0',		# 上传人的UID
  dateline int(10) unsigned NOT NULL default '0',	# 下载的时间   
  golds int(10) NOT NULL default '0',			# 下载时支付的金币
  PRIMARY KEY (uid, aid),
  KEY (uploaduid, dateline),
  KEY (aid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 我的发帖，每个主题不管回复多少次，只记录一次。根据pid查询page
DROP TABLE IF EXISTS bbs_mypost;
CREATE TABLE bbs_mypost (
  uid int(11) unsigned NOT NULL default '0',		# uid
  fid int(11) unsigned NOT NULL default '0',		#
  tid int(11) unsigned NOT NULL default '0',		# 用来排除
  pid int(11) unsigned NOT NULL default '0',		# 查询 post 知道所在的 thread, post.page.
  PRIMARY KEY (uid, fid, pid),				# 每一个帖子只能插入一次 unique
  KEY (uid, fid, tid),					# 用户发表的主题，用来查询
  KEY (uid, pid)					# 用户发表的回帖，用来查询，按照 pid 排序
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 在线用户，每隔五分钟插入一次。	$this->count('online'); 查询和记录总数，否则 mysqld 重启会清空该表。
DROP TABLE IF EXISTS bbs_online;
CREATE TABLE bbs_online (
  sid char(16) NOT NULL default '0',			# 随机生成 id 不能重复
  uid int(11) unsigned NOT NULL default '0',		# 用户id 未登录为0
  username char(16) NOT NULL default '',		# 用户名	未登录为空
  ip int(11) NOT NULL default '0',			# 用户ip
  groupid tinyint(3) unsigned NOT NULL default '0',	# 用户组
  url char(100) NOT NULL default '',			# 当前访问 url
  lastvisit int(11) unsigned NOT NULL default '0',	# 上次活动时间
  PRIMARY KEY (sid),
  KEY lastvisit (lastvisit),
  KEY uid (uid)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 友情链接表
DROP TABLE IF EXISTS bbs_friendlink;
CREATE TABLE bbs_friendlink(
  linkid int(10) unsigned NOT NULL auto_increment ,	# 友情链接表
  type tinyint(1) NOT NULL default '0',			# 连接类型：0:文字，1:LOGO	# type="text" default="0"			#
  rank tinyint(1) unsigned NOT NULL default '0',	# 排序			# type="text" default="0"			#
  sitename char(16) NOT NULL default '',		# 站点名称		# type="text" default="0"			#
  url char(64) NOT NULL default '',			# URL			# type="text" default="0"			#
  logo char(64) NOT NULL default '',			# LOGO path		# type="text" default="0"			#
  PRIMARY KEY (linkid),
  KEY type (type, rank)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO bbs_friendlink SET linkid='1', type='0', rank='255', sitename='Xiuno BBS', url='http://www.xiuno.com/', logo='';

# -------------------------> 以下为用户相关的表，增加用户互动。

# 消息的记录，记录A给B发，如果很多，可以根据 recvuid 分区，多对多的关系, N*N(N = user.count())，控制在40*N
# 新消息 recvuid=123 AND count>0
# 删除某人关系 recvuid=123, senduid=222 (可能没有这个必要，造成碎片？)，最近联系人。保留最后40个！  一次取1000个，删除掉后面的。
# dateline 为最后更新的时间，可以用来排序。
DROP TABLE IF EXISTS bbs_pmnew;
CREATE TABLE bbs_pmnew (
  recvuid int(11) unsigned NOT NULL default '0',	# 接受者UID，与 user.newpms 配合使用，非唯一主键
  senduid int(11) unsigned NOT NULL default '0',	# 发送者UID
  count int(11) unsigned NOT NULL default '0',		# 新消息的条数
  dateline int(11) unsigned NOT NULL default '0',	# 按照时间顺序排序 php 排序
  PRIMARY KEY (recvuid, senduid),
  KEY (recvuid, count)					# recvuid=123 and count>0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 用户聊天，类似在线QQ，根据 uid 分区, uid1 小，uid2 大，此表记录最大行数 = N*N (N = user.count())
DROP TABLE IF EXISTS bbs_pmcount;
CREATE TABLE bbs_pmcount (
  uid1 int(11) unsigned NOT NULL default '0',		# 用户id small uid
  uid2 int(11) unsigned NOT NULL default '0',		# 用户id big uid
  count int(11) unsigned NOT NULL default '0',		# 两人对话的记录条数，用来判断 pm.page 的最大页数
  dateline int(11) unsigned NOT NULL default '0',	# 按照时间顺序排序
  PRIMARY KEY (uid1, uid2)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 根据 pmid 分区，变长表。没有全表扫描操作。
DROP TABLE IF EXISTS bbs_pm;
CREATE TABLE bbs_pm (
  pmid bigint(11) unsigned NOT NULL auto_increment,	# pmid
  uid1 int(11) unsigned NOT NULL default '0',		# 用户id small uid
  uid2 int(11) unsigned NOT NULL default '0',		# 用户id big uid
  uid int(11) unsigned NOT NULL default '0',		# 由谁发出
  page int(11) unsigned NOT NULL default '0',		# 翻页数据
  username1 char(16) NOT NULL default '',		# 用户名	未登录为空
  username2 char(16) NOT NULL default '',		# 用户名	未登录为空
  dateline int(11) unsigned NOT NULL default '0',	# 时间
  message varchar(255) NOT NULL default '',		# 内容，没有编辑操作。避免碎片产生
  PRIMARY KEY (pmid),
  KEY (uid1, uid2, page)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 最多可关注 50 个。
DROP TABLE IF EXISTS bbs_follow;
CREATE TABLE bbs_follow (
  uid int(11) unsigned NOT NULL default '0',		# uid
  fuid int(11) unsigned NOT NULL default '0',		# uid关注的fuid
  direction int(11) unsigned NOT NULL default '0',	# 0: 保留, 1: 单向, 2: 双向
  PRIMARY KEY (uid, fuid),
  KEY (uid),
  KEY (fuid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 版主操作日志
DROP TABLE IF EXISTS bbs_modlog;
CREATE TABLE bbs_modlog(
  logid bigint(11) unsigned NOT NULL auto_increment,	# logid
  uid int(11) unsigned NOT NULL default '0',		# 版主 uid
  username char(16) NOT NULL default '',		# 版主 用户名
  fid int(11) unsigned NOT NULL default '0',		# 版块id
  tid int(11) unsigned NOT NULL default '0',		# 主题id
  pid int(11) unsigned NOT NULL default '0',		# 帖子id
  subject char(32) NOT NULL default '',			# 主题
  credits  int(11) unsigned NOT NULL default '0',	# 加减积分
  dateline int(11) unsigned NOT NULL default '0',	# 时间
  action char(16) NOT NULL default '',			# digest|top|delete|undigest|untop
  PRIMARY KEY (logid),
  KEY (uid, logid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 充值记录，线下支付后，由管理员手工给会员添加。
DROP TABLE IF EXISTS bbs_pay;
CREATE TABLE bbs_pay (
  payid int(11) unsigned NOT NULL auto_increment,	# 支付ID/订单ID
  uid int(11) unsigned NOT NULL default '0',
  username char(16) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',	# 支付时间	# type="time"
  payamount int(3) NOT NULL default '0',		# 支付金额	# type="text"
  paytype tinyint(3) NOT NULL default '0',		# 支付方式	# type="select" options={"0":"线下付款", "1":"支付宝", "2":"网银"}
  status tinyint(3) NOT NULL default '0',		# 状态		# type="select" options={"0":"等待支付", "1":"已支付"}
  
  alipay_email char(60) NOT NULL default '',           
  alipay_orderid char(60) NOT NULL default '',         
  alipay_fee int(10) NOT NULL default '0',             
  alipay_receive_name char(10) NOT NULL default '',    
  alipay_receive_phone char(20) NOT NULL default '',   
  alipay_receive_mobile char(10) NOT NULL default '',  
  ebank_orderid char(64) NOT NULL default '',          
  ebank_amount mediumint(9) NOT NULL default '0',      
  epay_amount int(11) NOT NULL default '0',            
  epay_orderid char(64) NOT NULL default '0',     
  PRIMARY KEY(payid),
  KEY(uid)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 统计信息，统计每日的总贴数，总用户数，新增贴数，用户数，每天增加一条记录
DROP TABLE IF EXISTS bbs_stat;
CREATE TABLE bbs_stat (
  year int(11) unsigned NOT NULL default '0',
  month int(11) unsigned NOT NULL default '0',
  day int(11) unsigned NOT NULL default '0',
  threads int(11) unsigned NOT NULL default '0',
  posts int(11) unsigned NOT NULL default '0',
  users int(11) unsigned NOT NULL default '0',
  newthreads int(11) unsigned NOT NULL default '0',
  newposts int(11) unsigned NOT NULL default '0',
  newusers int(11) unsigned NOT NULL default '0',
  PRIMARY KEY(year, month, day)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS bbs_kv;
CREATE TABLE bbs_kv (
  k char(16) NOT NULL default '',
  v text NOT NULL default '',
  expiry int unsigned NOT NULL default '0',
  PRIMARY KEY(k)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

# 频繁更新的数据，运行期间频繁更新的数据，可能多台WEB同步调用：比如: threads, posts, users, todayposts, todayusers, newuser, cron_1_next_time, cron_2_next_time, toptids
DROP TABLE IF EXISTS bbs_runtime;
CREATE TABLE bbs_runtime (
  k char(16) NOT NULL default '',
  v char(255) NOT NULL default '',
  PRIMARY KEY(k)
) ENGINE=Memory DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;