<?php
/**
 * Helper functions
 *
 * @package Elgg.Core.Plugin
 * @subpackage uservalidationbyadmin
 */
/**
 * Get the admin user (returns the first admin user)
 * There is no API in elgg 1.8.5 to get the admin user
*/
function get_admin_user_details(){
return false;
	global $CONFIG;
	$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity as e WHERE ( e.admin = 'yes')"; 
	$info = get_data($query);
	return $info[0];
}	
/**
 * Generate an email activation code.
 *
 * @param int    $user_guid     The guid of the user
 * @param string $email_address Email address
 * @return string
 */
function uservalidationbyadmin_generate_code($user_guid, $email_address) {
	$site_url = elgg_get_site_url();
	// Note I bind to site URL, this is important on multisite!
	return md5($user_guid . $email_address . $site_url . get_site_secret());
}

/**
 * Request user validation email.
 * Send email out to the address and request a confirmation.
 *
 * @param int  $user_guid       The user's GUID
 * @param bool $admin_requested Was it requested by admin
 * @return mixed
 */
function uservalidationbyadmin_request_validation($user_guid, $admin_requested = FALSE) {

	$site = elgg_get_site_entity();
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	
	//notify admin
	if (($user) && ($user instanceof ElggUser)) {
		$admin = get_admin_user_details();
		// Work out validate link
		$code = uservalidationbyadmin_generate_code($user_guid, $user->email);
		$link = "{$site->url}uservalidationbyadmin/confirm?u=$user_guid&c=$code";
		// IP detection
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$geoloc = "http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress=".$ip_address;
		$geotags = get_meta_tags($geoloc);
		$geocountry = $geotags['country'];
		$georegion = $geotags['region'];
		$geocity = $geotags['city'];
		$geocertainty = $geotags['certainty'];
		$geostring = $ip_address." ; ".$geocountry." ; ".$georegion." ; ".$geocity." ; ".$geocertainty;
		
		// Send validation email
		$subject = elgg_echo('email:validate:subject', array($user->name, $site->name));
		$body = elgg_echo('email:validate:body', array($admin->name, $user->name,  $ip_address, $geostring, $link, $site->name, $site->url));
		//$result = notify_user($admin->guid, $site->guid, $subject, $body, NULL, 'email');
		//if ($result && !$admin_requested) {
			system_message(elgg_echo('uservalidationbyadmin:registerok'));
	//	}
		return $result;
	}
	return FALSE;
}

/**
 * Validate a user
 *
 * @param int    $user_guid
 * @param string $code
 * @return bool
 */
function uservalidationbyadmin_validate_email($user_guid, $code) {
	$user = get_entity($user_guid);

	if ($code == uservalidationbyadmin_generate_code($user_guid, $user->email)) {
		return elgg_set_user_validation_status($user_guid, true, 'email');
	}

	return false;
}

