<?php

/**
 *  Event calendar plugin
 *
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008-2011
 * @link http://radagast.biz/
 */


elgg_register_event_handler('init','system','event_calendar_init');

function event_calendar_init() {

	elgg_register_library('elgg:event_calendar', elgg_get_plugins_path() . 'event_calendar/models/model.php');
	
	elgg_register_plugin_hook_handler('cron', 'fiveminute', 'event_calendar_handle_reminders_cron',400);
		
	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('event_calendar','event_calendar_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'event_calendar','event_calendar_url');

	// Register granular notification for this type
	register_notification_object('object', 'event_calendar', elgg_echo('event_calendar:new_event'));
		
	// Set up site menu
	$site_calendar = elgg_get_plugin_setting('site_calendar', 'event_calendar');
	if (!$site_calendar || $site_calendar != 'no') {
		// add a site navigation item
/*		elgg_register_menu_item('site', array(
			'name' => 'event_calendar',
			'href' => 'event_calendar/list',
			'text' => '&#59393;',
			'title' => elgg_echo('item:object:event_calendar'),
		));
*/
	}
	// make event calendar title and description searchable
	elgg_register_entity_type('object','event_calendar');

	// make legacy tags searchable
	if (function_exists('elgg_register_tag_metadata_name')) {
		elgg_register_tag_metadata_name('event_tags');
	}
	
	// register the plugin's JavaScript
	$plugin_js = elgg_get_simplecache_url('js', 'event_calendar/event_calendar');
	elgg_register_js('elgg.event_calendar', $plugin_js);

	//add to group profile page
	// TODO - are the left and right values still relevant for Elgg 1.8?
	$group_calendar = elgg_get_plugin_setting('group_calendar', 'event_calendar');
	if (!$group_calendar || $group_calendar != 'no') {
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'event_calendar_owner_block_menu');
		$group_profile_display = elgg_get_plugin_setting('group_profile_display', 'event_calendar');
		if (!$group_profile_display || $group_profile_display == 'right') {
			//elgg_extend_view('groups/right_column', 'event_calendar/groupprofile_calendar');
			elgg_extend_view('groups/tool_latest', 'event_calendar/group_module');
		} else if ($group_profile_display == 'left') {
			elgg_extend_view('groups/tool_latest', 'event_calendar/group_module');
			//elgg_extend_view('groups/left_column', 'event_calendar/groupprofile_calendar');
		}
	}

	//add to the css
	elgg_extend_view('css/elgg', 'event_calendar/css');
	
	$event_calendar_listing_format = elgg_get_plugin_setting('listing_format', 'event_calendar');
	if (elgg_is_active_plugin('event_poll') || ($event_calendar_listing_format == 'full')) {
		elgg_extend_view('css/elgg', 'fullcalendar/css');
		$plugin_js = elgg_get_simplecache_url('js', 'event_calendar/fullcalendar');
		elgg_register_js('elgg.full_calendar', $plugin_js);
	}

	//add a widget
	elgg_register_widget_type('event_calendar',elgg_echo("event_calendar:widget_title"),elgg_echo('event_calendar:widget:description'), 'all,groups');

	// add the event calendar group tool option
	$event_calendar_group_default = elgg_get_plugin_setting('group_default', 'event_calendar');
	if (!$event_calendar_group_default || ($event_calendar_group_default == 'yes')) {
		add_group_tool_option('event_calendar',elgg_echo('event_calendar:enable_event_calendar'),true);
	} else {
		add_group_tool_option('event_calendar',elgg_echo('event_calendar:enable_event_calendar'),false);
	}

	// if autogroup is set, listen and respond to join/leave events
	if (elgg_get_plugin_setting('autogroup', 'event_calendar') == 'yes') {
		elgg_register_event_handler('join', 'group', 'event_calendar_handle_join');
		elgg_register_event_handler('leave', 'group', 'event_calendar_handle_leave');
	}
	
	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'event_calendar_entity_menu_setup');
	elgg_register_plugin_hook_handler('prepare', 'menu:entity', 'event_calendar_entity_menu_prepare');

	// register actions
	$action_path = elgg_get_plugins_path() . 'event_calendar/actions/event_calendar';

	elgg_register_action("event_calendar/edit","$action_path/edit.php");
	elgg_register_action("event_calendar/delete","$action_path/delete.php");
	elgg_register_action("event_calendar/add_personal","$action_path/add_personal.php");
	elgg_register_action("event_calendar/remove_personal","$action_path/remove_personal.php");
	elgg_register_action("event_calendar/request_personal_calendar","$action_path/request_personal_calendar.php");
	elgg_register_action("event_calendar/toggle_personal_calendar","$action_path/toggle_personal_calendar.php");
	elgg_register_action("event_calendar/killrequest","$action_path/killrequest.php");
	elgg_register_action("event_calendar/addtocalendar","$action_path/addtocalendar.php");
	elgg_register_action("event_calendar/add_to_group","$action_path/add_to_group.php");
	elgg_register_action("event_calendar/remove_from_group","$action_path/remove_from_group.php");
	elgg_register_action("event_calendar/add_to_group_members","$action_path/add_to_group_members.php");
	elgg_register_action("event_calendar/remove_from_group_members","$action_path/remove_from_group_members.php");
	elgg_register_action("event_calendar/manage_subscribers","$action_path/manage_subscribers.php");
	elgg_register_action("event_calendar/modify_full_calendar","$action_path/modify_full_calendar.php");
	elgg_register_action("event_calendar/join_conference","$action_path/join_conference.php");
}

