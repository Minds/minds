<?php 
	$key = get_input('k');		
	$guid = get_input("guid");
	$user_guid = get_input('u_g', elgg_get_logged_in_user_guid());

	if($guid && ($entity = get_entity($guid))) {	
		if($entity instanceof Event) {
			$event = $entity;
		}
	}
	
	$save_to_pdf_link = '<a href="'.$vars['url'].elgg_add_action_tokens_to_url('/action/event_manager/registration/pdf?k='.md5($event->time_created . get_site_secret() . $user_guid).'&guid='.$guid.'&u_g='.$user_guid).'">'.elgg_echo('event_manager:registration:view:savetopdf').' <img border="0" src="'.$vars['url'].'/mod/event_manager/_graphics/icons/pdf_icon.gif" /></a>';
	
	if(!empty($key)) {
		$tempKey = md5($event->time_created . get_site_secret() . $user_guid);
		
		if($event && ($tempKey == $key) && get_entity($user_guid)) {
			
			$title_text = elgg_echo('event_manager:registration:registrationto')."'".$event->title."'";
			
			$output .= $save_to_pdf_link;

			elgg_set_ignore_access(true);
			
			$output .= elgg_view('event_manager/event/pdf', array('entity' => $event));
			$output .= $event->getRegistrationData($user_guid);
			
			if($event->with_program) {
				$output .= $event->getProgramData($user_guid);
			}

			elgg_set_ignore_access(false);
			
			elgg_push_breadcrumb($event->title, $event->getURL());
			elgg_push_breadcrumb($title_text);
				
			$body = elgg_view_layout('content', array(
						'filter' => '',
						'content' => $output,
						'title' => $title_text,
					));
	
			echo elgg_view_page($title_text, $body);
		
		} else {
			forward(EVENT_MANAGER_BASEURL);
		}
	} else {
		gatekeeper();

		if($event) {
			if($event->canEdit() || ($user_guid == elgg_get_logged_in_user_guid())) {
				$title_text = elgg_echo('event_manager:registration:registrationto')."'".$event->title."'";

				$output .=  $save_to_pdf_link;
				
				$output .= elgg_view('event_manager/event/pdf', array('entity' => $event));

				$output .= $event->getRegistrationData($user_guid);

				if($event->with_program) {
					$output .= $event->getProgramData($user_guid);
				}			

				if($user_guid == elgg_get_logged_in_user_guid()) {
					$output .= '<br /><a style="margin-left: 10px;" href="'.EVENT_MANAGER_BASEURL.'/event/register/'.$event->getGUID().'/event_attending">'.elgg_echo('event_manager:registration:edityourregistration').'</a>';
				}	
				
				elgg_push_breadcrumb($event->title, $event->getURL());
				elgg_push_breadcrumb($title_text);
				
				$body = elgg_view_layout('content', array(
							'filter' => '',
							'content' => $output,
							'title' => $title_text,
						));
		
				echo elgg_view_page($title_text, $body);
			} else {
				forward($event->getURL());
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
			forward(REFERER);
		}
	}