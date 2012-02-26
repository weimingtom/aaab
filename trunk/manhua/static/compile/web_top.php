<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<link href="static/img/a/DefaultSkin.css" type=text/css rel=stylesheet>
<TABLE cellSpacing=0 cellPadding=0 width=950 align=center border=0>
<TBODY>
<TR>
<TD width=330 height=80><A href="http://www.rt798.com/index.html"><IMG height=66 src="/static/img/a/5.gif" width=330 border=0></A></TD>
<TD width=10>&nbsp;</TD>
<TD width=610>&nbsp;</TD>
</TR>
</TBODY>
</TABLE>

<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
<TR>
<TD height=34 background="/static/img/a/MNav01.gif" bgcolor="#FF9999">
  <TABLE height=34 cellSpacing=0 cellPadding=0 width=950 align=center border=0>
<TBODY>
<TR>
<TD class=white12 align=left width=50><A href="http://www.rt798.com/index.html"><SPAN class=white12>首　页</SPAN></A></TD>
<TD class=white12 width=2 background=/static/img/a/MNav02.gif></TD>

<? foreach((array)$menuArr as $col) {?>
<TD class=white12 align=middle width=70><A href="<?=$col['menulink']?>" title="<?=$col['cate_name']?>" target=_blank><SPAN class="white12 <? if($xxid == $col['id']) { ?>on<? } ?>"><?=$col['cate_name']?></SPAN></A></TD>
<TD class=white12 align=middle width=2 background=/static/img/a/MNav02.gif></TD>
<? } ?>

<TD class=white12 align=middle width=110><A class=main_tdbg_bsd06 href="javascript:window.external.addFavorite('http://www.rt798.com','俏佳人人体艺术')"><SPAN class=white12>收藏本站</SPAN></A></TD>
<TD align=middle>
<DIV align=right>&nbsp;</DIV></TD>
</TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
