<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!$entity) {
    return true;
}

$extract = hj_framework_extract_params_from_entity($entity);
$obj_params = elgg_extract('params', $extract, array());

$form = $obj_params['form'];
$fields = $obj_params['fields'];
$filefolder = $obj_params['container'];
$owner = $obj_params['owner'];

$title = $entity->title;

$filefolder = sprintf(elgg_echo('hj:framework:filefolder'), $filefolder->title);
$filename = sprintf(elgg_echo('hj:framework:filename'), $entity->originalfilename);
$simpletype = sprintf(elgg_echo('hj:framework:simpletype'), $entity->simpletype);
$filesize = sprintf(elgg_echo('hj:framework:filesize'), $entity->filesize);

$subtitle = "$filefolder <br />$filename  <br />$simpletype <br />$filesize";
$short_description = elgg_get_excerpt($entity->description);

if ($full) {
    $header_menu = elgg_view_menu('hjentityhead', array(
        'entity' => $entity,
        'file_guid' => $entity->guid,
        'view_params' => $view_params,
        'handler' => 'hjfile',
        'class' => 'elgg-menu-hz hj-menu-hz',
        'sort_by' => 'priority',
        'params' => $extract
            ));

    if ($entity->simpletype == 'image') {
        if ($view_params == 'gallery') {
            $preview = elgg_view_entity_icon($entity, 'full');
        } else {
            $preview = elgg_view_entity_icon($entity, 'preview');
        }
    }

    $fields_view = elgg_view('page/components/hj/fieldtable', array('entity' => $entity, 'fields' => $fields, 'view_params' => $view_params, 'intro' => $intro));
    $full_description = elgg_view('page/components/hj/fullview', array('entity' => $entity, 'content' => $preview . $fields_view, 'view_params' => $view_params, 'handler' => 'hjfile', 'extras' => array('file_guid' => $entity->guid)));
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
    'tags' => false,
    'content' => $content
);

$params = $params + $vars + $obj_params;
$list_body = elgg_view('object/elements/summary', $params);
$icon = elgg_view_entity_icon($entity, 'medium');

echo elgg_view_image_block($icon, $list_body);

