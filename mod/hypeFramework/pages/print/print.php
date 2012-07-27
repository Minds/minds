<?php
$params = hj_framework_extract_params_from_url();
$entity_guid = elgg_extract('entity_guid', $params);
$entity = get_entity($entity_guid);

$template = get_input('template', 'default');

if (!elgg_instanceof($entity)) {
    return;
}

$options = array(
    'head' => elgg_view("css/hj/framework/print/$template")
);

$subtype = $entity->getSubtype();
$type = $entity->getType();

$title = elgg_echo('hj:framework:print:title', array($entity->title));

$body = elgg_view("print/$type/$subtype", $params);

echo elgg_view_page($title, $body, 'print', $options);