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

<script>
	$(function(){

	});
</script>

<div id=content class=container>
	<h2><?php echo $title ?></h2>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
		$attributes = array('class' => 'form-password-change form-horizontal', 'role' => 'form');
		echo form_open('password_change', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=password class="col-sm-2 control-label">现密码</label>
				<div class=col-sm-10>
					<input class=form-control name=password type=password placeholder="现密码" required>
					<?php echo form_error('password') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=password_new class="col-sm-2 control-label">新密码</label>
				<div class=col-sm-10>
					<input class=form-control name=password_new type=password placeholder="请设置6-20位长度的密码" required>
					<?php echo form_error('password_new') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=password2 class="col-sm-2 control-label">确认新密码</label>
				<div class=col-sm-10>
					<input class=form-control name=password2 type=password placeholder="确认密码" required>
					<?php echo form_error('password2') ?>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>
