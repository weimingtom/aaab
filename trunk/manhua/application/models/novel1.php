<?php
class Novel
{
	function __construct(&$base) {
		$this->base = $base;
		$this->db = $base->mydb ();
	}
	
	public function get_novel($novelId) {
		return $this->db->fetch_first("SELECT * FROM god_novel WHERE novelId='$novelId'");
	}
	
	public function get_novel_condition($str){
		if($str) {
			$str = "WHERE $str";
			return $this->db->fetch_first("SELECT * FROM god_novel $str LIMIT 1");
		}
		return '';
	}
	
	public function get_novels($str='', $page=1, $length=0) {
		if($str) {
			$str = "WHERE $str";
		}
		$length = $length ? $length : GODHOUSE_PPP;
		$startRecord = ($page - 1)*$length;
		$data  = $this->db->fetch_all("SELECT * FROM god_novel $str ORDER BY novelId DESC LIMIT $startRecord,$length");
		foreach($data as &$v) {
			if (empty($v['picture'])) {
				$v['picture'] = '/images/untitled.bmp';
			}
			$v['createdTime2'] = date('Y-m-d H:i:s', $v['createdTime']);
		}
		return $data;
	}
	/* 根据子类ID查询所有小说 */
	public function get_novels_by_categoryId($categoryId, $page=1, $length=0) {
		$length = $length ? $length : GODHOUSE_PPP;
		$startRecord = ($page - 1)*$length;
		$data  = $this->db->fetch_all("SELECT * FROM god_novel WHERE categoryId='$categoryId' ORDER BY novelId DESC LIMIT $startRecord,$length");
		foreach($data as &$v) {
			$v['novelName'] = cutstr($v['novelName'], 20, '');
			if (empty($v['picture'])) {
				$v['picture'] = '/images/untitled.bmp';
			}
		}
		return $data;
	}
	/* 根据父类ID查询所有小说 */
	public function get_novels_by_parentId($categoryId, $page=1, $length=0) {
		$length = $length ? $length : GODHOUSE_PPP;
		$startRecord = ($page - 1)*$length;
		$data  = $this->db->fetch_all("SELECT * FROM god_novel WHERE parentId='$categoryId' ORDER BY novelId DESC LIMIT $startRecord,$length");
		foreach($data as &$v) {
			//$v['novelName'] = cutstr($v['novelName'], 20, '');
			if (empty($v['picture'])) {
				$v['picture'] = '/images/untitled.bmp';
			}
		}
		return $data;
	}
	
	/* 根据分类取热的小说列表 */
	public function get_hot_novels($mix='', $length=10) {
		$length = $length ? $length : 10;
		$sqladd = '';
		if($mix && is_numeric($mix)) {
			$sqladd = "WHERE categoryId='$mix'";
		} elseif($mix) {
			$sqladd = "WHERE $mix";
		}
		$data = $this->db->fetch_all("SELECT * FROM god_novel $sqladd ORDER BY views DESC LIMIT $length");
		foreach ($data as &$v) {
			$v['novelName'] = cutstr($v['novelName'], 18, '');
		}
		return $data;
	}
	
	/* 根据自定义条件获取总记录 */
	public function get_num($categoryId=0, $sqlstr='') {
		$sqladd = '';
		if($categoryId) {
			$sqladd = " categoryId='$categoryId' AND";
		}
		if($sqlstr) {
			$sqladd .= $sqlstr.' AND';
		}
		if($sqladd){
			$sqladd = "WHERE ".substr($sqladd, 0, -3);
		}
		$sql = "SELECT COUNT(*) FROM god_novel ".$sqladd;
		return $this->db->result_first($sql);
	}
	
	/* 修改章节总数 */
	public function update_novel_totals($novelId, $totals) {
		$this->db->query("UPDATE god_novel SET totals='$totals' WHERE novelId='$novelId'");
	}
	/* 修改点击数 */
	public function update_novel_views($novelId) {
		$this->db->query("UPDATE god_novel SET views=views+'1' WHERE novelId='$novelId'");
	}
	
