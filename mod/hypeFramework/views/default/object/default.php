<?php

elgg_load_js('hj.framework.ajax');
elgg_load_js('hj.framework.fieldcheck');

$entity = elgg_extract('entity', $vars);

if (elgg_instanceof($entity)) {
    $form = hj_framework_get_data_pattern($entity->getType(), $entity->getSubtype());
    if (elgg_instanceof($form, 'object', 'hjform')) {
        $full = elgg_extract('full_view', $vars, false);
        $view_params = elgg_extract('view_params', $vars, '');
        $fields = $form->getFields();
        $owner = $entity->getOwnerEntity();

        // Short View of the Entity
        $title = $entity->title;
        $short_description = elgg_get_excerpt($entity->description);

        if ($full) {
            $params_menu = hj_framework_extract_params_from_entity($entity);
            $header_menu = elgg_view_menu('hjentityhead', array(
                'entity' => $entity,
                'view_params' => $view_params,
                'class' => 'elgg-menu-hz hj-menu-hz',
                'sort_by' => 'priority',
                'params' => $params_menu
                    ));

            $section = elgg_echo('hj:hjportfolio:hjexperience');
            $intro = elgg_view_title("{$owner->name} - $section");

            $fields_view = elgg_view('page/components/hj/fieldtable', array('entity' => $entity, 'fields' => $fields, 'view_params' => $view_params, 'intro' => $intro));
            $full_description = elgg_view('page/components/hj/fullview', array('entity' => $entity, 'content' => $fields_view, 'view_params' => $view_params));
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
            'content' => $content,
            'class' => 'hj-portfolio-widget'
        );

        $params = $params + $vars;
        $list_body = elgg_view('object/elements/summary', $params);

        echo elgg_view_image_block(NULL, $list_body);
        return true;
    }
}

/**
 * ElggObject default view.
 *
 * @warning This view may be used for other ElggEntity objects
 *
 * @package Elgg
 * @subpackage Core
 */
$icon = elgg_view_entity_icon($vars['entity'], 'small');

$title = $vars['entity']->title;
if (!$title) {
    $title = $vars['entity']->name;
}
if (!$title) {
    $title = get_class($vars['entity']);
}

if (elgg_instanceof($vars['entity'], 'object')) {
    $metadata = elgg_view('navigation/menu/metadata', $vars);
}

$owner_link = '';
$owner = $vars['entity']->getOwnerEntity();
if ($owner) {
    $owner_link = elgg_view('output/url', array(
        'href' => $owner->getURL(),
        'text' => $owner->name,
            ));
}

$date = elgg_view_friendly_time($vars['entity']->time_created);

$subtitle = "$owner_link $date";

$params = array(
    'entity' => $vars['entity'],
    'title' => $title,
    'metadata' => $metadata,
    'subtitle' => $subtitle,
    'tags' => $vars['entity']->tags,
);
$params = $params + $vars;
$body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($icon, $body);