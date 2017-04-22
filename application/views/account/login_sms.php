<style>
	#content {padding-top:2rem;}
	form {padding-top:2rem;}

	#captcha-image {width:80px;height:34px;position:relative;top:-34px;float:right}
</style>

<script src="/js/main.js"></script>

<div id=content>
	<div class="btn-group btn-group-justified" role=group>
		<!--<a class="btn btn-default" href="<?php echo base_url('login') ?>">密码登录</a>-->
		<a class="btn btn-primary" href="#">短信登录</a>
	</div>
	<?php
		if ( isset($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
		$attributes = array('class' => 'form-login-sms', 'role' => 'form');
		echo form_open('login_sms', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=mobile>手机号</label>
				<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
				<?php echo form_error('mobile') ?>
			</div>

			<div class=form-group>
				<label for=captcha_verify>图片验证码</label>
					<input id=captcha-verify class=form-control name=captcha_verify type=number max=9999 min=0001 step=1 size=4 placeholder="请输入图片验证码" required>
					<img id=captcha-image src="<?php echo base_url('captcha') ?>">
				<?php echo form_error('captcha_verify') ?>
			</div>

			<div class=form-group>
				<label for=captcha>短信验证码</label>
				<div class=input-group>
					<input id=captcha-input class=form-control name=captcha type=number max=999999 step=1 size=6 pattern="\d{6}" placeholder="请输入短信验证码" disabled required>
					<span class="input-group-addon">
						<a id=sms-send class=append href="#">获取验证码</a>
					</span>
				</div>
				<?php echo form_error('captcha') ?>
			</div>
		</fieldset>

		<button class="btn btn-primary btn-lg btn-block" type=submit role=button>登录</button>
	</form>

	<a class="btn btn-vice btn-lg btn-block" title="注册" href="<?php echo base_url('register') ?>">注册</a>
	<ul class="list-unstyled hide">
		<li><a title="找回密码" href="<?php echo base_url('password_reset') ?>">找回密码</a></li>
	</ul>
</div>
