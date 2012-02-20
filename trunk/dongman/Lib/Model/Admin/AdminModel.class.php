<?php 
import('AdvModel');
class AdminModel extends AdvModel {
	protected $_validate=array(
		array('admin_name','require','标题必须！',1),
		//array('admin_pwd','require','密码必须！',1),
		array('admin_email','email','邮箱格式错误！',1),
	);
	protected $_auto=array(
		array('admin_pwd','pwd',3,'callback'),
		array('admin_ip','get_client_ip',3,'function'),
		array('admin_logintime','time',3,'function'),
	);
	public function pwd(){
		if(empty($_POST['admin_pwd'])){
		    return false;
		}else{
		    return md5($_POST['admin_pwd']);
		}		
	}
}
?>