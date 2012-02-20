 <?php
class TagAction extends BaseAction{	
	// 显示分类
    public function show(){
		$rs=D("Admin.Tag");
		if('ajax'==$_GET['id']){
			$limit=100;$tpl='tagajax';
		}else{
			$tpl='tag';
		}
		$array=$rs->field('*,count(tag_name) as tag_count')->limit($limit)->group('tag_name,tag_sid')->order('tag_count desc')->select();
		foreach($array as $key=>$val){
			if($array[$key]['tag_sid']==2){
				$array[$key]['tag_url']=adminurl('Admin-News/Show',array('tag_name'=>urlencode($array[$key]['tag_name']),'tag_type'=>1),'',false,true);
			}else{
				$array[$key]['tag_url']=adminurl('Admin-Vod/Show',array('tag_name'=>urlencode($array[$key]['tag_name']),'tag_type'=>1),'',false,true);
			}
		}		
		$this->assign('tag_list',$array);
		$this->display(APP_PATH.'/Public/admin/'.$tpl.'.html');
    }
    public function del(){
		$rs=D("Admin.Tag");
		$tag=$_GET['id'];
		$rs->where("tag_name='".$tag."'")->delete();
		$this->success('成功删除标签为'.$tag.'的数据！');
    }									
}
?>