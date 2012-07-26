<?php

/*
 * Copyright (C) xiuno.com
 */

if(!defined('FRAMEWORK_PATH')) {
	exit('FRAMEWORK_PATH not defined.');
}

class db_mysql implements db_interface {

	private $conf;
	//private $wlink;	// 读写分离
	//private $rlink;	// 读写分离
	public $tablepre;	// 方便外部读取
	
	public function __construct($conf) {
		$this->conf = $conf;
		$this->tablepre = $this->conf['master']['tablepre'];
	}
		
	public function __get($var) {
		$conf = $this->conf;
		if($var == 'rlink') {
			// 如果没有指定从数据库，则使用 master
			if(empty($this->conf['slaves'])) {
				$this->rlink = $this->wlink;
				return $this->rlink;
			}
			$n = rand(0, count($this->conf['slaves']) - 1);
			$conf = $this->conf['slaves'][$n];
			$this->rlink = $this->connect($conf['host'], $conf['user'], $conf['password'], $conf['name'], $conf['charset'], $conf['engine']);
			return $this->rlink;
		} elseif($var == 'wlink') {
			$conf = $this->conf['master'];
			$this->wlink = $this->connect($conf['host'], $conf['user'], $conf['password'], $conf['name'], $conf['charset'], $conf['engine']);
			return $this->wlink;
		}
		
		// innodb_flush_log_at_trx_commit
	}
	
	// insert & update 整行更新
	public function set($key, $data, $life = 0) {
		list($table, $keyarr, $sqladd) = $this->parse_key($key);
		$tablename = $this->tablepre.$table;
		if(is_array($data)) {
			
			// 覆盖主键的值
			$data += $keyarr;
			$s = '';
			foreach($data as $k=>$v) {
				$v = addslashes($v);
				$s .= "$k='$v',";
			}
			$s = substr($s, 0, -1);
			return $this->query("REPLACE INTO $tablename SET $s", $this->wlink);
		} else {
			return FALSE;
		}
		
	}
	
	/**
		get('user-uid-123');
		get('user-fid-123-uid-123');
		get(array(
			'user-fid-123-uid-111',
			'user-fid-123-uid-222',
			'user-fid-123-uid-333'
		));
	
	*/
	public function get($key) {
		if(!is_array($key)) {
			list($table, $keyarr, $sqladd) = $this->parse_key($key);
			$tablename = $this->tablepre.$table;
			$result = $this->query("SELECT * FROM $tablename WHERE $sqladd", $this->rlink);
			return mysql_fetch_assoc($result);
		} else {
			
			// 此处可以递归调用，但是为了效率，单独处理
			$sqladd = $_sqladd = $table =  $tablename = '';
			$data = $return = $keyarr = array();
			$keys = $key;
			foreach($keys as $key) {
				$return[$key] = array();	// 定序，避免后面的 OR 条件取出时顺序混乱
				list($table, $keyarr, $_sqladd) = $this->parse_key($key);
				$tablename = $this->tablepre.$table;
				$sqladd .= "$_sqladd OR ";
			}
			$sqladd = substr($sqladd, 0, -4);
			if($sqladd) {
				// todo: 需要判断分库。分库以后，这里会统一在一台DB上取
				$result = $this->query("SELECT * FROM $tablename WHERE $sqladd", $this->rlink);
				while($data = mysql_fetch_assoc($result)) {
					$keyname = $table;
					foreach($keyarr as $k=>$v) {
						$keyname .= "-$k-".$data[$k];
					}
					$return[$keyname] = $data;
				}
			}
			return $return;
		}
	}

	public function delete($key) {
		list($table, $keyarr, $sqladd) = $this->parse_key($key);
		$tablename = $this->tablepre.$table;
		return $this->query("DELETE FROM $tablename WHERE $sqladd", $this->wlink);
	}
	
