<?php
// +----------------------------------------------------------------------
// | ThinkSNS
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: nonant <nonant@thinksns.com>
// +----------------------------------------------------------------------
//

/**
 * 短地址服务
 */
class ShortUrlService extends Service{

	/**
	 * 获取给定URL的短地址
	 * @param string $url 原URL地址
	 * @return string 短URL地址
	 */
	function getShort($url) {
		return $this->getGoogleUrl( $url );
		
		$shorturl = model('Xdata')->lget('shorturl');
		$type = ($shorturl['shorturl_type']) ? $shorturl['shorturl_type'] : 'google';
		switch ( $type ) {
			case 'close':
				return $url;
				break;
				
			case 'google':
				return $this->getGoogleUrl( $url );
				break;
				
			case 'thinksns':
				return $this->getCustomizeUrl( 'http://tsurl.cn/api.php?url=' , $url );
				break;
				
			case 'customize':
				return $this->getCustomizeUrl( $shorturl['customize_url'] , $url );
				break;
				
			default:
				return $this->getGoogleUrl( $url );
				break;
		}
	}
	
	function getCustomizeUrl( $apiurl , $url ){
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $apiurl );   //goo.gl api url
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'user=toolbar@google.com&url='.urlencode( $url ).'&auth_token='.$this->googlToken($url)); 
		$saida = curl_exec($curl); 
		curl_close($curl);
		if( $saida ){
			return $saida;
		}else{
			return $url;
		}
	}
	
	function getGoogleUrl( $url ){
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, 'http://goo.gl/api/url');   //goo.gl api url
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'user=toolbar@google.com&url='.urlencode( $url ).'&auth_token='.$this->googlToken($url)); 
		$saida = curl_exec($curl); 
		curl_close($curl);
		
		if($saida){
		    $json = json_decode($saida);
		    return $json->short_url?$json->short_url:$url;    //result
		}else {
			return $url;
		}
	}
	
	//goo.gl token
	function googlToken($b){
	    $i = $this->tke($b);
	    $i = $i >> 2 & 1073741823;
	    $i = $i >> 4 & 67108800 | $i & 63;
	    $i = $i >> 4 & 4193280 | $i & 1023;
	    $i = $i >> 4 & 245760 | $i & 16383;
	    $j = "7";
	    $h = $this->tkf($b);
	    $k = ($i >> 2 & 15) << 4 | $h & 15;
	    $k |= ($i >> 6 & 15) << 12 | ($h >> 8 & 15) << 8;
	    $k |= ($i >> 10 & 15) << 20 | ($h >> 16 & 15) << 16;
	    $k |= ($i >> 14 & 15) << 28 | ($h >> 24 & 15) << 24;
	    $j .= $this->tkd($k);
	    return $j;
	}

	function tkc(){
	    $l = 0;
	    foreach (func_get_args() as $val) {
	        $val &= 4294967295;
	        $val += $val > 2147483647 ? -4294967296 : ($val < -2147483647 ? 4294967296 : 0);
	        $l   += $val;
	        $l   += $l > 2147483647 ? -4294967296 : ($l < -2147483647 ? 4294967296 : 0);
	    }
	    return $l;
	}
	
	function tkd($l){
	    $l = $l > 0 ? $l : $l + 4294967296;
	    $m = "$l";  //must be a string
	    $o = 0;
	    $n = false;
	    for($p = strlen($m) - 1; $p >= 0; --$p){
	        $q = $m[$p];
	        if($n){
	            $q *= 2;
	            $o += floor($q / 10) + $q % 10;
	        } else {
	            $o += $q;
	        }
	        $n = !$n;
	    }
	    $m = $o % 10;
	    $o = 0;
	    if($m != 0){
	        $o = 10 - $m;
	        if(strlen($l) % 2 == 1){
	            if ($o % 2 == 1){
	                $o += 9;
	            }
	            $o /= 2;
	        }
	    }
	    return "$o$l";
	}

	function tke($l){
	    $m = 5381;
	    for($o = 0; $o < strlen($l); $o++){
	        $m = $this->tkc($m << 5, $m, ord($l[$o]));
	    }
	    return $m;
	}

	function tkf($l){
	    $m = 0;
	    for($o = 0; $o < strlen($l); $o++){
	        $m = $this->tkc(ord($l[$o]), $m << 6, $m << 16, -$m);
	    }
	    return $m;
	}
	
	//运行服务，系统服务自动运行
	public function run(){

	}
		
	//启动服务，未编码
	public function _start(){
		return true;
	}

	//停止服务，未编码
	public function _stop(){
		return true;
	}

	//安装服务，未编码
	public function _install(){
		return true;
	}

	//卸载服务，未编码
	public function _uninstall(){
		return true;
	}
}
?>