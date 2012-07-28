<?php
   /**
    * Elgg Membership plugin
    * Membership payment page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    $cat_guid=get_input('cat_guid');
    $guid=get_input("guid");
    
    // set the title
    $title = elgg_echo("confirm:your:payment");
    elgg_push_breadcrumb($title);
    
    // Add the form to this section
    $area2 .= elgg_view("cubet_membership/payment",array(guid=>$guid,cat_guid=>$cat_guid));
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));
    
    // Finally draw the page
    echo elgg_view_page($title, $body);
