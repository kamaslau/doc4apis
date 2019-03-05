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
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('经理', '管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
	<?php if ($this->session->role === '管理员' || $this->session->role === '经理'): ?>
		<fieldset>
			<legend>基本信息</legend>

			<?php if ( !empty($item['biz_id']) ): ?>
			<div class=form-group>
				<label for=biz_id class="col-sm-2 control-label">所属企业</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $biz['brief_name'] ?></p>
				</div>
			</div>
			<?php endif ?>

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
					<select class=form-control name=role required>
						<?php
							$input_name = 'role';
							$option_list = array(
								'成员', '经理', '设计师', '工程师',
							);
							if ($this->session->role === '管理员') $option_list[] = '管理员';
							foreach ($option_list as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($item[$input_name] === $option) echo 'selected' ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>
					<?php echo form_error('role') ?>
				</div>
			</div>
			
			<?php
				// 不可授予他人比自己高的等级
				if ($this->session->user_id !== $this->input->get_post('id')):
					$max_level = $this->session->level - 1;
				else:
					$max_level = $this->session->level;
				endif;
			?>
			<div class=form-group>
				<label for=level class="col-sm-2 control-label">等级</label>
				<div class=col-sm-10>
					<input class=form-control name=level type=number min=0 step=1 max="<?php echo $max_level ?>" value="<?php echo $item['level'] ?>" placeholder="等级" required>
					<?php echo form_error('level') ?>
				</div>
			</div>
		</fieldset>
	<?php endif ?>

		<fieldset>
			<legend>资料</legend>

			<div class=form-group>
				<label for=nickname class="col-sm-2 control-label">昵称</label>
				<div class=col-sm-10>
					<input class=form-control name=nickname type=text value="<?php echo $item['nickname'] ?>" placeholder="最多10个字符">
					<?php echo form_error('nickname') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=gender class="col-sm-2 control-label">性别</label>
				<div class=col-sm-10>
					<label class=radio-inline>
						<input type=radio name=gender value="女" required <?php if ($item['gender'] === '女') echo 'checked' ?>> <i class="fal fa-venus"></i> 女
					</label>
					<label class=radio-inline>
						<input type=radio name=gender value="男" required <?php if ($item['gender'] === '男') echo 'checked' ?>> <i class="fal fa-mars"></i> 男
					</label>

                    <p class=help-block>以自我认同为准</p>
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
				<label for=dob class="col-sm-2 control-label">生日（公历，可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=dob type=date value="<?php echo $item['dob'] ?>" placeholder="例如：<?php echo date('Y-m-d', strtotime("-18 years")) ?>">
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
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>