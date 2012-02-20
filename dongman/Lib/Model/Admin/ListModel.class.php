<?php 
import('AdvModel');
class ListModel extends AdvModel {
	// 自动验证设置
	protected $_validate=array(
		array('list_name','require','必须填写分类标题！',1),
		array('list_pid','number','必须填写选择父类！',1),
		array('list_oid','number','必须填写排序ID！',1),
	);
	// 自动填充设置
	protected $_auto=array(
		//array('user_pwd','md5',1,'function'),
		//array('user_regtime','time',1,'function'), // 对create_time字段在新加的时候写入当前时间戳
	);
}
?>