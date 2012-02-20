<?php 
import('AdvModel');
class GbModel extends AdvModel {
	protected $_validate=array(
	    array('gb_del','number','请选择是否审核！',3),
		array('gb_content','require','留言内容必须填写！',3),
	);
	protected $_auto=array(
		array('gb_addtime','gb_time',3,'callback'),
	);
	public function gb_time($str){
		if($_POST['gb_time']){
			return time();
		}else{
			return strtotime($str);
		}
	}	
}
?>