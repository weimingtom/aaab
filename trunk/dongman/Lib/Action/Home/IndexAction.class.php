<?php
class IndexAction extends HomeAction{
	public function index(){
		$lock = './Public/install/install.lock';
		if (!is_file($lock)) {
			$this->assign("jumpUrl",'index.php?s=Admin-Install');
			$this->error('您还没安装本程序，请运行 install.php 进入安装!');
		}
		
		$vod = D('Home.Vod');
		
		// 昨天
		$yesterdaystart = strtotime(date('Y-m-d', strtotime('-1 day')));
		$yesterdayend = $yesterdaystart + 86400;
		$vodlist['yesterday'] = $vod->field("vod_name")->where("vod_addtime>='$yesterdaystart' AND vod_addtime<='$yesterdayend'")->limit('22')->select();
		$yesterdaystart2 = $yesterdaystart - 86400;
		$yesterdayend2 = $yesterdaystart;
		$vodlist['yesterday2'] = $vod->field("vod_name,vod_url,vod_color")->where("vod_addtime>='$yesterdaystart2' AND vod_addtime<='$yesterdayend2'")->limit('22')->select();
		
		$this->assign('title',C('site_name').'-首页'.C('site_by'));
		$this->assign('pplist', A("Home"));
		$this->assign('vodlist', $vodlist);
		$this->display('pp_index');
	}
}?>