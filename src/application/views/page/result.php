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
	<ol class="breadcrumb container">
	  	<li><a href="<?php echo base_url() ?>">首页</a></li>
	  	<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<h2><?php echo $title ?></h2>
	<section><?php echo $content ?></section>

	<ul class=row>
		<li class="col-xs-12 col-md-6 col-sm-3"><a class="btn btn-default btn-lg" title="<?php echo $this->class_name_cn ?>列表" href="<?php echo base_url($this->class_name) ?>">返回<?php echo $this->class_name_cn ?>列表</a></li>

	<?php if ( !empty($operation) ): ?>

		<?php if ($operation === 'create'): ?>
		<li class="col-xs-12 col-md-6 col-sm-3"><a class="btn btn-primary btn-lg" title="继续创建" href="<?php echo base_url($this->class_name.'/create') ?>">继续创建</a></li>
		<?php elseif ($operation === 'edit' || $operation === 'duplicate'): ?>
		<li class="col-xs-12 col-md-6 col-sm-3"><a class="btn btn-primary btn-lg" title="查看<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/detail?id='.$id) ?>">确认一下</a></li>
		<?php endif ?>

	<?php endif ?>
	</ul>
	
</div>