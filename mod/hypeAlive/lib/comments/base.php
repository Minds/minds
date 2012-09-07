<?php

function hj_alive_view_comments_list($entity, $params) {
	$parent_guid = elgg_extract('parent_guid', $params, null);
    $river_id = elgg_extract('river_id', $params, null);
    $annotation_name = elgg_extract('aname', $params, 'generic_comment');

    $options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        'owner_guid' => null,
        //'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => $annotation_name),
            array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
            array('name' => 'parent_guid', 'value' => $parent_guid),
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'count' => false,
        'limit' => 3,
        'order_by' => 'e.time_created desc'
    );

    $options['count'] = true;
    $count = elgg_get_entities_from_metadata($options);

    if (elgg_instanceof($entity, 'object', 'hjannotation')) {
        $options['limit'] = 0;
    } else {
        $options['count'] = false;
        $comments = elgg_get_entities_from_metadata($options);
    }

    if ($annotation_name == 'generic_comment') {
        unset($params['aname']);
    }
    foreach ($params as $key => $option) {
        if ($option) {
            $data[$key] = $option;
        }
    }
    $vars['data-options'] = htmlentities(json_encode($data), ENT_QUOTES, 'UTF-8');
    $vars['sync'] = true;
    $vars['pagination'] = false;
    $vars['list_class'] = 'hj-comments';

    $vars['class'] = "hj-annotation-list-$annotation_name";

    $visible = elgg_view_entity_list(array_reverse($comments), $vars);

    $limit = elgg_extract('limit', $options, 0);
    if ($count > 0 && $count > $limit) {
        $remainder = $count - $limit;
        if ($limit > 0) {
            $summary = elgg_echo('hj:alive:comments:remainder', array($remainder));
        } else {
            $summary = elgg_echo('hj:alive:comments:viewall', array($remainder));
        }
    }

    return elgg_view('hj/comments/list', array(
                'summary' => $summary,
                'visible' => $visible,
                'hidden' => $hidden
            ));
}