<?php
/**
 * @name 附件上传模块
 */
class UploadAction extends BaseAction{	
	// 显示上传表单
    public function show(){
        $this->display('./Public/admin/upload.html');
    }	
	// 执行上传操作
	public function upload(){
		echo('<div style="font-size:12px; height:30px; line-height:30px">');
		$uppath = './'.C('upload_path').'/';
		$uppath_s = './'.C('upload_path').'-s/';
		//模块与回跳参数
		$sid = trim($_POST['sid']);
		$fileback = !empty($_POST['fileback']) ? trim($_POST['fileback']) : 'vod_pic';
		if ($sid) {
			$uppath .= $sid.'/';
			$uppath_s .= $sid.'/';
			$backpath = $sid.'/';
			mkdirss($uppath);
		}
		import("ORG.Net.UploadFile");
		$up = new UploadFile();
		//$up->maxSize = 3292200;
		$up->savePath = $uppath;
		$up->saveRule = uniqid;
		$up->uploadReplace = true;
		$up->allowExts = explode(',',C('upload_class'));
		$up->autoSub = true;
		$up->subType = date;
		$up->dateFormat = C('upload_style');
        if (!$up->upload()) {
			$error = $up->getErrorMsg();
			if($error == '上传文件类型不允许'){
				$error .= ',可上传<font color=red>'.C('upload_class').'</font>';
			}
			exit($error.' [<a href="?s=Admin-Upload-Show-sid-'.$sid.'-fileback-'.$fileback.'">重新上传</a>]');
		}
		$uploadList = $up->getUploadFileInfo();
		//是否添加水印
		if (C('upload_water')) {
		   import("ORG.Util.Image");
		   Image::water($uppath.$uploadList[0]['savename'],C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
		}
		//是否生成缩略图
		if (C('upload_thumb')) {
		   $thumbdir = substr($uploadList[0]['savename'],0,strrpos($uploadList[0]['savename'], '/'));
		   mkdirss($uppath_s.$thumbdir);
		   import("ORG.Util.Image");
		   Image::thumb($uppath.$uploadList[0]['savename'],$uppath_s.$uploadList[0]['savename'],'',C('upload_thumb_w'),C('upload_thumb_h'),true);
		}
		//是否远程图片
		if (C('upload_ftp')) {
			$img = D('Img');
			$img->ftp_upload($backpath.$uploadList[0]['savename']);
		}
		echo "<script type='text/javascript'>parent.document.getElementById('".$fileback."').value='".$backpath.$uploadList[0]['savename']."';</script>";
		echo '文件<a href="'.$uppath.$uploadList[0]['savename'].'" target="_blank"><font color=red>'.$uploadList[0]['savename'].'</font></a>上传成功　[<a href="?s=Admin-Upload-Show-sid-'.$sid.'-fileback-'.$fileback.'">重新上传</a>]';
		echo '</div>';
	}		
}
?>