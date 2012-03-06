<?php
class Login
{
	function __construct(&$base)
	{
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getUserPass($data)
	{
		$userArr = $this->db->fetch_first("SELECT * FROM mh_member WHERE username = '". $data['username'] ."' AND password = '". md5($data['password']) ."' ");
		return $userArr;
	}
}
?>