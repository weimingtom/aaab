<?php
import('AdvModel');
class CmModel extends AdvModel {
	protected $_validate=array(
		array('cm_cid','number','您没有指定评论ID！',1),
		array('cm_sid','require','您没有指定评论模块！',1),
		array('cm_user','require','您没有填写呢称！',1),
		array('cm_content','require','您没有填写评论内容！',1),
		array('cm_user','checkcmcode','您填写的验证码不正确！',1,'callback'),
		array('cm_user','checkcmtime','请坐下来稍息一下，再评论好吗？',1,'callback')
	);
	protected $_auto=array(
		array('cm_sid','get_cm_sid',1,'callback'),
		array('cm_user','hh_content',1,'callback'),
		array('cm_content','hh_content',1,'callback'),
		array('cm_ip','get_client_ip',1,'function'),
		array('cm_addtime','time',1,'function'),
	);
	public function checkcmcode(){
		if($_SESSION['verify']!=md5($_POST['cm_code'])){
			return false;
		}
	}
	public function checkcmtime(){
		$cookie='cmadd-'.getsid($_POST['cm_sid']).'-'.$_POST['cm_cid'];
		if(Cookie::is_set($cookie)){
			return false;
		}
	}
	public function get_cm_sid(){
		return getsid($_POST['cm_sid']);
	}
	public function hh_content($str){
		$array=explode('|',C('user_replace'));
		return str_replace($array,'***',hh($str));
	}	
}
?>