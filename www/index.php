<?php
define('WWW.MYDECMS.COM',true);
include 'webadmin/include/config.inc.php';

/*******************************ѭ�����ಢ���뵽����class_arr*****************************/
$class_arr=array();
$sql = "select * from `-table-dafenglei` order by `sort` asc, id asc";
$query = $mysql -> query($sql);
while($row = $mysql -> fetch_array($query)){
	$class_arr[$row['id']] = array($row['id'],$row['name'],$row['classid'],$row['sort'],$row['dir'],$row['type']);
}
/*******************************ѭ�����ಢ���뵽����*************************************/
/*
$tpl = new templateClass($mydecms['moban']);
$tpl -> assign(array('mysql','mydecms','tpl','class_arr','page'));
$tpl -> display('index');
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title><?php echo $mydecms['webtitle']?></title>
<meta name="robots" content="all" />
<meta name="description" content="<?php echo $mydecms['webdescription']?>" />
<meta name="keywords" content="<?php echo $mydecms['webkeywords']?>" />
<meta name="Copyright" content="��Ȩ��Ϣ" />
<meta name="revisit-after" content="1 days" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/comic.css">
<script type="text/javascript" src="js/core_min.js"></script>
</head>

<body>

<div id="page_main">
<div id="page_container">

	<!--ͷ����ʼ-->
<div id="page_header">
    	<div class="logobox" title="<?php echo $mydecms['webname']?>">&nbsp;</div>
        <div class="big_img">&nbsp;</div>
      <div class="topmenubox">
        <a href="#"><strong>artery</strong></a><a href="#">�˳�</a><a href="#">����Ϣ</a><a href="#">ͳ��</a><a href="#">ϵͳ����</a><a href="#">����</a>
        </div>
<div class="searchbox">
        	<form action="" method="get">
              <input type="button" value="&nbsp;" class="buttonstyle" />
              <input name="" type="text" class="inputstyle"   value="�������" onFocus="if(this.value=='�������')this.value='';" onBlur="if(this.value=='')this.value='�������';" />
        </form>
        </div>
    </div>
    
    <!--�˵���ʼ-->
<div id="menu">
    	<ul>
		<li class="here"><a href="<?php echo $mydecms['weburl']?>">��ҳ<em>HOMEPAGE</em></a></li>
		<li><a href="#">��½����<em>MENU TEXT</em></a></li>
		<li><a href="#">��������<em>MENU TEXT</em></a></li>
		<li><a href="#">�·�����<em>MENU TEXT</em></a></li>
		<li><a href="#">�����鱨<em>MENU TEXT</em></a></li>
		<li><a href="#">COSPLAY<em>MENU TEXT</em></a></li>
        </ul>
    </div>
    <!--�˵�����-->
    <!--ͷ������-->
    
    
    <!--���ݿ�ʼ-->
<div id="content">
    
    	<!--��������ʼ-->
	<div class="left">
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
		<div class="adbox"><img src="images/t7.gif" width="120" height="240" /></div>
	</div>
        <!--����������-->
        
        <!--�Ҳ����ݿ�ʼ-->
		<div class="right">
        
        <!--������ͼ��ʼ-->
        <div id="Tu_commend">
          <h2 class="Tu_title"><span class="title"><img src="images/a_34.gif" width="81" height="5" /></span> <span class="more"><span id="TuList"></span></span></h2>
          <div class="Tu_box"><img src="images/a_35.gif" id="Tu_Left" alt="����" onMouseDown="TU_GoUp()" onMouseUp="TU_StopUp()" onMouseOut="TU_StopUp()" ondragstart="return false" />
            <div id="TU_Cont">
              <div class="ScrTuCont">
                <div id="Tu_List1">
                <ul>
		<?php
		$news = $mysql->fetch_all("SELECT * FROM `-table-article` where dafenglei='1' ORDER BY id DESC LIMIT 5");
		foreach ($news as $v) { ?>
			<li><A title="<?php echo $v['title']?>" href="<?php echo $v['url']?>" target="_blank"><IMG src="<?php echo $v['img']?>" ></A></li>
		<?php }?>
                </ul>
                </div>
                <div id="Tu_List2"></div>
              </div>
            </div>
            <img src="images/a_35.gif" id="Tu_Right" alt="����" onMouseDown="TU_GoDown()" onMouseUp="TU_StopDown()" onMouseOut="TU_StopDown()" ondragstart="return false" /></div>
        </div>
        <script src="js/11.js" type="text/javascript"></script>
        <!--������ͼ����-->
        
        <!--�������ſ�ʼ-->
        <div id="topnewsbox">
        <div id="andyscroll">
        <div id="scrollmessage">
        <?php
	$news = $mysql->fetch_all("SELECT * FROM `-table-article` WHERE dafenglei='2' ORDER BY id DESC LIMIT 6");
	foreach ($news as $v) { ?>
          <div class="textboxlist">
            <div class="textbox">
            	<a href="<?php echo $v['url']?>" target="_blank"><img src="<?php echo $v['img']?>" /></a>
            	<a href="<?php echo $v['url']?>" target="_blank"><?php echo $v['title']?>...</a> <br />
		<?php echo $v['description']?> ... 
              </div>
          </div>
         <?php }?>
        </div>
      </div>
	<script src="js/22.js" type="text/javascript"></script>      
        </div>
        <!--�������Ž���-->
        
        <div id="index_bannerbox">
        	<a href="#"><img src="images/t10.gif" width="830" height="62" /></a>
        </div>
        
        <!--���ݲ��ֿ�ʼ-->
        <div id="container">
        
        	<!--��ಿ�ֿ�ʼ-->
            <div id="left">
         <?php
	$news = $mysql->fetch_all("SELECT * FROM `-table-article` WHERE dafenglei='3' ORDER BY id DESC LIMIT 5");
	foreach ($news as $k=>$v) { ?>
	<div class="content">
		<div class="timebox"><?php echo substr($k+101, 1,2);?><em><?php echo substr($v['date'], 5, 5)?></em></div>
		<h1><a href="<?php echo $v['url']?>"><?php echo $v['title']?></a></h1>
		<p><?php echo $v['description']?></p>
		<div class="img"><img src="<?php echo $v['img']?>" width="611" height="242" /></div>
	</div>
	<?php }?>
	<div class="morearticle">
		<a href="#">�鿴����...</a>
	</div>
            
            </div>
            <!--��ಿ�ֽ���-->
            
            <!--�Ҳಿ�ֿ�ʼ-->
            <div id="right">
            
                <!--��ǩ1��ʼ-->
		<div class="nTab">
			<h1>�������ڱ�</h1>
			<div class="TabTitle">
				<ul id="myTab1">
					<li class="normal" onmouseover="nTabs(this, 0);">����</li>
					<li class="normal" onmouseover="nTabs(this, 1);">��һ</li>
					<li class="normal" onmouseover="nTabs(this, 2);">�ܶ�</li>
					<li class="normal" onmouseover="nTabs(this, 3);">����</li>
					<li class="normal" onmouseover="nTabs(this, 4);">����</li>
					<li class="normal" onmouseover="nTabs(this, 5);">����</li>
					<li class="normal" onmouseover="nTabs(this, 6);">����</li>
				</ul>
			</div>
			<div class="TabContent">
				<?php 
				$categorys = $mysql->fetch_all("SELECT * FROM -table-dafenglei WHERE classid='4' order by `sort` ASC");
				foreach($categorys as $k=>$category) {?>
				<div id="myTab1_Content<?php echo $k?>">
					<div class="text">
						<ul>
							<?php 
							$news = $mysql->fetch_all("SELECT * FROM -table-article WHERE dafenglei='{$category['id']}' ORDER BY id DESC LIMIT 10");
							foreach ($news as $v) {?>
								<li><a href="<?php echo $v['url']?>"><?php echo $v['title']?></a></li>
							<?php }?>
						</ul>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
                <!--��ǩ1����-->
                
                <!--��ǩ2��ʼ-->
		<div class="day" id="j-tab-b1">
			<div class="title">
				<h4 class="H402">�������ڱ�</h4>
			</div>
			<ul>
				<li style="background:url(images/t17.gif)">����</li>
				<li style="background:url(images/t17.gif)" class="active">��һ</li>
				<li style="background:url(images/t17.gif)" >�ܶ�</li>
				<li style="background:url(images/t17.gif)" >����</li>
				<li style="background:url(images/t17.gif)" >����</li>
				<li style="background:url(images/t17.gif)" >����</li>
				<li style="background:url(images/t17.gif)" >����</li>
			</ul>
			<?php 
				$categorys = $mysql->fetch_all("SELECT * FROM -table-dafenglei WHERE classid='4' order by `sort` ASC");
				foreach($categorys as $k=>$category) {
					$news = $mysql->fetch_all("SELECT * FROM -table-article WHERE dafenglei='{$category['id']}' ORDER BY id DESC LIMIT 10");	
					if ($news) {?>
					<ol>
						<?php 
						
						foreach ($news as $v) {?>
						<li><a href="<?php echo $v['url']?>" title="<?php echo $v['title']?>" target="_blank"><?php echo $v['title']?></a></li>
						<?php }?>
					</ol>
					<?php }
				}?>
		</div>
                <!--��ǩ2����-->
 		<script src="js/tab.js" type="text/javascript"></script>
                
                <div class="adbox"><img src="images/t20.gif" /></div>
                <div class="adbox"><img src="images/t20.gif" /></div>
                <div class="adbox"><img src="images/t21.gif" /></div>
                <div class="adbox"><img src="images/t21.gif" /></div>

                
            </div>
            <!--�Ҳಿ�ֽ���-->
            
            
            
            
            
        </div>
        <!--���ݲ��ֽ���-->
            
		</div>
        <!--�Ҳ����ݽ���-->
        
    
	</div>
	<!--���ݽ���-->



    <!--�ײ���ʼ-->
    <div id="page_footer">
    <a href="#">���ڴ�½</a>��<a href="#">��½��̬</a>��<a href="#">�������</a>��<a href="#">��Ȩ����</a>��<a href="#">��������</a>��<a href="#">��Ƹ��Ϣ</a>��<a href="#">�������</a>��<a href="#">��������</a>��<a href="#">��ϵ��ʽ</a><br />
��Ȩ���ֲ��֡�����
    </div>
    <!--�ײ�����-->
</div>
</div>
<script type="text/javascript" src="js/chl_comic_min.js"></script>
</body>
</html>