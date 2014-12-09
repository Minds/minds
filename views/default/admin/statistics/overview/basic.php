<?php
// Work out number of users
$users_stats = get_number_users();
$total_users = get_number_users(true);

// Get version information
$version = get_version();
$release = get_version(true);
?>
<table class="elgg-table-alt">
	<tr class="odd">
		<td><b><?php echo elgg_echo('admin:statistics:label:numusers'); ?> :</b></td>
		<td> <?php echo $total_users; ?></td>
	</tr>
</table>