/**
 * Ajaxupload类
 *
 * 处理AJAX文件上传
 *
 * @version beta20170515
 * @author Kamas 'Iceberg' Lau <https://github.com/kamaslau/ajaxupload>
 * @copyright Kamas 'Iceberg' Lau <kamaslau@outlook.com>
 */

// AJAX文件上传服务器端URL；上传目标文件夹名稍后通过上传按钮的相关属性获取
var api_url = '//www.doc4apis.com/ajaxupload?target=';

// 图片存储根路径
var uploads_url = '//www.doc4apis.com/uploads/';

/* 从此处起请谨慎修改 */
$(function(){
	// 文件上传主处理方法
	$('.file-upload').click(function(){
		// 检查当前浏览器是否支持AJAX文件上传
		check_support_formdata();

		// 禁用上传按钮
		$(this).attr('disabled', 'disabled');
		$(this).html('<i class="fa fa-refresh" aria-hidden=true></i> 上传中……');

		// 处理上传
		var button = $(this);
		file_upload( button );

		// 激活上传按钮
		button.removeAttr('disabled');
		button.html('<i class="fa fa-upload" aria-hidden=true></i> 上传');
	});

	// 检查浏览器是否支持完成文件上传必须的XHR2（FormData）功能
	function check_support_formdata()
	{
		if ( ! window.hasOwnProperty('FormData') )
		{
			alert('您正在使用安全性差或者已过时的浏览器；请使用谷歌或火狐浏览器。');
			return false;
		}
	}

	// 获取文件大小
	function file_size(file)
	{
		return (file.files[0].size / 1024).toFixed(2);
	}

	// 处理文件上传
	function file_upload(button)
	{
		// 创建FormData对象
		var formData = new FormData();

		// 获取文件选择器对象
		var file_selector = $( '#' + button.attr('data-selector-id') );

		// 获取待上传的文件数量（HTML中可通过type=file表单项中添加multiple属性对多文件上传提供支持）
		var file_count = file_selector[0].files.length;
		
		// 若无任何文件被选中，进行提示
		if (file_count == 0)
		{
			alert('请选择文件');
			return;
		}

		// 将所有需上传的文件信息放入formData对象
		for (var i=0; i<file_count; i++)
		{
			formData.append('file'+i, file_selector[0].files[ i ] );
		}

		// 获取上传目标文件夹名
		var dir_target = button.attr('data-target-dir');

		$.ajax({
	        url: api_url + dir_target, // 处理上传的后端URL
	        type: 'POST',
			cache: false, // 上传文件不需要缓存
	        data: formData,

	        processData: false,  // 不处理发送的数据
	        contentType: false // 不设置Content-Type请求头
	    }).then(function(data){

			// 进行总体提示
			if (data.status == 200)
			{
				alert('成功上传');
			}
			else // 若上传失败，进行提示
			{
				alert(data.content);
			}

			// 初始化表单值
			var input_value = '';

			// 轮番显示上传结果
			$.each(
				data.items,
				function(i, item)
				{
					// 若上传成功，显示预览；若上传失败，显示源文件信息及错误描述
					if (item.status == 200)
					{
						// 更新预览区
						var item_content =
						'<li class="col-xs-12 col-md-3">' +
						'	<figure class="thumbnail">' +
						'		<figcaption>' + item.content + '</figcaption>' +
						'		<img src="' + uploads_url + dir_target + '/'+ item.content +'">' +
						'	</figure>' +
						'</li>';

						// 更新表单值
						input_value += item.content + ' ';
					}
					else
					{
						// 更新预览区
						var item_content =
						'<li class="col-xs-12 col-md-3">' +
						'	<dl>' +
						'		<dt>失败原因</dt><dd>' + item.content.descirption + '</dd>' +
						'		<dt>源文件名</dt><dd>' + item.content.file.name + '</dd>' +
						'		<dt>源文件类型</dt><dd>' + item.content.file.type + '</dd>' +
						'		<dt>源文件大小</dt><dd>' + (item.content.file.size / 1024).toFixed(2) + 'kb</dd>' +
						'	</dl>' +
						'</li>';
					}

					// 在相应位置显示预览
					var file_previewer = button.siblings('.upload_preview');
					file_previewer.prepend(item_content);
				}
			); //end $.each

			// 向相应表单项赋值
			input_value = $.trim(input_value);
			if (input_value != '')
			{
				$('[name=' + button.attr('data-input-name') + ']').val(input_value);
			}
	    });

	} //end file_upload

});