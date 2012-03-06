<?php
class template
{
	private $tpldir;
	private $objdir;
	private $tplfile;
	private $objfile;
	private $vars;
	private $force = 0;
	private $var_regexp = "\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*";
	private $vtag_regexp = "\<\?=(\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)\?\>";
	private $const_regexp = "\{([\w]+)\}";

	function __construct()
	{
		$this->template();
	}

	function template()
	{
		ob_start();
		$this->defaulttpldir = ROOT_PATH.'/application/views'.VIEWSPATH;
		$this->tpldir = ROOT_PATH.'/application/views'.VIEWSPATH;
		$this->objdir = ROOT_PATH.'/static/compile';
	}

	function assign($k, $v)
	{
		$this->vars[$k] = $v;
	}

	function display($file)
	{
		if($this->vars)extract($this->vars, EXTR_SKIP);
		include $this->gettpl($file);
	}

	function gettpl($file)
	{
		isset($_GET['inajax']) && ($file == 'header' || $file == 'footer') && $file = 'ajax_'.$file;
		isset($_GET['inajax']) && ($file == 'admin_header' || $file == 'admin_footer') && $file = 'ajax_'.substr($file, 6);
		$this->tplfile = $this->tpldir.'/'.$file.'.html';
		$this->objfile = $this->objdir.'/'.$file.'.php';
		$tplfilemtime = @filemtime($this->tplfile);
		if($tplfilemtime === FALSE)
		{
			$this->tplfile = $this->defaulttpldir.'/'.$file.'.html';
		}
		if($this->force || !file_exists($this->objfile) || @filemtime($this->objfile) < filemtime($this->tplfile))
		{
			$this->complie();
		}
		return $this->objfile;
	}

	function complie()
	{
		$template = file_get_contents($this->tplfile);
		$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
		$template = preg_replace("/\{($this->var_regexp)\}/", "<?=\\1?>", $template);
		$template = preg_replace("/\{($this->const_regexp)\}/", "<?=\\1?>", $template);
		$template = preg_replace("/(?<!\<\?\=|\\\\)$this->var_regexp/", "<?=\\0?>", $template);
		$template = preg_replace("/\<\?=(\@?\\\$[a-zA-Z_]\w*)((\[[\\$\[\]\w]+\])+)\?\>/ies", "\$this->arrayindex('\\1', '\\2')", $template);
		$template = preg_replace("/\{\{eval (.*?)\}\}/ies", "\$this->stripvtag('<? \\1?>')", $template);
		$template = preg_replace("/\{eval (.*?)\}/ies", "\$this->stripvtag('<? \\1?>')", $template);
		$template = preg_replace("/\{for (.*?)\}/ies", "\$this->stripvtag('<? for(\\1) {?>')", $template);
		$template = preg_replace("/\{elseif\s+(.+?)\}/ies", "\$this->stripvtag('<? } elseif(\\1) { ?>')", $template);
		
		for($i=0; $i<2; $i++) {
			$template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopsection('\\1', '\\2', '\\3', '\\4')", $template);
			$template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopsection('\\1', '', '\\2', '\\3')", $template);
		}
		
		$template = preg_replace("/\{if\s+(.+?)\}/ies", "\$this->stripvtag('<? if(\\1) { ?>')", $template);
		$template = preg_replace("/\{template\s+(\w+?)\}/is", "<? include \$this->gettpl('\\1');?>", $template);
		$template = preg_replace("/\{template\s+(.+?)\}/ise", "\$this->stripvtag('<? include \$this->gettpl(\\1); ?>')", $template);
		// huyao ++
		$template = preg_replace("/\{createUrl=(\w+?)\}/is", "<? \$this->createUrl('\\1');?>", $template);
		$template = preg_replace("/\{createUrl=(.+?)\}/ise", "\$this->stripvtag('<? echo \$this->createUrl(\'\\1\');?>')", $template);
		
		$template = preg_replace("/\{else\}/is", "<? } else { ?>", $template);
		$template = preg_replace("/\{\/if\}/is", "<? } ?>", $template);
		$template = preg_replace("/\{\/for\}/is", "<? } ?>", $template);
		$template = preg_replace("/$this->const_regexp/", "<?=\\1?>", $template);
		$template = "<? if(!defined('ROOT_PATH')) exit('Access Denied');?>\r\n$template";
		$template = preg_replace("/(\\\$[a-zA-Z_]\w+\[)([a-zA-Z_]\w+)\]/i", "\\1'\\2']", $template);
		
		$fp = fopen($this->objfile, 'w');
		fwrite($fp, $template);
		fclose($fp);
		
		unset($template);
	}
	
	function arrayindex($name, $items)
	{
		$items = preg_replace("/\[([a-zA-Z_]\w*)\]/is", "['\\1']", $items);
		return "<?=$name$items?>";
	}

	function stripvtag($s)
	{
		return preg_replace("/$this->vtag_regexp/is", "\\1", str_replace("\\\"", '"', $s));
	}

	function loopsection($arr, $k, $v, $statement)
	{
		$arr = $this->stripvtag($arr);
		$k = $this->stripvtag($k);
		$v = $this->stripvtag($v);
		$statement = str_replace("\\\"", '"', $statement);
		
		return $k ? "<? foreach((array)$arr as $k => $v) {?>$statement<?}?>" : "<? foreach((array)$arr as $v) {?>$statement<? } ?>";
	}
	
	// 转换URL
	private function createUrl($str) {
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

/*

Usage:
require_once 'library/template.class.php';
$this->view = new template();
$this->view->assign('page', $page);
$this->view->assign('userlist', $userlist);
$this->view->display("user_ls");

{loop $arr $arr1}
         {loop $arr1[arr] $arr2}
               $arr2[0]
         {/loop}
{/loop}

<!--{loop $a $b}--><!--{/loop}-->

{createUrl=admin/login/index}
*/
?>