<?php
!defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );

class CateController extends adminbase
{
	public function __construct()
	{
		parent::__construct ();
		$this->load('admin_category');
	}

	public function actionCatedel()
	{
		$cateid= (int)get('cateid', 'G');

		if($cateid > 0 && is_numeric($cateid))
		{
			if($this->db->result_first("SELECT COUNT(*) FROM mh_categories WHERE parent_id = '$cateid' "))
			{
				$this->_alert('请先删除下面的分类！', 'admin.php?c=cate');
			}
			$this->db->query("DELETE FROM mh_categories WHERE id = '$cateid' ");
		}

		header("location: admin.php?c=cate");
	}

	public function actionIndex()
	{
		$ulist = $this->cmd->get_list();
		$this->view->assign('arr', $ulist);
		$this->display('cate_index');
	}

	public function actionAdd()
	{
		$action = get('action', 'P');

		if($action == 'add' && isset($_POST['action']) && !empty($_POST['action']) && !empty($_POST['cate_name']))
		{
			$cate_name = get('cate_name', 'P');

			$r = $this->db->result_first("SELECT COUNT(*) FROM mh_categories WHERE cate_name = '$cate_name' ");

			if($r > 0) $this->_alert('这个分类已经存在了.', 'admin.php?c=cate');

			$data['parent_id'] = get('parent_id', 'P');
			$data['cate_name'] = get('cate_name', 'P');
			$data['ismenu'] = isset($_POST['ismenu']) && !empty($_POST['ismenu']) ? get('ismenu', 'P') : 0;
			$data['menulink'] = get('menulink', 'P');
			$data['sort_order'] = get('sort_order', 'P');
			$data['content']  = get('content', 'P');
			$data['time']    = date('Y-m-d');

			$ve = $this->db->insert('mh_categories', $data);

			unset($data);

			$this->_alert('分类添加成功.', 'admin.php?c=cate', false);
		}

		$this->view->assign('options', $this->cmd->get_options());

		$this->display('cate_add');
	}

	public function actionEdit()
	{
		$cateid = (int)get('cateid');
		$action = get('action', 'P');

		if(isset($_POST['action']) && !empty($_POST['action']) && !empty($_POST['cate_name']) && is_numeric($cateid))
		{
			$data['parent_id'] = get('parent_id', 'P');
			$data['cate_name'] = get('cate_name', 'P');
			$data['ismenu'] = isset($_POST['ismenu']) && !empty($_POST['ismenu']) ? get('ismenu', 'P') : 0;
			$data['menulink'] = get('menulink', 'P');
			$data['sort_order'] = get('sort_order', 'P');
			$data['content']  = get('content', 'P');

			$up = $this->db->update('mh_categories', $data, "id = '$cateid'");

			unset($data);

			header("location: admin.php?c=cate");
		}

		$arr = $this->db->fetch_first("SELECT * FROM mh_categories WHERE id = '$cateid' ");

		$this->view->assign('info', array(
			'cateid' => $cateid,
			'parent_id' => $arr['parent_id'],
			'cate_name' => $arr['cate_name'],
			'ismenu' => $arr['ismenu'],
			'menulink' => $arr['menulink'],
			'sort_order' => $arr['sort_order'],
			'content'	=> $arr['content']
		));

		$this->view->assign('options', $this->cmd->get_options());

		$this->display('cate_edit');
	}

	/* 清空数据 */
	public function actionTruncate() {
		$categoryId = getgpc('categoryId');
		if ($categoryId && is_numeric($categoryId)) {
			$data  = $this->db->fetch_all("SELECT * FROM god_novel WHERE categoryId='$categoryId'");
			foreach($data as $v) {
				$this->novelModel->delete_novel($v['novelId']);
			}
			$this->_alert('删除成功。', 'admin.php?c=cate&a=index');
		} else {
			$this->_alert('删除失败。', 'admin.php?c=cate&a=index');
		}
	}
}

?>