	/**
	 * 
	 * maxid('user-uid') 返回 user 表最大 uid
	 * maxid('user-uid', '+1') maxid + 1, 占位，保证不会重复
	 * maxid('user-uid', 10000) 设置最大的 maxid 为 10000
	 *
	 */
	public function maxid($key, $val = FALSE) {
		list($table, $col) = explode('-', $key);
		$maxid = $this->table_maxid($key);
		if($val === FALSE) {
			return $maxid;
		} elseif(is_string($val) && $val{0} == '+') {
			$val = intval($val);
			$this->query("UPDATE {$this->tablepre}framework_maxid SET maxid=maxid+'$val' WHERE name='$table'", $this->wlink);
			return $maxid += $val;
		} else {
			$this->query("UPDATE {$this->tablepre}framework_maxid SET maxid='$val' WHERE name='$table'", $this->wlink);
			// ALTER TABLE Auto_increment 这个不需要改，REPLACE INTO 直接覆盖
			return $val;
		}
	}
	
	// 原生的 count
	public function native_maxid($table, $col) {
		$tablename = $this->tablepre.$table;
		$arr = $this->fetch_first("SELECT MAX($col) AS num FROM $tablename");
		return isset($arr['num']) ? intval($arr['num']) : 0;
	}
	
	/* 返回表的总行数
	* count('forum')
	* count('forum-fid-1')
	* count('forum-fid-2')
	*/
	public function count($key, $val = FALSE) {
		$count = $this->table_count($key);
		if($val === FALSE) {
			return $count;
		} elseif(is_string($val)) {
			$count = $this->table_count($key);
			if($val{0} == '+') {
				$val = $count + abs(intval($val));
				$this->query("UPDATE {$this->tablepre}framework_count SET count = '$val' WHERE name='$key'", $this->wlink);
				return $val;
			} else {
				$val = max(0, $count - abs(intval($val)));
				$this->query("UPDATE {$this->tablepre}framework_count SET count = '$val' WHERE name='$key'", $this->wlink);
				return $val;
			}
		} else {
			$this->query("UPDATE {$this->tablepre}framework_count SET count='$val' WHERE name='$key'", $this->wlink);
			return $val;
		}
	}
	
	// 原生的 count
	public function native_count($table) {
		$tablename = $this->tablepre.$table;
		$arr = $this->fetch_first("SELECT COUNT(*) AS num FROM $tablename");
		return isset($arr['num']) ? intval($arr['num']) : 0;
	}
	
	public function truncate($table) {
		$table = $this->tablepre.$table;
		try {
			$this->query("TRUNCATE $table");// 不存在，会报错，但无关紧要
			return TRUE;
		} catch(Exception $e) {
			return FALSE;
		}
	}

	/*
		用法：
			同 index_fetch_id()
		返回：
			array(
				'user-uid-1'=>array('uid'=>1, 'username'=>'zhangsan'),
				'user-uid-2'=>array('uid'=>2, 'username'=>'lisi'),
				'user-uid-3'=>array('uid'=>3, 'username'=>'wangwu'),
			)
	*/
	public function index_fetch($table, $keyname, $cond = array(), $orderby = array(), $start = 0, $limit = 0) {
		$keynames = $this->index_fetch_id($table, $keyname, $cond, $orderby, $start, $limit);
		if(!empty($keynames)) {
			return $this->get($keynames);			
		} else {
			return array();
		}
	}
	
