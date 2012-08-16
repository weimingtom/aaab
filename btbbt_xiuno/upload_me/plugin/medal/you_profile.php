$medallist = $this->medal_user->get_user_medal($this->you['uid']);
$this->view->assign('medallist', $medallist);