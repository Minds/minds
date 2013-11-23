<?php

	function user_support_entity_menu_hook($hook, $type, $return_value, $params){
		$result = $return_value;
		
		if (!empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);
			
			if (!empty($entity) ){
				if (elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE, "UserSupportTicket")) {
					if (user_support_staff_gatekeeper(false)) {
						if ($entity->getStatus() == UserSupportTicket::OPEN) {
							$result[] = ElggMenuItem::factory(array(
								"name" => "status",
								"text" => elgg_echo("close"),
								"href" => "action/user_support/support_ticket/close?guid=" . $entity->getGUID(),
								"is_action" => true,
								"priority" => 200
							));
						} else {
							$result[] = ElggMenuItem::factory(array(
								"name" => "status",
								"text" => elgg_echo("user_support:reopen"),
								"href" => "action/user_support/support_ticket/reopen?guid=" . $entity->getGUID(),
								"is_action" => true,
								"priority" => 200
							));
						}
					}

					// cleanup some menu items
					foreach ($result as $index => $menu_item) {
						if (($menu_item->getName() == "delete") && !user_support_staff_gatekeeper(false)) {
							unset($result[$index]);
						} elseif (in_array($menu_item->getName(), array("likes", "likes_count"))) {
							unset($result[$index]);
						}
					}
				} elseif (elgg_instanceof($entity, "object", UserSupportHelp::SUBTYPE, "UserSupportHelp")) {
					// cleanup all menu items
					foreach ($result as $index => $menu_item) {
						if ($menu_item->getName() != "delete") {
							unset($result[$index]);
						}
					}
					// user_support_help_edit_form_wrapper
					$result[] = ElggMenuItem::factory(array(
						"name" => "edit",
						"text" => elgg_echo("edit"),
						"href" => "#user_support_help_edit_form_wrapper",
						"rel" => "toggle",
						"priority" => 200
					));
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Adds items to site menu
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $return_value
	 * @param unknown_type $params
	 * @return Ambigous <ElggMenuItem, NULL>
	 */
	function user_support_site_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (elgg_get_plugin_setting("add_faq_site_menu_item", "user_support") != "no") {
			$result[] = ElggMenuItem::factory(array(
				"name" => "faq",
				"text" => elgg_echo("user_support:menu:faq"),
				"href" => "user_support/faq"
			));
		}

		if (elgg_get_plugin_setting("add_help_center_site_menu_item", "user_support") == "yes") {
			$options = array(
				"name" => "help_center",
				"text" => elgg_echo("user_support:button:text"),
				"href" => "user_support/help_center"
			);
			
			if (elgg_get_plugin_setting("show_as_popup", "user_support") != "no") {
				elgg_load_js("lightbox");
				elgg_load_css("lightbox");
				$options["class"] = "elgg-lightbox";
			}
			
			$result[] = ElggMenuItem::factory($options);
		}
		
		if ($user = elgg_get_logged_in_user_entity()) {
			$result[] = ElggMenuItem::factory(array(
				"name" => "support_ticket_mine",
				"text" => elgg_echo("user_support:menu:support_tickets:mine"),
				"href" => "user_support/support_ticket/owner/" . $user->username
			));
		}
		
		return $result;
	}

	/**
	 * Adds items to footer menu
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $return_value
	 * @param unknown_type $params
	 * @return Ambigous <ElggMenuItem, NULL>
	 */
	function user_support_footer_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (elgg_get_plugin_setting("add_faq_footer_menu_item", "user_support") == "yes") {
			$result[] = ElggMenuItem::factory(array(
				"name" => "faq",
				"text" => elgg_echo("user_support:menu:faq"),
				"href" => "user_support/faq"
			));
		}
		
		return $result;
	}

	/**
	 * Adds items to page menu
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $return_value
	 * @param unknown_type $params
	 * @return Ambigous <ElggMenuItem, NULL>
	 */
	function user_support_page_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		$result[] = ElggMenuItem::factory(array(
			"name" => "faq",
			"text" => elgg_echo("user_support:menu:faq"),
			"href" => "user_support/faq",
			"context" => "user_support"
		));
		
		if ($user = elgg_get_logged_in_user_entity()) {
			$result[] = ElggMenuItem::factory(array(
				"name" => "support_ticket_mine",
				"text" => elgg_echo("user_support:menu:support_tickets:mine"),
				"href" => "user_support/support_ticket/owner/" . $user->username,
				"context" => "user_support"
			));
		}
		
		return $result;
	}
	
	/**
	 * Adds items to user support filter
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $return_value
	 * @param unknown_type $params
	 * @return Ambigous <ElggMenuItem, NULL>
	 */
	function user_support_user_support_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if ($user = elgg_get_logged_in_user_entity()) {
			$post_fix = "";
			if ($q = get_input("q")){
				$post_fix = "?q=" . $q;
			}
			
			$result[] = ElggMenuItem::factory(array(
				"name" => "mine",
				"text" => elgg_echo("user_support:menu:support_tickets:mine"),
				"href" => "user_support/support_ticket/owner/" . $user->username . $post_fix
			));

			$result[] = ElggMenuItem::factory(array(
				"name" => "my_archive",
				"text" => elgg_echo("user_support:menu:support_tickets:mine:archive"),
				"href" => "user_support/support_ticket/owner/" . $user->username . "/archive" . $post_fix
			));
			
			if (user_support_staff_gatekeeper(false)) {
				// filter menu
				$result[] = ElggMenuItem::factory(array(
					"name" => "all",
					"text" => elgg_echo("user_support:menu:support_tickets"),
					"href" => "user_support/support_ticket" . $post_fix
				));
				
				$result[] = ElggMenuItem::factory(array(
					"name" => "archive",
					"text" => elgg_echo("user_support:menu:support_tickets:archive"),
					"href" => "user_support/support_ticket/archive" . $post_fix
				));
			}
		}
		
		return $result;
	}
	
	function user_support_owner_block_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (!empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);
			
			if (elgg_instanceof($entity, "group")) {
				if ($entity->faq_enable == "yes") {
					$result[] = ElggMenuItem::factory(array(
						"name" => "faq",
						"text" => elgg_echo("user_support:menu:faq:group"),
						"href" => "user_support/faq/group/" . $entity->getGUID() . "/all"
					));
				}
			} elseif (elgg_instanceof($entity, "user")) {
				if ($entity->getGUID() == elgg_get_logged_in_user_guid()) {
					$result[] = ElggMenuItem::factory(array(
						"name" => "support_ticket_mine",
						"text" => elgg_echo("user_support:menu:support_tickets:mine"),
						"href" => "user_support/support_ticket/owner/" . $entity->username
					));
				}
			}
		}
		
		return $result;
	}
	
	function user_support_title_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (elgg_in_context("faq")) {
			$user = elgg_get_logged_in_user_entity();
			$page_owner = elgg_get_page_owner_entity();
			
			if (!empty($user) && ($user->isAdmin() || (!empty($page_owner) && elgg_instanceof($page_owner, "group") && $page_owner->canEdit()))) {
				$container_guid = elgg_get_site_entity()->getGUID();
				
				if (!empty($page_owner) && elgg_instanceof($page_owner, "group")) {
					$container_guid = $page_owner->getGUID();
				}
				
				$result[] = ElggMenuItem::factory(array(
					"name" => "add",
					"text" => elgg_echo("user_support:menu:faq:create"),
					"href" => "user_support/faq/add/" . $container_guid,
					"class" => "elgg-button elgg-button-action"
				));
			}
		} elseif (elgg_in_context("support_ticket_title")) {
			$user = elgg_get_logged_in_user_entity();
			
			if (!empty($user)) {
				$result[] = ElggMenuItem::factory(array(
					"name" => "add",
					"text" => elgg_echo("user_support:help_center:ask"),
					"href" => "user_support/support_ticket/add",
					"class" => "elgg-button elgg-button-action"
				));
			}
		}
		
		return $result;
	}
	
	function user_support_widget_url_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (!$result && !empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);
			
			if (!empty($entity) && elgg_instanceof($entity, "object", "widget")) {
				$owner = $entity->getOwnerEntity();
				
				switch ($entity->handler) {
					case "faq":
						$owner = $entity->getOwnerEntity();
						$link = "user_support/faq";
						if (elgg_instanceof($owner, "group")) {
							$link .= "/group/" . $owner->getGUID() . "/all";
						}
						
						$result = $link;
						
						break;
					case "support_ticket":
						$link = "user_support/support_ticket/" . $owner->username;
						if ($entity->filter == UserSupportTicket::CLOSED) {
							$link .= "/archive";
						}
						
						$result = $link;
						break;
					case "support_staff":
						$result = "user_support/support_ticket";
						break;
				}
			}
		}
		
		return $result;
	}
	
	function user_support_user_hover_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (($user = elgg_get_logged_in_user_entity()) && $user->isAdmin()) {
			if (!empty($params) && is_array($params)) {
				$entity = elgg_extract("entity", $params);
				
				if ($entity->getGUID() != $user->getGUID()) {
					$text = elgg_echo("user_support:menu_user_hover:make_staff");
					if (user_support_staff_gatekeeper(false, $entity->getGUID())) {
						$text = elgg_echo("user_support:menu_user_hover:remove_staff");
					}
					
					$result[] = ElggMenuItem::factory(array(
						"name" => "user_support_staff",
						"text" => $text,
						"href" => "action/user_support/support_staff?guid=" . $entity->getGUID(),
						"confirm" => elgg_echo("question:areyousure"),
						"section" => "admin"
					));
				}
			}
		}
		
		return $result;
	}
	
	function user_support_comments_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (!empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);
			
			if (!empty($entity) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE)) {
				$result = elgg_view("user_support/support_ticket/comments", $params);
			}
		}
		
		return $result;
	}
	
	function user_support_permissions_check_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if (!$result && !empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);
			$user = elgg_extract("user", $params);
			
			if (!empty($entity) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE)) {
				$result = user_support_staff_gatekeeper(false, $user->getGUID());
			}
		}
		
		return $result;
	}
	
	function user_support_annotation_menu_hook($hook, $type, $return_value, $params) {
		$result = $return_value;
		
		if ($user = elgg_get_logged_in_user_entity()) {
			if (!empty($params) && is_array($params)) {
				$annotation = elgg_extract("annotation", $params);
				
				if (!empty($annotation) && ($annotation instanceof ElggAnnotation)) {
					$entity = $annotation->getEntity();
					
					if (!empty($entity) && elgg_instanceof($entity, "object", UserSupportTicket::SUBTYPE)) {
						if ($user->isAdmin()) {
							$result[] = ElggMenuItem::factory(array(
								"name" => "user_support_promote",
								"text" => elgg_echo("user_support:support_ticket:promote"),
								"href" => "user_support/faq/add/" . elgg_get_site_entity()->getGUID() . "?annotation=" . $annotation->id,
								"is_trusted" => true,
								"priority" => 99
							));
						}
						
						if ($annotation->getOwnerGUID() != $user->getGUID()) {
							foreach ($result as $index => $menu_item) {
								if ($menu_item->getName() == "delete") {
									unset($result[$index]);
								}
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	