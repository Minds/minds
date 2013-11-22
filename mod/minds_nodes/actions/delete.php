<?php
    action_gatekeeper();
    admin_gatekeeper();

    $entity = get_entity(get_input('id'));
    
    if (($entity) && ($entity->canEdit()))
        $entity->delete();
    
    
    forward(REFERER);