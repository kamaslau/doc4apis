<style>


	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
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
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
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
							<label for=stuff_id class="col-sm-2 control-label">员工ID</label>
							<div class=col-sm-10>
								<input class=form-control name=stuff_id type=text value="<?php echo set_value('stuff_id') ?>" placeholder="员工ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=user_id class="col-sm-2 control-label">用户ID</label>
							<div class=col-sm-10>
								<input class=form-control name=user_id type=text value="<?php echo set_value('user_id') ?>" placeholder="用户ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=biz_id class="col-sm-2 control-label">所属商户ID</label>
							<div class=col-sm-10>
								<input class=form-control name=biz_id type=text value="<?php echo set_value('biz_id') ?>" placeholder="所属商户ID" required>
							</div>
						</div>
						<div class=form-group>
							<label for=password class="col-sm-2 control-label">员工操作密码</label>
							<div class=col-sm-10>
								<input class=form-control name=password type=text value="<?php echo set_value('password') ?>" placeholder="员工操作密码" required>
							</div>
						</div>
						<div class=form-group>
							<label for=role class="col-sm-2 control-label">角色</label>
							<div class=col-sm-10>
								<input class=form-control name=role type=text value="<?php echo set_value('role') ?>" placeholder="角色" required>
							</div>
						</div>
						<div class=form-group>
							<label for=level class="col-sm-2 control-label">0暂不授权，1普通员工，10门店级，20品牌级，30企业级</label>
							<div class=col-sm-10>
								<input class=form-control name=level type=text value="<?php echo set_value('level') ?>" placeholder="0暂不授权，1普通员工，10门店级，20品牌级，30企业级" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_create class="col-sm-2 control-label">创建时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_create type=text value="<?php echo set_value('time_create') ?>" placeholder="创建时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_delete class="col-sm-2 control-label">删除时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_delete type=text value="<?php echo set_value('time_delete') ?>" placeholder="删除时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=time_edit class="col-sm-2 control-label">最后编辑时间</label>
							<div class=col-sm-10>
								<input class=form-control name=time_edit type=text value="<?php echo set_value('time_edit') ?>" placeholder="最后编辑时间" required>
							</div>
						</div>
						<div class=form-group>
							<label for=creator_id class="col-sm-2 control-label">创建者user_id</label>
							<div class=col-sm-10>
								<input class=form-control name=creator_id type=text value="<?php echo set_value('creator_id') ?>" placeholder="创建者user_id" required>
							</div>
						</div>
						<div class=form-group>
							<label for=operator_id class="col-sm-2 control-label">最后操作者user_id</label>
							<div class=col-sm-10>
								<input class=form-control name=operator_id type=text value="<?php echo set_value('operator_id') ?>" placeholder="最后操作者user_id" required>
							</div>
						</div>
						<div class=form-group>
							<label for=status class="col-sm-2 control-label">状态</label>
							<div class=col-sm-10>
								<input class=form-control name=status type=text value="<?php echo set_value('status') ?>" placeholder="状态" required>
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