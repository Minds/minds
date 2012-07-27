<?php
/**
 * Action to reorder entities
 *
 * @uses $priorities An array of  position => guid
 *
 * @return bool
 */

$priorities = get_input('priorities');

if (is_array($priorities)) {
    foreach ($priorities as $priority => $guid) {
        $entity = get_entity($guid);
        $entity->priority = $priority;
    }
    system_message(elgg_echo('hj:framework:success'));
}
return true;