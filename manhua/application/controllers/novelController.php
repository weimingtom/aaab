<?php
!defined('IN_ROOT') && exit('Access Denied');
class novelController extends Base 
{
	public function __construct() {
		parent::__construct();
		/* 顶部导航分类显示 */
		$navCategorys = $this->categoryModel->get_nav_categorys();
		$this->view->assign('navCategorys', $navCategorys);
	}
	
	/* 最新更新的小说 */
	public function actionNewlist() {
		/* 最近推出的小说 */
		$novels = $this->novelModel->get_novels('', 1, 15);
		/* 最近小说排行 */
		$hotNovels = array();
		foreach($novels as $v) {
			if($hotNovels) {
				$length = count($hotNovels);
				if($v['views'] > $hotNovels[$length-1]['views']) {
					array_unshift($hotNovels, $v);
				} else {
					array_push($hotNovels, $v);
				}
			} else {
				$hotNovels[] = $v;
			}
		}
		//print_r($hotNovels);
		
		$this->view->assign('novels', $novels);
		$this->view->assign('hotNovels', $hotNovels);
		$this->display('novel_newlist');
	}
	
	/* 小说搜索 */
	public function actionSearch() {
		/* 最近推出的小说 */
		$novels = $this->novelModel->get_novels('', 1, 15);
		/* 最近小说排行 */
		$hotNovels = array();
		foreach($novels as $v) {
			if($hotNovels) {
				$length = count($hotNovels);
				if($v['views'] > $hotNovels[$length-1]['views']) {
					array_unshift($hotNovels, $v);
				} else {
					array_push($hotNovels, $v);
				}
			} else {
				$hotNovels[] = $v;
			}
		}
		unset($novels);
		/* 搜索 */
		$page = max(1, getgpc('page'));
		$searchType = getgpc('searchType');
		$keywords = getgpc('keywords');
		
		$sqladd = '';
		if($searchType == 'novelName') {
			$sqladd .= "novelName like '%$keywords%'";
		} elseif($searchType == 'author') {
			$sqladd .= "author='$keywords'";
		} else {
			$this->message('请您选择查询关键字', GODHOUSE_DOMAIN_WWW);
		}
		$novels = $this->novelModel->get_novels($sqladd, 1);
		foreach($novels as &$v) {
			if ($searchType == 'novelName') {
				$v['novelName2'] = str_replace($keywords, "<span style=\"color:red\">$keywords</span>", $v['novelName']);
				$v['author2'] = $v['author'];
			} elseif($searchType == 'author') {
				$v['author2'] = substr_replace($keywords, "<span style=\"color:red\">$keywords</span>", $v['author']);
				$v['novelName2'] = $v['novelName'];
			}
		}
		$num = $this->novelModel->get_num(0, $sqladd);
		/* 分页显示 */
		$multipage = page2($num, GODHOUSE_PPP, $page, "novel-search.htm?keywords=$keywords&searchType=$searchType");
		
		$this->view->assign('novels', $novels);
		$this->view->assign('multipage', $multipage);
		$this->view->assign('keywords', $keywords);
		$this->view->assign('searchType', $searchType);
		$this->view->assign('hotNovels', $hotNovels);
		$this->display('novel_search');
	}
}