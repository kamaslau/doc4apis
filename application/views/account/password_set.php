<style>
	#content {padding-top:2rem;}
	form {padding-top:2rem;}
	
	/* 宽度在960像素以上的设备 */
	@media only screen and (min-width:961px)
	{

	}
</style>

<div id=content>
	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
		$attributes = array('class' => 'form-password-set', 'role' => 'form');
		echo form_open('password_set', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=password>密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=password type=password size=6 pattern="\d{6}" placeholder="密码（6位数字）" required>
				</div>
				<?php echo form_error('password') ?>
			</div>
			
			<div class=form-group>
				<label for=password2>确认密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fa fa-lock fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=password2 type=password size=6 pattern="\d{6}" placeholder="请再次输入一次" required>
				</div>
				<?php echo form_error('password2') ?>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12 col-sm-offset-2 col-sm-2">
				<button class="btn btn-primary btn-lg btn-block" type=submit>确定</button>
		    </div>
		</div>
	</form>
</div>