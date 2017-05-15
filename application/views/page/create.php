<style>


	/* 宽度在768像素以上的设备 */
	@media only screen and (min-width:769px)
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

<script defer src="/js/file-upload.js"></script>
<script defer src="/js/main.js"></script>

<div id=breadcrumb>
	<ol class="breadcrumb container">
		<li><a href="<?php echo base_url() ?>">首页</a></li>
		<li><a title="<?php echo $project['name'] ?>" href="<?php echo base_url('project/detail?id='.$project['project_id']) ?>"><?php echo $project['name'] ?></a></li>
		<li><a href="<?php echo base_url($this->class_name.'?project_id='.$project['project_id']) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
	</ol>
</div>

<div id=content class=container>
	<?php
	// 需要特定角色和权限进行该操作
	$current_role = $this->session->role; // 当前用户角色
	$current_level = $this->session->level; // 当前用户权限
	$role_allowed = array('经理', '管理员');
	$level_allowed = 1;
	if ( in_array($current_role, $role_allowed) && ($current_level >= $level_allowed) ):
	?>
	<div class=btn-group role=group>
		<a class="btn btn-default" title="所有<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name) ?>"><i class="fa fa-list fa-fw" aria-hidden=true></i> 所有<?php echo $this->class_name_cn ?></a>
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-primary" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-create form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/create', $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=project_id class="col-sm-2 control-label">所属项目</label>
				<div class=col-sm-10>
					<p class="form-control-static"><?php echo $project['name'] ?></p>
					<input name=project_id type=hidden value="<?php echo $project['project_id'] ?>">
				</div>
			</div>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo set_value('name') ?>" placeholder="页面名称，无需加“页”字" required>
					<?php echo form_error('name') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=3 placeholder="页面功能的简要描述"><?php echo set_value('description') ?></textarea>
					<?php echo form_error('description') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=code_class class="col-sm-2 control-label">类名</label>
				<div class=col-sm-10>
					<input class=form-control name=code_class type=text value="<?php echo set_value('code_class') ?>" placeholder="输出该页面的入口方法所属的类名；需注意相关开发语言的保留字" required>
					<?php echo form_error('code_class') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=code_function class="col-sm-2 control-label">方法名</label>
				<div class=col-sm-10>
					<input class=form-control name=code_function type=text value="<?php echo set_value('code_function') ?>" placeholder="输出该页面的入口方法名；需注意相关开发语言的保留字" required>
					<?php echo form_error('code_function') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=private class="col-sm-2 control-label">需登录</label>
				<div class=col-sm-10>
					<label class=radio-inline>
						<input type=radio name=private value="是" required <?php echo set_radio('private', '是', TRUE) ?>> 是
					</label>
					<label class=radio-inline>
						<input type=radio name=private value="否" required <?php echo set_radio('private', '否') ?>> 否
					</label>
					<?php echo form_error('private') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=elements class="col-sm-2 control-label">主要视图元素（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;tr&gt;&lt;td&gt;ID&lt;/td&gt;&lt;td&gt;类型&lt;/td&gt;&lt;td&gt;名称&lt;/td&gt;&lt;td&gt;说明&lt;/td&gt;&lt;/tr&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=elements>+</a>
					<textarea class=form-control name=elements rows=10 placeholder="完成页面功能所必需的视图元素，包括但不限于文本、图片、视频、按钮、表单项等"><?php echo set_value('elements') ?></textarea>
					<?php echo form_error('elements') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_design class="col-sm-2 control-label">设计图URL（可选）</label>
				<div class=col-sm-10>
					<p class=help-block>请上传jpg/png/webp格式设计图，文件大小控制在2M之内</p>

					<input id=url_design class=form-control type=file multiple>
					<input name=url_design type=hidden value="<?php echo set_value('url_design') ?>">

					<button class="file-upload btn btn-primary btn-lg col-xs-12 col-md-3" data-target-dir=page data-selector-id=url_design data-input-name=url_design type=button><i class="fa fa-upload" aria-hidden=true></i> 上传</button>

					<ul class="upload_preview list-inline row"></ul>
					<?php echo form_error('url_design') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=url_assets class="col-sm-2 control-label">美术素材URL（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=url_assets type=url value="<?php echo set_value('url_assets') ?>" placeholder="请将PSD文件、UI素材、字体、媒体文件等压缩后上传到百度云盘，并将该压缩文件的分享链接填入此处">
					<?php echo form_error('url_assets') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=onloads class="col-sm-2 control-label">载入事件（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=onloads>+</a>
					<textarea class=form-control name=onloads rows=10 placeholder="页面载入时需要完成的功能"><?php echo set_value('onloads') ?></textarea>
				</div>
				<?php echo form_error('onloads') ?>
			</div>

			<div class=form-group>
				<label for=events class="col-sm-2 control-label">业务流程（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;h4&gt;&lt;/h4&gt;&lt;ol&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;/ol&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=events>+</a>
					<textarea class=form-control name=events rows=10 placeholder="除载入事件外，页面内可以完成的功能"><?php echo set_value('events') ?></textarea>
				</div>
				<?php echo form_error('events') ?>
			</div>
			
			<div class=form-group>
				<label for=api_ids class="col-sm-2 control-label">相关API（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=api_ids type=text value="<?php echo set_value('api_ids') ?>" placeholder="与当前页面有关的API的ID们，多个ID间用一个空格分隔">
				</div>
				<?php echo form_error('api_ids') ?>
			</div>
			
			<div class=form-group>
				<label for=page_ids class="col-sm-2 control-label">相关页面（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=page_ids type=text value="<?php echo set_value('page_ids') ?>" placeholder="与当前页面有关的其它页面的ID们，多个ID间用一个空格分隔">
				</div>
				<?php echo form_error('page_ids') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>