<?php

	function user_support_get_help_for_context($help_context){
		$result = false;
		
		if(!empty($help_context)){
			$options = array(
				"type" => "object",
				"subtype" => UserSupportHelp::SUBTYPE,
				"site_guids" => false,
				"limit" => false,
				"metadata_name_value_pairs" => array("help_context" => $help_context)
			);
			
			//if($help = elgg_get_entities_from_metadata($options)){
				$result = $help[0];
		//	}
		}
		
		return $result;
	}
	
	function user_support_get_help_context($url = ""){
		$result = false;
		
		if(empty($url)){
			$url = current_page_url();
		}
		
		if(!empty($url)){
			if($path = parse_url($url, PHP_URL_PATH)){
				$parts = explode("/", $path);
				
				if(!($page_owner = elgg_get_page_owner_entity())){
					$page_owner = elgg_get_logged_in_user_entity();
				}
				
				$new_parts = array();
				
				foreach($parts as $index => $part){
					if(empty($part)){
						continue;
					} elseif(is_numeric($part) || (!empty($page_owner) && ($page_owner->username == $part))){
						break;
					} else {
						$new_parts[] = $part;
					}
				}
				
				if(!empty($new_parts)){
					$result = implode("/", $new_parts);
				}
			}
		}
		
		return $result;
	}
	
	function user_support_time_created_string(ElggObject $entity){
		$result = false;
		
		if(!empty($entity) && elgg_instanceof($entity, "object", null, "ElggObject")){
			if($date_array = getdate($entity->time_created)){
				$result = elgg_echo("date:month:" . str_pad($date_array["mon"], 2, "0", STR_PAD_LEFT), array($date_array["mday"])) . " " . $date_array["year"];
			}
		}
		
		return $result;
	}
	
	function user_support_find_unique_help_context(){
		static $result;
		
		if(!isset($result)){
			$result = false;
			
			// get all metadata values of help_context
			$options = array(
				"metadata_name" => "help_context",
				"type" => "object",
				"subtypes" => array(UserSupportFAQ::SUBTYPE, UserSupportHelp::SUBTYPE, UserSupportTicket::SUBTYPE),
				"limit" => false
			);
			if($metadata = elgg_get_metadata($options)){
				// make it into an array
				if($filtered = metadata_array_to_values($metadata)){
					//get unique values
					$result = array_unique($filtered);
					natcasesort($result);
				}
			}
		}
		
		return $result;
	}
	
	function user_support_get_admin_notify_users(UserSupportTicket $ticket){
		$result = false;
		
		if (!empty($ticket) && elgg_instanceof($ticket, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
			$support_staff_id = add_metastring("support_staff");
			
			$options = array(
				"type" => "user",
				"limit" => false,
				"site_guids" => false,
				"relationship" => "member_of_site",
				"relationship_guid" => elgg_get_site_entity()->getGUID(),
				"inverse_relationship" => true,
				"joins" => array(
					"JOIN " . get_config("dbprefix") . "private_settings ps ON e.guid = ps.entity_guid",
					"JOIN " . get_config("dbprefix") . "users_entity ue ON e.guid = ue.guid",
					"JOIN " . get_config("dbprefix") . "metadata md ON e.guid = md.entity_guid"
				),
				"wheres" => array(
					"(ps.name = '" . ELGG_PLUGIN_USER_SETTING_PREFIX . "user_support:admin_notify' AND ps.value = 'yes')",
					"(ue.admin = 'yes' OR md.name_id = " . $support_staff_id . ")",
					"(e.guid <> " . $ticket->getOwnerGUID() . ")"
				)
			);
				
			$users = elgg_get_entities_from_relationship($options);
				
			// trigger hook to get more/less users
			$users = elgg_trigger_plugin_hook("admin_notify", "user_support", array("users" => $users, "entity" => $ticket), $users);

			if(!empty($users)){
				if(is_array($users)){
					$result = $users;
				} else {
					$result = array($users);
				}
			}
		}
		
		return $result;
	}
	
	function user_support_staff_gatekeeper($forward = true, $user_guid = 0) {
		$result = false;
		
		$user_guid = $user_guid;
		if (empty($user_guid)) {
			$user_guid = elgg_get_logged_in_user_guid();
		}
		
		if (!empty($user_guid)) {
			if ($user = get_user($user_guid,'user')) {
				if ($user->isAdmin() || $user->support_staff) {
					$result = true;
				}
			}
		}
		
		if (!$result && $forward) {
			register_error(elgg_echo("user_support:staff_gatekeeper"));
			forward(REFERER);
		}
		
		return $result;
	}
	