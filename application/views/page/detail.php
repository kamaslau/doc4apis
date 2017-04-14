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
	<h2><?php echo $item['name'] ?></h2>
	<em><?php echo ($item['private'] === '1')? '需登录': '不需登录'; ?></em>
	<p><?php echo $item['description'] ?></p>
	
	<section>
		<h3>视图元素</h3>
		<?php echo $item['elements'] ?>
		<ul>
			<?php if ( !empty($item['url_design_image']) ): ?>
			<li>
				设计稿 <a id=design-show title="显示设计稿" href="<?php echo $item['url_design_image'] ?>" target=_blank><i class="fa fa-eye" aria-hidden=true></i> 显示</a>
				<figure id=design-image></figure>
			</li>
			<?php endif ?>
			<?php if ( !empty($item['url_design_assets']) ): ?>
			<li>
				设计附件 <a title="下载设计附件" href="<?php echo $item['url_design_assets'] ?>" target=_blank><i class="fa fa-download" aria-hidden=true></i> 去下载</a>
			</li>
			<?php endif ?>
		</ul>
	</section>
	
	<section>
		<h3>载入事件</h3>
		<?php echo $item['onloads'] ?>
	</section>
	
	<section>
		<h3>业务流程</h3>
		<?php echo $item['events'] ?>
	</section>
	
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

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

		<?php if ( !empty($item['time_delete']) ): ?>
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
	
	<ul class="list-unstyled horizontal">
		<?php
		// 需要特定角色和权限进行该操作
		$current_role = $this->session->role; // 当前用户角色
		$current_level = $this->session->level; // 当前用户权限
		$role_allowed = array('经理', '管理员');
		$level_allowed = 1;
		if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
		?>
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
		<li><a title="删除" href="<?php echo base_url($this->class_name.'/delete?ids='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-trash"></i> 删除</a></li>
		<?php endif ?>
	</ul>
</div>