	/*  插入小说数据 */
	public function insert_novel($arr) {
		$insert_id = 0;
		if($arr && is_array($arr)) {
			$insert_id = $this->db->insert('god_novel', $arr);
		}
		return $insert_id;
	}
	
	/* 删除小说和所有的章节 */
	public function delete_novel($novelId) {
		$this->db->query("DELETE FROM god_novel WHERE novelId='$novelId'");
		$this->db->query("DELETE FROM god_novel_content WHERE novelId='$novelId'");
		$this->db->query("DELETE FROM god_novel_spider WHERE novelId='$novelId'");
	}
	
	/* 修改第一条评论字节数 */
	public function set_novel_first_comment(&$novels, $length=70) {
		if($novels && isset($novels[0])) {
			$novels[0]['comment'] = cutstr($novels[0]['comment'], $length, '');
		}
	}
	
	/* ----------------------------------与chapter表相关的------------------------------------------- */
	public function get_novel_chapters($novelId) {
		return $this->db->fetch_all("SELECT * FROM god_novel_chapter WHERE novelId='$novelId' ORDER BY chapterId ASC");
	}
	
	public function insert_novel_chapter($arr) {
		if($arr && is_array($arr)) {
			$this->db->insert('god_novel_chapter', $arr);
		}
	}
	
	/* ----------------------------------与content表相关的------------------------------------------- */
	function get_novel_contents_by_chapterId($chapterId) {
		$data  = $this->db->fetch_all("SELECT * FROM god_novel_content WHERE chapterId='$chapterId' ORDER BY contentId ASC");
		return $data;
	}
	function get_novel_contents($novelId) {
		$data  = $this->db->fetch_all("SELECT * FROM god_novel_content WHERE novelId='$novelId' ORDER BY contentId ASC");
		/*foreach($data as &$v) {
			if (empty($v['picture'])) {
				$v['picture'] = '/images/untitled.bmp';
			}
		}*/
		return $data;
	}
	
	/* 根据传入条件获取小说列表 */
	function get_contents_list($sqladd='', $page=1, $ppp=10) {
		if($sqladd) {
			$sqladd = "WHERE $sqladd";
		}
		$startRecord = ($page-1)*$ppp;
		return $this->db->fetch_all("SELECT * FROM god_novel_content $sqladd LIMIT $startRecord, $ppp");
	}
	
	/* 获取总数 */
	function get_contents_num($sqladd) {
		if($sqladd) {
			$sqladd = "WHERE $sqladd";
		}
		return $this->db->result_first("SELECT COUNT(*) FROM god_novel_content $sqladd");
	}
	
	/* 获取当前章节 */
	function get_novel_content($contentId=0, $sqladd='', $order='DESC'){
		$sql = '';
		if($contentId) {
			$sql .= "contentId='$contentId' AND";
		}
		if($sqladd) {
			$sql .= " $sqladd AND";
		}
		if($sql) {
			$sql = 'WHERE '.substr($sql, 0, -3);
		}
		return $this->db->fetch_first("SELECT * FROM god_novel_content $sql ORDER BY contentId $order LIMIT 1");
	}
	
	/* 获取上下页目录 */
	function get_novel_content_dir($novelId, $sqladd='', $order='DESC') {
		$sql = '';
		if($novelId) {
			$sql .= "novelId='$novelId' AND";
		}
		if($sqladd) {
			$sql .= " $sqladd AND";
		}
		if($sql) {
			$sql = 'WHERE '.substr($sql, 0, -3);
		}
		return $this->db->fetch_first("SELECT * FROM god_novel_content $sql ORDER BY contentId $order LIMIT 1");
	}
	
	public function insert_novel_content($arr) {
		$insert_id = 0;
		if($arr && is_array($arr)) {
			$this->db->insert('god_novel_content', $arr);
		}
		return $insert_id;
	}
	
	/* -------------------------------------------与spdier表相关的--------------------------- */
	public function insert_novel_spider($arr) {
		if($arr && is_array($arr)) {
			$this->db->insert('god_novel_spider', $arr);
		}
	}
}
?>