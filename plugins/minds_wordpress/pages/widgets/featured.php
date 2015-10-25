<?php
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header('Cache-Control: no-cache, must-revalidate');
    header("Pragma: no-cache");
    header('Content-type: application/x-javascript; charset=UTF-8');
    
$featured = minds_get_featured('',get_input('limit', 5));
$result = array();
$list = array();

foreach ($featured as $entity) {
    
    // Construct entity
    $e = new stdClass();
    $e->title = $entity->title ? $entity->title : $entity->name;
    $e->description = strip_tags($entity->description);
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