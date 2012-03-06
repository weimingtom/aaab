<?php
class UserController extends AdminController
{
	function __construct()
	{
		parent::__construct();
	}

	function actionIndex()
	{
		$this->view->assign('userArr', $this->userModel->getNumbers());
		$this->view->display('user_index');
	}

	function actionEditpass()
	{

		if(isset($_POST['oldpassword'], $_POST['password'], $_POST['password2']) && !empty($_POST['password']) && !empty($_POST['oldpassword']))
		{
			$oldpassword = get('oldpassword', 'P');
			$password = get('password', 'P');
			$password2 = get('password2', 'P');
			if ($password == $password2)
			{
				if(!$this->userModel->setUpdateUserPass($oldpassword, $password, $this->sid))
				{
					exit("<script>alert('原始密码输入有误'); history.go(-1)</script>");
				}
				else
				{
					exit("<script>alert('密码修改成功'); location.href='?c=user'</script>");
				}
			}
		}
		$this->view->display('user_editpass');
	}

	function actionAdd()
	{
		if(isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['password2']))
		{
			if($_POST['password'] == $_POST['password2'])
			{
				$data['username'] = get('username', 'P');
				$data['password'] = md5(get('password', 'P'));
				$data['groupid'] = get('group', 'P');

				daddslashes(&$data);

				$this->userModel->addUser($data);

				header("Location: ?c=user");
			}
		}

		$this->view->assign('group', Auth());
		$this->view->display('user_add');
	}

	function actionEdit()
	{
		$uid = get('uid');

		if(!is_numeric($uid) || empty($uid))
		{
			header("Location: ?c=user");
		}

		if(isset($_POST['editserver']) && !empty($_POST['editserver']))
		{
			if(isset($_POST['password']) && !empty($_POST['password']))
			{
				$data['password'] = md5(get('password', 'P'));
			}

			$data['groupid'] = get('groupid', 'P');
				
			$this->userModel->updateUser($data, $uid);
				
			header("Location: ?c=user");
		}

		$this->view->assign('uArr', $this->userModel->getOneUserData($uid));
		$this->view->assign('group', Auth());

		$this->view->display('user_edit');
	}

	function actionDel()
	{
		$uid = get('uid', 'G');

		if(!is_numeric($uid) || empty($uid))
		{
			header("Location: ?c=user");
		}

		$this->userModel->deleteUser($uid);

		header("Location: ?c=user");
	}



}
?>