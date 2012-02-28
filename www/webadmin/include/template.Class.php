<?php
/**
 *---------------------模板操作类----------------------
 */
class templateClass
{
	var $var_arr;              //变量名数组
	
	var $tpl_dir  = 'tpl/';    //模板所在目录
	
	var $tpl_ext  = '.php';   //模板文件后缀
	
	/**   
	  *---------构造方法---------------*
	 */
	function __construct($tpl_dir ='')
	{
		if($tpl_dir != ''){
			
			$this -> tpl_dir  = $tpl_dir;
			
		}
		
	}
	
	
	/**   
	  *---------模板编译后显示---------------*
	 */
	function display($file)
	{
		$arr = $this -> var_arr;
		if(is_array($arr)){
			foreach($arr as $value){
				global $$value;
			}
		}else{
			global $$arr;
		}

		$tpl_file = $this -> tpl_dir.$file.$this -> tpl_ext;
		if(file_exists($tpl_file)){
			include $tpl_file;
		}else{
			echo $file.'模板文件不存在!';
			return false;
		}
		
	}
	
	/**   
	  *---------传递变量数组---------------*
	 */
	function assign($arr){
		$this -> var_arr = $arr;
	}
	
	function createFolder($path){
		if(!file_exists($path)){
			$this -> createFolder(dirname($path));
			mkdir($path,0777);
		}
	}
	
	function tourl($url){
		if(function_exists('curl_init')) {
			$ch = curl_init();//产生一个会话
			$timeout=10000000;//下用
			curl_setopt($ch, CURLOPT_URL, $url);//获取一个url
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//获取的输出的文本流
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//指定最多的HTTP重定向的数量
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//制定页面获取超时时间
			$output = curl_exec($ch);//执行
			curl_close($ch);//关闭一打开的会话
			return $output;//返回这个读取的文本流
		}else{
			$output = @file_get_contents($url);
			return $output;
		}
	}
	
}
/*------------------实例-------------------------*/
/*
$asp = '测试ASP';
$php = '测试php';
$p = new templateClass();
$p -> assign(array('asp'));
$p -> display('test');
*/

class geturl
{
	var $orl_file;
	var $new_url;
	var $new_file;
	var $type = false;
	
	function __construct($orl_file,$new_url,$new_file)
	{
		$this -> orl_file = $orl_file;
		$this -> new_url = $new_url;
		$this -> new_file = $new_file;
		if($this -> htmlwrite()){
			echo $this -> new_url.$this -> new_file."生成成功!<br />";
			$this -> type = true;
		}else{
			echo "<span class=\"red\">".$this -> new_url.$this -> new_file."生成失败!</span><br />";
		}
	}
	
	function type(){
		return $this -> type;
	}
	
	function htmlwrite(){
		//$content = @file_get_contents($this -> orl_file);
		//if(!$content) 
		$content = $this -> tourl($this -> orl_file);
		if($content){
		
			if($fp=@fopen($this -> createFolder($this -> new_url).$this -> new_file,"w")){
			
				fwrite($fp,$content);
				
				/*关闭文件*/
				fclose($fp);
				
				return true;
				
			}else{
			
				return false;
				
			}
		}else{
		
			return false;
			
		}
	}
	
	function createFolder($path){
	
		if(!file_exists($path)){
		
			$this -> createFolder(dirname($path));
			
			mkdir($path,0777);
			
		}
		return $path;
	}
	
	function tourl($url){
		if(function_exists('curl_init')) {
			$ch = curl_init();//产生一个会话
			$timeout=10000000;//下用
			curl_setopt($ch, CURLOPT_URL, $url);//获取一个url
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//获取的输出的文本流
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//指定最多的HTTP重定向的数量
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//制定页面获取超时时间
		
			$output = curl_exec($ch);//执行
		
			curl_close($ch);//关闭一打开的会话
		
			return $output;//返回这个读取的文本流
		}else{
			$output = @file_get_contents($url);
			return $output;
		}
	}
}



class subfile
{
	var $orl_file;
	var $new_url;
	var $new_file;
	var $strarr;
	
	function __construct($orl_file,$strarr,$new_url,$new_file)
	{
		$this -> orl_file = $orl_file;
		$this -> new_url = $new_url;
		$this -> new_file = $new_file;
		$this -> strarr = $strarr;
		if($this -> htmlwrite()){
			echo $this -> new_url.$this -> new_file."生成成功!<br />";
		}else{
			echo "<span class=\"red\">".$this -> new_url.$this -> new_file."生成失败!</span><br />";
		}
	}
	
	function htmlwrite(){
		$content = $this -> tourl($this -> orl_file);
		if($content){
			if(is_array($this -> strarr)){
				foreach($this -> strarr as $k => $v){
					$old_arr[] = "{".$k."}";
					$new_arr[] = $v;
				}
				$content = str_replace($old_arr,$new_arr,$content);
			}
			if($fp=@fopen($this -> createFolder($this -> new_url).$this -> new_file,"w")){
			
				fwrite($fp,$content);
				
				/*关闭文件*/
				fclose($fp);
				
				return true;
				
			}else{
			
				return false;
				
			}
		}else{
		
			return false;
			
		}
	}
	
	function createFolder($path){
	
		if(!file_exists($path)){
		
			$this -> createFolder(dirname($path));
			
			mkdir($path,0777);
			
		}
		return $path;
	}
	
	function tourl($url){
		if(function_exists('curl_init')) {
			$ch = curl_init();//产生一个会话
			$timeout=10000000;//下用
			curl_setopt($ch, CURLOPT_URL, $url);//获取一个url
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//获取的输出的文本流
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//指定最多的HTTP重定向的数量
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//制定页面获取超时时间
		
			$output = curl_exec($ch);//执行
		
			curl_close($ch);//关闭一打开的会话
		
			return $output;//返回这个读取的文本流
		}else{
			$output = @file_get_contents($url);
			return $output;
		}
	}
}

?>