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

<script defer src="/js/file-upload.js"></script>
<script defer src="/js/main.js"></script>

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
	$current_level = $this->session->level; // 当前用户级别
	$role_allowed = array('管理员', '经理');
	$level_allowed = 30;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fal fa-list fa-fw"></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fal fa-trash fa-fw"></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.@$project['project_id']) ?>"><i class="fal fa-plus fa-fw"></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=sdk_ios class="col-sm-2 control-label">iOS最低版本</label>
				<div class=col-sm-10>
					<input class=form-control name=sdk_ios type=text value="<?php echo $item['sdk_ios'] ?>" placeholder="例如：9.0">
					<?php echo form_error('sdk_ios') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=sdk_android class="col-sm-2 control-label">Android最低版本</label>
				<div class=col-sm-10>
					<input class=form-control name=sdk_android type=text value="<?php echo $item['sdk_android'] ?>" placeholder="例如：4.1">
					<?php echo form_error('sdk_android') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>测试/开发环境</legend>

			<div class=form-group>
				<label for=sandbox_url_web class="col-sm-2 control-label">WEB URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=sandbox_url_web type=url value="<?php echo $item['sandbox_url_web'] ?>" placeholder="必须以https://开头">
					<?php echo form_error('sandbox_url_web') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=sandbox_url_api class="col-sm-2 control-label">API URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=sandbox_url_api type=url value="<?php echo $item['sandbox_url_api'] ?>" placeholder="必须以https://开头">
					<?php echo form_error('sandbox_url_api') ?>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>正式/生产环境</legend>

			<div class=form-group>
				<label for=url_web class="col-sm-2 control-label">WEB URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_web type=url value="<?php echo $item['url_web'] ?>" placeholder="必须以https://开头">
					<?php echo form_error('url_web') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_wechat class="col-sm-2 control-label">微信公众号二维码（可选）</label>
				<div class=col-sm-10>
					<p class=help-block>二维码文本内容可通过 <a class="btn btn-info btn-sm" href="http://cli.im/deqr" target=_blank>解码工具</a> 获取</p>
					<input class=form-control name=url_wechat type=url value="<?php echo $item['url_wechat'] ?>" placeholder="即微信公众号二维码文本">
					<?php echo form_error('url_wechat') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_api class="col-sm-2 control-label">API URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_api type=url value="<?php echo $item['url_api'] ?>" placeholder="必须以https://开头">
					<?php echo form_error('url_api') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_ios class="col-sm-2 control-label">iOS URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_ios type=url value="<?php echo $item['url_ios'] ?>" placeholder="必须是官方下载URL；URL中除appid外不可有其它参数">
					<?php echo form_error('url_ios') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_android class="col-sm-2 control-label">Android URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_android type=url value="<?php echo $item['url_android'] ?>" placeholder="必须以https://开头">
					<?php echo form_error('url_android') ?>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>API规范</legend>

			<div class=form-group>
				<label for=encode class="col-sm-2 control-label">字符编码</label>
				<div class=col-sm-10>
					<select class=form-control name=encode>
						<?php
							$input_name = 'encode';
							$options = array('UTF8','GBK','GB2312','ASCII','BIG5');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>

					<?php echo form_error('encode') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=protocol class="col-sm-2 control-label">传输协议</label>
				<div class=col-sm-10>
					<select class=form-control name=protocol>
						<?php
							$input_name = 'protocol';
							$options = array('HTTPS','HTTP');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>

					<?php echo form_error('protocol') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=request_method class="col-sm-2 control-label">请求方式</label>
				<div class=col-sm-10>
					<select class=form-control name=request_method>
						<?php
							$input_name = 'request_method';
							$options = array('POST','GET','PUT','DELETE');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>

					<?php echo form_error('request_method') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=request_format class="col-sm-2 control-label">请求格式</label>
				<div class=col-sm-10>
					<select class=form-control name=request_format>
						<?php
							$input_name = 'request_format';
							$options = array('form-data','x-www-form-urlencoded','raw','binary','JSON','XML');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>

					<?php echo form_error('request_format') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=respond_format class="col-sm-2 control-label">响应返回格式</label>
				<div class=col-sm-10>
					<select class=form-control name=respond_format>
						<?php
							$input_name = 'respond_format';
							$options = array('JSON','XML');
							foreach ($options as $option):
						?>
						<option value="<?php echo $option ?>" <?php if ($option === $item[$input_name]) echo 'selected'; ?>>
							<?php echo $option ?>
						</option>
						<?php endforeach ?>
					</select>

					<?php echo form_error('respond_format') ?>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>公共参数（可选）</legend>
			
			<div class=form-group>
				<label for=sign class="col-sm-2 control-label">签名方式</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=sign>+</a>
					<textarea class=form-control name=sign rows=10 placeholder="签名方式"><?php echo $item['sign'] ?></textarea>
					<?php echo form_error('sign') ?>
				</div>
			</div>
			
			<div class=form-group>
				<label for=params_request class="col-sm-2 control-label">请求参数</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;tr&gt;&lt;td&gt;名称&lt;/td&gt;&lt;td&gt;类型&lt;/td&gt;&lt;td&gt;是否必要&lt;/td&gt;&lt;td&gt;示例&lt;/td&gt;&lt;td&gt;说明&lt;/td&gt;&lt;/tr&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=params_request>+</a>
					<textarea class=form-control name=params_request rows=10 placeholder="请求参数"><?php echo $item['params_request'] ?></textarea>
					<?php echo form_error('params_request') ?>
				</div>
			</div>
			
			<div class=form-group>
				<label for=params_respond class="col-sm-2 control-label">响应参数</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;tr&gt;&lt;td&gt;名称&lt;/td&gt;&lt;td&gt;类型&lt;/td&gt;&lt;td&gt;示例&lt;/td&gt;&lt;td&gt;说明&lt;/td&gt;&lt;/tr&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=params_respond>+</a>
					<textarea class=form-control name=params_respond rows=10 placeholder="响应参数"><?php echo $item['params_respond'] ?></textarea>
					<?php echo form_error('params_respond') ?>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>