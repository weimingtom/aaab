$medallist = $this->medal_user->get_user_medal($this->_user['uid']);
$this->view->assign('medallist', $medallist);