<?php
    
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = elgg_echo('publisher:title');
 
 
    // Add the form to this section
    $content .= elgg_view("publisher/chatpage");
 
    // layout the page
    $body = elgg_view_layout('one_column', array('content' => $content));
 
    // draw the page
    echo elgg_view_page($title, $body);
?>
