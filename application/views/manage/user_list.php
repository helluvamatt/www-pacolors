<h1>Manage Users</h1>
<hr>
<table class="table table-bordered table-condensed">
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Display Name</th>
		<th>Status</th>
	</tr>
<?php
foreach ($users as $user) {
?>
	<tr class="user-row <?php echo $user->enabled ? "success" : "error"; ?>">
		<td class="data"><?php echo $user->username; ?></td>
		<td class="data"><?php echo $user->email; ?></td>
		<td class="data"><?php echo $user->get_display_name(); ?></td>
		<td>
<?php if ($user->enabled) { ?>
			<strong><abbr>Enabled</abbr></strong>
			<a href="<?php echo site_url('manage/toggle_user/' . $user->id); ?>" class="btn btn-mini btn-danger"><i class="icon-ban-circle icon-white"></i>&nbsp;Disable</a>
<?php } else { ?>
			<strong><abbr title="This user cannot log in, and anything created by this user is hidden.">Disabled</abbr></strong>
			<a href="<?php echo site_url('manage/toggle_user/' . $user->id); ?>" class="btn btn-mini btn-success"><i class="icon-plus icon-white"></i>&nbsp;Enable</a>
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