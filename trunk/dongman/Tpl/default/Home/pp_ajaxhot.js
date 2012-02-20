<php>$vod_up=$pplist->ppvod('day:30;num:13;order:vod_up desc');</php>
<volist name="vod_up" id="ppvod" mod="4"><eq name="mod" value="1">
document.write('<a href="{$ppvod.vod_url}" title="{$ppvod.vod_name}"><font style="color:#FFCCFF">{$ppvod.vod_name|msubstr=0,10}</font></a>&nbsp;&nbsp;');<else/>
document.write('<a href="{$ppvod.vod_url}" title="{$ppvod.vod_name}">{$ppvod.vod_name|msubstr=0,4}</a>&nbsp;&nbsp;');
</eq>
</volist>