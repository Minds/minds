<?php
// Cancellation requests
$options = array(
	'type' => 'object',
	'subtype' => 'cancel_account_request',
	'owner_guid' => $userguid,
    'limit'=>100, 
	'count' => true
);

$cancellation_requests = elgg_list_entities($options);

?>

<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('admin:users:cancellations'); ?></h3>
	</div>
	<div class="elgg-body">
		<?php
			echo elgg_view_form('cms_cancel_account/bulk_action', array(
				'id' => 'cms_cancel_account-form',
				'action' => 'action/cms_cancel_account/bulk_action'
			));
		?>		
	</div>
</div>
