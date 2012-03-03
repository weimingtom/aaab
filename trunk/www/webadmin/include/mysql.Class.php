<?php
/**
 *-------------------------数据库操作类-----------------------------*
*/
class mysql_Class
{
	var $db_start = 'mydecms_';
	function __construct($host, $user, $pass)
	{
 		@mysql_pconnect($host,$user,$pass) or die("数据库连接失败!");
		mysql_query("SET NAMES 'gbk'");
 	}
	
	function set_db_start($str="")
	{
		if($str<>"") $this -> db_start = $str;
	}
	
	function select_db($db)//连接表
	{
		return @mysql_select_db($db);
	}
	
	function query($sql)//执行SQL语句
	{
		return @mysql_query(str_replace('-table-',$this -> db_start,$sql));
	}
	
	function fetch_array($fetch_array)
	{
		return @mysql_fetch_array($fetch_array, MYSQL_ASSOC);
	}
	
	function result_first($sql)
	{
		$query = $this->query ($sql);
		return $this->result($query, 0);
	}
	
	function fetch_first($sql)
	{
		$query = $this->query ( $sql );
		return $this->fetch_array ( $query );
	}
	
	function fetch_all($sql, $id = '')
	{
		$arr = array ();
		$query = $this->query ( $sql );
		while ( $data = $this->fetch_array ( $query ) )
		{
			$id ? $arr [$data [$id]] = $data : $arr [] = $data;
		}
		return $arr;
	}
	
	function result($query, $row)
	{
		$query = @mysql_result ( $query, $row );
		return $query;
	}
	
	function num_rows($sql)//返回记录的行数
	{
		return @mysql_num_rows($sql);
	}
	
	function affected_rows()//取得前一次 MySQL 操作所影响的记录行数
	{
		return @mysql_affected_rows();
	}
	
	function close() //关闭数据库
	{ 
		return @mysql_close();
	}
	
	function insert($table,$arr) //添加记录
	{
		$sql = $this -> query("INSERT INTO `$table` (`".implode('`,`', array_keys($arr))."`) VALUES('".implode("','", $arr)."')");
		return $sql;
	}
	
	function update($table, $arr, $where = '') //修改记录
	{
		$sql = '';
		foreach($arr as $key => $value)
		{
			$sql .= ", `$key`='$value'";
		}
		$sql = substr($sql, 1);
		$sql = ($where) ? "UPDATE `$table` SET $sql WHERE $where": "UPDATE `$table` SET $sql";
		return $this -> query($sql);
	}
}

?>