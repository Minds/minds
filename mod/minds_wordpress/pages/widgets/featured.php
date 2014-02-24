<?php

$featured = minds_get_featured('',get_input('limit', 5));
$result = array();
$list = array();

foreach ($featured as $entity) {
    
    // Construct entity
    $e = new stdClass();
    $e->title = $entity->title ? $entity->title : $entity->name;
    $e->description -> $entity->description;
    $e->url = $entity->getUrl();
    
    // Construct user
    $user = get_entity($entity->owner_guid, 'user'); 
    $author = new stdClass();
    $author->name = $user->name;
    $author->url = $user->getUrl();
    $author->icon = $user->getIconURL();
    $author->icon_small = $user->getIconURL('small');
    $author->icon_tiny = $user->getIconURL('tiny');
    
    $e->author = $author;
    $list[] = $e;
    
}

$result['result'] = $list;
$result['count'] = count($featured);


// JSONP encode
echo get_input('callback') . '(' . json_encode($result) . ')';