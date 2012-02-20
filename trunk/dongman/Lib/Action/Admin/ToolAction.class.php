<?php
ini_set('memory_limit', '-1');//取消内存限制
class ToolAction extends BaseAction{
    public function index(){
		$rs = new Model();
		$result=$rs->query('SHOW TABLES FROM '.C('db_name'));
		$info=array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
		$this->assign('table',$info);
        $this->display(APP_PATH.'/Public/admin/tool.html');
    }
	
    public function backup(){//处理备份
		$file = DATA_PATH.'_bak/'; $random = mt_rand(1000, 9999); $sql=""; $p=1;
		foreach($_POST['tables'] as $table){
			$rs=D("Admin.".str_replace(C('db_prefix'),'',$table));
			$array = $rs->select();
			$sql.="TRUNCATE TABLE `$table`;\n";//$sql.="-- \n-- $table\n-- \n";
			foreach($array as $value){
				$sql.=$this->get_insert_sql($table, $value);
				if(strlen($sql)>=$_POST['filesize']*1000){
					$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
					write_file($filename,$sql);
					$p++;$sql='';
				}
			}
		}
		if(!empty($sql)){
			$filename = $file.date('Ymd').'_'.$random.'_'.$p.'.sql';
			write_file($filename,$sql);
		}
		$this->assign("jumpUrl",'index.php?s=Admin-Tool-Restore');
		$this->success('数据库分卷备份已完成,共分成'.$p.'个sql文件存放!');
    }
	
	public function get_insert_sql($table, $row){//生成备份语句
		$sql = "INSERT INTO `{$table}` VALUES ("; 
		$values = array(); 
		foreach ($row as $value) { 
			$values[] = "'" . mysql_real_escape_string($value) . "'"; 
		} 
		$sql .= implode(', ', $values) . ");\n"; 
		return $sql; 
	}	
	
    public function restore(){//获取还原
		$file = DATA_PATH.'_bak/*.sql';
		$sqlfiles = glob($file);
		if(!empty($sqlfiles)){
			foreach($sqlfiles as $k=>$sqlfile){
				//preg_match("/([a-z0-9_]+_[0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				preg_match("/([0-9]{8}_[0-9a-z]{4}_)([0-9]+)\.sql/i",basename($sqlfile),$num);
				$info[$k]['filename'] = basename($sqlfile);
				$info[$k]['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
				$info[$k]['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
				$info[$k]['pre'] = $num[1];
				$info[$k]['number'] = $num[2];
				$info[$k]['path'] = $file = DATA_PATH.'_bak/';
			}
			$this->assign('sql_list',$info);
        	$this->display(APP_PATH.'/Public/admin/tool.html');
		}else{
			$this->assign("jumpUrl",'index.php?s=Admin-Tool');
			$this->error('没有检测到备份文件,请先备份或上传备份文件到'.DATA_PATH.'_bak/');
		}
    }
	
	public function update(){//处理还原
		$rs = new Model();
		$pre=$_GET['id'];
		$fileid = $_GET['fileid'] ? $_GET['fileid'] : 1;
		$filename = $pre.$fileid.'.sql';
		$filepath = DATA_PATH.'_bak/'.$filename;
		if(file_exists($filepath)){
			$sql = read_file($filepath);
			$sql = str_replace("\r\n", "\n", $sql); 
			foreach(explode(";\n", trim($sql)) as $query) {
				$rs->query(trim($query)); 
			}
			$fileid++;
			$this->assign("jumpUrl",'index.php?s=Admin-Tool-Update-id-'.$pre.'-fileid-'.$fileid.'');
			$this->success('数据库分卷'.$fileid.'恢复成功,准备恢复下一个分卷,请稍等!');
		}else{
			$this->assign("jumpUrl",'index.php?s=Admin-Index-Main');
			$this->success('数据库恢复成功!');
		}		
	}
	
	public function down(){//下载还原
		$file=DATA_PATH.'_bak/'.$_GET['id'];
		if(file_exists($file)){
			$filename = $filename ? $filename : basename($file);
			$filetype = trim(substr(strrchr($filename, '.'), 1));
			$filesize = filesize($file);
			header('Cache-control: max-age=31536000');
			header('Expires: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
			header('Content-Encoding: none');
			header('Content-Length: '.$filesize);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Type: '.$filetype);
			readfile($file);
			exit;
		}
	}
		
	public function del(){//删除还原
		$filename=$_GET['id'];
		@unlink(DATA_PATH.'_bak/'.$filename);
		$this->success($filename.'已经删除!');
	}
	
	public function delall(){//删除所有还原
		foreach($_POST['filename'] as $value){
			@unlink(DATA_PATH.'_bak/'.$value);
		}
		$this->success('批量删除备份文件成功！');
	}	
	
    public function runsql(){//执行sql语句
		$sql=trim($_POST['sql']);
		if(empty($sql)){
			$this->error('SQL语句不能为空!');
		}else{
			$sql=trim(stripslashes($sql));
			$rs = new Model();
			$rs->query($sql);
			$this->success('SQL语句成功运行!');
		}
    }	
	
    public function ajaxfields(){//ajax获取字段信息
		$id=str_replace(C('db_prefix'),'',$_GET['id']);
		if(!empty($id)){
			$rs=D("Admin.".$id);
			$array=$rs->getDbFields();
			echo "<div style='border:1px solid #ababab;width:500px;background-color:#FEFFF0;margin-top:6px;padding:3px;line-height:160%'>";
			echo "表(".C('db_prefix').$id.")含有的字段：<br>";
			foreach($array as $key=>$val){
				if(!is_int($key)){break;}
				echo "<a href=\"javascript:rpfield('".$val."')\">".$val."</a>\r\n";
			}
			echo "</div>";
		}else{
			echo 'no fields';
		}
    }
	
    public function replacefield(){//批量替换
		if(empty($_POST['rpfield'])){$this->assign("jumpUrl","index.php?s=Admin-Tool-index-3");$this->error("请手工指定要替换的字段!");}
		if(empty($_POST['rpstring'])){$this->assign("jumpUrl","index.php?s=Admin-Tool-index-3");$this->error("请指定要被替换内容！");}
		$exptable=str_replace(C('db_prefix'),'',$_POST['exptable']);
		$rs=D("Admin.".$exptable);
		$exptable=C('db_prefix').$exptable;//表
		$rpfield=trim($_POST['rpfield']);//字段
		$rpstring=$_POST['rpstring'];//被替换的
		$tostring=$_POST['tostring'];//替换内容
		$condition=trim($_POST['condition']);//条件
		$condition=empty($condition) ? '' : " where $condition ";
		$rs->execute(" update $exptable set $rpfield=Replace($rpfield,'$rpstring','$tostring') $condition ");
		$this->success('批量替换完成!');
    }										
}
?>