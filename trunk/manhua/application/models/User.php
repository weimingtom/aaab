<?php
class User
{
	function __construct(&$base)
	{
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function getNumbers()
	{
		$group = Auth();
		
		$userArr = $this->db->fetch_all("SELECT * FROM mh_member");
		
		foreach ($userArr as $col)
		{
			$uArr[] = array('uid' => $col['uid'], 'username' => $col['username'], 'group' => $group[$col['groupid']]['Name']);
		}
		
		return $uArr;
	}
	
	function getOneUserData($uid)
	{
		$uArr = $this->db->fetch_first("SELECT * FROM mh_member WHERE uid = '".intval($uid)."' ");
		return $uArr;
	}
	
	function addUser($data)
	{
		$userCount = $this->db->result_first("SELECT * FROM mh_member WHERE username = '". $data['username'] ."' ");
		if($userCount > 0)
		{
			exit("<script>alert('此用户已经存在了'); history.go(-1)</script>");
		}
		$this->db->insert('mh_member', $data);
	}
	
	function updateUser($data, $uid)
	{
		$this->db->update('mh_member', $data, "uid = $uid");
	}
	
	function deleteUser($uid)
	{
		$this->db->query("DELETE FROM mh_member WHERE uid = '". intval($uid) ."' ");
	}
	
	function setUpdateUserPass($oldpassword, $password, $uname)
	{
		$uCount = $this->db->result_first("SELECT COUNT(*) FROM mh_member WHERE username = '". $uname ."' AND password = '". md5($oldpassword) ."' ");
		
		if($uCount == 0)
		{
			return false;
		}
		
		$this->db->update('mh_member', array('password' => md5($password)), " username = '". $uname ."' AND password = '". md5($oldpassword) ."' ");
		return true;
	}
}
?>