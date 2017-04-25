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

	<p>
		<?php if ( !empty($item['sdk_ios']) ): ?>
		<span><i class="fa fa-apple" aria-hidden="true"></i> ≥<?php echo $item['sdk_ios'] ?></span>
		<?php endif ?>

		<?php if ( !empty($item['sdk_android']) ): ?>
		<span><i class="fa fa-android" aria-hidden="true"></i> ≥<?php echo $item['sdk_android'] ?></span>
		<?php endif ?>
	</p>

	<?php if ( !empty($item['url_logo']) ): ?>
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
	<?php 	endif ?>
	</figure>
	<?php endif ?>

	<?php if ( !empty($item['url_assets']) ): ?>
	<a href="<?php echo $item['url_assets'] ?>" target=_blank><i class="fa fa-download" aria-hidden=true></i>下载素材</a>
	<?php endif ?>

	<section>
		<h3>开发环境</h3>
		<dl class=dl-horizontal>
			<?php if ( !empty($item['sandbox_url_web']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> WEB</dt>
			<dd id=sandbox_url_web>
				<?php echo $item['sandbox_url_web'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#sandbox_url_web').qrcode("<?php echo $item['sandbox_url_web'] ?>");
				</script>
			</dd>
			<?php endif ?>
		
			<?php if ( !empty($item['sandbox_url_api']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> API</dt>
			<dd><?php echo $item['sandbox_url_api'] ?></dd>
			<?php endif ?>
		</dl>
	</section>

	<section>
		<h3>正式环境</h3>
		<dl class=dl-horizontal>
			<?php if ( !empty($item['url']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> WEB</dt>
			<dd id=url_web>
				<?php echo $item['url'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_web').qrcode("<?php echo $item['url'] ?>");
				</script>
			</dd>
			<?php endif ?>

			<?php if ( !empty($item['url_api']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> API</dt>
			<dd><?php echo $item['url_api'] ?></dd>
			<?php endif ?>

			<?php if ( !empty($item['url_ios']) ): ?>
			<dt><i class="fa fa-apple" aria-hidden="true"></i> iOS</dt>
			<dd id=url_ios>
				<?php echo $item['url_ios'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_ios').qrcode("<?php echo $item['url_ios'] ?>");
				</script>
			</dd>
			<?php endif ?>
		
			<?php if ( !empty($item['url_android']) ): ?>
			<dt><i class="fa fa-android" aria-hidden="true"></i> Android</dt>
			<dd id=url_android>
				<?php echo $item['url_android'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_android').qrcode("<?php echo $item['url_android'] ?>");
				</script>
			</dd>
			<?php endif ?>
		</dl>
	</section>

	<ul class="list-unstyled list-inline">
		<li><a title="查看任务" href="<?php echo base_url('task?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-tasks" aria-hidden=true></i> 任务</a></li>
		<li><a title="查看流程" href="<?php echo base_url('flow?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-code-fork" aria-hidden=true></i> 流程</a></li>
		<li><a title="查看页面" href="<?php echo base_url('page?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-html5" aria-hidden=true></i> 页面</a></li>
		<li><a title="查看API" href="<?php echo base_url('api?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-arrows-v" aria-hidden=true></i> API</a></li>
		<?php
		// 需要特定角色和权限进行该操作
		$role_allowed = array('管理员');
		$level_allowed = 30;
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="创建任务" href="<?php echo base_url('task/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建任务</a></li>
		<li><a title="创建流程" href="<?php echo base_url('flow/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建流程</a></li>
		<li><a title="创建页面" href="<?php echo base_url('page/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建页面</a></li>
		<li><a title="创建API" href="<?php echo base_url('api/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建API</a></li>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-trash"></i> 删除</a></li>
		<?php endif ?>
	</ul>
</div>