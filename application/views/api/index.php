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
		<?php if ( isset($project) ): ?>
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<?php endif ?>
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
		<a class="btn btn-primary" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash"></i> 回收站</a>
		<a class="btn btn-default" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
	</blockquote>

	<?php else: ?>
	<li class="well well-sm dl-horizontal">
        <li><i class="fal fa-fw fa-hourglass-half"></i> 草稿/修改中</li>

		<li><i class="fal fa-fw fa-bolt"></i> 3天内添加</li>

		<li><i class="fal fa-fw fa-exclamation"></i> 3天内更新</li>
	</li>
	<table class="table table-condensed table-responsive table-striped sortable">
		<thead>
			<tr>
				<th>状态</th>
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
                    // 若为草稿状态，进行提示
                    if ($item['status'] === '0') echo '<i class="fal fa-fw fa-hourglass-half"></i>';

					$time_current = time();

					// 若创建于3天内，则提示
					if ( strtotime($item['time_create']) > ($time_current - 60*60*24*3) )
						echo '<i class="fal fa-fw fa-bolt"></i>';

					// 若修改于3天内，则提示
					if ( strtotime($item['time_edit']) > ($time_current - 60*60*24*3) )
						echo '<i class="fal fa-fw fa-exclamation"></i>';

				?>
				</td>
				<?php
					$tr = array_keys($data_to_display);
					foreach ($tr as $td)
						echo '<td>' .$item[$td]. '</td>';
				?>
				<td>
					<ul class="list-unstyled horizontal">
						<li><a href="<?php echo base_url($this->view_root.'/detail?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-eye"></i></a></li>
						<?php
						// 需要特定角色和权限进行该操作
						$role_allowed = array('管理员', '经理');
						$level_allowed = 30;
						if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
						?>
						<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-edit"></i></a></li>
						<li><a href="<?php echo base_url($this->class_name.'/duplicate?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-copy"></i></a></li>
						<li><a href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-trash"></i></a></li>
						<?php endif ?>
					</ul>
				</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>

	<?php endif ?>
</div>