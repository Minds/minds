<?php 

	$event = $vars["entity"];
	$register_type = $vars["register_type"];
	
	if(!empty($event) && ($event instanceof Event))
	{
		if($event->with_program)
		{
			if($eventDays = $event->getEventDays())
			{
				foreach($eventDays as $key => $day)
				{
					if($key == 0 )
					{
						// select the first
						$selected = true;
						$tabtitles .= "<li class='elgg-state-selected'>";
					} 
					else 
					{
						$selected = false;
						$tabtitles .= "<li>";
					}
					
					$tabtitles .= "<a href='javascript:void(0);' rel='day_" . $day->getGUID() . "'>" . date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $day->date) . "</a>";
					$tabtitles .= "</li>";
					
					$tabcontent .= elgg_view("event_manager/program/elements/day", array("entity" => $day, "selected" => $selected, 'participate' => true, 'register_type' => $register_type));
				}
			}
			
			$program = '<div id="event_manager_event_view_program">';
			$program .= '<ul class="elgg-tabs elgg-htabs">';
			
			$program .= $tabtitles;
			
			$program .= '</ul>';
			
			$program .= '</div>';
			$program .= elgg_view('input/hidden', array('id' => 'event_manager_program_guids', 'name' => 'program_guids'));
			
			$program .= $tabcontent;
			
			echo elgg_view_module("info", elgg_echo('event_manager:event:progam'), $program);
			
			?>
				<script type='text/javascript'>
					$(document).ready(function()
					{
						$("#event_manager_event_view_program a").live("click", function()
						{
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