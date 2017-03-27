<style>

	/* 宽度在640像素以上的设备 */
	@media only screen and (min-width:641px)
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
	$role_allowed = array('管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a type=button class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a type=button class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a type=button class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2><?php echo $item['name'] ?></h2>
	<p><?php echo $item['description'] ?></p>
	
	<dl class=dl-horizontal>
		<?php if ( !empty($item['url']) ): ?>
		<dt><i class="fa fa-safari" aria-hidden="true"></i> WEB</dt>
		<dd id=url_web>
			<?php echo $item['url'] ?>
			<script>
				jQuery('<div class=qrcode>').appendTo('#url_web').qrcode("<?php echo $item['url'] ?>");
			</script>
		</dd>
		<?php endif ?>
		
		<?php if ( !empty($item['url_ios']) ): ?>
		<dt><i class="fa fa-apple" aria-hidden="true"></i> iOS</dt>
		<dd id=url_ios>
			<?php echo $item['url_ios'] ?>
			<script>
				jQuery('<div class=qrcode>').appendTo('#url_ios').qrcode("<?php echo $item['url_ios'] ?>");
			</script>
		</dd>
		<?php endif ?>
		
		<?php if ( !empty($item['url_android']) ): ?>
		<dt><i class="fa fa-android" aria-hidden="true"></i> Android</dt>
		<dd id=url_android>
			<?php echo $item['url_android'] ?>
			<script>
				jQuery('<div class=qrcode>').appendTo('#url_android').qrcode("<?php echo $item['url_android'] ?>");
			</script>
		</dd>
		<?php endif ?>
	</dl>
	
	<ul class=horizontal>
		<li><a title="查看页面" href="<?php echo base_url('page?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-eye"></i> 查看页面</a></li>
		<li><a title="创建页面" href="<?php echo base_url('page/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建页面</a></li>
		<li><a title="查看API" href="<?php echo base_url('api?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-eye"></i> 查看API</a></li>
		<li><a title="创建API" href="<?php echo base_url('api/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-plus-square"></i> 创建API</a></li>
	</ul>
</div>