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

			<?php if ($this->session->role === '管理员'): ?>
			<div class=form-group>
				<label for=biz_id class="col-sm-2 control-label">所属企业</label>
				<div class=col-sm-10>
					<select class=form-control name=biz_id>
						<option value="">个人项目</option>

						<?php
							$input_name = 'biz_id';
							$option_list = $bizs;
							foreach ($option_list as $option):
						?>
						<option value="<?php echo $option[$input_name] ?>" <?php if ($item[$input_name] === $option[$input_name]) echo 'selected' ?>>
							<?php echo $option['brief_name'] ?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php endif ?>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="最多10个字符" required>
				</div>
				<?php echo form_error('name') ?>
			</div>
			
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">简介（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="最多100个字符"><?php echo $item['description'] ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
		</fieldset>

		<fieldset>
			<legend>项目素材</legend>

			<div class=form-group>
				<label for=url_logo class="col-sm-2 control-label">LOGO（可选）</label>
				<div class=col-sm-10>
					<?php if ( !empty($item['url_logo']) ): ?>
					<figure id=project-logo class=row>
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item['url_logo'] ?>">
					</figure>
					<?php endif ?>

					<input id=url_logo class=form-control type=file>
					<input name=url_logo type=hidden value="<?php echo $item['url_logo'] ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir=project data-selector-id=url_logo data-input-name=url_logo type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
					<?php echo form_error('url_logo') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_preview class="col-sm-2 control-label">效果图（可选）</label>
				<div class=col-sm-10>
					<?php if ( !empty($item['url_preview']) ): ?>
					<figure id=project-logo class=row>
					<?php
							// 若含多项，根据分隔符拆分并轮番输出
							if (strpos( trim($item['url_preview']), ' ') !== FALSE):
								$items_array = explode(' ', $item['url_preview']);
								foreach ($items_array as $item_to_show):
					?>	
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item_to_show ?>">
					<?php
								endforeach;
							else:
					?>
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item['url_preview'] ?>">
					<?php 	endif ?>
					</figure>
					<?php endif ?>

					<input id=url_preview class=form-control type=file multiple>
					<input name=url_preview type=hidden value="<?php echo $item['url_preview'] ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir=project data-selector-id=url_preview data-input-name=url_preview type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
					<?php echo form_error('url_preview') ?>
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

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>