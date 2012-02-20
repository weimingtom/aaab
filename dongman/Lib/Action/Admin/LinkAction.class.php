 <?php
class LinkAction extends BaseAction{	
	// 显示分类
    public function show(){
	    $rs=D("Admin.Link");
		$array=$rs->order('link_order asc')->select();
		$order=$rs->max('link_order')+1;
		$this->assign('order',$order);
		$this->assign('listlink',$array);
		$this->display(APP_PATH.'/Public/admin/link.html');
    }
	
	// 更新数据
	public function update(){
	    $array=$_POST;
		$rs=D("Admin.Link");
		if(!empty($array['link_name_sub'])){
			$data['link_name'] = trim($array['link_name_sub']);
			$data['link_url'] = trim($array['link_url_sub']);
			$data['link_logo'] = trim($array['link_logo_sub']);
			$data['link_order'] = $array['link_order_sub'];
			$data['link_type'] = $array['link_type_sub'];
			$rs->add($data);
		}
		foreach($array['link_id'] as $value){
		    $data['link_id'] = $array['link_id'][$value];
			$data['link_name'] = trim($array['link_name'][$value]);
			$data['link_url'] = trim($array['link_url'][$value]);
			$data['link_logo'] = trim($array['link_logo'][$value]);
			$data['link_order'] = $array['link_order'][$value];
			$data['link_type'] = $array['link_type'][$value];
			if(empty($data['link_name'])){
			    $rs->where('link_id='.$data['link_id'])->delete();
			}else{
			    $rs->save($data);
			}
		}
		$link=$rs->order('link_type asc,link_order asc')->select();
		F('_ppvod/link',$link);
		$this->success('数据更新成功！');
	}					
}
?>