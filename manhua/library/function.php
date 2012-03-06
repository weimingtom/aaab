<?php
function getgpc($k, $var='G') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
		case 'V': $var = &$_SERVER; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

function mkDirs($path)
{
	$array_path = explode("/",$path);

	$_path = "";
		
	for($i=0;$i<count($array_path);$i++)
	{
		$_path .= $array_path[$i]."/";

		if(!empty($array_path[$i]) && !file_exists($_path))
		{
			if( !empty($_path) )
			{
				mkdir($_path,0777);
			}
		}
	}

	return true;
}

function replacecode($getStr)
{
	$getStr = str_ireplace(array("\r","\n","\t"),"",$getStr);

	$getStr = preg_replace( '@<!--(.+?)-->@is', '', $getStr );

	$getStr = preg_replace( '@\/\*(.+?)\*\/@is', '', $getStr );

	$getStr = preg_replace( '/\s+/', ' ', $getStr );
	
	return $getStr;
}

function getServerPhoto($referer, $imgPath)
{
	header("content-type:image/jpeg");
	$opts = array('http' => array('header'=>"Referer: $referer/\r\n"));
	$context = stream_context_create($opts);
	$fileResource = @file_get_contents('$imgPath', FALSE, $context);	
	return $fileResource;
}

/**
 * 保存远程图片至本地
 *
 * @param unknown_type $Referer
 * @param unknown_type $imgurl
 * @param unknown_type $savepath
 */
