<?php 
// 导入高级模型类 用以支持表单自动验证和自动完成
import('AdvModel');
class UserModel extends AdvModel {
	// 自动验证设置
	protected $_validate=array(
		array('user_name','require','标题必须！',1),
		array('user_pwd','require','密码必须！',1),
		array('user_email','email','邮箱格式错误！',1),
	);
	// 自动填充设置
	protected $_auto=array(
		array('user_pwd','md5',1,'function'), // 对create_time字段在更新的时候写入当前时间戳
		array('user_regtime','time',1,'function'), // 对create_time字段在新加的时候写入当前时间戳
	);
	
}
?>