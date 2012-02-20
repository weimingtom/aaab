 <?php
class ListAction extends BaseAction{	
	// 显示分类
    public function show(){
	    $sid=$_GET['sid'];
		if($sid){$where='list_sid='.$sid;}
	    $rs=D("Admin.List");
		$list=$rs->where($where)->order('list_oid asc')->select();
		if($list){
			$this->assign('listtree',list_to_tree($list,'list_id','list_pid','son',0));
			$this->display(APP_PATH.'/Public/admin/list.html');
		}else{
		    $this->assign("jumpUrl",'index.php?s=Admin-List-Add');
			$this->success('暂无分类数据请先添加！');		    
		}
    }

	// 添加编辑分类
    public function add(){
		$array=array();
	    $rs=D("Admin.List");
		$list_id=$_GET['list_id'];
		if($list_id>0){
            $where['list_id']=$list_id;// 设置查询条件
			//$field='list_id,list_pid,list_oid,list_name,list_dir,list_skin,list_keywords';// 需要查询的字段
			$array=$rs->where($where)->find();
		}else{
		    $array['list_id']=0;
		    $array['list_pid']=$_GET['list_pid'];
		    $array['list_oid']=$rs->max('list_oid')+1;
			$array['list_skin']='pp_vodlist';
		}
		$this->assign($array);
		$this->assign('listvod',F('_ppvod/listvod'));
		$this->assign('listnews',F('_ppvod/listnews'));
		$this->display(APP_PATH.'/Public/admin/list.html');
    }
	
	// 批量编辑数据
    public function editall(){
		$list=D("Admin.List");
		$array=$_POST;
		foreach($array['list_id'] as $value){
		    $data['list_oid'] = $array['list_oid'][$value];
			$data['list_sid'] = $array['list_sid'][$value];
			$data['list_zid'] = $array['list_zid'][$value];
			$data['list_name'] = $array['list_name'][$value];
			$data['list_dir'] = $array['list_dir'][$value];
			$data['list_skin'] = $array['list_skin'][$value];
			$list->where('list_id='.$value)->save($data); 
		}
		$this->ppvod_list();
		$this->success('数据更新成功！');
    }	
	
	// 批量删除数据
    public function delall(){
		$list=D("Admin.List");
		$array=$_POST;
		foreach($array['list_id'] as $value){
		    $list->where('list_id='.$value)->delete(); 
		}
		$this->ppvod_list();
		$this->success('数据删除成功！');
    }	
	
	// 写入数据
	public function insert(){
		$Form=D("Admin.List");
		if($Form->create()){//if($vo=$Form->create())返回值
			if(false!==$Form->add()){
			    $this->ppvod_list();
			    $this->assign("jumpUrl",'index.php?s=Admin-List-Show');
				$this->success('数据添加成功！');
			}else{
				$this->error('数据写入错误');
			}
		}else{
		    $this->error($Form->getError());
		}		
	}
	
	// 更新数据
	public function update(){
		$Form=D("Admin.List");
		if($Form->create()){
			$list=$Form->save();
			if($list!==false){
			    $this->ppvod_list();
				$this->success('数据更新成功！');
			}else{
				$this->error("没有更新任何数据!");
			}
		}else{
			$this->error($Form->getError());
		}
	}
	
	// 删除数据
    public function del(){
		$list=D("Admin.List");
		$list_id=$_GET['list_id'];
		if(!getlistson($list_id)){$this->error("请先删除本类下面的小类！");}
		$where['list_id']=$list_id;
		$list->where($where)->delete();
		$this->ppvod_list();
		$this->success('成功删除一条ID为'.$list_id.'的数据！');
    }					
}
?>