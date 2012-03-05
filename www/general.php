<?php
define('WWW.MYDECMS.COM',true);
include 'webadmin/include/config.inc.php';

$a = isset($_GET['a']) ? $_GET['a'] : 'about';
$navIds = array(
	'about'=>13,			// ���ڴ�½
	'dynamic'=>14,			// ��½��̬
	'partners'=>15,			// �������
	'copyright'=>16,		// ��Ȩ����
	'link'=>17,			// ��������
	'jobs'=>18,			// ��Ƹ��Ϣ
	'feedback'=>19,			// �������
	'help'=>20,			// ��������
	'contact'=>21,			// ��ϵ����
);

$idNavs = array_flip($navIds);

$navId = isset($navIds[$a]) ? $navIds[$a] : '';
if (!$navId) {
	exit('error');
}
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
</head>

<body>
<div id="page_main">
	<div id="page_container">
		<?php include("header.php")?>    
		<!--���ݿ�ʼ-->
		<div id="otherpage">
	    
		    	<!--��������ʼ-->
			<div class="left">
	        	<ul>
		        	<?php
		        	$navs = $mysql->fetch_all("SELECT * FROM -table-article WHERE dafenglei='21'", 'id');
		        	foreach ($navs as $v) {?>
		            	<li <?php if($navId==$v['id']){?>class="here"<?php }?>><a href="general.php?a=<?php echo $idNavs[$v['id']]?>"><?php echo $v['title']?></a></li>
		            	<?php }?>
		            </ul>
			</div>
		        <!--����������-->
	        
		        <!--�Ҳ����ݿ�ʼ-->
			<div class="right">
				<h1><?php echo $navs[$navId]['title']?>~~<?php echo $navs[$navId]['description']?></h1>
				<?php echo $navs[$navId]['content']?>
			</div>
		        <!--�Ҳ����ݽ���-->
		</div>
		<!--���ݽ���-->
		<?php include("footer.php");?>
	</div>
</div>
</body>
</html>
