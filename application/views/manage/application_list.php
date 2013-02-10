<h1>Manage Applications</h1>
<hr>
<table class="table table-bordered table-condensed">
	<tr>
		<th>Application</th>
		<th>Package Name</th>
		<th>Colors</th>
		<th>Status</th>
	</tr>
<?php
foreach ($applications as $application) {
?>
	<tr class="application-row <?php echo $application->enabled ? "success" : "error"; ?>">
		<td class="data"><?php echo $application->display_name; ?></td>
		<td class="data"><?php echo $application->package_name; ?></td>
		<td class="data"><?php echo $application->cs_count . ' ' . ($application->cs_count == 1 ? 'color' : 'colors'); ?></td>
		<td>
<?php if ($application->enabled) { ?>
			<strong><abbr>Enabled</abbr></strong>
			<a href="<?php echo site_url('manage/toggle_application/' . $application->id); ?>" class="btn btn-mini btn-danger"><i class="icon-ban-circle icon-white"></i>&nbsp;Disable</a>
<?php } else { ?>
			<strong><abbr title="This application is hidden, and any colors using it are hidden.">Disabled</abbr></strong>
			<a href="<?php echo site_url('manage/toggle_application/' . $application->id); ?>" class="btn btn-mini btn-success"><i class="icon-plus icon-white"></i>&nbsp;Enable</a>
<?php } ?>
			
		</td>
	</tr>
<?php
}
?>
</table>
<script type="text/javascript">
$('abbr[title]').tooltip();
</script>