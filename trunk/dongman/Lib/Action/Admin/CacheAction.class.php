<?php
class CacheAction extends BaseAction{
    public function show(){    
		 $this->display(APP_PATH.'/Public/admin/cache.html');
		//$this->success('数据更新成功！');
    }
	public function vodday(){
	    $this->ppvod_cachevodday();
		$this->assign("jumpUrl",'index.php?s=Admin-Cache-Show');
		$this->success('清空当天静态缓存成功！');
	}
    public function del(){   
	    import("ORG.Io.Dir");
		$dir=new Dir;
		$cache=$_POST;
		if($cache['fields']){
		    if(!$dir->isEmpty(APP_PATH.'/Runtime/Data/_fields')){$dir->del(APP_PATH.'/Runtime/Data/_fields');}
			$this->success('更新数据库缓存文件成功！');
		}elseif($cache['temp']){
		    @unlink(APP_PATH.'/Runtime/~app.php');
			@unlink(APP_PATH.'/Runtime/~runtime.php');		
			if(!$dir->isEmpty(APP_PATH.'/Runtime/Temp')){$dir->delDir(APP_PATH.'/Runtime/Temp');}
			$this->success('更新文件缓存文件成功！');		    
		}elseif($cache['cache']){
			if(!$dir->isEmpty(APP_PATH.'/Runtime/Cache')){$dir->delDir(APP_PATH.'/Runtime/Cache');}
			$this->success('更新模板缓存文件成功！');		    
		}elseif($cache['menu']){
			$this->ppvod_list();
			$this->success('更新导航菜单缓存成功！');		    
		}else{
		    $this->ppvod_list();
		    @unlink(APP_PATH.'/Runtime/~app.php');
			@unlink(APP_PATH.'/Runtime/~runtime.php');
		    if(!$dir->isEmpty(APP_PATH.'/Runtime/Data/_fields')){$dir->del(APP_PATH.'/Runtime/Data/_fields');}
			if(!$dir->isEmpty(APP_PATH.'/Runtime/Temp')){$dir->delDir(APP_PATH.'/Runtime/Temp');}
			if(!$dir->isEmpty(APP_PATH.'/Runtime/Cache')){$dir->delDir(APP_PATH.'/Runtime/Cache');}
			if(!$dir->isEmpty(APP_PATH.'/Runtime/Logs')){$dir->delDir(APP_PATH.'/Runtime/Logs');}	
			$this->success('更新所有系统缓存文件成功！');
		}	
    }	
	// 删除静态缓存
	public function delhtml(){
	    import("ORG.Io.Dir");
		$dir=new Dir;
		$cache=$_POST;//$dir->delDir(APP_PATH.'/Html');
		if($cache['index']){
		    //if(!$dir->isEmpty(APP_PATH.'/Html')){$dir->del(APP_PATH.'/Html');}
			@unlink(APP_PATH.'/Html/index'.c('html_file_suffix'));
			$this->success('更新首页缓存文件成功！');
		}elseif($cache['vodshow']){
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_show')){$dir->delDir(APP_PATH.'/Html/Vod_show');}
			$this->success('更新视频列表页缓存文件成功！');		    
		}elseif($cache['vodread']){
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_read')){$dir->delDir(APP_PATH.'/Html/Vod_read');}
			$this->success('更新视频内容页缓存文件成功！');		    
		}elseif($cache['vodplay']){
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_play')){$dir->delDir(APP_PATH.'/Html/Vod_play');}
			$this->success('更新视频播放页缓存文件成功！');		    
		}elseif($cache['newsshow']){
			if(!$dir->isEmpty(APP_PATH.'/Html/News_show')){$dir->delDir(APP_PATH.'/Html/News_show');}
			$this->success('更新新闻列表页缓存文件成功！');	    
		}elseif($cache['newsread']){
			if(!$dir->isEmpty(APP_PATH.'/Html/News_read')){$dir->delDir(APP_PATH.'/Html/News_read');}
			$this->success('更新新闻内容页缓存文件成功！');	    
		}elseif($cache['ajaxshow']){
		    if(!$dir->isEmpty(APP_PATH.'/Html/Ajax_show')){$dir->delDir(APP_PATH.'/Html/Ajax_show');}	
			$this->success('更新Ajax自定义模板调用缓存文件成功！');	    
		}else{
		    if(!$dir->isEmpty(APP_PATH.'/Html')){$dir->del(APP_PATH.'/Html');}
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_show')){$dir->delDir(APP_PATH.'/Html/Vod_show');}
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_read')){$dir->delDir(APP_PATH.'/Html/Vod_read');}
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_play')){$dir->delDir(APP_PATH.'/Html/Vod_play');}
			if(!$dir->isEmpty(APP_PATH.'/Html/News_show')){$dir->delDir(APP_PATH.'/Html/News_show');}
			if(!$dir->isEmpty(APP_PATH.'/Html/News_read')){$dir->delDir(APP_PATH.'/Html/News_read');}
			if(!$dir->isEmpty(APP_PATH.'/Html/Ajax_show')){$dir->delDir(APP_PATH.'/Html/Ajax_show');}		
			$this->success('更新所有静态缓存文件成功！');		
		}
	}
}
?>