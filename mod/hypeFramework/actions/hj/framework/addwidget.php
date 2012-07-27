<?php

$params = get_input('params');

$subject_guid = elgg_extract('subject_guid', $params, null);
$subject = get_entity($subject_guid);

$context = elgg_extract('context', $params);

if (elgg_instanceof($subject, 'object', 'hjsegment')) {
    $widget = $subject->addWidget('default', null, $context);
}
if ($widget) {
    $html = elgg_view_entity($widget);
    print(json_encode($html));
    system_message(elgg_echo('hj:framework:widget:add:success'));
} else {
    register_error(elgg_echo('hj:framework:widget:add:failure'));
}
forward(REFERER);

