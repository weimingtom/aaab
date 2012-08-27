<?php
function video_getflashinfo($link, $host) {
        $return='';
        $content = file_get_contents("compress.zlib://".$link);
		if('youku.com' == $host)
		{
			preg_match('/id\_(\w+)\.html/', $link, $flashvar);
			preg_match("/<title>(.*?)<\/title>/i",$content,$title);
			$jsoninfo = json_decode(file_get_contents('http://v.youku.com/player/getPlayList/VideoIDS/'.$flashvar[1]));
			$img = array(0,str_replace('ykimg.com/1','ykimg.com/0',$jsoninfo->data[0]->logo));
		}
        elseif('ku6.com' == $host)
        {
            preg_match("/\/([\w\-]+)\.html/",$link,$flashvar);
        	preg_match("/<span class=\"s_pic\">(.*?)<\/span>/i",$content,$img);
        	preg_match("/<title>(.*?)<\/title>/i",$content,$title);
        	$title[1] = iconv("GBK","UTF-8",$title[1]);
        }
        elseif('sina.com.cn' == $host)
        {
        	preg_match("/vid=(.*?)\/s\.swf/",$content,$flashvar);
        	preg_match("/pic\:[ ]*\'(.*?)\'/i",$content,$img);
        	preg_match("/<title>(.*?)<\/title>/i",$content,$title);        	
        }
        elseif('tudou.com' == $host)
        {
        	//土豆视频解析修改　　editby: nonant 2012-1-19 参考了记事狗解析正则.
			if(preg_match('~(?:(?:[\?\&\#]iid\=)|(?:\d+i))(\d+)~',$link,$defaultIid) )
			{
			    $defaultIid = $defaultIid[1];
			}elseif(preg_match('~(?:(?:\,iid\s*=)|(?:\,defaultIid\s*=)|(?:\.href\)\s*\|\|))\s*(\d+)~',$content,$defaultIid) )
			{
				$defaultIid = $defaultIid[1];
			}
			if( $defaultIid ){
				preg_match('~'.$defaultIid.'.*?icode\s*[\:\=]\s*[\"\']([\w\d\-\_]+)[\"\']~s',$content,$flashvar);
				preg_match('~'.$defaultIid.'.*?title\s*[\:\=]\s*[\"\']([^\"\']+?)[\"\']~s',$content,$title);
				preg_match('~'.$defaultIid.'.*?pic\s*[\:\=]\s*[\"\']([^\"\']+?)[\"\']~s',$content,$img);
				$title[1] = iconv("GBK","UTF-8",$title[1]);
			}
        }
 		elseif('youtube.com' == $host) {
        }
        elseif('5show.com' == $host) {
        }
        elseif('sohu.com' == $host) {
            preg_match("/vid=\"(.*?)\"/", $content, $flashvar);
        	preg_match('/cover="([^"]+)";/', $content, $img);
        	preg_match("/<title>(.*?)<\/title>/", $content, $title);
        	$title[1] = iconv("GBK","UTF-8",$title[1]);
        }
        elseif('mofile.com' == $host)
        {
            preg_match("/\/([\w\-]+)\.shtml/",$link,$flashvar);
        	preg_match("/thumbpath=\"(.*?)\";/i",$content,$img);
        	preg_match("/<title>(.*?)<\/title>/i",$content,$title);
        }

        $return['flashvar'] = $flashvar[1];
        $return['img']   = $img[1];
        $return['title'] = $title[1];
        return $return;
}