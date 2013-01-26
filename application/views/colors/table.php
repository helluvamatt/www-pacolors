<?php
if (isset($color_list) && count($color_list) > 0)
{
?>
<!-- Color List Start -->
<table class="table table-striped table-bordered">
	<tr>
		<th>Color</th>
<?php if (!isset($hide_app_col)): ?>
		<th>Application</th>
<?php endif; ?>
		<th>User</th>
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
			<img src="/render/preview/<?php echo $color->id; ?>" alt="Preview" title="Preview" />
		</td>
<?php if (!isset($hide_app_col)): ?>
		<td>
			<!-- Application -->
			<a href="/applications/view/<?php echo $color->appid; ?>" title="<?php echo $color->app_package; ?>"><?php echo $color->app_name; ?></a>
		</td>
<?php endif; ?>
		<td>
			<!-- User Name -->
			<?php echo isset($color->username) ? $color->username : "<i>None</i>"; ?>
		</td>
		<td><!-- Tools Buttons --></td>
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
