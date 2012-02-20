<?php
class NewsAction extends HomeAction{
    //新闻列表
    public function show(){
		$cid=$_GET['id'];
		$list=list_search(F('_ppvod/list'),'list_id='.$cid);$list[0]["list_page"]=!empty($_GET['p'])?$_GET['p']:1;
		$this->assign($list[0]);
		$this->assign('pplist',A("Home.News"));
		$this->assign('title',$list[0]['list_name'].'第'.$list[0]['list_page'].'页-'.C('site_name').C('site_by'));		
		$skin=$list[0]['list_skin'];if(empty($skin)){$skin='pp_newslist';};			
		$this->display($skin);
    }
	//搜索新闻列表
    public function search(){
	    $url=$_REQUEST;unset($url['a']);unset($url['m']);unset($url['g']);unset($url['p']);
		$oid='addtime';if(!empty($url['o'])){$oid=$url['o'];}
		$zid=C('url_num_vod');if(!empty($url['z'])){$zid=$url['z'];}
		$nowPage=!empty($_GET['p'])?$_GET['p']:1;
		$keyword=trim($url['id']);
		$keytype=trim($url['x']);
		$search=array();
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
			//$search['_logic'] = 'or';
		}
		$keyword=trim($_REQUEST['id']);
		$this->assign('keyword',$keyword);
		$this->assign('pplist',A("Home.News"));
		$this->assign('title',C('site_name').$keyword.C('site_by'));
		$this->display('pp_newssearch');
    }		
	//新闻内容
    public function read(){
		$vid = intval($_GET['id']);
		$pid = $_GET['p']?intval($_GET['p']):1;
		$rs=D("Home.News");$arr=$rs->where('news_del=0 and news_id='.$vid)->find();//$rs->find($vid);
		if($arr){
			Vendor('News.Page');
			$CP=new cutpage();
			$CP->pagestr=$arr['news_content'];
			$CP->cut_custom="{nextpage}";//手动分页标签
			$CP->page_word=5000;
			$CP->cut_tag=array("</table>", "</div>", "</p>", "<br/>", "</img>", "”。", "。", ".", "！", "……", "？", ",");//自动分页截取尾部的标记
			$CP->ipage=$pid;
			$CP->url=ppvodurl('news/read',array('id'=>$vid)).'-p-{!page!}'.C('url_html_suffix');
			$CP->cut_str();
			$arr['news_content']=$CP->pagearr[$pid-1];//分页后的内容
			$list=list_search(F('_ppvod/list'),'list_id='.$arr['news_cid']);
			$this->assign($list[0]);
			$this->assign($arr);
			$this->assign('page',$CP->show_prv_next());
			$this->assign('pplist',A("Home.News"));
			$this->assign('title',$arr['news_name'].'-'.C('site_name').C('site_by'));
			$this->display('pp_news');
		}else{
			$this->error('暂无数据，请谅解！');
		}
    }
	//ajax顶踩
    public function ajax(){
		$id=$_GET['id'];$tid=$_GET['t'];
		if($id){
		    if($tid>0&&Cookie::is_set('newsud-'.$id)){
			    exit('0');
			}
			$rs=D("Home.News");
			if(1==$tid){
			    $rs->setInc('news_up','news_id='.$id);
			}elseif(2==$tid){
			    $rs->setInc('news_down','news_id='.$id);
			};
			if($tid>0){
				C('COOKIE_EXPIRE',60*60*24);
				Cookie::set('newsud-'.$id,'ok');
			}
			$field[]='news_up';$field[]='news_down';
			$arr=$rs->field($field)->find($id);
			echo($arr['news_up'].':'.$arr['news_down']);
		}
    }
	//ajax调用浏览数
    public function ajaxhot(){
		$id=$_GET['id'];
		if($id){
		    $rs=D("Home.News");
			$rs->setInc('news_hits','news_id='.$id);
		    $arr=$rs->field('news_hits')->find($id);
			echo("document.write('".$arr['news_hits']."');");
		}
    }
	//ajax评分
    public function gold(){
		$id = intval($_GET['id']);$tid=$_GET['t'];
		$rs=D("Home.News");
		$arr=$rs->field('news_gold,news_golder')->find($id);
		if($arr){
			if($tid){
				if(Cookie::is_set('newsgold-'.$id)){exit('0');}
				$array['news_gold']=number_format(($arr['news_gold']*$arr['news_golder']+$tid)/($arr['news_golder']+1),1);
				$array['news_golder']=$arr['news_golder']+1;
				$rs->where('news_id='.$id)->setField(array('news_gold','news_golder'),array($array['news_gold'],$array['news_golder']));
				C('COOKIE_EXPIRE',60*60*24);Cookie::set('newsgold-'.$id,'ok');
			}else{
				$array=$arr;
			}
		}else{
			$array['news_gold']=0;$array['news_golder']=0;
		}
		echo($array['news_gold'].':'.$array['news_golder']);
    }	
	//js自定义调用
    public function script(){
		$id=trim($_GET['id']);
		$this->assign('pplist',A("Home.Vod"));
		$this->display('pp_'.$id);
	}
	//iframe自定义调用
    public function iframe(){
		$id=trim($_GET['id']);
		$this->assign('pplist',A("Home.Vod"));
		$this->display('pp_'.$id);
	}								
}
?>