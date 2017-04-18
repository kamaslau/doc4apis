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

	<?php $name = $item['lastname'].$item['firstname'] ?>
	<h2>
		<?php echo $name ?>
		<?php echo !empty($item['nickname'])? '('.$item['nickname'].')': NULL; ?>
	</h2>
	<h3><?php echo $item['mobile'] ?></h3>

	<dl class=dl-horizontal>
		<?php if ( !empty($item['role']) ): ?>
		<dt>角色</dt>
		<dd><?php echo $item['role'] ?></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['level']) ): ?>
		<dt>权限</dt>
		<dd><?php echo $item['level'] ?></dd>
		<?php endif ?>
	</dl>

	<dl class=dl-horizontal>
		<?php if ( !empty($item['avatar']) ): ?>
		<dt>头像</dt>
		<dd>
			<img alt="<?php echo $name.'的头像' ?>" src="<?php echo $item['avatar'] ?>">
		</dd>
		<?php endif ?>

		<?php if ( !empty($item['gender']) ): ?>
		<dt>性别</dt>
		<dd><?php echo $item['gender'] ?></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['dob']) ): ?>
		<dt>生日（公历）</dt>
		<dd><?php echo $item['dob'] ?></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['email']) ): ?>
		<dt>Email</dt>
		<dd><?php echo $item['email'] ?></dd>
		<?php endif ?>
	</dl>

	<ul class="list-unstyled list-inline">
		<?php
		// 需要特定角色和权限进行该操作
		$role_allowed = array('经理', '管理员');
		$level_allowed = 1;
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-trash"></i> 删除</a></li>
		<?php endif ?>
	</ul>
</div>