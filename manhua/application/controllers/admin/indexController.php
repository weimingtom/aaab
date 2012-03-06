<?php
class IndexController extends adminbase
{
	function actionIndex()
	{
		header("location: admin.php?c=getdata");
		exit();
		$this->view->display('admin_index');
	}
}
?>