<?php
	function user_support_page_handler($page){
		$result = false;
		
		switch(elgg_extract(0, $page)) {
			case "help":
				$result = true;
				
				include(dirname(dirname(__FILE__)) . "/pages/support_ticket/list.php");
				break;
			case "faq":
				$result = true;
				
				elgg_push_breadcrumb(elgg_echo("user_support:menu:faq"), "user_support/faq");
				
				switch(elgg_extract(1, $page)) {
					case "edit":
						if (!empty($page[2]) && is_numeric($page[2])) {
							set_input("guid", $page[2]);
						}
						include(dirname(dirname(__FILE__)) . "/pages/faq/edit.php");
						break;
					case "group":
						if (!empty($page[2]) && is_numeric($page[2])) {
							elgg_set_page_owner_guid($page[2]);
						}
						include(dirname(dirname(__FILE__)) . "/pages/faq/group.php");
						break;
					case "add":
						if (!empty($page[2]) && is_numeric($page[2])) {
							elgg_set_page_owner_guid($page[2]);
						}
						include(dirname(dirname(__FILE__)) . "/pages/faq/add.php");
						break;
					default:
						if (!empty($page[1]) && is_numeric($page[1])) {
							set_input("guid", $page[1]);
							include(dirname(dirname(__FILE__)) . "/pages/faq/view.php");
						} else {
							include(dirname(dirname(__FILE__)) . "/pages/faq/list.php");
						}
						break;
				}
				break;
			case "support_ticket":
				$result = true;
				
				switch(elgg_extract(1, $page)) {
					case "edit":
						if (!empty($page[2]) && is_numeric($page[2])) {
							set_input("guid", $page[2]);
						}
						include(dirname(dirname(__FILE__)) . "/pages/support_ticket/edit.php");
						break;
					case "archive":
						include(dirname(dirname(__FILE__)) . "/pages/support_ticket/archive.php");
						break;
					case "mine":
						gatekeeper();
						
						$user = elgg_get_logged_in_user_entity();
						
						$url = "user_support/support_ticket/owner/" . $user->username;
						if (!empty($page[2]) && ($page[2] == "archive")) {
							$url .= "/archive";
						}
						register_error(elgg_echo("changebookmark"));
						forward($url);
						break;
					case "owner":
						if (!empty($page[2])) {
							set_input("username", $page[2]);
						}
						
						if (!empty($page[3]) && ($page[3] == "archive")) {
							set_input("status", UserSupportTicket::CLOSED);
						}
						include(dirname(dirname(__FILE__)) . "/pages/support_ticket/owner.php");
						break;
					case "add":
						include(dirname(dirname(__FILE__)) . "/pages/support_ticket/add.php");
						break;
					default:
						if (!empty($page[1]) && is_numeric($page[1])) {
							set_input("guid", $page[1]);
							include(dirname(dirname(__FILE__)) . "/pages/support_ticket/view.php");
						} else {
							include(dirname(dirname(__FILE__)) . "/pages/support_ticket/list.php");
						}
						break;
				}
				break;
			case "help_center":
				$result = true;
				
				include(dirname(dirname(__FILE__)) . "/pages/help_center.php");
				break;
			case "search":
				$result = true;
				
				include(dirname(dirname(__FILE__)) . "/procedures/search.php");
				break;
		}
		
		return $result;
	}