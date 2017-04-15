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
		$attributes = array('class' => 'form-login', 'role' => 'form');
		echo form_open('login', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=mobile>手机号</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fa fa-mobile fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
					<?php echo form_error('mobile') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=password>密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fa fa-key fa-fw" aria-hidden=true></i></span>
					<input class=form-control name=password type=password <?php if ($this->input->cookie('mobile')) echo 'autofocus '; ?>size=6 pattern="\d{6}" placeholder="密码（6位数字）" required>
					<?php echo form_error('password') ?>
				</div>
			</div>
		</fieldset>

		<div class=form-group>
		    <div class="col-xs-12">
				<button class="btn btn-primary btn-lg btn-block" type=submit role=button>登录</button>
		    </div>
		</div>
	</form>

	<ul class=hide>
		<li><a title="注册" href="<?php echo base_url('register') ?>">注册</a></li>
		<li><a title="找回密码" href="<?php echo base_url('password_reset') ?>">找回密码</a></li>
	</ul>
</div>
