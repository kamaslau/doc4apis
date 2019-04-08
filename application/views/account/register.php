<style>

</style>

<script>
	$(function(){

	});
</script>

<div id=content>
	<?php
		if ( !empty($error) ) echo '<div class="alert alert-warning" role=alert>'.$error.'</div>'; // 若有错误提示信息则显示
		$attributes = array('class' => 'form-register', 'role' => 'form');
		echo form_open('register', $attributes);
	?>
		<fieldset>
			<div class=form-group>
				<label for=mobile>手机号</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fal fa-mobile fa-fw"></i></span>
					<input class=form-control name=mobile type=tel value="<?php echo $this->input->post('mobile')? set_value('mobile'): $this->input->cookie('mobile') ?>" size=11 pattern="\d{11}" placeholder="手机号" required>
				</div>
				<?php echo form_error('mobile') ?>
			</div>
			
			<div class=form-group>
				<label for=password>密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fal fa-lock fa-fw"></i></span>
					<input class=form-control name=password type=password <?php if ($this->input->cookie('mobile')) echo 'autofocus '; ?>size=6 pattern="\d{6}" placeholder="请输入6位数字" required>
				</div>
				<?php echo form_error('password') ?>
			</div>

			<div class=form-group>
				<label for=password2>确认密码</label>
				<div class=input-group>
					<span class="input-group-addon"><i class="fal fa-lock fa-fw"></i></span>
					<input class=form-control name=password2 type=password size=6 pattern="\d{6}" placeholder="请再次输入密码" required>
				</div>
				<?php echo form_error('password2') ?>
			</div>
		</fieldset>

		<p class=text-center>点击“注册”，即表示您同意<a href="<?php echo base_url('article/user-agreement') ?>" target=_blank>用户协议</a>。</p>
		<button class="btn btn-primary btn-lg btn-block" type=submit role=button>注册</button>
	</form>
	
	<a class="btn btn-vice btn-lg btn-block" href="<?php echo base_url('login') ?>">登录</a>

	<ul class="list-unstyled hide">
		<li><a href="<?php echo base_url('password_reset') ?>">忘记密码</a></li>
	</ul>
</div>
