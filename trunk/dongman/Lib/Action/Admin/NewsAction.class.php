<?php
class NewsAction extends BaseAction{	
	// 新闻管理
    public function show(){
		$where=array();$news=$_REQUEST;unset($news['a']);unset($news['m']);unset($news['g']);unset($news['p']);unset($news['news_submit']);
		if($news["news_cid"]>0){
		    $tree=list_search(F('_ppvod/listnews'),'list_id='.$news["news_cid"]);
			if(!empty($tree[0]['son'])){
				foreach($tree[0]['son'] as $val){$arrcid[]=$val['list_id'];}
			}else{
			    $arrcid=$news["news_cid"];
			}
		}
		if($arrcid){$where['news_cid']=ids($arrcid);}
		if($news["news_stars"]){$where['news_stars']=$news["news_stars"];}
		if($news["news_del"]==1){$where['news_del']=1;}
		if($news["news_name"]){$where['news_name']=array('like','%'.trim($news["news_name"]).'%');$vod["news_name"]=urlencode($vod["news_name"]);}
		if($news["news_type"]){$order=$news["news_type"];}else{$order='news_'.C('admin_order_type');}
		if($news["news_order"]){$order=$order.' '.$news["news_order"];}else{$order=$order.' desc';};
		//查询开始
	    $rs=D("Admin.News");
		$currentPage=!empty($_GET['p'])?$_GET['p']:1;
		if($news["tag_type"]){
			$join=C('db_prefix').'tag on '.C('db_prefix').'news.news_id = '.C('db_prefix').'tag.tag_id';
			$tag['tag_name']=trim($news['tag_name']);$tag['tag_sid']=2;$news["tag_name"]=urlencode($news["tag_name"]);
			$count=$rs->join($join)->where($tag)->count('news_id');
			$array=$rs->join($join)->where($tag)->order($order)->limit(C('url_num_admin'))->page($currentPage)->select();
		}else{//dump($rs->getLastSql());
			$count=$rs->where($where)->count('news_id');
			$array=$rs->where($where)->order($order)->limit(C('url_num_admin'))->page($currentPage)->select();
		}
		$totalPages=ceil($count/C('url_num_admin'));
		$pageUrl=adminurl('Admin-News/Show',$news).'-p-{!page!}'.C('url_html_suffix');
		foreach($array as $key=>$val){
		    $array[$key]['news_showid']='index.php?s=Admin-News-Show-news_cid-'.$array[$key]['news_cid'];
			if(C('url_html')){
				$dataurl=C('site_path').C('url_newsdata').getdataurl_p($array[$key]['news_id'],1).C('html_file_suffix');
				$array[$key]['news_readid']=str_replace('index'.C('html_file_suffix'),'',$dataurl);
			}else{
				$array[$key]['news_readid']=str_replace('home-','',str_replace('index.php?s=/home-','?s=',U('home-news/read?id='.$array[$key]['news_id'])));
			}
		}
		$_SESSION['vod_gourl']=adminurl('Admin-News/Show',$news).'-p-'.$currentPage.C('url_html_suffix');	
		$this->assign($news);
		$this->assign('listnews',F('_ppvod/listnews'));
		$this->assign('list',$array);
		$this->assign('page','共'.$count.'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,5,$pageUrl,'pagego(\''.$pageUrl.'\','.$totalPages.')'));
        $this->display(APP_PATH.'/Public/admin/news.html');
    }

	// 添加编辑
    public function add(){
		$news=D("Admin.News");
		$array=array();
		$news_id=$_GET['news_id'];
		if($news_id>0){
            $where['news_id']=$news_id;
			$array=$news->where($where)->find();
			$tag=D("Admin.Tag");$array['news_tag']=$tag->gettag($news_id,2);
		}else{
		    $array['news_cid']=cookie('news_cid');
		    $array['news_stars']=0;
		    $array['news_del']=0;
			$array['news_hits']=0;
			$array['news_inputer']='admin';
			$array['news_addtime']=time();
		} 
		$this->ppvod_play();
		$this->assign($array);
		$this->assign('listnews',F('_ppvod/listnews'));
		$this->display(APP_PATH.'/Public/admin/news.html');
    }
	
