<?php
/**
 * Widget view of banned users
 */

echo elgg_view('admin/users/ban_list', array(
	'pagination' => false,
	'limit' => $vars['entity']->num_display,
));
