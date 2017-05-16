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
	$current_level = $this->session->level; // 当前用户等级
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2><?php echo $item['name'] ?></h2>
	<p><?php echo $item['description'] ?></p>

	<?php if ( !empty($item['url_logo']) ): ?>
	<section>
		<figure id=project-logo class=row>
		<?php
				// 若含多项，根据分隔符拆分并轮番输出
				if (strpos( trim($item['url_logo']), ' ') !== FALSE):
					$items_array = explode(' ', $item['url_logo']);
					foreach ($items_array as $item_to_show):
		?>	
			<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item_to_show ?>">
		<?php
					endforeach;
				else:
		?>
			<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'project/'.$item['url_logo'] ?>">
		<?php	endif ?>
		</figure>
	</section>
	<?php endif ?>

	<?php if ( !empty($item['url_assets']) ): ?>
	<section>
		<a class="btn btn-info btn-lg" href="<?php echo $item['url_assets'] ?>" target=_blank><i class="fa fa-download" aria-hidden=true></i>素材下载</a>
	</section>
	<?php endif ?>

	<ul class="list-unstyled list-inline">
		<!--
		<li><a title="查看任务" href="<?php echo base_url('task?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-tasks" aria-hidden=true></i> 任务</a></li>
		<li><a title="查看流程" href="<?php echo base_url('flow?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-code-fork" aria-hidden=true></i> 流程</a></li>
		-->
		<li><a title="查看参数" href="<?php echo base_url('meta?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-cogs" aria-hidden=true></i> 参数</a></li>
		<li><a title="查看页面" href="<?php echo base_url('page?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-html5" aria-hidden=true></i> 页面</a></li>
		<li><a title="查看API" href="<?php echo base_url('api?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-arrows-v" aria-hidden=true></i> API</a></li>
	</ul>

	<?php
	// 需要特定角色和权限进行该操作
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<ul class="list-unstyled list-inline">
		<li><a title="创建任务" href="<?php echo base_url('task/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建任务</a></li>
		<li><a title="创建流程" href="<?php echo base_url('flow/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建流程</a></li>
		<li><a title="创建页面" href="<?php echo base_url('page/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建页面</a></li>
		<li><a title="创建API" href="<?php echo base_url('api/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建API</a></li>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-trash"></i> 删除</a></li>
	</ul>
	<?php endif ?>
</div>