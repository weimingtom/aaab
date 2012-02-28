<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div id="top">
<div class="nav">
  <ul>
    <li><a href="?action=add<?php echo "&classid=".$classid."&type=".$type."&key=".$key; ?>">添加文章</a></li>
    <li> <a href="#" title="分类选择" onmouseover="javascript:display('top_nav','block');" onmouseout="javascript:display('top_nav','none');">分类选择</a></li>
    <li><a href="?action=&type=1">未显示</a></li>
    <li><a href="?action=&type=5">已显示</a></li>
    <li><input type="text" value="<?php echo $key<>""? $key : '输入您想要搜索的关键词';?>" onkeydown="javascript: if(event.keyCode==13){ location='?action=&key='+this.value;return false;}" title="输入您想要搜索的关键词" onFocus="if (value =='输入您想要搜索的关键词'){value =''}" onBlur="if (value ==''){value='输入您想要搜索的关键词'}" class="input" /></li>
  </ul>
  <div class="clear"></div>
</div>
<div id="top_nav" class="nav_class" onmouseover="javascript:display('top_nav','block');" onmouseout="javascript:display('top_nav','none');">
    <ul>
      <li><a href="?classid=&act=<?php  echo $act; ?>" title="<?php echo $row["name"];?>">全部分类</a></li>
      <?php
    $query = $mysql ->query("SELECT `id`,`name`,`sort` FROM `-table-dafenglei` Order by sort Asc,id Desc");
    while ($rs = $mysql ->fetch_array($query)) {
?>
      <li><a href="?classid=<?php echo $rs["id"];?>&act=<?php  echo $act; ?>"><?php echo str_len($rs["name"],8,1);?></a></li>
      <?php
	}
?>
    </ul>
    <div class="clear"></div>
  </div>
</div>
<form action="" method="post" id="form" name="form">
  <table class="table" width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <thead>
      <tr>
        <th width="31"><div align="center">ID</div></th>
        <th><div align="center">标题</div></th>
        <th width="160"><div align="center">TAG标签</div></th>
        <th width="160"><div align="center">所属分类</div></th>
        <th width="85"><div align="center">综合属性</div></th>
        <th width="75"><div align="center">添加时间</div></th>
        <th width="45"><div align="center">排序</div></th>
        <th width="45"><div align="center">点击</div></th>
        <th width="70"><div align="center">状态</div></th>
        <th width="42"><div align="center">操作</div></th>
      </tr>
    </thead>
    <?php while($row = $mysql -> fetch_array($result)){ ?>
    <tr onmouseover="this.style.backgroundColor='#EEF6FB';" onmouseout="this.style.backgroundColor='';">
      <td><div align="center">
          <input type="checkbox" name="id[]" id="id" value="<?php  echo $row['id']; ?>"> <input type="hidden" name="idd[]" value="<?php  echo $row['id']; ?>" />
        </div></td>
      <td><a class="a12px" href="?action=edit&amp;id=<?php  echo $row['id']; ?><?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key; ?>" title="<?php  echo $row['title']; ?>">
          <?php  echo str_len($row['title'],25,2); ?>
          </a> <?php if($row['img']<>"") echo '<font color="red" title="此文章有图片">图</font>'; ?></td>
       <td><div align="center">
         <input name="tag[]" type="text" id="tag[]" size="20" class="input" value="<?php  echo $row['tag']; ?>" />
       </div></td>
       <td>
         <div align="center">
           <select id="dafenglei[]" name="dafenglei[]">
             <?php dafenglei_select(0,0,$row['dafenglei']); ?>
           </select>
         </div></td>
       <td><div align="center">
       <?php echo select("top[]",array('1'=>'普通','5'=>'分类置顶','10'=>'总置顶'),$row['top']); ?>
       </div></td>
       <td><div align="center"><?php  echo date("Y-m-d",strtotime($row['date'])); ?></div></td>
       <td><div align="center"><input name="sort[]" type="text" id="sort[]" size="3" class="input" value="<?php  echo $row['sort']; ?>" /></div></td>
       <td><div align="center"><?php  echo $row['count']; ?></div></td>
      <td>
      <div align="center"><?php echo select("type[]",array('1'=>'未显示','5'=>'已显示'),$row['type']); ?></div></td>
      <td><div align="center"><a href="?action=edit&amp;id=<?php  echo $row['id']; ?>&page=<?php echo $page; ?>">修改</a></div></td>
    </tr>
    <?php } ?>
    <tr class="pagetr">
      <td colspan="10"><?php echo $pages -> myde_write(); ?></td>
    </tr>
    <tr>
      <td colspan="10"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        全选
        <input type="button" onclick="del(this.form,false,'?action=del<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="删除" /> 
      <input type="button" onclick="act_submit(this.form,false,'?action=article<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="设置为已显示" />
      <input type="button" onclick="act_submit(this.form,false,'?action=articleto<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="设置为未显示" />
      <input type="button" onclick="act_submit(this.form,true,'?action=articleall<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="全部设置为已显示" />
      <input type="button" onclick="act_submit(this.form,true,'?action=piliang<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="批量修改" /> 
      <input type="button" onclick="act_submit(this.form,false,'gotohtml.php?action=Articlehtml<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="生成HTML" />  <input type="button" onclick="act_submit(this.form,true,'gotohtml.php?action=Articletype<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="将所有已显示的文章生成HTML" />   <input type="button" onclick="del(this.form,true,'?action=qingkong&page=<?php echo $page; ?>');" value="清空文章" />    </td>
    </tr>
  </table>
</form>