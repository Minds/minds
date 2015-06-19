<?php

$full = elgg_extract('full_view', $vars, FALSE);
$entity = elgg_extract('entity', $vars);

$owner = $entity->getOwnerEntity(true); 

if($full){

    switch($entity->getStatus()){
        case 'PROGRESSING':
            echo '<div class="archive-note"> This track is currently rendering and will be available shortly </div>';
            break;
    }

    $video = elgg_view('output/audio', array(
            'class' => '',
            'sources' => array(
                $entity->getSourceUrl('128.mp3') => array('name'=>'128.mp3', 'type'=>'audio/mp3'),
                $entity->getSourceUrl('360.mp4') => array('name'=>'360p', 'type'=>'video/mp4')
        )));
    
    $body = $video;
    
    if(isset($vars['video_only'])){
        echo $body;
        return true;
    }

    $body .= '<div class="archive-description">'.strip_tags($entity->description).'</div>';
    $body .= elgg_view('minds/license', array('license'=>$entity->license));
    echo $body;

} else {

    $menu = elgg_view_menu('entity', array(
        'entity' => $entity,
        'handler' => 'archive',
        'sort_by' => 'priority',
        'class' => 'elgg-menu-hz',
    ));

    $owner = $entity->getOwnerEntity(true);
    if(!$owner)
        return false;
    $owner_link = elgg_view('output/url', array(
        'text' => $owner->name,
        'href' => $owner->getURL()
    ));

    $title = elgg_view('output/url', array(
        'text' => $entity->title,
        'href' => $entity->getURL(),
    ));

    $description = $entity->description ? minds_filter(substr(strip_tags($entity->description), 0, 125) . '...') : '';

    $subtitle = '<i>'.
        elgg_echo('by') . ' ' . $owner_link . ' ' .
        elgg_view_friendly_time($entity->time_created) . '</i>';
        //elgg_echo("kalturavideo:label:length") . ' <strong class="kaltura_video_length">'.$entity->kaltura_video_length.'</strong>';


    '<b class="kaltura_video_created">'. elgg_view_friendly_time($entity->time_created).'</b> by ' . $owner_link;

    $params = array(
        'entity' => $album,
        'title' => $title,
        'metadata' => $menu,
        'subtitle' => $subtitle,
        'content'=>$description,
        'tags' => elgg_view('output/tags', array('tags' => $entity->tags)),
    );
    $params = $params + $vars;
    $summary = elgg_view('object/elements/summary', $params);

    $image = elgg_view('output/url', array(
            'href' => $entity->getURL(),
            'class' => 'archive archive-video t1',
            'text' =>  '<span></span><img src=\'/mod/archive/graphics/wave.png\'/>',
            'title' => $entity->title,
        ));

    $title = elgg_view('output/url', array('href'=>$entity->getURL(), 'text'=>elgg_view_title($entity->title)));

    $extras = '<span class="extras"> <p class="time">'. $date . '</p>' . $menu .'</span>';

    $body = '<span class="info">' . $title . $extras . '<span>';

    $content = $image . $body;
    echo $menu;
    $header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), strip_tags($title) . $subtitle);
    echo $image;
    echo $header;


}
