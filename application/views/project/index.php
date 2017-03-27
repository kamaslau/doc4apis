<style>
	#projects {overflow:hidden;}

	/* 宽度在640像素以上的设备 */
	@media only screen and (min-width:641px)
	{
		#projects>li {width:50%;}
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
		<li class=active><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a type=button class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a type=button class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a type=button class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
	<ul id=projects class=horizontal>

		<?php foreach ($items as $item): ?>
			<li>
				<div class=meta>
					<h2><?php echo $item['name'] ?></h2>
					<p><?php echo $item['description'] ?></p>
				</div>
				<ul class="actions horizontal">
					<li><a title="查看" href="<?php echo base_url($this->view_root.'/detail?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-eye"></i> 查看</a></li>
					<li><a title="查看页面" href="<?php echo base_url('page?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-eye"></i> 查看页面</a></li>
					<li><a title="创建页面" href="<?php echo base_url('page/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建页面</a></li>
					<li><a title="查看API" href="<?php echo base_url('api?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-eye"></i> 查看API</a></li>
					<li><a title="创建API" href="<?php echo base_url('api/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建API</a></li>
					<?php
					// 需要特定角色和权限进行该操作
					$role_allowed = array('管理员');
					$level_allowed = 1;
					if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
					?>
					<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
					<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-trash"></i> 删除</a></li>
					<?php endif ?>
				</ul>
			</li>
		<?php endforeach ?>

	<?php endif ?>
</div>