<?php
if (elgg_is_xhr()) {
    $sync = get_input('sync');
    $ts = (int) get_input('time');
    if (!$ts) {
        $ts = time();
    }
    $options = get_input('options');
    if ($sync == 'new') {
        $options['wheres'] = array("e.time_created > {$ts}");
        $options['order_by'] = 'e.time_created asc';
        $options['limit'] = 0;
    } else {
        $options['wheres'] = array("e.time_created < {$ts}");
        $options['order_by'] = 'e.time_created desc';
    }
    $defaults = array(
        'offset' => (int) max(get_input('offset', 0), 0),
        'limit' => (int) max(get_input('limit', 10), 0),
        'pagination' => TRUE,
		'class' => 'hj-syncable-list'
    );

    $options = array_merge($defaults, $options);

    $items = elgg_get_entities($options);

    if (is_array($items) && count($items) > 0) {
        foreach ($items as $key => $item) {
            $id = "elgg-{$item->getType()}-{$item->guid}";
            $time = $item->time_created;

            $html = "<li id=\"$id\" class=\"elgg-item\" data-timestamp=\"$time\">";
            $html .= elgg_view_list_item($item, $vars);
            $html .= '</li>';

            $output[] = $html;
        }
    }
    print(json_encode($output));
    exit;
}

forward(REFERER);