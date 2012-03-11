<?php
/*-------------------------------------------------老函数兼容开始------------------------------------------------------------------*/
function getfirstchar($s0){
	return ff_letter_first($s0);
}
function getpinyin($str,$ishead=0,$isclose=1){
	return ff_pinyin($str,$ishead=0,$isclose=1);
}
function getsitepath($filename){
	return get_site_path($filename);
}
function getbaseurl($baseurl,$url){
	return get_base_url($baseurl,$url);
}
function getfile($url){
	return ff_file_get_contents($url);
}
function getvoddata($rule,$html){
	return ff_preg_match($html,$rule);
}
function getvodall($rule,$html){
	return ff_preg_match_all($html,$rule);
}
function getreurl($listurl){
   return ff_krsort_url($listurl);
}
function getreplace($arr){
	return ff_implode_rule($arr);
}
function getrole($str){
	return ff_replace_rule($str);
}
function getrandstr($string){
	return  ff_rand_str($string);
}
/*-------------------------------------------------文件夹与文件操作开始------------------------------------------------------------------*/
//读取文件
function read_file($l1){
	return @file_get_contents($l1);
}
//写入文件
function write_file($l1, $l2=''){
	$dir = dirname($l1);
	if(!is_dir($dir)){
		mkdirss($dir);
	}
	return @file_put_contents($l1, $l2);
}
//递归创建文件
function mkdirss($dirs,$mode=0777) {
	if(!is_dir($dirs)){
		mkdirss(dirname($dirs), $mode);
		return @mkdir($dirs, $mode);
	}
	return true;
}
// 数组保存到文件
function arr2file($filename, $arr=''){
	if(is_array($arr)){
		$con = var_export($arr,true);
	} else{
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
	write_file($filename, $con);
}

/*-------------------------------------------------系统路径相关函数开始------------------------------------------------------------------*/
//获取当前地址栏URL
function get_http_url(){
	return htmlspecialchars("http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
}
//获取根目录路径
function get_site_path($filename){
    $basepath = $_SERVER['PHP_SELF'];
    $basepath = substr($basepath,0,strpos($basepath,$filename));
	return $basepath;
}
//相对路径转绝对路径
function get_base_url($baseurl,$url){
	if("#" == $url){
		return "";
	}elseif(FALSE !== stristr($url,"http://")){
		return $url;
	}elseif( "/" == substr($url,0,1) ){
		$tmp = parse_url($baseurl);
		return $tmp["scheme"]."://".$tmp["host"].$url;
	}else{
		$tmp = pathinfo($baseurl);
		return $tmp["dirname"]."/".$url;
	}
}
//获取指定地址的域名
function get_domain($url){
	preg_match("|http://(.*)\/|isU", $url, $arr_domain);
	return $arr_domain[1];
}

/*-------------------------------------------------字符串处理开始------------------------------------------------------------------*/
// UT*转GBK
function u2g($str){
	return iconv("UTF-8","GBK",$str);
}
// GBK转UTF8
function g2u($str){
	return iconv("GBK","UTF-8//ignore",$str);
}
// 转换成JS
function t2js($l1, $l2=1){
    $I1 = str_replace(array("\r", "\n"), array('', '\n'), addslashes($l1));
    return $l2 ? "document.write(\"$I1\");" : $I1;
}
// 去掉换行
function nr($str){
	$str = str_replace(array("<nr/>","<rr/>"),array("\n","\r"),$str);
	return trim($str);
}
//去掉连续空白
function nb($str){
	$str = str_replace("&nbsp;",' ',$str);
	$str = str_replace("　",' ',$str);
	$str = ereg_replace("[\r\n\t ]{1,}",' ',$str);
	return trim($str);
}
// 输入过滤 同时去除连续空白字符
function hh($str,$rptype=0){
	$str = stripslashes($str);
	$str = htmlspecialchars($str);
	$str = nb($str);
	return addslashes($str);
}
//字符串截取(同时去掉HTML与空白)
function msubstr($str, $start=0, $length, $suffix=false){
	return ff_msubstr(eregi_replace('<[^>]+>','',nb($str)),$start,$length,'utf-8',$suffix);
}
function ff_msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$length_new = $length;
	for($i=$start; $i<$length; $i++){
		if (ord($match[0][$i]) > 0xa0){
			//中文
		}else{
			$length_new++;
			$length_chi++;
		}
	}
	if($length_chi<$length){
		$length_new = $length+($length_chi/2);
	}
	$slice = join("",array_slice($match[0], $start, $length_new));
    if($suffix && $slice != $str){
		return $slice."…";
	}
    return $slice;
}
// 汉字转拼单
function ff_pinyin($str,$ishead=0,$isclose=1){
	$str = u2g($str);//转成GBK
	global $pinyins;
	$restr = '';
	$str = trim($str);
	$slen = strlen($str);
	if($slen<2){
		return $str;
	}
	if(count($pinyins)==0){
		$fp = fopen('./Common/pinyin.dat','r');
		while(!feof($fp)){
			$line = trim(fgets($fp));
			$pinyins[$line[0].$line[1]] = substr($line,3,strlen($line)-3);
		}
		fclose($fp);
	}
	for($i=0;$i<$slen;$i++){
		if(ord($str[$i])>0x80){
			$c = $str[$i].$str[$i+1];
			$i++;
			if(isset($pinyins[$c])){
				if($ishead==0){
					$restr .= $pinyins[$c];
				}
				else{
					$restr .= $pinyins[$c][0];
				}
			}else{
				//$restr .= "_";
			}
		}else if( eregi("[a-z0-9]",$str[$i]) ){
			$restr .= $str[$i];
		}
		else{
			//$restr .= "_";
		}
	}
	if($isclose==0){
		unset($pinyins);
	}
	return $restr;
}
//生成字母前缀
function ff_letter_first($s0){
	$firstchar_ord=ord(strtoupper($s0{0})); 
	if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0}; 
	$s=iconv("UTF-8","gb2312", $s0); 
	$asc=ord($s{0})*256+ord($s{1})-65536; 
	if($asc>=-20319 and $asc<=-20284)return "A";
	if($asc>=-20283 and $asc<=-19776)return "B";
	if($asc>=-19775 and $asc<=-19219)return "C";
	if($asc>=-19218 and $asc<=-18711)return "D";
	if($asc>=-18710 and $asc<=-18527)return "E";
	if($asc>=-18526 and $asc<=-18240)return "F";
	if($asc>=-18239 and $asc<=-17923)return "G";
	if($asc>=-17922 and $asc<=-17418)return "H";
	if($asc>=-17417 and $asc<=-16475)return "J";
	if($asc>=-16474 and $asc<=-16213)return "K";
	if($asc>=-16212 and $asc<=-15641)return "L";
	if($asc>=-15640 and $asc<=-15166)return "M";
	if($asc>=-15165 and $asc<=-14923)return "N";
	if($asc>=-14922 and $asc<=-14915)return "O";
	if($asc>=-14914 and $asc<=-14631)return "P";
	if($asc>=-14630 and $asc<=-14150)return "Q";
	if($asc>=-14149 and $asc<=-14091)return "R";
	if($asc>=-14090 and $asc<=-13319)return "S";
	if($asc>=-13318 and $asc<=-12839)return "T";
	if($asc>=-12838 and $asc<=-12557)return "W";
	if($asc>=-12556 and $asc<=-11848)return "X";
	if($asc>=-11847 and $asc<=-11056)return "Y";
	if($asc>=-11055 and $asc<=-10247)return "Z";
	return 0;//null
}
/*-------------------------------------------------采集函数开始------------------------------------------------------------------*/
// 采集内核
function ff_file_get_contents($url){
	for($i=0;$i<3;$i++){
		$content = @file_get_contents($url);
		if($content) break;
	}
	if($content){
		return $content;
	}
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
		$content = curl_exec($ch);
		curl_close($ch);
		if($content){
			return $content;
		}		
	}
	return false;
}
// 采集-匹配规则结果
function ff_preg_match($rule,$html){
	$arr = explode('$$$',$rule);
	if(count($arr) == 2){
	    preg_match('/'.$arr[1].'/', $html, $data);
		return $data[$arr[0]].'';
	}else{
	    preg_match('/'.$rule.'/', $html, $data);
		return $data[1].'';
	}
}
// 采集-匹配规则结果all
function ff_preg_match_all($rule,$html){
	$arr = explode('$$$',$rule);
	if(count($arr) == 2){
	    preg_match_all('/'.$arr[1].'/', $html, $data);
		return $data[$arr[0]];
	}else{
	    preg_match_all('/'.$rule.'/', $html, $data);
		return $data[1];
	}
}
// 采集-倒序采集
function ff_krsort_url($listurl){
   krsort($listurl);
   foreach($listurl as $val){
       $list[]=$val;
   }
   return $list;
}
// 采集-将所有替换规则保存在一个字段
function ff_implode_rule($arr){
    foreach($arr as $val){
	    $array[] = trim(stripslashes($val));
	}
	return implode('|||',$array);
}
//  采集-规则替换
function ff_replace_rule($str){
	//$str = str_replace(array("\n","\r"),array("<nr/>","<rr/>"),strtolower($str));
	$arr1 = array('?','"','(',')','[',']','.','/',':','*','||');
	$arr2 = array('\?','\"','\(','\)','\[','\]','\.','\/','\:','.*?','(.*?)');
	//$str = str_replace(array("\n","\r"),array("<nr/>","<rr/>"),strtolower($str));
	return str_replace('\[$ppvod\]','([\s\S]*?)',str_replace($arr1,$arr2,$str));
}
//生成随机伪静态简介
function ff_rand_str($string){
    $arr = C('play_collect_content');
    //$all=mb_strlen($string,'utf-8');
	$all = iconv_strlen($string,'utf-8');
    $len = floor(mt_rand(0,$all-1));
    $str = msubstr($string,0,$len);
	$str2 = msubstr($string,$len,$all);
	return $str.$arr[array_rand($arr,1)].$str2;
}
//获取绑定分类对应ID值
function ff_bind_id($key){
	$bindcache = F('_xml/bind');
	return $bindcache[$key];
}
//自动TAG分词获取
function ff_tag_auto($title,$content){
	$data = ff_getfile('http://keyword.discuz.com/related_kw.html?ics=utf-8&ocs=utf-8&title='.rawurlencode($title).'&content='.rawurlencode(msubstr($content,0,500)));
	if($data) {
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $data, $values, $index);
		xml_parser_free($parser);
		$kws = array();
		foreach($values as $valuearray) {
			if($valuearray['tag'] == 'kw' || $valuearray['tag'] == 'ekw') {
				//$kws[] = !empty($chs) ? $chs->convert(trim($valuearray['value'])) : trim($valuearray['value']);
				$kws[] = trim($valuearray['value']);
			}
		}
		return implode(' ',$kws);
	}
	return false;
}
/*-------------------------------------------------飞飞系统栏目相关函数开始------------------------------------------------------------------*/
//通过栏目名名获取对应的栏目ID
function getlistid($str){
    $arr=list_search(F('_ppvod/list'),'list_name='.$str);
	if(empty($arr)){
		return 0;
	}else{
	    return $arr[0]['list_id'];
	}
}
//通过栏目ID获取对应的栏目名称/别名等
function getlistname($cid,$type='list_name'){
    $arr=list_search(F('_ppvod/list'),'list_id='.$cid);
	if(is_array($arr)){
		return $arr[0][$type];
	}else{
	    return '未知ID'.$cid;
	}
}
//通过栏目ID返回其它值数组方式
function getlistarr($cid,$type='list_id'){
    $tree=list_search(F('_ppvod/listvod'),'list_id='.$cid);
	if(!empty($tree[0]['son'])){
		foreach($tree[0]['son'] as $val){$param[]=$val[$type];}
		return $param;
	}else{
		return false;
	}
}
//检查是否没有小类
function getlistson($pid){
	$tree=list_search(F('_ppvod/listtree'),'list_id='.$pid);
	if(!empty($tree[0]['son'])){
		return false;
	}else{
	    return true;
	}
}
// 获取数据统计
function getcount($cid){
	$where=array();
	$where['vod_del']=0;
	if($cid>0){
		if(999==$cid){
			$time=time()-3600*24;
			$where['vod_addtime']=array('GT',$time);
		}else{
			$tree=list_search(F('_ppvod/listvod'),'list_id='.$cid);
			if(!empty($tree[0]['son'])){foreach($tree[0]['son'] as $val){$abc['cid'][]=$val['list_id'];}}else{$abc['cid']=$cid;}
			if($abc['cid']){$where['vod_cid']=ids($abc['cid']);}
		}
	}
	$rs=D("Home.Vod");
	$count=$rs->where($where)->count('vod_id');
	return $count+0;
}
//获取模块ID
function getsid($str){
	if('news'==$str){
		return 2;
	}else{
		return 1;
	}
}
//生成列表sql查询范围语句
function ids($l1){
	if(is_array($l1)){
		$l1 = implode(',', $l1);
	}
	if(strpos($l1, ',')!==false){
		return array('IN',''.$l1.'');
	}else{
		return $l1;
	}
}
/*-------------------------------------------------飞飞系统访问路径函数开始------------------------------------------------------------------*/
//获取静态模式栏目页文件结构(返回的值为buildHtml的文件名)
function getlisturl($cid,$page){
	if(1==c('url_dir_a')){//dir/id-1
		if(1==$page){$listurl=$cid.'/index';}else{$listurl=$cid.'-'.$page.'/index';}
	}elseif(2==c('url_dir_a')){//dir/name-1
		if(1==$page){$listurl=getlistname($cid,'list_dir').'/index';}else{$listurl=getlistname($cid,'list_dir').'-'.$page.'/index';}
	}elseif(3==c('url_dir_a')){//id
		$listurl=$cid.'_'.$page;
	}elseif(4==c('url_dir_a')){//dir_id
		$listurl=getlistname($cid,'list_dir').'_'.$page;
	}else{
		$listurl=md5($cid).'_'.$page;
	}
	return $listurl;
}
//获取静态模式内容页文件结构(返回的值为buildHtml的文件名)
function getdataurl($id,$cid,$vodname){
	if(1==C('url_dir_b')){//dir/id
		$readurl=C('url_voddata').$id.'/index';
	}elseif(2==C('url_dir_b')){//dir/md5(id)
		$readurl=C('url_voddata').md5($id).'/index';
	}elseif(4==C('url_dir_b')){//md5(id).html
		$readurl=C('url_voddata').md5($id);
	}elseif(5==C('url_dir_b')){//pinyin
		//$num=getvoddata($vodname,'([0-9]+)');
		//$let=getvoddata($vodname,'([A-Za-z]+)');
		//$readurl=ff_pinyin(utf82gb($vodname)).$num.$let;
		$readurl = ff_pinyin($vodname);
		if(empty($readurl)){$readurl=$vodname;};
		$readurl=getlistname($cid,'list_dir').'/'.$readurl.'/index';
	}else{//id.html
		$readurl=C('url_voddata').$id;
	}
	return $readurl;
}
//获取静态模式内容页文件结构带分页参数
function getdataurl_p($id,$page){
	if(1==c('url_dir_b')){
		if(1==$page){$readurl=$id.'/index';}else{$readurl=$id.'-'.$page.'/index';}
	}elseif(2==c('url_dir_b')){
		if(1==$page){$readurl=md5($id).'/index';}else{$readurl=md5($id).'-'.$page.'/index';}
	}elseif(4==c('url_dir_b')){
		$readurl=md5($id).'-'.$page;
	}else{
		$readurl=$id.'-'.$page;
	}
	return $readurl;
}
// 获取播放地址链接第一集
function getplayurl($vodid){
    if(C('url_html')==1||C('url_html')==2){
		$playurl=C('site_path').C('url_vodplay').$vodid.C('html_file_suffix').'?vod-play-id-'.$vodid.'-sid-0-pid-0'.C('html_file_suffix');
	}else{
	    $abc['id']=$vodid;$abc['sid']=0;$abc['pid']=0;
		$playurl=ppvodurl('Home-vod/play',$abc,'',false,true);
	}
	return $playurl;
}
// 获取多参数链接地址
function geturl($url,$str){
	$str=str_replace('/','-',$str);//htmlentities(urlencode($str)) target="_blank"
	$url=str_replace(C('url_html_suffix'),'',$url).'-'.$str.C('url_html_suffix');
	return $url;
}
//连惯sql查询语名
function ppvodsql($model,$field,$where,$order,$limit,$page){
	$rs=D("Home.".$model);
	if(!empty($page)){$array['count']=$rs->where($where)->count($model.'_id');};
	$array['list']=$rs->field($field)->where($where)->order($order)->limit($limit)->page($page)->select();
	//dump($rs->getLastSql());
	return $array;
}
//分页函数
function ppvodpage($currentPage,$totalPages,$halfPer=5,$url,$pagego){
    $linkPage .= ( $currentPage > 1 )
        ? '<a href="'.str_replace('{!page!}',1,$url).'" class="pagegbk">首页</a>&nbsp;<a href="'.str_replace('{!page!}',($currentPage-1),$url).'" class="pagegbk">上一页</a>&nbsp;' 
        : '<em>首页</em>&nbsp;<em>上一页</em>&nbsp;'; 
    for($i=$currentPage-$halfPer,$i>1||$i=1,$j=$currentPage+$halfPer,$j<$totalPages||$j=$totalPages;$i<$j+1;$i++){
        $linkPage .= ($i==$currentPage)?'<span>'.$i.'</span>&nbsp;':'<a href="'.str_replace('{!page!}',$i,$url).'">'.$i.'</a>&nbsp;'; 
    }
    $linkPage .= ( $currentPage < $totalPages )
        ? '<a href="'.str_replace('{!page!}',($currentPage+1),$url).'" class="pagegbk">下一页</a>&nbsp;<a href="'.str_replace('{!page!}',$totalPages,$url).'" class="pagegbk">尾页</a>'
        : '<em>下一页</em>&nbsp;<em>尾页</em>';
	
    return str_replace('-1/"','/"',$linkPage);
}
//$model,$arrayparams,$redirect,$suffix参考U函数;$indexphp=自由控制是否过滤index.php;C('url_html_suffix')
function ppvodurl($model,$params,$indexphp,$redirect=false,$suffix=false){
	return str_replace('Admin-','',str_replace('Home-','',str_replace($indexphp.'?s=/','?s=',U($model,$params,$redirect,$suffix))));
};
// 获取某图片的访问地址
function getpicurl($file){
	if(strpos($file,'http://')!==false){
		return $file;
	}else{
		$prefix=C('upload_http_prefix');
		if(!empty($prefix)){
			return $prefix.$file;
		}else{
			return C('site_path').C('upload_path').'/'.$file;
		}
	};
}
// 获取某图片的缩略图本地地址
function getpicurl_s($file){
	$prefix=C('upload_http_prefix');
	if(!empty($prefix)){
		return $prefix.$file;
	}else{
    	$file=strrchr($file,"/");
		return C('site_path').C('upload_path').'-s/'.$file;
	}	
}
// 获取26个字母链接
function getletter($file='vod',$str=''){
	if(C('url_html')==1){
		$index='index.html';
	}else{
		$index='index.php';
	}
    for($i=1;$i<=26;$i++){
	   $url=ppvodurl('Home-'.$file.'/search',array('id'=>chr($i+64),'x'=>'letter'),$index,false,true);
	   $str.='<a href="'.$url.'" class="pp_letter">'.chr($i+64).'</a>';
	}
	return $str;
}
// 主演带链接
function getactorurl($str,$type="actor"){
    $str=str_replace("/"," ",$str);
	$arr=explode(" ",$str);
	$restr='';
	foreach($arr as $key=>$val){
	    $restr=$restr.'<a href="'.C('site_path').'index.php?s=vod-search-id-'.urlencode($val).'-x-'.$type.'" target="_blank">'.$val.'</a> ';
	}
	return $restr;	
}
// 获取广告调用地址
function getadsurl($str,$charset="utf-8"){
	return '<script type="text/javascript" src="'.C('site_path').'Public/ads/'.$str.'.js" charset="'.$charset.'"></script>';
}

