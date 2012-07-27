<?php

/**
 * hjSegment View
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @category Object
 * @category Views
 *
 * @return string HTML
 */

elgg_load_library('hj:framework:forms');

$segment = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, true);

if (!$segment || !elgg_instanceof($segment, 'object', 'hjsegment')) {
    return true;
}

if ($segment->handler && elgg_view_exists('object/hjsegment/' . $segment->handler)) {
    echo elgg_view('object/hjsegment/' . $segment->handler, $vars);
    return true;
}

$owner = $segment->getOwnerEntity();
$container = $segment->getContainerEntity();

if ($full) {

    $params = elgg_clean_vars($vars);
    $params['ajaxify'] = false;
    $extract = hj_framework_extract_params_from_entity($segment, $params);

    $header = $segment->title;

    $menu = elgg_view_menu('hjsegmenthead', array(
        'entity' => $segment,
        'handler' => 'hjsegment',
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz hj-menu-hz',
        'params' => $extract
            ));

    $body = $segment->description;

    $content = elgg_view_module('main', $header . $menu, $body);

    if ($segment->display == 'sections') {
        $content = elgg_view_layout('hj/sections', array(
            'content' => $content,
            'segment' => $segment
                ));
    } else {
        $content = elgg_view_layout('hj/widgets', array(
            'content' => $content,
            'num_columns' => 2,
            'exact_match' => true,
            'show_add_widgets' => false,
            'segment' => $segment
                ));
    }

    $body = elgg_view('object/elements/full', array('entity' => $segment, 'body' => $content));
    echo "<div id=\"elgg-object-$segment->guid\">$body</div>";

} else {
    $header = $segment->title;

    $sections = $segment->getSections();

    if (is_array($sections)) {
        $subtitle = '<div class="clearfix">';
        foreach ($sections as $section) {
            $count = elgg_get_entities(array(
                'type' => 'object',
                'subtype' => $section,
                'container_guid' => $segment->guid,
                'count' => true
                    ));
            $name = $segment->getSectionTitle($section);

            $subtitle .= "<span class\"left\"><b>$name</b>: $count  </span>";
        }
        $subtitle .= '</div>';
    }

    $params = array(
        'entity' => $segment,
        'title' => $header,
        'subtitle' => $subtitle
    );

    $params = $params + $vars;
    $list_body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $list_body);
}