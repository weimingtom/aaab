<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

function lib_letter(&$ctag,&$refObj)
{
	global $dsql,$envs;
	
	//属性处理
	$attlist="typeid|0,reid|0,row|0,col|1,type|son,currentstyle|,cacheid|";
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	$innertext = $ctag->GetInnerText();
	
	$curlettervalue = '';
	if (isset($refObj->lettervalue) && !empty($refObj->lettervalue))
	{
		$curlettervalue = $refObj->lettervalue;
	}
	include(DEDEROOT."/plus/lettersort/config.php");
	$tempLetterList = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	if (isset($row) && $row > 0)
	{
		$tempLetterList = array_slice($tempLetterList, 0, $row);
	}

	$revalue = '';
	$dtp2 = new DedeTagParse();
	$dtp2->SetNameSpace('field','[',']');
	$dtp2->LoadSource($innertext);

	foreach ($tempLetterList as $tempLetterValue)
	{
		$row = array();

		$row['letterurl'] = lib_GetLetterUrl($letterconfig, $tempLetterValue);
		$row['lettername'] = $tempLetterValue;
		$row['rel'] = '';

		if ($curlettervalue != '' && strcasecmp($curlettervalue, $tempLetterValue) == 0 && $currentstyle != '')
		{
			$linkOkstr = $currentstyle;
			$linkOkstr = str_replace("~rel~",$row['rel'],$linkOkstr);
			$linkOkstr = str_replace("~letterurl~",$row['letterurl'],$linkOkstr);
			$linkOkstr = str_replace("~lettername~",$row['lettername'],$linkOkstr);
			$revalue .= $linkOkstr;
		}
		else
		{
			if(is_array($dtp2->CTags))
			{
				foreach($dtp2->CTags as $tagid=>$ctag)
				{
					if(isset($row[$ctag->GetName()]))
					{
						$dtp2->Assign($tagid,$row[$ctag->GetName()]);
					}
				}
			}
			$revalue .= $dtp2->GetResult();
		}
	}
	//你需编写的代码，不能用echo之类语法，把最终返回值传给$revalue
	//------------------------------------------------------
	
	//------------------------------------------------------
	return $revalue;
}

function lib_GetLetterUrl($letterConfig, $letterValue)
{
	$tempReturnUrl = '';

	$namerule = $letterConfig['namerule'];
	$listdir = $letterConfig['listdir'];

	$listdir = str_replace('{cmspath}',$GLOBALS['cfg_cmspath'],$listdir);
	$tempReturnUrl = str_replace('{listid}', '0',$namerule);
	$tempReturnUrl = str_replace('{listdir}',$listdir, $tempReturnUrl);
	$tempReturnUrl = str_replace('{letter}',$letterValue,$tempReturnUrl);
	$tempReturnUrl = str_replace("\\","/",$tempReturnUrl);

	if ($letterConfig['nodefault'] == 0)
	{
		$tempReturnUrl = str_replace('_{page}','',$tempReturnUrl);
	}
	else
	{
		$tempReturnUrl = str_replace('{page}','1',$tempReturnUrl);
	}
	$tempReturnUrl = ereg_replace("/{1,}","/",$tempReturnUrl);
	return $tempReturnUrl;

}
?>