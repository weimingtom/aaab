<?php 
import('AdvModel');
class CmModel extends AdvModel {
	protected $_validate=array(
	    array('cm_del','number','请选择是否审核！',3),
		array('cm_content','require','评论内容必须填写！',3),
	);
	protected $_auto=array(
		array('cm_addtime','cm_time',3,'callback'),
	);
	public function cm_time($str){
		if($_POST['cm_time']){
			return time();
		}else{
			return strtotime($str);
		}
	}	
}
?>