<?
require_once(dirname(__FILE__)."/config.php");

define('DEDEPLUS', DEDEROOT."/plus");
$tempLetterConfigFile = DEDEPLUS."/lettersort/config.php";

$tempIsInclude = @include_once($tempLetterConfigFile);
if ($tempIsInclude === FALSE || !isset($letterconfig))
{
	$tempCacheData['letterconfig'] = array('showtitle' => '按首字母检索-{letter}', 'namerule' => '{listdir}/{letter}_{page}.html', 'listdir' => '{cmspath}/letterlist/', 'templet' => '{style}/list_letter.htm', 'endtime' => 0, 'listtag' => '', 'keywords' => '', 'description' => '', 'nodefault' => 1, 'isupdatetable' => 0);
	$strCacheData = GetCacheVar($tempCacheData);
	WriteCacheFile($tempLetterConfigFile, $strCacheData);
	ShowMsg("配置初始化成功!", "lettersort_main.php");
	exit();
}
$action = isset($action) ? trim($action) : '';

if ($action == '')
{
	require_once DEDEINC.'/typelink.class.php';
	require_once DEDEINC.'/dedetag.class.php';

	//列表配置选项
	$pagesize = 30;
	$col = 1;
	$titlelen = 60;
	$setype = '';
	$orderby = 'pubdate';
	$order = 'desc';
	$keywordarc = '';
	$innertext = '';
	if ($letterconfig['listtag'] != '')
	{
		$dtp = new DedeTagParse();
		$dtp->SetNameSpace("dede","{","}");
		$dtp->LoadSource("--".$letterconfig['listtag']."--");
		$ctag = $dtp->GetTag('list');
		
		$pagesize = $ctag->GetAtt('pagesize');
		$col = $ctag->GetAtt('col');
		$titlelen = $ctag->GetAtt('titlelen');
		$orderby = $ctag->GetAtt('orderby');
		$order = $ctag->GetAtt('orderway');
		$setype = $ctag->GetAtt('type') != '' ? $ctag->GetAtt('type') : '';
		$keywordarc = $ctag->GetAtt('keyword');
		$innertext = $ctag->GetInnerText();
	}
	include DedeInclude('templets/lettersort.htm');
	exit();
}
elseif ($action == 'add')
{
	$tempCacheData['letterconfig'] = array();
	$tempCacheData['letterconfig']['showtitle'] = $title;
	$tempCacheData['letterconfig']['namerule'] = $namerule;
	$tempCacheData['letterconfig']['listdir'] = $listdir;
	$tempCacheData['letterconfig']['templet'] = $templet;
	$tempCacheData['letterconfig']['endtime'] = time();
	$tempCacheData['letterconfig']['keywords'] = $keywords;
	$tempCacheData['letterconfig']['description'] = $description;
	$tempCacheData['letterconfig']['nodefault'] = isset($nodefault) ? intval($nodefault) : 1;

	$templistattr = '';
	$templistattr = "pagesize='$pagesize' col='$col' titlelen='$titlelen' orderby='$orderby' orderway='$order'";

	$tempTypeAttr = '';
	if (isset($types) && !empty($types))
	{
		$tempTypeAttr = " type='".implode(" ", $types)."'";
	}
	if ($tempTypeAttr != '')
	{
		$templistattr .= $tempTypeAttr;
	}
	if (trim($keywordarc) != '')
	{
		$templistattr .= " keyword='$keywordarc'";
	}
	$innertext = trim($innertext);
	if($innertext != '')
	{
		$innertext = stripslashes($innertext);
	}
	else
	{
		ShowMsg("请配置循环内的单行记录样式!", "-1");
		exit();
	}
	$tempListTag = "{dede:list $templistattr}$innertext{/dede:list}";
	$tempCacheData['letterconfig']['listtag'] = $tempListTag;
	$tempCacheData['letterconfig']['isupdatetable'] = $letterconfig['isupdatetable'];
	$strCacheData = GetCacheVar($tempCacheData);
	WriteCacheFile($tempLetterConfigFile, $strCacheData);
	ShowMsg("列表配置成功!", "lettersort_main.php");
	exit();
}
elseif ($action=='makehtml')
{
	$gotokey = isset($gotokey) ? intval($gotokey) : 0;
	require_once(DEDEINC."/arc.letterlist.class.php");
	$tempLetterList = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	if (!isset($tempLetterList[$gotokey]))
	{
		ShowMsg("恭喜您，列表生成完成!", "lettersort_main.php");
		exit();
	}
	$lettervalue = $tempLetterList[$gotokey];
	$lv = new LetterList($letterconfig, $lettervalue);
	$lv->MakeHtml();
	$lv->Close();
	
	$tempgotokey = $gotokey + 1;
	ShowMsg("完成对字母{$tempLetterList[$gotokey]}的检索列表生成,将进行下一字母检索!", "lettersort_main.php?action=makehtml&gotokey={$tempgotokey}", 0, 100);
	exit();
}
elseif ($action == 'altertable')
{
	$tempPageSize = 500;
	$startNum = (isset($startNum) && $startNum > 0) ? intval($startNum) : 0;
	/*
	if ($letterconfig['isupdatetable'] == 1)
	{
		ShowMsg("数据字段已经升级，无须执行该操作!", "lettersort_main.php");
		exit();
	}
	*/
	$row = $dsql->GetOne("SELECT COUNT(*) as dd FROM `#@__archives` WHERE titleenglish = ''");
	$tempTotalNum = $row['dd'];
	if ($tempTotalNum <= 0)
	{
		ShowMsg("没有需要转换的数据!", "-1");
		exit();
	}

	if ($tempTotalNum > $startNum + $tempPageSize)
	{
		$tempLimitSql = " LIMIT $startNum, $tempPageSize";
	}
	else
	{
		$tempLimitSql = " LIMIT $startNum, ".($tempTotalNum - $startNum);
	}
	$dsql->Execute('out',"SELECT id, title From `#@__archives`{$tempLimitSql}");
	while($data = $dsql->GetObject('out'))
	{
		$startNum++;
		if (trim($data->title) != '')
		{
			$tempTitleEnglish = GetPinyin($data->title);
			$dsql->ExecuteNoneQuery("UPDATE `#@__archives` SET titleenglish = '{$tempTitleEnglish}' WHERE id=".$data->id);
		}
	}

	if ($startNum < $tempTotalNum)
	{
		ShowMsg("表数据更新中，请等待...", "lettersort_main.php?action=altertable&startNum={$startNum}",0,100);
		exit();
	}
	else
	{
		$tempCacheData['letterconfig'] = $letterconfig;
		$tempCacheData['letterconfig']['isupdatetable'] = 1;
		$strCacheData = GetCacheVar($tempCacheData);
		WriteCacheFile($tempLetterConfigFile, $strCacheData);
		ShowMsg("表数据更新成功!", "lettersort_main.php");
		exit();
	}
	exit();
}
function WriteCacheFile($strFile, $strCacheData, $bolPhpCode = TRUE)
{
	if ($strFile != '')
	{
		$strFolder = dirname($strFile);
		if (!is_dir($strFolder))
		{
			
			MkdirAll($strFolder, 0777);
		}
		if (@$fp = fopen($strFile, 'wb'))
		{
			flock($fp, LOCK_EX);
			if ($bolPhpCode)
			{
				fwrite($fp, "<?php\n\n{$strCacheData}\n?>");
			}
			else
			{
				fwrite($fp, $strCacheData);
			}
			flock($fp, LOCK_UN);
			fclose($fp);
			@chmod($strFile, 0777);
		}
		else
		{
			exit('Can not write cache file: '.basename($strFile).'.');
		}
	}
}

