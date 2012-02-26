<?php    
class Thief{     
	// 需要得到数据的网址    
	var $URL;    
	// 需要分析的开始标记    
	var $startFlag;    
	//需要分析的结束标记    
	var $endFlag;    
	//存储图片的路径    
	var $saveImagePath;    
	//访问图片的路径    
	var $imageURL;    
	// 列表内容
	var $ListContent;    
	//需要获得的图片路径    
	var $ImageList;
	//存储的图片名称    
	var $FileName;
	
	/**   
	* 得到页面内容   
	* @return String 列表页面内容   
	*/   
	function getPageContent($cutCode) {                    
		$pageContent = @file_get_contents($this->URL);
		if($pageContent && $cutCode) {
			$pageContent = iconv($cutCode, 'utf-8//IGNORE', $pageContent);
		}
		return $pageContent;    
	}
	
	function getPageTpl($content, $rule) {
		$tpl = array();
		preg_match_all("@".$rule."\w?@is", $content, $tpl);
		//$content = $tpl;    
		//$content = implode("", $content);    
		return $tpl;    
	}
	
	/**   
	* 根据标记得到列表段   
	* @param $content  页面源数据   
	* @return String   列表段内容   
	*/   
	function getContentPiece($content)    
	{    
		$content = $this->getContent($content, $this->startFlag, $this->endFlag);    
		return $content;    
	}
	
	/**   
	* 得到一个字符串中的某一部分   
	* @param $sourceStr 源数据   
	* @param $startStr 分离部分的开始标记   
	* @param $endStart 分离部分的结束标记   
	* @return boolean  操作成功返回true   
	*/   
	function getContent($sourceStr, $startStr, $endStart)
	{
		$tpl = array();
		preg_match_all("@".$startStr."(.*?)".$endStart."@is", $sourceStr, $tpl);
		$content = $tpl;    
		//$content = implode("", $content);    
		return $content;    
	}
	
	/* 根据配置提取数据，以数组形式返回 */
	public function spdier($cutCode, $targetUrl, $listRule, $currentPage=0) {
		$tpl = $fields = array();
		preg_match_all("/\{(.*?)\}/is", $listRule, $tpl);
		if($tpl && $tpl[1]) {
			$arr = $tpl[1];
			//$arr = array_flip($arr);
			foreach($arr as $k=>$v) {
				$value = explode('=', $v);
				$fields[$k] = $value;
			}
		}
		$listRule = preg_replace("/\{([^\}]*)\}/i", "(.*?)", $listRule);

		if(strpos($targetUrl, '[page]') !== FALSE) {
			$targetUrl = str_replace('[page]', $currentPage, $targetUrl);
		}
		
		$this->setURL($targetUrl);
		$content = $this->getPageContent($cutCode);
		
		$arr = array();
		$data = $this->getPageTpl($content, $listRule);
		foreach($fields as $k=>$field) {
			if(isset($field[1])) {
				$s = $data[$k+1];
				foreach($s as $key=>$value) {
					$arr[$key][$field[0]] = addslashes($value);
				}
			}
		}
		return $arr;
	}
	
	/* 初始化数据后的一个设置 */
	function setURL($u)    
	{    
		$this->URL = $u;    
		return true;    
	}    
	function setStartFlag($s)    
	{    
		$this->startFlag = $s;    
		return true;    
	}    
	function setEndFlag ($e)    
	{    
		$this->endFlag = $e;    
		return true;    
	}    
	function setSaveImagePath ($p)    
	{    
		$this->saveImagePath = $p;    
		return true;    
	}    
	function setImageURL ($i)    
	{    
		$this->imageURL = $i;    
		return true;    
	}
}
?>   