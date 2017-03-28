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
				<label for=project_id class="col-sm-2 control-label">所属项目</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $project['name'] ?></p>
					<input name=project_id type=hidden value="<?php echo $project['project_id'] ?>">
				</div>
			</div>
			
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="例如：商家详情" required>
				</div>
				<?php echo form_error('name') ?>
			</div>
			
			<div class=form-group>
				<label for=code class="col-sm-2 control-label">序号</label>
				<div class=col-sm-10>
					<input class=form-control name=code type=text value="<?php echo set_value('code') ?>" placeholder="例如：BIZ1" required>
				</div>
				<?php echo form_error('code') ?>
			</div>
			
			<div class=form-group>
				<label for=url class="col-sm-2 control-label">URL</label>
				<div class=col-sm-10>
					<input class=form-control name=url type=text value="<?php echo set_value('url') ?>" placeholder="除API服务器根URL之外的路径，例如：biz/detail" required>
				</div>
				<?php echo form_error('url') ?>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=10 placeholder="说明"><?php echo set_value('description') ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
			
			<div class=form-group>
				<label for=request_sample class="col-sm-2 control-label">请求示例（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=request_sample rows=10 placeholder="请求示例"><?php echo set_value('request_sample') ?></textarea>
				</div>
				<?php echo form_error('request_sample') ?>
			</div>
			
			<div class=form-group>
				<label for=respond_sample class="col-sm-2 control-label">返回示例（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=respond_sample rows=10 placeholder="返回示例"><?php echo set_value('respond_sample') ?></textarea>
				</div>
				<?php echo form_error('respond_sample') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>