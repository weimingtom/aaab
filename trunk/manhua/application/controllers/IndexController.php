<?php
!defined('IN_ROOT') && exit('Access Denied');
class IndexController extends Base 
{
	public $categorys;
	
	public function __construct()
	{
		parent::__construct ();
		$this->categorys = $this->categoryModel->get_categorys();
		$this->view->assign('categorys', $this->categorys);
		//print_r($this->categorys);
		/* 顶部导航分类显示 */
		$navCategorys = $this->categoryModel->get_nav_categorys();
		$this->view->assign('navCategorys', $navCategorys);
		
		define('GODHOUSE_NETWORK_ID', 1);			// 网络
		define('GODHOUSE_NEW_ID', 2);				// 现代
		define('GODHOUSE_CLASSICAL_ID', 3);			// 古典文学
		define('GODHOUSE_MARTIAL_ARTS_ID', 4);			// 武侠小说
		define('GODHOUSE_ROMANCE_ID', 5);			// 言情小说
		define('GODHOUSE_FANTASY_ID', 6);			// 玄幻小说
		define('GODHOUSE_DETECTIVE_ID', 7);			// 侦探
		define('GODHOUSE_CHILDREN_ID', 9);			// 少儿读物
	}
	
	public function actionIndex()
	{
		/* 书友点击榜 */
		$d = $this->time; 			//待处理的日期 
		$w = date("w",$d); 			//这天是星期几 
		$weekStartTime = date("Y-m-d", strtotime("-$w day",$d) + 86400); //周开始 
		$weekNovels = $this->novelModel->get_hot_novels("createdTime>'$weekStartTime'", 24);
		/* 本月的排行 */
		$monthStartTime = strtotime(date('Y-m-1'));
		$monthNovels = $this->novelModel->get_hot_novels("createdTime>'$monthStartTime'", 24);
		/* 总的排行 */
		$totalNovels = $this->novelModel->get_hot_novels('', 24);
		
		/* 强力推荐 */
		$newNovels = $this->novelModel->get_novels('', 1, 24);
		$this->cutstr($newNovels, 19);
		
		/* 小说列表 */
		$network_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_NETWORK_ID, 1, 5);
		$this->novelModel->set_novel_first_comment($network_novels);
		$this->cutstr($network_novels, 20);
		
