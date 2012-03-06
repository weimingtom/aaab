<?php
!defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );
class AdminController extends base 
{
	protected $sid;
	protected $group;
	
	function __construct()
	{
		parent::__construct();
		 
		$this->sid = getgpc('ncms_sid', 'C');
		$this->group = getgpc('ncms_group', 'C');
		
		$c = getgpc('c');

		if($c != 'login' && $c != 'logout')
		{
			$this->check_priv();
		}

		$this->sid = sid_decode($this->sid, TIMEOUT);
		$this->group = sid_decode($this->group, TIMEOUT);
	}
	
	private function check_priv()
	{
		$username = sid_decode($this->sid, TIMEOUT);
		$group = sid_decode($this->group, TIMEOUT);
		
		if(empty($username))
		{
			//header('location: admin.php?c=login');
			exit('无权限访问。');
		}
		else
		{
			$admin = $this->db->fetch_first("SELECT username FROM mh_member WHERE username='". daddslashes($username) ."' LIMIT 1");
			if(empty($admin['username']))
			{
				header('location: admin.php?c=login');
				exit;
			}
			else
			{
				$this->view->assign('username', $username);

				$this->sid = sid_encode ( $username, TIMEOUT );
				$this->group = sid_encode ( $group, TIMEOUT );
				
				setcookie('ncms_sid', $this->sid, time() + 43200);
				setcookie('ncms_group', $this->group, time() + 43200);
			}
		}
	}			
}
?>