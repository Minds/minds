<?php
    /**
    * Elgg Membership plugin
    * Authorize.net payment page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    global $CONFIG;
    
    $cat_guid=get_input('cat_guid');
    $guid=get_input("guid");
    elgg_pop_breadcrumb();
    
    // set the title
    $title = elgg_echo('payment:result');
    
    // Add the form to this section
    $area2 .= elgg_view("cubet_membership/authorizenetsuccess",array(guid=>$guid,cat_guid=>$cat_guid));
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));

    // Finally draw the page
    echo elgg_view_page($title, $body);
