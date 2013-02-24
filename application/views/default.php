<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo "ParanoidAndroid Colors" . ((isset($title) && $title != "") ? (" | " . $title) : "") ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Matt Schneeberger">

		<!-- Le styles -->
		<link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/css/jPicker-1.1.6.css'); ?>" rel="stylesheet" />
		<link href="<?php echo base_url('assets/css/app.css'); ?>" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<!-- Le javascript
		================================================== -->
		<!-- Placed at the beginning, because placing at the end is a fucking stupid idea -->
		<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<?php foreach ($extra_js as $name => $js): ?>
		<script type="text/javascript">
<?php echo "// " . $name; ?>
<?php echo $js; ?>
		</script>
<?php endforeach; ?>
<?php foreach ($extra_js_file as $name => $js_file): ?>
<?php if ($js_file[0] != '/') $js_file = "assets/js/" . $js_file; ?>
		<script type="text/javascript" src="<?php echo base_url($js_file); ?>"></script>
<?php endforeach; ?>

	<!-- Fav and touch icons -->
		<link rel="shortcut icon" href="<?php echo base_url('assets/ico/favicon.png'); ?>">
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="<?php echo site_url(); ?>">PAcolors</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li <?php if ($active == 'applications') echo "class=\"active\""; ?>><?php echo anchor('applications', 'Applications'); ?></li>
							<li <?php if ($active == 'colors') echo "class=\"active\""; ?>><?php echo anchor('colors', 'Colors'); ?></li>
							<li><a href="http://www.paranoid-rom.com/">Parandoid Android</a></li>
						</ul>
						<ul class="nav pull-right">
<?php
if (has_role($role_map, 'sys.manage')):
?>
							<li class="dropdown<?php if (strpos($active, 'manage') !== false) echo " active"; ?>">
								<a href="Javascript:;" class="dropdown-toggle" data-toggle="dropdown">Manage&nbsp;<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li <?php if ($active == 'manage.colors') echo "class=\"active\""; ?>><?php echo anchor('manage/colors', '<i class="icon-tint"></i>&nbsp;Manage Colors'); ?></li>
									<li <?php if ($active == 'manage.applications') echo "class=\"active\""; ?>><?php echo anchor('manage/applications', '<i class="icon-cog"></i>&nbsp;Manage Applications'); ?></li>
<?php
	if (has_role($role_map, 'sys.roles.admin')):
?>
									<li class="divider"></li>
									<li <?php if ($active == 'manage.users') echo "class=\"active\""; ?>><?php echo anchor('manage/users', '<i class="icon-user"></i>&nbsp;Manage Users'); ?></li>
<?php
	endif;
?>
								</ul>
							</li>
<?php
endif;
if (isset($user) && $user !== FALSE):
?>
							<li class="dropdown<?php if (strpos($active, 'user') !== false) echo " active"; ?>">
								<a href="Javascript:;" class="dropdown-toggle" style="padding: 1px 15px 1px; " data-toggle="dropdown">
									<img src="<?php echo "http://www.gravatar.com/avatar/" . md5(strtolower($user->email)) . "?s=32&d=retro"; ?>" class="user-avatar" alt="<?php echo $user->email; ?>">
									<span style="padding: 9px 0px; display: inline-block;"><?php echo $user->get_display_name(); ?>&nbsp;<b class="caret"></b></span>
								</a>
								<ul class="dropdown-menu">
									<li <?php if ($active == 'user.colors') echo "class=\"active\""; ?>><?php echo anchor('user/colors', '<i class="icon-tint"></i>&nbsp;My Colors'); ?></li>
									<li <?php if ($active == 'user.favorites') echo "class=\"active\""; ?>><?php echo anchor('user/favorites', '<i class="icon-star"></i>&nbsp;My Favorites'); ?></li>
									<li class="divider"></li>
									<li><?php echo anchor('user/logout', '<i class="icon-lock"></i>&nbsp;Log Out'); ?></li>
								</ul>
							</li>
<?php
else:
?>
							<li class="dropdown">
								<a href="Javascript:;" class="dropdown-toggle" data-toggle="dropdown">Login&nbsp;<b class="caret"></b></a>
								<ul class="dropdown-menu dropdown-form">
									<li>
										<form name="loginForm" id="login_form" method="post" action="<?php echo site_url('user/login'); ?>" accept-charset="utf-8">
											<input type="hidden" name="redirect" value="<?php echo uri_string(); ?>" />
											<label class="control-label" for="input_username">Username</label>
											<input type="text" id="input_username" name="username" placeholder="Username" />
											<label class="control-label" for="input_password">Password</label>
											<input type="password" id="input_password" name="password" placeholder="Password">
											<button type="submit" class="btn btn-primary pull-right">Login</button>
											<a href="<?php echo site_url('user/signup'); ?>">Sign Up</a>
										</form>
									</li>
								</ul>
							</li>
<?php
endif;
?>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
		<div class="container">

<?php echo $content; ?>

		</div> <!-- /container -->
<?php if (isset($flashdata_message)) { ?>
		<div class="alert alert-block fade in fixedtop alert-<?php echo $flashdata_message->css_class; ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong><?php echo $flashdata_message->message; ?></strong>
		</div>
		<script type="text/javascript">
			$(function() {
				$("div.alert").alert();
				setTimeout(function() {
					$("div.alert.fixedtop").alert('close');
				}, 3000);
			});
		</script>
<?php } ?>
	</body>
</html>