<div style="width: 700px; margin: auto;">
	<h1>5. Xiuno BBS 选择插件</h1>
	<p>您可以在这里选择您需要的插件，也可以跳过此步，在您需要的时候，在后台进行安装。</p>
	<form action="?step=plugin" method="post" id="pluginform">
		<div class="border bg1 shadow">
			<ul>
				<li><input type="checkbox" name="plugindir[]" value="view_width_screen" /> <b class="red">【Xiuno BBS 2.0.0 RC1 宽屏风格】</b><span>(特点：窄导航，宽屏风格，区别于传统的论坛)</span></li>
				<li><input type="checkbox" name="plugindir[]" value="qq_login" /> 【QQ 登录】</li>
				<li><input type="checkbox" name="plugindir[]" value="announcement" checked="checked" /> 【三级置顶转公告】</li>
				<li><input type="checkbox" name="plugindir[]" value="vip_seo" /> 【SEO 关键词优化】</li>
			</ul>
		</div>
		<p style="text-align: center;">
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