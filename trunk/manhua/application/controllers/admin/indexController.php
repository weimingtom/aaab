<?php
class indexController extends adminbase
{
	function indexAction()
	{
		
		header("location: admin.php?c=getdata");
		
		exit();

		$this->view->display('admin_index');
	}
}
?>