		$new_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_NEW_ID, 1, 5);
		$this->novelModel->set_novel_first_comment($new_novels);
		$this->cutstr($new_novels, 20);
		
		$classical_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_CLASSICAL_ID, 1, 5);
		$this->novelModel->set_novel_first_comment($classical_novels);
		$this->cutstr($classical_novels, 20);
		
		$martial_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_MARTIAL_ARTS_ID, 1, 5);
		$this->novelModel->set_novel_first_comment($martial_novels);
		$this->cutstr($martial_novels, 20);
		
		$romance_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_ROMANCE_ID);
		$this->novelModel->set_novel_first_comment($romance_novels, 50);
		$this->cutstr($romance_novels, 20);
		
		$fantasy_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_FANTASY_ID);
		$this->novelModel->set_novel_first_comment($fantasy_novels, 50);
		$this->cutstr($fantasy_novels, 20);
		
		$detective_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_DETECTIVE_ID);
		$this->novelModel->set_novel_first_comment($detective_novels, 50);
		$this->cutstr($detective_novels, 20);
		
		$children_novels = $this->novelModel->get_novels_by_parentId(GODHOUSE_CHILDREN_ID);
		$this->novelModel->set_novel_first_comment($children_novels, 50);
		$this->cutstr($children_novels, 20);
		
		$this->header['title'] = '网游小说-都市免费爱情小说阅读网就在木耳屋';
		$this->header['description'] = '木耳屋小说网提供原创玄幻小说,武侠小说,网游小说,都市言情小说,历史军事小说,短篇小说,校园小说.最新章节免费阅读等小说在线阅读';
		$this->header['keyword'] = '网游小说,都市小说,免费小说,爱情小说';
		
		$this->view->assign('network_novels', $network_novels);
		$this->view->assign('new_novels', $new_novels);
		$this->view->assign('classical_novels', $classical_novels);
		$this->view->assign('martial_novels', $martial_novels);
		$this->view->assign('romance_novels', $romance_novels);
		$this->view->assign('fantasy_novels', $fantasy_novels);
		$this->view->assign('detective_novels', $detective_novels);
		$this->view->assign('children_novels', $children_novels);
		$this->view->assign('weekNovels', $weekNovels);
		$this->view->assign('monthNovels', $monthNovels);
		$this->view->assign('totalNovels', $totalNovels);
		$this->view->assign('newNovels', $newNovels);
		$this->display('index');
	}
	
	public function actionList() {
		$page = max(1, getgpc('page'));
		$categoryId = getgpc(2);
		$subCategoryId = getgpc(3);
		$subCategory = array();							// 当前子类
		
		$isParentId = $subCategoryId ? 0 : 1;										// 是否是父ID  
		/* 当前的分类 */
		$category = $this->categoryModel->get_category($categoryId);
		if(!empty($category['parent_id'])) {
			$subCategoryId = $categoryId;
			$subCategory = $category;
			$category = $this->categoryModel->get_category($category['parent_id']);
			$categoryId = $category['id'];
			$isParentId = 0;
		}
		
		$this->header['title'] = $category['cate_name'].'-爱情小说阅读网就在木耳屋';
		
		/* 当前分类的子分类列表*/
		$subCategorys = $this->categoryModel->get_sub_categorys($categoryId, $this->categorys);
		
		/* 子类的第一个ID */
		$subCategoryId = $subCategoryId ? $subCategoryId : $subCategorys[0]['cate_id'] ;
		if(!$subCategory) {
			$subCategory = $this->categoryModel->get_category($subCategoryId);
		}
		/* 热门的小说列表 */
		$hotNovels = $this->novelModel->get_hot_novels($subCategoryId, 30);
		
		
		/* 小说列表 */
		$num = 0;
		$novels = array();
		if ($isParentId) {
			$subCategoryId = 0;
			$num = $this->novelModel->get_num(0, "parentId='$categoryId'");
			$novels = $this->novelModel->get_novels_by_parentId($categoryId, $page, GODHOUSE_PPP);
			$multipage = page2($num, GODHOUSE_PPP, $page, "index-list-$categoryId.htm");
		} else {
			$num = $this->novelModel->get_num($subCategoryId);
			$novels = $this->novelModel->get_novels_by_categoryId($subCategoryId, $page, GODHOUSE_PPP);
			$multipage = page2($num, GODHOUSE_PPP, $page, "index-list-$categoryId-$subCategoryId.htm");
		}
		
		$this->view->assign('novels', $novels);
		$this->view->assign('hotNovels', $hotNovels);
		$this->view->assign('category', $category);
		$this->view->assign('subCategory', $subCategory);
		$this->view->assign('subCategorys', $subCategorys);
		$this->view->assign('multipage', $multipage);
		$this->view->assign('subCategoryId', $subCategoryId);
		$this->display('list');
	}
	
	public function actionDetail() {
		$novelId = getgpc(2);
		/* 当前的小说 */
		$novel = $this->novelModel->get_novel($novelId);
		/* 小说卷 */
		$novelChapters = $this->novelModel->get_novel_chapters($novelId);
		foreach($novelChapters as &$chapter) {
			$novelContents = $this->novelModel->get_novel_contents_by_chapterId($chapter['chapterId']);
			$chapter['contents'] = $novelContents;
		}
		
		/* 当前的分类 */
		$category = $this->categoryModel->get_category($novel['categoryId']);
		/* 当前的父类*/
		$parentCategory = $this->categoryModel->get_category($category['parent_id']);
		/* 书友点击榜 */
		$hotNovels = $this->novelModel->get_hot_novels('', 15);
		/* 点击量加1 */
		$this->novelModel->update_novel_views($novelId);
		
		$this->header['title'] = $novel['novelName'].'-爱情小说阅读网就在木耳屋';
		
		$this->view->assign('novel', $novel);
		$this->view->assign('novelChapters', $novelChapters);
		$this->view->assign('category', $category);
		$this->view->assign('parentCategory', $parentCategory);
		$this->view->assign('hotNovels', $hotNovels);
		$this->display('detail');
	}
	
	public function actionContent() {
		$contentId = getgpc(2);
		/* 当前章节 */ 
		$content = $this->novelModel->get_novel_content($contentId);
		/* 当前的小说 */
		$novel = $this->novelModel->get_novel($content['novelId']);
		/* 小说章节列表 */
		$contents = $this->novelModel->get_novel_contents($content['novelId']);
		/* 当前的分类 */
		$category = $this->categoryModel->get_category($novel['categoryId']);
		/* 当前的父类*/
		$parentCategory = $this->categoryModel->get_category($category['parent_id']);
		/* 上一目录，下一目录 */
		$prevContent = $this->novelModel->get_novel_content_dir($content['novelId'], "contentId<$contentId");
		$nextContent = $this->novelModel->get_novel_content_dir($content['novelId'], "contentId>$contentId", "ASC");
		
		$this->header['title'] = $content['title'].'-'.$novel['novelName'].'-爱情小说阅读网就在木耳屋';
		$this->view->assign('novel', $novel);
		$this->view->assign('content', $content);
		$this->view->assign('contents', $contents);
		$this->view->assign('category', $category);
		$this->view->assign('parentCategory', $parentCategory);
		$this->view->assign('prevContent', $prevContent);
		$this->view->assign('nextContent', $nextContent);
		$this->display('content');
	}
	
	private function cutstr(&$data, $length) {
		foreach($data as &$v) {
			$v['novelName'] = cutstr($v['novelName'], $length, '');
		}
	}
}

?>