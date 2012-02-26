<?php
/**
 * @author huyao
 * @exception 采集工具
 */
class spiderController extends adminbase
{
	function __construct()
	{
		parent::__construct();

		$this->load('admin_spider');
	}

	function indexAction() {
		$spiderList = $this->cmd->getList();
		$this->view->assign('spiderList', $spiderList);
		$this->view->display('admin_spider_index');
	}
	
	function logListAction() {
		$spiderId = getgpc('spiderId', 'R');
		
		$logList = array();
		$logFile = GODHOUSE_ROOT."data/tmp/".$spiderId.'/content.log';
		$fp = fopen($logFile, 'r');
		while(!feof($fp)) {
        		$buffer = fgets($fp, 98096);
        		if($buffer) {
				$json = json_decode($buffer);
	        		$arr['content'] = htmlspecialchars(cutstr($json->content, 150));
	        		$arr['title'] = $json->title;
	        		$arr['url'] = $json->url;
	        		$logList[] = $arr;
        		}
		}
		fclose($fp);
		
		if(getgpc('submitTo', 'P')) {
			set_time_limit(0);
			$keys = getgpc('P', 'P');
			$categoryId = getgpc('categoryId', 'P');
			$category = $this->categoryModel->get_category($categoryId);
			$spider = $this->cmd->getSpiderById($spiderId);
			$novelId = $this->novelModel->insert_novel(array(
				'novelName'=>$spider['spiderName'], 
				'totals'=>$spider['recordTotal'], 
				'parentId'=>$category['parent_id'], 
				'categoryId'=>$categoryId, 
				'categoryName'=>$category['cate_name'], 
				'comment'=>$spider['comment'], 
				'author'=>$spider['author'], 
				'createdTime'=>$this->time,
			));
			foreach($keys as $k) {
				$novel = $logList[$k];
				$this->novelModel->insert_novel_content(array(
					'novelId'=>$novelId,
					'title'=>$novel['title'],
					'contentText'=>$novel['content'],
					'createdTime'=>$this->time,
				));
			}
		}
		
		/* 获取分类 */
		$categorys = $this->categoryModel->get_categorys_sort();
		
		$this->view->assign('categorys', $categorys);
		$this->view->assign('spiderId', $spiderId);
		$this->view->assign('logList', $logList);
		$this->view->display('admin_spider_loglist');
	}
	
	function logContent() {
		
	}
	
	function deleteAction() {
		$spiderId = getgpc('spiderId');
		$this->cmd->deleteSpider($spiderId);
		$tmpFile = GODHOUSE_ROOT.'data/tmp/'.$spiderId;
		$files = array('content.log', 'array.php');
		foreach ($files as $file) {
			@unlink($tmpFile.'/'.$file);
		}
		@rmdir($tmpFile);
		$this->_alert('删除成功。', 'admin.php?c=spider');
	}
	
