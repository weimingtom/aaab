<?php 
import('AdvModel');
class VodModel extends AdvModel {
	protected $_validate=array(
	    array('vod_cid','number','请选择分类！',3),
		array('vod_name','require','影片名称必须填写！',3),
		array('vod_cid','getlistson','请选择当前分类下面的子类栏目！',3,'function'),
	);
	protected $_auto=array(
		array('vod_addtime','vod_time',3,'callback'),
	);	
	public function vod_time($str){
		if($_POST['vod_time']){
			return time();
		}else{
			return strtotime($str);
		}
	}		
}
?>