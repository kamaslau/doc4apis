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
	  	<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class="container-fluid">
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('经理', '管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php echo $content ?>

    <ul class=row>
        <li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-default btn-lg" title="<?php echo $this->class_name_cn ?>列表" href="<?php echo base_url($this->class_name) ?>">返回<?php echo $this->class_name_cn ?>列表</a></li>

        <?php if ( !empty($operation) ): ?>

            <?php if ($operation === 'create'): ?>
                <li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-primary btn-lg" title="继续创建" href="<?php echo base_url($this->class_name.'/create') ?>">继续创建</a></li>
            <?php
                endif;
                if ( in_array($operation, array('create', 'create_quick', 'edit',)) ):
            ?>
                <li class="col-xs-12 col-sm-6 col-sm-3"><a class="btn btn-primary btn-lg" title="查看<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/detail?id='.$id) ?>">确认一下</a></li>
            <?php endif ?>

        <?php endif ?>
    </ul>

    <?php if ($operation === 'create' && $class === 'success'): ?>
    <div class="well well-sm text-center">
        <p>登录网址（登录后首页会提示修改初始密码）<br><strong><?php echo base_url('login') ?></strong></p>
    </div>
    <?php endif ?>
</div>