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
			<legend>基本信息</legend>

			<?php if ( !empty($biz) ): ?>
			<div class=form-group>
				<label for=biz_id class="col-sm-2 control-label">所属企业</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $biz['brief_name'] ?></p>
					<input name=biz_id type=hidden value="<?php echo $biz['biz_id'] ?>">
				</div>
			</div>
			<?php endif ?>

			<div class=form-group>
				<label for=mobile class="col-sm-2 control-label">手机号</label>
				<div class=col-sm-10>
					<input class=form-control name=mobile type=tel size=11 pattern="\d{11}" value="<?php echo set_value('mobile') ?>" placeholder="手机号" required>
					<?php echo form_error('mobile') ?>

                    <p class=help-block>新建的成员可使用上述手机号和初始密码（该手机号的最后6位）登录（后附网址），登录后首页会提示修改初始密码。</p>
				</div>
			</div>

			<div class=form-group>
				<label for=lastname class="col-sm-2 control-label">姓</label>
				<div class=col-sm-10>
					<input class=form-control name=lastname type=text value="<?php echo set_value('lastname') ?>" placeholder="姓氏" required>
					<?php echo form_error('lastname') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=firstname class="col-sm-2 control-label">名</label>
				<div class=col-sm-10>
					<input class=form-control name=firstname type=text value="<?php echo set_value('firstname') ?>" placeholder="名" required>
					<?php echo form_error('firstname') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>权限</legend>

			<div class=form-group>
				<label for=role class="col-sm-2 control-label">角色</label>
				<div class=col-sm-10>
					<select class=form-control name=role required>
						<?php
							$input_name = 'role';
							$option_list = array(
								'成员', '经理', '设计师', '工程师',
							);
							if ($this->session->role === '管理员') $option_list[] = '管理员';
							foreach ($option_list as $option):
						?>
						<option value="<?php echo $option ?>" <?php echo set_select($input_name, $option) ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>
					<?php echo form_error('role') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=level class="col-sm-2 control-label">等级</label>
				<div class=col-sm-10>
					<input class=form-control name=level type=number min=0 step=1 max="<?php echo $this->session->level ?>" value="<?php echo empty(set_value('level'))? 10: set_value('level') ?>" placeholder="等级" required>
					<?php echo form_error('level') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>资料</legend>

			<div class=form-group>
				<label for=gender class="col-sm-2 control-label">性别（以自我认同为准）</label>
				<div class=col-sm-10>
					<label class=radio-inline>
						<input type=radio name=gender value="女" required <?php echo set_radio('gender', '女', TRUE) ?>> <i class="fal fa-venus"></i> 女
					</label>
					<label class=radio-inline>
						<input type=radio name=gender value="男" required <?php echo set_radio('gender', '男') ?>> <i class="fal fa-mars"></i> 男
					</label>

                    <p class=help-block>以自我认同为准</p>
					<?php echo form_error('gender') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=email class="col-sm-2 control-label">Email（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=email type=email value="<?php echo set_value('email') ?>" placeholder="Email">
					<?php echo form_error('email') ?>
				</div>
			</div>
		</fieldset>

		<div class="well well-sm text-center">
			<p>登录网址（登录后首页会提示修改初始密码）</p>
			<p><strong><?php echo base_url('login') ?></strong></p>
		</div>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>