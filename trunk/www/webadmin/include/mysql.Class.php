<?php
/**
 *-------------------------���ݿ������-----------------------------*
*/
class mysql_Class
{
	var $db_start = 'mydecms_';
	function __construct($host, $user, $pass)
	{
 		@mysql_pconnect($host,$user,$pass) or die("���ݿ�����ʧ��!");
		mysql_query("SET NAMES 'gbk'");
 	}
	
	function set_db_start($str="")
	{
		if($str<>"") $this -> db_start = $str;
	}
	
	function select_db($db)//���ӱ�
	{
		return @mysql_select_db($db);
	}
	
	function query($sql)//ִ��SQL���
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
	
	function num_rows($sql)//���ؼ�¼������
	{
		return @mysql_num_rows($sql);
	}
	
	function affected_rows()//ȡ��ǰһ�� MySQL ������Ӱ��ļ�¼����
	{
		return @mysql_affected_rows();
	}
	
	function close() //�ر����ݿ�
	{ 
		return @mysql_close();
	}
	
	function insert($table,$arr) //��Ӽ�¼
	{
		$sql = $this -> query("INSERT INTO `$table` (`".implode('`,`', array_keys($arr))."`) VALUES('".implode("','", $arr)."')");
		return $sql;
	}
	
	function update($table, $arr, $where = '') //�޸ļ�¼
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