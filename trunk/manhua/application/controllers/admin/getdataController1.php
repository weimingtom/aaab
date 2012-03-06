<?php
!defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );
class GetdataController extends adminbase
{
	public function __construct()
	{
		parent::__construct ();
		
		$this->load('admin_getdata');
	}
	
	public function actionDatabase()
	{
		$appgetID = get('appgetID', 'P');
		
		if($appgetID == 126) $appget = 'wenshen';
		elseif ($appgetID == 127) $appget = 'xingwei';
		elseif ($appgetID == 125) $appget = 'caihui';
		elseif ($appgetID == 128) $appget = 'jiqiao';
		elseif ($appgetID == 122) $appget = 'yishu';
		elseif ($appgetID == 123) $appget = 'shenying';
		elseif ($appgetID == 149) $appget = 'heibai';
		else $appget = '';
	
		if(isset($_POST['action']) && !empty($_POST['action']) && !empty($appget))
		{			
			set_time_limit(0);
			
			$get003dhContent = @file_get_contents(ROOT_PATH . "/data/003dh.com/$appget-20101015-data.log");
	
			if( !empty($get003dhContent) )
			{
				$get003dhArr = explode("\t\n", $get003dhContent);
	
				foreach ($get003dhArr as $val)
				{
					if(!empty($val) && isset($val))
					{
						$zzdArr = @get_object_vars(json_decode($val));
	
						if(isset($zzdArr[5][0]) && !empty($zzdArr[5][0]))
						{
							if( !$this->db->result_first("SELECT COUNT(*) FROM mh_product WHERE smallpic = '". $zzdArr[3] ."' ") )
							{
								$id = $this->db->insert('mh_product', array(
									'cateid' => $appgetID,
									'title' => $zzdArr[2],
									'smallpic' => $zzdArr[3],
									'author' => $zzdArr[4],
									'zpnum' => count($zzdArr[5]) ,
									'addtime' => date('Ymd')
								));
								
								if(is_numeric($id) && !empty($id))
								{
									foreach ($zzdArr[5] as $imgadress)
									{
										$this->db->insert('mh_product_img', array('productid' => $id, 'imgaddress' => $imgadress));
									}
								}
							}
							usleep(100000);
						}
						else 
						{
							file_put_contents(ROOT_PATH . '/data/err.log', $zzdArr[1] . "\n", FILE_APPEND);
						}
					}
				}
			}
			exit('数据插入完成.');
		}
		else 
		{
			exit('数据插入失败.');
		}
		
	}
	
	public function actionIndex()
	{
		if((isset($_REQUEST['mtid']) && is_array($_REQUEST['mtid'])) || isset($_REQUEST['mtid']))
		{
			
			$mtid = daddslashes($_REQUEST['mtid']);

			$mtid = is_array($mtid) ? implode(',', $mtid) : $mtid;
			
			$this->cmd->delmtData( $mtid );
			
			exit('<script>location.href="admin.php?c=getdata";</script>');
			
		}
		
		if((isset($_REQUEST['P']) && is_array($_REQUEST['P'])) || isset($_REQUEST['P']))
		{
			
			$pid = daddslashes($_REQUEST['P']);
			$ydcateid = daddslashes($_REQUEST['ydcateid']);
			
			$pid = is_array($pid) ? implode(',', $pid) : $pid;
			
			$this->cmd->yidongData( $ydcateid, $pid );
			
			exit('<script>location.href="admin.php?c=getdata";</script>');
			
		}
		
		
		$data['cateid'] = (int)get('cateid');
		$data['keyname'] = get('keyname');
		
		daddslashes(&$data);
		
		list($prArr, $pageData) = $this->cmd->cateData($data);
		
		require ROOT_PATH . '/application/models/admin/category.php';
		
		$cgory = new admin_category( $this );
		
		$this->view->assign('serchData', $data);
		$this->view->assign('options', $cgory->get_options());
		$this->view->assign('dataArr', $prArr);
		$this->view->assign('pageData', $pageData);
		
		$this->display('getdata_index');
	}
	
	public function actionAdd()
	{
		require ROOT_PATH . '/application/models/admin/category.php';
		
		$cgory = new admin_category( $this );
		
		$this->view->assign('options', $cgory->get_options());
		
		$this->display('getdata_add');
	}
	
