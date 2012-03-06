<?php
class Base 
{
	public $header = array('','');
	public $onlineip;
	public $time;
	
	private $inajax;
	
	function __construct() {
		$this->init_var();
	}
	
	function _alert($alertInfo, $url='', $open = true) {
		if(!$url) {
			$url = $_SERVER['REQUEST_URI'];
		}
		
		if($open) {
			exit('<script>alert("'.$alertInfo .'"); location.href="'. $url .'";</script>');
		} else {
			exit('<script>location.href="'. $url .'";</script>');
		}
	}
	
	private function init_var()
	{
		$this->time = time();
		$cip = getenv('HTTP_CLIENT_IP');
		$xip = getenv('HTTP_X_FORWARDED_FOR');
		$rip = getenv('REMOTE_ADDR');
		$srip = $_SERVER['REMOTE_ADDR'];
		
		if ($cip && strcasecmp ( $cip, 'unknown' ))
			$this->onlineip = $cip;
		
		elseif ($xip && strcasecmp ( $xip, 'unknown' ))
			$this->onlineip = $xip;
		
		elseif ($rip && strcasecmp ( $rip, 'unknown' ))
			$this->onlineip = $rip;
		
		elseif ($srip && strcasecmp ( $srip, 'unknown' ))
			$this->onlineip = $srip;
			
		preg_match ( "/[\d\.]{7,15}/", $this->onlineip, $match );
		$this->onlineip = $match [0] ? $match [0] : 'unknown';
		
		$this->header['title'] = GODHOUSE_SITENAME;
		$this->header['keyword'] = GODHOUSE_HEADER_KEYWORK;
		$this->header['description'] = GODHOUSE_HEADER_DESCRIPTION;
		
		$this->inajax = getgpc('inajax');
	}
	
	// 改方法为公共方法，可以从外部直接调用。主要还有一个功能为AJAX跨域调用
	public function message($message = '', $redirect = '', $extra = '') {
		$inajax = getgpc('inajax');
		$inajax == 1 && getgpc('message') && $message = stripslashes(getgpc('message'));
		empty($inajax) && strpos($message, '<error>') !== FALSE && $message = preg_replace("/\<error\>\w+\<\/error\>\<message\>(.+?)\<\/message\>/", "\\1", $message);

		// 清理js设置的客户端缓存数据
		$extra = $extra == 'CLEARDATA' ? '<script>setdata("message", "");</script>' : '';
		$this->view->assign('extra', $extra);
		$this->view->assign('message', $message);
		$this->view->assign('redirect', $redirect);
		$this->display("ajax_message");
		/*if(!getgpc('inajax')) {
			echo '<script>if(document.getElementById("debug_time"))document.getElementById("debug_time").innerHTML = "'.substr((microtime(1) - $_SERVER['starttime']), 0, 6).'";</script>';
		}*/
		exit;
	}
	
	public function __get($var) {
		if($var == 'db') {
			global $db;
			if($db) {
				 $this->db = $db;
			} else {
				require_once ROOT_PATH .'/library/db.php';
				$this->db = new db( );
				$this->db->connect(DBHOST, DBUSER, DBPW, DBNAME, DBCHAR );
			}
		} else if($var == 'view') {
			
			require ROOT_PATH.'/library/template.php';
			$this->view = new template();
			$this->view->assign('inajax', $this->inajax);
			
		} else if(strtolower(substr($var, -5)) == 'model') {
			$model = ucfirst(substr($var, 0, -5));
			require ROOT_PATH."/application/models/{$model}.php";
			$this->$var = new $model($this);
		}
		return $this->$var;
	}
	
	protected function display($page)
	{
		$this->view->assign('header', $this->header);
		$this->view->display($page);
	}
	
	protected function redirect($url) {
		$url = $this->createUrl($url);
		exit('<script>location.href="'.$url.'";</script>');
	}
	
	// 转换URL
	protected function createUrl($str) {
		$url = GODHOUSE_DOMAIN_WWW;
		if(GODHOUSE_REWRITEENGINE) {
			if($str{0} == '/') {
				$url = $str.'.htm';
			} else {
				$url = '/'.$str.'.htm';
			}
		} else {
			$url = 'admin.php?r='.$str;
		}
		return $url;
	}
}

?>