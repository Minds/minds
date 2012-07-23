<?php 
	
	$guid = get_input("guid");
	
	if(!empty($guid) && ($entity = get_entity($guid))){	
		if($entity->getSubtype() == Event::SUBTYPE) {
			$event = $entity;
		}
	}
	
	if($event){		
		elgg_set_page_owner_guid($event->getContainerGUID());
		$page_owner = elgg_get_page_owner_entity();
		if($page_owner instanceof ElggGroup){
			elgg_push_breadcrumb($page_owner->name, "/events/event/list/" . $page_owner->getGUID());
		}
		
		$title_text = $event->title;
		elgg_push_breadcrumb($title_text);
		
		$output = elgg_view_entity($event, array("full_view" => true));
		
		$body = elgg_view_layout('one_sidebar', array(
			'filter' => '',
			'content' => $output,
			'title' => $title_text,
		));
		
		echo elgg_view_page($title_text, $body);
		
	} else {
		register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
		forward(REFERER);
	}