<?php if(!defined('WWW.MYDECMS.COM')) exit('�벻Ҫ�Ƿ�����'); ?>
<div id="fade" class="layer_body"></div>
  <div id="nav" class="nav">
    <ul>
      <li><a href="fujian.php">��Ŀ¼</a></li>
      <li><a href="javascript:void(0);" onClick="opendiv('open','div_2');">��Ŀ¼</a></li>
      <li><a href="javascript:void(0);" onClick="opendiv('open','div_1');">�ϴ�</a></li>
    </ul>
    <div class="clear"></div>
  </div>
  <div id="div_1" class="div" onmouseover="Move_obj(&quot;div_1&quot;)">
    <h3><cite><a href="javascript:void(0);" onClick="opendiv('close','div_1');">�ر�</a></cite><span>�ϴ��ļ���</span></h3>
      <div><form enctype="multipart/form-data" method="post" action="?action=upload&filename=<?php echo $fileName;?>">
        ��ѡ���ļ���
        <input type="file" name="upload_file" class="input"  />
        <input type="submit" value="�ϴ�" />
      </form>
      </div>
  </div>
  <div id="div_2" class="div" onmouseover="Move_obj(&quot;div_2&quot;)">
    <h3><cite><a href="javascript:opendiv('close','div_2');">�ر�</a></cite><span>�½�Ŀ¼��</span></h3>
      <div><form enctype="multipart/form-data" method="post" action="?action=newdir&filename=<?php echo $fileName;?>">
        �ļ������ƣ�
        <input type="text" name="dir_name" style="width:150px;" class="input" />
        <input type="submit" value="�½�" />
      </form>
      </div>
  </div>
  <div id="div_3" class="div" onmouseover="Move_obj(&quot;div_3&quot;)">
    <h3><cite><a href="javascript:opendiv('close','div_3');">�ر�</a></cite><span>�ƶ��ļ���</span></h3>
      <div><form method="post" action="?action=move">
        <p>ע�����Ŀ���ļ��в����ڣ����ᱻ������������Ŀ¼��Ŀ¼����:test/test����β������/��</p>
        Ŀ���ļ������ƣ�
        <input type="text" name="todirname" style="width:150px;" class="input"  />
        <input type='hidden' name='urlstr' value='' id='movefileurl' />
        <input type='hidden' name='file' value='' id='movefilename' />
        <input type="submit" value="�ƶ�" />
      </form>
      </div>
  </div>
</div>