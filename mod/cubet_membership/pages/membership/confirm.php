<?php
     /**
    * Elgg Membership plugin
    * Membership payment confirm page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    gatekeeper();
    $guid=get_input("guid");
    // set the title
    $title = elgg_echo('select:membership:category');
    elgg_push_breadcrumb($title);
    
    // Add the form to this section
    $area2 .= elgg_view("cubet_membership/confirm",array(guid=>$guid));
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));
    
    // Finally draw the page
    echo elgg_view_page($title, $body);
    