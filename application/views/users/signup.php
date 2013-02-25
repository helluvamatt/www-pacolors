<?php echo form_open('user/signup', array('class' => 'form-horizontal')); ?>
	<h1>Sign Up</h1>
<?php if (validation_errors() != "") { ?>
	<div class="alert alert-block alert-error">
		<h4>Please correct the following issues:</h4>
		<ul>
<?php echo validation_errors('			<li>', '</li>'); ?>
		</ul>
	</div>
<?php } ?>
<?php if (isset($error)) { ?>
	<div class="alert alert-error">
		<?php echo $error; ?>
	</div>
<?php } ?>
	<div class="control-group<?php if (form_error('username') != "") {echo " error";} ?>">
		<label class="control-label" for="inputUsername">Username</label>
		<div class="controls controls-row">
			<input type="text" name="username" id="inputUsername" placeholder="Username" class="span5" value="<?php echo set_value('username'); ?>">
			<span class="help-inline">*</span>
		</div>
	</div>
	<div class="control-group<?php if (form_error('email') != "") {echo " error";} ?>">
		<label class="control-label" for="inputEmail">Email</label>
		<div class="controls controls-row">
			<input type="text" name="email" id="inputEmail" placeholder="Email" class="span5" value="<?php echo set_value('email'); ?>">
			<span class="help-inline">*</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputRealnameFirst">Name</label>
		<div class="controls controls-row">
			<input type="text" name="realname_first" id="inputRealnameFirst" placeholder="First Name" class="span2" value="<?php echo set_value('realname_first'); ?>">
			<input type="text" name="realname_last" id="inputRealnameLast" placeholder="Last Name" class="span3" value="<?php echo set_value('relname_last'); ?>">
		</div>
	</div>
	<div class="control-group<?php if (form_error('password_1') != "") {echo " error";} ?>">
		<label class="control-label" for="inputPassword_1">Password</label>
		<div class="controls controls-row">
			<input type="password" name="password_1" id="inputPassword_1" placeholder="Password" class="span5" value="<?php echo set_value('password_1'); ?>">
			<span class="help-inline">*</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Password Strength</label>
		<div class="controls">
			<div id="passwordStrengthField" style="width: 450px;"></div>
		</div>
	</div>
	<div class="control-group<?php if (form_error('password_2') != "") {echo " error";} ?>">
		<label class="control-label" for="inputPassword_2">Confirm Password</label>
		<div class="controls controls-row">
			<input type="password" name="password_2" id="inputPassword_2" placeholder="Confirm Password" class="span5" value="<?php echo set_value('password_2'); ?>">
			<span class="help-inline">*</span>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Submit</button>
		<a href="<?php echo site_url(); ?>" class="btn">Cancel</a>
	</div>
</form>
<script type="text/javascript" src="<?php echo base_url('assets/js/password-meter.js'); ?>"></script>
<script type="text/javascript">
$('#inputPassword_1').passwordMeter({
	renderTarget: '#passwordStrengthField'
});
</script>
