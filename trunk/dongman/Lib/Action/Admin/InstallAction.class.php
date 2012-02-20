<?php
class InstallAction extends BaseAction{	
    public function _initialize() {
	    header("Content-Type:text/html; charset=".DEFAULT_CHARSET);
		if(is_file(APP_PATH.'/Public/install/install.lock')){
			$this->error('已经安装过PPVOD影视系统,重新安装请先删除Public/install/install.lock 文件!');
		}
    }
    public function index(){	
        $this->display(APP_PATH.'/Public/admin/install.html');
    }
    public function second(){	
        $this->display(APP_PATH.'/Public/admin/install.html');
    }
    public function setup(){	
        $this->display(APP_PATH.'/Public/admin/install.html');
    }	
    public function install(){
		$data = $_POST['data'];
		$rs = @mysql_connect($data['db_host'].":".$data['db_port'],$data['db_user'],$data['db_pwd']);
		if(!$rs){
			$this->error('数据库连接失败!请检查数据库连接参数!');	
		}
		// 数据库不存在,尝试建立
		if(!@mysql_select_db($data['db_name'])){
			$sql = "CREATE DATABASE `".$data["db_name"]."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
			mysql_query($sql);
		}
		// 建立不成功
		if(!@mysql_select_db($data['db_name'])){
			$this->error('建立失败,请手动创建数据库!~或者填写管理员分配的数据库名!');
		}
		// 保存配置文件
		//$config_old = C();
		$config = array(
		    'site_path'=>$data['site_path'],
			'db_host'=>$data['db_host'],
			'db_name'=>$data['db_name'],
			'db_user'=>$data['db_user'], 
			'db_pwd'=>$data['db_pwd'],
			'db_port'=>$data['db_port'],
			'db_prefix'=>$data['db_prefix'],
		);
		$array=require APP_PATH.'/Conf/setting.php';
		$new=array_merge($array,$config);
		arr2file(APP_PATH.'/Conf/setting.php', $new);
		// 批量导入安装SQL
		$db_config = array('dbms'=>'mysql','username'=>$data['db_user'],'password'=>$data['db_pwd'],'hostname'=>$data['db_host'],'hostport'=>$data['db_port'],'database'=>$data['db_name']);	
		$sql = read_file(APP_PATH.'/Public/install/install.sql');
		$sql = str_replace('pp_',$data['db_prefix'],$sql);
		$this->batQuery($sql,$db_config);
		touch(APP_PATH.'/Public/install/install.lock');
		@unlink(APP_PATH.'/Runtime/~app.php');
		@unlink(APP_PATH.'/Runtime/~runtime.php');
		$this->assign("jumpUrl",'index.php?s=Admin-Login');
		$this->success('恭喜您！PPVOD系统安装完成，点击进入后台管理！!');
    }
	public function batQuery($sql,$db_config){
	    // 连接数据库
		//import('Think.Db.Driver.Dbmysql');
		require THINK_PATH.'/Lib/Think/Db/Driver/DbMysql.class.php';
		$db = new Dbmysql($db_config);
		$sql = str_replace("\r\n", "\n", $sql); 
		$ret = array(); 
		$num = 0; 
		foreach(explode(";\n", trim($sql)) as $query){
			$queries = explode("\n", trim($query)); 
			foreach($queries as $query) { 
				$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query; 
			} $num++; 
		} 
		unset($sql); 
		foreach($ret as $query) {  
			if(trim($query)) { 
			    $db->query($query); 
			} 
		} 
		return true; 
	}								
}
?>