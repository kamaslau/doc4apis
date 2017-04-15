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
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash'] ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create'] ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=mobile class="col-sm-2 control-label">手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=mobile type=tel size=11 pattern="\d{11}" value="<?php echo $item['mobile'] ?>" placeholder="手机号" required>
					<?php echo form_error('mobile') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=lastname class="col-sm-2 control-label">姓</label>
				<div class=col-sm-10>
					<input class=form-control name=lastname type=text value="<?php echo $item['lastname'] ?>" placeholder="姓氏" required>
					<?php echo form_error('lastname') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=firstname class="col-sm-2 control-label">名</label>
				<div class=col-sm-10>
					<input class=form-control name=firstname type=text value="<?php echo $item['firstname'] ?>" placeholder="名" required>
					<?php echo form_error('firstname') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>权限</legend>

			<div class=form-group>
				<label for=role class="col-sm-2 control-label">角色</label>
				<div class=col-sm-10>
					<input class=form-control name=role type=text value="<?php echo $item['role'] ?>" placeholder="角色" required>
					<?php echo form_error('role') ?>
				</div>
			</div>
			
			<div class=form-group>
				<label for=level class="col-sm-2 control-label">等级</label>
				<div class=col-sm-10>
					<input class=form-control name=level type=number min=0 step=1 max="<?php echo $this->session->role ?>" value="<?php echo $item['level'] ?>" placeholder="等级" required>
					<?php echo form_error('level') ?>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>资料</legend>

			<div class=form-group>
				<label for=gender class="col-sm-2 control-label">性别</label>
				<div class=col-sm-10>
					<select name=gender required>
						<option>请选择</option>
						<option value="女" <?php echo set_select('gender', '女') ?>>女</option>
						<option value="男" <?php echo set_select('gender', '男') ?>>男</option>
					</select>
					<?php echo form_error('gender') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=avatar class="col-sm-2 control-label">头像（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=avatar type=url value="<?php echo $item['avatar'] ?>" placeholder="头像URL">
					<?php echo form_error('avatar') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=dob class="col-sm-2 control-label">生日（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=dob type=date value="<?php echo $item['dob'] ?>" placeholder="格式为YYYY-MM-DD，例如：1989-07-28">
					<?php echo form_error('dob') ?>
				</div>
			</div>
			
			<div class=form-group>
				<label for=email class="col-sm-2 control-label">Email（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=email type=email value="<?php echo $item['email'] ?>" placeholder="Email">
					<?php echo form_error('email') ?>
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