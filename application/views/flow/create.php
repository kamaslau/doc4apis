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

<script>
	$(function(){
		$('select>option').click(function(){
			var value_to_append = $(this).val(); // 获取所选项值
			var list_name = $(this).parent('select').attr('id');
			var input = $('[data-list='+ list_name +']');
			var input_origin = ' ' + input.val() + ' '; // 获取当前值，并在前后追加各一个空格，为后续操作进行准备

			// 若所选项值在当前值中不存在，则追加所选项值到当前值末尾，并去掉前后空格
			if (input_origin.indexOf(value_to_append) == -1)
			{
				var input_current = $.trim(input_origin + ' ' + value_to_append);
				input.val(input_current);
			}
			else
			{
				alert('该项已被添加过');
			}

			return false;
		});
	});
</script>

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>
			
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
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="名称" required>
				</div>
				<?php echo form_error('name') ?>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="说明" required><?php echo set_value('description') ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
		</fieldset>

		<div class=form-group>
			<label for=page_ids class="col-sm-2 control-label">相关页面（可选）</label>
			<div class=col-sm-10>
				<code class=help-block>可多选</code>
				<input class=form-control name=page_ids type=text data-list=pages value="<?php echo set_value('page_ids') ?>" placeholder="与当前页面有关的其它页面的ID们，多个ID间用一个空格分隔">
				<select id=pages>
					<option>请选择</option>
					<?php foreach($pages as $page): ?>
					<option value="<?php echo $page['page_id'] ?>"><?php echo $page['code'].' '.$page['name'] ?></option>
					<?php endforeach ?>
				</select>
				<?php echo form_error('page_ids') ?>
			</div>
		</div>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>