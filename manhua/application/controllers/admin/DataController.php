<?php
! defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );
class DataController extends AdminController
{
	public function __construct()
	{
		parent::__construct ();
	}

	public function actionIndex() {
		$this->display('data_index');
	}

	/* 输出数据表里面的数据以SQL形式存放在文件里 */
	public function actionTable() {
		if(getgpc('submitTo', 'P')) {
			set_time_limit(0);
			$isIgnoreKey = getgpc('isIgnoreKey', 'P');
			$saveType = getgpc('saveType', 'P');
			$condition = getgpc('condition', 'P');
			if(!$condition) {
				$this->_alert('请输入导出条件。', "admin.php?c=data&a=index");
			}

			/* 保存文件地址 */
			$saveFile = GODHOUSE_ROOT.'data/sql/'.date('Ymd', time()).'_'.rand(1,100);

			if($condition) {
				$condition = stripslashes($condition);
			}
			$num = $this->novelModel->get_contents_num($condition);
			//echo $num;exit;
			$prv = 1;
			$ppp = 1000;
			if($num > $ppp) {
				$prv = ceil($num / $ppp);
			}
			for($i=1; $i<=$prv; $i++) {
				usleep(500000);
				$fp = fopen($saveFile."_$i.sql", 'a');
				$s = '';
				$data = $this->novelModel->get_contents_list($condition, $i, $ppp);
				foreach($data as $v) {
					//$s .= "INSERT INTO god_novel_content SET contentId='{$v['contentId']}', novelId='{$v['novelId']}', title='".addslashes($v['title'])."', contentText='".addslashes($v['contentText'])."', createdTime='{$v['createdTime']}';\n";
					$s .= "INSERT INTO god_novel_content SET novelId='{$v['novelId']}', title='".addslashes($v['title'])."', contentText='".addslashes($v['contentText'])."', createdTime='{$v['createdTime']}';\n";
				}
				fwrite($fp, $s);
			}
			echo '导出成功';
		}
	}

	public function actionImportFile() {
		if(getgpc('submitTo', 'P')) {
			$upfile = $_FILES['importFile'];
			$filename = $upfile['name'];
			$saveType = getgpc('saveType', 'P');
			if(!$filename) {
				$this->_alert("请选择导入的文件。", "admin.php?c=data&a=index");
			}

			$dat = substr($filename, strpos($filename, '.')+1);
			set_time_limit(0);
			switch($saveType) {
				case 'log':
					if($dat != 'log') {
						$this->_alert("导入的文件不符合 $saveType 数据。", "admin.php?c=data&a=index");
					}
					$content = file_get_contents($upfile['tmp_name']);
					$this->AnalysisLog($content);
					break;
				case 'sql':
					if($dat != 'sql') {
						$this->_alert("导入的文件不符合 $saveType 数据。", "admin.php?c=data&a=index");
					}
					$content = file_get_contents($upfile['tmp_name']);
					$data = explode(";\r\n", $content);
					foreach($data as $s) {
						if($s) {
							$this->db->query($s);
						}
					}
					break;
				default:
					echo '上传的文件类型不正确。'; exit(0);
					break;
			}
			echo '导入成功。';
		}
	}

	/* 直接导入 */
	public function actionLogDir() {
		$dir = GODHOUSE_ROOT.'data/dataLog/log/';
		if (is_dir($dir)) {
			set_time_limit(0);
			if ($dh = opendir($dir)) {
				while(($file = readdir($dh)) !== false) {
					$fileLog = $dir.$file;
					if(filetype($fileLog) == 'file') {
						$content = file_get_contents($fileLog);
						$this->AnalysisLog($content);
						@unlink($fileLog);
					}
				}
				closedir($dh);
			}
		}
	}

	/* 解析LOG文件进行入库 */
	private function AnalysisLog($content) {
		$data = explode("\r\n", $content);
		foreach($data as $s) {
			if($s) {
				$arr = get_object_vars(json_decode($s));
				$arr['novelName'] = str_replace('?', '.', $arr['novelName']);
				$arr['author'] = str_replace('?', '.', $arr['author']);
				$contentArr = $arr['contentArr'];
				$novelUrl = $arr['novelUrl'];
				unset($arr['contentArr'], $arr['novelUrl']);
				$novelid = $this->novelModel->insert_novel($arr);
				$this->novelModel->insert_novel_spider(array('novelId'=>$novelid, 'novelUrl'=>$novelUrl));
				foreach($contentArr as $v) {
					$v = get_object_vars($v);
					$v['novelId'] = $novelid;
					$this->novelModel->insert_novel_content($v);
				}
			}
		}
	}
}