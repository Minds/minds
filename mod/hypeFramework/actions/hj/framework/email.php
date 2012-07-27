<?php
gatekeeper();

$extract = hj_framework_extract_params_from_url();
$params = elgg_extract('params', $extract, array());

$subject = $params['subject'];
$container = $params['container'];
$owner = $params['owner'];
$form = $params['form'];
$fields = $params['fields'];

$body .= elgg_view('page/components/hj/inputvaluetable', array('fields' => $fields));

$user = elgg_get_logged_in_user_entity();
$from = $user->email;
$from_name = $user->name;
$to = get_input('email_to');
$subject = get_input('email_subject');

$file_attachments = get_input('attachments');

$attachments = NULL;
foreach ($file_attachments as $file_guid) {
    $file = get_entity((int)$file_guid);
    $attachments[] = array('path' => $file->getFilenameonFilestore());
}

if (phpmailer_send($from, $from_name, $to, '', $subject, $body, NULL, true, $attachments, NULL)) {
    system_message(elgg_echo('hj:framework:email:success'));
} else {
    system_message(elgg_echo('hj:framework:email:error'));
}

forward();
