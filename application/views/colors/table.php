<?php
if (isset($color_list) && count($color_list) > 0)
{
?>
<!-- Color List Start -->
<table>
	<tr>
		<th>Application</th>
		<th>User</th>
		<th>Color</th>
		<th>&nbsp;</th>
	</tr>
<?php
	foreach($color_list as $color)
	{
?>
<!-- Color Start -->
	<tr>
		<td><!-- Application Name / Package --></td>
		<td><!-- User Name --></td>
		<td><!-- Color Rendering / Hex Code --></td>
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
