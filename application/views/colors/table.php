<?php
if (isset($color_list) && count($color_list) > 0)
{
?>
<!-- Color List Start -->
<table class="table table-striped table-bordered">
	<tr>
		<th>Preview</th>
		<th>Color Settings</th>
<?php if (!isset($hide_app_col) || !$hide_app_col): ?>
		<th>Application</th>
<?php endif; ?>
<?php if (!isset($hide_user_col) || !$hide_user_col): ?>
		<th>User</th>
<?php endif; ?>
		<th>&nbsp;</th>
	</tr>
<?php
	foreach($color_list as $color)
	{
?>
<!-- Color Start -->
	<tr>
		<td>
			<!-- Color Rendering / Hex Code -->
			<img src="<?php echo site_url('render/preview/' . $color->id); ?>" alt="Preview" title="Preview" class="preview-img" />
		</td>
		<td>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Background:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_bg()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Foreground:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_fg()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Glow:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_gl()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Background:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_status_bg()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Foreground:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_status_fg()); ?></p>
		</td>
<?php if (!isset($hide_app_col) || !$hide_app_col): ?>
		<td>
			<!-- Application -->
			<a href="<?php echo site_url('applications/view/' . $color->appid); ?>" title="<?php echo $color->app_package; ?>"><?php echo $color->app_name; ?></a>
		</td>
<?php endif; ?>
<?php if (!isset($hide_user_col) || !$hide_user_col): ?>
		<td>
			<!-- User Name -->
			<?php echo isset($color->username) ? $color->username : "<i>None</i>"; ?>
		</td>
<?php endif; ?>
		<td>
			<!-- Tools Buttons -->
			<p><a href="<?php echo site_url('colors/edit/' . $color->id); ?>" class="btn"><i class="icon-edit"></i>&nbsp;Edit</a><p>
			<p><a href="<?php echo site_url('colors/delete/' . $color->id); ?>" class="btn btn-danger"><i class="icon-trash icon-white"></i>&nbsp;Delete</a>
		</td>
	</tr>
<!-- Color End->
<?php
	}
?>
<!-- Color List End -->
</table>
<?php
}
else
{
?>
<!-- No Colors -->
<div class="alert alert-block alert-error">
<h4>Achtung!</h4>
<p>There were no color settings that matched that query.</p>
</div>
<?php
}
?>
