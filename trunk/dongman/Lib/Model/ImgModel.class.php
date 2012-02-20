<?php
class ImgModel extends Model {
	//调用接口
	public function down_load($url){
		if (C('upload_http')) {
			return $this->down_img($url);
		}else{
			return $url;
		}		
	}
	//远程下载图片
	public function down_img($url){
	   if(!empty($url)){
	       $chr = strrchr($url,".");
		   $imgUrl = uniqid();
		   $imgPath = C('upload_path').'/'.date(C('upload_style'),time()).'/';	
		   $filename = $imgPath.$imgUrl.$chr;
		   $get_file = getfile($url);
		   if($get_file){
			   write_file($filename,$get_file);
			   if(C('upload_thumb')){//是否生成缩略图
				   mkdirss(APP_PATH.'/'.C('upload_path').'/thumb/');
				   import("ORG.Util.Image");
				   Image::thumb($filename,C('upload_path').'/thumb/'.$imgUrl.$chr,'',C('upload_thumb_w') ,C('upload_thumb_h'),true);
			   }
			   if(C('upload_water')){//是否添加水印
				   import("ORG.Util.Image");
				   Image::water($filename,C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
			   }
			   $imgfile = date(C('upload_style'),time()).'/'.$imgUrl.$chr;
			   if(C('upload_ftp')){//是否上传远程
			       $this->ftp_upload($imgfile);
			   }
		   	   return $imgfile;
		   }
		}
		return false;
	}	
	//远程ftp附件
	public function ftp_upload($imgurl){
		Vendor('Ftp.Ftp');
		$ftpcon = array(
			'ftp_host'=>C('upload_ftp_host'),
			'ftp_port'=>C('upload_ftp_port'),
			'ftp_user'=>C('upload_ftp_user'),
			'ftp_pwd'=>C('upload_ftp_pass'),
			'ftp_dir'=>C('upload_ftp_dir'),
		);
		$ftp = new ftp();
		$ftp->config($ftpcon);
		$ftp->connect();
		$ftpimg = $ftp->put(C('upload_path').'/'.$imgurl,C('upload_path').'/'.$imgurl);
		if(C('upload_thumb')){
			//$imgurl_s = strrchr($imgurl,"/");
			//$ftpimg_s = $ftp->put(C('upload_path').'/thumb'.$imgurl_s, 'thumb'.$imgurl_s);
			$ftpimg_s = $ftp->put(C('upload_path').'-s/'.$imgurl, C('upload_path').'-s/'.$imgurl);
		}
		if(C('upload_ftp_del')){
			if($ftpimg){
				@unlink(C('upload_path').'/'.$imgurl);
			}
			if($ftpimg_s){
				@unlink(C('upload_path').'/thumb'.$imgurl_s);
			}
		}
		$ftp->bye();
	}
}
?>