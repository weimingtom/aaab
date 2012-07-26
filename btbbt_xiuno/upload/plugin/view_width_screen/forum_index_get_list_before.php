	$window_height = core::gpc('window_height', 'C');
	empty($window_height) && $window_height = 600;
	$header_height = 266;
	$line_height = 31;
	$pagesize = ceil((($window_height - $header_height) / $line_height)) * 2;
	$pagesize = max(30, $pagesize);
	
	$window_width = core::gpc('window_width', 'C');
	empty($window_width) && $window_width = 988;
	$window_width < 988 && $window_width = 988;
	$window_width > 1400 && $window_width = 1400;
	$this->view->assign('window_width', $window_width);