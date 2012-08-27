<?php 
include_once 'function.php';

switch ($do_type){
	case 'before_publish': //发布前检验
    	if( $_FILES['pic'] ){
    		
    		if($_FILES['pic']['size'] > 2*1024*1024 ){
	        	$result['boolen']    = 0;
	        	$result['message']   = '图片太大，不能超过2M';
	        	exit( json_encode( $result ) );
    		}

    	    $imageInfo = getimagesize($_FILES['pic']['tmp_name']);
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            if( !in_array($imageType,array('jpg','gif','png','jpeg')) ) {
                $result['boolen']    = 0;
                $result['message']   = '图片格式错误';
                exit( json_encode( $result ) );
	        }

    		//执行上传操作
    		$savePath =  getSaveTempPath();
            //$filename = md5( time().$this->mid ).'.'.substr($_FILES['pic']['name'],strpos($_FILES['pic']['name'],'.')+1);
            $filename = md5( time().$this->mid ).'.'.$imageType;
	    	if(@copy($_FILES['pic']['tmp_name'], $savePath.'/'.$filename) || @move_uploaded_file($_FILES['pic']['tmp_name'], $savePath.'/'.$filename)) 
	        {
	        	$result['boolen']    = 1;
	        	$result['type_data'] = 'temp/'.$filename;
	        	$result['file_name'] = $filename;
	        	$result['picurl']    = __UPLOAD__.'/temp/'.$filename;
				//添加图片水印
				
/*				$domain=D( 'user' )->where('uid='.$_SESSION['mid'].'')->find();
$img="data/uploads/temp/".$filename.""; 
$arr=GetImageSize($img);
if($arr['0']>300){
switch($arr[2])
  {
case 1 :
$im= imagecreatefromgif($img);
break;
case 2 :
$im = imagecreatefromjpeg($img);
break;
case 3 :
$im = imagecreatefrompng($img);
break;
default :
return;
  }
$str=SITE_URL."/".$domain['domain'];
$wigth=imagettfbbox($arr['0']*0.02, 0, "Arial.ttf", $str);
$wigth1=imagettfbbox($arr['0']*0.02, 0, "Arial.ttf", $str1);
$color = imagecolorallocate($im,255,255,255 );
$grey = imagecolorallocate ($im, 0, 0, 0);
$info=getimagesize($img);
imageantialias ($im ,true);
//添加阴影
imagettftext ($im, $arr['0']*0.02,0,$arr['0']-$wigth[2]-8,$arr['1']-8, $grey, 'Arial.ttf',$str);
imagettftext($im,$arr['0']*0.02,0,$arr['0']-$wigth[2]-9,$arr['1']-9,$color,'Arial.ttf',$str);
 
imagejpeg($im,$img);
 
}*/
			//添加图片水印 end	
				
	        } else {
	        	$result['boolen']    = 0;
	        	$result['message']   = '上传失败';
	        }
    	}else{
        	$result['boolen']    = 0;
        	$result['message']   = '上传失败';
    	}
    	exit( json_encode( $result ) );		
		break;
		
	case 'publish':  //发布处理
			if(!file_exists($type_data)){
				$type_data = '/data/uploads/'.$type_data;
			}else{
				$type_data = preg_replace("/^\./",'',$type_data);
			}
	 		preg_match('|\.(\w+)$|', basename($type_data), $ext);
	 		$fileext  = strtolower($ext[1]);
	 		$filename = md5($type_data) . '.' . $fileext;
			$datePath = date('Y/md/H');
	 		$savePath = SITE_PATH.'/data/uploads/miniblog/'.$datePath;
        	if( !file_exists( $savePath ) ) mk_dir( $savePath  );
	 		$thumbname = substr( $filename , 0 , strpos( $filename,'.' ) ).'_small.jpg';
	 		$thumbmiddlename = substr( $filename , 0 , strpos( $filename,'.' ) ).'_middle.jpg';
	 		if( copy( SITE_PATH.$type_data , $savePath.'/'.$filename) ){
	 				include_once SITE_PATH.'/addons/libs/Image.class.php';
					Image::thumb( $savePath.'/'.$filename , $savePath.'/'.$thumbname , '' , 160 , 160 );
					Image::thumb( $savePath.'/'.$filename , $savePath.'/'.$thumbmiddlename , '' , 465 ,'auto' );
		        	$typedata['thumburl'] = 'miniblog/'.$datePath.'/'.$thumbname;
		        	$typedata['thumbmiddleurl'] = ($fileext=='gif')?'miniblog/'.$datePath.'/'.$filename:'miniblog/'.$datePath.'/'.$thumbmiddlename;
		        	$typedata['picurl']   = 'miniblog/'.$datePath.'/'.$filename;
					
					//分享:发布微博分享图片,自动同步到相册
/*						
					
 $imgids = M('photo_album')->where('userId='.$_SESSION['mid'].' AND name="微博图集"')->field('*')->find();
   if(!$imgids){
   //如果没有微博图集的相册，尝试建立
   $img = array(
   'userId'  =>$_SESSION['mid'],
   'name'         =>"微博图集",
    'cTime'        =>time(),
   'mTime'        =>time(),
   'photoCount'   =>'1',
   'coverImagePath' =>$typedata['picurl'],
      'status' => '1',
   'privacy'  => "1",
      );
      $id=M('photo_album')->add($img); 
   }else{
   //否则更新图集，照片统计增1
   $id=$imgids['id'];
     $key=array('photoCount','cTime','mTime','coverImagePath');
           $value=array(''.($imgids['photoCount']+1).'',time(),time(),''.$typedata['picurl'].'');
           M('photo_album')->where('id='.$id.' AND userId='.$_SESSION['mid'].'')->setField($key, $value);
   
   }
   //写入相册
   $imgphoto = array(
   'attachId'         => $id,
   'albumId'         => $id,
   'userId'  =>$_SESSION['mid'],
   'status' => '1',
   'name'  => "来自微博",
   'savepath'  =>$typedata['picurl'],
   );
      M('photo')->add($imgphoto); 
					
	*/				
					//分享:发布微博分享图片,自动同步到相册 end
					
					
	 		}
		break;
		
	case 'after_publish': //发布完成后的处理

		break;
}