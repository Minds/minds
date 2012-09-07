<?php 
$event = $vars['entity'];
if ($event->organizer) {
	$organizer = "\nORGANIZER;CN={$event->organizer}\n";
} else {
	$organizer = '';
}

if ($event->description) {
	// make sure that we are using Unix line endings
	$description = str_replace("\r\n","\n",$event->description);
	$description = str_replace("\r","\n",$description);
	
	// now convert to icalendar format
	$description = str_replace("\n",'\n',$description);
	$description = wordwrap($description,75,"\r\n ",TRUE);
} else {
	$description = '';
}
?>
BEGIN:VEVENT
UID:<?php echo elgg_get_site_url().'event_calendar/view/'.$event->guid; ?>

URL:<?php echo elgg_get_site_url().'event_calendar/view/'.$event->guid; ?>

DTSTAMP:<?php echo date("Ymd\THis\Z", $event->getTimeUpdated())?>

CREATED:<?php echo date("Ymd\THis\Z", $event->getTimeCreated())?>

LAST-MODIFIED:<?php echo date("Ymd\THis\Z", $event->getTimeUpdated())  ?>

DTSTART;VALUE=DATE:<?php echo date("Ymd\THis\Z", $event->start_date);  ?>

DTEND;VALUE=DATE:<?php echo date("Ymd\THis\Z", $event->real_end_time);  ?>

SUMMARY:<?php echo $event->title;  ?>

DESCRIPTION:<?php echo $description;  ?>

LOCATION:<?php echo $event->venue;  ?><?php echo $organizer;  ?>

CATEGORIES:<?php implode(",",$event->tags);  ?>

END:VEVENT