	/**
	 	用法：
			index_fetch_id('user', 'uid', array('uid'=> 100), array('uid'=>1), 0, 10);
			index_fetch_id('user', 'uid', array('uid'=> array('>'=>'100', '<'=>'200')), array('uid'=>1), 0, 10);
			index_fetch_id('user', 'uid', array('username'=> array('LIKE'=>'abc'), array('uid'=>1), 0, 10);
		返回：
			array (
				'user-uid-1',
				'user-uid-2',
				'user-uid-3',
			)
	*/
	public function index_fetch_id($table, $keyname, $cond = array(), $orderby = array(), $start = 0, $limit = 0) {
		$tablename = $this->tablepre.$table;
		$keyname = (array)$keyname;
		$sqladd = implode(',', $keyname);
		$s = "SELECT $sqladd FROM $tablename";
		if(!empty($cond)) {
			$s .= ' WHERE ';
			foreach($cond as $k=>$v) {
				if(!is_array($v)) {
					$v = addslashes($v);
					$s .= "$k = '$v' AND ";
				} else {
					foreach($v as $k1=>$v1) {
						$v1 = addslashes($v1);
						$k1 == 'LIKE' && $v1 = "%$v1%";
						$s .= "$k $k1 '$v1' AND ";
					}
				}
			}
			$s = substr($s, 0, -4);
		}
		if(!empty($orderby)) {
			$s .= ' ORDER BY ';
			$comma = '';
			foreach($orderby as $k=>$v) {
				$s .= $comma."$k ".($v == 1 ? ' ASC ' : ' DESC ');
				$comma = ',';
			}
		}
		$s .= ($limit ? " LIMIT $start, $limit" : '');
		
		$return = array();
		$result = $this->query($s, $this->rlink);
		while($data = mysql_fetch_assoc($result)) {
			$keyadd = '';
			foreach($keyname as $k) {
				$keyadd .= "-$k-".$data[$k];
			}
			$return[] = $table.$keyadd;
		}
		return $return;
	}
	
	
	// --------------> mysql 特定接口，仅供升级使用
	public function fetch_first($sql) {
		$result = $this->query($sql, $this->rlink);
		return mysql_fetch_assoc($result);
	}
	/*
	public function fetch_all($sql) {
		$return = $data = array();
		$result = $this->query($sql, $this->rlink);
		while($data = mysql_fetch_assoc($result)) {
			$return[] = $data;
		}
		return $return;
	}
	*/
	
	// -------------> 特定接口
	
	// $index = array('uid'=>1, 'dateline'=>-1)
	// $index = array('uid'=>1, 'dateline'=>-1, 'unique'=>TRUE, 'dropDups'=>TRUE)
	// 创建索引
	public function index_create($table, $index) {
		$table = $this->tablepre.$table;
		$keys = implode(', ', array_keys($index));
		$keyname = implode('', array_keys($index));
		return $this->query("ALTER TABLE $table ADD INDEX $keyname($keys)");
	}
	
	// 删除索引
	public function index_drop($table, $index) {
		$table = $this->tablepre.$table;
		$keys = implode(', ', array_keys($index));
		$keyname = implode('', array_keys($index));
		return $this->query("ALTER TABLE $table DROP INDEX $keyname");
	}

	// -------------> 公共方法，非公开接口
	public function query($sql, $link = NULL) {
		empty($link) && $link = $this->wlink;
		defined('DEBUG') && DEBUG && isset($_SERVER['sqls']) && count($_SERVER['sqls']) < 1000 && $_SERVER['sqls'][] = htmlspecialchars(stripslashes($sql));// fixed: 此处导致的轻微溢出后果很严重，已经修正。
		$result = mysql_query($sql, $link);
		if(!$result) {
			throw new Exception(self::br('MySQL Query Error:'.$sql.'. '.mysql_error()));
		}
		return $result;
	}
	
	// -------------> 私有方法
	private function connect($host, $user, $password, $name, $charset, $engine) {
		$link = mysql_connect($host, $user, $password, $name);
		if(!$link) {
			throw new Exception(self::br(mysql_error()));
		}
		$bool = mysql_select_db($name, $link);
		if(!$bool) {
			throw new Exception(self::br(mysql_error()));
		}
		if(!empty($engine) && $engine == 'InnoDB') {
			$this->query("SET innodb_flush_log_at_trx_commit=no", $link);
		}
		// 保证客户端一直是 utf-8
		if($charset) {
			// character_set_connection: sql 语句的编码，写入中文字符的时候必须设置正确
			// character_set_results: mysqld 返回的数据编码
			// character_set_client: 客户端编码, binary 不转换 mysqld 会将 character_set_client -> character_set_connection.
			$this->query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary, sql_mode=''", $link);
			//$this->query("SET names $charset /* $host $user */", $link);
			//$this->query("SET sql_mode=''", $link);
		}
		// sql-mode=""
		return $link;
	}

