<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div id="top">
<div class="nav">
  <ul>
    <li><a href="?action=add<?php echo "&classid=".$classid."&type=".$type."&key=".$key; ?>">�������</a></li>
    <li> <a href="#" title="����ѡ��" onmouseover="javascript:display('top_nav','block');" onmouseout="javascript:display('top_nav','none');">����ѡ��</a></li>
    <li><a href="?action=&type=1">δ��ʾ</a></li>
    <li><a href="?action=&type=5">����ʾ</a></li>
    <li><input type="text" value="<?php echo $key<>""? $key : '��������Ҫ�����Ĺؼ���';?>" onkeydown="javascript: if(event.keyCode==13){ location='?action=&key='+this.value;return false;}" title="��������Ҫ�����Ĺؼ���" onFocus="if (value =='��������Ҫ�����Ĺؼ���'){value =''}" onBlur="if (value ==''){value='��������Ҫ�����Ĺؼ���'}" class="input" /></li>
  </ul>
  <div class="clear"></div>
</div>
<div id="top_nav" class="nav_class" onmouseover="javascript:display('top_nav','block');" onmouseout="javascript:display('top_nav','none');">
    <ul>
      <li><a href="?classid=&act=<?php  echo $act; ?>" title="<?php echo $row["name"];?>">ȫ������</a></li>
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
        <th><div align="center">����</div></th>
        <th width="160"><div align="center">TAG��ǩ</div></th>
        <th width="160"><div align="center">��������</div></th>
        <th width="85"><div align="center">�ۺ�����</div></th>
        <th width="75"><div align="center">���ʱ��</div></th>
        <th width="45"><div align="center">����</div></th>
        <th width="45"><div align="center">���</div></th>
        <th width="70"><div align="center">״̬</div></th>
        <th width="42"><div align="center">����</div></th>
      </tr>
    </thead>
    <?php while($row = $mysql -> fetch_array($result)){ ?>
    <tr onmouseover="this.style.backgroundColor='#EEF6FB';" onmouseout="this.style.backgroundColor='';">
      <td><div align="center">
          <input type="checkbox" name="id[]" id="id" value="<?php  echo $row['id']; ?>"> <input type="hidden" name="idd[]" value="<?php  echo $row['id']; ?>" />
        </div></td>
      <td><a class="a12px" href="?action=edit&amp;id=<?php  echo $row['id']; ?><?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key; ?>" title="<?php  echo $row['title']; ?>">
          <?php  echo str_len($row['title'],25,2); ?>
          </a> <?php if($row['img']<>"") echo '<font color="red" title="��������ͼƬ">ͼ</font>'; ?></td>
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
       <?php echo select("top[]",array('1'=>'��ͨ','5'=>'�����ö�','10'=>'���ö�'),$row['top']); ?>
       </div></td>
       <td><div align="center"><?php  echo date("Y-m-d",strtotime($row['date'])); ?></div></td>
       <td><div align="center"><input name="sort[]" type="text" id="sort[]" size="3" class="input" value="<?php  echo $row['sort']; ?>" /></div></td>
       <td><div align="center"><?php  echo $row['count']; ?></div></td>
      <td>
      <div align="center"><?php echo select("type[]",array('1'=>'δ��ʾ','5'=>'����ʾ'),$row['type']); ?></div></td>
      <td><div align="center"><a href="?action=edit&amp;id=<?php  echo $row['id']; ?>&page=<?php echo $page; ?>">�޸�</a></div></td>
    </tr>
    <?php } ?>
    <tr class="pagetr">
      <td colspan="10"><?php echo $pages -> myde_write(); ?></td>
    </tr>
    <tr>
      <td colspan="10"><input type="checkbox" id="chkAll" onclick="CheckAll(this.form)" value="checkbox">
        ȫѡ
        <input type="button" onclick="del(this.form,false,'?action=del<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="ɾ��" /> 
      <input type="button" onclick="act_submit(this.form,false,'?action=article<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="����Ϊ����ʾ" />
      <input type="button" onclick="act_submit(this.form,false,'?action=articleto<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="����Ϊδ��ʾ" />
      <input type="button" onclick="act_submit(this.form,true,'?action=articleall<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="ȫ������Ϊ����ʾ" />
      <input type="button" onclick="act_submit(this.form,true,'?action=piliang<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="�����޸�" /> 
      <input type="button" onclick="act_submit(this.form,false,'gotohtml.php?action=Articlehtml<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="����HTML" />  <input type="button" onclick="act_submit(this.form,true,'gotohtml.php?action=Articletype<?php echo "&page=".$page."&classid=".$classid."&type=".$type."&key=".$key;?>');" value="����������ʾ����������HTML" />   <input type="button" onclick="del(this.form,true,'?action=qingkong&page=<?php echo $page; ?>');" value="�������" />    </td>
    </tr>
  </table>
</form>