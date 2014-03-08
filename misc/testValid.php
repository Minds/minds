<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

elgg_set_ignore_access();
$user = get_user_by_username('mark');
$guid = $user->guid;
// only validate if not validated
	$is_validated = elgg_get_user_validation_status($guid);
	$validate_success = elgg_set_user_validation_status($guid, TRUE, 'manual');
var_dump($guid,$is_validated, $validated_success); 
	if ($is_validated !== FALSE || !($validate_success && $user->enable())) {
		$error = TRUE;
	echo 'an error';
	}
