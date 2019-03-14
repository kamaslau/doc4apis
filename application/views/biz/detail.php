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
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="far fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="far fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="far fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

  <template v-if="item.length === 0">
    <p>没有相应的信息。</p>
  </template>

  <template v-else>
    <h2>{{ item.name }}</h2>

    <ul class=list-unstyled>
      <?php
      // 需要特定角色和权限进行该操作
      if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
      ?>
      <li><a href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="far fa-edit"></i> 编辑</a></li>
      <?php endif ?>
    </ul>

    <dl id=list-info class=dl-horizontal>
      <dt><?php echo $this->class_name_cn ?>ID</dt>
      <dd>{{ item[id_name] }}</dd>

      <dt>名称</dt>
      <dd>{{ item.name }}</dd>

      <dt>简称</dt>
      <dd>{{ item.brief_name }}</dd>

      <dt>说明</dt>
      <dd>{{ item.description }}</dd>
    </dl>

    <section v-if="item.url_logo.length > 0">
      <figure id=url-logo class=row>
        <img class="col-xs-12 col-md-3" :alt="item.name" src="<?php echo IMAGES_URL.'biz/' ?> + item.url_logo">
      </figure>
    </section>

    <dl id=list-record class=dl-horizontal>
      <dt>创建时间</dt>
      <dd>
        {{ item.time_create }}
        <?php if ( !empty($item['creator_id']) ): ?>
        <a href="<?php echo base_url('stuff/detail?id='.$item['creator_id']) ?>" target=new>查看创建者</a>
        <?php endif ?>
      </dd>

      <?php if ( ! empty($item['time_delete']) ): ?>
      <dt>删除时间</dt>
      <dd>{{ item.time_delete }}</dd>
      <?php endif ?>

      <?php if ( ! empty($item['operator_id']) ): ?>
      <dt>最后操作时间</dt>
      <dd>
        {{ item.time_edit }}
        <a href="<?php echo base_url('stuff/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
      </dd>
      <?php endif ?>
    </dl>
  </template>

</div>

<script>
  const vue_app = new Vue({
    el: '#content',

    data: {
      id_name: 'biz_id',
      item: <?php echo empty($item)? '[]': json_encode($item) ?>
    }
  })
</script>