<?php
class VodAction extends BaseAction{	
	// 影片管理
    public function show(){
		$where=array();$vod=$_REQUEST;unset($vod['a']);unset($vod['m']);unset($vod['g']);unset($vod['p']);unset($vod['vod_submit']);unset($vod['__hash__']);//去除多余的参数列表
		if($vod["vod_cid"]=='0'){
		    $where['vod_cid']=0;
		}elseif($vod["vod_cid"]>0){
		    $tree=list_search(F('_ppvod/listvod'),'list_id='.$vod["vod_cid"]);
			if(!empty($tree[0]['son'])){foreach($tree[0]['son'] as $val){$arrcid[]=$val['list_id'];}}else{$arrcid=$vod["vod_cid"];}
			if($arrcid){$where['vod_cid']=ids($arrcid);}
		}else{
		    $where['vod_cid']=array('gt',0);
		}
		if($vod["vod_stars"]){$where['vod_stars']=$vod["vod_stars"];}
		if($vod["vod_del"]==1){$where['vod_del']=1;}elseif($vod["vod_del"]==2){$where['vod_del']=0;}
		if($vod["vod_play"]){$where['vod_play']=array('like','%'.trim($vod["vod_play"]).'%');}
		if($vod["vod_continu"]==1){$where['vod_continu']=array('neq','0');}elseif($vod["vod_continu"]==2){$where['vod_continu']=0;}
		if($vod["vod_name"]){
			$keyword=trim($vod["vod_name"]);
			$search['vod_name']=array('like','%'.$keyword.'%');
			$search['vod_title']=array('like','%'.$keyword.'%');
			$map=$search;
			$map['_logic'] = 'or';
			$where['_complex']=$map;
			$vod["vod_name"]=urlencode($keyword);
		}
		if($vod["vod_type"]){$order=$vod["vod_type"];}else{$order='vod_'.C('admin_order_type');}
		if($vod["vod_order"]){$order=$order.' '.$vod["vod_order"];}else{$order=$order.' desc';};
		if($vod["vod_inputer"]){$where['vod_inputer']=$vod["vod_inputer"];}
		if($vod["vod_pic"]){$where['Left(vod_pic,8)']=array('eq','httpf://');}
		//查询开始
		$rs=D("Admin.Vod");
		$currentPage=!empty($_GET['p'])?$_GET['p']:1;
		if($vod["tag_type"]){
			$join=C('db_prefix').'tag on '.C('db_prefix').'vod.vod_id = '.C('db_prefix').'tag.tag_id';
			$tag['tag_name']=trim($vod['tag_name']);$tag['tag_sid']=1;$vod["tag_name"]=urlencode($vod["tag_name"]);
			$count=$rs->join($join)->where($tag)->count('vod_id');
			$array=$rs->join($join)->where($tag)->order($order)->limit(C('url_num_admin'))->page($currentPage)->select();
		}else{//dump($rs->getLastSql());
			$count=$rs->where($where)->count('vod_id');
			$array=$rs->where($where)->order($order)->limit(C('url_num_admin'))->page($currentPage)->select();
		}
		$totalPages=ceil($count/C('url_num_admin'));
		$pageUrl=adminurl('Admin-Vod/Show',$vod).'-p-{!page!}'.C('url_html_suffix');
		foreach($array as $key=>$val){
		    $array[$key]['vod_showid']='index.php?s=Admin-Vod-Show-vod_cid-'.$array[$key]['vod_cid'];
			if(C('url_html')){
			    $vodurl=C('site_path').getdataurl($array[$key]['vod_id'],$array[$key]['vod_cid'],$array[$key]['vod_name']).C('html_file_suffix');
			    $array[$key]['vod_readid']=str_replace('index'.c('html_file_suffix'),'',$vodurl);
			}else{
			    $array[$key]['vod_readid']=str_replace('home-','',str_replace('index.php?s=/home-','?s=',U('home-vod/read?id='.$array[$key]['vod_id'])));
			}
		}
		$_SESSION['vod_gourl']=adminurl('Admin-Vod/Show',$vod).'-p-'.$currentPage.C('url_html_suffix');//$_SERVER['HTTP_REFERER']回跳页面
		$this->ppvod_play();
		$this->assign($vod);
		$this->assign('listvod',F('_ppvod/listvod'));
		$this->assign('list',$array);
		$this->assign('page','共'.$count.'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,5,$pageUrl,'pagego(\''.$pageUrl.'\','.$totalPages.')'));
        $this->display(APP_PATH.'/Public/admin/vod.html');
    }

