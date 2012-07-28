<?php 
   /**
    * Elgg Membership plugin
    * Membership settings page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    
    admin_gatekeeper();
    // Make sure only valid admin users can see this
    elgg_set_context('membership');
    $title = elgg_echo('account:settings');
    elgg_push_breadcrumb($title);
    
    $filter = get_input("filter");
    if(!$filter) {
        $filter = "general";
    } 
    $area2 .= elgg_view("settings/settings_tab_view",array("filter" => $filter));
    switch($filter){
        case "general":
            $area2 .= elgg_view("settings/general");
            break;
        case "premium":
            $area2 .= elgg_view("settings/premium");
            break;
        case "coupon":
            $area2 .= elgg_view("settings/coupon");
            break;
        case "authorizenet":
            $area2 .= elgg_view("settings/authorizenet");
            break;
        case "paypal":
            $area2 .= elgg_view("settings/paypal");
            break;
        case "report":
            $area2 .= elgg_view("settings/report");
            break;
    }
    
    // Create a layout
    $body = elgg_view_layout('content', array(
            'filter' => '',
            'content' => $area2,
            'title' => $title,
            'sidebar' => $area1,
    ));
    
    // Finally draw the page
    echo elgg_view_page($title, $body);
