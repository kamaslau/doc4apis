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
		<?php if ( isset($project) ): ?>
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<?php endif ?>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.@$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
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
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
    <a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.@$project['project_id']) ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2><?php echo $item['code'] ?> <?php echo $item['name'] ?> <?php if ($item['status'] === '0') echo '<span class="btn btn-warning">草稿</span>' ?></h2>
  <?php
    // 从API路径数据中摘取代码文件名（不含后缀名部分），并生成下载代码按钮
    $file_name = substr( $item['url'], 0, strpos($item['url'], '/') )
  ?>
  <a class="btn btn-block btn-primary" href="<?php echo base_url( $this->class_name.'/download/'. $file_name ) ?>" target="_blank">下载代码文件</a>
  <p><?php echo $item['description'] ?></p>

	<dl class=dl-horizontal>
		<dt>URL</dt>
		<?php if ( empty($item['url_full']) ): ?>
		<dd><?php echo $item['url'] ?></dd>
		<?php else: ?>
		<dd><?php echo $item['url_full'] ?></dd>
		<?php endif ?>
	</dl>

	<section>
		<h3>请求参数（除公共参数外）</h3>

		<?php if ( !empty($item['params_request']) ): ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>是否必要</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $item['params_request'] ?>
			</tbody>
		</table>
		<?php else: ?>
		<p>除公共参数外，无其它参数</p>
		<?php endif ?>
	</section>

	<section>
		<h3>响应参数（即返回值中content的内容）</h3>

		<?php if ( !empty($item['params_respond']) ): ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $item['params_respond'] ?>
			</tbody>
		</table>
		<?php else: ?>
		<p>除公共参数外，无其它参数</p>
		<?php endif ?>
	</section>

	<?php if ( !empty($item['sample_request']) ): ?>
	<section>
		<h3>请求示例</h3>
		<pre><?php echo $item['sample_request'] ?></pre>
	</section>
	<?php endif ?>

	<?php if ( !empty($item['sample_respond']) ): ?>
	<section>
		<h3>返回示例</h3>
		<pre><?php echo $item['sample_respond'] ?></pre>
	</section>
	<?php endif ?>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

		<?php if ( ! empty($item['time_delete'])): ?>
		<dt>删除时间</dt>
		<dd>
			<?php echo $item['time_delete'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看删除者</a>
		</dd>
		<?php endif ?>

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('user/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
	
	<?php
	// 需要特定角色和权限进行该操作
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<ul class="list-unstyled horizontal">
		<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-edit"></i> 编辑</a></li>
		<li><a href="<?php echo base_url($this->class_name.'/duplicate?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-copy"></i> 克隆</a></li>
	</ul>
	<?php endif ?>
</div>