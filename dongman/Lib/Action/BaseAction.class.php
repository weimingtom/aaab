<?php
class BaseAction extends AllAction{
	//构造
	public function _initialize(){
		set_time_limit(C('admin_time_limit'));
		$this->checkadmin();
	}
	//checkadmin
	public function checkadmin(){
		header("Content-Type:text/html; charset=".C(DEFAULT_CHARSET));
		if(!in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))){//不需要认证的模块除外
			//检查登录
			if(!$_SESSION[C('USER_AUTH_KEY')]){
				$this->assign("jumpUrl","index.php?s=Admin-Login");
				$this->error('对不起,您还没有登录！');
			}
			//检查权限
			if(!in_array(strtolower(ACTION_NAME),explode(',',C('NOT_AUTH_ACTION')))) {//不需要认证的操作除外
				$model_id=array_search(MODULE_NAME,explode(',',C('REQUIRE_AUTH_MODULE')));//检索当前模块是否在设定需要认证的模块范围内
				if(false===$model_id){
					$this->error('未知模块，请到后台用户管理中增加对该模块的管理权限！');
				}else{//当前用户的权限列表
					$admin_ok=explode(',',$_SESSION['admin_ok']);
					if(!$admin_ok[$model_id]){$this->error('对不起您没有管理该模块的权限,请联系超级管理员授权！');}
				}
			}
		}
	}
	//生成播放器列表
	public function ppvod_play(){
		$this->assign('countplayer',count(C('PP_PLAYER')));
		$this->assign('playtree',C('play_player'));
	}
	//生成前台分类缓存
	public function ppvod_list(){
		$rs=D("Admin.List");
		$where['list_oid']=array('GT',0);
		$list=$rs->where($where)->order('list_oid asc')->select();
		foreach($list as $key=>$val){
			if(C('url_html')==1||C('url_html')==4){
				if(1==$list[$key]['list_sid']){
					$listurl=C('site_path').C('url_vodlist').getlisturl($list[$key]['list_id'],1).C('html_file_suffix');
				}elseif(2==$list[$key]['list_sid']){
					$listurl=C('site_path').C('url_newslist').getlisturl($list[$key]['list_id'],1).C('html_file_suffix');
				}else{
					$listurl=str_replace('{$root}',C('site_path'),$list[$key]['list_dir']);
				}
				$list[$key]['list_url']=str_replace('index'.c('html_file_suffix'),'',$listurl);//去除index.html
			}else{
				if(1==$list[$key]['list_sid']){
					$list[$key]['list_url']=ppvodurl('Home-vod/show',array('id'=>$list[$key]['list_id']),'index.php',false,true);
				}elseif(2==$list[$key]['list_sid']){
					$list[$key]['list_url']=ppvodurl('Home-news/show',array('id'=>$list[$key]['list_id']),'index.php',false,true);
				}else{
					$list[$key]['list_url']=str_replace('{$root}',C('site_path'),$list[$key]['list_dir']);
				}
			}
		}
		F('_ppvod/list',$list);
		F('_ppvod/listtree',list_to_tree($list,'list_id','list_pid','son',0));

		$where['list_sid']=array('EQ',1);
		$list=$rs->where($where)->order('list_oid asc')->select();
		F('_ppvod/listvod',list_to_tree($list,'list_id','list_pid','son',0));

		$where['list_sid']=array('EQ',2);
		$list=$rs->where($where)->order('list_oid asc')->select();
		F('_ppvod/listnews',list_to_tree($list,'list_id','list_pid','son',0));
	}
	//当天缓存文件清理
	public function ppvod_cachevodday(){
		$ppvod=D("Admin.Vod");
		$time=time()-3600*24;
		$where['vod_addtime']= array('GT',$time);
		$rs=$ppvod->field('vod_id')->where($where)->order('vod_id desc')->select();
		if($rs){
			foreach($rs as $key=>$val){
				$id=md5($rs[$key]['vod_id']).c('html_file_suffix');
				@unlink(APP_PATH.'/Html/Vod_read/'.$id);
				@unlink(APP_PATH.'/Html/Vod_play/'.$id);
			}
			import("ORG.Io.Dir");
			$dir=new Dir;
			if(!$dir->isEmpty(APP_PATH.'/Html/Vod_show')){$dir->delDir(APP_PATH.'/Html/Vod_show');}
			if(!$dir->isEmpty(APP_PATH.'/Html/Ajax_show')){$dir->delDir(APP_PATH.'/Html/Ajax_show');}
			@unlink(APP_PATH.'/Html/index'.c('html_file_suffix'));
		}
	}
}
?>