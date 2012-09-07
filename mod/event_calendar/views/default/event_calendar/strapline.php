<div class="contentWrapper">
<span class="event_calendar_strapline">
<?php

	$event = $vars['entity'];
                
	$time_updated = $event->time_created;
	$owner_guid = $event->owner_guid;
	$owner = get_entity($owner_guid);

	echo sprintf(elgg_echo('event_calendar:strapline'),
					elgg_view_friendly_time($time_updated),
					"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>"
	);

?>
</span>
</div>