	function settingAction() {
		$spiderId = getgpc('spiderId', 'R');
		$mod = getgpc('mod', 'R');
		$submit = getgpc('submit', 'P');
		if($submit) {
			set_time_limit(0);
			$spiderName= getgpc('spiderName', 'P');			// 编码转换
			$cutCode = getgpc('cutCode', 'P');			// 编码转换
			$listRule = getgpc('listRule', 'P');			// 列表规则
			$contentRule = getgpc('contentRule', 'P');		// 内容规则
			$normalUrl = getgpc('normalUrl', 'P');			// 普通URL
			$pageUrl = getgpc('pageUrl', 'P');			// 分页URL
			$startPage = getgpc('startPage', 'P');			// 起始页是多少
			$endPage = getgpc('endPage', 'P');			// 结束页是多少
			$stepPage = getgpc('stepPage', 'P');			// 结束页是多少
			
			if(!$listRule || !$contentRule) {
				$this->_alert('请您填写规则');
			}
			if(!$normalUrl && !$pageUrl) {
				$this->_alert('您至少填写一个目标URL');
			}
			
			//$targetUrl = 'http://yy.17k.com/list/81463.html';
			$domain = $this->getDomain($normalUrl, $pageUrl);	// 获取域名地址
			
			/* 写入采集配置文件到数据库 */
			$config = array(
				'spiderName'=>$spiderName,
				'cutCode'=>$cutCode,
				'listRule'=>$listRule,
				'contentRule'=>$contentRule,
				'normalUrl'=>$normalUrl,
				'pageUrl'=>$pageUrl,
				'domain'=>$domain,
				'startPage'=>$startPage,
				'stepPage'=>$stepPage,
				'endPage'=>$endPage,
				'createdTime'=>date('Y-m-d H:i:s'),
			);
			if(empty($spiderId) || $mod=='copy') {
				$spiderId = $this->cmd->insertSpider($config);
			} else {
				$this->cmd->updateSpider($spiderId, $config);
			}
			
			require GODHOUSE_ROOT.'/library/thief.php';
			$mergeArr = array();
			if($normalUrl) {
				$arr = $this->pageSpiderList($cutCode, $normalUrl, $listRule);
				$mergeArr = $arr;
			}
			if(is_numeric($startPage) && is_numeric($endPage) && $pageUrl) {
				for($i=$startPage; $i<=$endPage; $i++) {
					$arr = $this->pageSpiderList($cutCode, $pageUrl, $listRule, $i);
					$mergeArr = array_merge($mergeArr, $arr);
				}
			}
			//print_r($mergeArr);
			//exit(0);
			/* 写入采集过来的列表数据到临时文件 */
			mkdirs(GODHOUSE_ROOT."data/tmp/".$spiderId);
			$tmpFile = GODHOUSE_ROOT."data/tmp/".$spiderId.'/array.php';
			$writeText = "<?php\r\n";
			$writeText .= 'return '.var_export($mergeArr, true);
			$writeText .= "?>";
			file_put_contents($tmpFile, $writeText);
			
			$this->_alert('数据已采集完毕，请选择要入库的数据', 'admin.php?c=spider&a=dataList&spiderId='.$spiderId);
		}
		$this->view->assign('spiderId', $spiderId);
		if($spiderId) {
			$spider = $this->cmd->getSpiderById($spiderId);
			$this->view->assign('spider', $spider);
			$this->view->assign('mod', $mod);
			$this->view->display('admin_spider_edit');
		} else {
			$this->view->display('admin_spider_setting');
		}
	}
	
	public function dataListAction() {
		$spiderId = getgpc('spiderId');
		$tmpFile = GODHOUSE_ROOT."data/tmp/".$spiderId.'/array.php';		// 得到临时文件
		$data = require $tmpFile;
		
		$this->view->assign('data', $data);
		$this->view->assign('spiderId', $spiderId);
		$this->view->display('admin_spider_datalist');
	}
	
	public function contentAction() {
		$submit = getgpc('submit', 'P');
		if($submit) {
			set_time_limit(0);
			$arrId = getgpc('P', 'P');
			$spiderId = getgpc('spiderId', 'P');
			$mod = getgpc('mod', 'P');
			
			$config = $this->cmd->getSpiderById($spiderId);
			$arrayFile = GODHOUSE_ROOT."data/tmp/".$spiderId.'/array.php';		// 得到临时文件
			$data = require $arrayFile;
			require GODHOUSE_ROOT.'library/thief.php';
			
			$tmpLogFile = GODHOUSE_ROOT."data/tmp/".$spiderId.'/content.log';		// 写入采集到的内容
			if($mod == 'normal') $this->cmd->updateRecord($spiderId, TRUE);
			$fp = fopen($tmpLogFile, 'w');
			$content = '';
			
			ob_start();
			$fields = array();
			foreach($arrId as $k) {
				if($data[$k]) {
					$title = $data[$k]['title'];
					$url = $data[$k]['url'];
					
					/* 以日志形式存放 */
					$arr = $this->pageSpiderContent($config['cutCode'], $url, $config['contentRule']);
					$arr['title'] = $title;
					$arr['url'] = $url;
					$str = '<span style="color:red;font-size:12px;line-height:20px;">正在采集 >>> '.$title.'<a href="'.$url.'">'.$url.'</a></span><br/>';
					echo $str;
					if($mod == 'test') echo htmlspecialchars($arr['content']).'<br/>';
					
					@ob_end_flush();
					flush();
					
					if($mod == 'normal') $content .= json_encode($arr)."\r\n";
					if($mod == 'normal') $this->cmd->updateRecord($spiderId);
					/* 以数组形式存放 
					$writeText = "<?php\r\n";
					$writeText .= 'return '.var_export($arr, true);
					$writeText .= "?>";
					
					$fp = fopen($tmpLogFile, 'a+');
					fwrite($fp, $writeText);
					fclose($fp);*/
					usleep(500000);
				}
			}
			fwrite($fp, $content);
			fclose($fp);
			@ob_end_clean();
			echo '<span style="color:red; font-size:12px;line-height:20px;">采集数据完成。 <a href="###" onclick="window.close();">关闭</a></span>';
			/* 这也是一种采集输出思路，自调URL进行输出
			echo "<META HTTP-EQUIV=REFRESH CONTENT='1;URL=?lfj=$lfj&action=$action&id=$id&system_type=$system_type&GetFile=$GetFile&file_dir=$file_dir&makesmallpic=$makesmallpic&showpic=$showpic&username=$username&fid=$fid&testgather=$testgather&page=$page'>";
			*/
		}
	}
	
