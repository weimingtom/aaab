<?php
function adminurl($model,$params,$indexphp,$redirect=false,$suffix=false){
	$pageurl=str_replace('Home-','',str_replace($indexphp.'?s=/','?s=',U($model,$params,$redirect,$suffix)));
	if(c('url_model')==2){
		return 'index.php?s='.$pageurl;
	}else{
		return $pageurl;
	}
};
function gettplnum($filename,$rule){// 获取模板分页数据大小
	$content=read_file(TMPL_PATH.C('default_theme').'/Home/'.$filename);
	preg_match_all('/'.$rule.'/', $content, $data);//pplist->ppvod\(\'(.*)\'\)
	foreach($data[1] as $key=>$val){
		if(strpos($val,'page:true')>0){
			$array=explode(';',$val);
			foreach ($array as $v){list($key,$val)=explode(':',trim($v));$param[trim($key)]=trim($val);}
			return $param['num'];break;
		}
	}
	return false;
}
function getcmcid($id,$sid){// 获取相对应的影片或文章
	if($sid==1){
		$rs=D("Admin.Vod");
		$array=$rs->field('vod_name')->where('vod_id='.$id)->find();
		if($array){return $array['vod_name'];}
	}else{
		$rs=D("Admin.News");
		$array=$rs->field('news_name')->where('news_id='.$id)->find();
		if($array){return $array['news_name'];}
	}
	return '未知数据！';
}
function testwrite($d){
	$tfile = '_ppvod.txt';
	$d = ereg_replace('/$','',$d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp){
		return false;
	}else{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs){
			return true;
		}else{
			return false;
		}
	}
}
?>