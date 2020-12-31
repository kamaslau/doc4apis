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
		<a class="btn btn-default" :href="base_url + class_name"><i class="fal fa-list"></i> 所有{{ class_name_cn }}</a>
    <a class="btn btn-default" :href="base_url + class_name + '/trash'"><i class="fal fa-trash"></i> 回收站</a>
		<a class="btn btn-default" :href="base_url + class_name + '/create?project_id=' + '<?php echo $project['project_id'] ?>'"><i class="fal fa-plus"></i> 添加{{ class_name_cn }}</a>
	</div>
	<?php endif ?>

	<h2>
    {{ item.code }} {{ item.name }}
    <span v-if="item.status == 0" class="label label-warning">草稿</span>
  </h2>
  <p>{{ item.description }}</p>

  <?php
  // 需要特定角色和权限进行该操作
  if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
      ?>
    <ul class="list-unstyled list-inline">
      <li><a :href="base_url + class_name + '/edit?id=' + item[id_name]" target=_blank><i class="fal fa-edit"></i> 编辑</a></li>
      <li><a :href="base_url + class_name + '/duplicate?id=' + item[id_name]" target=_blank><i class="fal fa-copy"></i> 克隆</a></li>
      <li><a title="下载所属类源文件" :href="base_url + class_name + '/download/' + '<?php echo substr( $item['url'], 0, strpos($item['url'], '/') ) ?>'" target="_blank"><i class="fal fa-download"></i> 下载</a></li>
    </ul>
  <?php endif ?>

	<dl class=dl-horizontal>
		<dt>URL</dt>

		<dd v-if="item.url_full">{{ item.url_full }}</dd>
		<dd v-else>{{ item.url }}</dd>
	</dl>

	<section>
    <h3>请求参数<small>（除公共参数外）</small></h3>

		<table v-if="item.params_request" class="table table-striped">
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>必要</th><th>示例</th><th>说明</th>
				</tr>
			</thead>

			<tbody v-html="item.params_request"></tbody>
		</table>

		<p v-else>除公共参数外，无其它参数</p>
	</section>

	<section>
		<h3>响应参数<small>（返回值中content的内容）</small></h3>

		<table v-if="item.params_respond" class="table table-striped">
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>示例</th><th>说明</th>
				</tr>
			</thead>

			<tbody v-html="item.params_respond"></tbody>
		</table>

		<p v-else>除公共参数外，无其它参数</p>
	</section>

	<section v-if="item.sample_request">
		<h3>请求示例</h3>
		<pre>{{ item.sample_request }}</pre>
	</section>

	<section v-if="item.sample_respond">
		<h3>返回示例</h3>
		<pre>{{ item.sample_respond }}</pre>
	</section>

	<dl id=list-record class=dl-horizontal>
		<dt>创建时间</dt>
		<dd>
			{{ item.time_create }}
			<a v-if="item.creator_id" :href="url_stuff_detail + item.creator_id" target=new>查看创建者</a>
		</dd>

    <template v-if="item.time_delete">
      <dt>删除时间</dt>
      <dd>
        {{ item.time_delete }}
        <a v-if="item.operator_id" :href="url_stuff_detail + item.operator_id" target=new>查看删除者</a>
      </dd>
    </template>

    <dt>最后操作时间</dt>
    <dd>
      {{ item.time_edit }}
      <a v-if="item.operator_id" :href="url_stuff_detail + item.operator_id" target=new>查看删除者</a>
    </dd>
  </dl>

</div>

<script>
  const vue_app = new Vue({
    el: '#content',

    data: {
      base_url: '<?php echo base_url() ?>',

      class_name: '<?php echo $this->class_name ?>',
      class_name_cn: '<?php echo $this->class_name_cn ?>',
      id_name: '<?php echo $this->id_name ?>',

      url_stuff_detail: '<?php echo base_url('stuff/detail?id=') ?>',

      item: <?php echo empty($item)? '[]': json_encode($item) ?>
    }
  })
</script>