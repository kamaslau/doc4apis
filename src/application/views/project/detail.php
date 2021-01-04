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
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class="container-fluid">
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户等级
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2><?php echo $item['name'] ?></h2>

	<dl class=list-horizontal>
		<?php if ( !empty($item['biz_id']) ): ?>
		<dt>所属企业</dt>
		<dd>
			<?php echo $biz['brief_name'] ?>
			<a class="btn btn-info btn-sm" href="<?php echo base_url('biz/detail?id='.$biz['biz_id']) ?>" target=_blank>查看</a>
		</dd>
		<?php endif ?>

		<dt>简介</dt>
		<dd><?php echo $item['description'] ?></dd>
	</dl>

	<?php if ( !empty($item['url_logo']) ): ?>
	<section>
		<figure id=url-logo class=row>
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
		<a class="btn btn-info btn-lg" href="<?php echo $item['url_assets'] ?>" target=_blank><i class="fal fa-download"></i>素材下载</a>
	</section>
	<?php endif ?>

	<ul class="list-unstyled list-inline">
		<li><a href="<?php echo base_url('meta/detail?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-cogs"></i> 技术参数</a></li>
		<!--
		<li><a href="<?php echo base_url('task?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-tasks"></i> 任务</a></li>
		-->
		<li><a href="<?php echo base_url('flow?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-code-branch"></i> 流程</a></li>
		<li><a href="<?php echo base_url('page?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fab fa-html5"></i> 页面</a></li>
		<li><a href="<?php echo base_url('api?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-plug"></i> API</a></li>
	</ul>

	<?php
	// 需要特定角色和权限进行该操作
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<ul class="list-unstyled list-inline">
		<li><a href="<?php echo base_url('task/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-plus-square"></i> 创建任务</a></li>
		<li><a href="<?php echo base_url('flow/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-plus-square"></i> 创建流程</a></li>
		<li><a href="<?php echo base_url('page/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-plus-square"></i> 创建页面</a></li>
		<li><a href="<?php echo base_url('api/create?project_id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-plus-square"></i> 创建API</a></li>
		<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-edit"></i> 编辑</a></li>
		<li><a href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-trash"></i> 删除</a></li>
	</ul>
	<?php endif ?>
</div>