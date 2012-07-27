<?php

/**
 * Print View
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category Object
 * @category Views
 *
 * @uses hjSegment
 *
 * @return string HTML
 */
?><?php

elgg_load_library('hj:framework:forms');

elgg_push_context('print');

$entity_guid = elgg_extract('entity_guid', $vars);
$segment = get_entity($entity_guid);

$owner_guid = elgg_extract('owner_guid', $vars);
$owner = get_entity($owner_guid);

if (!elgg_instanceof($segment)) {
    return true;
}

$options = array(
    'type' => 'object',
    'subtype' => 'widget',
    'owner_guid' => $owner->guid,
    'container_guid' => $segment->guid,
    'limit' => 0
);

$widgets = elgg_get_entities($options);
//$widgets = $segment->sortWidgets($widgets);

$hj_sidebar_box_params = array(
    'entity' => $owner,
    'title' => elgg_view_title("$owner->name - $segment->title"),
    'metadata' => false,
    'subtitle' => $segment->description
    //'content' => $hj-sidebar-box_content
);

$hj_sidebar_box = elgg_view('object/elements/summary', $hj_sidebar_box_params);

$page_contents = elgg_view('page/components/image_block', array('body' => $hj_sidebar_box, 'image_alt' => elgg_view_entity_icon($owner, 'small')));

if (is_array($widgets)) {
    foreach ($widgets as $widget) {
        $section = $widget->section;
        $entities = $segment->getSectionContent($section, array('widget' => $widget));
        if ($entities) {
            $module_title = $widget->title;
            $module_content = elgg_view_entity_list($entities, array(
                'full_view' => false,
                'list_class' => 'hj-ajaxed hj-view-list',
                'item_class' => 'hj-ajaxed hj-view-entity',));
            $page_contents .= elgg_view_module('info', $module_title, $module_content);
        }
    }
}
$page_header = '';
$page_contents = elgg_view_module('main', $page_header, $page_contents);

$page = elgg_view_layout('one_column', array(
    'content' => $page_contents,
        ));

elgg_pop_context();

echo $page;