/*-------------------------------------------------模板常用函数-----------------------------------------------------------------*/
//读出qvod地址的文件名
function getqvodname($jiname,$server,$playname){
	$jiname = getvoddata($jiname,'3$$$(.*)\|(.*)\|(.*)\.(.*)\|');
	return $jiname;
}
//积分效果
function getjifen($fen){
	$array = explode('.',$fen);
	return '<strong>'.$array[0].'</strong>.'.$array[1];
}
//获得某天前的时间戳
function getxtime($day){
	$day = intval($day);
	return mktime(23,59,59,date("m"),date("d")-$day,date("y"));
}
// 获取标题颜色
function getcolor($str,$color){
	if(empty($color)){
	    return $str;
	}else{
	    return '<font color="'.$color.'">'.$str.'</font>';
	}
}
// 获取时间颜色
function getcolordate($type='Y-m-d H:i:s',$time,$color='red'){
	if((time()-$time)>86400){
	    return date($type,$time);
	}else{
	    return '<font color="'.$color.'">'.date($type,$time).'</font>';
	}
}

/*---------------------------------------ThinkPhp扩展函数库开始------------------------------------------------------------------*/
// 获取客户端IP地址
function get_client_ip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return($ip);
}
/**
 +----------------------------------------------------------
 * 检查字符串是否是UTF8编码
 +----------------------------------------------------------
 * @param string $string 字符串
 +----------------------------------------------------------
 * @return Boolean
 +----------------------------------------------------------
 */
