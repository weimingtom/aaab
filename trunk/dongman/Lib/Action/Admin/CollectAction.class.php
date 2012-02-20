<?php
class CollectAction extends BaseAction{	
	// 资源列表
    public function xmllist(){
		$this->assign('jumpurl',F('_xml/xucai'));
		$this->display('./Public/admin/xml_show_list.html');
    }
	// FeiFei备用库
    public function feifei(){
		$cai = D('Cai');
		$xml = $cai->xml_httpurl('feifei');
		if ($xml) {
			$this->xmlmdb($xml);		
		}else{
			$this->error("采集失败，通常原因为网络不稳定或禁用了采集！");
		}
    }
	// 资源站A型
    public function caijia(){
		$cai = D('Cai');
		$xml = $cai->xml_httpurl('caijia');
		if ($xml) {
			$this->xmlmdb($xml);
		}else{
			$this->error("采集失败，通常原因为网络不稳定或禁用了采集！");
		}
    }
	// 断点续采
    public function xuncai(){
		$jumpurl = F('_xml/xucai');
		redirect($jumpurl);
    }			
	//分解数据
	public function xmlmdb($xml){
		$cai = D('Cai');	
		$array_url = $xml['url'];
		$array_tpl = $xml['tpl'];
		$xml_page = $xml['page'];
		$list_class = $xml['listclass'];
		$list_vod = $xml['listvod'];	
		//是否采集入库
		if($array_url['action']){
			$page = $array_url['page'];
			echo '<style type="text/css">div{font-size:12px;color: #333333}span{font-weight:bold;color:#FF0000}</style>';
			echo'<div id="show"><div>当前采集任务<strong class="green">'.$page.'</strong>/<span class="green">'.$xml_page['pagecount'].'</span>页，共需要采集数据<span>'.$xml_page['recordcount'].'</span>个</div>';
			foreach($list_vod as $key=>$vod){
				//判断地址入库
				$vod_play = explode('$$$',$vod['vod_play']);
				$vod_url = explode('$$$',$vod['vod_url']);
				echo '<div style="line-height:22px"><span>'.(($page-1)*$xml_page['pagesize']+$key+1).'</span> ['.getlistname($vod['vod_cid']).'] '.$vod['vod_name'].' 需采集<span>'.count($vod_play).'</span>组播放地址 <font color=green>';
				foreach($vod_play as $ii=>$value){
					$vod['vod_play'] = $value;
					$vod['vod_url'] = trim($vod_url[$ii]);
					echo $cai->xml_insert($vod,$array_url['pic']);
				}
				echo '</font></div>';
				ob_flush();
				flush();
			}
			if('all' == $array_url['action'] || 'day' == $array_url['action']){
				if($page < $xml_page['pagecount']){
					$jumpurl = str_replace('{!page!}',($page+1),$array_tpl['pageurl']);
					//缓存断点续采
					F('_xml/xucai',$jumpurl);	
					//跳转到下一页			
					echo C('play_collect_time').'秒后将自动采集下一页！';
					echo '<meta http-equiv="refresh" content='.C('play_collect_time').';url='.$jumpurl.'>';
				}else{
					//清除断点续采
					F('_xml/xucai',NULL);
					echo '<div>所有采集任务已经完成，返回[<a href="?s=Admin-Vod-Show">视频管理中心</a>]，查看[<a href="?s=Admin-Vod-Show-vod_cid-0">相似未入库影片</a>]!</div>';					
				}
			}
		}else{
			$array_url['vodids'] = '';
			$this->assign($array_url);
			$this->assign($array_tpl);
			$this->assign('list_class',$list_class);
			$this->assign('list_vod',$list_vod);
			$this->display('./Public/admin/xml_show.html');
		}
	}
	// 检测第三方资源分类是否绑定
    public function setbind(){
		$rs = M("List");
		$list = $rs->field('list_id,list_pid,list_sid,list_name')->where('list_sid = 1')->order('list_id asc')->select();
		foreach($list as $key=>$value){
			if(!getlistson($list[$key]['list_id'])){
				unset($list[$key]);
			}
		}
		$this->assign('list',$list);
		$this->display('./Public/admin/xml_setbind.html');
	}
	// 存储第三方资源分类绑定
    public function insertbind(){
		$bindcache = F('_xml/bind');
		if (!is_array($bindcache)) {
			$bindcache = array();
			$bindcache['1_1'] = 0;
		}
		$bindkey = trim($_GET['bind']);
		$bindinsert[$bindkey] = intval($_GET['cid']);
		$bindarray = array_merge($bindcache,$bindinsert);
		F('_xml/bind',$bindarray);
		exit('ok');
	}	
/*****官方一键采集功能完毕******************************/

