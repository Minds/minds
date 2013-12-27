<?php
/*******************************************************************************
 * spam_login_filter
 *
 * @author RayJ
 ******************************************************************************/

function spam_login_filter_init() {

	$action_path = elgg_get_plugins_path() . "spam_login_filter/actions/spam_login_filter";
	
	elgg_register_plugin_hook_handler("action", "register", "spam_login_filter_verify_action_hook", 999);
	
	elgg_register_plugin_hook_handler('cron', 'daily', 'spam_login_filter_cron');
	
	elgg_register_page_handler('spam_login_filter', 'spam_login_filter_page_handler');
	
	elgg_register_action('spam_login_filter/delete_ip', "$action_path/delete_ip.php", 'admin');
	
	elgg_register_event_handler('pagesetup', 'system', 'spam_login_filter_pagesetup');
	
	if (elgg_get_plugin_setting("use_ip_blacklist_cache") == "yes") {
		elgg_extend_view('forms/register', 'spam_login_filter/register', 100);
	}
	
	// Extend context menu with admin links
	if (elgg_is_admin_logged_in()) {
		if (elgg_is_active_plugin('tracker')) {
			elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'spam_login_filter_hover_menu', 1000);
			elgg_register_action("spam_login_filter/delete", "$action_path/delete.php", "admin");
		}
	}
	
	return true;
}

function spam_login_filter_pagesetup() {
	elgg_register_admin_menu_item('administer', 'manageip', 'administer_utilities');
}

function spam_login_filter_page_handler($page) {
	
	$page = (isset($page[0])) ? $page[0] : FALSE;

	if ($page == 'admin') {
		set_context('admin');
		admin_gatekeeper();
		$content = elgg_view('spam_login_filter/manageip');
		$title = elgg_echo('spam_login_filter:admin:manage_ips');

		$body = elgg_view_layout('two_column_left_sidebar', '', elgg_view_title($title) . $content);

		echo elgg_view_page(elgg_echo('spam_login_filter:admin:manage_ips'), $title . $body);

		return TRUE;
	}

	forward();
}

function spam_login_filter_verify_action_hook($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;
	//Check against stopforumspam and domain blacklist
	
	$email = get_input('email');
	
	if (validateUser($email, $_SERVER['REMOTE_ADDR'])) {		
		return true;
	}
	else {
		return;
		//Check if the ip exists			
		$options = array(
			"type" => "object",
			"subtype" => "spam_login_filter_ip",
			"metadata_names" => "ip_address",
			"metadata_values" => $_SERVER['REMOTE_ADDR'],
			"count" => TRUE
		);
		
		elgg_set_ignore_access(true);
		
		$spam_login_filter_ip_list = elgg_get_entities_from_metadata($options);
				
		if ($spam_login_filter_ip_list == 0) {
			//Create the banned ip
			$ip = new ElggObject();
			$ip->subtype = 'spam_login_filter_ip';
			$ip->access_id = ACCESS_PRIVATE;
			$ip->ip_address = $_SERVER['REMOTE_ADDR'];
			$ip->owner_guid = $CONFIG->site_id;
			$ip->save();
		}
		
		elgg_set_ignore_access(false);

		//return false;
		forward();
	}
}

function spam_login_filter_notify_admin($blockedEmail, $blockedIp, $reason) {
	if (elgg_get_plugin_setting("notify_by_mail") == "yes") {
		//Notify spam tentative to administrator
		global $CONFIG;
		$site = get_entity($CONFIG->site_guid);
		if (($site) && (isset($site->email))) {
			$from = $site->email;
		} else {
			$from = 'noreply@' . get_site_domain($CONFIG->site_guid);
		}

		$message = sprintf(elgg_echo('spam_login_filter:notify_message'), $blockedEmail, $blockedIp, $reason);

		elgg_send_email($from, elgg_get_plugin_setting("notify_mail_address"), elgg_echo('spam_login_filter:notify_subject'), $message);
	}		
}

