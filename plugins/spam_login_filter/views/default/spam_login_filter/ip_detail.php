<?php
$spam_login_filter_ip = (isset($vars['spam_login_filter_ip'])) ? $vars['spam_login_filter_ip'] : NULL;

if (!$spam_login_filter_ip) {
	return;
}

$created = sprintf(elgg_echo('spam_login_filter:admin:ip_date_created'), elgg_view_friendly_time($spam_login_filter_ip->time_created));

$delete = elgg_view('output/confirmlink', array(
	'confirm' => sprintf(elgg_echo('spam_login_filter:admin:confirm_delete_ip'), $spam_login_filter_ip->ip_address),
	'href' => $vars['url'] . "action/spam_login_filter/delete_ip/?spam_login_filter_ip_list[]=$spam_login_filter_ip->guid",
	'text' => elgg_echo('spam_login_filter:admin:delete_ip')
));

if (elgg_is_active_plugin('tracker')){
	$tracker_url = sprintf(elgg_get_plugin_setting('tracker_url', 'tracker'), $spam_login_filter_ip->ip_address);
	// Create tracker link
	$tracker_link = "<a href=\"$tracker_url\" target=\"_blank\" title=\"" . elgg_echo('tracker:moreinfo') . "\" />" . elgg_echo('tracker:info') . "</a>";
}
?>

<tr>
	<td><?php echo $spam_login_filter_ip->ip_address; ?></td>
	<td>&nbsp;-&nbsp;<?php echo $created; ?></td>
	<?php
		if ($tracker_link) {
			echo "<td>&nbsp;-&nbsp;" . $tracker_link . "</td>";
		}
	?>
	<td>&nbsp;-&nbsp;<?php echo "$delete"; ?></td>
</tr>