<?php

$sync = get_input('sync', 'old');
$data = get_input('data');

if (!$data)
    exit;

if (!is_array($data)) {
    $data = array($data);
}
foreach ($data as $entity) {
    $ts = elgg_extract('timestamp', $entity, get_input('__elgg_ts', time() - 30));
    $container_guid = elgg_extract('container_guid', $entity, null);
    $river_id = elgg_extract('river_id', $entity, null);
    $annotation_name = elgg_extract('aname', $entity, 'generic_comment');

    $options = array(
        'type' => 'object',
        'subtype' => 'hjannotation',
        //'owner_guid' => $user->guid,
        'container_guid' => $container_guid,
        'metadata_name_value_pairs' => array(
            array('name' => 'annotation_name', 'value' => $annotation_name),
            array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
            array('name' => 'river_id', 'value' => $river_id)
        ),
        'limit' => 0,
        'order_by' => 'e.time_created desc'
    );
    
    if ($sync == 'new') {
		$options['limit'] = 1;
        $options['wheres'] = "e.time_created > $ts";
    } else {
        $options['wheres'] = "e.time_created < $ts";
    }
    
    $items = elgg_get_entities_from_metadata($options);
    if (is_array($items) && count($items) > 0) {
        foreach ($items as $key => $item) {
            $id = "elgg-{$item->getType()}-{$item->guid}";
            $time = $item->time_created;

            $html = "<li id=\"$id\" class=\"elgg-item\" data-timestamp=\"$time\">";
            $html .= elgg_view_list_item($item);
            $html .= '</li>';

            $comments[] = $html;
        }
    } 
    if ($comments) {
        if (!$id = $river_id) {
            $id = $container_guid;
        }
        $output[] = array('id' => $id, 'comments' => $comments);
    }
    unset($comments);
    unset($options);
}

print(json_encode($output));
return true;