	// 添加编辑影片
    public function add(){
		$vod=D("Admin.Vod");$array=array();
		$vod_id=$_GET['vod_id'];
		if($vod_id>0){
            $where['vod_id']=$vod_id;
			$array=$vod->where($where)->find();
			foreach(explode('$$$',$array['vod_play']) as $key=>$val){
			    $play[array_search($val,C('play_player'))]=$val;
			}
			$array['vod_play_list']=C('play_player');
			$array['vod_server_list']=C('play_server');
			$array['vod_play']=explode('$$$',$array['vod_play']);
			$array['vod_server']=explode('$$$',$array['vod_server']);	
			$array['vod_url']=explode('$$$',$array['vod_url']);
			$tag=D("Admin.Tag");$array['vod_tag']=$tag->gettag($vod_id,1);//获取标签
		}else{
		    $array['vod_cid']=cookie('vod_cid');
		    $array['vod_stars']=0;
		    $array['vod_del']=0;
			$array['vod_hits']=0;
			$array['vod_addtime']=time();
			$array['vod_continu']=0;
			$array['vod_inputer']=$_SESSION['admin_name'];
			$array['vod_play_list']=C('play_player');
			$array['vod_server_list']=C('play_server');
			$array['vod_url']=array(0=>'');
		}
		$array['vod_language_list']=explode(',',C('play_language'));
		$array['vod_area_list']=explode(',',C('play_area'));
		$array['vod_year_list']=explode(',',C('play_year'));		
		$this->ppvod_play();
		$this->assign($array);
		$this->assign('listvod',F('_ppvod/listvod'));
		$this->display(APP_PATH.'/Public/admin/vod.html');
    }	
	
	// 新增数据
	public function insert(){
	    cookie('vod_cid',intval($_POST["vod_cid"]));
		$this->checkpost();
		$Form=D("Admin.Vod");
		if($Form->create()){
			$vod_id=$Form->add();
			if(false!==$vod_id){
				if(!empty($_POST["vod_tag"])){$tag=D("Admin.Tag");$tag->addtag($vod_id,1,$_POST["vod_tag"]);}			
			    $this->assign("jumpUrl",'index.php?s=Admin-Vod-Add');
				$this->success('数据添加成功,继续添加新影片！');
			}else{
				$this->error('数据写入错误');
			}
		}else{
		    $this->error($Form->getError());
		}
	}
	
