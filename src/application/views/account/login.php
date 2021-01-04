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

<div id=content class="container-fluid">
	<h2 class=text-center><?php echo $title ?></h2>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
		$attributes = array('class' => 'form-login col-xs-12 col-md-6 col-md-offset-3', 'role' => 'form');
		echo form_open('login', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=mobile>手机号</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fal fa-mobile fa-fw"></i></span>
					<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
					<?php echo form_error('mobile') ?>
				</div>
			</div>

			<div class=form-group>
				<label for=password>密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fal fa-key fa-fw"></i></span>
					<input class=form-control name=password type=password <?php if ($this->input->cookie('mobile')) echo 'autofocus '; ?> placeholder="密码" required>
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
		<li><a href="<?php echo base_url('register') ?>">注册</a></li>
		<li><a href="<?php echo base_url('password_reset') ?>">忘记密码</a></li>
	</ul>
</div>
