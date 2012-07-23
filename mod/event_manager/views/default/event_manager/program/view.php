<?php 

	$event = $vars["entity"];
	$tabtitles = '';
	$tabcontent = '';
	
	if(!empty($event) && ($event instanceof Event)){
		if($event->with_program){
			if($eventDays = $event->getEventDays()){
				foreach($eventDays as $key => $day){
						
					if($key == 0 ){
						$selected = true;
						$tabtitles .= "<li class='elgg-state-selected'>";
					} else {
						$selected = false;
						$tabtitles .= "<li>";
					}
					
					$tabtitles .= "<a href='javascript:void(0);' rel='day_" . $day->getGUID() . "'>" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $day->date) . "</a>";
					$tabtitles .= "</li>";
					
					$tabcontent .= elgg_view("event_manager/program/elements/day", array("entity" => $day, "selected" => $selected, "member" => $vars["member"]));
					
				}
			}
			
			// make program
			$program = '<div id="event_manager_event_view_program">';
			$program .= '<ul class="elgg-tabs elgg-htabs">';
			
			$program .= $tabtitles;
			
			if($event->canEdit() && !elgg_in_context('programmailview')){
				$program .= '<li><a href="javascript:void(0);" rel="' . $event->getGUID() . '" class="event_manager_program_day_add">' . elgg_echo("event_manager:program:day:add") . "</a></li>";
			}
			
			$program .= '</ul>';
			$program .= '</div>';
			
			$program .= $tabcontent;
			
			echo elgg_view_module("info", elgg_echo('event_manager:event:progam'), $program);
			
			?>
				<script type='text/javascript'>
					$(document).ready(function(){
						$("#event_manager_event_view_program a").live("click", function(){
							$(".event_manager_program_day").hide();
							$("#event_manager_event_view_program li").removeClass("elgg-state-selected");
							var selected = $(this).attr("rel");
							$(this).parent().addClass("elgg-state-selected");
							$("#" + selected).show();
						});
					});
				</script>
			<?php 
		}
	}	