<?php
class spiderCustomController extends adminbase
{
	function __construct()
	{
		parent::__construct();

		$this->load('admin_spider');
	}
	
	function listAction() {
		
		$this->view->display('spiderCustom_list');
	}
	
	public function qidianAction() {
		//http://www.qidian.com/
	}
	
	public function xiaoshuoAction() {
		set_time_limit(0);
		//$conf[] = array('parentId'=>6, 'categoryId'=>31, 'categoryName'=>'外国科幻', 'url'=>'http://www.xiaoshuo.com/column/106001_2_0_','endPage'=>34);
		//$conf[] = array('parentId'=>4, 'categoryId'=>28, 'categoryName'=>'经典作品', 'url'=>'http://www.xiaoshuo.com/column/104001_2_0_','endPage'=>52);	// 武侠小说->经典作品
		$conf[] = array('parentId'=>1, 'categoryId'=>12, 'categoryName'=>'网络言情', 'url'=>'http://www.xiaoshuo.com/column/101002_2_0_','endPage'=>1);	// 现代文学->网络言情
		$conf[] = array('parentId'=>3, 'categoryId'=>25, 'categoryName'=>'经典名著', 'url'=>'http://www.xiaoshuo.com/column/103001_2_0_','endPage'=>1);	// 古典小说->经典名著
		$conf[] = array('parentId'=>2, 'categoryId'=>17, 'categoryName'=>'经典名篇', 'url'=>'http://www.xiaoshuo.com/column/102001_2_0_','endPage'=>1);	// 古典小说->经典名篇
		$conf[] = array('parentId'=>5, 'categoryId'=>43, 'categoryName'=>'都市生活', 'url'=>'http://www.xiaoshuo.com/column/105001_2_0_','endPage'=>1);	// 言情小说->都市生活
		$conf[] = array('parentId'=>7, 'categoryId'=>35, 'categoryName'=>'欧美侦探', 'url'=>'http://www.xiaoshuo.com/column/107001_2_0_','endPage'=>1);	// 侦探->欧美侦探
		$conf[] = array('parentId'=>9, 'categoryId'=>42, 'categoryName'=>'寓言故事', 'url'=>'http://www.xiaoshuo.com/column/109003_2_0_','endPage'=>1);	// 少儿->寓言故事
		//$conf[] = array('parentId'=>7, 'categoryId'=>36, 'categoryName'=>'日本推理', 'url'=>'http://www.xiaoshuo.com/column/107002_2_0_','endPage'=>23);	// 少儿->日本推理 +5
		foreach($conf as $v) {
			$this->xiaoshuo($v);
		}
	}
	
	private function xiaoshuo($conf) {
		$thiefFile =  GODHOUSE_ROOT."library/thief.php";
		require_once($thiefFile);
		
		$rule = '\[<font color=\"#525252\">{author=*}</font>\]&nbsp;<a class=\"link\" href=\"{url=*}\" onClick=\"javascript:SetCookie\(\'lastpage\',\'media.0019997\'\);\">{novelName=*}</a>';	// 定义规则
		//echo htmlspecialchars($content);
		$thief = new Thief();
		$tpl = array();
		for ($i=1; $i<=$conf['endPage']; $i++) {
			$arr = $thief->spdier('gb2312', $conf['url'].$i.".html", $rule);
			$tpl = array_merge($tpl, $arr);
			if($i % 5 == 0) {
				usleep(250000);
			}
		}
		/* 开始抓取数据 */
		foreach($tpl as $s) {
			/* 抓取小说 */
			$rule = '<iframe id="bookdown" src="{pictureurl=*}"  marginwidth="0" marginheight="0" SCROLLING="no" FRAMEBORDER="0" width=\'100%\' allowTransparency="true"><\/iframe>{*}';
			$rule .= '<td bgcolor="#EFEFEF" valign="top" width="372">&nbsp;&nbsp;&nbsp;{comment=*}<\/td>{*}';
			$rule .= '<iframe id="bookbadge" src="{url=*}"  marginwidth="0" marginheight="0" SCROLLING="no" FRAMEBORDER="0" width=\'100%\' allowTransparency="true"></iframe>';
			$arr = $thief->spdier('gb2312', 'http://www.xiaoshuo.com'.$s['url'], $rule);
			if($arr && $arr[0]) {
				$novel = $arr[0];
				$urlId = substr($novel['url'], strpos($novel['url'], '=')+1);
				if(!$urlId) {
					continue;
				}
				$novelField = array(
					'novelName'=>'',
					'picture'=>'',
					'comment'=>'',
					'author'=>'',
					'parentId'=>$conf['parentId'],
					'categoryId'=>$conf['categoryId'],
					'categoryName'=>$conf['categoryName'],
					'totals'=>0,
					'createdTime'=>$this->time,
					'modifiedTime'=>$this->time,
				);
				$novelField['author'] = $s['author'];
				$novelField['novelName'] = $s['novelName'];
				$novelField['comment'] = $novel['comment'];
				
				/* 抓取封面图片 */
				$rule = '<td width="121"><img height="160" src="{picture=*}" width="120"></td>';
				$image = $thief->spdier('gb2312', 'http://www.xiaoshuo.com'.$novel['pictureurl'], $rule);
				if($image && $image[0]['picture']) {
					//$pictureFile = GODHOUSE_ROOT.'data/picture/'.
					//saveimg();
					$novelField['picture'] = $image[0]['picture'];
				}
				
				//$novelId = $this->novelModel->insert_novel($novelField);
				
				/* 抓取章节 */
				$rule = '<strong>\n<a href="{url=*}" class="link">{title=*}</a>';
				$contents = $thief->spdier('gb2312', 'http://www.xiaoshuo.com/readindex/index_'.$urlId.'.html', $rule);
				if($contents) {
					
					$nov = $this->novelModel->get_novel_condition("categoryId='{$conf['categoryId']}' AND novelName='{$novelField['novelName']}'");
					
					$rule = '<td height="327" valign="top" style="padding:10px;line-height:20px;word-wrap:break-word;word-break:break-all;">{content=*}<\/td>';
					foreach($contents as $k=>$v) {
						
						if($k < 5) {
							continue;
						}
						$contentField = array(
							'novelId'=>$nov['novelId'],
							'title'=>$v['title'],
							'contentText'=>'',
							'createdTime'=>$this->time,
						);
						$content =  $thief->spdier('gb2312', 'http://www.xiaoshuo.com'.$v['url'], $rule);
						if($content && $content[0]['content']) {
							$contentField['contentText'] = $content[0]['content'];
							$this->novelModel->insert_novel_content($contentField);
						}
						$novelField['totals']++;
					}
				}
				$this->novelModel->update_novel_totals($nov['novelId'], $novelField['totals']+5);
			}
			usleep(250000);
		}
		//$tmpFile = GODHOUSE_ROOT."data/tmp/".$spiderId.'/array.php';		// 得到临时文件
		//$data = require $tmpFile;
	}
}