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
		<li><a href="<?php echo base_url($this->class_name) ?>"><?php echo $this->class_name_cn ?></a></li>
		<li class=active><?php echo $title ?></li>
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
	  	<a class="btn btn-default" title="<?php echo $this->class_name_cn ?>回收站" href="<?php echo base_url($this->class_name.'/trash') ?>"><i class="fa fa-trash fa-fw" aria-hidden=true></i> 回收站</a>
		<a class="btn btn-default" title="创建<?php echo $this->class_name_cn ?>" href="<?php echo base_url($this->class_name.'/create?project_id='.$project['project_id']) ?>"><i class="fa fa-plus fa-fw" aria-hidden=true></i> 创建<?php echo $this->class_name_cn ?></a>
	</div>
	<?php endif ?>

	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-'.$this->class_name.'-edit form-horizontal', 'role' => 'form');
		echo form_open_multipart($this->class_name.'/edit?id='.$item[$this->id_name], $attributes);
	?>
		<fieldset>
			<legend>基本信息</legend>

			<div class=form-group>
				<label for=biz_id class="col-sm-2 control-label">所属企业</label>
				<div class=col-sm-10>
					<select class=form-control name=biz_id>
						<option value="">个人项目</option>

					<?php if ( isset($bizs) ):
							$input_name = 'biz_id';
							$text_name = 'brief_name';
							$option_list = $bizs;
							foreach ($option_list as $option):
						?>
						<option value="<?php echo $option[$input_name] ?>" <?php if ($item[$input_name] === $option[$input_name]) echo 'selected' ?>>
							<?php echo $option[$text_name] ?>
						</option>
						<?php endforeach ?>

					<?php elseif ( isset($biz) ): ?>
							<option value="<?php echo $biz[$input_name] ?>" <?php echo set_select($input_name, $biz[$input_name], TRUE) ?>><?php echo $biz[$text_name] ?></option>
					<?php endif ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label class="col-sm-2 control-label">所属项目</label>
				<div class=col-sm-10>
					<select class=form-control name=project_id>
						<option value="">不限</option>

					<?php if ( isset($projects) ):
							$input_name = 'project_id';
							$text_name = 'name';
							$option_list = $projects;
							foreach ($option_list as $option):
						?>
						<option value="<?php echo $option[$input_name] ?>" <?php if ($item[$input_name] === $option[$input_name]) echo 'selected' ?>>
							<?php echo $option[$text_name] ?>
						</option>
						<?php endforeach ?>

					<?php elseif ( isset($project) ): ?>
							<option value="<?php echo $project[$input_name] ?>" <?php echo set_select($input_name, $project[$input_name], TRUE) ?>><?php echo $project[$text_name] ?></option>
					<?php endif ?>
					</select>
				</div>
			</div>

			<div class=form-group>
				<label for=name class="col-sm-2 control-label">名称</label>
				<div class=col-sm-10>
					<input class=form-control name=name type=text value="<?php echo $item['name'] ?>" placeholder="页面名称，不加“页”字" required>
				</div>
				<?php echo form_error('name') ?>
			</div>

			<div class=form-group>
				<label for=code class="col-sm-2 control-label">序号</label>
				<div class=col-sm-10>
					<input class=form-control name=code type=text value="<?php echo $item['code'] ?>" placeholder="例如：BIZ100；系统会自动将英文字母转为大写" required>
				</div>
				<?php echo form_error('code') ?>
			</div>

			<div class=form-group>
				<label for=description class="col-sm-2 control-label">说明</label>
				<div class=col-sm-10>
					<textarea class=form-control name=description rows=3 placeholder="页面功能的简要描述"><?php echo $item['description'] ?></textarea>
				</div>
				<?php echo form_error('description') ?>
			</div>

			<div class=form-group>
				<label for=code_class class="col-sm-2 control-label">类名</label>
				<div class=col-sm-10>
					<input class=form-control name=code_class type=text value="<?php echo $item['code_class'] ?>" placeholder="输出该页面的入口方法所属的类名；需注意相关开发语言的保留字" required>
					<?php echo form_error('code_class') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=code_function class="col-sm-2 control-label">方法名</label>
				<div class=col-sm-10>
					<input class=form-control name=code_function type=text value="<?php echo $item['code_function'] ?>" placeholder="输出该页面的入口方法名；需注意相关开发语言的保留字" required>
					<?php echo form_error('code_function') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=private class="col-sm-2 control-label">需登录</label>
				<div class=col-sm-10>
					<label class=radio-inline>
						<input type=radio name=private value="1" required <?php if ($item['private'] === '1') echo 'checked'; ?>> 是
					</label>
					<label class=radio-inline>
						<input type=radio name=private value="0" required <?php if ($item['private'] === '0') echo 'checked'; ?>> 否
					</label>
					<?php echo form_error('private') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=elements class="col-sm-2 control-label">主要视图元素（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>
						<code class=help-block>常用制表符 ┣┗</code>
						&lt;tr&gt;&lt;td&gt;名称&lt;/td&gt;&lt;td&gt;所属组件ID&lt;/td&gt;&lt;td&gt;类型&lt;/td&gt;&lt;td&gt;说明&lt;/td&gt;&lt;/tr&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=elements>+</a>
					<textarea class=form-control name=elements rows=10 placeholder="完成页面功能所必需的视图元素，包括但不限于文本、图片、视频、按钮、表单项等"><?php echo $item['elements'] ?></textarea>
				</div>
				<?php echo form_error('elements') ?>
			</div>

			<div class=form-group>
				<label for=url_design class="col-sm-2 control-label">设计图URL（可选）</label>
				<div class=col-sm-10>
					<?php if ( !empty($item['url_design']) ): ?>
					<figure id=page-design class=row>
					<?php
							// 若含多项，根据分隔符拆分并轮番输出
							if (strpos( trim($item['url_design']), ' ') !== FALSE):
								$items_array = explode(' ', $item['url_design']);
								foreach ($items_array as $item_to_show):
					?>	
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'page/'.$item_to_show ?>">
					<?php
								endforeach;
							else:
					?>
						<img class="col-xs-12 col-md-3" alt="<?php echo $item['name'] ?>" src="<?php echo IMAGES_URL.'page/'.$item['url_design'] ?>">
					<?php 	endif ?>
					</figure>
					<?php endif ?>

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
					<?php if ( ! empty($item['url_assets']) ): ?>
					<p class=help-block>请将PSD文件、UI素材、字体、媒体文件等压缩后上传到百度云盘，并将该压缩文件的分享链接填入此处</p>
					<?php endif ?>
					<input class=form-control name=url_assets type=url value="<?php echo $item['url_assets'] ?>" placeholder="请将PSD文件、UI素材、字体、媒体文件等压缩后上传到百度云盘，并将该压缩文件的分享链接填入此处">
					<?php echo form_error('url_assets') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=note_designer class="col-sm-2 control-label">设计师备注（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=note_designer rows=3 placeholder="对于页面设计的补充说明或解释；最多255个字符"><?php echo $item['note_designer'] ?></textarea>
				</div>
				<?php echo form_error('note_designer') ?>
			</div>

			<div class=form-group>
				<label for=onloads class="col-sm-2 control-label">载入事件（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>
						&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=onloads>+</a>
					<textarea class=form-control name=onloads rows=10 placeholder="页面载入时需要完成的功能"><?php echo $item['onloads'] ?></textarea>
					<?php echo form_error('onloads') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=events class="col-sm-2 control-label">业务流程（可选）</label>
				<div class=col-sm-10>
					<code class=help-block>常用制表符 ┣┗</code>
					<code class=help-block>
						&lt;div class="panel panel-default"&gt;&lt;h4 class=panel-heading&gt;&lt;/h4&gt;&lt;ol class=panel-body&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;li&gt;&lt;/li&gt;&lt;/ol&gt;&lt;/div&gt;
					</code>
					<a class="add-html btn btn-info" data-textarea-name=events>+</a>
					<textarea class=form-control name=events rows=10 placeholder="除载入事件外，页面内可以完成的功能"><?php echo $item['events'] ?></textarea>
					<?php echo form_error('events') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=api_ids class="col-sm-2 control-label">相关API（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=api_ids type=text value="<?php echo $item['api_ids'] ?>" placeholder="与当前页面有关的API的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('api_ids') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=page_ids class="col-sm-2 control-label">相关页面（可选）</label>
				<div class=col-sm-10>
					<input class=form-control name=page_ids type=text value="<?php echo $item['page_ids'] ?>" placeholder="与当前页面有关的其它页面的ID们，多个ID间用一个空格分隔">
					<?php echo form_error('page_ids') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=note_developer class="col-sm-2 control-label">开发者备注（可选）</label>
				<div class=col-sm-10>
					<textarea class=form-control name=note_developer rows=3 placeholder="对于技术开发的补充说明或解释；最多255个字符"><?php echo $item['note_developer'] ?></textarea>
				</div>
				<?php echo form_error('note_developer') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>保存</button>
		    </div>
		</div>
	</form>
</div>