	private function result($query, $row) {
		return mysql_num_rows($query) ? intval(mysql_result($query, $row)) : 0;
	}
	
	/*
		例子：
		table_count('forum');
		table_count('forum-fid-1');
		table_count('forum-fid-2');
		table_count('forum-stats-12');
		table_count('forum-stats-1234');
		返回：总数值
	*/
	private function table_count($key) {
		$count = 0;
		$query = mysql_query("SELECT count FROM {$this->tablepre}framework_count WHERE name='$key'", $this->rlink);
		if($query) {
			$count = $this->result($query, 0);
		} elseif(mysql_errno($this->rlink) == 1146) {
			$this->query("CREATE TABLE {$this->tablepre}framework_count (
				`name` char(32) NOT NULL default '',
				`count` int(11) unsigned NOT NULL default '0',
				PRIMARY KEY (`name`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		} else {
			throw new Exception('framework_cout 错误, mysql_error:'.mysql_error());
		}
		if(empty($count)) {
			$this->query("REPLACE INTO {$this->tablepre}framework_count SET name='$key', count='0'", $this->wlink);
		}
		return $count;
	}
	
	/*
		例子：只能为表名
		table_maxid('forum-fid');
		table_maxid('thread-tid');
	*/
	private function table_maxid($key) {
		list($table, $col) = explode('-', $key);
		$maxid = 0;
		$query = mysql_query("SELECT maxid FROM {$this->tablepre}framework_maxid WHERE name='$table'", $this->rlink);
		if($query) {
			$maxid = $this->result($query, 0);
		} elseif(mysql_errno($this->rlink) == 1146) {
			$this->query("CREATE TABLE `{$this->tablepre}framework_maxid` (
				`name` char(32) NOT NULL default '',
				`maxid` int(11) unsigned NOT NULL default '0',
				PRIMARY KEY (`name`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		} else {
			throw new Exception("{$this->tablepre}framework_maxid 错误, mysql_errno:".mysql_errno().', mysql_error:'.mysql_error());
		}
		if(empty($maxid)) {
			$query = $this->query("SELECT MAX($col) FROM {$this->tablepre}$table", $this->rlink);
			$maxid = $this->result($query, 0);
			$this->query("REPLACE INTO {$this->tablepre}framework_maxid SET name='$table', maxid='$maxid'", $this->wlink);
		}
		return $maxid;
	}
	
	public static function br($s) {
		if(!core::is_cmd()) {
			return nl2br($s);
		} else {
			return $s;
		}
	}
	
	/*
		in: 'forum-fid-1-uid-2'
		out: array('forum', 'fid=1 AND uid=2', array('fid'=>1, 'uid'=>2))
	*/
	private function parse_key($key) {
		$sqladd = '';
		$arr = explode('-', $key);
		$len = count($arr);
		$keyarr = array();
		for($i = 1; $i < $len; $i = $i + 2) {
			if(isset($arr[$i + 1])) {
				$sqladd .= ($sqladd ? ' AND ' : '').$arr[$i]."='".addslashes($arr[$i + 1])."'";
				$t = $arr[$i + 1];// mongodb 识别数字和字符串
				$keyarr[$arr[$i]] = is_numeric($t) ? intval($t) : $t;
			} else {
				$keyarr[$arr[$i]] = NULL;
			}
		}
		$table = $arr[0];
		if(empty($table)) {
			throw  new Exception("parse_key($key) failed, table is empty.");
		}
		if(empty($sqladd)) {
			throw  new Exception("parse_key($key) failed, sqladd is empty.");
		}
		return array($table, $keyarr, $sqladd);
	}
	
	public function __destruct() {
		if(!empty($this->wlink)) {
			mysql_close($this->wlink);
		}
		if(!empty($this->rlink) && !empty($this->wlink) && $this->rlink != $this->wlink) {
			mysql_close($this->rlink);
		}
	}
	
	public function version() {
		return mysql_get_server_info($this->rlink);
	}

}
?>