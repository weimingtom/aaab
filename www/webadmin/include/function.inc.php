<?php
function msg($msg,$url,$s=5)
{
		$str .= "  <div id=\"msg\" class=\"layer_msg\">\n";
		$str .= "    <div class=\"msg_content\">$msg</div>\n";
		if(is_numeric($url)){
			$str .= '    <script language=javascript>setTimeout("history.go(-1)",'.($s*1000).');</script>';
			$str .= "    <p><a href=\"javascript:history.go(-1);\">������������û���Զ���ת,��������</a></p>\n";
		}else{
			$str .= "    <meta http-equiv=\"refresh\" content=\"$s;url=$url\">\n";
			$str .= "    <p><a href=\"$url\">������������û���Զ���ת,��������</a></p>\n";
		}
		$str .= "  </div>\n";
		exit($str."</div>\n</body>\n</html>");
		
}

// ��ȡ�ַ����ĳ��Ⱥ���
function csubstr($str,$start,$len)
{ 
	$strlen = strlen($str); 
	$clen = 0; 
	$tmpstr = '';
	for($i=0;$i<$strlen;$i++,$clen++)
	{ 
		if ($clen >= $start + $len) break; 
		if(ord(substr($str,$i,1)) > 0xa0){ 
			if ($clen >= $start) $tmpstr .= substr($str,$i,2); 
			$i++; 
		}else { 
			if ($clen >= $start) $tmpstr .= substr($str,$i,1); 
		} 
	} 
	return $tmpstr; 
} 

function str_len($str, $len, $type)
{ 
	$tempstr = csubstr($str, 0, $len); 
	if ($str <> $tempstr){
		if($type <> 1) $tempstr .= "..."; //Ҫ��ʲô��β,�޸�����Ϳ���.
	}
	return $tempstr; 
}

function str($str){
	return addslashes($str);
}

function glsql($str)
{
	$keyarr = array("and","execute","update","count","chr","mid","master","truncate","char","declare","select","create","delete","insert","'",'"'," ","or","=","%20");
	$str = str_replace($keyarr,"",$str);
	return addslashes($str);
}

function numeric($id) //
{
	if (strlen($id)>0){
    		if (!ereg("^[0-9]+$",$id)){
				$id = 1;
    		}else{
				$id = substr($id,0,11);
 			}
		}else{
			$id = "";
		}
		return $id;
}


function isdate($str,$format="Y-m-d"){

    $unixTime  = strtotime($str);
	
    $checkDate = date($format,$unixTime);
	
    if($checkDate==$str){
	
        return true;
		
    }else{
	
        return false;
		
	}
}


function delfilename($file_name){
	if(file_exists($file_name)){
		if(!is_dir($file_name)){
			unlink($file_name);
			echo "�ɹ�ɾ���ļ�:".$file_name;
		}else{
			if($handle=opendir($file_name)){
				while(false!==($files=readdir($handle))){
					if($files!="."&&$files!="..")
					{
						$arrfile=$file_name."/".$files;
						unlink($arrfile);
						echo $arrfile."<br>";
					}
				}
				closedir($handle);
				rmdir($file_name);
			}
		}
	}
}


function select($id,$arr,$default){
	$str = '<select name="'.$id.'" id="'.$id.'">';
	if(is_array($arr)){
		foreach($arr as $k => $v){
			if($k == $default){
				$str .= '  <option value="'.$k.'" selected="selected">'.$arr[$k].'</option>'."\n";
			}else{
				$str .= '  <option value="'.$k.'">'.$arr[$k].'</option>'."\n";
			}
		}
	}
	$str .= '</select>'."\n";
	return $str;
}

//��ȡ��ǰ��½�û�IP
function get_ip(){
	if ($_SERVER['REMOTE_ADDR']){
		$ip = $_SERVER['REMOTE_ADDR'];
	}elseif (getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	} elseif (getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	} else {
		$ip = "δ֪";
	}
return $ip;
}

function str_replace_once($needle, $replace, $haystack) {//ֻ�滻һ���ַ���
	$pos = strpos($haystack, $needle);
	if ($pos === false) {
		return $haystack;
	}
	return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function tourl($url){
	if(function_exists('curl_init')) {
		$ch = curl_init();//����һ���Ự
		$timeout=10000000;//����
		curl_setopt($ch, CURLOPT_URL, $url);//��ȡһ��url
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//��ȡ��������ı���
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//ָ������HTTP�ض��������
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//�ƶ�ҳ���ȡ��ʱʱ��
	
		$output = curl_exec($ch);//ִ��
	
		curl_close($ch);//�ر�һ�򿪵ĻỰ
	
		return $output;//���������ȡ���ı���
	}else{
		$output = @file_get_contents($url);
		return $output;
	}
}
?>