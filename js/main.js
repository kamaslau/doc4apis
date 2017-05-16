/**
 * doc4apis 主要JS功能
 *
 * Kamas 20170512
 */

$(function(){
	// 点击“添加参数”按钮后，在相应textarea中添加一行HTML模板
	$('.add-html').click(function(){
		add_html( $(this) );
		return false;
	});

	// HTML模板
	var html_templates = {
		'params_request' : '<tr><td>名称</td><td>类型</td><td>是否必要</td><td>示例</td><td>说明</td></tr>', // 请求参数
		'params_respond' : '<tr><td>名称</td><td>类型</td><td>示例</td><td>说明</td></tr>', // 响应参数
		'sign' : '<li></li><li></li><li></li>', // 签名方式
		'sample_request' : '{"":"","":""}', // 请求示例
		'sample_respond' : '{"status":200,"content":[{"":"","":""}]}', // 响应示例
		'elements' : '<tr><td>ID</td><td>类型</td><td>内容</td><td>说明</td></tr>', // 主要视图元素
		'onloads' : '<li></li><li></li><li></li>', // 载入事件
		'events' : '<h4></h4><ol><li></li><li></li><li></li></ol>', // 业务流程（其它事件）
		//'' : '',
	}

	function add_html(object)
	{
		// 获取相应textarea的name属性
		var textarea_name = object.attr('data-textarea-name');

		// 获取当前textarea内容，并清除首尾空格
		var current_html = $.trim( $('[name='+ textarea_name +']').val() );

		// 生成新内容
		var html_row = html_templates[textarea_name];
		
		// 若字段值不为空，添加换行符
		if (current_html != '')
		{
			html_row = "\r\n" + html_row;
		}
		var new_row = current_html + html_row;

		// 添加新内容
		$('[name='+ textarea_name +']').val(new_row);
	}
});