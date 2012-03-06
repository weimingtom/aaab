<?php
class Spider
{
	private $_table = 'god_spider';
	
	function __construct(&$base)
	{
		$this->base = $base;
		$this->db = $base->mydb();
	}
	
	public function insertSpider($arr) {
		$rows = $this->db->insert($this->_table, $arr);
		if($rows) {
			return $this->db->insert_id();
		}
		return 0;
	}
	
	public function updateSpider($spiderId, $arr) {
		$sqlAdd = " spiderId='$spiderId' ";
		$this->db->update($this->_table, $arr, $sqlAdd);
	}
	
	function getList() {
		$sql = "SELECT * FROM god_spider ORDER BY spiderId DESC";
		$data = $this->db->fetch_all($sql);
		foreach($data as &$v) {
			$v['url'] = $v['normalUrl'] ? $v['normalUrl'] : $v['pageUrl']; 
			$v['createdTimeForm'] = date('Y-m-d H:i:s', $v['createdTime']);
		}
		return $data;
	}
	
	function getSpiderById($spiderId) {
		$sql = "SELECT * FROM god_spider WHERE spiderId='$spiderId'";
		return $this->db->fetch_first($sql);
	}
	
	function updateRecord($spiderId, $isTrancate=FALSE){
		if($isTrancate) {
			$sql = "UPDATE god_spider SET recordTotal=0 WHERE spiderId='$spiderId'";
		} else {
			$sql = "UPDATE god_spider SET recordTotal=recordTotal+'1' WHERE spiderId='$spiderId'";
		}
		$this->db->query($sql);
	}
	
	function deleteSpider($spiderId) {
		$sql = "DELETE FROM god_spider WHERE spiderId='$spiderId'";
		$this->db->query($sql);
	}
}
?>