	// chunk $catelist
	
	// 根据屏幕宽度进行 chunk，每列宽 246 px
	$col = 4;
	$window_width = core::gpc('window_width', 'C');
	empty($window_width) && $window_width = 988;
	$window_width < 988 && $window_width = 988;
	$window_width > 1400 && $window_width = 1400;
	$col += floor(($window_width - 988) / 280);
	foreach($catelist as &$cate) {
		if(empty($cate['forumlist'])) continue;
		$cate['forumlist_chunk'] = array_chunk($cate['forumlist'], $col);
		$last = ceil(count($cate['forumlist']) / $col);
		$cate['forumlist_chunk'][$last - 1] = array_pad($cate['forumlist_chunk'][$last - 1], $col, array());
	}
	$forum_width = floor($window_width / $col);
	$this->view->assign('forum_width', $forum_width);