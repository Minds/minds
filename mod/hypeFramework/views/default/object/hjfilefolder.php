<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!$entity) {
    return true;
}

$obj_params = hj_framework_extract_params_from_entity($entity);

$files = $entity->getContainedFiles();
$file_count = count($files);
$file_count_text = elgg_echo('hj:framework:filecount');

$title = $entity->title;
$subtitle = "<b>$file_count_text</b>  $file_count";
$short_description = elgg_get_excerpt($entity->description);

if ($full) {
    $header_menu = elgg_view_menu('hjentityhead', array(
        'entity' => $entity,
        'handler' => 'hjfilefolder',
        'class' => 'elgg-menu-hz hj-menu-hz',
        'sort_by' => 'priority',
        'params' => $obj_params
            ));

    $file_view = elgg_view_entity_list($files);
    $full_description = elgg_view('page/components/hj/fullview', array('entity' => $entity, 'content' => $files));
}

$content = <<<HTML
    $short_description
    $full_description
HTML;

$params = array(
    'entity' => $entity,
    'title' => $title,
    'metadata' => $header_menu,
    'subtitle' => $subtitle,
    'content' => $content
);

$params = $params + $vars + $obj_params;
$list_body = elgg_view('object/elements/summary', $params);
$icon = elgg_view_entity_icon($entity, 'small');

echo elgg_view_image_block($icon, $list_body);