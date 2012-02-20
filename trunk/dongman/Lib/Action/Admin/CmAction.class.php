 <?php
class CmAction extends BaseAction{	
	// 显示分类
    public function show(){
		$where=array();$cm=$_REQUEST;unset($cm['a']);unset($cm['m']);unset($cm['g']);unset($cm['p']);unset($cm['cm_submit']);unset($cm['__hash__']);//去除多余的参数列表
		if($cm["cid"]){$where['cm_cid']=$cm['cid'];}
		if($cm["cm_sid"]){$where['cm_sid']=$cm['cm_sid'];}
		if($cm["cm_del"]==1){$where['cm_del']=1;}elseif($cm["cm_del"]==2){$where['cm_del']=0;}
		if($cm["cm_type"]){$order=$cm["cm_type"];}else{$order='cm_id';}
		if($cm["cm_order"]){$order=$order.' '.$cm['cm_order'];}else{$order=$order.' desc';}
		if($cm["cm_keyword"]){
			$search=array();
			$search['cm_user']=array('like','%'.$cm["cm_keyword"].'%');
			$search['cm_ip']=array('like','%'.$cm["cm_keyword"].'%');
			$search['cm_content']=array('like','%'.$cm["cm_keyword"].'%');
			$map=$search;
			$map['_logic'] = 'or';
			$where['_complex']=$map;
			$cm["cm_keyword"]=urlencode($cm["cm_keyword"]);
		}
	    $rs=D("Admin.Cm");
		$count=$rs->where($where)->count('cm_id');
		$currentPage=!empty($_GET['p'])?$_GET['p']:1;
		$totalPages=ceil($count/C('url_num_admin'));
		$pageUrl=adminurl('Admin-Cm/Show',$cm).'-p-{!page!}'.C('url_html_suffix');
		$array=$rs->where($where)->order($order)->limit(C('url_num_admin'))->page($currentPage)->select();//dump($rs->getLastSql());
		$_SESSION['cm_gourl']=adminurl('Admin-Cm/Show',$cm).'-p-'.$currentPage.C('url_html_suffix');
		$this->assign($cm);
		$this->assign('cm_list',$array);
		$this->assign('page','共'.$count.'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,5,$pageUrl,'pagego(\''.$pageUrl.'\','.$totalPages.')'));
		$this->display(APP_PATH.'/Public/admin/cm.html');
    }
	
    public function add(){
		$cm_id=$_GET['id'];
		$rs=D("Admin.Cm");
		if($cm_id>0){
            $where['cm_id']=$cm_id;
			$array=$rs->where($where)->find();
		}
		$this->assign($array);
		$this->display(APP_PATH.'/Public/admin/cm.html');
    }
	
    public function update(){
		$rs=D("Admin.Cm");
		if($rs->create()){
			if(false!==$rs->save()){
			    $this->assign("jumpUrl",$_SESSION['cm_gourl']);
				$this->success('更新网友评论成功！');
			}else{
				$this->error("没有更新任何数据!");
			}
		}else{
			$this->error($rs->getError());
		}
    }
	
    public function del(){
		$rs=D("Admin.Cm");
		$cm_id=$_GET['id'];
		$rs->where('cm_id='.$cm_id)->delete();
		$this->success('成功删除一条ID为'.$cm_id.'的数据！');
    }
	
    public function delid(){
		$rs=D("Admin.Cm");
		foreach($_POST['cm_id'] as $value){$rs->where('cm_id='.$value)->delete();}
		$this->success('批量删除评论成功！');
    }
	
    public function delall(){
		$rs=D("Admin.Cm");
		$rs->where('cm_id>0')->delete();
		$this->success('清空评论成功！');
    }	

    public function sheheid(){
		$rs=D("Admin.Cm");
		foreach($_POST['cm_id'] as $value){$rs->where('cm_id='.$value)->setField('cm_del',0);}
		$this->success('批量审核评论成功！');
    }
	
    public function sheheall(){
		$rs=D("Admin.Cm");
		$rs->where('cm_id>0')->setField('cm_del',0);
		$this->success('所有评论审核成功！');
    }	
	
    public function daisheid(){
		$rs=D("Admin.Cm");
		foreach($_POST['cm_id'] as $value){$rs->where('cm_id='.$value)->setField('cm_del',1);}
		$this->success('批量取消审核成功！');
    }

    public function daisheall(){
		$rs=D("Admin.Cm");
		$rs->where('cm_id>0')->setField('cm_del',1);
		$this->success('所有评论取消审核成功！');
    }											
}
?>