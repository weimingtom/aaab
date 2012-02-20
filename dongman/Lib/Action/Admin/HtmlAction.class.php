<?php
class HtmlAction extends BaseAction{
    public function _initialize(){
	    parent::_initialize();
	    header("Content-Type:text/html; charset=utf-8");
		$this->assign("waitSecond",C('url_time'));
		echo'<style type="text/css">.show{font-size:12px}.show span{font-size:21px;font-weight:bold;color:#ff0000}.show .green{color:green}.show .blue{color:blue}</style>';
    }	
	//显示生成
    public function show(){
	    $this->assign('listvod',F('_ppvod/listvod'));
		$this->assign('listnews',F('_ppvod/listnews'));
        $this->display(APP_PATH.'/Public/admin/html.html');
    }
	//判断生成条件
    public function checkhtml(){	
	    if(!C('url_html')){
		    $this->assign("jumpUrl",'index.php?s=Admin-Index-Main');
		    $this->error('动态模式不需要生成网站！');
		}
	}
	//判断栏目生成条件
    public function checkhtmllist(){	
		if(C('url_html')==1||C('url_html')==4){
			$this->checkhtml();
		}else{
			if(1==$_REQUEST['gid']){$this->assign("jumpUrl",'index.php?s=Admin-Html-Readvod-did-'.$_REQUEST['did'].'-gid-1');$this->success('不需要生成栏目,正在准备生成内容页！');}else{$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');$this->error('栏目分类设为动态,不需要生成！');}
		}
	}	
    //生成自定义模板
    public function mytpl(){
		$get=$_GET;
		if(!empty($get)){
			$tplsuffix=C('tmpl_template_suffix');
			$this->assign('pplist',A("Home"));
			C('html_file_suffix','.'.$get['ext']);C('tmpl_template_suffix','.'.$get['ext']);
			$this->buildHtml($get['id'],APP_PATH.'/'.C('url_map'),'Home:'.$get['id']);
			C('tmpl_template_suffix',$tplsuffix);
		}
		$this->success('自定义模板生成成功！');
    }	
    //生成网站地图
    public function mapsite(){
	    $this->assign('title',C('site_name').'网站地图'.C('site_by'));
	    $this->assign('pplist',A("Home.Vod"));
		$this->buildHtml("vodmap",APP_PATH.'/'.C('url_map'),'Home:pp_mapsite');
		$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
        $this->success('生成网站地图成功！');				    	
    }
    public function mapbaidu(){
	    $this->assign('pplist',A("Home.Vod"));
		C('html_file_suffix','.xml');
		$this->buildHtml("vodbaidu",APP_PATH.'/'.C('url_map'),'Home:pp_mapbaidu');
		$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
        $this->success('生成网站地图成功,请通过http://news.baidu.com/newsop.html提交！');				    	
    }
    public function mapgoogle(){
	    $this->assign('pplist',A("Home.Vod"));
		C('html_file_suffix','.xml');
		$this->buildHtml("vodgoogle",APP_PATH.'/'.C('url_map'),'Home:pp_mapgoogle');
		$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
        $this->success('生成网站地图成功,请通过http://www.google.com/webmasters/tools/提交！');;				    
    }
    public function maprss(){
	    $this->assign('pplist',A("Home.Vod"));
		C('html_file_suffix','.xml');
		$this->buildHtml("vodrss",APP_PATH.'/'.C('url_map'),'Home:pp_maprss');
		$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
		$this->success('生成网站RSS成功！');
    }
    public function mapall(){
	    $this->assign('title',C('site_name').'网站地图'.C('site_by'));
	    $this->assign('pplist',A("Home.Vod"));
		$this->buildHtml("vodmap",APP_PATH.'/'.C('url_map'),'Home:pp_mapsite');
		C('html_file_suffix','.xml');
		$this->buildHtml("vodrss",APP_PATH.'/'.C('url_map'),'Home:pp_maprss');
		$this->buildHtml("vodgoogle",APP_PATH.'/'.C('url_map'),'Home:pp_mapgoogle');
		$this->buildHtml("vodbaidu",APP_PATH.'/'.C('url_map'),'Home:pp_mapbaidu');
		$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
		$this->success('恭喜您！所有地图页静态化完成！');
    }
    //生成网站首页
    public function createindex(){
		$this->checkhtml();
	    $gid=$_REQUEST["gid"];
	    $this->assign('title',C('site_name').'首页'.C('site_by'));
	    $this->assign('pplist',A("Home.Vod"));
		$this->buildHtml("index",APP_PATH.'/','Home:pp_index');
		if(1==$gid){
			$this->assign("jumpUrl",'index.php?s=Admin-Html-Mapall');
			$this->success('恭喜您！网站首页静态化完成，正在准备生成地图页，请稍等！');	
		}else{
		    $this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
            $this->success('恭喜您！网站首页静态化完成！');				    
		}		
    }
    //读取列表
    public function showvod(){
		$this->checkhtmllist();
	    $id=$_REQUEST["id"];//要生成的分类id
		$gid=$_REQUEST["gid"];//是否转向
		$did=$_REQUEST["did"];//是否当天		
		$gourl='';$alllist=1;$where=array();$where['vod_del']=0;
		if($id<1){//处理是否为生成全部分类选项
			$pid=!empty($_GET['pid'])?$_GET['pid']:0;//生成全部分类时的正是生成第几个的ID
		    $rs=D("Admin.List");$listarr=$rs->field('list_id')->where('list_sid=1')->select();$id=$listarr[$pid]['list_id'];//取得要生成的分类ID
			$alllist=count($listarr);if($pid<($alllist-1)){$gourl='index.php?s=Admin-Html-Showvod-id-0-did-'.$did.'-gid-'.$gid.'-pid-'.($pid+1);}//跳转条件
		}
		//处理要生成的分类ID是否有小类并生成sql查询条件
		$tree=list_search(F('_ppvod/listvod'),'list_id='.$id);
		if(!empty($tree[0]['son'])){foreach($tree[0]['son'] as $val){$arrcid[]=$val['list_id'];}}else{$arrcid=$id;}
		if($arrcid){$where['vod_cid']=ids($arrcid);}
		$rs=D("Home.Vod");
		$count=$rs->where($where)->count('vod_id');
		//从缓存查询当前分类的一些信息并赋值
		$list=list_search(F('_ppvod/list'),'list_id='.$id);
		$skin=$list[0]['list_skin'];if(empty($skin)){$skin='pp_vodlist';}
		$this->assign($list[0]);
		$this->assign('pplist',A("Home.Vod"));
		$listRows=gettplnum($skin.'.html','pplist->ppvod\(\'(.*)\'\)');if($listRows){$totalPages=ceil($count/$listRows);}else{$totalPages=1;}//C('url_num_vod');根据模板标签得出共要生成多少页
		//准备生成html
		echo'<div class="show" id="show">总共需要生成分类<span>'.$alllist.'</span>个,正在生成第<span class="blue">'.($pid+1).'</span>个,当前分类一共需要生成<span>'.$totalPages.'</span>页<br>';
		C('html_vod',true);C('html_cid',$id);//为前台解析参数传值
		for($i=0;$i<$totalPages;$i++){
			C('html_page',$i+1);
			$this->assign('list_page',$i+1);$this->assign('title',$list[0]['list_name'].'第'.($i+1).'页-'.C('site_name').C('site_by'));//栏目第几页标题变量赋值与生成默认标题
			$listdir=getlisturl($id,$i+1);$this->buildHtml($listdir,APP_PATH.'/'.C('url_vodlist'),'Home:'.$skin);//生成html
			$listurl=C('site_path').C('url_vodlist').$listdir.C('html_file_suffix');
			echo $id.'_'.($i+1).'栏目分页静态化任务已经完成，点此查看>><a href="'.$listurl.'" target="_blank">'.$listurl.'</a><br>';
		};C('html_vod',false);
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if(!empty($gourl)){
			$this->assign("jumpUrl",$gourl);
            $this->success('当前分类('.$id.')的静态化任务已经完成，正在准备下一个，请稍等！');
		}else{
			if(1==$gid){
				$this->assign("jumpUrl",'index.php?s=Admin-Html-Readvod-did-'.$did.'-gid-1');
            	$this->success('恭喜您！所有栏目分类静态化任务已经完成，正在准备生成视频内容页，请稍等！');
			}else{
		        $this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
                $this->success('恭喜您！所有栏目分类静态化任务已经完成！');				    
			}			
	    }		
    }
	//读取影片内容按ID
	public function readvodid(){
		$this->checkhtml();
	    $id=$_REQUEST["id"];
		$rs=D("Home.Vod");
		$arr=$rs->where('vod_del=0 and vod_id='.$id)->find();
		if($arr){$this->createreadvod($arr);}
	}	
	//读取影片内容按分类
    public function readvod(){
	    $this->checkhtml();
	    $id=$_REQUEST["id"];
		$gid=$_REQUEST["gid"];//是否生成首页
		$did=$_REQUEST["did"];//是否一键当天
		$where=array();$where['vod_del']=0;
		if($did){
		    $time=time()-3600*24;
		    $where['vod_addtime']= array('GT',$time);		    
		}else{
			if($id>0){
				$tree=list_search(F('_ppvod/listvod'),'list_id='.$id);
				if(!empty($tree[0]['son'])){foreach($tree[0]['son'] as $val){$arrcid[]=$val['list_id'];}}else{$arrcid=$id;}
				if($arrcid){$where['vod_cid']=ids($arrcid);}
			}else{
				$where['vod_cid']=array('gt',0);
			}
		}
		$rs=D("Home.Vod");
		$count=$rs->where($where)->count('vod_id');
		$listRows=C('url_number');//每页生成数
		$totalPages=ceil($count/$listRows);//总页数
		$nowPage=!empty($_GET['pid'])?$_GET['pid']:1;if(!empty($totalPages) && $nowPage>$totalPages) {$nowPage = $totalPages;}//当前页数
		echo'<div class="show" id="show">总共需要生成数据<span>'.$count.'</span>个,分<span class="green">'.$totalPages.'</span>页生成,正在生成第<span class="blue">'.$nowPage.'</span>页<br>';
		$list=$rs->where($where)->order('vod_addtime desc')->limit($listRows)->page($nowPage)->select();
		if($list){
		    foreach($list as $key=>$value){
				$this->createreadvod($value);
			}
			echo '</div><script>document.getElementById("show").style.display="none";</script>';
			if($nowPage<$totalPages){//是否生成完成
				$this->assign("jumpUrl",'index.php?s=Admin-Html-Readvod-id-'.$id.'-did-'.$did.'-gid-'.$gid.'-pid-'.($nowPage+1));
           		$this->success('当前分页('.$nowPage.')的静态化任务已经完成,正在准备下一个,请稍等！');
			}else{
				if($gid){
					$this->assign("jumpUrl",'index.php?s=Admin-Html-Createindex-gid-1');
           			$this->success('所有内容页静态化任务已经完成,正在准备生成首页，请稍等！');
				}else{
					$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
					$this->success('恭喜您！所有静态化网页的任务已经完成！');				    
				}
			}
		}else{
			echo '</div><script>document.getElementById("show").style.display="none";</script>';
			$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');$this->error('暂无数据不需要生成！');
		}
    }
	//生成视频内容与播放页
    public function createreadvod($arr){
		$play = explode('$$$',$arr['vod_play']);
		$server = explode('$$$',$arr['vod_server']);
		$url  =explode('$$$',$arr['vod_url']);
		$arr['vod_count'] = count($url);
		$serverurl = C('play_server');
		$playerlist = C('play_player');
		foreach($play as $key=>$val){
			$servername = array_search($play[$sid],C('play_player'));
			$ppplay[$key] = array('servername'=>$servername,'playername'=>$playerlist[$val],'playname'=>$val,'serverurl'=>$serverurl[$server[$key]],'son'=>$this->playlist($url[$key],$arr['vod_id'],$key,$lid.$tid));
		}
		$list=list_search(F('_ppvod/list'),'list_id='.$arr['vod_cid']);
		if(C('url_html')<3){//解析播放页
			$arr['vod_number']='<span id="vod_number"></span>';
			$arr['vod_url']=str_replace('index'.C('html_home_suffix'),'',C('site_path').getdataurl($arr['vod_id'],$arr['vod_cid'],$arr['vod_name']).C('html_home_suffix'));//去除index.html
			$arr['vod_count']=count($url);
			$arr['vod_ppplay']=$ppplay;
			$arr['vod_playname']='<span id="vod_playname"></span>';	
			$arr['vod_playpath']='<span id="vod_playpath"></span>';
			//生成播放地址列表
			$pp_play = '<script>'."\n";
			$urllist='';$servername='';
			foreach($arr['vod_ppplay'] as $key=>$val){
				$playlist='';
				foreach($val['son'] as $keysub=>$valsub){
					if($keysub > 0){
						$playlist .= '+++'.$valsub['playname'].'++'.$valsub['playpath'];
					}else{
						$playlist .= $valsub['playname'].'++'.$valsub['playpath'];
					}
				}
				if($key>0){
					$servername .= '$$$'.$val['servername'];
					$urllist .= '$$$'.$playlist;
				}else{
					$servername .= $val['servername'];
					$urllist .= $playlist;
				}
			}
			$pp_play .= 'var vod_name="'.$arr['vod_name'].'";';
			$pp_play .= 'var list_name="<a href='.$list[0]['list_url'].'>'.$list[0]['list_name'].'</a>";';
			$pp_play .= 'var server_name="'.$servername.'";';
			$pp_play .= 'var player_name="'.$arr['vod_play'].'";';
			$urllist = urlencode($urllist);
			$pp_play .= 'var url_list="'.$urllist.'";';//转化后的所有播放地址赋值
			$pp_play .= '</script>'."\n";
			$arr['vod_player'] = $pp_play.'<iframe border="0" src="'.C('site_path').'Public/player/play.html" width="'.C('play_width').'" height="'.(C('play_height')+26).'" marginWidth="0" frameSpacing="0" marginHeight="0" frameBorder="0" scrolling="no" vspale="0" style="z-index:99;" noResize></iframe>';
		};//赋值并生成
		$this->assign($arr);
		$this->assign($list[0]);
		$this->assign('pplist',A("Home.Vod"));
		$this->assign('ppplay',$ppplay);
		$this->assign('title',$arr['vod_name'].'-'.C('site_name').C('site_by'));
		$skin='pp_vod';if(!empty($arr['vod_skin'])){$skin=$arr['vod_skin'];}
		$voddir=getdataurl($arr['vod_id'],$arr['vod_cid'],$arr['vod_name']);
		$this->buildHtml($voddir,APP_PATH.'/','Home:'.$skin);
		$vodurl=C('site_path').$voddir.c('html_file_suffix');
		if(C('url_html')==1||C('url_html')==2){//是否生成播放页
			$this->buildHtml($arr['vod_id'],APP_PATH.'/'.C('url_vodplay'),'Home:pp_play');
			echo 'ID为'.$arr['vod_id'].'的内容页与播放页静态化完成 <a href="'.$vodurl.'" target="_blank">'.$vodurl.'</a><br>';
		}else{
			echo 'ID为'.$arr['vod_id'].'的内容页静态化完成 <a href="'.$vodurl.'" target="_blank">'.$vodurl.'</a><br>';
		}
	}
	//分解播放地址链接	
	public function playlist($url,$vid,$sid,$other){
	    $play=explode(chr(13),$url);
		foreach($play as $key=>$val){
		    if(strpos($val,'$')>0){
		        $ji=explode('$',$val);
			    $list['playname']=trim($ji[0]);
			    $list['playpath']=trim($ji[1]);
			}else{
			    $list['playname']='第'.($key+1).'集';
			    $list['playpath']=trim($val);		
			}
			if(C('url_html')<3){
				$list['playurl']=C('site_path').C('url_vodplay').$vid.c('html_file_suffix').'?vod-play-id-'.$vid.'-sid-'.$sid.'-pid-'.$key.'.html';
			}else{
				$list['playurl']=str_replace('?s=','index.php?s=',ppvodurl('Home-vod/play',array('id'=>$vid,'sid'=>$sid,'pid'=>$key),'index.php',false,true));
			}
			$list['playcount']=count($play);
		    $urllist[]=$list;
		}
	    return $urllist;
	}
	//生成新闻列表
    public function shownews(){
	    $this->checkhtmllist();	
	    $id=$_REQUEST["id"];
		$gid=$_REQUEST["gid"];//是否转向
		$did=$_REQUEST["did"];//是否当天
		$gourl='';$alllist=1;$where=array();$where['news_del']=0;
		if($id<1){//处理是否为生成全部分类选项
			$pid=!empty($_GET['pid'])?$_GET['pid']:0;//全部分类当前ID
		    $rs=D("Admin.List");$listarr=$rs->field('list_id')->where('list_sid=2')->select();$id=$listarr[$pid]['list_id'];//取得要生成的分类ID
			$alllist=count($listarr);if($pid<($alllist-1)){$gourl='index.php?s=Admin-Html-Shownews-id-0-did-'.$did.'-gid-'.$gid.'-pid-'.($pid+1);}//跳转条件
		}
		//处理要生成的分类ID是否有小类并生成sql查询条件
		$tree=list_search(F('_ppvod/listnews'),'list_id='.$id);//查询小类
		if(!empty($tree[0]['son'])){foreach($tree[0]['son'] as $val){$arrcid[]=$val['list_id'];}}else{$arrcid=$id;}
		if($arrcid){$where['news_cid']=ids($arrcid);}
		$rs=D("Home.News");
		$count=$rs->where($where)->count('news_id');
		//从缓存查询当前分类的一些信息并赋值
		$list=list_search(F('_ppvod/list'),'list_id='.$id);
		$skin=$list[0]['list_skin'];if(empty($skin)){$skin='pp_newslist';}
		$this->assign($list[0]);
		$this->assign('pplist',A("Home.News"));
		$listRows=gettplnum($skin.'.html','pplist->ppnews\(\'(.*)\'\)');if($listRows){$totalPages=ceil($count/$listRows);}else{$totalPages=1;}
		//准备生成html
		echo'<div class="show" id="show">总共需要生成分类<span>'.$alllist.'</span>个,正在生成第<span class="blue">'.($pid+1).'</span>个,当前分类一共需要生成<span>'.$totalPages.'</span>页<br>';
		C('html_news',true);C('html_cid',$id);//为前台解析参数传值
		for($i=0;$i<$totalPages;$i++){
			C('html_page',$i+1);
			$this->assign('list_page',$i+1);$this->assign('title',$list[0]['list_name'].'第'.($i+1).'页-'.C('site_name').C('site_by'));//栏目第几页标题变量赋值与生成默认标题
			$listdir=getlisturl($id,$i+1);$this->buildHtml($listdir,APP_PATH.'/'.C('url_newslist'),'Home:'.$skin);//生成html
			$listurl=C('site_path').C('url_newslist').$listdir.C('html_file_suffix');
			echo $id.'_'.($i+1).'栏目分页静态化任务已经完成，点此查看>><a href="'.$listurl.'" target="_blank">'.$listurl.'</a><br>';
		};C('html_news',false);
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if(!empty($gourl)){
			$this->assign("jumpUrl",$gourl);
            $this->success('当前分类('.$id.')的静态化任务已经完成，正在准备下一个，请稍等！');
		}else{
			if(1==$gid){
				$this->assign("jumpUrl",'index.php?s=Admin-Html-Readnews-did-'.$did.'-gid-1');
            	$this->success('恭喜您！所有栏目分类静态化任务已经完成，正在准备生成视频内容页，请稍等！');				
			}else{
		        $this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
                $this->success('恭喜您！所有栏目分类静态化任务已经完成！');				    
			}
	    }
    }
	//读取新闻内容按ID
	public function readnewsid(){
		$this->checkhtml();
	    $id=$_REQUEST["id"];
		$rs=D("Home.News");
		$arr=$rs->where('news_del=0 and news_id='.$id)->find();
		if($arr){$this->createreadnews($arr);}
	}	
	//读取新闻内容按分类
    public function readnews(){
	    $this->checkhtml();
	    $id=$_REQUEST["id"];
		$gid=$_REQUEST["gid"];//是否转向
		$did=$_REQUEST["did"];//是否当天		
		$where=array();$where['news_del']=0;
		if($did){
		    $time=time()-3600*24;
		    $where['news_addtime']= array('GT',$time);		    
		}else{
			if($id>0){
				$tree=list_search(F('_ppvod/listnews'),'list_id='.$id);
				if(!empty($tree[0]['son'])){foreach($tree[0]['son'] as $val){$arrcid[]=$val['list_id'];}}else{$arrcid=$id;}
				if($arrcid){$where['news_cid']=ids($arrcid);}
			}else{
				$where['news_cid']=array('gt',0);
			}
		}
		$rs=D("Home.News");
		$count=$rs->where($where)->count('news_id');
		$listRows=C('url_number');$totalPages=ceil($count/$listRows);
		$nowPage=!empty($_GET['pid'])?$_GET['pid']:1;if(!empty($totalPages) && $nowPage>$totalPages) {$nowPage = $totalPages;}
		echo'<div class="show" id="show">总共需要生成数据<span>'.$count.'</span>个,分<span class="green">'.$totalPages.'</span>页生成,正在生成第<span class="blue">'.$nowPage.'</span>页<br>';
		$list=$rs->where($where)->order('news_id asc')->limit($listRows)->page($nowPage)->select();//news_addtime desc
		if($list){
		    foreach($list as $key=>$value){
				$this->createreadnews($value);
			}
			echo '</div><script>document.getElementById("show").style.display="none";</script>';
			if($nowPage<$totalPages){//是否生成完成
				$this->assign("jumpUrl",'index.php?s=Admin-Html-Readnews-id-'.$id.'-did-'.$did.'-pid-'.($nowPage+1));
           		$this->success('当前分页('.$nowPage.')的静态化任务已经完成,正在准备下一个,请稍等！');
			}else{
				if(1==$gid){
					$this->assign("jumpUrl",'index.php?s=Admin-Html-Createindex-gid-1');
           			$this->success('所有内容页静态化任务已经完成,正在准备生成首页，请稍等！');
				}else{
					$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');
					$this->success('恭喜您！所有静态化网页的任务已经完成！');				    
				}
			}
		}else{
		    echo '</div><script>document.getElementById("show").style.display="none";</script>';
			$this->assign("jumpUrl",'index.php?s=Admin-Html-Show');$this->error('暂无数据不需要生成！');
		}
    }		
	//生成新闻内容
    public function createreadnews($arr){
	    $pid=1;
		Vendor('News.Page');
		$CP=new cutpage();
		$CP->pagestr=$arr['news_content'];
		$CP->cut_custom="{nextpage}";//手动分页标签
		$CP->page_word=5000;
		$CP->cut_tag=array("</table>", "</div>", "</p>", "<br/>", "</img>", "”。", "。", ".", "！", "……", "？", ",");//自动分页截取尾部的标记
		$CP->url=str_replace('index'.C('html_home_suffix'),'',C('site_path').C('url_newsdata').getdataurl_p($arr['news_id'],'{!page!}').C('html_home_suffix'));
		$CP->cut_str();
		$totalPages=$CP->sum_page;
		$list=list_search(F('_ppvod/list'),'list_id='.$arr['news_cid']);
		$this->assign($list[0]);
		$this->assign('title',$arr['news_name'].'-'.C('site_name').C('site_by'));
		$this->assign('pplist',A("Home.News"));
		if($totalPages>0){
			for($i=1;$i<=$totalPages;$i++){
				$CP->ipage=$i;
				$arr['news_content']=$CP->pagearr[$i-1];//分页后的内容
				$this->assign($arr);
				$this->assign('page',$CP->show_prv_next());
				$voddir=getdataurl_p($arr['news_id'],$i);
				$this->buildHtml($voddir,APP_PATH.'/'.C('url_newsdata'),'Home:pp_news');
				$newsurl=C('site_path').C('url_newsdata').$voddir.C('html_file_suffix');
				echo 'ID为'.$arr['news_id'].'的内容页静态化完成 <a href="'.$newsurl.'" target="_blank">'.$newsurl.'</a><br>';	
			}
		}else{
			$CP->ipage=1;
			$arr['news_content']=$CP->pagearr[0];
			$this->assign($arr);
			$this->assign('page','');
			$voddir=getdataurl_p($arr['news_id'],1);
			$this->buildHtml($voddir,APP_PATH.'/'.C('url_newsdata'),'Home:pp_news');
			$newsurl=C('site_path').C('url_newsdata').$voddir.C('html_file_suffix');
			echo 'ID为'.$arr['news_id'].'的内容页静态化完成 <a href="'.$newsurl.'" target="_blank">'.$newsurl.'</a><br>';
		}
	}											
}
?>