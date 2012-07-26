
	//
	private function get_toplist() {
		$toplist = $fids = $tids = array();
		$this->get_fids_tids($fids, $tids, $this->conf['runtime']['toptids']);
		$toplist = $this->thread->get($fids, $tids);
		return $toplist;
	}
	
	// copy from thread_control.class.php
	private function get_fids_tids(&$fids, &$tids, $s) {
		if($s) {
			$fidtidlist = explode(' ', trim($s));
			foreach($fidtidlist as $fidtid) {
				list($fid, $tid) = explode('-', $fidtid);
				$fids[] = $fid;
				$tids[] = $tid;
			}
		} else {
			$fids = $tids = array();
		}
	}