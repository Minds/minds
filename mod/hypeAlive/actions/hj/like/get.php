<?php

$data = get_input('data');

if (!$data) {
    register_error(elgg_echo('hj:likes:nodata'));
    return true;
}

if (!is_array($data)) {
    $data = array($data);
}

foreach ($data as $entity) {
    $params['container_guid'] = elgg_extract('container_guid', $entity, null);
    $params['river_id'] = elgg_extract('river_id', $entity, null);

    $likes = hj_alive_view_likes_list($params);
    $owner = hj_alive_does_user_like($params);
    
    if (!$id = $params['river_id']) {
        $id = $params['container_guid'];
    }
    
    $output[] = array('id' => $id, 'likes' => $likes, 'self' => $owner['self']);
}

print(json_encode($output));
return true;