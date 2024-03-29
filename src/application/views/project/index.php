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
	<ol class="breadcrumb container-fluid-fluid">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
	</ol>
</div>

<div id=content class="container-fluid">
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

  <h2>项目</h2>
  <div>
    <p>这里列出了所有你可以查看的项目。每个项目都可以设置参数，发布流程、页面，及API。</p>
  </div>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>没有目前可以查看的项目。</p>
	</blockquote>

	<?php else: ?>
	<ul id=projects class="list-unstyled list-inline">
		<?php
      foreach ($items as $item):
        $item_id = $item[$this->id_name]
    ?>
			<li class="col-sm-6 col-lg-4 col-xl-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">
							<a title="查看<?php echo $item['name'] ?>" href="<?php echo base_url($this->view_root.'/detail?id='.$item_id) ?>" target=_blank><?php echo $item['name'] ?></a>
						</h2>
						
						<?php
						// 需要特定角色和权限进行该操作
						$role_allowed = array('管理员');
						$level_allowed = 30;
						if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
						?>
						<ul class="actions list-unstyled list-inline">
							<li><a href="<?php echo base_url($this->view_root.'/detail?id='.$item_id) ?>" target=_blank><i class="fal fa-eye"></i></a></li>
              <li><a title="查看参数" href="<?php echo base_url('meta/detail?project_id='.$item_id) ?>" target=_blank><i class="fal fa-cogs"></i></a></li>
							<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item_id) ?>" target=_blank><i class="fal fa-edit"></i></a></li>
						</ul>
						<?php endif ?>
					</div>

					<div class="panel-body">
				    	<p><?php echo $item['description'] ?></p>
					</div>

					<div class="panel-footer">
						<ul class="row actions list-unstyled list-inline">
							<li class="col-xs-4"><a href="<?php echo base_url('flow?project_id='.$item_id) ?>" target=_blank><i class="fal fa-fw fa-code-branch"></i> 流程</a></li>
							<li class="col-xs-4"><a href="<?php echo base_url('page?project_id='.$item_id) ?>" target=_blank><i class="fab fa-fw fa-html5"></i> 页面</a></li>
							<li class="col-xs-4"><a href="<?php echo base_url('api?project_id='.$item_id) ?>" target=_blank><i class="fal fa-fw fa-plug"></i> API</a></li>
							<?php
							// 需要特定角色和权限进行该操作
							$role_allowed = array('管理员', '经理');
							$level_allowed = 30;
							if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
							?>
							<!--<li class=col-xs-3><a href="<?php echo base_url('task/create?project_id='.$item_id) ?>" target=_blank><i class="fal fa-plus-square"></i> 创建任务</a></li>-->
							<li class="col-xs-4"><a href="<?php echo base_url('flow/create?project_id='.$item_id) ?>" target=_blank><i class="fal fa-plus-square"></i> 添加流程</a></li>
							<li class="col-xs-4"><a href="<?php echo base_url('page/create?project_id='.$item_id) ?>" target=_blank><i class="fal fa-plus-square"></i> 添加页面</a></li>
							<li class="col-xs-4"><a href="<?php echo base_url('api/create?project_id='.$item_id) ?>" target=_blank><i class="fal fa-plus-square"></i> 添加API</a></li>
							<?php endif ?>
						</ul>
					</div>
				</div>
			</li>
		<?php endforeach ?>

	<?php endif ?>
</div>
