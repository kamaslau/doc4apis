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
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li class=active><?php echo $this->class_name_cn ?></li>
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
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
	<dl class="well well-sm dl-horizontal">
		<dt><i class="far fa-fw fa-bolt"></i></dt>
		<dd>3天内新添加的API</dd>
		<dt><i class="far fa-fw fa-exclamation"></i></dt>
		<dd>3天内有更新的API</dd>
	</dl>
	<table class="table table-condensed table-responsive table-striped sortable">
		<thead>
			<tr>
				<th>动态</th>
				<?php
					$thead = array_values($data_to_display);
					foreach ($thead as $th):
						echo '<th>' .$th. '</th>';
					endforeach;
				?>
				<th>操作</th>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td>
				<?php
					$time_current = time();

					// 若创建于3天内，则提示是新的API
					if ( strtotime($item['time_create']) > ($time_current - 60*60*24*3) )
						echo '<i class="far fa-fw fa-bolt"></i>';

					// 若修改于3天内，则提示是有更新的API
					if ( strtotime($item['time_edit']) > ($time_current - 60*60*24*3) )
						echo '<i class="far fa-fw fa-exclamation"></i>';

					// 若为草稿状态，进行提示
					if ($item['status'] === '0') echo ' 草稿<i class="far fa-fw fa-spinner fa-pulse"></i>';
				?>
				</td>
				<?php
					$tr = array_keys($data_to_display);
					foreach ($tr as $td)
						echo '<td>' .$item[$td]. '</td>';
				?>
				<td>
					<ul class="list-unstyled horizontal">
						<li><a title="查看" href="<?php echo base_url($this->view_root.'/detail?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-eye"></i> 查看</a></li>
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
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>

	<?php endif ?>
</div>