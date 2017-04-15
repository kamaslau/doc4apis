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
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>';
		$attributes = array('class' => 'form-password-reset', 'role' => 'form');
		echo form_open('password_reset', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=password_new>新密码</label>
				<div>
					<input name=password_new type=password placeholder="新密码" required>
					<?php echo form_error('password_new') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=password2>确认新密码</label>
				<div>
					<input name=password2 type=password placeholder="确认新密码" required>
					<?php echo form_error('password2') ?>
				</div>
			</div>
		</fieldset>

		<button type=submit>确定</button>
	</form>
</div>
