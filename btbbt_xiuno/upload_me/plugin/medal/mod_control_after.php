
// 版主颁发勋章
public function on_medalgrant() {
	$this->_checked['mod_model'] = 'class="checked"';

	$this->_title[] = '颁发勋章';
	$this->_nav[] = '颁发勋章';

	$page = misc::page();

	if ($this->form_submit()) {
		$medalid = core::gpc('medalid', 'P');
		$medal = $this->medal->get($medalid);
		if (!$medal) {
			$this->message('错误，勋章不存在。', false);
		}
		$username = core::gpc('username', 'P');
		$uid = 0;
		if (!$username) {
			$this->message('错误，用户名不能为空。', false);
		} else {
			$user = $this->user->get_user_by_username($username);
			if (!$user) {
				$this->message('错误，用户名不存在。', false);
			} else {
				$uid = $user['uid'];
			}
		}
		$medaluser = $this->medal_user->get_medaluser_by_uid_medalid($uid, $medalid);
		if ($medaluser) {
			$this->message('勋章存在，不能重复颁发。', false);
		}

		$expiredtime = core::gpc('expiredtime', 'P');
		if ($expiredtime) {
			$expiredtime = time()+86400*intval($expiredtime);
		}

		$arr = array(
		'medalid'=>$medalid,
		'username'=>$username,
		'uid'=>$uid,
		'receivetype'=>$medal['receivetype'],
		'expiredtime'=>$expiredtime,
		'isapply'=>1,
		'fuid'=>$this->_user['uid'],
		'createdtime'=>time()
		);
		$this->medal_user->create($arr);

		$this->message('勋章发放成功。', true, "?mod-medalgrant-page-$page.htm");
	}

	$pagesize = 10;
	$num = $this->medal_user->count();
	$medaluserlist = $this->medal_user->get_medaluserlist_by_isapply(1, $page, $pagesize);
	$this->medal_user->medaluserlist_format($medaluserlist);

	$pages = misc::pages("?mod-medalgrant.htm", $num, $page, $pagesize);

	// 勋章列表
	$medallist = $this->medal->get_medallist_orderby_seq(1);

	$this->view->assign('medallist', $medallist);
	$this->view->assign('medaluserlist', $medaluserlist);
	$this->view->assign('pages', $pages);
	$this->view->assign('page', $page);

	$this->view->display('mod_grant.htm');
}

// 回收勋章
public function on_medalrecover() {
	$page = intval(core::gpc('page'));
	$muid = intval(core::gpc('muid'));
	$muids = empty($muid) ? core::gpc('muids', 'P') : array($muid);
	foreach((array)$muids as $muid) {
		$muid = intval($muid);
		$this->medal_user->_delete($muid);
	}

	$this->location("?mod-medalgrant-page-$page.htm");
}