/**
 * Add a menu item to an ownerblock
 */
function event_calendar_owner_block_menu($hook, $type, $return, $params) {
	elgg_load_library('elgg:event_calendar');
	if (elgg_instanceof($params['entity'], 'group')) {
		if (event_calendar_activated_for_group($params['entity'])) {
			$url = "event_calendar/group/{$params['entity']->guid}";
			$item = new ElggMenuItem('event_calendar', elgg_echo('event_calendar:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

function event_calendar_url($entity) {
	$friendly_title = elgg_get_friendly_title($entity->title);
	return "event_calendar/view/{$entity->guid}/$friendly_title";
}

/**
 * Dispatches event calendar pages.
 *
 * URLs take the form of
 *  Site event calendar:			event_calendar/list/<start_date>/<display_mode>/<filter_context>/<region>
 *  Single event:       			event_calendar/view/<event_guid>/<title>
 *  New event:        				event_calendar/add
 *  Edit event:       				event_calendar/edit/<event_guid>
 *  Group event calendar:  			event_calendar/group/<group_guid>/<start_date>/<display_mode>/<filter_context>/<region>
 *  Add group event:   				event_calendar/add/<group_guid>
 *  Review requests:				event_calendar/review_requests/<event_guid>
 *  Display event subscribers:		event_calendar/display_users/<event_guid>
 *  Events for a user's calendar:	event_calendar/owner/<username>/<start_date>/<display_mode>/<filter_context>/<region>
 *
 * Title is ignored
 *
 * @param array $page
 * @return NULL
 */
function event_calendar_page_handler($page) {

	elgg_load_library('elgg:event_calendar');
	$page_type = $page[0];
	switch ($page_type) {
		case 'list':
			if (isset($page[1])) {
				$start_date = $page[1];
				if (isset($page[2])) {
					$display_mode = $page[2];
					if (isset($page[3])) {
						$filter_mode = $page[3];
						if (isset($page[4])) {
							$region = $page[4];
						} else {
							$region = '';
						}
					} else {
						$filter_mode = '';
					}
				} else {
					$display_mode = '';
				}
			} else {
				$start_date = 0;
			}
			echo event_calendar_get_page_content_list($page_type,0,$start_date,$display_mode,$filter_mode,$region);
			break;
		case 'view':
			echo event_calendar_get_page_content_view($page[1]);
			break;
		case 'view_light_box':
			echo event_calendar_get_page_content_view($page[1],TRUE);
			break;
		case 'display_users':
			echo event_calendar_get_page_content_display_users($page[1]);
			break;
		case 'manage_users':
			echo event_calendar_get_page_content_manage_users($page[1]);
			break;
		case 'schedule':
		case 'add':
			if (isset($page[1])) {
				group_gatekeeper();
				$group_guid = $page[1];
			} else {
				gatekeeper();
				$group_guid = 0;
			}
			echo event_calendar_get_page_content_edit($page_type,$group_guid,$page[2]);
			break;
		case 'edit':
			gatekeeper();
			echo event_calendar_get_page_content_edit($page_type, $page[1]);
			break;
		case 'group':
			group_gatekeeper();
			if (isset($page[1])) {
				$group_guid = $page[1];
				if (isset($page[2])) {
					$start_date = $page[2];
					if (isset($page[3])) {
					$display_mode = $page[3];
					if (isset($page[4])) {
						$filter_mode = $page[4];
						if (isset($page[5])) {
							$region = $page[5];
						} else {
							$region = '';
						}
					} else {
						$filter_mode = '';
					}
					} else {
						$display_mode = '';
					}
				} else {
					$start_date = '';
				}
			} else {
				$group_guid = 0;
			}
			echo event_calendar_get_page_content_list($page_type,$group_guid,$start_date,$display_mode,$filter_mode,$region);
			break;
		case 'owner':
			if (isset($page[1])) {
				$username = $page[1];
				$user = get_user_by_username($username);
				$user_guid = $user->guid;
				if (isset($page[2])) {
					$start_date = $page[2];
					if (isset($page[3])) {
					$display_mode = $page[3];
					if (isset($page[4])) {
						$filter_mode = $page[4];
						if (isset($page[5])) {
							$region = $page[5];
						} else {
							$region = '';
						}
					} else {
						$filter_mode = '';
					}
					} else {
						$display_mode = '';
					}
				} else {
					$start_date = '';
				}
			} else {
				$group_guid = 0;
			}
			echo event_calendar_get_page_content_list($page_type,$user_guid,$start_date,$display_mode,$filter_mode,$region);
			break;
		case 'review_requests':
			gatekeeper();
			echo event_calendar_get_page_content_review_requests($page[1]);
			break;
		case 'get_fullcalendar_events':
			echo event_calendar_get_page_content_fullcalendar_events($page[1],$page[2],$page[3],$page[4]);
			break;
		default:
			return FALSE;
	}
	return TRUE;
}

/**
 * Add particular event calendar links/info to entity menu
 */
function event_calendar_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'event_calendar') {
		return $return;
	}
	if (elgg_is_active_plugin('event_poll') && $entity->canEdit() && $entity->schedule_type == 'poll') {
		$options = array(
			'name' => 'schedule',
			'text' => elgg_echo('event_poll:schedule_button'),
			'title' => elgg_echo('event_poll:schedule_button'),
			'href' => 'event_poll/vote/'.$entity->guid,
			'priority' => 150,
		);
		$return[] = ElggMenuItem::factory($options);
	}
	$user_guid = elgg_get_logged_in_user_guid();
	if ($user_guid) {
		$calendar_status = event_calendar_personal_can_manage($entity,$user_guid);
		if ($calendar_status == 'open') {
			if (event_calendar_has_personal_event($entity->guid,$user_guid)) {
				$options = array(
					'name' => 'personal_calendar',
					'text' => elgg_echo('event_calendar:remove_from_the_calendar_menu_text'),
					'title' => elgg_echo('event_calendar:remove_from_my_calendar'),
					'href' => elgg_add_action_tokens_to_url("action/event_calendar/remove_personal?guid={$entity->guid}"),
					'priority' => 150,
				);
				$return[] = ElggMenuItem::factory($options);
			} else {
				if (!event_calendar_is_full($entity->guid) && !event_calendar_has_collision($entity->guid,$user_guid)) {
					$options = array(
						'name' => 'personal_calendar',
						'text' => elgg_echo('event_calendar:add_to_the_calendar_menu_text'),
						'title' => elgg_echo('event_calendar:add_to_my_calendar'),
						'href' => elgg_add_action_tokens_to_url("action/event_calendar/add_personal?guid={$entity->guid}"),
						'priority' => 150,
					);
					$return[] = ElggMenuItem::factory($options);			}
			}
		} else if ($calendar_status == 'closed') {
			if (!event_calendar_has_personal_event($entity->guid,$user_guid) && !check_entity_relationship($user_guid, 'event_calendar_request', $entity->guid)) {
				$options = array(
					'name' => 'personal_calendar',
					'text' => elgg_echo('event_calendar:make_request_title'),
					'title' => elgg_echo('event_calendar:make_request_title'),
					'href' => elgg_add_action_tokens_to_url("action/event_calendar/request_personal_calendar?guid={$entity->guid}"),
					'priority' => 150,
				);
				$return[] = ElggMenuItem::factory($options);
			}		
		}
	}
	
	$count = event_calendar_get_users_for_event($entity->guid,0,0,true);
	if ($count == 1) {
		$calendar_text = elgg_echo('event_calendar:personal_event_calendars_link_one');
	} else {	
		$calendar_text = elgg_echo('event_calendar:personal_event_calendars_link',array($count));
	}
	
	$options = array(
		'name' => 'calendar_listing',
		'text' => $calendar_text,
		'title' => elgg_echo('event_calendar:users_for_event_menu_title'),
		'href' => "event_calendar/display_users/{$entity->guid}",
		'priority' => 150,
	);
	$return[] = ElggMenuItem::factory($options);
	
	/*if (elgg_is_admin_logged_in() && (elgg_get_plugin_setting('allow_featured', 'event_calendar') == 'yes')) {
		if ($event->featured) {
			add_submenu_item(elgg_echo('event_calendar:unfeature'), $CONFIG->url . "action/event_calendar/unfeature?event_id=".$event_id.'&'.event_calendar_security_fields(), 'eventcalendaractions');
		} else {
			add_submenu_item(elgg_echo('event_calendar:feature'), $CONFIG->url . "action/event_calendar/feature?event_id=".$event_id.'&'.event_calendar_security_fields(), 'eventcalendaractions');
		}
	}*/

	return $return;
}

function event_calendar_entity_menu_prepare($hook, $type, $return, $params) {
	// remove access level from listings
	if (elgg_in_context('event_calendar') && !elgg_in_context('event_calendar:view')) {
		$new_return = array();
		if (isset($return['default']) && is_array($return['default'])) {
			foreach($return['default'] AS $item) {
				if ($item->getName() != 'access') {
					$new_return[] = $item;
				}
			}
		}
		$return['default'] = $new_return;
	}	
	
	return $return;
}

function event_calendar_handle_join($event, $object_type, $object) {
	elgg_load_library('elgg:event_calendar');
	$group = $object['group'];
	$user = $object['user'];
	$user_guid = $user->getGUID();
	$events = event_calendar_get_events_for_group($group->getGUID());
	foreach ($events as $event) {
		$event_id = $event->getGUID();
		event_calendar_add_personal_event($event_id,$user_guid);
	}
}

function event_calendar_handle_leave($event, $object_type, $object) {
	elgg_load_library('elgg:event_calendar');
	$group = $object['group'];
	$user = $object['user'];
	$user_guid = $user->getGUID();
	$events = event_calendar_get_events_for_group($group->getGUID());
	foreach ($events as $event) {
		$event_id = $event->getGUID();
		event_calendar_remove_personal_event($event_id,$user_guid);
	}
}

function event_calendar_handle_reminders_cron() {
	elgg_load_library('elgg:event_calendar');
	event_calendar_queue_reminders();
}
