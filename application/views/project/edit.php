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

<script defer src="/js/file-upload.js"></script>
<script defer src="/js/main.js"></script>

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
					<?php if ( !empty($item['url_logo']) ): ?>
					<figure id=project-logo class=row>
					<?php
							// 若含多项，根据分隔符拆分并轮番输出
							if (strpos( trim($item['url_logo']), ' ') !== FALSE):
								$items_array = explode(' ', $item['url_logo']);
								foreach ($items_array as $item_to_show):
					?>	
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item_to_show ?>">
					<?php
								endforeach;
							else:
					?>
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item['url_logo'] ?>">
					<?php 	endif ?>
					</figure>
					<?php endif ?>

					<input id=url_logo class=form-control type=file multiple>
					<input name=url_logo type=hidden value="<?php echo $item['url_logo'] ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir=project data-selector-id=url_logo data-input-name=url_logo type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
					<?php echo form_error('url_logo') ?>
				</div>
			</div>

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
			<legend>开发环境</legend>

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
			<legend>正式/生产环境</legend>

			<div class=form-group>
				<label for=url_web class="col-sm-2 control-label">WEB URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_web type=url value="<?php echo $item['url_web'] ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('url_web') ?>
			</div>
			
			<div class=form-group>
				<label for=url_wechat class="col-sm-2 control-label">微信公众号二维码（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_wechat type=url value="<?php echo $item['url_wechat'] ?>" placeholder="即微信公众号二维码文本">
					<p class=help-block>可以通过二维码解码工具获取二维码文本 http://cli.im/deqr</p>
				</div>
				<?php echo form_error('url_wechat') ?>
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
		
		<fieldset>
			<legend>公共参数（可选）</legend>
			
			<div class=form-group>
				<label for=params_request class="col-sm-2 control-label">请求参数</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;tr&gt;&lt;td&gt;名称&lt;/td&gt;&lt;td&gt;类型&lt;/td&gt;&lt;td&gt;是否必要&lt;/td&gt;&lt;td&gt;示例&lt;/td&gt;&lt;td&gt;说明&lt;/td&gt;&lt;/tr&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=params_request>+</a>
					<textarea class=form-control name=params_request rows=10 placeholder="请求参数"><?php echo $item['params_request'] ?></textarea>
				</div>
				<?php echo form_error('params_request') ?>
			</div>
			
			<div class=form-group>
				<label for=params_respond class="col-sm-2 control-label">响应参数</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;tr&gt;&lt;td&gt;名称&lt;/td&gt;&lt;td&gt;类型&lt;/td&gt;&lt;td&gt;示例&lt;/td&gt;&lt;td&gt;说明&lt;/td&gt;&lt;/tr&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=params_respond>+</a>
					<textarea class=form-control name=params_respond rows=10 placeholder="响应参数"><?php echo $item['params_respond'] ?></textarea>
				</div>
				<?php echo form_error('params_respond') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>