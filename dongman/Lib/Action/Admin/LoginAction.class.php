<?php
class LoginAction extends BaseAction{
    //默认操作
    public function index(){
		if ($_SESSION[C('USER_AUTH_KEY')]){
			redirect("index.php?s=Admin-Index");
		}	
		$this->display(APP_PATH.'/Public/admin/login.html');
    }
	
	//生成验证码
    public function vcode(){
	    import("ORG.Util.Image");
		Image::buildImageVerify();//6,0,'png',1,20,'verify'
    }
	
	//登录检测
    public function check(){
	    if(empty($_POST['user_name'])){$this->error('帐号必须！');}
		if(empty($_POST['user_pwd'])){$this->error('密码必须！');}
		if(function_exists('gd_info')){
		    if(empty($_POST['verify'])){$this->error('验证码必须！');}
			if($_SESSION['verify']!=md5($_POST['verify'])){$this->error('验证码错误！');}
		}
        //生成认证条件
        $map=array();
		//支持使用绑定帐号登录
		$map['admin_name']=$_POST['user_name'];
        //$map["user_status"]=array('gt',0);//状态
		$rs=D("Admin.Admin");
		$authInfo=$rs->where($map)->find();
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            if($authInfo['admin_pwd']!=md5($_POST['user_pwd'])){
            	$this->error('密码错误！');
            }
			// 缓存访问权限
            $_SESSION[C('USER_AUTH_KEY')]=$authInfo['admin_id'];
			$_SESSION['admin_ok']=$authInfo['admin_ok'];
			$_SESSION['admin_name']=$authInfo['admin_name'];			
            //$_SESSION['email']=$authInfo['admin_email'];
            //$_SESSION['lastLoginTime']=$authInfo['admin_logintime'];
			//$_SESSION['login_count']=$authInfo['admin_count'];
            //if($authInfo['user_name']=='admin') {
            	//$_SESSION['administrator']		=	true;
            //}	
            //保存登录信息
			$ip=get_client_ip();
            $data=array();
			$data['admin_id']=$authInfo['admin_id'];
			$data['admin_logintime']=time();
			$data['admin_count']=array('exp','admin_count+1');
			$data['admin_ip']=get_client_ip();
			$rs->save($data);
			redirect('index.php?s=Admin-Index');					
		}
    }	
	
	// 用户登出
    public function logout(){
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
            $this->assign('jumpUrl','index.php?s=Admin-Login');
            $this->success('登出成功！');
        }else {
            $this->error('已经登出！');
        }
    }		
}
?>