	// 自定义采集管理
    public function show(){
	    $user=D("Admin.Collect");
		$list=$user->order('collect_id desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display('./Public/admin/collect.html');
    }
	// 添加编辑采集
    public function add(){
		$collect=D("Admin.Collect");
		$array=array();
		$collect_id=$_GET['collect_id'];
		if($collect_id>0){
            $where['collect_id']=$collect_id;// 设置查询条件段
			$array=$collect->where($where)->find();
			$array['collect_replace']=explode('|||',$array['collect_replace']);
		}else{
		    $array['collect_id']=0;
		    $array['collect_encoding']='2312';
			$array['collect_player']='qvod';
			$array['collect_savepic']=0;
			$array['collect_order']=0;
			$array['collect_pagetype']=1;
			$array['collect_pagesid']=1;
			$array['collect_pageeid']=9;
		}
		$this->ppvod_play();
		$this->assign($array);
		$this->assign('listtree',F('_ppvod/listvod'));
		$this->display('./Public/admin/collect.html');
    }
	
	// 新增数据
	public function insert() {
		$Form=D("Admin.Collect");
		if($Form->create()){
		    $vo=$Form->add();
			if(false!==$vo){
			    $this->f_replace($_POST['collect_replace'],$vo);
			    $this->assign("jumpUrl",'index.php?s=Admin-Collect-Ing-collect_id-'.$vo.'-tid-2');
				$this->success('数据添加成功,马上测试一下是否能正常采集！');
			}else{
				$this->error('数据写入错误');
			}
		}else{
		    $this->error($Form->getError());
		}
	}	
	
	// 更新数据
	public function update(){
		if(empty($_POST['collect_savepic'])) $_POST['collect_savepic']=0;
		if(empty($_POST['collect_order'])) $_POST['collect_order']=0;
		$Form=D("Admin.Collect");
		if($Form->create()){
			if(false!==$Form->save()){
				F('_collects/cid_'.$_POST['collect_id'],NULL);
				F('_collects/cid_'.$_POST['collect_id'].'_rule',NULL);
				$this->f_replace($_POST['collect_replace'],$_POST['collect_id']);
				$this->assign("jumpUrl",'index.php?s=Admin-Collect-Ing-collect_id-'.$_POST['collect_id'].'-tid-2');
				$this->success('数据更新成功,马上测试一下是否能正常采集！');
			}else{
				$this->error("没有更新任何数据!");
			}
		}else{
			$this->error($Form->getError());
		}
	}
	
	// 复制数据
    public function cop(){
	    $collect=D("Admin.Collect");
		$collect_id=$_GET['collect_id'];
		$rs=$collect->find($collect_id);
		unset($rs["collect_id"]);
		$rs["collect_title"]=$rs["collect_title"].'-复制';
		$collect->data($rs)->add();
		$this->success('成功复制一条采集规则！');
    }	
	
	// 删除数据
    public function del(){
		$collect=D("Admin.Collect");
		$collect_id=$_GET['collect_id'];
		$where['collect_id']=$collect_id;
		$collect->where($where)->delete();
		F('_collects/cid_'.$collect_id.'_rule',NULL);
		F('_collects/cid_'.$collect_id.'_replace',NULL);
		$this->success('成功删除一条ID为'.$collect_id.'的数据！');
    }	
	
	// 批量删除数据
    public function delall(){
		$collect=D("Admin.Collect");
		foreach($_POST['collect_id'] as $value){
		    $collect->where('collect_id='.$value)->delete();
			F('_collects/cid_'.$value.'_rule',NULL);
			F('_collects/cid_'.$value.'_replace',NULL); 
		}
		$this->success('批量删除数据成功！');
    }
	
	// 组合采集列表+批量采集数据
    public function ing(){
		$where['collect_id']=$_REQUEST['collect_id'];
		if(is_array($where['collect_id'])){
			$gid='1,'.implode(',',$where['collect_id']);
			$where['collect_id']=$where['collect_id'][0];
		}else{
			$gid=$_GET['gid'];
		}
		$collect=D("Admin.Collect");
		if($rs=$collect->where($where)->find()){
		    //生成匹配规则($rule)
			$rule=array();
			foreach($rs as $key =>$val){
			    if(strpos($val,'[$ppvod]')>0){$rule[$key]=getrole($val);}else{$rule[$key]=$val;}
			}
			//生成采集范围($listurl)
			if($rs['collect_pagetype']==2||$rs['collect_pagetype']==3){
				for($i=0;$i<=abs(intval($rs['collect_pagesid']-$rs['collect_pageeid']));$i++){
				    $listurl[$i]=str_replace('[$ppvod]',$i+$rs['collect_pagesid'],$rs['collect_pagestr']);
				}
			}elseif($rs['collect_pagetype']==1||$rs['collect_pagetype']==4){
			    $listurl=explode(chr(13),$rs['collect_liststr']);
			};
			//是否倒序($listurl)
			if(1==$rs['collect_order'])$listurl=getreurl($listurl);
			//生成缓存
			F('_collects/cid_'.$where['collect_id'],$listurl);
			F('_collects/cid_'.$where['collect_id'].'_rule',$rule);
			//规则写好后测试一个
			if(2==$_GET['tid']){
				$this->ingtest($where['collect_id'],$rs['collect_pagetype']);				
			}
			//判断采集方式开始采集	sid=start eid=end tid=test gid=nextcollect
			if($rs['collect_pagetype']==1||$rs['collect_pagetype']==2){
				$collect_url='index.php?s=Admin-Collect-Ingbylist-cid-'.$where['collect_id'].'-sid-0-eid-'.count($listurl).'-tid-'.$_GET['tid'].'-gid-'.$gid.'.html';
			}elseif($rs['collect_pagetype']==3||$rs['collect_pagetype']==4){
			    $collect_url='index.php?s=Admin-Collect-Ingbyid-cid-'.$where['collect_id'].'-sid-0-eid-'.count($listurl).'-tid-'.$_GET['tid'].'-gid-'.$gid.'.html';
			}
			header("Location: ".$collect_url."");
		}else{
		    $this->error('未查询到相关记录!');
		}
    }
	
	//判断与处理下一个采集节点转向地址
	public function checknext($string){
		if(!empty($string)){
			$gid=explode(',',$string);
			$collect_nowid=$gid[0];
			$gid[0]=$collect_nowid+1;
			$collect_nexturl='index.php?s=Admin-Collect-Ing-collect_id-'.$gid[$gid[0]];	
			$collect_count=count($gid)-1;
			$gid=implode(',',$gid);
			$collect_nexturl=$collect_nexturl.'-gid-'.$gid;//生成下一个采集节点地址
			if($collect_nowid==$collect_count){
				if(c('url_html')){
					header("Location: index.php?s=Admin-Html-Showvod-did-1-gid-1");//生成当天静态
				}else{
					if(c('html_cache_on')){
					header("Location: index.php?s=Admin-Cache-Vodday");//清空当天缓存
					}
				}
			}else{
				header("Location: ".$collect_nexturl."");
			}
		}
	}
	
	//按分页采集
	public function ingbylist(){
	    $go=$_GET;
	    $listurl=F('_collects/cid_'.$go['cid']);
		$rule=F('_collects/cid_'.$go['cid'].'_rule');
		$this->assign($go);
		if(false!==$listurl&&false!==$rule){
		    if($go['sid']<$go['eid']){
				$listurl=trim($listurl[$go['sid']]);
				$html=getfile($listurl);
				if("utf-8"<>$rule['collect_encoding'])$html=g2u($html);
				//缩小列表范围
				if(!empty($rule['collect_listurlstr'])){
					$html=getvoddata($html,$rule['collect_listurlstr']);
				}
				//列表页采集图片
				if(!empty($rule['collect_listpicstr'])){
					$arraypic=getvodall(trim($html),$rule['collect_listpicstr']);
				}
				$array=getvodall(trim($html),$rule['collect_listlink']);
				if(1==$rule['collect_order']){$array=getreurl($array);$arraypic=getreurl($arraypic);}//倒序
				$this->assign("count",count($array));
				$i=1;
				foreach($array as $key=>$val){
					$this->assign($this->ingbymdb(getbaseurl($listurl,trim($val)),$rule,trim($arraypic[$key]),$go['tid']));
					$this->assign("listurl",$listurl);
					$this->assign("listi",$i);
					$i=$i+1;
					$this->display('./Public/admin/collectinglist.html');
				}
				$this->display('./Public/admin/collectinglistgo.html');
			}else{
				F('_collects/cid_'.$go['cid'],NULL);
				F('_collects/cid_'.$go['cid'].'_rule',NULL);
				$this->checknext($go['gid']);				
				$this->assign("jumpUrl",'index.php?s=Admin-Vod-Show-vod_cid-0');
				$this->success('恭喜你！所有采集任务成功完成！<br><br>点此查看一些相似的影片是否需要入库！');
			}
		}else{
			$this->error("没有相关采集数据！");
		}
	}	
	
	//按ID采集
	public function ingbyid(){
	    $go=$_GET;
	    $listurl=F('_collects/cid_'.$go['cid']);
		$rule=F('_collects/cid_'.$go['cid'].'_rule');
		$this->assign($go);
		if(false!==$listurl&&false!==$rule){
		    if($go['sid']<$go['eid']){
				$this->assign($this->ingbymdb(trim($listurl[$go['sid']]),$rule,'',$go['tid']));
				$this->display('./Public/admin/collectingid.html');
			}else{
				F('_collects/cid_'.$go['cid'],NULL);
				F('_collects/cid_'.$go['cid'].'_rule',NULL);
				$this->checknext($go['gid']);
				$this->assign("jumpUrl",'index.php?s=Admin-Vod-Show-vod_cid-0');
				$this->success('恭喜你！所有采集任务成功完成！<br><br>点此查看一些相似的影片是否需要入库！');
			}
		}else{
		    $this->error("没有相关采集数据！");
		}	
	}
	
	//处理采集内容并入库		
	public function ingbymdb($url,$rule,$listpic='',$tid=''){
	    $replace=F('_collects/cid_'.$rule['collect_id'].'_replace');//获取过滤规则
		$url=preg_replace($replace['all']['patterns'],$replace['all']['replaces'],$url);
	    $vod=array();
		$html=getfile($url);
		if(false!==$html){
		    if("utf-8"<>$rule['collect_encoding'])$html=g2u($html);
			$vod['vod_cid']=$rule['collect_cid'];
			if(0==$vod['vod_cid']){
				$vod['vod_cid']=preg_replace($replace['listname']['patterns'],$replace['listname']['replaces'],trim(getvoddata($html,$rule['collect_listname'])));
				$vod['vod_cid']=getlistid($vod['vod_cid']);
			}
		    $vod['vod_name']=trim(getvoddata($html,$rule['collect_name']));
			  $vod['vod_name']=preg_replace($replace['vodname']['patterns'],$replace['vodname']['replaces'],$vod['vod_name']);
		    $vod['vod_actor']=getvoddata($html,$rule['collect_actor']);
			  $vod['vod_actor']=preg_replace($replace['actor']['patterns'],$replace['actor']['replaces'],$vod['vod_actor']);
		    $vod['vod_director']=getvoddata($html,$rule['collect_director']);
			  $vod['vod_director']=preg_replace($replace['director']['patterns'],$replace['director']['replaces'],$vod['vod_director']);
		    $vod['vod_content']=getvoddata($html,$rule['collect_content']);
			  $vod['vod_content']=preg_replace($replace['content']['patterns'],$replace['content']['replaces'],$vod['vod_content']);
			  if(true==C('play_collect')){$vod['vod_content']=getrandstr($vod['vod_content']);}
			if($listpic){
			  $vod['vod_pic']=getbaseurl($url,$listpic);
			}else{
		    $vod['vod_pic']=getvoddata($html,$rule['collect_pic']);
			  $vod['vod_pic']=getbaseurl($url,str_replace($arr[0][0],$arr[0][1],$vod['vod_pic']));
			}
			  $vod['vod_pic']=preg_replace($replace['vodpic']['patterns'],$replace['vodpic']['replaces'],$vod['vod_pic']);
		    $vod['vod_area']=getvoddata($html,$rule['collect_area']);
			  $vod['vod_area']=preg_replace($replace['all']['patterns'],$replace['all']['replaces'],$vod['vod_area']);
		    $vod['vod_language']=getvoddata($html,$rule['collect_language']);
			  $vod['vod_language']=preg_replace($replace['all']['patterns'],$replace['all']['replaces'],$vod['vod_language']);
			$vod['vod_savepic']=$rule['collect_savepic'];
		    $vod['vod_year']=getvoddata($html,$rule['collect_year']);
		    $vod['vod_continu']=getvoddata($html,$rule['collect_continu']);
			  $vod['vod_continu']=preg_replace($replace['continu']['patterns'],$replace['continu']['replaces'],$vod['vod_continu']);if(empty($vod['vod_continu'])){$vod['vod_continu']=0;}
			$vod['vod_addtime']=time();
			$vod['vod_inputer']='collect'.$rule['collect_id'];
			$vod['vod_stars']=1;
			$vod['vod_letter']=getfirstchar($vod['vod_name']);
			$vod['vod_play']=$rule['collect_player'];
			$vod['vod_reurl']=$url;
			if(!empty($rule['collect_urlstr'])){//缩小地址范围
				$html=getvoddata($html,$rule['collect_urlstr']);
			}
			if(!empty($rule['collect_urlname'])){//分集名
			    $arrname=getvodall(trim($html),$rule['collect_urlname']);
			}
			$arrurl=getvodall(trim($html),$rule['collect_urllink']);
			$playurl='';
			foreach($arrurl as $key=>$val){
			    if(is_array($arrname)){$playname=$arrname[$key].'$';}else{$playname='';}
			    if(!empty($rule['collect_url'])){
					$val=preg_replace($replace['url']['patterns'],$replace['url']['replaces'],$val);
					$html=getfile(getbaseurl($url,$val));
					if("utf-8"<>$rule['collect_encoding'])$html=g2u($html);
					$playurl=$playurl.chr(13).$playname.trim(getvoddata($html,$rule['collect_url']));
				}else{
				    $playurl=$playurl.chr(13).$playname.trim($val);
				}
			}
		    $vod['vod_url']=trim(preg_replace($replace['url']['patterns'],$replace['url']['replaces'],$playurl));
			//处理是否写入数据
			if($tid){
				$vod['vod_state'] = '采集测试,数据不写入数据库!';
			}else{
				$cai = D('Cai');
				$vod['vod_state'] = $cai->xml_insert($vod,false);
			}
			return $vod;								
		}else{
			//$this->error('采集xx页面出现异常!');
		}		
	}
	
	//定时采集
	public function dingshi(){
	if(in_array(date("w"),$_POST['collect_week'])){
		if(in_array(date("H"),$_POST['collect_hour'])){
			$gid=implode(',',$_REQUEST['collect_id']);
			$gid='index.php?s=Admin-Collect-Ing-collect_id-'.$_REQUEST['collect_id'][0].'-gid-1,'.$gid;
		}
	}
	$this->assign('gid',$gid);
	$this->display('./Public/admin/collect.html');
	}
	
	// 写入替换规则缓存
    public function f_replace($rearr,$collectid){
		foreach($rearr as $i=>$v1){
		    $arr1=array();$arr2=array();
			foreach(explode(chr(13),trim($v1)) as $j=>$v){
				list($key,$val)=explode('$$$',trim($v));
				$arr1[$j]='/'.trim(stripslashes($key)).'/si';
				$arr2[$j]=trim($val);
			}
			$arr[$i]=array('patterns'=>$arr1,'replaces'=>$arr2);
		}				
		F('_collects/cid_'.$collectid.'_replace',$arr);
		return $arr;
		/*foreach($array as $i=>$val){
			foreach (explode(chr(13),$val) as $key=>$v){
			    list($key,$val)=explode('$$$',trim($v));
			    $arr[$i][trim($key)]=trim($val);
			}
		}*/		   
	}
	
	//规则写完后测试是否正确
    public function ingtest($cid,$type){
	    $listurl=F('_collects/cid_'.$cid);
		$rule=F('_collects/cid_'.$cid.'_rule');
		end($listurl);
		$testurl=trim($listurl[key($listurl)]);
	    if($type==3||$type==4){
		    $test=$this->ingbymdb($testurl,$rule,'',2);
		}else{
			$html=getfile($testurl);
			if("utf-8"<>$rule['collect_encoding'])$html=g2u($html);		
			if(!empty($rule['collect_listurlstr'])){$html=getvoddata($html,$rule['collect_listurlstr']);}
			if(!empty($rule['collect_listpicstr'])){$arraypic=getvodall(trim($html),$rule['collect_listpicstr']);}
			$array=getvodall(trim($html),$rule['collect_listlink']);
			if(1==$rule['collect_order']){$array=getreurl($array);$arraypic=getreurl($arraypic);}
			end($array);
			$urlid=getbaseurl($testurl,trim($array[key($array)]));
			end($arraypic);
			$urlpic=trim($arraypic[key($arraypic)]);
			$test=$this->ingbymdb($urlid,$rule,$urlpic,2);
		}
		F('_collects/cid_'.$go['cid'],NULL);
		F('_collects/cid_'.$go['cid'].'_rule',NULL);		
		$this->assign($test);
		$this->display('./Public/admin/collectingtest.html');
		exit();
	}	
	// 采集导出
    public function export(){
	    $rs=D("Admin.Collect");
		$list=$rs->order('collect_id desc')->select();
		$this->assign('list',$list);
        $this->display('./Public/admin/collectmain.html');
    }		
	//执行采集导出
    public function exportsql(){
	    if(!is_array($_POST['id'])){$this->error("请选择要导出的规则！");}
	    $where['collect_id']=array('in',$_POST['id']);;
	    $rs=D("Admin.Collect");
		$list=$rs->where($where)->order('collect_id desc')->select();	
		F('_collects/ppvod_collect',$list);
        $this->success("恭喜您！您选择的采集规则已经完整导出！");
    }			
	// 采集导入
    public function import(){
		$list=F('_collects/ppvod_collect');
		if(!is_array($list)){$this->error("没有找到采集规则文件Runtime/Data/_collects/ppvod_collect.php！");}
		$this->assign('list',$list);
        $this->display('./Public/admin/collectmain.html');
    }	
	//执行采集导入
    public function importsql(){
	    $id=$_POST['id'];
		if(!is_array($id)){$this->error("请选择要导入的规则！");}
		$list=F('_collects/ppvod_collect');
		$rs=D("Admin.Collect");
		$replace=array('all'=>'','listname'=>'','vodname'=>'','actor'=>'','director'=>'','content'=>'','vodpic'=>'','continu'=>'','url'=>'');
		foreach($list as $key=>$val){
		    if(in_array($val['collect_id'],$id)){
				unset($val['collect_id']);
				$cid=$rs->data($val)->add();
				$abc=explode('|||',$val['collect_replace']);
				$replace=array();
				$replace['all']=$abc[0];
				$replace['listname']=$abc[1];
				$replace['vodname']=$abc[2];
				$replace['actor']=$abc[3];
				$replace['director']=$abc[4];
				$replace['content']=$abc[5];
				$replace['vodpic']=$abc[6];
				$replace['continu']=$abc[7];
				$replace['url']=$abc[8];
				$this->f_replace($replace,$cid);
			};
		}
		$this->assign("jumpUrl",'index.php?s=Admin-Collect-Show');
		$this->success("恭喜您！您选择的采集规则已经成功导入！");
    }				
}
?>