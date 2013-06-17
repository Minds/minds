<?php
    
    // make sure only logged in users can see this page	
    gatekeeper();
 
    // set the title
    $title = elgg_echo('stream:title');
 
 
    // Add the form to this section
    $content .= '<div class="elgg-inner">' . elgg_view("stream/index") . '</div>';
 
    // layout the page
    $body = elgg_view_layout('one_column', array('content' => $content, 'header'=>elgg_view_title($title)));
 
    // draw the page
    echo elgg_view_page($title, $body);
?>
