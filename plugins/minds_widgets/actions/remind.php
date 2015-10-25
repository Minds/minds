<?php

    $url = get_input('url');
    $title = get_input('title');
    $description = get_input('description');
    
    if (!$url)
    {
        register_error("Missing URL!");
        forward();
    }


    // TODO: pull data from original article
    
    
    if ($guid = minds_service_remind($url, $title, $description))
    {
        $entity = get_entity($guid);
        forward($entity->getURL());
    }
 else {
     register_error("There was a problem reMinding your link!");
}
    