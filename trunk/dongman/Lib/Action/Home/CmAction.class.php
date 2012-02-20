<?php
class CmAction extends HomeAction{
	//生成验证码
    public function cmcode(){
	    import("ORG.Util.Image");
		Image::buildImageVerify();
    }
    //评论列表
    public function index(){
		$where['cm_cid']=$_GET['id'];$where['cm_sid']=getsid($_GET['cm_sid']);if(C('user_gbcm')){$where['cm_del']='0';}
		$currentPage=!empty($_GET['p'])?$_GET['p']:1;//当前页数
		$array=ppvodsql('cm','*',$where,'cm_addtime desc,cm_id desc',C('user_cmnum'),$currentPage);//按分页数据查询
		$totalPages=ceil($array['count']/C('user_cmnum'));//总页数
		$pageUrl='javascript:" onclick="plus_cm_get(\''.ppvodurl('cm/index',array('id' => $where['cm_cid'],'sid' => $where['cm_sid'])).'-p-{!page!}'.C('url_html_suffix').'\')';//分页链接参数
		$pageShow='共'.$array['count'].'条数据&nbsp;页次:'.$currentPage.'/'.$totalPages.'页&nbsp;'.ppvodpage($currentPage,$totalPages,5,$pageUrl);//组合分页信息
		foreach($array['list'] as $key=>$val){
			$array['list'][$key]['cm_floor']=$array['count']-(($currentPage-1)*5+$key);
		}
		$this->assign('cm_list',$array['list']);
		$this->assign('cm_count',$array['count']);
		$this->assign('cm_page',$pageShow);
		$this->display('pp_cm');
    }
	// 添加评论
    public function add(){
		$cid=$_POST['cm_cid'];
		$sid=getsid($_POST['cm_sid']);
		$rs=D("Home.Cm");
		if($rs->create()){
			$id=$rs->add();
			if(false!==$id){
				C('COOKIE_EXPIRE',C('user_second'));Cookie::set('cmadd-'.$sid.'-'.$cid,'ok');
				header("Location: ".C("site_path")."index.php?s=Cm-index-id-".$cid."-sid-".$sid);
			}else{
				$this->error('数据写入错误，请重试！');
			}
		}else{
		    $this->error($rs->getError());
		}
    }
	//ajax顶踩
    public function ajaxud(){
		$cmid = intval($_GET['id']);
		if($cmid){
			if(Cookie::is_set('cmud-'.$cmid)){
				exit('0');
			}
			$rs=D("Home.Cm");
			if('cm_up'==$_GET['tid']){
			    $rs->setInc('cm_up','cm_id='.$cmid);
				$arr=$rs->where('cm_id='.$cmid)->getField('cm_up');
			}else{
			    $rs->setInc('cm_down','cm_id='.$cmid);
				$arr=$rs->where('cm_id='.$cmid)->getField('cm_down');
			}
			C('COOKIE_EXPIRE',60*60*24);Cookie::set('cmud-'.$cmid,'ok');
			echo($arr);
		}
    }	
}
?>