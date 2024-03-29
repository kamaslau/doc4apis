<style>

	/* 宽度在768像素以上的设备 */
	@media only screen and (min-width:769px)
	{

	}

	/* 宽度在992像素以上的设备 */
	@media only screen and (min-width:993px)
	{

	}

	/* 宽度在1200像素以上的设备 */
	@media only screen and (min-width:1201px)
	{

	}
</style>

<script defer src="/js/file-upload.js"></script>
<script defer src="/js/main.js"></script>

<div id=breadcrumb>
	<ol class="breadcrumb container-fluid">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class="container-fluid">
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户等级
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=biz_id class="col-sm-2 control-label">所属企业</label>
				<div class=col-sm-10>
					<select class=form-control name=biz_id>
						<option value="">个人项目</option>

					<?php if ( isset($bizs) ):
							$input_name = 'biz_id';
							$text_name = 'brief_name';
							$option_list = $bizs;
							foreach ($option_list as $option):
						?>
						<option value="<?php echo $option[$input_name] ?>" <?php echo set_select($input_name, $option[$input_name]) ?>>
							<?php echo $option[$text_name] ?>
						</option>
						<?php endforeach ?>

					<?php elseif ( isset($biz) ): ?>
							<option value="<?php echo $biz[$input_name] ?>" <?php echo set_select($input_name, $biz[$input_name], TRUE) ?>><?php echo $biz[$text_name] ?></option>
					<?php endif ?>
					</select>
				</div>
			</div>
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="最多10个字符" required>
				</div>
				<?php echo form_error('name') ?>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">简介（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="最多100个字符"><?php echo set_value('description') ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>项目素材</legend>

			<div class=form-group>
				<label for=url_logo class="col-sm-2 control-label">LOGO（可选）</label>
				<div class=col-sm-10>
					<input id=url_logo class=form-control type=file>
					<input name=url_logo type=hidden value="<?php echo set_value('url_logo') ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir=project data-selector-id=url_logo data-input-name=url_logo type=button><i class="fal fa-upload"></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
					<?php echo form_error('url_logo') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_preview class="col-sm-2 control-label">效果图（可选）</label>
				<div class=col-sm-10>
					<input id=url_preview class=form-control type=file multiple>
					<input name=url_preview type=hidden value="<?php echo set_value('url_preview') ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir=project data-selector-id=url_preview data-input-name=url_preview type=button><i class="fal fa-upload"></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
					<?php echo form_error('url_preview') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_assets class="col-sm-2 control-label">素材URL（可选）</label>
				<div class=col-sm-10>
					<span class=help-block>此处填写项目的LOGO文件、应用商店预览图等素材URL；具体页面相关的素材URL建议在相应页面上传。</span>
					<input class=form-control name=url_assets type=url value="<?php echo set_value('url_assets') ?>" placeholder="请将PSD文件、UI素材、字体、媒体文件等压缩后上传到百度云盘，并将该压缩文件的分享链接填入此处">
					<?php echo form_error('url_assets') ?>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>