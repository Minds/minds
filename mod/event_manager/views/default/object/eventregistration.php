<?php 

if(!$vars['full'])
{
	echo elgg_view("event_manager/registration/viewsmall", $vars);
}
else
{
	echo elgg_view("event_manager/registration/view", $vars);
}