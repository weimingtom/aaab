<?php
/**
 * 发布微博Widget
 * 
 * @author daniel <desheng.young@gmail.com>
 */
class WeiboWidget extends Widget {
	
	/**
	 * 发布微博Widget, 用法包括分享等
	 * 
	 * $data接受的参数:
	 * <code>
	 * array(
	 * 	'page_title'(可选)	=> '弹出窗标题',		 // 默认为"分享到我的微博"
	 * 	'button_title'(可选)	=> '弹出窗内按钮的标题', // 默认为"发布"
	 * 	'tpl_name'(必须)		=> '模版名称',		 // 管理后台配置的模版的名称
	 * )
	 * </code>
	 * <br/>
	 * 与其它Widget不同的是, WeiboWidget必须手动触发, 触发方法是在JS中调用_widget_weibo_start(page_title, tpl_data, param_data)方法,
	 * 参数说明:
	 * page_title: string					  与WeiboWidget的page_title相同
	 * tpl_data:   先serialize再urlencode的数组 模版数据, 用以填充WeiboWidget的tpl_name参数标示的模版变量
	 * param_data: 先serialize再urlencode的数组 widget参数, 格式为:
	 * 										  array(
	 * 										  	'has_status' 		=> 1,		   // 或0. 是否含有状态.
	 * 										  	'is_success_status' => 1,		   // 或0. 状态是否为成功. 当has_status=1时有效.
	 * 										  	'status_title' 		=> '状态的标题', // 状态的标题, 如"发布成功", "发布失败"等. 当has_status=1时有效.
	 * 										  )
	 * 
	 * @see Widget::render()
	 */
	public function render($data) {
		// 默认值
		$data['page_title']		= isset($data['page_title']) 		? $data['page_title'] 		: '分享到我的微博';
		$data['button_title']	= isset($data['button_title'])		? $data['button_title']		: '发布';
		$data['status_title']	= isset($data['status_title'])		? t($data['status_title'])	: '';

		$data['url']	= U('home/Widget/weibo',array('button_title'=>urlencode($data['button_title']),'tpl_name'=>$data['tpl_name']));
		
		$content = $this->renderFile(ADDON_PATH . '/widgets/Weibo.html', $data);
		return $content;
	}
}