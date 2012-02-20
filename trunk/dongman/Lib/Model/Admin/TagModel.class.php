<?php 
import('AdvModel');
class TagModel extends AdvModel {
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
	public function gettag($id,$sid){
		$rs=D("Admin.Tag");
		$where['tag_id']=$id;$where['tag_sid']=$sid;
		$array=$rs->where($where)->select();
		if($array){
			foreach($array as $key=>$val){$tag[]=$array[$key]['tag_name'];}
			return implode(',',$tag);
		}else{
			return false;
		}
	}
	public function addtag($id,$sid,$tag){
		$rs=D("Admin.Tag");$data['tag_id']=$id;$data['tag_sid']=$sid;
		$rs->where($data)->delete(); 	
		$tags=explode(',',trim($tag));
		$tags=array_unique($tags);
		foreach($tags as $key=>$val){
			$data['tag_name']=$val;
			$rs->data($data)->add();
		}
	}		
}
?>