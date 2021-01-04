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
				var input_current = $.trim(input_origin) + ' ' + value_to_append; // 清除多余空格
				input.val( $.trim(input_current) ); // 清除原选项值为空时的多余空格
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
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'?project_id='.$item['project_id']) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash?project_id='.$item['project_id']) ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$item['project_id']) ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label class="col-sm-2 control-label">所属项目</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $project['name'] ?></p>
				</div>
			</div>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="名称" required>
				</div>
				<?php echo form_error('name') ?>
			</div>
			
			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=5 placeholder="说明" required><?php echo $item['description'] ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>

			<div class=form-group>
				<label for=page_ids class="col-sm-2 control-label">相关页面（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>可多选</code>
					<input class=form-control name=page_ids type=text data-list=pages value="<?php echo $item['page_ids'] ?>" placeholder="与当前页面有关的其它页面的ID们，多个ID间用一个空格分隔">
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
				<label for=status class="col-sm-2 control-label">状态</label>
				<div class=col-sm-10>
					<label class=radio-inline>
						<input type=radio name=status value="1" required <?php if ($item['status'] === '1') echo 'checked'; ?>> 正常
					</label>
					<label class=radio-inline>
						<input type=radio name=status value="0" required <?php if ($item['status'] === '0') echo 'checked'; ?>> 草稿
					</label>
					<?php echo form_error('status') ?>
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