	public function actionGetimg()
	{
		set_time_limit(0);
		
		$appdata = get('app', 'G');

		$get003dhContent = @file_get_contents(ROOT_PATH . "/data/003dh.com/$appdata-20101015-data.log");

		if( !empty($get003dhContent) )
		{
			$get003dhArr = explode("\t\n", $get003dhContent);
			
			$stati = 0;
			
			foreach ($get003dhArr as $val)
			{
				if(!empty($val) && isset($val))
				{
					$zzdArr = @get_object_vars(json_decode($val));
					
					if(isset($zzdArr[3]) && !empty($zzdArr[3]) && isset($zzdArr[5][0]) && !empty($zzdArr[5][0]))
					{
						if( strpos($zzdArr[3], 'tu.jueserenti.com') !== false )
						{							
							$parseurl = parse_url($zzdArr[3]);
							
							$zzdArr[3] = $parseurl['path'];
							
							if(!is_file(ROOT_PATH . '/upload' . $zzdArr[3]))
							{
								mkDirs( ROOT_PATH . '/upload' . dirname($zzdArr[3]) );
								
								if(is_dir(ROOT_PATH . '/upload' . dirname($zzdArr[3])))
								{
									saveimg('http://tu.jueserenti.com', 'http://tu.jueserenti.com' . $zzdArr[3], ROOT_PATH . '/upload' . $zzdArr[3]);
									$stati ++;
									
									if(isset($zzdArr[5][0]) && !empty($zzdArr[5][0]))
									{
										foreach($zzdArr[5] as $Mval)
										{
											$Mparseurl = parse_url($Mval);
							
											$Mval = $Mparseurl['path'];
							
											if(!is_file(ROOT_PATH . '/upload' . $Mval))
											{
												mkDirs( ROOT_PATH . '/upload' . dirname($Mval) );
												
												if(is_dir(ROOT_PATH . '/upload' . dirname($Mval)))
												{
													saveimg('http://tu.jueserenti.com', 'http://tu.jueserenti.com' . $Mval, ROOT_PATH . '/upload' . $Mval);
													$stati ++;
												}
											}
										}
									}
								}
							}							
						}
						else
						{
							if(!is_file(ROOT_PATH .'/'.$zzdArr[3]))
							{
								mkDirs( ROOT_PATH .'/'. dirname($zzdArr[3]) );
								
								if(is_dir(ROOT_PATH .'/'. dirname($zzdArr[3])))
								{
									saveimg('http://www.003dh.com', "http://www.003dh.com$zzdArr[3]", ROOT_PATH .'/'. $zzdArr[3]);
									$stati ++;
									
									if(isset($zzdArr[5][0]) && !empty($zzdArr[5][0]))
									{
										foreach($zzdArr[5] as $Mval)
										{
											if(!is_file(ROOT_PATH .'/'. $Mval))
											{
												mkDirs( ROOT_PATH .'/'. dirname($Mval) );
												if(is_dir(ROOT_PATH .'/'. dirname($Mval)))
												{
													saveimg('http://www.003dh.com', "http://www.003dh.com$Mval", ROOT_PATH .'/'. $Mval);
													$stati ++;
												}
											}
										}
									}
								}
							}
						}
					}
					
				}
			}
		}
		echo $stati . ' Done...';
	}
	
