<?php
    /**
    * Elgg Membership plugin
    * Membership upgrade action
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    gatekeeper();
    global $CONFIG;
    // get the form input
    $usertype=get_input('usertype');
    $guid = get_input('guid');
    $joins = array("JOIN {$CONFIG->dbprefix}objects_entity oe on e.guid = oe.guid");
    $wheres = array("(oe.title = '$usertype')");
    // Get the membership schemes
    $options = array('types' => 'object', 
            'subtypes' => 'premium_membership',
            'limit' => 9999,
            'offset' => 0,
            'joins' => $joins,
            'wheres' => $wheres,
            'full_view' => FALSE,
            'pagination' => FALSE
            );
    $entities = elgg_get_entities($options);
    if($entities) {
        foreach ($entities as $entity) {
            $amount = $entity->amount;
        }
    } else {
        // If Free Membership
        $amount = 0;
    }

    $user = get_entity($guid);
    $user->user_type=$usertype;
    $user->amount = $amount;
    $user->update_date = strtotime(date("F j Y"));
    $user->expiry_date = '';
    $user->notify_date = '';
    $user->expired_reason = 'Administrator upgraded membership';
    $user->save();
    // Update product accees
    if(elgg_is_active_plugin('socialcommerce')) {
        update_product_access($guid);
    }
    $receive_notifications = $CONFIG->plugin_settings->receive_notifications;
    if($receive_notifications == '1') {
        //get admin's guid
        $admin_guid = get_administrator_guid();
        // Send Notifications to admin
        $result = notify_user($admin_guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $user->username), sprintf(elgg_echo('receive:notifications:body'),get_entity($admin_guid)->name, 'You have',$user->name.'\'s', $usertype), NULL, 'email');
    }
    // Send Notifications
    $result = notify_user($guid, $CONFIG->site->guid, sprintf(elgg_echo('receive:notifications:subject'), $user->username), sprintf(elgg_echo('receive:notifications:body'),get_entity($guid)->name, 'The administrator has','your', $usertype), NULL, 'email');
    forward($CONFIG->wwwroot . "membership/report");
?>