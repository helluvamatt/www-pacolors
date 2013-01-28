<h1>Login</h1>
<form name="loginForm" id="login_form" method="post" action="<?php echo site_url('user/login'); ?>" accept-charset="utf-8" class="form-horizontal">	

<?php echo validation_errors('<div class="alert alert-block alert-error">', '</div>'); ?>

	<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
	<div class="control-group">
		<label class="control-label" for="input_username">Username</label>
		<div class="controls">
			<input type="text" id="input_username" name="username" placeholder="Username" value="<?php echo set_value('username'); ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="input_password">Password</label>
		<div class="controls">
			<input type="password" id="input_password" name="password" placeholder="Password" value="<?php echo set_value('password'); ?>">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Login</button>
		<a href="<?php echo site_url('user/signup'); ?>">Sign Up</a>
	</div>
</form>