<?php
class Getdata
{
	function __construct(&$base)
	{
		$this->base = $base;
		$this->db = $base->mydb();
	}
	
	function delmtData($mtid)
	{
		$this->db->query("DELETE FROM mh_product WHERE id IN ( $mtid )");
		$this->db->query("DELETE FROM mh_product_img WHERE productid IN ( $mtid )");
	}
	
	function yidongData( $ydcateid, $pid )
	{
		$this->db->query("UPDATE mh_product SET cateid = '". intval($ydcateid) ."' WHERE id IN ( $pid )");
	}
	
	function cateData( $arg )
	{
		$page = empty($_GET['page']) ? 1 : get('page');
		
		$where = isset($arg['cateid']) && !empty($arg['cateid']) ? ' cateid = ' . intval($arg['cateid']) : '';
		
		$where = !empty($where) && !empty($arg['keyname']) ? $where . " AND title like '%" . $arg['keyname'] ."%'" : 
		(empty($where) ? " title like '%" . $arg['keyname'] ."%' " : $where);
		
		$statNumber = $this->db->result_first("SELECT COUNT(*) FROM mh_product " . (!empty($where) ? "where $where" : ''));
		
		$multipage = page($statNumber, 30, $page, "admin.php?c=getdata");

		$start = page_get_start($page, 30, $statNumber);
		
		$allData = $this->db->fetch_all("SELECT * FROM mh_product ". (!empty($where) ? "where $where" : '') ." LIMIT $start, 30");
		
		$data = array();
		
		foreach ($allData as $val)
		{
			$data[] = array(
				'id' => $val['id'],
				'xxid' => $val['cateid'],
				'title' => $val['title'],
				'clicknum' => $val['clicknum'],
				'num' => 0,//$this->db->result_first("SELECT COUNT(*) FROM mh_product_img WHERE productid = '". $val['id'] ."' "),
				'smallpic' => sid_encode(str_ireplace('/Article/UploadFiles/','http://www.003dh.com/Article/UploadFiles/', $val['smallpic'])),
				'_smallpic' => str_ireplace('http://tu.jueserenti.com', '/upload', $val['smallpic']),
				'author' => $val['author'],
				'addtime' => date("Y-m-d", strtotime($val['addtime']))
			);
		}
		return array($data, $multipage);
	}
}
?>