<!--�ײ���ʼ-->
<div id="page_footer">
	<?php
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
	$navs = $mysql->fetch_all("SELECT id,title FROM -table-article WHERE dafenglei='21'");
	foreach ($navs as $k=>$v) {?>
    	<a href="general.php?a=<?php echo $idNavs[$v['id']]?>" target="_blank"><?php echo $v['title']?></a>
    	<?php 
		if($k != 8) {echo '��';}
	}?>
	<br />
	��β����½��Ȩ���� ��ICP��12001441��-1
</div>
<!--�ײ�����-->