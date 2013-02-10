<h1>Manage Colors</h1>
<hr>
<table class="table table-bordered table-condensed">
	<tr>
		<th>Preview</th>
		<th>Color Settings</th>
		<th>Application</th>
		<th>User</th>
		<th>Status</th>
	</tr>
<?php
foreach ($colors as $color) {
?>
	<tr class="color-row <?php echo ($color->is_enabled()) ? "success" : "error"; ?>">
		<td class="span6 data">
<?php if ($color->is_enabled()) { ?>
			<img src="<?php echo site_url('render/preview/' . $color->id); ?>" alt="Preview" title="Preview" class="preview-img" />
<?php } else { ?>
			No Preview Available
<?php } ?>
		</td>
		<td class="span4 data">
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Background:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_bg()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Foreground:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_fg()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Navbar&nbsp;Glow:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_navbar_gl()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Background:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_status_bg()); ?></p>
			<p><b style="display: inline-block; width: 180px;">Status&nbsp;Bar&nbsp;Foreground:</b>&nbsp;<?php echo Color_Object::format_color_string($color->get_color_status_fg()); ?></p>
		</td>
		<td class="data">
<?php if ($color->app_enabled) { ?>
			<span><?php echo $color->app_name; ?></span>
<?php } else { ?>
			<abbr style="color: red; font-weight: bold;" title="This application is disabled."><?php echo $color->app_name; ?></abbr>
<?php } ?>
		</td>
		<td class="data">
<?php if ($color->user_enabled) { ?>
			<span><?php echo $color->user_name; ?></span>
<?php } else { ?>
			<abbr style="color: red; font-weight: bold;" title="This user is disabled."><?php echo $color->user_name; ?></abbr>
<?php } ?>
		</td>
		<td>
<?php
if ($color->app_enabled && $color->user_enabled) {
	if ($color->enabled) {
?>
			<strong><abbr>Enabled</abbr></strong>
			<a href="<?php echo site_url('manage/toggle_color/' . $color->id); ?>" class="btn btn-mini btn-danger"><i class="icon-ban-circle icon-white"></i>&nbsp;Disable</a>
<?php
	} else {
?>
			<strong><abbr title="This color is hidden.">Disabled</abbr></strong>
			<a href="<?php echo site_url('manage/toggle_color/' . $color->id); ?>" class="btn btn-mini btn-success"><i class="icon-plus icon-white"></i>&nbsp;Enable</a>
<?php
	}
} else {
	if ($color->app_enabled == false) {
?>
			<strong><abbr title="This color setting is hidden, because it's application is disabled.">App Disabled</abbr></strong>
<?php
	}
	if ($color->user_enabled == false) {
?>
			<strong><abbr title="This color setting is hidden, because it's owner is disabled.">Owner Disabled</abbr></strong>
<?php	
	}
}
?>
			
		</td>
	</tr>
<?php
}
?>
</table>
<script type="text/javascript">
$('abbr[title]').tooltip();
</script>