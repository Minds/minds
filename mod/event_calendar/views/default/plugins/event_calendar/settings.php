<?php
$yn_options = array(elgg_echo('event_calendar:settings:yes')=>'yes',
	elgg_echo('event_calendar:settings:no')=>'no',
);

$time_format_options = array(elgg_echo('event_calendar:time_format:12hour')=>'12',elgg_echo('event_calendar:time_format:24hour')=>'24');

$membership_options = array(
	elgg_echo('event_calendar:personal_manage:open') => 'open' ,
	elgg_echo('event_calendar:personal_manage:closed') => 'closed',
	elgg_echo('event_calendar:personal_manage:private') => 'private',
	elgg_echo('event_calendar:personal_manage:by_event') => 'by_event',
);

$access_options = array(	ACCESS_PRIVATE => elgg_echo("PRIVATE"),
								ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
								ACCESS_PUBLIC => elgg_echo("PUBLIC"));

$listing_options = array(elgg_echo('event_calendar:settings:paged')=>'paged',
	elgg_echo('event_calendar:settings:agenda')=>'agenda',
	elgg_echo('event_calendar:settings:month')=>'month',
	elgg_echo('event_calendar:settings:full')=>'full',
);

$body = '';

/*$event_calendar_full_calendar = elgg_get_plugin_setting('full_calendar', 'event_calendar');
if (!$event_calendar_full_calendar) {
	$event_calendar_full_calendar = 'no';
}

$body .= elgg_echo('event_calendar:settings:full_calendar:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[full_calendar]','value'=>$event_calendar_full_calendar,'options'=>$yn_options));

$body .= '<br />';*/

$event_calendar_hide_access = elgg_get_plugin_setting('hide_access', 'event_calendar');
if (!$event_calendar_hide_access) {
	$event_calendar_hide_access = 'no';
}

