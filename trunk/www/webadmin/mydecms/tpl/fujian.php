<?php if(!defined('WWW.MYDECMS.COM')) exit('请不要非法操作'); ?>
<div id="fade" class="layer_body"></div>
  <div id="nav" class="nav">
    <ul>
      <li><a href="fujian.php">根目录</a></li>
      <li><a href="javascript:void(0);" onClick="opendiv('open','div_2');">新目录</a></li>
      <li><a href="javascript:void(0);" onClick="opendiv('open','div_1');">上传</a></li>
    </ul>
    <div class="clear"></div>
  </div>
  <div id="div_1" class="div" onmouseover="Move_obj(&quot;div_1&quot;)">
    <h3><cite><a href="javascript:void(0);" onClick="opendiv('close','div_1');">关闭</a></cite><span>上传文件：</span></h3>
      <div><form enctype="multipart/form-data" method="post" action="?action=upload&filename=<?php echo $fileName;?>">
        请选择文件：
        <input type="file" name="upload_file" class="input"  />
        <input type="submit" value="上传" />
      </form>
      </div>
  </div>
  <div id="div_2" class="div" onmouseover="Move_obj(&quot;div_2&quot;)">
    <h3><cite><a href="javascript:opendiv('close','div_2');">关闭</a></cite><span>新建目录：</span></h3>
      <div><form enctype="multipart/form-data" method="post" action="?action=newdir&filename=<?php echo $fileName;?>">
        文件夹名称：
        <input type="text" name="dir_name" style="width:150px;" class="input" />
        <input type="submit" value="新建" />
      </form>
      </div>
  </div>
  <div id="div_3" class="div" onmouseover="Move_obj(&quot;div_3&quot;)">
    <h3><cite><a href="javascript:opendiv('close','div_3');">关闭</a></cite><span>移动文件：</span></h3>
      <div><form method="post" action="?action=move">
        <p>注：如果目标文件夹不存在，将会被创建。可以填目录里目录，如:test/test，结尾不能有/。</p>
        目标文件夹名称：
        <input type="text" name="todirname" style="width:150px;" class="input"  />
        <input type='hidden' name='urlstr' value='' id='movefileurl' />
        <input type='hidden' name='file' value='' id='movefilename' />
        <input type="submit" value="移动" />
      </form>
      </div>
  </div>
</div>