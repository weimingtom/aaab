		
		// 切分为左右两列
		$n = count($threadlist);
		$threadlist_chunk = array();
		if($n > $pagesize / 2) { // $pagesize 为偶数
			$threadlist_chunk = array_chunk($threadlist, ceil(count($threadlist) / 2));
		} else {
			$threadlist_chunk = array($threadlist, array());
		}
		$this->view->assign('threadlist_chunk', $threadlist_chunk);