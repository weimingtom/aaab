<?php 
session_start();

//������֤��ͼƬ 
header("Content-type: image/PNG");  
srand((double)microtime()*1000000); 
$im = imagecreate(65,25); 
$black = ImageColorAllocate($im, 0,0,0); 
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 200,200,200); 
imagefill($im,0,0,$white); 
//while(($authnum=rand()%100000)<10000);
$authnum = rand(10000,99999);
$_SESSION["WWW_MYDECMS_COM_NZM"] = $authnum;

//����λ������֤�����ͼƬ 
imagestring($im, 6, 6, 6, $authnum, $black); 


for($i=0;$i<150;$i++)   //����������� 
{ 
    $randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
    imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); 
} 


ImagePNG($im); 
ImageDestroy($im); 
?>