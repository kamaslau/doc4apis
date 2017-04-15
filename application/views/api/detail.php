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
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('经理', '管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2><?php echo $item['name'] ?> <span>[<?php echo $item['code'] ?>]</span></h2>
	<p><?php echo $item['description'] ?></p>
	
	<dl class=list-horizontal>
		<dt>生产环境URL</dt>
		<?php if ( empty($item['url_full']) ): ?>
		<dd><?php echo $project['url_api']. $item['url'] ?></dd>
		<?php else: ?>
		<dd><?php echo $item['url_full'] ?></dd>
		<?php endif ?>

		<dt>沙盒环境URL</dt>
		<?php if ( empty($item['url_full']) ): ?>
		<dd><?php echo $project['sandbox_url_api']. $item['url'] ?></dd>
		<?php else: ?>
		<dd><?php echo $item['url_full'] ?></dd>
		<?php endif ?>
	</dl>
	
	<section>
		<h3>公共参数</h3>
		<table>
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</section>
	
	<section>
		<h3>请求参数</h3>
		<table>
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>是否必要</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</section>
	
	<section>
		<h3>响应参数</h3>
		<table>
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</section>
	
	<?php if ( !empty($item['request_sample']) ): ?>
	<section>
		<h3>请求示例</h3>
	</section>
	<?php endif ?>
	
	<?php if ( !empty($item['respond_sample']) ): ?>
	<section>
		<h3>响应示例</h3>
	</section>
	<?php endif ?>
	
	<?php if ( !empty($item['faq']) ): ?>
	<section>
		<h3>FAQ</h3>
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

		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
	</dl>
</div>