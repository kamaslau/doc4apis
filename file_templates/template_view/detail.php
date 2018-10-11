<link rel=stylesheet media=all href="<?php echo CSS_URL ?>detail.css">
<style>


	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px)
	{
		
	}
</style>

<script defer src="<?php echo JS_URL ?>detail.js"></script>
<script>
    $(function(){
		
    });
</script>

<base href="<?php echo $this->media_root ?>">

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content>
    <div class=container>

    <div class=btn-group role=group>
        <a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
        <a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
        <a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="far fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
    </div>

	<?php if ( empty($item) ): ?>
	<p><?php echo $error ?></p>

	<?php
		else:
			// 需要特定角色和权限进行该操作
			$current_role = $this->session->role; // 当前用户角色
			$current_level = $this->session->level; // 当前用户级别
			$role_allowed = array('管理员', '经理');
			$level_allowed = 30;
			if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
			?>
		    <ul id=item-actions class=list-unstyled>
				<li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>">编辑</a></li>
		    </ul>
			<?php endif ?>

	<dl id=list-info class=dl-horizontal>
		<dt><?php echo $this->class_name_cn ?>ID</dt>
		<dd><?php echo $item[$this->id_name] ?></dd>

        <dt>描述</dt>
        <dd><?php echo empty($item['description'])? 'N/A': $item['description'] ?></dd>
		
		<dt>主图</dt>
		<dd class=row>
			<?php
				$column_image = 'url_image_main';
				if ( empty($item[$column_image]) ):
			?>
			<p>未上传</p>
			<?php else: ?>
			<figure class="col-xs-12 col-sm-6 col-md-4">
				<img src="<?php echo $item[$column_image] ?>">
			</figure>
			<?php endif ?>
		</dd>
		
		<dt>形象图</dt>
		<dd>
			<?php
				$column_images = 'url_image_main';
				if ( empty($item[$column_images]) ):
			?>
			<p>未上传</p>
			<?php else: ?>
			<ul class=row>
				<?php
					$image_urls = explode(',', $item[$column_images]);
					foreach($image_urls as $url):
				?>
				<li class="col-xs-6 col-sm-4 col-md-3">
					<img src="<?php echo $url ?>">
				</li>
				<?php endforeach ?>
			</ul>
			<?php endif ?>
		</dd>
		
		[[content]]
	</dl>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			<?php echo $item['time_create'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
		</dd>

		<?php if ( ! empty($item['time_delete']) ): ?>
		<dt>删除时间</dt>
		<dd><?php echo $item['time_delete'] ?></dd>
		<?php endif ?>

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
	<?php endif ?>
	
    </div><!-- end #content.container-->
</div>