$body .= elgg_echo('event_calendar:settings:hide_access:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[hide_access]','value'=>$event_calendar_hide_access,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_default_access = elgg_get_plugin_setting('default_access', 'event_calendar');
if (!$event_calendar_default_access) {
	$event_calendar_default_access = ACCESS_LOGGED_IN;
}

$body .= elgg_echo('event_calendar:settings:default_access:title');
$body .= '<br />';
$body .= elgg_view('input/dropdown',array('name'=>'params[default_access]','value'=>$event_calendar_default_access,'options_values'=>$access_options));

$body .= '<br /><br />';

$event_calendar_hide_end = elgg_get_plugin_setting('hide_end', 'event_calendar');
if (!$event_calendar_hide_end) {
	$event_calendar_hide_end = 'no';
}

$body .= elgg_echo('event_calendar:settings:hide_end:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[hide_end]','value'=>$event_calendar_hide_end,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_listing_format = elgg_get_plugin_setting('listing_format', 'event_calendar');
if (!$event_calendar_listing_format) {
	$event_calendar_listing_format = 'month';
}

$body .= elgg_echo('event_calendar:settings:listing_format:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[listing_format]','value'=>$event_calendar_listing_format,'options'=>$listing_options));

$body .= '<br />';

$event_calendar_repeated_events = elgg_get_plugin_setting('repeated_events', 'event_calendar');
if (!$event_calendar_repeated_events) {
	$event_calendar_repeated_events = 'no';
}

$body .= elgg_echo('event_calendar:settings:repeated_events:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[repeated_events]','value'=>$event_calendar_repeated_events,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_reminders = elgg_get_plugin_setting('reminders', 'event_calendar');
if (!$event_calendar_reminders) {
	$event_calendar_reminders = 'no';
}

$body .= elgg_echo('event_calendar:settings:reminders:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[reminders]','value'=>$event_calendar_reminders,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_times = elgg_get_plugin_setting('times', 'event_calendar');
if (!$event_calendar_times) {
	$event_calendar_times = 'yes';
}

$body .= elgg_echo('event_calendar:settings:times:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[times]','value'=>$event_calendar_times,'options'=>$yn_options));

$body .= '<br />';

/*$event_calendar_restricted_times = elgg_get_plugin_setting('restricted_times', 'event_calendar');
if (!$event_calendar_restricted_times) {
	$event_calendar_restricted_times = 'no';
}

$body .= elgg_echo('event_calendar:settings:restricted_times:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[restricted_times]','value'=>$event_calendar_restricted_times,'options'=>$yn_options));

$body .= '<br />';*/

$event_calendar_time_format = elgg_get_plugin_setting('timeformat', 'event_calendar');
if (!$event_calendar_time_format) {
	$event_calendar_time_format = '24';
}

$body .= elgg_echo('event_calendar:settings:timeformat:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[timeformat]','value'=>$event_calendar_time_format,'options'=>$time_format_options));

$body .= '<br />';

$event_calendar_autopersonal = elgg_get_plugin_setting('autopersonal', 'event_calendar');
if (!$event_calendar_autopersonal) {
	$event_calendar_autopersonal = 'yes';
}

$body .= elgg_echo('event_calendar:settings:autopersonal:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[autopersonal]','value'=>$event_calendar_autopersonal,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_autogroup = elgg_get_plugin_setting('autogroup', 'event_calendar');
if (!$event_calendar_autogroup) {
	$event_calendar_autogroup = 'no';
}

$body .= elgg_echo('event_calendar:settings:autogroup:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[autogroup]','value'=>$event_calendar_autogroup,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_add_to_group_calendar = elgg_get_plugin_setting('add_to_group_calendar', 'event_calendar');
if (!$event_calendar_add_to_group_calendar) {
	$event_calendar_add_to_group_calendar = 'no';
}

$body .= elgg_echo('event_calendar:settings:add_to_group_calendar:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[add_to_group_calendar]','value'=>$event_calendar_add_to_group_calendar,'options'=>$yn_options));

$body .= '<br />';

// agenda view is now handled in the listing area

/*$event_calendar_agenda_view = get_plugin_setting('agenda_view', 'event_calendar');
if (!$event_calendar_agenda_view) {
	$event_calendar_agenda_view = 'no';
}

$body .= elgg_echo('event_calendar:settings:agenda_view:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[agenda_view]','value'=>$event_calendar_agenda_view,'options'=>$yn_options));

$body .= '<br />';*/

$event_calendar_venue_view = elgg_get_plugin_setting('venue_view', 'event_calendar');
if (!$event_calendar_venue_view) {
	$event_calendar_venue_view = 'no';
}

$body .= elgg_echo('event_calendar:settings:venue_view:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[venue_view]','value'=>$event_calendar_venue_view,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_fewer_fields = elgg_get_plugin_setting('fewer_fields', 'event_calendar');
if (!$event_calendar_fewer_fields) {
	$event_calendar_fewer_fields = 'no';
}

$body .= elgg_echo('event_calendar:settings:fewer_fields:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[fewer_fields]','value'=>$event_calendar_fewer_fields,'options'=>$yn_options));

$body .= '<br />';

$options = array(elgg_echo('event_calendar:settings:no')=>'no',
	elgg_echo('event_calendar:settings:site_calendar:admin')=>'admin',
	elgg_echo('event_calendar:settings:site_calendar:loggedin')=>'loggedin',
);

$event_calendar_site_calendar = elgg_get_plugin_setting('site_calendar', 'event_calendar');
if (!$event_calendar_site_calendar) {
	$event_calendar_site_calendar = 'admin';
}

$body .= elgg_echo('event_calendar:settings:site_calendar:title').'<br />';
$body .= elgg_view('input/radio',array('name'=>'params[site_calendar]','value'=>$event_calendar_site_calendar,'options'=>$options));

$body .= '<br />';

$options = array(elgg_echo('event_calendar:settings:no')=>'no',
	elgg_echo('event_calendar:settings:group_calendar:admin')=>'admin',
	elgg_echo('event_calendar:settings:group_calendar:members')=>'members',
);

$event_calendar_group_calendar = elgg_get_plugin_setting('group_calendar', 'event_calendar');
if (!$event_calendar_group_calendar) {
	$event_calendar_group_calendar = 'members';
}

$body .= elgg_echo('event_calendar:settings:group_calendar:title').'<br />';
$body .= elgg_view('input/radio',array('name'=>'params[group_calendar]','value'=>$event_calendar_group_calendar,'options'=>$options));

$body .= '<br />';

$options = array(elgg_echo('event_calendar:settings:group_default:yes')=>'yes',
	elgg_echo('event_calendar:settings:group_default:no')=>'no',
);

$event_calendar_group_default = elgg_get_plugin_setting('group_default', 'event_calendar');
if (!$event_calendar_group_default) {
	$event_calendar_group_default = 'yes';
}

$body .= elgg_echo('event_calendar:settings:group_default:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[group_default]','value'=>$event_calendar_group_default,'options'=>$options));

$body .= '<br />';

$event_calendar_group_always_display = elgg_get_plugin_setting('group_always_display', 'event_calendar');
if (!$event_calendar_group_always_display) {
	$event_calendar_group_always_display = 'no';
}

$body .= elgg_echo('event_calendar:settings:group_always_display:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[group_always_display]','value'=>$event_calendar_group_always_display,'options'=>$yn_options));

