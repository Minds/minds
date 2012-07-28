<?php 
    /**
    * Elgg Membership plugin
    * Membership add category
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    // Make sure only valid admin users can see this
    admin_gatekeeper();
    elgg_set_context('membership');

    $guid=get_input("guid");
    if(!$guid) {
        $title = elgg_echo('add:premium:category');
    } else {
        $title = elgg_echo('edit:premium:category');
    }
    elgg_push_breadcrumb($title);
    
    // Add the form to this section
    $area2 .= elgg_view("cubet_membership/add",array("guid"=>$guid));
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));
    
    // Finally draw the page
    echo elgg_view_page($title, $body);
