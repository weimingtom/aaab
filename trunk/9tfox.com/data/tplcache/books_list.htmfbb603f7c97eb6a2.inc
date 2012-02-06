<?php
$z[0]=Array("global","",250,276);
$z[0][4]['name']="cfg_webname";
$z[1]=Array("global","",320,350);
$z[1][4]['name']="cfg_description";
$z[2]=Array("global","",387,414);
$z[2][4]['name']="cfg_keywords";
$z[3]=Array("include","",498,542);
$z[3][4]['filename']="aabbccdd_head.htm";
$z[4]=Array("booklist","\r\n<DL><SPAN class=flr>[field:pubdate function=\"MyDate('m-d',@me)\"/]</SPAN><a href='[field:bookurl /]'>[field:bookname /]</a></DL>\r\n",665,866);
$z[4][4]['row']="8";
$z[4][4]['titlelen']="24";
$z[4][4]['orderby']="weekcc";
$z[5]=Array("booklist","\r\n<DIV class=innr8>\r\n<UL>\r\n  <LI><A href=\"[field:bookurl /]\"><IMG   src=\"[field:litpic/]\"></A></LI></UL>\r\n<DL>\r\n  <DT class=red_lj><STRONG><A href=\"[field:bookurl /]\" >[field:bookname/]</A></STRONG></DT>\r\n  <DT>状态：[field:array runphp='yes']@me = (@me['status']==0 ? \"连载中\" : \"已完结\"); [/field:array] </DT>\r\n  <DT>作者：[field:author/]</DT>\r\n  <DT>人气：[field:click /]</DT>\r\n  </DL>\r\n  </DIV>\r\n<DIV class=ff></DIV>\r\n",947,1455);
$z[5][4]['row']="8";
$z[5][4]['titlelen']="24";
$z[5][4]['orderby']="commend";
$z[6]=Array("booklist","\r\n<DL><a href='[field:bookurl /]'>[field:bookname /]</a></DL>\r\n",1571,1703);
$z[6][4]['row']="8";
$z[6][4]['titlelen']="24";
$z[6][4]['orderby']="click";
$z[7]=Array("field","",2605,2634);
$z[7][4]['name']="position";
$z[8]=Array("list"," \r\n<li class=\"red_lj\">\r\n<a href=\"[field:bookurl/]\"><img  src=\"[field:litpic/]\"></a><br><a href=\"[field:bookurl/]\">[field:title/]</a>(<span style=\"color: red;\">[field:comic/]</span>)<br>作者：[field:author/]<br>更新时间：[field:pubdate function=\"MyDate('m-d',@me)\"/]</li>\r\n",2671,2988);
$z[8][4]['pagesize']="10";
$z[9]=Array("pagelist","",3075,3153);
$z[9][4]['listitem']="info,index,end,pre,next,pageno,option";
$z[9][4]['listsize']="5";
$z[10]=Array("include","",3258,3302);
$z[10][4]['filename']="aabbccdd_foot.htm";

?>