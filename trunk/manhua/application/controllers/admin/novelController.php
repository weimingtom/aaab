<?php
!defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );
class novelController extends adminbase
{
	public function __construct()
	{
		parent::__construct ();
		//$this->load('novel');
	}
	
	public function adminAction() {
		$page = max(1, getgpc('page'));
		
		$categorys = $this->categoryModel->get_categorys();
		$novels = $this->novelModel->get_novels('', $page, GODHOUSE_PPP3);
		$num  = $this->novelModel->get_num();
		$multipage = page($num, GODHOUSE_PPP3, $page, 'admin.php?c=novel&a=admin');
		
		$this->view->assign('novels', $novels);
		$this->view->assign('categorys', $categorys);
		$this->view->assign('multipage', $multipage);
		$this->display('novel_admin');
	}
	
	public function deleteAction() {
		$novelId = getgpc('novelId');
		$this->novelModel->delete_novel($novelId);
		$this->_alert('删除成功。', 'admin.php?c=novel&a=admin');
	}
}
?>