$body .= '<br />';

// There does not seem to be a way to determine where the group calendar box will appear in Elgg 1.8

/*$options = array(elgg_echo('event_calendar:settings:group_profile_display_option:left')=>'left',
	elgg_echo('event_calendar:settings:group_profile_display_option:right')=>'right',
	elgg_echo('event_calendar:settings:group_profile_display_option:none')=>'none',
);

$event_calendar_group_profile_display = elgg_get_plugin_setting('group_profile_display', 'event_calendar');
if (!$event_calendar_group_profile_display) {
	$event_calendar_group_profile_display = 'right';
}

$body .= elgg_echo('event_calendar:settings:group_profile_display:title').'<br />';
$body .= elgg_view('input/radio',array('name'=>'params[group_profile_display]','value'=>$event_calendar_group_profile_display,'options'=>$options));

$body .= '<br />';*/

$event_calendar_add_users = elgg_get_plugin_setting('add_users', 'event_calendar');
if (!$event_calendar_add_users) {
	$event_calendar_add_users = 'no';
}

$body .= elgg_echo('event_calendar:settings:add_users:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[add_users]','value'=>$event_calendar_add_users,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_add_users_notify = elgg_get_plugin_setting('add_users_notify', 'event_calendar');
if (!$event_calendar_add_users_notify) {
	$event_calendar_add_users_notify = 'no';
}

$body .= elgg_echo('event_calendar:settings:add_users_notify:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[add_users_notify]','value'=>$event_calendar_add_users_notify,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_personal_manage = elgg_get_plugin_setting('personal_manage', 'event_calendar');
if (!$event_calendar_personal_manage && $personal_manage == 'yes') {
	$event_calendar_personal_manage = 'open';
}

$body .= elgg_echo('event_calendar:settings:personal_manage:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[personal_manage]','value'=>$event_calendar_personal_manage,'options'=>$membership_options));
$body .= '<p>'.elgg_echo('event_calendar:settings:personal_manage:description').'</p>';

$event_calendar_spots_display = elgg_get_plugin_setting('spots_display', 'event_calendar');
if (!$event_calendar_spots_display) {
	$event_calendar_spots_display = 'no';
}

$body .= elgg_echo('event_calendar:settings:spots_display:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[spots_display]','value'=>$event_calendar_spots_display,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_no_collisions = elgg_get_plugin_setting('no_collisions', 'event_calendar');
if (!$event_calendar_no_collisions) {
	$event_calendar_no_collisions = 'no';
}

$body .= elgg_echo('event_calendar:settings:no_collisions:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[no_collisions]','value'=>$event_calendar_no_collisions,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_collision_length = elgg_get_plugin_setting('collision_length', 'event_calendar');
if (!$event_calendar_collision_length) {
	$event_calendar_collision_length = '3600';
}

$body .= elgg_echo('event_calendar:settings:collision_length:title');
$body .= '<br />';
$body .= elgg_view('input/text',array('name'=>'params[collision_length]','value'=>$event_calendar_collision_length));

$body .= '<br /><br />';

$event_calendar_region_display = elgg_get_plugin_setting('region_display', 'event_calendar');
if (!$event_calendar_region_display) {
	$event_calendar_region_display = 'no';
}

$body .= elgg_echo('event_calendar:settings:region_display:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[region_display]','value'=>$event_calendar_region_display,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_region_list = elgg_get_plugin_setting('region_list', 'event_calendar');
if (!$event_calendar_region_list) {
	$event_calendar_region_list = '';
}

$body .= elgg_echo('event_calendar:settings:region_list:title');
$body .= '<br />';
$body .= elgg_view('input/plaintext',array('name'=>'params[region_list]','value'=>$event_calendar_region_list));

$body .= '<br />';

$event_calendar_region_list_handles = elgg_get_plugin_setting('region_list_handles', 'event_calendar');
if (!$event_calendar_region_list_handles) {
	$event_calendar_region_list_handles = 'no';
}

$body .= elgg_echo('event_calendar:settings:region_list_handles:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[region_list_handles]','value'=>$event_calendar_region_list_handles,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_type_display = elgg_get_plugin_setting('type_display', 'event_calendar');
if (!$event_calendar_type_display) {
	$event_calendar_type_display = 'no';
}

$body .= elgg_echo('event_calendar:settings:type_display:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[type_display]','value'=>$event_calendar_type_display,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_type_list = elgg_get_plugin_setting('type_list', 'event_calendar');
if (!$event_calendar_type_list) {
	$event_calendar_type_list = '';
}

$body .= elgg_echo('event_calendar:settings:type_list:title');
$body .= '<br />';
$body .= elgg_view('input/plaintext',array('name'=>'params[type_list]','value'=>$event_calendar_type_list));

$body .= '<br />';

$event_calendar_type_list_handles = elgg_get_plugin_setting('type_list_handles', 'event_calendar');
if (!$event_calendar_type_list_handles) {
	$event_calendar_type_list_handles = 'no';
}

$body .= elgg_echo('event_calendar:settings:type_list_handles:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[type_list_handles]','value'=>$event_calendar_type_list_handles,'options'=>$yn_options));

$body .= '<br />';

$event_calendar_first_date = elgg_get_plugin_setting('first_date', 'event_calendar');
if (!$event_calendar_first_date) {
	$event_calendar_first_date = '';
}

$body .= elgg_echo('event_calendar:settings:first_date:title');
$body .= '<br />';
$body .= elgg_view('input/text',array('name'=>'params[first_date]','value'=>$event_calendar_first_date));

$body .= '<br /><br />';

$event_calendar_last_date = elgg_get_plugin_setting('last_date', 'event_calendar');
if (!$event_calendar_last_date) {
	$event_calendar_last_date = '';
}

$body .= elgg_echo('event_calendar:settings:last_date:title');
$body .= '<br />';
$body .= elgg_view('input/text',array('name'=>'params[last_date]','value'=>$event_calendar_last_date));

$body .= '<br /><br />';

$event_calendar_more_required = elgg_get_plugin_setting('more_required', 'event_calendar');
if (!$event_calendar_more_required) {
	$event_calendar_more_required = 'no';
}

$body .= elgg_echo('event_calendar:settings:more_required:title');
$body .= '<br />';
$body .= elgg_view('input/radio',array('name'=>'params[more_required]','value'=>$event_calendar_more_required,'options'=>$yn_options));

$body .= '<br />';

$ical_auth_file_name = elgg_get_plugin_setting('ical_auth_file_name', 'event_calendar');

$body .= elgg_echo('event_calendar:ical_auth_file_name:title');
$body .= '<br />';
$body .= elgg_view('input/text',array('name'=>'params[ical_auth_file_name]','value'=>$ical_auth_file_name, 'class'=>'event-calendar-ical-auth-setting'));

$body .= '<br /><br />';

$event_calendar_bbb_server_url = elgg_get_plugin_setting('bbb_server_url', 'event_calendar');

$body .= elgg_echo('event_calendar:bbb_server_url');
$body .= '<br />';
$body .= elgg_view('input/text', array(
	'name' => 'params[bbb_server_url]',
	'value' => $event_calendar_bbb_server_url,
	'class' => 'text_input',
));

$body .= '<br /><br />';

$event_calendar_bbb_security_salt = elgg_get_plugin_setting('bbb_security_salt', 'event_calendar');

$body .= elgg_echo('event_calendar:bbb_security_salt');
$body .= '<br />';
$body .= elgg_view('input/text', array(
	'name' => 'params[bbb_security_salt]',
	'value' => $event_calendar_bbb_security_salt,
	'class' => 'text_input',
));

$body .= '<br /><br />';

echo $body;
