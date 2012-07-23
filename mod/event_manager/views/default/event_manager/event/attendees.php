<?php 

	global $EVENT_MANAGER_ATTENDING_EVENT;

	$event = $vars["entity"];
	
	$can_edit = $event->canEdit();
	
	$tab_titles = '';
	$tab_content = '';
	
	if($relationships = $event->getRelationships()){
		$i = 0;
		
		if($can_edit){
			$EVENT_MANAGER_ATTENDING_EVENT = $event->getGUID();
		}
		
		// force correct order
		foreach(event_manager_event_get_relationship_options() as $rel)	{
			if(($rel == EVENT_MANAGER_RELATION_ATTENDING) || $event->$rel || ($rel == EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST && $can_edit)){
				if(array_key_exists($rel, $relationships)){
					if($rel == EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST && !$event->waiting_list_enabled){
						continue;
					}
					
					$members = $relationships[$rel];
	
					$tab_titles .= "<li";
					if($i == 0){
						$tab_titles .= " class='elgg-state-selected'";	
					}
					$tab_titles .= "><a href='javascript:void(0);' rel='" . $event->getGUID() . "_relation_". $rel . "'>" . elgg_echo("event_manager:event:relationship:" . $rel) . " (" . count($members) . ")</a></li>";
					
					$tab_content .= "<div";
					if($i == 0){
						$tab_content .= " style='display:block;'";	
					}
					$tab_content .= " class='event_manager_event_view_attendees' id='" . $event->getGUID() . "_relation_". $rel . "'>"; 
					
					foreach($members as $member){
						if($user = get_user($member)){
							$tab_content .= elgg_view_entity_icon($user, "small");
						} elseif($unregistered_member = get_entity($member)) {
							$tab_content .= '<div class="elgg-avatar elgg-avatar-small">';
							
							if($can_edit) {
								// TODO: translate
								$tab_content .= '<span class="elgg-icon elgg-icon-hover-menu " style="display: none;"></span>
												<ul class="elgg-menu elgg-menu-hover">
													<h3>' . $unregistered_member->name . '</h3>
												
													<li>
														<ul class="elgg-menu elgg-menu-hover-actions">
															<!-- <li><a href="' . EVENT_MANAGER_BASEURL . '/registration/view/?guid=' . $event->getGUID() . '&u_g=' . $member . '">' . elgg_echo("event_manager:event:viewregistration") . '</a></li> -->
															<li><a href="' . elgg_add_action_tokens_to_url($vars['url'] . 'action/event_manager/event/rsvp?guid=' . $event->getGUID() . '&user=' . $member . '&type=' . EVENT_MANAGER_RELATION_UNDO) . '">' . elgg_echo("event_manager:event:relationship:kick") . '</a></li>
														</ul>
													</li>	
												</ul>';
							}
							$tab_content .= '
											<a href="#">
												<img style="background: url(' . $vars["url"] . '_graphics/icons/user/defaultsmall.gif) no-repeat scroll 0% 0% transparent;"
													 src="http://dev18.coldtrick.com/_graphics/spacer.gif"
													 alt="' . $unregistered_member->name . '" 
													 title="' . $unregistered_member->name . '" />
											</a>
										</div>';
						}
					}
					
					$tab_content .= "</div>";
					$i++;
				}
			}
		}
		
		$EVENT_MANAGER_ATTENDING_EVENT = false;
		
		if($tab_content){
			
			$attendees = "<div id='event_manager_event_view_attendees'>";
			$attendees .= "<ul class='elgg-tabs elgg-htabs'>" . $tab_titles . "</ul>";
			$attendees .= "</div>";
			$attendees .= $tab_content;
			$attendees .= "<div class='clearfloat'></div>";
			
			echo elgg_view_module("info", elgg_echo('event_manager:event:attendees'), $attendees)
			
			?>
			<script type='text/javascript'>
				$(document).ready(function(){
					$("#event_manager_event_view_attendees a").live("click", function(){
						$(".event_manager_event_view_attendees").hide();
						$("#event_manager_event_view_attendees li").removeClass("elgg-state-selected");
						var selected = $(this).attr("rel");
						$(this).parent().addClass("elgg-state-selected");
						$("#" + selected).show();
					});
				});
			</script>
			<?php
		} 
	}
	