<?php
/**
 * @version        $Id: show-photo.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/../include/common.inc.php");
require_once(dirname(__FILE__). './include/story.view.class.php');
$id = intval($id);
if(empty($id)) ParamError();

$bv = new BookView($id,'content');

//检测是否收费图书
$freenum = $bv->Fields['freenum'];
if($freenum > -1)
{
    require_once(DEDEINC. "/memberlogin.class.php");
    $ml = new MemberLogin();
    if($ml->M_MbType < $cfg_book_freerank)
    {
        $row = $bv->dsql->GetOne("SELECT chapnum FROM #@__story_chapter WHERE id='{$bv->Fields['chapterid']}' ");
        $chapnum = $row['chapnum'];
        $member_err = '';

        //确定当前内容属于收费章节
        if($chapnum > $freenum)
        {
            if( empty($ml->M_ID) )
            {
                $member_err = "NoLogin";
            }
            else
            {
                $row = $bv->dsql->GetOne("SELECT * FROM #@__story_viphistory WHERE mid='{$ml->M_ID}' ");
                if(!is_array($row) && $ml->M_Money < $cfg_book_money)
                {
                    $member_err = "NoEnoughMoney";
                }
            }

            //权限错误
            if($member_err!='')
            {
                $row = $bv->dsql->GetOne("SELECT membername FROM #@__arcrank WHERE rank = '$cfg_book_freerank' ");
                if(!is_array($row))
                {
                    $membername = '';
                }
                else
                {
                    $membername = $row['membername'];
                }
                require_once($cfg_basedir.$cfg_templets_dir.'/'.$cfg_df_style.'/book_member_err.htm');
                $bv->Close();
                exit();
            }

            //扣点
            else
            {
                $rs = $bv->dsql->ExecuteNoneQuery("INSERT INTO #@__story_viphistory(cid,uid) Values('{$id}','{$ml->M_ID}') ");
                if($rs)
                {
                    $bv->dsql->ExecuteNoneQuery("UPDATE #@__member SET money=money-{$cfg_book_money} WHERE id='{$ml->M_ID}' ");
                }
            }
        }
    }
}

// huyao+ 2012.02.06
$photo = $bv->dsql->getOne("Select chapterid From #@__story_content where id='{$id}'");
$photoArr = array();
if ($photo) {
	$bv->dsql->SetQuery("Select id From #@__story_content where chapterid='{$photo['chapterid']}' order by id asc ");
	$bv->dsql->Execute();
	$i=1;
	while ($row = $bv->dsql->getArray()) {
		$photoArr[$i] = $row['id'];
		$i++;
	}
}
$bv->dtp->Assign('photoArr', $photoArr);
// huyao end

$bv->Display();
$bv->Close();
