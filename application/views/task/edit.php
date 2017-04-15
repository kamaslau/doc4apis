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
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('经理', '管理员');
	$level_allowed = 1;
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
			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
					<?php echo form_error('name') ?>
				</div>
			</div>
			
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="说明"><?php echo $item['description'] ?></textarea>
					<?php echo form_error('description') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=flow_ids class="col-sm-2 control-label">相关流程（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=flow_ids type=text value="<?php echo $item['flow_ids'] ?>" placeholder="相关的流程的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('flow_ids') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=api_ids class="col-sm-2 control-label">相关API（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=api_ids type=text value="<?php echo $item['api_ids'] ?>" placeholder="相关的API的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('api_ids') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=page_ids class="col-sm-2 control-label">相关页面（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=page_ids type=text value="<?php echo $item['page_ids'] ?>" placeholder="相关的其它页面的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('page_ids') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<div class=form-group>
				<label for=team_id class="col-sm-2 control-label">指定团队</label>
				<div class=col-sm-10>
					<input name=team_id type=number step=1 min=1 value="<?php echo $item['project_id'] ?>">
					<?php echo form_error('team_id') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=user_id class="col-sm-2 control-label">指定成员</label>
				<div class=col-sm-10>
					<input name=user_id type=number step=1 min=1 value="<?php echo $item['user_id'] ?>">
					<?php echo form_error('user_id') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=time_due class="col-sm-2 control-label">最迟完成时间</label>
				<div class=col-sm-10>
					<input name=time_due type=datetime value="<?php echo $item['time_due'] ?>">
					<?php echo form_error('time_due') ?>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>