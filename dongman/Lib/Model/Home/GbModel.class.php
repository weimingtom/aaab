<?php 
import('AdvModel');
class GbModel extends AdvModel {
	protected $_validate=array(
		array('gb_user','require','您没有填写呢称！',1),
		//array('gb_qq','number','请正确填写您的QQ号码！',1),
		array('gb_content','require','您没有填写留言内容！',1),
		array('gb_code','checkgbcode','您填写的验证码不正确！',1,'callback'),
		array('gb_del','checkgbtime','请坐下来稍息一下再留言好吗？',1,'callback'),
	);
	protected $_auto=array(
		array('gb_user','hh_content',1,'callback'),
		array('gb_content','hh_content',1,'callback'),
		array('gb_intro','hh_content',1,'callback'),
		array('gb_ip','get_client_ip',1,'function'),
		array('gb_addtime','time',1,'function'),
	);
	public function checkgbcode(){
		if($_SESSION['verify']!=md5($_POST['gb_code'])){
			return false;
		}
	}
	public function checkgbtime(){
		if(time()-$_SESSION['gb_lasttime']<C('user_second')){
			return false;
		}
	}
	public function hh_content($str){
		$array=explode('|',C('user_replace'));
		return str_replace($array,'***',hh($str));
	}			
}
?>