	public function actionGetmtzxy()
	{
		set_time_limit(0);
		
		function convhtml( $string )
		{
			$string = @iconv('gbk','utf-8', $string);
			$string = str_replace(array("\t", "\n", "\r"), '', $string);
			$string = preg_replace( '/\s+/', ' ', $string );
			return $string;
		}
		
		$imgArr1 = $_Darr = array();
		
		function getPhotourl( $getmtphotos )
		{
			preg_match("/<td vAlign=center align=middle height=30>(.*)<p align='center'>/isU", $getmtphotos, $imgphotoArr);
			preg_match_all('/<IMG\s?src="(.*)"\s?border=0>.*/isU', $imgphotoArr[1], $MimgArr1);
			
			if(isset($MimgArr1[1][0]) && !empty($MimgArr1[1][0]))
			{
				return $MimgArr1[1];
			}
		}
		
		$appurl = $_GET['appurl'];
				
		$getPathContent = file_get_contents('http://www.mtzxy.com/rentiyishu/'.$appurl.'/Index.html');
					
		$getPathContent = convhtml($getPathContent);
		
		preg_match("/<table width='100%' cellpadding='0' cellspacing='5' border='0' align='center'><tr valign='top'>(.*)<table cellSpacing=0 cellPadding=0 width=\"98%\" border=0>/isU", $getPathContent, $zxArr);
		preg_match("/<div class=show_page><div class=\"showpage\">(.*)<!-- 分页结束 -->/is", $getPathContent, $Maxpage);
		
		preg_match('/<a href="\/rentiyishu\/'.$appurl.'\/List_(.*).html">.*<\/a>/isU', $Maxpage[1], $pageArr);

		preg_match_all("/<td align='center'><a class=\"\" href=\"(.*)\" title=\"(.*)\" target=\"_blank\"><img class='pic1' src='(.*)' width='110' height='147' border='0'><\/a><br><a class=\"\" href=\".*\" title=\".*\" target=\"_blank\">.*<\/a><\/td>/isU",$zxArr[0], $mtnrArr);
		
		$mtnrArr[0] = '张筱�?';	

		$fp = @fopen(ROOT_PATH . "/data/mtzxy.com/". date('Ymd') ."/".$appurl."_". date("Ymd") ."-data.log", "w");
		
		@flock($fp, LOCK_EX);
		
		
		for($i=0;$i<=$pageArr[1];$i++)
		{
			if($i > 0)
			{
				$getPathContent = @file_get_contents('http://www.mtzxy.com/rentiyishu/'.$appurl.'/List_'.$i.'.html');
				
				$getPathContent = convhtml($getPathContent);
				
				preg_match("/<table width='100%' cellpadding='0' cellspacing='5' border='0' align='center'><tr valign='top'>(.*)<table cellSpacing=0 cellPadding=0 width=\"98%\" border=0>/isU", $getPathContent, $zxArr);
				preg_match_all("/<td align='center'><a class=\"\" href=\"(.*)\" title=\"(.*)\" target=\"_blank\"><img class='pic1' src='(.*)' width='110' height='147' border='0'><\/a><br><a class=\"\" href=\".*\" title=\".*\" target=\"_blank\">.*<\/a><\/td>/isU",$zxArr[0], $mtnrArr);
				
				$mtnrArr[0] = '张筱�?';
			}
			
			foreach($mtnrArr[1] as $key => $val)
			{
				/** 获取每个链接下所有的图片 **/
				$getmtphotos = @file_get_contents('http://www.mtzxy.com/'.$val);		
				$getmtphotos = convhtml($getmtphotos);
			
				if($parr = getPhotourl( $getmtphotos ))
				{
					foreach ($parr as $Mval)
					{
						array_push($imgArr1, $Mval);
					}
				}
				
				preg_match("/<\/p><\/p><p align='center'><b><font color='red'>(.*)<\/b><\/p><\/P><P><\/P><P>/is", $getmtphotos, $photoLinkArr);
				preg_match_all("/<a href='(.*)'>.*<\/a>/isU", $photoLinkArr[1], $Darr);
				
				if(count($Darr[0]) > 10)
				{
					$getmtphotos = @file_get_contents( 'http://www.mtzxy.com'.$Darr[1][10] );
					$getmtphotos = convhtml($getmtphotos);
				
					if($parr = getPhotourl($getmtphotos))
					{
						foreach ($parr as $Mval)
						{
							array_push($imgArr1, $Mval);
						}
					}
				
					preg_match("/<\/p><\/p><p align='center'><b>(.*)<\/b><\/p><\/P><P><\/P><P>/is", $getmtphotos, $photoLinkArr);
					preg_match_all("/<a href='(.*)'>.*<\/a>/isU", $photoLinkArr[1], $_Darr);
		
					if(count($_Darr[1]) > 4)
					{
						array_pop($_Darr[1]);
					}
					
					unset($_Darr[1][0], $_Darr[1][1]);
					unset($Darr[1][9], $Darr[1][10]);
				}
				else
				{
					unset($Darr[1][9]);
				}
				
				if(isset($_Darr[1])) 
				{	
					$mergeArr = array_merge($Darr[1], $_Darr[1]);
				}
				else
				{
					$mergeArr = $Darr[1];
				}
				
				foreach($mergeArr as $urlval)
				{
					$getmtphotos = @file_get_contents( 'http://www.mtzxy.com'.$urlval );
					$getmtphotos = convhtml($getmtphotos);
				
					if($parr = getPhotourl($getmtphotos))
					{
						foreach ($parr as $Mval)
						{
							array_push($imgArr1, $Mval);
						}
					}
				}
				
				//$mtnrArr[1][$key] = $mtnrArr[1][$key] = array('url' => $val, 'photos' => $imgArr1);
				
				//print_r($mtnrArr);
				
				$newData = array('mt' => $mtnrArr[0], 'webpath' => $mtnrArr[1][$i], 'title' => $mtnrArr[2][$i], 'smpic' => $mtnrArr[3][$i], 'moreArr' => $imgArr1 );
				
				mkDirs(ROOT_PATH . "/data/mtzxy.com/". date('Ymd') ."/");
				
				@fwrite($fp, json_encode($newData) . "\n");
				
				unset($newData, $imgArr1);
			}

		}
		
		@flock($fp, LOCK_UN);
		@fclose($fp);	
	}
	