	/**
	 * 采集标题列表
	 *
	 * @param unknown_type 采集的URL
	 * @param unknown_type 列表标题的规则
	 * @param unknown_type 采集的当前页
	 * @return Array
	 */
	private function pageSpiderList($cutCode, $targetUrl, $listRule, $currentPage=0) {
		$domain = $this->getDomain($targetUrl);
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
		
		$thief = new Thief();
		$thief->setURL($targetUrl);
		$content = $thief->getPageContent($cutCode);
		//print_r($content);
		$arr = array();
		$data = $thief->getPageTpl($content, $listRule);
				
		foreach($fields as $k=>$field) {
			if(isset($field[1])) {
				$s = $data[$k+1];
				foreach($s as $key=>$value) {
					$arr[$key][$field[0]] = addslashes($value);
				}
			}
		}
		unset($fields);
		foreach($arr as $k=>&$fields) {
			if(isset($fields['url']) && $fields['url']) {
				if(strpos($fields['url'], '"')!==FALSE) {
					$fields['url'] = substr($fields['url'], 0, strpos($fields['url'], '"'));
				} elseif(strpos($fields['url'], "'")!==FALSE){
					$fields['url'] = substr($fields['url'], 0, strpos($fields['url'], "'"));
				} elseif(strpos($fields['url'], ' ')!= FALSE){
					$fields['url'] = substr($fields['url'], 0, strpos($fields['url'], ' '));
				} else {
					if(strpos(strtolower($fields['url']), 'http://') !== FALSE) {
						
					} else {
						$url = $fields['url'];
						if($url{0} == '/') {
							$fields['url'] = 'http://'.$domain.$fields['url'];
						} else {
							$fields['url'] = 'http://'.$domain.'/'.$fields['url'];
						}
					}
				}
			}
		}
		//print_r($arr);exit;
		return $arr;
	}
	
	/* 采集内容页 */
	private function pageSpiderContent($cutCode, $targetUrl, $rule) {
		$tpl = $fields = array();
		preg_match_all("/\{(.*?)\}/is", $rule, $tpl);
		if($tpl && $tpl[1]) {
			$arr = $tpl[1];
			//$arr = array_flip($arr);
			foreach($arr as $k=>$v) {
				$value = explode('=', $v);
				$fields[$k] = $value;
			}
		}
		//print_r($fields);exit;
		$rule = preg_replace("/\{([^\}]*)\}/i", "(.*?)", $rule);

		if(strpos($targetUrl, '[page]') !== FALSE) {
			$targetUrl = str_replace('[page]', $currentPage, $targetUrl);
		}
		$thief = new Thief();
		$thief->setURL($targetUrl);
		$content = $thief->getPageContent($cutCode);

		$arr = array();
		$data = $thief->getPageTpl($content, $rule);
		//print_r($data);exit;
		foreach($fields as $k=>$field) {
			if(isset($field[1])) {
				$s = $data[$k+1];
				$arr[$field[0]] = $s[0];
			}
		}
		return $arr;
	}
	
	/**
	 * 获取域名地址
	 *
	 * @return 返回域名
	 */
	private function getDomain($normalUrl, $pageUrl='') {
		$targetUrl = '';
		if($normalUrl) {
			$targetUrl = $normalUrl;
		} else 
			$targetUrl = $pageUrl;
			
		if($targetUrl && strpos(strtolower($targetUrl), 'http://') !== FALSE) {
			$length = 0;
			if(strpos($targetUrl, '/', 7)!==false) {
				$length = strpos($targetUrl, '/', 7)-7;
			} else {
				$length = strlen($targetUrl)-7;
			}
			return substr($targetUrl, 7, $length);
		}
		return '';
	}
}
?>