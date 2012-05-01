<?php
class ApiAction extends HomeAction
{
	public function vodlist(){
		$rs = D("Home.Video");
		$vodlist = S('api_vodlist');
		if (empty($vodlist)) {
			$arr = $rs->field('id, title, picurl')->where()->order('hits desc')->limit(40)->select();
			$vodlist[] = $arr[rand(1, 10)];
			$vodlist[] = $arr[rand(11, 20)];
			$vodlist[] = $arr[rand(21, 30)];
			$vodlist[] = $arr[rand(31, 40)];
			S('api_vodlist', $vodlist, 3600, 'File');
		}
		$this->assign('vodlist', $vodlist);
		$this->display('api_vodlist');
	}
}
?>