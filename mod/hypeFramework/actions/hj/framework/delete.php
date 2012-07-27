<?php
$entity_guid = get_input('e');
$entity = get_entity($entity_guid);

if ($entity->delete()) {
    system_message(elgg_echo('hj:framework:entity:delete:success'));
} else {
    register_error(elgg_echo('hj:framework:entity:delete:error'));
}
forward(REFERER);