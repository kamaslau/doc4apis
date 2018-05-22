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
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $item['name'] ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="far fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2>
		<?php echo $item['name'] ?>

		<?php if ($item['priority'] === '优先'): ?>
		<span class="label label-warning">优先</span>
		<?php elseif ($item['priority'] === '紧急'): ?>
		<span class="label label-danger">紧急</span>
		<?php endif ?>
	</h2>
	<p><?php echo $item['description'] ?></p>
	
	<?php if ( !empty($item['flow_ids']) ): ?>
	<section>
		<h3>相关流程</h3>
		<p>
			<?php foreach ($flows as $flow): ?>
			<a class="btn btn-default" href="<?php echo base_url('flow/detail?id='.$flow['flow_id']) ?>" target=_blank><?php echo $flow['name'] ?></a>
			<?php endforeach ?>
		</p>
	</section>
	<?php endif ?>

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

	<?php if ( !empty($item['api_ids']) ): ?>
	<section>
		<h3>相关API</h3>
		<p>
			<?php foreach ($apis as $api): ?>
			<span class="label label-default">
				<a href="<?php echo base_url('api/detail?id='.$api['api_id']) ?>" target=_blank><?php echo $api['name'] ?></a>
			</span>
			<?php endforeach ?>
		</p>
	</section>
	<?php endif ?>

	<dl class=dl-horizontal>
		<?php if ( !empty($item['team_id']) ): ?>
		<dt>指定团队</dt>
		<dd>
			<a href="<?php echo base_url('team/detail?id='.$team['team_id']) ?>" target=_blank><?php echo $team['name'] ?></a>
		</dd>
		<?php endif ?>

		<?php if ( !empty($item['user_id']) ): ?>
		<dt>指定成员</dt>
		<dd>
			<a href="<?php echo base_url('user/detail?id='.$user['user_id']) ?>" target=_blank><?php echo $user['lastname'].$user['firstname'] ?></a>
		</dd>
		<?php endif ?>

		<?php if ( !empty($item['time_start']) ): ?>
		<dt>开始日期</dt>
		<dd><?php echo date('Y-m-d H:i:s', $item['time_start']) ?></dd>
		<?php endif ?>
		
		<?php if ( !empty($item['time_due']) ): ?>
		<dt>截止日期</dt>
		<dd><?php echo date('Y-m-d H:i:s', $item['time_due']) ?></dd>
		<?php endif ?>
	</dl>

	<ul class="list-unstyled list-inline">
		<?php
		// 需要特定角色和权限进行该操作
		$role_allowed = array('管理员', '经理');
		$level_allowed = 30;
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-edit"></i> 编辑</a></li>
		<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-trash"></i> 删除</a></li>
		<?php endif ?>
	</ul>
</div>