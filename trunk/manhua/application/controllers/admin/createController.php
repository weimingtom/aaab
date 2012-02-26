<?php

! defined ( 'IN_ROOT' ) && exit ( 'Access Denied' );

class createController extends adminbase
{
	public function __construct()
	{
		parent::__construct ();
		
		$this->load('admin_create');
	}
	
	function indexAction()
	{
		require ROOT_PATH . 'application/models/admin/category.php';
		
		$cgory = new admin_category( $this );
		
		
		$this->view->assign('options', $cgory->get_options());
		
		$this->display('create_index');
	}
	
	function copyAction()
	{
		set_time_limit(0);
		
		$topMenu = array(
			123 => 'shinei',
			149 => 'pengpai',
			125 => 'caihui',
			126 => 'huihua',
			127 => 'huaxu',
			128 => 'jiqiao',
			129 => 'neiyixiu',
			142 => 'nanxing',
			143 => 'meinv',
			122 => 'waipai'
		);
				
		if( !empty($_POST['action']) && isset($_POST['action']) )
		{
			$WebServerAddress = 'http://'.$_SERVER['SERVER_NAME'] . '/';
			
			$appgetID = get('appgetID', 'P');
			
			if(!empty($_POST['appgetID']) && isset($_POST['appgetID']) && $appgetID == -1)
			{
				$webnr = file_get_contents( $WebServerAddress . 'apprt.php' );
	
				if( !empty($webnr) ) $webnr = str_replace(array("\t"), '', $webnr);
				
				//preg_match('/<!--StartMenu-->(.*)<!--EndMenu-->/isU', $webnr, $menuArr );				
				//$repHTML = preg_replace('/<a href="apprt.php\?a=(.*)&xxid=(\d*)"([^>]*)>(.*)<\/a>/iU', '<a href="\\1/\\2.html" \\3>\\4</a>', $menuArr[1]);
				//$repHTML = preg_replace('/<!--StartMenu-->.*<!--EndMenu-->/isU', $repHTML, $webnr);
				$repHTML = preg_replace('/<a href="apprt.php\?a=(.*)&xxid=(.*)&mt=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="<\\2>/\\2/\\3/1.html" \\4>\\5</a>', $webnr);				
				$repHTML = preg_replace('/<a href="apprt.php\?a=(.*)&xxid=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="<\\2>/" title="\\3">\\4<\/a>', $repHTML);
				
				$repHTML = str_replace(array('<123>','<149>','<125>','<126>','<127>','<128>','<129>','<142>','<143>','<122>'), 
				array('shinei','pengpai','caihui','huihua','huaxu','jiqiao','neiyixiu','nanxing','meinv','waipai'), $repHTML);
		
				replacecode( &$repHTML );
				
				file_put_contents(ROOT_PATH . 'index.html', $repHTML);
				
				echo '首页生成成功...';
			}

			//?a=list&xxid=122&page=1
			if( !empty($appgetID) && isset($appgetID) && is_numeric($appgetID) && $appgetID > 0 )
			{
				$appName = $topMenu[$appgetID];
				
				$webcontent = file_get_contents( $WebServerAddress . "apprt.php?a=list&xxid=$appgetID" );
				
				preg_match('/<!-- STARTPAGE ([0-9]*) ENDPAGE -->/is', $webcontent, $webcArr);
				
				for($i=1; $i <= intval($webcArr[1]); $i++)
				{
					$webnr = file_get_contents( $WebServerAddress . "apprt.php?a=list&xxid=$appgetID&page=$i" );
					
					if( !empty($webnr) ) $repHTML = str_replace(array("\t"), '', $webnr);
	
					mkDirs(ROOT_PATH . $appName . "/");
					
					preg_match('/<!--STARTPAGELIST-->(.*)<!--ENDPAGELIST-->/isU', $webnr, $listArr );
	
					preg_match_all('/<a href="(.*)"[^>]*>(.*)<\/a>/isU', $listArr[1], $linkArr);
	
					//<a href="apprt.php?a=list&xxid=122&page=2">02</a>
					$repHTML = preg_replace('/<a href="apprt.php\?a=(.*)&xxid=(.*)&page=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="\\2_\\3.html" \\4>\\5</a>', $repHTML);
					$repHTML = preg_replace('/<a href="apprt.php\?a=.*&xxid=(.*)&mt=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="\\1/\\2/1.html" \\3>\\4</a>', $repHTML);
					$repHTML = str_ireplace(array('href="static', 'src="static', 'getPhoto.php'), array('href="../static', 'src="../static', '../getPhoto.php'), $repHTML);
					
					replacecode( &$repHTML );
					
					$i == 1 ? file_put_contents(ROOT_PATH . "$appName/index.html", $repHTML) : file_put_contents(ROOT_PATH . "$appName/{$appgetID}_$i.html", $repHTML);
					
					foreach ($linkArr[1] as $val)
					{
						$infopath = str_ireplace(array('apprt.php?a=info&xxid=', '&mt='), '|', $val);
						$infopath = explode('|', $infopath);
						mkDirs(ROOT_PATH . "$appName/". $infopath[1] ."");
						
						$getmtNr = file_get_contents( $WebServerAddress . $val );
						$getmtNr = preg_replace('/<a href="apprt.php\?a=info&xxid=(.*)&mt=(.*)&page=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="../info/\\1/\\2/\\3.html" \\4>\\5</a>', $getmtNr);
						$getmtNr = str_ireplace(array('href="static', 'src="static', 'getPhoto.php'), array('href="../../static', 'src="../../static', '../../getPhoto.php'), $getmtNr);
						
						//$Mwebcontent = file_get_contents( $WebServerAddress . "apprt.php?a=info&xxid=$appgetID&mt=8252" );
						preg_match('/<!-- STARTPAGE ([0-9]*) ENDPAGE -->/is', $getmtNr, $MwebcArr);
				
						for($j=1; $j <= intval($MwebcArr[1]); $j++)
						{
							mkDirs(ROOT_PATH . "$appName/". $infopath[1] ."/".$infopath[2]."");
						
							$MgetmtNr = file_get_contents( $WebServerAddress . "apprt.php?a=info&xxid=".$infopath[1]."&mt=".$infopath[2]."&page=$j" );
							
							$MgetmtNr = str_ireplace(array('href="static', 'src="static', 'getPhoto.php'), array('href="../../../static', 'src="../../../static', '../../../getPhoto.php'), $MgetmtNr);
							
							//<a href="apprt.php?a=info&xxid=123&mt=8252&page=2">02</a>
							$MgetmtNr = preg_replace('/<a href="apprt.php\?a=info&xxid=(.*)&mt=(.*)&page=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="\\3.html" \\4>\\5</a>', $MgetmtNr);
							$MgetmtNr = preg_replace('/<a href="apprt.php\?a=.*&xxid=(.*)&mt=(.*)"([^>]*)>(.*)<\/a>/iU', '<a href="../\\2/1.html" \\3>\\4</a>', $MgetmtNr);
							
							replacecode( &$MgetmtNr );
							
							file_put_contents(ROOT_PATH . "$appName/". $infopath[1] ."/". $infopath[2] ."/$j.html", $MgetmtNr);
						}
						
					}
				}
				
			}
			
			
			
		}
	}
	
	
	
	
	
	
}
?>