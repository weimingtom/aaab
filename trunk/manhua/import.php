<?php
//exit(0);
header("Content-Type:text/html; charset=utf-8");
error_reporting ( E_ALL );

set_time_limit(0);

define ( 'IN_ROOT', true );
define ( 'ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/' );


require ROOT_PATH.'library/config.php';
require ROOT_PATH.'library/db.php';
require ROOT_PATH.'library/function.php';

$db = new db();
$db->connect(DBHOST, DBUSER, DBPW, DBNAME, DBCHAR);

function insert_novel($arr) {
	global $db;
	$insert_id = 0;
	if($arr && is_array($arr)) {
		$insert_id = $db->insert('god_novel', $arr);
	}
	return $insert_id;
}
function insert_novel_content($arr) {
	global $db;
	$insert_id = 0;
	if($arr && is_array($arr)) {
		$db->insert('god_novel_content', $arr);
	}
	return $insert_id;
}
function insert_novel_spider($arr) {
	global $db;
	if($arr && is_array($arr)) {
		$db->insert('god_novel_spider', $arr);
	}
}
function insert_novel_chapter($arr) {
	global $db;
	$chapterId = 0;
	if($arr && is_array($arr)) {
		$chapterId = $db->insert('god_novel_chapter', $arr);
	}
	return $chapterId;
}

function readBigFileLines($filename, $startLine = 1,$endLine=50) 
{
	$content = '';
	if($endLine < $startLine)
	{
		return 'error:end line error';
	}
	$count = $endLine - $startLine; 
	$fp = fopen($filename,'r');//读模式打开文件
	if(!$fp)
	{
		return 'error:can not read file';
	}

	for ($i=1;$i<$startLine;$i++) 
	{
		if(!feof($fp))
		{
			fgets($fp);//跳过前$startLine行
		}
	}

	for($i;$i<=$endLine;$i++)
	{
		if(!feof($fp))
		{
			$content .= fgets($fp);//读取文件行内容
		}
	}
	fclose($fp);
	return $content;
}