	public function actionGet003dh()
	{
		$_POST['action'] = 'a';
		if(isset($_POST['action']) && !empty($_POST['action']))
		{
			set_time_limit(0);
			
			$appdata = get('app', 'G');
	
			$getContent = @file_get_contents("http://www.003dh.com/Article/$appdata/Index.html");
			
			$ii = 1;
			
			while (!$getContent)
			{
				$ii++;
				if($ii > 3 ) break; 
				$getContent = @file_get_contents("http://www.003dh.com/Article/$appdata/Index.html");
			}

			if(!empty($getContent) && isset($getContent))
			{
				replacecode(&$getContent);
				
				preg_match('/<div class="box_l">(.*)<div class="box_r">/isU', $getContent, $pArr);
				
				preg_match("/<div class=\"showpage\">(.*)<Input type='text' name='page'/isU", $getContent, $morePathArr);
				
				preg_match('/<a href="\/Article\/'.$appdata.'\/List_(\d+).html">.*<\/a>/isU', $morePathArr[1], $allPageArr);
				
				if(is_array($allPageArr) && isset($allPageArr[1]))
				{
					if(is_numeric($allPageArr[1]) && !empty($allPageArr[1]))
					{
						$mpageSouce = array();
						
						$maxPage = ($allPageArr[1] + 1);
						
						for ($i = $maxPage; $i > 0; $i--)
						{
							if($i == $maxPage)
							{
								$mpageSouce[] = "http://www.003dh.com/Article/$appdata/";
							}
							else 
							{
								$mpageSouce[] = "http://www.003dh.com/Article/$appdata/List_$i.html";
							}
						}
						
						mkDirs(ROOT_PATH . "/data/003dh.com/". date('Ymd') ."/");
						
						if( $fp = @fopen(ROOT_PATH . "/data/003dh.com/". date('Ymd') ."/$appdata-". date("Ymd") ."-data.log", "w") )
						{
							if( @flock($fp, LOCK_EX) )
							{
								foreach ($mpageSouce as $val)
								{
									$jsdata = $this->getwebdata( $val, $fp, $appdata);							
								}							
								@flock($fp, LOCK_UN);
							}
						}
						
						@fclose($fp);					
					}
				
				}
				
				
				
			}
			
			$get003dhContent = @file_get_contents(ROOT_PATH . "/data/003dh.com/$appdata-". date("Ymd") ."-data.log");

			if( !empty($get003dhContent) )
			{
				$get003dhArr = explode("\t\n", $get003dhContent);

				foreach ($get003dhArr as $val)
				{
					if(!empty($val) && isset($val))
					{
						$zzdArr = @get_object_vars(json_decode($val));
						echo $zzdArr[2] . "&nbsp;&nbsp;" . $zzdArr[4] . "<br />";
					}
				}
			}
			
			echo '数据抓取完成...';
		}
	}
	
