<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends BaseAction{
    public function index(){
        $this->display('./Public/admin/index.html');
    }
    public function top(){
        $this->display('./Public/admin/top.html');
    }
    public function left(){
        $this->display('./Public/admin/left.html');
    }
    public function main(){
		$rs = D("Vod");
		$img = array();
		$imghttp=$rs->where('Left(vod_pic,7)="http://" and vod_del=0')->count();
		$imgfail=$rs->where('Left(vod_pic,8)="httpf://" and vod_del=0')->count();
		$this->assign('imghttp',$imghttp);$this->assign('imgfail',$imgfail);
        $this->display('./Public/admin/main.html');
    }
	
	// 配置信息
    public function config(){
		$tpl=TMPL_PATH.'*';$list=glob($tpl);
		foreach ($list as $i=>$file){
			$dir[$i]['filename']=basename($file);
		}
		$this->assign('dir',$dir);
	    $this->ppvod_list();//更新导航菜单
		$config=require './Conf/setting.php';
		$this->assign($config);
        $this->display('./Public/admin/config.html');
    }
	
	// 配置信息保存
    public function configsave(){
		$config=$_POST["config"];
		$config['site_tongji']=stripslashes($config['site_tongji']);
		$config['play_collect_content']=stripslashes($config['play_collect_content']);
		$config['admin_time_edit']=(bool) $config['admin_time_edit'];
		$config['upload_thumb']=(bool) $config['upload_thumb'];
		$config['upload_water']=(bool) $config['upload_water'];
		$config['upload_http']=(bool) $config['upload_http'];
		$config['upload_ftp']=(bool) $config['upload_ftp'];
		$config['play_collect']=(bool) $config['play_collect'];
		$config['tmpl_cache_on']=(bool) $config['tmpl_cache_on'];
		$config['html_cache_on']=(bool) $config['html_cache_on'];
		$config['user_gbcm']=(bool) $config['user_gbcm'];	
		$config['play_second']=intval($config['play_second']);
		//$arr1 = array('\"',"\'");
		//$arr2 = array('','');
		//$config=str_replace($arr1,$arr2,$config);
		foreach(explode(chr(13),trim($config["play_server"])) as $v){
			list($key,$val)=explode('$$$',trim($v));
			$arrserver[trim($key)]=trim($val);
		}
		$config["play_server"]=$arrserver;
		//
		foreach(explode(chr(13),trim($config["play_list"])) as $v){
			list($key,$val)=explode('$$$',trim($v));
			$arrlist[trim($key)]=trim($val);
		}
		$config["play_list"]=$arrlist;
		//	
		foreach(explode(chr(13),trim($config["play_collect_content"])) as $v){
			$arrcollect[]=trim($v);
		}
		$config["play_collect_content"]=$arrcollect;
		//静态缓存
		$config['html_cache_time']=$config['html_cache_time']*3600;//其它页缓存
		if($config['html_cache_index']>0){$config['_htmls_']['home:index:index']=array('{:action}',$config['html_cache_index']*3600);}else{$config['_htmls_']['home:index:index']=NULL;}
		if($config['html_cache_list']>0){
		    $config['_htmls_']['home:vod:show']=array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
			$config['_htmls_']['home:news:show']=array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
		}else{
		    $config['_htmls_']['home:vod:show']=NULL;
			$config['_htmls_']['home:news:show']=NULL;
		}
		if($config['html_cache_content']>0){
		    $config['_htmls_']['home:vod:read']=array('{:module}_{:action}/{id|md5}',$config['html_cache_content']*3600);
			$config['_htmls_']['home:news:read']=array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_content']*3600);
		}else{
		    $config['_htmls_']['home:vod:read']=NULL;
			$config['_htmls_']['home:news:read']=NULL;
		}
		if($config['html_cache_play']>0){
		    $config['_htmls_']['home:vod:play']=array('{:module}_{:action}/{id|md5}',$config['html_cache_play']*3600);
		}else{
		    $config['_htmls_']['home:vod:play']=NULL;
		}						
		if($config['html_cache_ajax']>0){
		    $config['_htmls_']['home:ajax:show']=array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_ajax']*3600);
		}else{
		    $config['_htmls_']['home:ajax:show']=NULL;
		}
		if(0==$config['url_html']){
			@unlink(APP_PATH.c('site_path').'index'.c('html_file_suffix'));//动态模式则删除首页静态文件
		}else{
			$config['html_home_suffix']=$config['html_file_suffix'];//将静态后缀写入配置供前台生成的路径的时候调用
		}
		$array=require './Conf/setting.php';
		$new=array_merge($array,$config);
		arr2file('./Conf/setting.php',$new);
		@unlink('./Runtime/~app.php');
		//
		$pp_play.= 'var width='.$config['play_width'].';';
		$pp_play.= 'var height='.$config['play_height'].';';
		$pp_play.= 'var ff_showlist='.$config['play_show'].';';
		$pp_play.= 'var ff_second='.intval($config['play_second']).';';
		$pp_play.= 'var ff_qvod="'.$config['play_qvod'].'";';
		$pp_play.= 'var ff_gvod="'.$config['play_gvod'].'";';
		$pp_play.= 'var ff_pvod="'.$config['play_pvod'].'";';
		$pp_play.= 'var ff_web9="'.$config['play_web9'].'";';
		$pp_play.= 'var ff_bdhd="'.$config['play_bdhd'].'";';
		$pp_play.= 'var ff_baofeng="'.$config['play_baofeng'].'";';
		$pp_play.= 'var ff_buffer="'.$config['play_playad'].'";';
		foreach(C('play_server') as $key=>$value){
			$pp_play.= 'var ff_'.$key.'="'.$value.'";';
		}
		foreach(C('play_player') as $key=>$value){
			$pp_play.= 'var play_'.$key.'="'.$value.'";';
		}			
		write_file('./Runtime/Player/play.js',$pp_play);
		$this->success('恭喜您，配置信息更新成功！');
    }	
	
	// 用户管理
    public function show(){
	    $rs=D("Admin.Admin");
		$list=$rs->order('admin_id desc')->select();
		$this->assign('list',$list);
        $this->display('./Public/admin/admin.html');
    }
	
	// 用户添加
    public function add(){
		$array=array();
		$id=$_GET['admin_id'];
		if($id){
		    $rs=D("Admin.Admin");
			$array=$rs->find($id);
			$abc=explode(',',$array['admin_ok']);
		}
		$this->assign('admin',$abc);
		$this->assign($array);
        $this->display('./Public/admin/admin.html');
    }
	
	//处理权限入库
	public function _before_insert(){
		$ok=$_POST['admin_ok'];
		for($i=0;$i<16;$i++){
			if($ok[$i]){
				$rs[$i]=$ok[$i];
			}else{
				$rs[$i]=0;
			}
		}
		$_POST['admin_ok']=implode(',',$rs);
	} 
	   	
	// 写入数据
	public function insert(){
		$Form=D("Admin.Admin");
		if($Form->where('admin_name="'.trim($_POST['admin_name']).'"')->find()){$this->error('该用户名已经存,在请重新填写！');};
		if($Form->create()){
			if(false!==$Form->add()){
			    $this->assign("jumpUrl",'index.php?s=Admin-Index-Show');
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
	    $this->_before_insert();
		$Form=D("Admin.Admin");
		if($Form->create()){
			$list=$Form->save();
			if($list!==false){
			    $this->assign("jumpUrl",'index.php?s=Admin-Index-Show');
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
		$rs=D("Admin.Admin");
		$admin_id=$_GET['admin_id'];
		$rs->where('admin_id='.$admin_id)->delete();
		$this->success('成功删除一条ID为'.$admin_id.'的数据！');
    }
	
	// 批量删除
    public function delall(){
		$rs=D("Admin.Admin");
		foreach($_POST['admin_id'] as $value){$rs->where('admin_id='.$value)->delete();}
		$this->success('批量删除数据成功！');
    }				
}
?>