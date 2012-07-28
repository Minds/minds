<?php 
   /**
    * Elgg Membership plugin
    * Membership upgrade page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    admin_gatekeeper();
    // Make sure only valid admin users can see this
    elgg_set_context('membership');
    $title = elgg_echo("upgrade:membership");
    elgg_push_breadcrumb($title);
    $guid=get_input("guid");

    // Add the form to this section
    $area2 .= elgg_view("cubet_membership/upgrade",array("guid"=>$guid));
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));
    
    // Finally draw the page
    echo elgg_view_page($title, $body);
    