$filename = dirname(__FILE__) . '/xsdata/7/7.log';//需要读取的文件
//$filename = dirname(__FILE__).'/data/xsdata.log';//需要读取的文件
$line = 1;
$step = 50;
while(true) {
	$startLine = ($line-1)*$step;		
	$endLine = $startLine + $step;
	
	$data = readBigFileLines($filename, $startLine+1, $endLine);
	if(empty($data)) break;
	$line++;
	
	/* key源ID value现上目标ID */
	/*$parentId = 6;					// 奇幻异幻
	$cate_1 = array(
			'西方魔幻'=>31,
			'东方奇幻'=>32,
			'魔法校园'=>34,
			'异世大陆'=>34,
			'转世重生'=>34,
			'诸神传说'=>33,
			'异类兽族'=>34,
			'亡灵骷髅'=>34,
	);
	
	$parentId = 5;						// 言情小说
	$cate_1 = array(
			'都市生活'=>43,
			'豪门恩怨'=>43,
			'浪漫言情'=>30,
			'白领生涯'=>48,
	);
	
	$parentId = 4;						// 武侠小说
	$cate_1 = array(
			'传统武侠'=>28,
			'古典仙侠'=>29,
			'现代修真'=>49,
			'奇幻修真'=>49,
			'谐趣武侠'=>49,
			'洪荒神话'=>29,
			'江湖情仇'=>28,
			'国术武技'=>28,
			'浪子异侠'=>28,
	);
	
	$parentId = 7;						// 武侠小说
	$cate_1 = array(
			'恐怖惊栗'=>38,
			'灵异神怪'=>36,
			'推理侦探'=>35,
			'风水命理'=>50,
			'神秘探险'=>37,
	);
	
	$parentId = 6;						// 奇幻异幻
	$cate_1 = array(
			'未来世界'=>51,
			'骇客时空'=>51,
			'星际机甲'=>51,
			'电子竞技'=>51,
			'体育竞技'=>51,
			'虚拟网游'=>51,
			'游戏异界'=>51,
			'末日危机'=>51,
	);
	
	$parentId = 5;						// 都市小说
	$cate_1 = array(
			'异术超能'=>53,
			'商海沉浮'=>48,
			'现代纪实'=>52,
			'官海风云'=>48,
			'热血都市'=>43,
			'都市生活'=>43,
			'谍战特工'=>53,
			'娱乐明星'=>53,
	);*/
	
	$parentId = 2;						// 现代文学
	$cate_1 = array(
			'架空历史'=>21,
			'历史传记'=>21,
			'历史穿越'=>21,
			'军事战争'=>20,
			'特种军旅'=>20,
			'战争幻想'=>20,
			'外国历史'=>54,
			'抗战烽火'=>20,
	);
	
	$data = explode("\n", $data);
	if(empty($data)) break;
	
	foreach($data as $moreArr){
		$s = json_decode($moreArr);
		if(is_array($s)){
			foreach($s as $val){
				$insertArr = get_object_vars($val);
				
				$author= str_replace('作者：','',$insertArr['author']);
				$categoryName = str_replace('类型：', '', $insertArr['lxtype']);
				$categoryId = $cate_1[$categoryName];
				if ($categoryId == 54) {
					$parentId = 10;
				} else {
					$parentId = 2;
				}
				$catename = $db->result_first("select cate_name from mh_categories where id='$categoryId'");
				
				$savepath = 'data/picture/'.$parentId.'/'.$categoryId.'/';
				mkDirs(ROOT_PATH.$savepath);
				$saveimage = $savepath.time().rand(1, 1000).'.jpg';
				saveimg('http://www.readnovel.com', $insertArr['xspic'], ROOT_PATH.$saveimage);
				$novel = array(
					'novelName'=>$insertArr['title'],
					'parentId'=>$parentId,
					'categoryId'=>$categoryId,
					'categoryName'=>$catename,
					'author'=>$author,
					'picture'=>$saveimage,
					'comment'=>$insertArr['jieshao'],
					'totals'=>$insertArr['totals'],
					'createdTime'=>time(),
					'modifiedTime'=>time(),
				);
				$novelId = insert_novel($novel);
				insert_novel_spider(array(
					'novelId'=>$novelId,
					'novelUrl'=>$insertArr['ylink'],
				));
				foreach($insertArr['jtxscontents'] as $jtArr)
				{
					$jtArrt = get_object_vars($jtArr);
	
					$chapter = array(
							'novelId' => $novelId,
							'title' => daddslashes($jtArrt['title'], 1), 
							'comment' => daddslashes($jtArrt['zjjieshao'], 1),
							'createdTime' => time(),
					);
					$chapterId = insert_novel_chapter($chapter);
					
					foreach($jtArrt['xscontents'] as $nrjtArr)
					{
						$jtArrs = get_object_vars($nrjtArr);
						$content = array(
								'novelId' => $novelId,
								'chapterId' => $chapterId,
								'title' => daddslashes($jtArrs['linkname'], 1),
								'contentText' => daddslashes($jtArrs['contents'], 1),
								'views' => 1,
								'createdTime' =>time(),
							);
							insert_novel_content($content);
							usleep(500000);
					}
				}
			}
		}
	}
}
@unlink($filename);
exit;

/*

$data = explode("\n", $data);

foreach($data as $moreArr)
{
	$data = json_decode($moreArr);
	
	if(is_array($data))
	{
		foreach($data as $val)
		{
			$insertArr = get_object_vars($val);

			$db->insert('xs_novels_list', 
				array(
					'title' => $insertArr['title'],
					'ylink' => $insertArr['ylink'],
					'xspic' => $insertArr['xspic'],
					'author' => str_replace('作者：','',$insertArr['author']),
					'maxtype' => $insertArr['maxtype'],
					'lxtype' => str_replace('类型：', '', $insertArr['lxtype']) ,
					'totals' => str_replace('总字数：', '', $insertArr['totals']) ,
					'jieshao' => daddslashes($insertArr['jieshao'], 1),
					'zdvip' => $insertArr['zdvip'],
					'lzstat' => str_replace('状态：', '', $insertArr['lzstat']),
					'zztime' => date('Ymd')			
				));
			
			$listid = $db->insert_id();

			foreach($insertArr['jtxscontents'] as $jtArr)
			{
				$jtArrt = get_object_vars($jtArr);

				$db->insert('xs_novels_zhangjie', array(
						'xsid' => $listid,
						'title' => daddslashes($jtArrt['title'], 1), 
						'zjjieshao' => daddslashes($jtArrt['zjjieshao'], 1)
				));
				
				$zjid = $db->insert_id();


				foreach($jtArrt['xscontents'] as $nrjtArr)
				{
					$jtArrs = get_object_vars($nrjtArr);

					$db->insert('xs_novels_contents', array(
							'xsid' => $listid,
							'zjid' => $zjid,
							'linkname' => daddslashes($jtArrs['linkname'], 1),
							'contents' => daddslashes($jtArrs['contents'], 1)
						));
				}

			}

		}
	}
}

echo "\n已完成\n";

*/

?>
