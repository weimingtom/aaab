<?php 
import('AdvModel');
class NewsModel extends AdvModel {
	protected $_validate=array(
	    array('news_cid','number','请选择分类！',1),
		array('news_name','require','新闻文章名称必须填写！',1),
		array('news_cid','getlistson','请选择当前分类下面的子类栏目！',1,'function'),
	);
	protected $_auto=array(
		array('news_addtime','strtotime',3,'function'),
	);
}
?>