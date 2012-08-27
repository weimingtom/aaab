<?php 
class IndexAction extends Action{
    
	function init(){
		echo './Public/miniblog.js';
	}
    
    
    //加载评论
    function loadcomment(){
        $intMinId = intval( $_POST['id'] );
        $data['weibo_id'] = $intMinId;
        $data['quick_reply'] = intval($_POST['quick_reply']);
        $data['quick_reply_uname'] = t($_POST['quick_reply_uname']);
        $data['quick_reply_comment_id'] = intval($_POST['quick_reply_comment_id']);
        $data['callback'] = t($_POST['callback']);
        $data['data']  = D('Operate')->where('weibo_id='.$intMinId)->find();
        $data['privacy'] = D('UserPrivacy','home')->getPrivacy($this->mid,$data['data']['uid']);
        $data['randtime'] = ($data['quick_reply_comment_id'])?$data['quick_reply_comment_id']:$data['data']['weibo_id'] ;
        if(!$data['quick_reply']) $data['list'] =  D('Comment')->getComment($intMinId);
        $this->assign( $data );
        $this->display();
    }
	//仿知美二次开发
	function publishcomment(){
        $intMinId = intval( $_GET['id'] );
        $data['weibo_id'] = $intMinId;
        $data['quick_reply'] = intval($_POST['quick_reply']);
        $data['quick_reply_uname'] = t($_POST['quick_reply_uname']);
        $data['quick_reply_comment_id'] = intval($_POST['quick_reply_comment_id']);
        $data['callback'] = t($_POST['callback']);
        $data['data']  = D('Operate')->where('weibo_id='.$intMinId)->find();
        $data['privacy'] = D('UserPrivacy','home')->getPrivacy($this->mid,$data['data']['uid']);
        $data['randtime'] = ($data['quick_reply_comment_id'])?$data['quick_reply_comment_id']:$data['data']['weibo_id'] ;
        if(!$data['quick_reply']) $data['list'] =  D('Comment')->getComment_5($intMinId);
        $this->assign( $data );
        $this->display();
    }
    
    
    //加载更多的
    function loadmore(){
    	$data['type'] = $_POST['type'];
    	$data['gid']  = $_POST['gid'];
    	$data['list'] = D('Operate')->getHomeList($this->mid,$data['type'],$_POST['since'],$_POST['limit'],$data['gid']);
    	$this->assign($data);
    	$this->display();
    }
    
    function loadnew(){
    	// 每120秒刷新一次!
    	if ( !lockSubmit('10') ) {
    		exit('<TSAJAX>');
    	}
    	$data['showfeed'] = intval($_REQUEST['showfeed']);
    	$data['list'] = D('Operate')->loadNew($this->mid,$_POST['since'],$_POST['limit'],$data['showfeed']);
    	$this->assign($data);
    	
    	// NO unlockSubmit(); !!!
    	
    	$this->display('loadmore');
    }
    
    //查看最新的
    function countnew(){
    	$data['showfeed'] = intval($_REQUEST['showfeed']);
    	$data['lastId'] = intval($_POST['lastId']);
    	$list = D('Operate')->countNew($this->mid,$data['lastId'],$data['showfeed']);
    	$data['since'] = $list[0]['weibo_id'];
    	$data['limit'] = count($list);
    	$this->assign($data);
    	$this->display();
    }
    
    //@xxx
    function searchuser(){
    	$name = t($_REQUEST['n']);
    	$list = M('user')->where("uname LIKE '{$name}%'")->field('uid,uname')->findall();
    	exit( json_encode($list));
    }
}
?>