	// 新增数据
	public function insert(){
	    cookie('news_cid',intval($_POST["news_cid"]));
		$Form=D("Admin.News");
		$_POST["news_letter"]=getfirstchar(trim($_POST["news_name"]));
		if($Form->create()){
			$news_id=$Form->add();
			if(false!==$news_id){
				if(!empty($_POST["news_tag"])){$tag=D("Admin.Tag");$tag->addtag($news_id,2,$_POST["news_tag"]);}			
			    $this->assign("jumpUrl",'index.php?s=Admin-News-Add');
				$this->success('数据添加成功,继续添加新的文章新闻！');
			}else{
				$this->error('数据写入错误');
			}
		}else{
		    $this->error($Form->getError());
		}
	}
	
	// 更新数据
	public function update(){
	    if($_POST['news_time']){$_POST['news_addtime']=date('Y-m-d H:i:s',time());}
	    if(strpos($_POST["news_pic"],'://')>0){
			$img = D('Img');$_POST["news_pic"] = $img->down_load(trim($_POST["news_pic"]));
		}
		if($_POST["news_gold"]>10){$_POST["news_gold"]=10;}
		$Form=D("Admin.News");
		if($Form->create()){
			if(false!==$Form->save()){
				if(!empty($_POST["news_tag"])){
					$tag=D("Admin.Tag");
					$tag->addtag($_POST["news_id"],2,$_POST["news_tag"]);
				}			
				if(c('url_html')){
				    $id=$_POST["news_id"];
				    echo'<iframe scrolling="no" src="index.php?s=Admin-Html-readnewsid-id-'.$id.'" frameborder="0" style="display:none"></iframe>';
				}	
				$this->assign("jumpUrl",$_SESSION['vod_gourl']);		
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
		$Form=D("Admin.News");
		$news_id=$_GET['news_id'];
		$Form->where('news_id='.$news_id)->delete();
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('成功删除一条ID为'.$news_id.'的数据！');
    }	
	
	// 批量删除数据
    public function delall(){
		$Form=D("Admin.News");
		foreach($_POST['news_id'] as $value){$Form->where('news_id='.$value)->delete();}
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('批量删除数据成功！');
    }
	
	// 批量生成数据
    public function ishtml(){
		foreach($_POST['news_id'] as $value){
		echo'<iframe scrolling="no" src="index.php?s=Admin-Html-readnewsid-id-'.$value.'" frameborder="0" style="display:none"></iframe>';
		}
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('批量生成数据成功！');
    }	
		
	// 批量审核
    public function isok(){
		$Form=D("Admin.News");
		foreach($_POST['news_id'] as $value){$Form->where('news_id='.$value)->setField('news_del',0);}
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('批量审核数据成功！');
    }
	
	// 批量取消审核
    public function notok(){
		$Form=D("Admin.News");
		foreach($_POST['news_id'] as $value){$Form->where('news_id='.$value)->setField('news_del',1);}
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('批量取消审核成功！');
    }	
	
	// 批量设置星级
    public function stars(){
	    $id=$_POST['news_stars'];
		$Form=D("Admin.News");
		foreach($_POST['news_id'] as $value){
		    $Form->where('news_id='.$value)->setField('news_stars',$id);
		}
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('批量设置星级成功！');
    }
	
	// 批量转移
    public function listgo(){
		$cid=$_POST['news_cid'];
		if(getlistson($cid)){
			$Form=D("Admin.News");
			foreach($_POST['news_id'] as $value){$Form->where('news_id='.$value)->setField('news_cid',$cid);}
			$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
			$this->success('批量转移数据成功！');		    
		}else{
			$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
			$this->error('请选择当前大类下面的子分类！');		
		}
    }
	
	// 批量变更
    public function other(){
	    $key=$_POST['news_otherkey'];
	    $other=$_POST['news_other'];
		if("0"==$other){$this->error('请选择批量设置条件！');}
		if("addtime"==$other){
			$key=strtotime($key);
		}elseif("gold"==$other){
			if($key>10){$key=10;}
		}
		$Form=D("Admin.News");
		foreach($_POST['news_id'] as $value){
		    $Form->where('news_id='.$value)->setField('news_'.$other,$key);
		}
		$this->assign("jumpUrl",$_SERVER['HTTP_REFERER']);
		$this->success('批量变更相关参数成功！');
    }	
	
	// Ajax设置权重
    public function ajaxhot(){
		$news_id=$_GET['id'];
		$news_stars=$_GET['hot'];
		$Form=D("Admin.News");
		$Form->where('news_id='.$news_id)->setField('news_stars',$news_stars);
		exit('1');
    }
	
	// Ajax设置状态
    public function ajaxdel(){
		$news_id=$_GET['id'];
		$news_del=$_GET['isok'];
		$Form=D("Admin.News");
		$Form->where('news_id='.$news_id)->setField('news_del',$news_del);
		exit('1');
    }					
}
?>