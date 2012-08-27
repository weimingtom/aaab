<?php

class GoodsAction extends Action {
	
     
    function index()
    {   
		
	  if($_GET['tag']){
		   $tag = M('goodstag')->where('tag="'. t($_GET['tag']).'"')->findall();
		    $key = $_GET['tag'];
		       foreach($tag as $s){
		          $sq[]=$s['goodsid'];
		        }
		  $map['weibo_id']=array('in',$sq);
		}
		$map['type']                  =5;
		$map['transpond_id']          =0;
		$map['isdel']                 =0;
		
		
	  	if($_GET['fenlei']){
		$map['fenlei_input']          =$_GET['fenlei'];
		}
		
		
		$page                         =$_GET['page'];
		
		$data=M('weibo')->where($map)->limit(40)->order('cTime DESC')->page($page)->findall();
		
		$tag = M('weibo')->where("isdel=0 AND transpond_id=0")->GROUP('fenlei_input')->findAll() ;
		

		
		
	    $this->assign('tag' , $tag) ;
	
		$this->assign('data' , $data) ;
		
		
		
		$this->setTitle($key.' 挑宝贝 ' .$page);
        $this->display() ;
		
		
		
    }
	
	function hot()
    {   
		
	  if($_GET['tag']){
		   $tag = M('goodstag')->where('tag="'. t($_GET['tag']).'"')->findall();
		    $key = $_GET['tag'];
		       foreach($tag as $s){
		          $sq[]=$s['goodsid'];
		        }
		  $map['weibo_id']=array('in',$sq);
		}
		$map['type']                  =5;
		$map['transpond_id']          =0;
		$map['isdel']                 =0;
		$page                         =$_GET['page'];
		
		$data=M('weibo')->where($map)->limit(40)->order('favcount DESC')->page($page)->findall();
		
	    
	
		$this->assign('data' , $data) ;
		
	
		
		$this->setTitle($key.' 挑宝贝 ' .$page);
        $this->display() ;
		
		
		
    }
	
	function recommend()
    {   
		
	  if($_GET['tag']){
		   $tag = M('goodstag')->where('tag="'. t($_GET['tag']).'"')->findall();
		    $key = $_GET['tag'];
		       foreach($tag as $s){
		          $sq[]=$s['goodsid'];
		        }
		  $map['weibo_id']=array('in',$sq);
		}

		$page                         =$_GET['page'];
		
		$starttime=mktime(0, 0, 0, date("m",strtotime("last Monday"))  , date("d",strtotime("last Monday")), date("Y",strtotime("last Monday")));
					$endtime=mktime(0, 0, 0, date("m",strtotime("next Sunday"))  , date("d",strtotime("next Sunday")), date("Y",strtotime("next Sunday"))); 
					//$wher='`count` > 0 and  `ctime` < '.$endtime.' and `ctime` > '.$starttime.'';
		
		$data=M('weibo')->where('type=5 and transpond_id=0 and isdel=0 and jiancount = 1 and `ctime` < '.$endtime.' and `ctime` > '.$starttime.'')->limit(40)->order('jiancount DESC')->page($page)->findall();
		//$data=M('weibo')->where($map)->limit(8)->order('cTime DESC')->page($page)->findall();
	    
	
		$this->assign('data' , $data) ;
		
		
		
		$this->setTitle($key.' 挑宝贝 ' .$page);
        $this->display() ;
		
		
		
    }
	

	
	//滑动时调用
	function load()
    {
	
	     if($_POST['tag']){
		 $tag = M('goodstag')->where('tag="'. t($_POST['tag']).'"')->findall();
		         foreach($tag as $s){
		          $sq[]=$s['goodsid'];
		        }
		     $sql='and weibo_id in('.implode(',',$sq).')';
		 }
		$data=M('weibo')->where('type=5 and transpond_id=0 and isdel=0 and weibo_id<'.$_POST['lastid'].' '.$sql.'')->limit(16)->order('cTime DESC')->findall();
	
		$this->assign('data' , $data) ;
        $this->display() ;
    }
    
	
   

	
}
