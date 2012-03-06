<?php
class LoginController extends AdminController
{
	function __construct()
	{
		parent::__construct();
	}

	public function actionIndex()
	{
		if(isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']))
		{
			$data['username'] = getgpc('username', 'P');
			$data['password'] = getgpc('password', 'P');
				
			daddslashes(&$data);
				
			$uRow = $this->userModel->getUserPass($data);
				
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

	public function actionLogout()
	{
		setcookie('ncms_sid', '', time() - 43200);
		setcookie('ncms_group', '', time() - 43200);
		header("Location: admin.php?c=login");
	}
}
?>