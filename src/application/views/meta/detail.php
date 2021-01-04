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
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $project['name'] ?></li>
	</ol>
</div>

<div id=content class="container-fluid">
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

  <template v-if="item.length === 0">
    <p>没有相应的信息。</p>
  </template>

  <template v-else>
    <h2>
      <a :title="project.name" :href="'<?php echo base_url() ?>project/detail?id=' + project.project_id" target=_blank>{{ project.name }}</a>
    </h2>

    <?php
    // 需要特定角色和权限进行该操作
    if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
        ?>
      <ul class="list-unstyled list-inline">
        <li><a :href="'<?php echo base_url($this->class_name) ?>/edit?id=' + item[id_name]" target=_blank><i class="fal fa-edit"></i> 编辑</a></li>
      </ul>
    <?php endif ?>

    <p>
      <span v-if="item.sdk_ios"><i class="fab fa-apple"></i> ≥ {{ item.sdk_ios }}</span>

      <span v-if="item.sdk_android"><i class="fab fa-android"></i> ≥ {{ item.sdk_android }}</span>
    </p>

    <section>
      <h3>测试/开发环境</h3>
      <dl class=dl-horizontal>
        <?php if ( !empty($item['sandbox_url_web']) ): ?>
        <dt><i class="fab fa-safari"></i> WEB</dt>
        <dd id=sandbox_url_web>
          <a :href="item.sandbox_url_web" target="_blank">{{ item.sandbox_url_web }}</a>

          <script>
            jQuery('<figure class=qrcode>').appendTo('#sandbox_url_web').qrcode("<?php echo $item['sandbox_url_web'] ?>");
          </script>
        </dd>
        <?php endif ?>

        <?php if ( !empty($item['sandbox_url_api']) ): ?>
        <dt><i class="fal fa-plug"></i> API</dt>
        <dd>{{ item.sandbox_url_api }}</dd>
        <?php endif ?>
      </dl>
    <section>

    <section>
      <h3>正式/生产环境</h3>
      <dl class=dl-horizontal>
        <?php if ( !empty($item['url_web']) ): ?>
        <dt><i class="fal fa-safari"></i> WEB</dt>
        <dd id=url_web>
          <a :href="item.url_web" target="_blank">{{ item.url_web }}</a>

          <script>
            jQuery('<figure class=qrcode>').appendTo('#url_web').qrcode(item.url_web);
          </script>
        </dd>
        <?php endif ?>

        <?php if ( !empty($item['url_wechat']) ): ?>
        <dt><i class="fal fa-safari"></i> 微信公众号二维码</dt>
        <dd id=url_wechat>
          {{ item.url_wechat }}

          <script>
            jQuery('<figure class=qrcode>').appendTo('#url_wechat').qrcode(item.url_wechat);
          </script>
        </dd>
        <?php endif ?>

        <?php if ( !empty($item['url_api']) ): ?>
        <dt><i class="fal fa-safari"></i> API</dt>
        <dd>{{ item.url_api }}</dd>
        <?php endif ?>

        <?php if ( !empty($item['url_ios']) ): ?>
        <dt><i class="fal fa-apple"></i> iOS</dt>
        <dd id=url_ios>
          {{ item.url_ios }}

          <script>
            jQuery('<figure class=qrcode>').appendTo('#url_ios').qrcode(item.url_ios);
          </script>
        </dd>
        <?php endif ?>

        <?php if ( !empty($item['url_android']) ): ?>
        <dt><i class="fal fa-android"></i> Android</dt>
        <dd id=url_android>
          {{ item.url_android }}

          <script>
            jQuery('<figure class=qrcode>').appendTo('#url_android').qrcode(item.url_ios);
          </script>
        </dd>
        <?php endif ?>
      </dl>
    </section>

    <section>
      <h3>API规范</h3>

      <dl class=dl-horizontal>
        <dt>字符编码</dt>
        <dd>{{ item.encode }}</dd>

        <dt>传输协议</dt>
        <dd>{{ item.protocol }}</dd>

        <dt>请求方式</dt>
        <dd>{{ item.request_method }}</dd>

        <dt>请求格式</dt>
        <dd>{{ item.request_format }}</dd>

        <dt>响应格式</dt>
        <dd>{{ item.respond_format }}</dd>
      </dl>
    </section>

    <section v-if="item.sign.length > 0">
      <h3>签名方式</h3>
      <ol v-html="item.sign"></ol>
    </section>

    <section>
      <h3>公共参数</h3>

      <table v-if="item.params_request.length > 0" class="table table-striped">
        <caption>请求</caption>
        <thead>
          <tr>
            <th>名称</th><th>类型</th><th>是否必要</th><th>示例</th><th>说明</th>
          </tr>
        </thead>
        <tbody v-html="item.params_request"></tbody>
      </table>

      <table v-if="item.params_respond.length > 0" class="table table-striped">
        <caption>响应</caption>
        <thead>
          <tr>
            <th>名称</th><th>类型</th><th>示例</th><th>说明</th>
          </tr>
        </thead>
        <tbody v-html="item.params_respond"></tbody>
      </table>
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
  </template>
</div>

<script>
  const vue_app = new Vue({
    el: '#content',

    data: {
      id_name: 'meta_id',
      item: <?php echo empty($item)? '[]': json_encode($item) ?>,

      project: <?php echo empty($project)? '[]': json_encode($project) ?>,

      url_stuff_detail: '<?php echo base_url('stuff/detail?id=') ?>'
    },

    created() {
      console.log('created: \r\n item:', this.item, '\r\n project:', this.project)
    }
  })
</script>
