<?php
class TplAction extends BaseAction{	
	// 显示分类
    public function show(){
		$tpl=TMPL_PATH.C('default_theme').'/Home/*';
		$list=glob($tpl);
		$css=glob(TMPL_PATH.C('default_theme').'/style.css');
		foreach ($list as $i=>$file){
			$dir[$i]['filename']=basename($file);
			$dir[$i]['tplname']=strtolower(substr(basename($file),0,strrpos(basename($file), '.')));
			$dir[$i]['gbname']=gettplname(basename($file));
			$dir[$i]['ext']=is_file($file)?strtolower(substr(strrchr(basename($file), '.'),1)):'';
			$dir[$i]['mtim']=filemtime($file);
		}
		$this->assign('css',filemtime($css[0]));
		$this->assign('dir',$dir);
		$this->display(APP_PATH.'/Public/admin/tpl.html');
    }
	// 编辑模板
	public function add(){
		$id=$_GET['id'];
		if(!empty($id)){
			if('style.css'==$id){
			$tpl=TMPL_PATH.C('default_theme').'/style.css';
			}else{
			$tpl=TMPL_PATH.C('default_theme').'/Home/'.$id;
			}
			$content=read_file($tpl);
			$this->assign('filename',$id);
			$this->assign('content',str_replace('</textarea>','&lt;/textarea>',$content));
			$this->display(APP_PATH.'/Public/admin/tpl.html');
		}else{
			$this->error('模板名不能为空!');
		}
	}
	// 更新数据
	public function update(){
		$filename=$_POST['filename'];
		$content=stripslashes($_POST['content']);
		$content=str_replace('&lt;/textarea>','</textarea>',$content);
		if(!empty($filename)&&!empty($content)){
			if('style.css'==$filename){
			$tpl=TMPL_PATH.C('default_theme').'/style.css';
			}else{
			$tpl=TMPL_PATH.C('default_theme').'/Home/'.$filename;
			}		
			write_file($tpl,$content);
			$this->assign("jumpUrl",'index.php?s=Admin-Tpl-Show');
			$this->success('恭喜您,模板更新成功！');
		}else{
			$this->error('模板内容不能为空!');
		}
	}
	// 无效图片清理
    public function pic(){
		$tpl=APP_PATH.'/'.C('upload_path').'/*';
		$list=glob($tpl);
		foreach ($list as $i=>$file){
			$dir[$i]['id']=str_replace('-','@',basename($file));
			$dir[$i]['path']=$file;
			$dir[$i]['filename']=basename($file);
			$dir[$i]['ext']=is_file($file)?strtolower(substr(strrchr(basename($file), '.'),1)):'';
			$dir[$i]['mtim']=filemtime($file);
		}
		$this->assign('dir',$dir);
		$this->display(APP_PATH.'/Public/admin/tplpic.html');
    }
	public function picdel(){
		$id=trim(str_replace('@','-',$_GET['id']));
		$tpl=APP_PATH.'/'.C('upload_path').'/'.$id.'/*';
		$list=glob($tpl);if(empty($list)){exit('ajaxend');}
		foreach ($list as $i=>$file){
			$dir[]=$id.'/'.basename($file);
		}
		$rs=D("Admin.Vod");
		$array=$rs->field('vod_pic')->where('Left(vod_pic,4)!="http"')->order('vod_addtime desc')->select();
		foreach ($array as $i=>$value){
			$dir2[]=$value['vod_pic'];
		}
		$del=array_diff($dir,$dir2);
		foreach ($del as $key=>$value){
			@unlink(APP_PATH.'/'.C('upload_path').'/'.$value);
		};
		exit('ajaxend');
    }					
}
?>