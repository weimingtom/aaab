<?php exit;?>
		// SEO 优化，商业版本使用
		$this->_title = array();
		if($thread['seo_keywords']) {
			$this->_title[] = $thread['seo_keywords'];
			$this->_seo_keywords = $thread['subject'];
		} else {
			$this->_title[] = $thread['subject'];
			$this->_seo_keywords = $thread['subject'];
		}