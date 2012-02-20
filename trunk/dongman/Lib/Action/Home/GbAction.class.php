<?php
class GbAction extends HomeAction{
	//生成验证码
    public function gbcode(){
	    import("ORG.Util.Image");
		Image::buildImageVerify();
    }
    //留言列表
    public function index(){
		$gb_content='请在此输入留言内容!';
		$get=$_GET;$currentPage=!empty($get['p'])?$get['p']:1;
		if(C('user_gbcm')){$where='gb_del=0';}
		$array=ppvodsql('gb','*',$where,'gb_oid desc,gb_addtime desc,gb_id desc',C('user_gbnum'),$currentPage);
		$totalPages=ceil($array['count']/C('user_gbnum'));
		$pageUrl=ppvodurl('gb/index').'-p-{!page!}'.C('url_html_suffix');
		$pageShow.='共'.$array['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,C('home_pagenum'),$pageUrl);
		foreach($array['list'] as $key=>$val){
			$array['list'][$key]['gb_floor']=$array['count']-(($currentPage-1)*5+$key);
		}
		if($get['id']){
			$rs=D("Home.Vod");
			$arr=$rs->field('vod_id,vod_name,vod_actor')->where('vod_del=0 and vod_id='.intval($_GET['id']))->find();
			if($arr){
				$gb_content='影片ID'.$arr['vod_id'].'点播出现错误！名称：'.$arr['vod_name'].' 主演：'.$arr['vod_actor'];
			}
		}
		$this->assign('gb_list',$array['list']);
		$this->assign('gb_count',$array['count']);
		$this->assign('gb_page',$pageShow);
		$this->assign('gb_cid',!empty($get['id'])?$get['id']:0);
		$this->assign('gb_content',$gb_content);
		$this->assign('pplist',A("Home"));
		$this->display('pp_gb');
    }
	// 添加留言
    public function add(){
		$rs=D("Home.Gb");
		if($rs->create()){
			if(false!==$rs->add()){
			$_SESSION['gb_lasttime']=time();
			$this->success('留言添加成功，我们会尽快审核及处理您的留言！');
			}else{
			$this->error('数据写入错误，请重试！');
			}
		}else{
		    $this->error($rs->getError());
		}
    }
}
?>