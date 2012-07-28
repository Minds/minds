<?php
    /**
    * Elgg Membership plugin
    * Membership cancel membership page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $userid = get_input('user_id');//$_POST["user_id"];
    $obj = get_entity($userid);

    $plugin_settings = $CONFIG->plugin_settings;
    $apiloginid = $plugin_settings->authorizenet_apiloginid;
    $transactionkey = $plugin_settings->authorizenet_transactionkey;
    $accounttype = $plugin_settings->authorizenet_environment;
    if($accounttype == "yes") {
        $arb_server = "1";
    } else {
        $arb_server = "0";
    }
    $user_subscription_id = $obj->subscription_id;
    if($user_subscription_id) {
        require('AuthnetARB.class.php');

        // Set up the subscription. Use the developer account for testing..
        $subscription = new AuthnetARB($apiloginid, $transactionkey, $arb_server);

         // Set subscription id
        $subscription->setParameter('subscrId', $user_subscription_id);

        // Delete the subscription
        $subscription->deleteAccount();

        // Check the results of our API call
        if ($subscription->isSuccessful())
        {
            // Get the subscription ID
            $subscription->getResponse();

            $obj->user_type = 'Free';
            $obj->amount = 0;
            $obj->update_date = strtotime(date("F j Y"));
            $obj->enable_status = true;
            $obj->subscription_id = 0;
            $obj->save();
            system_message(elgg_echo('user:cancel:yes'));
        }
        else
        {
            // The subscription was not created!
            $subscription->getResponseCode();
            $subscription->getResponse();
            register_error(elgg_echo('user:cancel:no'));
        }
    }
    forward($_SERVER['HTTP_REFERER']);
?>