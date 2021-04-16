<style>
	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px) {}
</style>

<script>
	$(function() {

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
			<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
			<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name . '/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
			<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name . '/create') ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
		</div>

		<h2><?php echo $title ?></h2>
		<?php if (!empty($content)) : ?>
			<section><?php echo $content ?></section>
		<?php endif ?>

		<ul class=row>
			<li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-default btn-lg" href="<?php echo base_url($this->class_name) ?>">返回<?php echo $this->class_name_cn ?>列表</a></li>

			<?php if (!empty($operation)) : ?>

				<?php if ($operation === 'create') : ?>
					<li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-primary btn-lg" href="<?php echo base_url($this->class_name . '/create') ?>">继续创建</a></li>
				<?php elseif ($operation === 'edit') : ?>
					<li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-primary btn-lg" href="<?php echo base_url($this->class_name . '/detail?id=' . $id) ?>">确认一下</a></li>
				<?php endif ?>

			<?php endif ?>
		</ul>

	</div><!-- end #content.container-->
</div>