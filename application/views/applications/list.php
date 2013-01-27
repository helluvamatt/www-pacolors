<h1>Applications</h1>
<?php
if (isset($application_list) && count($application_list) > 0)
{
?>
<!-- Application List Start -->
<table class="table table-striped table-bordered">
	<tr>
		<th>Name</th>
		<th>Package</th>
		<th>Colors</th>
		<th>&nbsp;</th>
	</tr>
<?php
	foreach($application_list as $app)
	{
?>
<!-- App Start -->
	<tr>
		<td><a href="<?php echo site_url('applications/view/' . $app->id); ?>"><?php echo $app->display_name; ?></a></td>
		<td><?php echo $app->package_name; ?></td>
		<td><?php echo $app->cs_count; ?></td>
		<td><!-- Tools Buttons --></td>
	</tr>
<!-- App End->
<?php
	}
?>
<!-- Application List End -->
</table>
<?php
}
else
{
?>
<!-- No Applications -->
<div class="alert alert-block alert-error">
<h4>Achtung!</h4>
<p>There were no applications that matched that query.</p>
</div>
<?php
}
?>
