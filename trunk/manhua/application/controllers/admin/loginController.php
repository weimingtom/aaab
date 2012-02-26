<?php
class loginController extends adminbase
{
	function __construct()
	{
		parent::__construct();

		$this->load('admin_login');
	}

	function indexAction()
	{
		if(isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']))
		{
			$data['username'] = get('username', 'P');
			$data['password'] = get('password', 'P');
				
			daddslashes(&$data);
				
			$uRow = $this->cmd->getUserPass($data);
				
			if(!$uRow)
			{
				exit("<script>alert('用户名或密码错误');history.go(-1)</script>");
			}
			elseif ( is_array($uRow) && !empty($uRow['uid']) )
			{
				setcookie('ncms_sid', sid_encode($data['username'], TIMEOUT), time() + 43200);
				setcookie('ncms_group', sid_encode($uRow['groupid'], TIMEOUT), time() + 43200);
				header("Location: admin.php?c=user");
			}
			else
			{
				exit("<script>alert('用户名或密码错误');history.go(-1)</script>");
			}
		}
		$this->view->display('admin_login');
	}

	function logoutAction()
	{
		setcookie('ncms_sid', '', time() - 43200);
		setcookie('ncms_group', '', time() - 43200);
		header("Location: admin.php?c=login");
	}
}
?>