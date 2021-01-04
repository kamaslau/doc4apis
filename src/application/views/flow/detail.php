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
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $item['name'] ?></li>
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

	<h2><?php echo $item['name'] ?> <?php if ($item['status'] === '0') echo '<span class="btn btn-warning">草稿</span>' ?></h2>
	<p><?php echo $item['description'] ?></p>

	<?php if ( !empty($item['page_ids']) ): ?>
	<section>
		<h3>相关页面</h3>
		<p>
			<?php foreach ($pages as $page): ?>
			<a class="btn btn-default" href="<?php echo base_url('page/detail?id='.$page['page_id']) ?>" target=_blank><?php echo $page['name'] ?></a>
			<?php endforeach ?>
		</p>
	</section>
	<?php endif ?>

	<ul class="list-unstyled list-inline">
		<?php
		// 需要特定角色和权限进行该操作
		$role_allowed = array('管理员', '经理');
		$level_allowed = 30;
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-edit"></i> 编辑</a></li>
		<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-trash"></i> 删除</a></li>
		<?php endif ?>
	</ul>
</div>