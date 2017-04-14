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
				</div>
				<?php echo form_error('name') ?>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=10 placeholder="说明" required><?php echo $item['description'] ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>
			
			<div class=form-group>
				<label for=private class="col-sm-2 control-label">是否需登录</label>
				<div class=col-sm-10>
					<select class=form-control name=private required>
						<option value="1" <?php if ($item['private'] === '1') echo 'selected'; ?>>是</option>
						<option value="0" <?php if ($item['private'] === '0') echo 'selected'; ?>>否</option>
					</select>
				</div>
				<?php echo form_error('private') ?>
			</div>

			<div class=form-group>
				<label for=elements class="col-sm-2 control-label">视图元素（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=elements rows=10 placeholder="视图元素"><?php echo $item['elements'] ?></textarea>
				</div>
				<?php echo form_error('elements') ?>
			</div>
			
			<div class=form-group>
				<label for=url_design class="col-sm-2 control-label">设计图URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_design type=file value="<?php echo $item['url_design'] ?>" placeholder="请上传jpg/webp格式设计图，文件大小控制在2M之内">
				</div>
				<?php echo form_error('url_design') ?>
			</div>
			
			<div class=form-group>
				<label for=url_assets class="col-sm-2 control-label">美术素材URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_assets type=text value="<?php echo $item['url_assets'] ?>" placeholder="请将UI素材、字体、媒体文件等压缩后上传到百度云盘，并将该压缩文件的分享链接填入此处">
				</div>
				<?php echo form_error('url_assets') ?>
			</div>

			<div class=form-group>
				<label for=onloads class="col-sm-2 control-label">载入事件（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=onloads rows=10 placeholder="载入事件"><?php echo $item['onloads'] ?></textarea>
				</div>
				<?php echo form_error('onloads') ?>
			</div>
			
			<div class=form-group>
				<label for=events class="col-sm-2 control-label">业务流程（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=events rows=10 placeholder="业务流程"><?php echo $item['events'] ?></textarea>
				</div>
				<?php echo form_error('events') ?>
			</div>

			<div class=form-group>
				<label for=api_ids class="col-sm-2 control-label">相关API（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=api_ids type=text value="<?php echo $item['api_ids'] ?>" placeholder="与当前页面有关的API的ID们，多个ID间用一个空格分隔">
				</div>
				<?php echo form_error('api_ids') ?>
			</div>
			
			<div class=form-group>
				<label for=page_ids class="col-sm-2 control-label">相关页面（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=page_ids type=text value="<?php echo $item['page_ids'] ?>" placeholder="与当前页面有关的其它页面的ID们，多个ID间用一个空格分隔">
				</div>
				<?php echo form_error('page_ids') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-primary" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>