<?php 
import('AdvModel');
class CollectModel extends AdvModel {
	protected $_validate=array(
	    array('collect_title','require','采集名称必须填写！',1),
		array('collect_cid','getlistson','请选择当前分类下面的子类栏目！',1,'function'),
	);
	protected $_auto=array(
		array('collect_liststr','trim',3,'function'),
		array('collect_listurlstr','trim',3,'function'),
		array('collect_listlink','trim',3,'function'),
		array('collect_replace','getreplace',3,'function'),
	);
}
?>