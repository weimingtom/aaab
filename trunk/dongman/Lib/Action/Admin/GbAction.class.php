 <?php
class GbAction extends BaseAction{	
	// 显示分类
    public function show(){
		$where=array();$gb=$_REQUEST;unset($gb['a']);unset($gb['m']);unset($gb['g']);unset($gb['p']);unset($gb['gb_submit']);unset($gb['__hash__']);//去除多余的参数列表
		if($gb['gb_del']==1){$where['gb_del']=1;}elseif($gb['gb_del']==2){$where['gb_del']=0;}
		if($gb['gb_intro']==1){$where['gb_intro']=array('eq','');}elseif($gb['gb_intro']==2){$where['gb_intro']=array('neq','');}
		if($gb['gb_cid']==1){$where['gb_cid']=array('eq',0);}elseif($gb['gb_cid']==2){$where['gb_cid']=array('gt',0);}
		if($gb['gb_type']){$order=$gb['gb_type'];}else{$order='gb_id';}
		if($gb['gb_order']){$order=$order.' '.$gb['gb_order'];}else{$order=$order.' desc';}
		if($gb['gb_keyword']){
			$search=array();
			$search['gb_user']=array('like','%'.$gb['gb_keyword'].'%');
			$search['gb_ip']=array('like','%'.$gb['gb_keyword'].'%');
			$search['gb_content']=array('like','%'.$gb['gb_keyword'].'%');
			$map=$search;
			$map['_logic'] = 'or';
			$where['_complex']=$map;
			$gb["gb_keyword"]=urlencode($gb["gb_keyword"]);
		}
	    $rs=D("Admin.Gb");
		$count=$rs->where($where)->count('gb_id');
		$currentPage=!empty($_GET['p'])?$_GET['p']:1;
		$totalPages=ceil($count/C('url_num_admin'));
		$pageUrl=adminurl('Admin-Gb/Show',$gb).'-p-{!page!}'.C('url_html_suffix');
		$array=$rs->where($where)->order('gb_oid desc,'.$order)->limit(C('url_num_admin'))->page($currentPage)->select();//dump($rs->getLastSql());
		$_SESSION['vod_gourl']=adminurl('Admin-Gb/Show',$gb).'-p-'.$currentPage.C('url_html_suffix');
		$this->assign($gb);
		$this->assign('gb_list',$array);
		$this->assign('page','共'.$count.'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,5,$pageUrl,'pagego(\''.$pageUrl.'\','.$totalPages.')'));
		$this->display(APP_PATH.'/Public/admin/gb.html');
    }
	
    public function add(){
		$gb_id=$_GET['id'];
		$rs=D("Admin.Gb");
		if($gb_id>0){
            $where['gb_id']=$gb_id;
			$array=$rs->where($where)->find();
		}
		$this->assign($array);
		$this->display(APP_PATH.'/Public/admin/gb.html');
    }
	
    public function update(){
		$rs=D("Admin.Gb");
		if($rs->create()){
			if(false!==$rs->save()){
			    $this->assign("jumpUrl",$_SESSION['vod_gourl']);
				$this->success('更新回复留言成功！');
			}else{
				$this->error("没有更新任何数据!");
			}
		}else{
			$this->error($rs->getError());
		}
    }
	
    public function del(){
		$rs=D("Admin.Gb");
		$gb_id=$_GET['id'];
		$rs->where('gb_id='.$gb_id)->delete();
		$this->success('成功删除一条ID为'.$gb_id.'的数据！');
    }
	
    public function ding(){
		$rs=D("Admin.Gb");
		$id=$_GET['id'];$cid=$_GET['cid'];
		if($cid==1){
			$rs->where('gb_id='.$id)->setField('gb_oid',1);
			$this->success('置顶留言成功！');		
		}else{
			$rs->where('gb_id='.$id)->setField('gb_oid',0);
			$this->success('取消置顶成功！');
		}
    }	
	
    public function delall(){
		$post=$_POST['gb_id'];$get=$_GET['cid'];$rs=D("Admin.Gb");
		if($get==1){
			foreach($post as $value){$rs->where('gb_id='.$value)->delete();}
			$this->success('批量删除留言成功！');		
		}elseif($get==2){
			$rs->where('gb_id>0')->delete();
			$this->success('清空所有留言成功！');	
		}else{
			$this->error('操作不正确！');
		}
    }

    public function shenhe(){
		$post=$_POST['gb_id'];$get=$_GET['cid'];$rs=D("Admin.Gb");
		if($get==0){
			foreach($post as $value){$rs->where('gb_id='.$value)->setField('gb_del',1);}
			$this->success('批量取消审核成功！');
		}elseif($get==1){
			foreach($post as $value){$rs->where('gb_id='.$value)->setField('gb_del',0);}
			$this->success('批量审核留言成功！');
		}elseif($get==2){
			$rs->where('gb_id>0')->setField('gb_del',0);
			$this->success('所有留言审核成功！');
		}elseif($get==3){
			$rs->where('gb_id>0')->setField('gb_del',0);
			$this->success('所有留言取消审核成功！');
		}else{
			$this->error('操作不正确！');
		}
    }									
}
?>