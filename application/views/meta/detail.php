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
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $project['name'] ?></li>
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
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<h2>
		<a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>" target=_blank><?php echo $project['name'] ?></a>
	</h2>

	<p>
		<?php if ( !empty($item['sdk_ios']) ): ?>
		<span><i class="fa fa-apple" aria-hidden="true"></i> ≥<?php echo $item['sdk_ios'] ?></span>
		<?php endif ?>

		<?php if ( !empty($item['sdk_android']) ): ?>
		<span><i class="fa fa-android" aria-hidden="true"></i> ≥<?php echo $item['sdk_android'] ?></span>
		<?php endif ?>
	</p>

	<section>
		<h3>测试/开发环境</h3>
		<dl class=dl-horizontal>
			<?php if ( !empty($item['sandbox_url_web']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> WEB</dt>
			<dd id=sandbox_url_web>
				<?php echo $item['sandbox_url_web'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#sandbox_url_web').qrcode("<?php echo $item['sandbox_url_web'] ?>");
				</script>
			</dd>
			<?php endif ?>

			<?php if ( !empty($item['sandbox_url_api']) ): ?>
			<dt><i class="fa fa-plug" aria-hidden="true"></i> API</dt>
			<dd><?php echo $item['sandbox_url_api'] ?></dd>
			<?php endif ?>
		</dl>
	<section>

	<section>
		<h3>正式/生产环境</h3>
		<dl class=dl-horizontal>
			<?php if ( !empty($item['url_web']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> WEB</dt>
			<dd id=url_web>
				<?php echo $item['url_web'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_web').qrcode("<?php echo $item['url_web'] ?>");
				</script>
			</dd>
			<?php endif ?>

			<?php if ( !empty($item['url_wechat']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> 微信公众号二维码</dt>
			<dd id=url_wechat>
				<?php echo $item['url_wechat'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_wechat').qrcode("<?php echo $item['url_wechat'] ?>");
				</script>
			</dd>
			<?php endif ?>

			<?php if ( !empty($item['url_api']) ): ?>
			<dt><i class="fa fa-safari" aria-hidden="true"></i> API</dt>
			<dd><?php echo $item['url_api'] ?></dd>
			<?php endif ?>

			<?php if ( !empty($item['url_ios']) ): ?>
			<dt><i class="fa fa-apple" aria-hidden="true"></i> iOS</dt>
			<dd id=url_ios>
				<?php echo $item['url_ios'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_ios').qrcode("<?php echo $item['url_ios'] ?>");
				</script>
			</dd>
			<?php endif ?>

			<?php if ( !empty($item['url_android']) ): ?>
			<dt><i class="fa fa-android" aria-hidden="true"></i> Android</dt>
			<dd id=url_android>
				<?php echo $item['url_android'] ?>
				<script>
					jQuery('<figure class=qrcode>').appendTo('#url_android').qrcode("<?php echo $item['url_android'] ?>");
				</script>
			</dd>
			<?php endif ?>
		</dl>
	</section>

	<section>
		<h3>API规范</h3>
		<dl class=dl-horizontal>
			<dt>字符编码</dt>
			<dd><?php echo $item['encode'] ?></dd>

			<dt>传输协议</dt>
			<dd><?php echo $item['protocol'] ?></dd>

			<dt>请求方式</dt>
			<dd><?php echo $item['request_method'] ?></dd>

			<dt>请求格式</dt>
			<dd><?php echo $item['request_format'] ?></dd>

			<dt>响应返回格式</dt>
			<dd><?php echo $item['respond_format'] ?></dd>
		</dl>
	</section>

	<?php if ( !empty($item['sign']) ): ?>
	<section>
		<h3>签名方式</h3>
		<ol>
		<?php echo $item['sign'] ?>
		</ol>
	</section>
	<?php endif ?>

	<section>
		<h3>公共参数</h3>

		<table class="table table-striped">
			<caption>请求参数</caption>
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>是否必要</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $item['params_request'] ?>
			</tbody>
		</table>

		<table class="table table-striped">
			<caption>响应参数</caption>
			<thead>
				<tr>
					<th>名称</th><th>类型</th><th>示例</th><th>说明</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $item['params_respond'] ?>
			</tbody>
		</table>
	</section>

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

		<?php if ( ! empty($item['operator_id']) ): ?>
		<dt>最后操作时间</dt>
		<dd>
			<?php echo $item['time_edit'] ?>
			<a href="<?php echo base_url('user/detail?id='.$item['operator_id']) ?>" target=new>查看最后操作者</a>
		</dd>
		<?php endif ?>
	</dl>
	
	
	<?php
	// 需要特定角色和权限进行该操作
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<ul class="list-unstyled horizontal">
		<li><a title="编辑" href="<?php echo base_url($this->class_name.'/edit?id='.$item[$this->id_name]) ?>" target=_blank><i class="fa fa-edit"></i> 编辑</a></li>
	</ul>
	<?php endif ?>
</div>