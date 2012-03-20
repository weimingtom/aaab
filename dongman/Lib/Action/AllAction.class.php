<?php
class AllAction extends Action{
	//解析影视模块
	public function ppvod($str=''){
		$param=array();$array=explode(';',$str);foreach ($array as $v){list($key,$val)=explode(':',trim($v));$param[trim($key)]=trim($val);}//生成参数列表
		if($param['pid']&&false==$param['cid']) {
			$tree=list_search(F('_ppvod/listvod'),'list_id='.$param['pid']);
			if(!empty($tree[0]['son'])){
				foreach($tree[0]['son'] as $val){$param['cid'][]=$val['list_id'];}
			}else{
				$param['cid']=$param['pid'];
			}
		}
		$where=array();$where['vod_del']=0;//根据参数生成查询条件
		if($param['cid']){$where['vod_cid']=ids($param['cid']);}else{$where['vod_cid']=array('gt',0);}
		if($param['stars']){$where['vod_stars']=ids($param['stars']);}
		if($param['hits']){$where['vod_hits']=array('GT',$param['hits']);}
		if($param['lz']==1){$where['vod_continu']=array('neq','0');}elseif($param['lz']==2){$where['vod_continu']=0;}//if($param['lz']){$where['vod_continu']=array('GT',0);}
		if($param['letter']){$where['vod_letter']=array('eq',$param['letter']);}
		if($param['day']){$time=time()-$param['day']*3600*24;$where['vod_addtime']= array('GT',$time);}
		if($param['order']){$order=$param['order'];}else{$order='vod_addtime desc';}
		if($param['num']){$limit=$param['num'];}else{$limit='20';}
		$url=$_REQUEST;//获取地址栏要传递的参数
		if($param['page']){
			$currentPage=!empty($url['p'])?$url['p']:1;//当前分页数
			if(!empty($url['o'])){$order='vod_'.$url['o'].' desc';};//多种排序方式,分页调用时有效
		};
		unset($url['a']);unset($url['m']);unset($url['g']);unset($url['p']);unset($url['Submit']);unset($url['__hash__']);unset($url['__ppvod__']);//去除多余的参数列表
		if('search'==$param['action']){
			if($param['keyword']){$url['id']=$param['keyword'];}//调用相关指定关键词的数据
			$keyword=trim($url['id']);$keytype=trim($url['x']);$search=array();
			if('letter'==$keytype){
				$search['vod_letter']=array('eq',$keyword);
			}elseif('year'==$keytype){
				$search['vod_year']=array('eq',$keyword);
			}elseif('name'==$keytype){
				$search['vod_name']=array('like','%'.$keyword.'%');
			}elseif('title'==$keytype){
				$search['vod_title']=array('like','%'.$keyword.'%');
			}elseif('actor'==$keytype){
				$search['vod_actor']=array('like','%'.$keyword.'%');
			}elseif('director'==$keytype){
				$search['vod_director']=array('like','%'.$keyword.'%');
			}elseif('area'==$keytype){
				$search['vod_area']=array('like','%'.$keyword.'%');
			}elseif('content'==$keytype){
				$search['vod_content']=array('like','%'.$keyword.'%');
			}elseif('lang'==$keytype){
				$search['vod_language']=array('like','%'.$keyword.'%');
			}elseif('keywords' == $keytype){
				$search['vod_keywords'] = array('like', '%'.$keyword.'%');
			}else{
				$search['vod_name']=array('like','%'.$keyword.'%');
				$search['vod_title']=array('like','%'.$keyword.'%');
				$search['vod_actor']=array('like','%'.$keyword.'%');
				$search['vod_director']=array('like','%'.$keyword.'%');
				//$search['vod_area']=array('like','%'.$keyword.'%');$search['vod_letter']=array('eq',$keyword);$search['vod_year']=array('eq',$keyword);
			}
			$map=$search;
			//print_r($map);
			$map['_logic'] = 'or';
			$where['_complex']=$map;
			$sql=ppvodsql('vod','*',$where,$order,$limit,$currentPage,$join);$arr=$sql['list'];//查询
			if($param['page']){
				$url['id']=urlencode($url['id']);//关键字编码传递
				$urlPage=ppvodurl('vod/search',$url).'-p-{!page!}'.C('url_html_suffix');
				$totalPages=ceil($sql['count']/$limit);if(C('home_pagego')){$pagego=''.C('home_pagego').'(\''.$urlPage.'\','.$totalPages.')';}
				$showPage.='共'.$sql['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$urlPage,$pagego);
				$arr[0]['page']=$showPage;$arr[0]['count']=$sql['count'];
			};
		}elseif('tag'==$param['action']){
			if($param['keyword']){$url['id']=$param['keyword'];}//调用相关指定标签的数据
			$rs=D('Home.Vod');
			$where['tag_name']=trim($url['id']);
			$where['tag_sid']=!empty($url['sid'])?$url['sid']:1;
			$join=C('db_prefix').'tag on '.C('db_prefix').'vod.vod_id = '.C('db_prefix').'tag.tag_id';
			$sql['count']=$rs->join($join)->where($where)->count('vod_id');
			$arr=$rs->join($join)->where($where)->order($order)->limit($limit)->page($currentPage)->select();//查询
			if($param['page']){
				$url['id']=urlencode($url['id']);//关键字编码传递
				$urlPage=ppvodurl('tag/show',$url).'-p-{!page!}'.C('url_html_suffix');
				$totalPages=ceil($sql['count']/$limit);if(C('home_pagego')){$pagego=''.C('home_pagego').'(\''.$urlPage.'\','.$totalPages.')';}
				$showPage.='共'.$sql['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$urlPage,$pagego);
				$arr[0]['page']=$showPage;$arr[0]['count']=$sql['count'];
			};
		}else{
			if($param['page']){
				if(C('html_vod')){//后台html生成
					$currentPage=C('html_page');
					$urlPage=str_replace('index'.C('html_file_suffix'),'',C('site_path').C('url_vodlist').getlisturl(C('html_cid'),'{!page!}').C('html_file_suffix'));
				}else{
					$model_name=strtolower(MODULE_NAME);$action_name=strtolower(ACTION_NAME);
					$urlPage=ppvodurl($model_name.'/'.$action_name,$url).'-p-{!page!}'.C('url_html_suffix');
				};
				$sql=ppvodsql('vod','*',$where,$order,$limit,$currentPage,$join);$arr=$sql['list'];//查询
				$totalPages=ceil($sql['count']/$limit);if(C('home_pagego')){$pagego=''.C('home_pagego').'(\''.$urlPage.'\','.$totalPages.')';}
				$showPage.='共'.$sql['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$urlPage,$pagego);
				$arr[0]['page']=$showPage;$arr[0]['count']=$sql['count'];
			}else{
				$sql=ppvodsql('vod','*',$where,$order,$limit,$currentPage,$join);$arr=$sql['list'];
			};
		}
		foreach($arr as $key=>$val){
			$arr[$key]['list_id']=$arr[$key]['vod_cid'];
			$arr[$key]['list_name']=getlistname($arr[$key]['list_id'],'list_name');
			$arr[$key]['list_url']=getlistname($arr[$key]['list_id'],'list_url');
			$arr[$key]['vod_playurl']=getplayurl($arr[$key]['vod_id']);
			if(C('url_html')>0){
				$vodurl=C('site_path').getdataurl($arr[$key]['vod_id'],$arr[$key]['vod_cid'],$arr[$key]['vod_name']).c('html_home_suffix');
				$arr[$key]['vod_url']=str_replace('index'.c('html_home_suffix'),'',$vodurl);//去除index.html
			}else{
				$url['id']=$arr[$key]['vod_id'];unset($url['o']);unset($url['x']);unset($url['sid']);
				$arr[$key]['vod_url']=ppvodurl('Home-vod/read',$url,'index.php',false,true);
			}
		}
		return $arr;
	}
	//解析新闻模块
	public function ppnews($str=''){
		$param=array();$array=explode(';',$str);foreach ($array as $v){list($key,$val)=explode(':',trim($v));$param[trim($key)]=trim($val);}//生成参数列表
		if($param['pid']&&false==$param['cid']) {
			$tree=list_search(F('_ppvod/listnews'),'list_id='.$param['pid']);
			if(!empty($tree[0]['son'])){
				foreach($tree[0]['son'] as $val){$param['cid'][]=$val['list_id'];}
			}else{
				$param['cid']=$param['pid'];
			}
		}
		$where=array();$where['news_del']=0;//根据参数生成查询条件
		if($param['cid']){$where['news_cid']=ids($param['cid']);}
		if($param['stars']){$where['news_stars']=ids($param['stars']);}
		if($param['hits']){$where['news_hits']=array('GT',$param['hits']);}
		if($param['letter']){$where['news_letter']=array('eq',$param['letter']);}
		if($param['day']){$time=time()-$param['day']*3600*24;$where['news_addtime']= array('GT',$time);}
		if($param['order']){$order=$param['order'];}else{$order='news_addtime desc';}
		if($param['num']){$limit=$param['num'];}else{$limit='20';}
		$url=$_REQUEST;//获取地址栏要传递的参数
		if($param['page']){
			$currentPage=!empty($url['p'])?$url['p']:1;//当前分页数
			if(!empty($url['o'])){$order='news_'.$url['o'].' desc';};//多种排序方式,分页调用时有效
		};
		unset($url['a']);unset($url['m']);unset($url['g']);unset($url['p']);unset($url['Submit']);unset($url['__hash__']);unset($url['__ppvod__']);//去除多余的参数列表
		if('search'==$param['action']){
			if($param['keyword']){$url['id']=$param['keyword'];}//调用指定键词的数据
			$keyword=trim($url['id']);$keytype=trim($url['x']);$search=array();
			if('letter'==$keytype){
				$search['news_letter']=array('eq',$keyword);
			}elseif('name'==$keytype){
				$search['news_name']=array('like','%'.$keyword.'%');
			}elseif('content'==$keytype){
				$search['news_content']=array('like','%'.$keyword.'%');
			}else{
				$search['news_letter']=array('eq',$keyword);
				$search['news_name']=array('like','%'.$keyword.'%');
				$search['news_content']=array('like','%'.$keyword.'%');
			}
			$map=$search;
			$map['_logic'] = 'or';
			$where['_complex']=$map;
			$sql=ppvodsql('news','*',$where,$order,$limit,$currentPage);$arr=$sql['list'];
			if($param['page']){
				$url['id']=urlencode($url['id']);//关键字编码传递
				$urlPage=ppvodurl('news/search',$url).'-p-{!page!}'.C('url_html_suffix');
				$totalPages=ceil($sql['count']/$limit);if(C('home_pagego')){$pagego=''.C('home_pagego').'(\''.$urlPage.'\','.$totalPages.')';}
				$showPage.='共'.$sql['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$urlPage,$pagego);
				$arr[0]['page']=$showPage;$arr[0]['count']=$sql['count'];
			};
		}elseif('tag'==$param['action']){
			if($param['keyword']){$url['id']=$param['keyword'];}//调用指定键词的数据
			$rs=D('Home.News');
			$where['tag_name']=trim($url['id']);
			$where['tag_sid']=!empty($url['sid'])?$url['sid']:2;
			$join=C('db_prefix').'tag on '.C('db_prefix').'news.news_id = '.C('db_prefix').'tag.tag_id';
			$sql['count']=$rs->join($join)->where($where)->count('vod_id');
			$arr=$rs->join($join)->where($where)->order($order)->limit($limit)->page($currentPage)->select();
			if($param['page']){
				$url['id']=urlencode($url['id']);//关键字编码传递
				$urlPage=ppvodurl('tag/show',$url).'-p-{!page!}'.C('url_html_suffix');
				$totalPages=ceil($sql['count']/$limit);if(C('home_pagego')){$pagego=''.C('home_pagego').'(\''.$urlPage.'\','.$totalPages.')';}
				$showPage.='共'.$sql['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$urlPage,$pagego);
				$arr[0]['page']=$showPage;$arr[0]['count']=$sql['count'];
			};
		}else{
			if($param['page']){
				if(C('html_news')){//后台html生成
					$currentPage=C('html_page');
					$urlPage=str_replace('index'.C('html_file_suffix'),'',C('site_path').C('url_newslist').getlisturl(C('html_cid'),'{!page!}').C('html_file_suffix'));
				}else{
					$model_name=strtolower(MODULE_NAME);$action_name=strtolower(ACTION_NAME);
					$urlPage=ppvodurl($model_name.'/'.$action_name,$url).'-p-{!page!}'.C('url_html_suffix');
				};
				$sql=ppvodsql('news','*',$where,$order,$limit,$currentPage);$arr=$sql['list'];
				$totalPages=ceil($sql['count']/$limit);if(C('home_pagego')){$pagego=''.C('home_pagego').'(\''.$urlPage.'\','.$totalPages.')';}
				$showPage.='共'.$sql['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$urlPage,$pagego);
				$arr[0]['page']=$showPage;$arr[0]['count']=$sql['count'];
			}else{
				$sql=ppvodsql('news','*',$where,$order,$limit,$currentPage);$arr=$sql['list'];
			}
		}
		foreach($arr as $key=>$val){
			$arr[$key]['list_id']=$arr[$key]['news_cid'];
			$arr[$key]['list_name']=getlistname($arr[$key]['list_id'],'list_name');
			$arr[$key]['list_url']=getlistname($arr[$key]['list_id'],'list_url');
			if(C('url_html')>0){
				$newsurl=C('site_path').C('url_newsdata').getdataurl_p($arr[$key]['news_id'],1).C('html_home_suffix');
				$arr[$key]['news_url']=str_replace('index'.C('html_home_suffix'),'',$newsurl);//去除index.html
			}else{
				$url['id']=$arr[$key]['news_id'];unset($url['o']);unset($url['x']);unset($url['sid']);
				$arr[$key]['news_url']=ppvodurl('Home-news/read',$url,'index.php',false,true);
			}
		}
		return $arr;
	}
	//解析Tag模块
	public function pptag($str=''){
		$param=array();$where=array();
		$array=explode(';',$str);foreach ($array as $v){list($key,$val)=explode(':',trim($v));$param[trim($key)]=trim($val);}
		if($param['id']){$where['tag_id']=ids($param['id']);}
		if($param['sid']){$where['tag_sid']=$param['sid'];}
		if($param['num']){$limit=$param['num'];}
		if(C('url_html')==1){$index='index.html';}else{$index='index.php';}
		$rs=D("Home.Tag");
		$array=$rs->field('*,count(tag_name) as tag_count')->where($where)->limit($limit)->group('tag_name,tag_sid')->order('tag_count desc')->select();
		foreach($array as $key=>$val){
			$array[$key]['tag_url']=ppvodurl('tag/show',array('id'=>urlencode($array[$key]['tag_name']),'sid'=>$array[$key]['tag_sid']),$index,false,true);
		}
		return $array;
	}
}
?>