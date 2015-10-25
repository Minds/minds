<?php
/**
 * Ban user admin page
 */

$user = get_entity(get_input('guid'));
echo elgg_view_form('ban/ban', array(), array('user' => $user));