function saveimg($Referer, $imgurl, $savepath)
{		
	$opts = array('http' => array('header'=>"Referer: $Referer/\r\n"));
	$context = stream_context_create($opts);
	$fileResource = @file_get_contents($imgurl, FALSE, $context);		
	$i = 1;
	while (!$fileResource)
	{
		$i++;
		if($i > 10 ) break; 
		$fileResource = @file_get_contents($imgurl, FALSE, $context);
	}
	if(!empty($fileResource))
	{
		if (! @ file_put_contents($savepath, $fileResource))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

function daddslashes($string, $force = 0, $strip = FALSE)
{
	if (! MAGIC_QUOTES_GPC || $force)
	{
		if (is_array ( $string ))
		{
			foreach ( $string as $key => $val )
			{
				$string [$key] = daddslashes ( $val, $force, $strip );
			}
		}
		else
		{
			$string = addslashes ( $strip ? stripslashes ( $string ) : $string );
		}
	}
	return $string;
}

function cutstr($string, $length, $dot = ' ...')
{
	if (strlen ( $string ) <= $length)
	{
		return $string;
	}
	
	$string = str_replace ( array ('&amp;', '&quot;', '&lt;', '&gt;' ), array ('&', '"', '<', '>' ), $string );
	$strcut = '';

	if (TRUE)
	{
		$n = $tn = $noc = 0;
		
		while ( $n < strlen ( $string ) )
		{			
			$t = ord ( $string [$n] );
			if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126))
			{
				$tn = 1;
				$n ++;
				$noc ++;
			} elseif (194 <= $t && $t <= 223) {
				$tn = 2;
				$n += 2;
				$noc += 2;
			} elseif (224 <= $t && $t < 239) {
				$tn = 3;
				$n += 3;
				$noc += 2;
			} elseif (240 <= $t && $t <= 247) {
				$tn = 4;
				$n += 4;
				$noc += 2;
			} elseif (248 <= $t && $t <= 251) {
				$tn = 5;
				$n += 5;
				$noc += 2;
			} elseif ($t == 252 || $t == 253) {
				$tn = 6;
				$n += 6;
				$noc += 2;
			} else {
				$n ++;
			}
			
			if ($noc >= $length)
			{
				break;
			}			
		}
		
		if ($noc > $length)
		{
			$n -= $tn;
		}
					
		$strcut = substr ( $string, 0, $n );
	
	}
	else
	{
		for($i = 0; $i < $length; $i ++)
		{
			$strcut .= ord ( $string [$i] ) > 127 ? $string [$i] . $string [++ $i] : $string [$i];
		}
	}
		
	$strcut = str_replace ( array ('&', '"', '<', '>' ), array ('&amp;', '&quot;', '&lt;', '&gt;' ), $strcut );		
	return $strcut . $dot;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
	$ckey_length = 0;

	$key = md5($key ? $key : KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++)
	{
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++)
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++)
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE')
	{
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
		{
			return substr($result, 26);
		}
		else
		{
			return '';
		}
	}
	else
	{
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function sid_encode($username, $exp = '')
{
	$ip = substr(KEY, 0, 10);
	$authkey = md5($ip.KEY);
	$check = substr(md5($ip), 0, 8);
	return rawurlencode(authcode("$username\t$check", 'ENCODE', $authkey, $exp));
}

function sid_decode($sid, $exp = '')
{
	$ip = $ip = substr(KEY, 0, 10);
	$authkey = md5($ip.KEY);
	$s = authcode(rawurldecode($sid), 'DECODE', $authkey, $exp);
	
	if(empty($s))
	{
		return false;
	}
	
	@list($username, $check) = explode("\t", $s);
	
	if($check == substr(md5($ip), 0, 8))
	{
		return $username;
	}
	else
	{
		return false;
	}
}

function Auth()
{
	$GroupName = array(
				array("Name" => "管理员", "Auth" => ""),
				array("Name" => "主编", "Auth" => ""),
				array("Name" => "开发人员", "Auth" => ""),
				array("Name" => "网址编辑", "Auth" => ""),
				array("Name" => "内容编辑", "Auth" => "")
			);
		
	return $GroupName;
}

//$num=总页数, $perpage=每页显示数量，$curpage=当前所在页，$mpurl=页面链接
function page($num, $perpage, $curpage, $mpurl)
{ 	
	$multipage = '';
	$mpurl .= strpos ( $mpurl, '?' ) ? '&' : '?';
	
	if ($num > $perpage)
	{
		$page = 10;
		$offset = 2;			
		$pages = @ceil ( $num / $perpage );
				
		if ($page > $pages)
		{
			$from = 1;
			$to = $pages;
		}
		else
		{
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if ($from < 1)
			{
				$to = $curpage + 1 - $from;
				$from = 1;
				if ($to - $from < $page) $to = $page;				
			}
			elseif ($to > $pages)
			{
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		
		$simple = '0';
		$realpages = $pages;
		$multipage = ($curpage > 1 ? '<a href="' . $mpurl . 'page=' . ($curpage - 1) . '" class="prev">上一页</a>' : '') . ($curpage - $offset > 1 && $pages > $page ? '<a href="' . $mpurl . 'page=1" class="first">首页</a>' : '');
		
		for($i = $from; $i <= $to; $i ++)
		{
			$multipage .= $i == $curpage ? '<strong>' . ($i < 10 ? '0' . $i : $i) . '</strong>' : '<a href="' . $mpurl . 'page=' . $i . ($i == $pages ? '' : '') . '">' . ($i < 10 ? '0' . $i : $i) . '</a>';
		}
				
		$multipage .= ($to < $pages ? '<a href="' . $mpurl . 'page=' . $pages . '" class="last">尾页</a>' : '') . ($curpage < $pages  ? '<a href="' . $mpurl . 'page=' . ($curpage + 1) . '" class="next">下一页</a>' : '') . (! $simple && $pages > $page ? $perpage . '个作品/页&nbsp;<kbd>转到第<input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\'' . $mpurl . 'page=\'+this.value; return false;}" />页</kbd>' : '');
		$multipage = $multipage ? "<div class=\"pages\">总共有 <b>{$num}</b> 个作品&nbsp;" . $multipage . '</div>' : '';
	}
	
	return $multipage;
}

//样式二 $num=总页数, $perpage=每页显示数量，$curpage=当前所在页，$mpurl=页面链接
function page2($num, $perpage, $curpage, $mpurl) { 	
	$multipage = '';
	$mpurl .= strpos ( $mpurl, '?' ) ? '&' : '?';
	
	if ($num > $perpage)
	{
		$page = 10;
		$offset = 2;			
		$pages = @ceil ( $num / $perpage );
				
		if ($page > $pages)
		{
			$from = 1;
			$to = $pages;
		}
		else
		{
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if ($from < 1)
			{
				$to = $curpage + 1 - $from;
				$from = 1;
				if ($to - $from < $page) $to = $page;				
			}
			elseif ($to > $pages)
			{
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		
		$simple = '0';
		$realpages = $pages;
		$multipage .= "<p>共<strong>$num</strong>篇，当前显示<strong>".(($curpage-1)*$perpage+1)."-".(($curpage==$pages ? $num : ($curpage-1)*$perpage+$perpage))."</strong>篇 &nbsp;</p>";
		$multipage .= "<p>";
		$multipage .= ($curpage > 1 ? '<a href="' . $mpurl . 'page=' . ($curpage - 1) . '">上一页</a>' : '') . ($curpage - $offset > 1 && $pages > $page ? '<a href="' . $mpurl . 'page=1" >1</a>...' : '');
		for($i = $from; $i <= $to; $i ++) {
			$multipage .= $i == $curpage ? '<span class="CurrentPage">' . ($i < 10 ? '0' . $i : $i) . '</span>' : '<a href="' . $mpurl . 'page=' . $i . ($i == $pages ? '' : '') . '">' . ($i < 10 ? '0' . $i : $i) . '</a>';
		}
		$multipage .= ($to < $pages ? '...<a href="'.$mpurl.'page='.$pages.'">'.$pages.'</a>' : '') . ($curpage < $pages  ? '<a href="'.$mpurl.'page='.($curpage + 1).'">下一页</a>' : '');
		$multipage .= "</p>";
	}
	return $multipage;
}

// $page=当前所在页, $app=每页显示数量，$totalnum=总记录数
function page_get_start($page, $ppp, $totalnum)
{
	$totalpage = ceil ( $totalnum / $ppp );
	$page = max ( 1, min ( $totalpage, intval ( $page ) ) );
	return ($page - 1) * $ppp;
}

function num2chs($num)
{ 
	$unit = array('', '十', '百', '千', '', '万', '亿', '兆'); 
	$char = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
	$num = rtrim(floatval($num), '0');//取出小数位的最后一个0
	if(empty($num)) { 
		return $char[0];//如果是单数 0 返回 零
	} 
	$arr = explode('.', $num);
	//分隔为整数和小数 $num = strrev($arr[0]);
	//翻转整数位 $len = strlen($num); 
	for ($i = 0; $i < $len; $i++)
	{ 
		$int[$i] = $char[$num[$i]];
		//汉字数字 
		if (!empty($num[$i])) { 
			$int[$i] .= $unit[$i%4];//单位 } 
			if ($i%4 == 0) 
			{//每四位之后有一个大的单位 $int[$i] .= $unit[4+floor($i/4)]; }
			} 
		}
	}
	//小数的汉字 $dec = isset($arr[1]) ? '点'.str_replace(range(0, 9), $char, $arr[1]) : ''; 
	return implode('', array_reverse($int)).$dec;
} 

?>