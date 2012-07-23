<?php 
/*$lat = 52.1879034;
$lng = 6.9043385;
$radius = 2*/


$lat 	= get_input('lat', 52.1879034);
$lng 	= get_input('lng', 6.9043385);
$radius = get_input('radius', 2);


$returnData = array();
$returnData['valid'] = 0;

$entities = get_entities_from_viewport($lat, $lng, ($radius*KILOMETER), 'object', 'event', 20);

foreach($entities as $event) {
	$eventBox = '<div class="gmaps_infowindow">'.PHP_EOL.
					'<div class="gmaps_infowindow_text">'.PHP_EOL.
						'<div class="event_manager_event_view_owner"><a href="'.$event->getURL().'">'.$event->title.'</a> ('.date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $event->start_day).')</div>'.PHP_EOL.
						$event->getLocation(true).'<br /><br />'.$event->shortdescription.'<br />'.elgg_view("event_manager/event/action", array('entity' => $event)).'</div>'.PHP_EOL.
					'<div class="gmaps_infowindow_icon"><img src="'.$event->getIcon('medium').'" /></div>'.PHP_EOL.
				'</div>';
						
	$returnData['markers'][] = array(	'lat' => $event->getLatitude(), 
										'lng' => $event->getLongitude(), 
										'title' => $event->title, 
										'html' => $eventBox,
										'hasrelation' => $event->getRelationshipByUser(),
										'iscreator' => (($event->getOwner() == elgg_get_logged_in_user_guid())?'owner':null)
										);
}

$returnData['valid'] = 1;

echo json_encode($returnData);
exit;