<style>


	/* 宽度在640像素以上的设备 */
	@media only screen and (min-width:641px)
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
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a type=button class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a type=button class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a type=button class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<legend>请填写以下信息</legend>
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
				</div>
				<?php echo form_error('name') ?>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=10 placeholder="说明"><?php echo set_value('description') ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=url class="col-sm-2 control-label">WEB URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url type=url value="<?php echo set_value('url') ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('url') ?>
			</div>
			
			<div class=form-group>
				<label for=url_ios class="col-sm-2 control-label">iOS URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_ios type=url value="<?php echo set_value('url_ios') ?>" placeholder="必须是官方下载URL；URL中除appid外不可有其它参数">
				</div>
				<?php echo form_error('url_ios') ?>
			</div>
			
			<div class=form-group>
				<label for=url_android class="col-sm-2 control-label">Android URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_android type=url value="<?php echo set_value('url_android') ?>" placeholder="必须以https://开头">
				</div>
				<?php echo form_error('url_android') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>