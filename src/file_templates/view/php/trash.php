<style>
	/* 宽度在750像素以上的设备 */
	@media only screen and (min-width:751px) {}
</style>

<script>
	// 页面主要数据
	let items = <?php echo json_encode($items) ?>;
	console.log(items);

	$(function() {
		// 点击触发异步请求
		$('#dom_id').on('click', function() {
			// 取值各字段，并请求API
			var params = common_params; // 初始化异步请求公共参数
			var params_needed = 'name1,name2'.split(',');
			for (let item of params_needed) {
				params[item] = $('[name=' + item + ']').val();
			}
			console.log(params);

			$.post(
				api_url + class_name + '/function',
				params,
				function(result) {
					console.log(result); // 输出回调数据到控制台

					if (result.status == 200) {
						// 操作成功后业务逻辑
						alert('succeed')

					} else {
						// 操作失败后业务逻辑
						alert(result.content.error.message)
					}
				}
			).fail(
				// 请求失败回调
				function() {
					alert("error")
				}
			);

			return false
		});

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

		<?php if (isset($content)) echo '<div class="alert alert-warning" role=alert>' . $content . '</div>'; ?>

		<?php if (empty($items)) : ?>
			<blockquote>
				<p>没有任何<?php echo $this->class_name_cn ?>曾经被删除。</p>
			</blockquote>

		<?php else : ?>
			<form method=get target=_blank>
				<fieldset>
					<div class=btn-group role=group>
						<button formaction="<?php echo base_url($this->class_name . '/restore') ?>" type=submit class="btn btn-default">恢复</button>
					</div>
				</fieldset>

				<table class="table table-condensed table-hover table-responsive table-striped sortable">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th><?php echo $this->class_name_cn ?>ID</th>
							<?php
							$thead = array_values($data_to_display);
							foreach ($thead as $th) :
								echo '<th>' . $th . '</th>';
							endforeach;
							?>
							<th>操作</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($items as $item) : ?>
							<tr>
								<td>
									<input name=ids[] class=form-control type=checkbox value="<?php echo $item[$this->id_name] ?>">
								</td>
								<td><?php echo $item[$this->id_name] ?></td>
								<?php
								$tr = array_keys($data_to_display);
								foreach ($tr as $td) :
									echo '<td>' . $item[$td] . '</td>';
								endforeach;
								?>
								<td>
									<ul class=list-unstyled>
										<li><a href="<?php echo base_url($this->view_root . '/detail?id=' . $item[$this->id_name]) ?>" target=_blank><i class="far fa-fw fa-eye"></i> 查看</a></li>
										<?php
										// 需要特定角色和权限进行该操作
										if (in_array($current_role, $role_allowed) && ($current_level >= $level_allowed)) :
										?>
											<li><a href="<?php echo base_url($this->class_name . '/edit?id=' . $item[$this->id_name]) ?>" target=_blank><i class="far fa-fw fa-edit"></i> 编辑</a></li>
											<li><a href="<?php echo base_url($this->class_name . '/restore?ids=' . $item[$this->id_name]) ?>" target=_blank><i class="far fa-fw fa-level-up"></i> 恢复</a></li>
										<?php endif ?>
									</ul>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>

			</form>
		<?php endif ?>

	</div><!-- end #content.container-->
</div>