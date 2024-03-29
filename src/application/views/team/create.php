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
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
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
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
					<?php echo form_error('name') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="说明"><?php echo set_value('description') ?></textarea>
					<?php echo form_error('description') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=leader_id class="col-sm-2 control-label">团队负责人/队长</label>
				<div class=col-sm-10>
					<input name=leader_id type=number step=1 min=1 value="<?php echo set_value('leader_id') ?>">
					<?php echo form_error('leader_id') ?>
				</div>
			</div>
			
			<div class=form-group>
				<label for=project_ids class="col-sm-2 control-label">相关项目（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=project_ids type=text value="<?php echo set_value('project_ids') ?>" placeholder="相关的项目的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('project_ids') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=user_ids class="col-sm-2 control-label">团队成员（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=user_ids type=text value="<?php echo set_value('user_ids') ?>" placeholder="团队成员的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('user_ids') ?>
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