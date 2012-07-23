<?php
/**
 * List of unvalidated users
 */

echo elgg_view_form('uservalidationbyadmin/bulk_action', array(
	'id' => 'uservalidationbyadmin-form',
	'action' => 'action/uservalidationbyadmin/bulk_action'
));