function GetCacheVar($aryData)
{
	$tempEvaluate = '';
	if ($aryData)
	{
		foreach ($aryData as $key => $val)
		{
			if (is_array($val))
			{
				$tempEvaluate .= "\$$key = ".FormatCacheArray($val).";\n\n";
			}
			else
			{
				$tempEvaluate .= "\$$key = '".addcslashes($val, '\'\\')."';\n";
			}
		}
	}
	return $tempEvaluate;
}

function FormatCacheArray($aryData, $intLevel = 0)
{
	if (function_exists('var_export'))
	{
		return var_export($aryData, TRUE);
	}

	$tempSpace = '';
	for ($i=0; $i<=$intLevel; $i++)
	{
		$tempSpace .= "\t";
	}
	$tempEvaluate = "array\n$tempSpace(\n";
	$tempComma = $tempSpace;
	foreach ($aryData as $key => $val)
	{
		$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
		$val = !is_array($val) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
		if (is_array($val))
		{
			$tempEvaluate .= "$tempComma$key => ".FormatCacheArray($val, $intLevel+1);
		}
		else
		{
			$tempEvaluate .= "$tempComma$key => $val";
		}
		$tempComma = ",\n$tempSpace";
	}
	$tempEvaluate .= "\n$tempSpace)";
	return $tempEvaluate;
}
?>