	// 更新数据
	public function update(){
	    $this->checkpost();
		$Form=D("Admin.Vod");
		if($Form->create()){
			if(false!==$Form->save()){
				if(!empty($_POST["vod_tag"])){
					$tag=D("Admin.Tag");
					$tag->addtag($_POST["vod_id"],1,$_POST["vod_tag"]);
				}
			    if(C('html_cache_on')){
				    $id=md5($_POST["vod_id"]).c('html_file_suffix');
				    @unlink(APP_PATH.'/Html/Vod_read/'.$id);@unlink(APP_PATH.'/Html/Vod_play/'.$id);			
				}
				if(C('url_html')){
				    $id=$_POST["vod_id"];
				    echo'<iframe scrolling="no" src="index.php?s=Admin-Html-readvodid-id-'.$id.'" frameborder="0" style="display:none"></iframe>';
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
	
	//处理添加与编辑数据时的地址转化
    public function checkpost(){
		$play=$_POST["vod_play"];
		$server=$_POST["vod_server"];
		foreach($_POST["vod_url"] as $key=>$val){
			$val=trim($val);
			if(!empty($val)){
			    $vod_play[]=$play[$key];
				$vod_server[]=$server[$key];
				$vod_url[]=$val;
			};
		}
		if($_POST["vod_gold"]>10){$_POST["vod_gold"]=10;}
		$_POST["vod_letter"]=getfirstchar(trim($_POST["vod_name"]));
		$_POST["vod_play"]=implode('$$$',$vod_play);
		$_POST["vod_server"]=implode('$$$',$vod_server);
		$_POST["vod_url"]=implode('$$$',$vod_url);
		if(strpos($_POST["vod_pic"],'://')>0){
			$img = D('Img');$_POST["vod_pic"] = $img->down_load(trim($_POST["vod_pic"]));
		}
	}	
	
	// 删除数据
    public function del(){
		$vod_id=$_GET['vod_id'];
		$this->delfile($vod_id);
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('成功删除一条ID为'.$vod_id.'的数据！');
    }	
	
	// 批量删除数据
    public function delall(){
		foreach($_POST['vod_id'] as $value){$this->delfile($value);}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量删除数据成功！');
    }
	
	// 删除静态文件与图片
    public function delfile($vod_id){
		$rs=D("Admin.Vod");$arr=$rs->field('vod_cid,vod_pic,vod_name')->where('vod_id='.$vod_id)->find();
		@unlink(APP_PATH.getpicurl($arr['vod_pic']));
		if(C('url_html')>0){
			@unlink(APP_PATH.C('site_path').getdataurl($vod_id,$arr['vod_cid'],$arr['vod_name']).C('html_home_suffix'));
			@unlink(APP_PATH.C('site_path').C('url_vodplay').$vod_id.C('html_file_suffix'));
		}
		$rs->where('vod_id='.$vod_id)->delete();
    }
	
	// 批量生成数据
    public function ishtml(){
		foreach($_POST['vod_id'] as $value){
		echo'<iframe scrolling="no" src="index.php?s=Admin-Html-readvodid-id-'.$value.'" frameborder="0" style="display:none"></iframe>';
		}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量生成数据成功！');
    }
		
	// 批量审核
    public function isok(){
		$Form=D("Admin.Vod");
		foreach($_POST['vod_id'] as $value){$Form->where('vod_id='.$value)->setField('vod_del',0);}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量审核数据成功！');
    }
	
	// 批量取消审核
    public function notok(){
		$Form=D("Admin.Vod");
		foreach($_POST['vod_id'] as $value){$Form->where('vod_id='.$value)->setField('vod_del',1);}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量取消审核成功！');
    }
	
	// 批量设置连载
    public function continu(){
	    $vod_id=$_POST['vod_id'];
		$vod_continu=$_POST['vod_continu'];
		$Form=D("Admin.Vod");
		foreach($vod_id as $value){
		$Form->where('vod_id='.$value)->setField('vod_continu',$vod_continu[$value]);
		}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量设置连载成功！');
    }	
	
	// 批量设置星级
    public function stars(){
	    $id=$_POST['vod_stars'];
		$Form=D("Admin.Vod");
		foreach($_POST['vod_id'] as $value){
		    $Form->where('vod_id='.$value)->setField('vod_stars',$id);
		}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量设置星级成功！');
    }
	
	// 批量转移
    public function listgo(){
		$cid=$_POST['vod_cid'];
		if(getlistson($cid)){
			$Form=D("Admin.Vod");
			foreach($_POST['vod_id'] as $value){$Form->where('vod_id='.$value)->setField('vod_cid',$cid);}
			$this->assign("jumpUrl",$_SESSION['vod_gourl']);
			$this->success('批量转移数据成功！');		    
		}else{
			$this->assign("jumpUrl",$_SESSION['vod_gourl']);
			$this->error('请选择当前大类下面的子分类！');		
		}
    }
	
	// 批量变更
    public function other(){
	    $key=$_POST['vod_otherkey'];
	    $other=$_POST['vod_other'];
		if("0"==$other){$this->error('请选择批量设置条件！');}
		if("tag"==$other){
			$rs=D("Admin.Tag");
			if(empty($key)){
				foreach($_POST['vod_id'] as $value){
					$rs->where('tag_sid=1 and tag_id='.$value)->delete();
				}
			}else{
				foreach($_POST['vod_id'] as $value){
					$tag_new=$rs->gettag($value,1);if(!empty($tag_new)){$tag_new.=','.$key;}else{$tag_new=$key;}
					$rs->addtag($value,1,$tag_new);
				}
			}
		}else{
			if(empty($key)){$this->error('设置内容不能为空！');}
			if("addtime"==$other){
				$key=strtotime($key);
			}elseif("gold"==$other){
				if($key>10){$key=10;}
			}
			$rs=D("Admin.Vod");
			foreach($_POST['vod_id'] as $value){
				$rs->where('vod_id='.$value)->setField('vod_'.$other,$key);
			}
		}
		$this->assign("jumpUrl",$_SESSION['vod_gourl']);
		$this->success('批量变更相关参数成功！');
    }
	
	// 批量保存图片
    public function ajaximgdown(){
		$id=$_GET['id'];
		$rs=D("Admin.Vod");
		$img = D('Img');
		if($id=='fail'){
			$rs->execute('update '.C('db_prefix').'vod set vod_pic=REPLACE(vod_pic,"httpf://", "http://")');
		}
		$list=$rs->where('Left(vod_pic,7)="http://"')->order('vod_id desc')->limit(C('upload_http_down'))->select();
		if($list){
		    echo('<table width="98%" border="0" cellpadding="4" cellspacing="1" class="tableoutline"><tr class="tb_head"><td><h2>一次只批量保存前'.C('upload_http_down').'张/5秒后执行下一次任务/如果太多失败图片请先手工处理 <a href="javascript:history.go(0)">关闭窗口</a></h2></td></tr><tr><td>');
		  foreach($list as $key=>$value){
			$vod['vod_pic'] = $img->down_img($value['vod_pic']);
			if($vod['vod_pic']){
				$rs->where('vod_id='.$value['vod_id'])->setField('vod_pic',$vod['vod_pic']);
				echo($value['vod_pic'].'>>'.$vod['vod_pic'].'成功下载!<br>');
			}else{
				echo('<font color=red>保存失败</font>,建议手工编辑[<a href="index.php?s=Admin-Vod-Add-vod_id-'.$value['vod_id'].'" target="_blank">'.$value['vod_name'].'</a>]该影片的图片!<br>');
				$rs->where('vod_id='.$value['vod_id'])->setField('vod_pic',str_replace("http://","httpf://",$value['vod_pic']));
			}
		  }
		    echo('</td></tr></table>');
		}else{
			echo('end');
		}
    }
	
	// Ajax设置权重
    public function ajaxhot(){
		$vod_id=$_GET['id'];
		$vod_stars=$_GET['hot'];
		$Form=D("Admin.Vod");
		$Form->where('vod_id='.$vod_id)->setField('vod_stars',$vod_stars);
		exit('1');
    }
	
	// Ajax设置状态
    public function ajaxdel(){
		$vod_id=$_GET['id'];
		$vod_del=$_GET['isok'];
		$Form=D("Admin.Vod");
		$Form->where('vod_id='.$vod_id)->setField('vod_del',$vod_del);
		exit('1');
    }					
}
?>