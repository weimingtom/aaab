<?php
/**
 *---------------------ģ�������----------------------
 */
class templateClass
{
	var $var_arr;              //����������
	
	var $tpl_dir  = 'tpl/';    //ģ������Ŀ¼
	
	var $tpl_ext  = '.php';   //ģ���ļ���׺
	
	/**   
	  *---------���췽��---------------*
	 */
	function __construct($tpl_dir ='')
	{
		if($tpl_dir != ''){
			
			$this -> tpl_dir  = $tpl_dir;
			
		}
		
	}
	
	
	/**   
	  *---------ģ��������ʾ---------------*
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
			echo $file.'ģ���ļ�������!';
			return false;
		}
		
	}
	
	/**   
	  *---------���ݱ�������---------------*
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
	
}
/*------------------ʵ��-------------------------*/
/*
$asp = '����ASP';
$php = '����php';
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
			echo $this -> new_url.$this -> new_file."���ɳɹ�!<br />";
			$this -> type = true;
		}else{
			echo "<span class=\"red\">".$this -> new_url.$this -> new_file."����ʧ��!</span><br />";
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
				
				/*�ر��ļ�*/
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
			echo $this -> new_url.$this -> new_file."���ɳɹ�!<br />";
		}else{
			echo "<span class=\"red\">".$this -> new_url.$this -> new_file."����ʧ��!</span><br />";
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
				
				/*�ر��ļ�*/
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
}

?>