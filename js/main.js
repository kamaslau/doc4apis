$(function(){
	// 点击“添加参数”按钮后，在相应textarea中添加一行HTML表格行模板
	$('.add-row').click(function(){
		add_row($(this));
		return false;
	});
	function add_row(object)
	{
		// 获取相应textarea的name属性
		var textarea_name = object.attr('data-textarea-name');

		// 获取当前textarea内容
		var current_html = $('[name='+ textarea_name +']').text();
		
		// 生成新内容
		var html_row = '<tr><td>名称</td><td>类型</td><td>必要</td><td>示例</td><td>说明</td></tr>';
		if (current_html != '')
		{
			html_row = "\r\n" + html_row; // 自动添加换行符
		}
		var new_row = current_html + html_row;
		
		// 添加新内容
		$('[name='+ textarea_name +']').text(new_row);
	}

});