function is_utf8($string){
	return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}
//输出安全的html
function h($text, $tags = null){
	$text	=	trim($text);
	//完全过滤注释
	$text	=	preg_replace('/<!--?.*-->/','',$text);
	//完全过滤动态代码
	$text	=	preg_replace('/<\?|\?'.'>/','',$text);
	//完全过滤js
	$text	=	preg_replace('/<script?.*\/script>/','',$text);

	$text	=	str_replace('[','&#091;',$text);
	$text	=	str_replace(']','&#093;',$text);
	$text	=	str_replace('|','&#124;',$text);
	//过滤换行符
	$text	=	preg_replace('/\r?\n/','',$text);
	//br
	$text	=	preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
	$text	=	preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
	//过滤危险的属性，如：过滤on事件lang js
	while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1],$text);
	}
	while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].$mat[3],$text);
	}
	if(empty($tags)) {
		$tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
	}
	//允许的HTML标签
	$text	=	preg_replace('/<('.$tags.')( [^><\[\]]*)>/i','[\1\2]',$text);
	//过滤多余html
	$text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
	//过滤合法的html标签
	while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
	}
	//转换引号
	while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
		$text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
	}
	//过滤错误的单个引号
	while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
		$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
	}
	//转换其它所有不合法的 < >
	$text	=	str_replace('<','&lt;',$text);
	$text	=	str_replace('>','&gt;',$text);
	$text	=	str_replace('"','&quot;',$text);
	 //反转换
	$text	=	str_replace('[','<',$text);
	$text	=	str_replace(']','>',$text);
	$text	=	str_replace('|','"',$text);
	//过滤多余空格
	$text	=	str_replace('  ',' ',$text);
	return $text;
}
/**
 +----------------------------------------------------------
 * 把返回的数据集转换成Tree
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0){
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**
 +----------------------------------------------------------
 * 对查询结果集进行排序
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}
/**
 +----------------------------------------------------------
 * 在数据列表中搜索
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
//获取模板编辑名称
function gettplname($filename){
	if('pp_footer.html'==$filename){
	    return '底部公用模板';
	}elseif('pp_header.html'==$filename){
	    return '顶部公用模板';
	}elseif('pp_index.html'==$filename){
	    return '网站首页模板';
	}elseif('pp_mapbaidu.html'==$filename){
	    return 'Baidu地图模板';
	}elseif('pp_mapgoogle.html'==$filename){
	    return 'Google地图模板';
	}elseif('pp_maprss.html'==$filename){
	    return 'Rss地图模板';
	}elseif('pp_mapsite.html'==$filename){
	    return 'Html地图模板';
	}elseif('pp_news.html'==$filename){
	    return '新闻模块内容模板';
	}elseif('pp_newslist.html'==$filename){
	    return '新闻模块栏目列表模板';
	}elseif('pp_newssearch.html'==$filename){
	    return '新闻模块搜索模板';
	}elseif('pp_play.html'==$filename){
	    return '播放页模板';
	}elseif('pp_vod.html'==$filename){
	    return '视频模块内容模板';
	}elseif('pp_vodlist.html'==$filename){
	    return '视频模块栏目列表模板';
	}elseif('pp_vodchannel.html'==$filename){
	    return '视频模块栏目列表大类模板';
	}elseif('pp_vodsearch.html'==$filename){
	    return '视频模块搜索模板';
	}elseif('pp_cm.html'==$filename){
	    return '评论模板';
	}elseif('pp_gb.html'==$filename){
	    return '留言模板';
	}elseif('pp_tag.html'==$filename){
	    return '标签关键字模板';
	}elseif('pp_tagvod.html'==$filename){
	    return '视频标签模板';
	}elseif('pp_tagnews.html'==$filename){
	    return '新闻标签模板';
	}else{
	    return false;
	}
}
?>