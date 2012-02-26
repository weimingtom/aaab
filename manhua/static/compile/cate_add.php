<? if(!defined('ROOT_PATH')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="static/img/houtai.css" rel="stylesheet" type="text/css" />
<title>默认首页 - rt798.com CMS管理系统</title>
<meta content="管理平台" name=keywords />
<meta content="管理平台" name=description />
<script language="javascript" type="text/javascript" src="static/js/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="static/js/command.js"></script>
<script language="javascript" type="text/javascript">	

$(function(){
	
	CMSAPP.addkehu = {
		
		subject: '分类管理',
		
		init: function(){
			
			CMSAPP.command.webTitle(this.subject);
			
			CMSAPP.command.mtabletr();
			
		}
	}

	CMSAPP.addkehu.init();
	
});

</script>
<style type="text/css">

.table1 tr.cur td { background:#ECF6FA;}
.table1a td{background:#d1eef9; }
#tongpage div a{ margin-left:2px; margin-right:2px;}
#tongpage div em{ margin-right:5px;}
.iptxt{width:30px;}


.table1 { width: 100%; background:none}
.table1 tbody { padding-left: 20px; }
.table1 tr {border-bottom:1px dotted #CCC;  }
.table1 td { border-bottom: 1px dotted #CCC; text-align: left; }
.table1 th { border-bottom: 1px solid #CCC;  text-align: left; color: #1A5499; }
.table1 th a { text-decoration: none; color:#1A5499; }
.table1 th a:hover { text-decoration: underline; color: #FF6600; }
</style>
</head>
<body>

<? include $this->gettpl('admin_top');?>

<div id="wrap">

<div class="path">
<span><a href="?c=cate">用户管理</a></span>
<span>添加分类</span></div>

<div class="content">
<div class="c1">
	  <form name="uform" action="" method="post">
      <table width="100%" border="0" cellpadding="0" cellspacing="1" class="c1content">
           <tr>
             <td align="right">所属分类：</td>
             <td>
			 <select name="parent_id">
			 <option value="0">作为大类</option>
			 <? foreach((array)$options as $val) {?>
			 <option value="<?=$val['cate_id']?>"><?=$val['cate_name']?></option>
			 <? } ?>
             </select>             </td>
           </tr>
           <tr>
            <td width="10%" align="right">分类名称：</td>
            <td><input name="cate_name" type="text" class="w01" id="cate_name" /></td>
          </tr>
		   <tr>
             <td align="right">作为导航：</td>
             <td><label>
               <input name="ismenu" type="checkbox" id="ismenu" value="1" />
             </label></td>
           </tr>
           <tr>
             <td align="right">分类链接：</td>
             <td><input name="menulink" type="text" class="w01" id="menulink" /></td>
           </tr>
          <tr>
            <td width="10%" align="right">分类排序：</td>
            <td><input name="sort_order" type="text" class="w01" id="sort_order" /></td>
          </tr>
          
          <tr>
            <td align="right">备注：</td>
            <td><textarea name="content" cols="45" rows="3" id="content"></textarea></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td><input class="w02" name="input" type="submit" value="添 加" />
            &nbsp;&nbsp;<input class="w02" name="input" type="reset" value="重 填" />
            <input type="hidden" name="action" value="add" /></td>
            </tr>
        </table>
      
      </form>
    </div>
</div>
  
<? include $this->gettpl('admin_floor');?>
</div>

</body>
</html>