function validateUser($register_email, $register_ip) {
	global $CONFIG;
	$spammer = false;
	
	//Mail domain blacklist
	if(elgg_get_plugin_setting("use_mail_domain_blacklist") == "yes"){
		$blacklistedMailDomains = preg_split('/\\s+/', customStripTags(elgg_get_plugin_setting("blacklisted_mail_domains")), -1, PREG_SPLIT_NO_EMPTY);
		$mailDomain = explode("@", $register_email);
		
		foreach ($blacklistedMailDomains as $domain) {
			if ($mailDomain[1] == $domain) {
				register_error(elgg_echo('spam_login_filter:access_denied_domain_blacklist'));
				spam_login_filter_notify_admin($register_email, $register_ip, "Internal domain blacklist");
				$spammer = true;
				break;
			}
		}
	}
	
	if ($spammer != true)
	{
		//Mail blacklist
		if(elgg_get_plugin_setting("use_mail_blacklist") == "yes"){
			$blacklistedMails = preg_split('/\\s+/', customStripTags(elgg_get_plugin_setting("blacklisted_mails")), -1, PREG_SPLIT_NO_EMPTY);
			
			foreach ($blacklistedMails as $blacklistedMail) {
				if ($blacklistedMail == $register_email) {
					register_error(elgg_echo('spam_login_filter:access_denied_mail_blacklist'));
					spam_login_filter_notify_admin($register_email, $register_ip, "Internal e-mail blacklist");
					$spammer = true;
					break;
				}
			}
		}
	}
	
	if ($spammer != true)
	{
		//StopForumSpam
		if(elgg_get_plugin_setting("use_stopforumspam") == "yes"){

			//check the e-mail adress
			$url = "http://www.stopforumspam.com/api?email=".$register_email."&f=serial";
			
			$return = file_get_conditional_contents($url);
			
			if ($return != false) {
				$data = unserialize($return);
				$email_frequency = $data[email][frequency];
					if($email_frequency != '0'){
						register_error(elgg_echo('spam_login_filter:access_denied_mail_blacklist'));
						spam_login_filter_notify_admin($register_email, $register_ip, "Stopforumspam e-mail blacklist");
						$spammer = true;
					}
			}

			if($spammer != true){
			//e-mail not found in the database, now check the ip
				$url = "http://www.stopforumspam.com/api?ip=".$register_ip."&f=serial";
				
				$return = file_get_conditional_contents($url);
				
				if ($return != false) {
					$data = unserialize($return);
					$ip_frequency = $data[ip][frequency];
						if($ip_frequency != '0'){
							register_error(elgg_echo('spam_login_filter:access_denied_ip_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Stopforumspam IP blacklist");
							$spammer = true;
						}
				}
			}
			
		}
	}
	
	if ($spammer != true){
		//Fassim
		if(elgg_get_plugin_setting("use_fassim") == "yes"){
			$fassim_api_key = elgg_get_plugin_setting("fassim_api_key");
			$fassim_check_email = elgg_get_plugin_setting("fassim_check_email");
			$fassim_check_ip = elgg_get_plugin_setting("fassim_check_ip");
			$fassim_block_proxies = elgg_get_plugin_setting("fassim_block_proxies");			
			$fassim_block_top_spamming_isps = elgg_get_plugin_setting("fassim_block_top_spamming_isps");
			$fassim_block_top_spamming_domains = elgg_get_plugin_setting("fassim_block_top_spamming_domains");			
			$fassim_blocked_country_list = elgg_get_plugin_setting("fassim_blocked_country_list");
			$fassim_blocked_region_list = elgg_get_plugin_setting("fassim_blocked_region_list");
			
			if (!empty($fassim_api_key) && preg_match('/^[0-9a-z]{8}(-[0-9a-z]{4}){3}-[0-9a-z]{12}$/i', $fassim_api_key)) {

				$url = 'http://api.fassim.com/regcheck.php?apikey='.$fassim_api_key.'&email='.$register_email."&ip=".$register_ip.'&proxy='.$fassim_block_proxies.'&topisp='.$fassim_block_top_spamming_isps.'&topdm='.$fassim_block_top_spamming_domains.'&cc='.$fassim_blocked_country_list.'&region='.$fassim_blocked_region_list.'&hostForumVersion=ELGG';
			
				$return = file_get_conditional_contents($url);
				
				if ($return != false) {
					$results = json_decode($return);
					
					if ($results != NULL){
						if ( $fassim_check_email == 1 && isset($results->email_status) && $results->email_status == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_mail_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim e-mail blacklist");
							$spammer = true;
						}
						
						if ( $fassim_check_ip == 1 && isset($results->ip_status) && $results->ip_status == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_ip_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim IP blacklist");
							$spammer = true;
						}
						
						if ( $fassim_block_proxies == 1 && isset($results->proxy) && $results->proxy == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_ip_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim proxy blacklist");
							$spammer = true;
						}
						
						if ( $fassim_block_top_spamming_isps == 1 && isset($results->top_isp) && $results->top_isp == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_ip_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim top ISP blacklist");
							$spammer = true;
						}
						
						if ( $fassim_block_top_spamming_domains == 1 && isset($results->top_domain) && $results->top_domain == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_domain_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim top domains blacklist");
							$spammer = true;
						}
						
						if ( !empty($fassim_blocked_country_list) && isset($results->country_match) && $results->country_match == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_country_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim country blacklist");
							$spammer = true;
						}
						
						if ( !empty($fassim_blocked_region_list) && isset($results->region) && $results->region == true )
						{
							register_error(elgg_echo('spam_login_filter:access_denied_region_blacklist'));
							spam_login_filter_notify_admin($register_email, $register_ip, "Fassim region blacklist");
							$spammer = true;
						}
					}
				}
			}
		}
	}
	
	return !$spammer;
}

function file_get_conditional_contents($szURL)
{
	$pCurl = curl_init($szURL);
	
	curl_setopt($pCurl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($pCurl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($pCurl, CURLOPT_TIMEOUT, 10);

	$szContents = curl_exec($pCurl);
	$aInfo = curl_getinfo($pCurl);
	
	if($aInfo['http_code'] === 200)
	{
		return $szContents;
	}
	
	return false;
}

function spam_login_filter_cron($hook, $entity_type, $returnvalue, $params){
	//Retrieve de the ips older than one week
	$time_to_seek = time() - 604800; //(7 * 24 * 60 * 60);

	$options = array(
		"type" => "object",
		"subtype" => "spam_login_filter_ip",
		"created_time_upper" => $time_to_seek
	);
	
	elgg_set_ignore_access(true);
	
	$spam_login_filter_ip_list = elgg_get_entities($options);

	if ($spam_login_filter_ip_list) {
		foreach($spam_login_filter_ip_list as $ip_to_exclude){
			$ip_to_exclude->delete();
		}
	}
	elgg_set_ignore_access(false);
}

/**
 * Add delete as spammer link to user hover menu
 */
function spam_login_filter_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	
	if ($user->guid != elgg_get_logged_in_user_guid()) {

		$item = ElggMenuItem::factory(array(
			'name' => "spam_login_filter_delete",
			'href' => "action/spam_login_filter/delete?guid={$user->guid}",
			'text' => elgg_echo("spam_login_filter:delete_and_report"),
			'is_action' => true,
			'section' => 'admin',
		));	
		$return[] = $item;
	}
	
	return $return;
}

function customStripTags($content) {
	$searchSpaces = array(' ', '&nbsp;');
	$content = str_replace($searchSpaces, '', $content);
	$content = strip_tags($content);
	return $content;
}

elgg_register_event_handler('init', 'system', 'spam_login_filter_init');
