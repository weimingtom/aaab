<?php

//! defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );

class huabandb 
{
	public $querynum = 0;
	public $histories;
	
	protected $link;
	protected $dbhost;
	protected $dbuser;
	protected $dbpw;
	protected $dbcharset;
	protected $pconnect;
	protected $tablepre;
	protected $time;
	
	protected $goneaway = 5;
	
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = '', $pconnect = 0, $tablepre = '', $time = 0)
	{
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpw = $dbpw;
		$this->dbname = $dbname;
		$this->dbcharset = $dbcharset;
		$this->pconnect = $pconnect;
		$this->tablepre = $tablepre;
		$this->time = $time;
		
		if ($pconnect)
		{
			if (! $this->link = mysql_pconnect ( $dbhost, $dbuser, $dbpw ))
			{
				$this->halt ( 'Can not connect to MySQL server' );
			}
			
		}
		else
		{
			if (! $this->link = mysql_connect ( $dbhost, $dbuser, $dbpw ))
			{
				$this->halt ( 'Can not connect to MySQL server' );
			}
		}
		
		if ($this->version () > '4.1')
		{
			if ($dbcharset)
			{
				mysql_query ( "SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link );
			}
			
			if ($this->version () > '5.0.1')
			{
				mysql_query ( "SET sql_mode=''", $this->link );
			}
		}
		
		if ($dbname)
		{
			mysql_select_db ( $dbname, $this->link );
		}
	
	}	

	function get_strs($data)
	{
		$str_col = '';
		$str_val = '';
		foreach ( $data as $key => $val )
		{
			$str_col .= $key . ', ';
			$str_val .= '"' . $val . '", ';
		}
		$str_col = substr ( $str_col, 0, - 2 );
		$str_val = substr ( $str_val, 0, - 2 );
		return array ('col' => $str_col, 'val' => $str_val );
	}
	
	static function get_update_strs($data)
	{
		$str = '';
		foreach ( $data as $key => $val )
		{
			if(isset($val{0}) && ($val{0}=='+' || $val{0}=='-')) {
				$str .= " $key=$key$val, ";
			} else {
				$str .= ' ' . $key . '="' . $val . '", ';
			}
			
		}
		$str = substr ( $str, 0, - 2 );
		return $str;
	}
	
	function update($table, $data, $where = '')
	{
		$col = self::get_update_strs($data);
		$this->query("UPDATE $table SET $col ". (!empty($where) ? " WHERE $where" : ''));
	}
	
	function insert($table, $data)
	{
		$col = self::get_strs($data);
		$this->query("INSERT INTO $table ({$col['col']}) VALUES ({$col['val']})");
		return $this->insert_id();
	}
	
	function fetch_array($query, $result_type = MYSQL_ASSOC)
	{
		return mysql_fetch_array ( $query, $result_type );
	}
	
	function result_first($sql)
	{
		$query = $this->query ( $sql );
		return $this->result ( $query, 0 );
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
	
	function cache_gc()
	{
		$this->query ( "DELETE FROM {$this->tablepre}sqlcaches WHERE expiry<$this->time" );
	}
	
	function query($sql, $type = '', $cachetime = FALSE)
	{
		$startTime = microtime(1);
		$func = $type == 'UNBUFFERED' && @function_exists ( 'mysql_unbuffered_query' ) ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link )) && $type != 'SILENT')
		{
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		$this->histories['sql'][] = $sql;
		$this->histories['time'][] = substr((microtime(1) - $startTime), 0, 6);
		return $query;
	}
	
	function affected_rows()
	{
		return mysql_affected_rows($this->link);
	}
	
	function error()
	{
		return (($this->link) ? mysql_error ($this->link) : mysql_error());
	}
	
	function errno()
	{
		return intval ( ($this->link) ? mysql_errno ( $this->link ) : mysql_errno () );
	}
	
	function result($query, $row)
	{
		$query = @mysql_result ( $query, $row );
		return $query;
	}
	
	function num_rows($query)
	{
		$query = mysql_num_rows ( $query );
		return $query;
	}
	
	function num_fields($query)
	{
		return mysql_num_fields ( $query );
	}
	
	function free_result($query)
	{
		return mysql_free_result ( $query );
	}
	
	function insert_id()
	{
		return ($id = mysql_insert_id ( $this->link )) >= 0 ? $id : $this->result ( $this->query ( "SELECT last_insert_id()" ), 0 );
	}
	
	function fetch_row($query)
	{
		$query = mysql_fetch_row ( $query );
		return $query;
	}
	
	function fetch_fields($query)
	{
		return mysql_fetch_field ( $query );
	}
	
	function version()
	{
		return mysql_get_server_info ( $this->link );
	}
	
	function close()
	{
		return mysql_close ( $this->link );
	}
	
	function halt($message = '', $sql = '')
	{
		$error = mysql_error ();
		$errorno = mysql_errno ();
		if ($errorno == 2006 && $this->goneaway -- > 0) {
			$this->connect ( $this->dbhost, $this->dbuser, $this->dbpw, $this->dbname, $this->dbcharset, $this->pconnect, $this->tablepre, $this->time );
			$this->query ( $sql );
		} else {
//			$s = 'Error:' . $error . ' ';
//			$s .= 'Errno:' . $errorno . ' ';
//			$s .= 'SQL::' . $sql."\r\n";
//			$fp = fopen('sqlerror.log', 'a+');
//			fwrite($fp, $s);
//			fclose($fp);
		}
	}
}

?>