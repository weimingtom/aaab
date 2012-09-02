<div style="width: 700px; margin: auto;">
	<h1>5. Xiuno BBS 选择插件</h1>
	<p>您可以在这里选择您需要的插件，也可以跳过此步，在您需要的时候，在后台进行安装。</p>
	<br />
	<form action="?step=plugin" method="post" id="pluginform">
	
		<h3>风格插件：</h3>
		<div class="border bg1 shadow">
			<table>
				<tr align="center">
					<td width="33%">
						<a href="http://custom.xiuno.com/view/image/style_default.jpg" target="_blank"><img src="view_default_icon.jpg" /></a>
						<br /> <input type="radio" name="style" value="default" checked="checked" /> <b>默认黑色风格</b>
					</td>
					<td width="33%">
						<a href="http://custom.xiuno.com/view/image/style_blue.jpg" target="_blank"><img src="../plugin/view_blue/icon.jpg" /></a>
						<br /> <input type="radio" name="style" value="blue" /> <b>蓝色风格</b>
					</td>
					<td width="33%">
						<a href="http://custom.xiuno.com/view/image/style_width.jpg" target="_blank"><img src="../plugin/view_width_screen/icon.jpg" /></a>
						<br /> <input type="radio" name="style" value="width_screen" /> <b>宽屏黑色风格</b><br />宽屏下的内容排版更出众
					</td>
			
			</table>
		</div>
		
		<h3>其他插件：</h3>
		<div class="border bg1 shadow">
			<ul>
				<li><input type="checkbox" name="plugindir[]" value="index_four_column" checked="checked" /> 【首页四格】</li>
				<li><input type="checkbox" name="plugindir[]" value="announcement" checked="checked" /> 【三级置顶转公告】</li>
			</ul>
		</div>
		<p style="text-align: center;">
			<input type="button" value=" 上一步" onclick="history.go(-2);"/>
			<input type="submit" value=" 下一步" name="formsubmit" />
		</p>
		<?php if(core::gpc('formsubmit', 'P')) { ?>
			<?php if($error) { ?>
				<div class="error"><?php echo $error;?></div>
			<?php } else {?>
				<script type="text/javascript">setTimeout(function() {window.location='?step=complete'}, 1000);</script>
			<?php }?>
		<?php }?>
	</form>
</div>