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
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
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
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
		<a class="btn btn-primary" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash?project_id='.$project['project_id']) ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php if ( empty($items) ): ?>
	<blockquote>
		<p>没有任何<?php echo $this->class_name_cn ?>曾经被删除。</p>
	</blockquote>

	<?php else: ?>
	<form method=post target=_blank>
		<div class=form-group>
			<?php
			$ids_value = '';
			for ($i=0; $i<count($ids); $i++):
				$ids_value .= $ids[$i][$this->id_name];
				if ($i < (count($ids) - 1)) $ids_value .= '|';
			endfor;
			?>
			<input name="ids[]" type=checkbox value="<?php echo $ids_value ?>">
			<label>全选</label>
		</div>
		<div class=btn-group role=group>
			<button formaction="<?php echo base_url($this->class_name.'/restore') ?>" type=submit class="btn btn-default">恢复</button>
		</div>

		<table class="table table-condensed table-hover table-responsive table-striped sortable">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th><?php echo $this->class_name_cn ?>ID</th>
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
						<input name="ids[]" type=checkbox value="<?php echo $item[$this->id_name] ?>">
					</td>
					<td><?php echo $item[$this->id_name] ?></td>
					<?php
						$tr = array_keys($data_to_display);
						foreach ($tr as $td):
							echo '<td>' .$item[$td]. '</td>';
						endforeach;
					?>
					<td>
						<ul class="list-unstyled list-inline">
							<li><a title="查看" href="<?php echo base_url($this->view_root.'/detail?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-eye"></i></a></li>
							<?php
							// 需要特定角色和权限进行该操作
							$role_allowed = array('管理员', '经理');
							$level_allowed = 30;
							if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
							?>
							<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-edit"></i></a></li>
							<li><a title="恢复" href="<?php echo base_url($this->class_name.'/restore?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fal fa-level-up"></i></a></li>
							<?php endif ?>
						</ul>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	
	</form>
	<?php endif ?>
</div>