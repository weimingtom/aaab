<?php
class VodAction extends HomeAction{
	//影视列表
	public function show(){
		$cid=$_GET['id'];
		$list=list_search(F('_ppvod/list'),'list_id='.$cid);$list[0]["list_page"]=!empty($_GET['p'])?$_GET['p']:1;
		$this->assign($list[0]);
		$this->assign('pplist',A("Home.Vod"));
		$this->assign('title',$list[0]['list_name'].'第'.$list[0]['list_page'].'页-'.C('site_name').C('site_by'));
		$skin=$list[0]['list_skin'];if(empty($skin)){$skin='pp_vodlist';}
		$this->display($skin);
	}
	//搜索影视列表
	public function search(){
		$keyword=trim($_REQUEST['id']);
		$this->assign('keyword',$keyword);
		$this->assign('pplist',A("Home.Vod"));
		$this->assign('title',$keyword.'-的搜索结果列表'.C('site_by'));
		$this->display('pp_vodsearch');
	}
	//读取影片内容
	public function read(){
		$get = $_GET;$url['id'] = intval($get['id']);$url['sid']='';$url['pid']='';//根据地址栏参数组合播放页链接参数
		if(!empty($get['l'])){$url['l']=$get['l'];}if(!empty($get['t'])){$url['t']=$get['t'];}//语言与模板自定义
		$rs=D("Home.Vod");
		$arr=$rs->where('vod_del=0 and vod_id='.$url['id'])->find();
		if($arr){
			$arr['vod_count']=count($vodurl);
			$play = explode('$$$',$arr['vod_play']);
			$server = explode('$$$',$arr['vod_server']);
			$vodurl = explode('$$$',$arr['vod_url']);
			$serverurl = C('play_server');
			$playerlist = C('play_player');
			foreach($play as $sid=>$val){
				$ppplay[$sid] = array('servername'=>$playerlist[$val],'playername'=>$playerlist[$val],'playname'=>$val,'serverurl'=>$serverurl[$server[$sid]],'son'=>$this->playlist($vodurl[$sid],$sid,$url));
			}
			$list=list_search(F('_ppvod/list'),'list_id='.$arr['vod_cid']);
			$this->assign($list[0]);
			$this->assign($arr);
			$this->assign('ppplay',$ppplay);
			$this->assign('pplist',A("Home.Vod"));
			$this->assign('title',$arr['vod_name'].'-'.C('site_name').C('site_by'));
			if(!empty($arr['vod_skin'])){
				$this->display($arr['vod_skin']);
			}else{
				$this->display('pp_vod');
			}
		}else{
			$this->assign("jumpUrl",C('site_path'));
			$this->error('暂无数据，请选择观看其它节目！');
		}
	}
	//分解播放地址链接
	public function playlist($vodurl,$sid,$url){
		$play=explode(chr(13),str_replace(array("\r\n", "\n", "\r"),chr(13),$vodurl));
		//krsort($play,SORT_NUMERIC );
		//array_multisort($play,SORT_ASC);
		//print_r($play);
		foreach($play as $key=>$val){
			$url['sid']=$sid;$url['pid']=$key;
			if(strpos($val,'$')>0){
				$ji=explode('$',$val);
				$list['playname']=trim($ji[0]);
				$list['playpath']=trim($ji[1]);
			}else{
				$list['playname']='第'.($key+1).'集';
				$list['playpath']=trim($val);
			}
			$list['playurl']=ppvodurl('Home-vod/play',$url,'index.php',false,true);
			$list['playcount']=count($play);
			$urllist[]=$list;
		}
		return $urllist;
	}
	//解析播放页
	public function play(){
		$get = $_GET;$url['id'] = intval($get['id']);$url['sid']='';$url['pid']='';//根据地址栏参数组合播放页链接参数
		if(!empty($get['l'])){$url['l']=$get['l'];}if(!empty($get['t'])){$url['t']=$get['t'];}//语言与模板自定义
		$rs=D("Home.Vod");
		$arr=$rs->where('vod_del=0 and vod_id='.$url['id'])->find();
		if(!$arr){$this->error('暂无数据，请选择观看其它节目！');}
		$play=explode('$$$',$arr['vod_play']);
		$server=explode('$$$',$arr['vod_server']);
		$vodurl=explode('$$$',$arr['vod_url']);unset($arr['vod_url']);
		$serverurl=c('play_server');
		foreach($play as $sid=>$val){
			$servername=array_search($play[$sid],C('play_player'));
			$ppplay[$sid]=array('playname'=>$val,'serverurl'=>$serverurl[$server[$sid]],'servername'=>$servername,'son'=>$this->playlist($vodurl[$sid],$sid,$url));
		}
		//播放页专用标签
		$arr['vod_number']=$pid+1;
		$arr['vod_url']=str_replace('index.php?s=/','?s=',U('vod/read?id='.$vid));
		$arr['vod_count']=count($vodurl);
		$arr['vod_ppplay']=$ppplay;unset($ppplay);
		$sid=$get['sid'];$pid=$get['pid'];
		$arr['vod_playname']=$arr['vod_ppplay'][$sid]['son'][$pid]['playname'];
		$arr['vod_playpath']=$arr['vod_ppplay'][$sid]['son'][$pid]['playpath'];
		$list=list_search(F('_ppvod/list'),'list_id='.$arr['vod_cid']);
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
		//$rs->setInc('vod_hits','vod_id='.$id);//更新点击数
		
		
//播放页底部播放器开始		
		$get = $_GET;$url['id'] = intval($get['id']);$url['sid']='';$url['pid']='';//根据地址栏参数组合播放页链接参数
		if(!empty($get['l'])){$url['l']=$get['l'];}if(!empty($get['t'])){$url['t']=$get['t'];}//语言与模板自定义
		$rs=D("Home.Vod");
		$arrs=$rs->where('vod_del=0 and vod_id='.$url['id'].'  ')->find();
		if($arrs){
			$arrs['vod_count']=count($vodurl);
			$play = explode('$$$',$arrs['vod_play']);
			$server = explode('$$$',$arrs['vod_server']);
			$vodurl = explode('$$$',$arrs['vod_url']);
			$serverurl = C('play_server');
			$playerlist = C('play_player');
			foreach($play as $sid=>$val){
				$ppplay[$sid] = array('servername'=>$playerlist[$val],'playername'=>$playerlist[$val],'playname'=>$val,'serverurl'=>$serverurl[$server[$sid]],'son'=>$this->playlist($vodurl[$sid],$sid,$url));
			}
			$list=list_search(F('_ppvod/list'),'list_id='.$arrs['vod_cid']);
		}
			$this->assign('ppplays',$ppplay);
//播放页底部播放器结束		
		$this->assign($list[0]);
		$this->assign($arr);
		$this->assign('ppplay',$arr['vod_ppplay']);
		$this->assign('pplist',A("Home.Vod"));
		$this->assign('title',$arr['vod_name'].'-'.C('site_name').C('site_by'));
		$this->display('pp_play');
	}
	//ajax顶踩
	public function ajax(){
		$id=$_GET['id'];
		$tid=$_GET['t'];
		if($id){
			if($tid>0&&Cookie::is_set('vodud-'.$id)){
				exit('0');
			}
			$rs=D("Home.Vod");
			if(1==$tid){
				$rs->setInc('vod_up','vod_id='.$id);
			}elseif(2==$tid){
				$rs->setInc('vod_down','vod_id='.$id);
			}
			if($tid>0){
				C('COOKIE_EXPIRE',60*60*24);
				Cookie::set('vodud-'.$id,'ok');
			}
			$field[]='vod_up';$field[]='vod_down';
			$arr=$rs->field($field)->find($id);
			echo($arr['vod_up'].':'.$arr['vod_down']);
		}
	}
	//ajax调用浏览数
	public function ajaxhot(){
		$id=$_GET['id'];$tid=$_GET['t'];
		if($id){
			$rs=D("Home.Vod");
			if($tid>0){
				$rs->setInc('vod_hits','vod_id='.$id);
			}
			if($tid<2){
				$arr=$rs->field('vod_hits')->find($id);
				echo("document.write('".$arr['vod_hits']."');");
			}
		}
	}
	//ajax评分
	public function gold(){
		$id = intval($_GET['id']);$tid=$_GET['t'];
		$rs=D("Home.Vod");
		$arr=$rs->field('vod_gold,vod_golder')->find($id);
		if($arr){
			if($tid){
				if(Cookie::is_set('vodgold-'.$id)){exit('0');}
				$array['vod_gold']=number_format(($arr['vod_gold']*$arr['vod_golder']+$tid)/($arr['vod_golder']+1),1);
				$array['vod_golder']=$arr['vod_golder']+1;
				$rs->where('vod_id='.$id)->setField(array('vod_gold','vod_golder'),array($array['vod_gold'],$array['vod_golder']));
				C('COOKIE_EXPIRE',60*60*24);Cookie::set('vodgold-'.$id,'ok');
			}else{
				$array=$arr;
			}
		}else{
			$array['vod_gold']=0;$array['vod_golder']=0;
		}
		echo($array['vod_gold'].':'.$array['vod_golder']);
	}
	//视频模块js自定义调用
	public function script(){
		$id=trim($_GET['id']);
		$this->assign('pplist',A("Home.Vod"));
		$this->display('pp_'.$id);
	}
	//视频模块js自定义调用
	public function iframe(){
		$id=trim($_GET['id']);
		$this->assign('pplist',A("Home.Vod"));
		$this->display('pp_'.$id);
	}
}
?>