	function getwebdata( $getUrl, $fp, $appdata )
	{	
		$getPathContent = @file_get_contents( $getUrl );
		
		$iii = 1;
			
		while (!$getPathContent)
		{
			$iii++;
			if($iii > 3 ) break; 
			$getPathContent = @file_get_contents($getUrl);
		}
		
		$getPathContent = @iconv('gbk','utf-8', $getPathContent);
		
		$doneArr = array();
		
		if(!empty($getPathContent) && isset($getPathContent))
		{
			replacecode(&$getPathContent);
			
			preg_match('/<div class="box_l">(.*)<div class="box_r">/isU', $getPathContent, $pArr);

			if( !empty($pArr[1]) && isset($pArr[1]) )
			{
				//$mtArr => 当前页列表数�?
				preg_match_all('/<li>(.*)<\/li>/isU', $pArr[1], $mtArr);
				
				if(isset($mtArr[1][0]))
				{
					foreach ($mtArr[1] as $val)
					{
						$imgContent = '';
						
						//找到单个图片的， 入口网址�? 缩略图地�?，标�?
						preg_match('/<a href="(.*)" title="(.*)" target="_blank"><img class=\'pic1\' src=\'(.*)\'\s*width=\'\d*\'\s*height=\'\d*\'\s*border=\'\d*\'>.*<\/a>/', $val, $mhArr);
						
						unset($mhArr[0]);
						
						//$photoid = str_ireplace(array("/", '.html'), '', strrchr($mhArr[1], '/'));
						if(!empty($mhArr[1]))
						{
							$pathimgweb = @explode('/', $mhArr[1]);
							
							$photodate = $pathimgweb[3];
							
							$photoid = str_ireplace('.html', '', $pathimgweb[4]);
							
							//进入该图片的详细页面
							$imgContent = @file_get_contents('http://www.003dh.com' . $mhArr[1]);
							
							$iiiii = 1;
			
							while (!$imgContent)
							{
								$iiiii++;
								if($iiiii > 3 ) break; 
								$imgContent = @file_get_contents('http://www.003dh.com' . $mhArr[1]);
							}
							
							$imgContent = @iconv('gbk','utf-8', $imgContent);
						
							if(!empty($imgContent) && is_numeric($photoid) && is_numeric($photodate))
							{
								replacecode(&$imgContent);
								
								$author = '';

								for($pti=1; $pti<20;$pti++)
								{
									if($pti == 1)
									{
										$morePhotoLink[] = "http://www.003dh.com/Article/$appdata/$photodate/{$photoid}.html";
									}
									else 
									{
										$morePhotoLink[] = "http://www.003dh.com/Article/$appdata/$photodate/{$photoid}_{$pti}.html";
									}
								}

								$imgArr1 = array();
								
								foreach ($morePhotoLink as $mlik)
								{
									$moreimg = @file_get_contents($mlik);
									
									$iiiiii = 1;
				
									while (!$moreimg)
									{
										$iiiiii++;
										if($iiiiii > 3 ) break; 
										$moreimg = @file_get_contents($mlik);
									}
								
									if(!empty($moreimg))
									{
										$moreimg = @iconv('gbk','utf-8', $moreimg);
										
										replacecode(&$moreimg);
										
										preg_match('/<div class="sPage">更新时间�?(.*) 作�?�：(.*)<a href/isU', $moreimg, $zzArr);
										
										
										if(isset($zzArr[2]) && !empty($zzArr[2]))
										{
											$author = $zzArr[2];
										}
										
										preg_match('/<div\s?class=str>(.*)<div\s?class="JSadvbox">\s?<script\s?language="javascript"\s?src=".*"><\/script>\s?<\/div>/', $moreimg, $MimgArr);
											
										if(!empty($MimgArr[1]))
										{
											preg_match_all('/<IMG\s?src="(.*)"\s?border=0>.*/isU', $MimgArr[1], $MimgArr1);
											
											if(isset($MimgArr1[1][0]) && !empty($MimgArr1[1][0]))
											{
												foreach ($MimgArr1[1] as $Mval)
												{
													array_push($imgArr1, $Mval);
												}
											}
										}
									}
								}

								
								array_push($mhArr, $author, $imgArr1);
								
								/*print_r($mhArr);
								
								exit;*/
								
								@fwrite($fp, json_encode($mhArr) . "\t\n");
							}
						}
					}
				}
			}
		}
	}
}
?>