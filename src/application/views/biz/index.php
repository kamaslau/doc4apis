<link rel=stylesheet media=all href="<?php echo VIEWPATH ?>css/index.css">
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
		<li class=active><?php echo $this->class_name_cn ?></li>
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
		<a class="btn btn-primary" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有</a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create') ?>"><i class="fal fa-plus fa-fw"></i> 创建</a>
	</div>
	<?php endif ?>

  <template v-if="items.length === 0">

    <blockquote>
      <p>这里空空如也，快点添加<?php echo $this->class_name_cn ?>吧</p>
    </blockquote>

  </template>

  <template v-else>

    <table class="table table-condensed table-responsive table-striped sortable">
      <thead>
        <tr>
          <th><?php echo $this->class_name_cn ?>ID</th><th>简称</th><th>名称</th><th>成员</th><th>操作</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, index) in items" :key="index">
          <td>{{ item[id_name] }}</td>
          <td>{{ item.brief_name }}</td>
          <td>{{ item.name }}</td>
          <td>
            <ul class=list-unstyled>
              <li>
                <a class="btn btn-default" :href="'<?php echo base_url() ?>' + 'user?biz_id=' + item[id_name]" target=_blank><i class="fal fa-users"></i></a>
              </li>
              <li>
                <a class="btn btn-default" :href="'<?php echo base_url() ?>' + 'user/create?biz_id=' + item[id_name]" target=_blank><i class="fal fa-plus"></i></a>
              </li>
            </ul>
          </td>
          <td>
            <ul class="list-actions list-unstyled list-inline">
              <li><a :href="'<?php echo base_url($this->view_root) ?>' + '/detail?id=' + item[id_name]" target=_blank><i class="fal fa-eye"></i></a></li>
                <?php
                // 需要特定角色和权限进行该操作
                if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
                    ?>
                  <li><a :href="'<?php echo base_url($this->class_name) ?>' + '/edit?id=' + item[id_name]" target=_blank><i class="fal fa-edit"></i></a></li>
                  <li><a :href="'<?php echo base_url($this->class_name) ?>' + '/delete?ids=' + item[id_name]" target=_blank><i class="fal fa-trash"></i></a></li>
                <?php endif ?>
            </ul>
          </td>
        </tr>

      </tbody>
    </table>

  </template>
</div>

<script>
  const vue_app = new Vue({
    el: '#content',

    data: {
      id_name: 'biz_id',
      items: <?php echo empty($items)? '[]': json_encode($items) ?>
    }
  })
</script>