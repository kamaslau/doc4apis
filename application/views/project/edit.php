<style>


	/* 宽度在768像素以上的设备 */
	@media only screen and (min-width:769px)
	{

	}
	
	/* 宽度在960像素以上的设备 */
	@media only screen and (min-width:961px)
	{

	}

	/* 宽度在1280像素以上的设备 */
	@media only screen and (min-width:1281px)
	{

	}
</style>

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户等级
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
				<?php echo form_error('name') ?>
			</div>
			
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="说明"><?php echo $item['description'] ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
			
			<div class=form-group>
				<label for=sdk_ios class="col-sm-2 control-label">iOS最低版本</label>
				<div class=col-sm-10>
					<input class=form-control name=sdk_ios type=text value="<?php echo $item['sdk_ios'] ?>" placeholder="例如：9.0">
				</div>
				<?php echo form_error('sdk_ios') ?>
			</div>

			<div class=form-group>
				<label for=sdk_android class="col-sm-2 control-label">Android最低版本</label>
				<div class=col-sm-10>
					<input class=form-control name=sdk_android type=text value="<?php echo $item['sdk_android'] ?>" placeholder="例如：4.1">
				</div>
				<?php echo form_error('sdk_android') ?>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>项目素材</legend>

			<div class=form-group>
				<label for=url_logo class="col-sm-2 control-label">LOGO（可选）</label>
				<div class=col-sm-10>
					<input id=url_logo class=form-control name=url_logo type=file placeholder="请上传jpg/png/webp格式设计图，文件大小控制在2M之内" multiple>
					<button id=file-upload class="btn btn-primary btn-lg" type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>
					<?php echo form_error('url_logo') ?>
				</div>
			</div>

<script>
$(function(){
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
	function file_upload()
	{
		//TODO 禁用上传按钮
		$('#file-upload').html('<i class="fa fa-refresh" aria-hidden=true></i> 上传中');
		
		// 创建FormData对象
		var formData = new FormData();

		// 获取待上传的文件数量（HTML中可通过type=file表单项中添加multiple属性对多文件上传提供支持）
		var file_count = $('#url_logo')[0].files.length;

		// 将所有需上传的文件信息放入formData对象
		for (var i=0; i<file_count; i++)
		{
			formData.append('file'+i, $('#url_logo')[0].files[ i ] );
		}

		$.ajax({
	        url: 'https://www.doc4apis.com/project/upload',
	        type: 'POST',
			cache: false, // 上传文件不需要缓存
	        data: formData,

	        processData: false,  // 不处理发送的数据
	        contentType: false // 不设置Content-Type请求头
	    }).then(function(data){
			alert(data.content);

			//TODO 激活上传按钮
			$('#file-upload').html('<i class="fa fa-upload" aria-hidden=true></i> 上传');
	    });
	}

	$('#file-upload').click(function(){
		check_support_formdata();
		file_upload();
	});
});
</script>

			<div class=form-group>
				<label for=url_assets class="col-sm-2 control-label">素材URL（可选）</label>
				<div class=col-sm-10>
					<span class=help-block>此处填写项目的LOGO文件、应用商店预览图等素材URL；具体页面相关的素材URL建议在相应页面上传。</span>
					<input class=form-control name=url_assets type=url value="<?php echo $item['url_assets'] ?>" placeholder="请将PSD文件、UI素材、字体、媒体文件等压缩后上传到百度云盘，并将该压缩文件的分享链接填入此处">
					<?php echo form_error('url_assets') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>正式环境</legend>
			
			<div class=form-group>
				<label for=sandbox_url_web class="col-sm-2 control-label">WEB URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=sandbox_url_web type=url value="<?php echo $item['sandbox_url_web'] ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('sandbox_url_web') ?>
			</div>

			<div class=form-group>
				<label for=sandbox_url_api class="col-sm-2 control-label">API URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=sandbox_url_api type=url value="<?php echo $item['sandbox_url_api'] ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('sandbox_url_api') ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>正式环境</legend>

			<div class=form-group>
				<label for=url class="col-sm-2 control-label">WEB URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url type=url value="<?php echo $item['url'] ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('url') ?>
			</div>

			<div class=form-group>
				<label for=url_api class="col-sm-2 control-label">API URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_api type=url value="<?php echo $item['url_api'] ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('url_api') ?>
			</div>

			<div class=form-group>
				<label for=url_ios class="col-sm-2 control-label">iOS URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_ios type=url value="<?php echo $item['url_ios'] ?>" placeholder="必须是官方下载URL；URL中除appid外不可有其它参数">
				</div>
				<?php echo form_error('url_ios') ?>
			</div>

			<div class=form-group>
				<label for=url_android class="col-sm-2 control-label">Android URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_android type=url value="<?php echo $item['url_android'] ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('url_android') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>