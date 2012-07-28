<?php
    /**
    * Elgg Membership plugin
    * Membership add category page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    // only logged in users can add blog posts
    gatekeeper();
    global $CONFIG;
    $plugin_settings = $CONFIG->plugin_settings;
    $allow_payment = $plugin_settings->allow_regpayment;
    // get the form input
    $title = get_input('title', '');
    $category = get_input('category');
    $amount = get_input('amount',0);
    $description = get_input('description');
    $m_permission = get_input('m_permission');
    if(!is_array($m_permission)){
      if(empty($m_permission)){
        $m_permission = array();
      }else{
        $m_permission = array($m_permission);
      }
    }

    // Cache to the session
    $_SESSION['category'] = $category;
    $_SESSION['title'] = $title;
    $_SESSION['amount'] = $amount;   
    $_SESSION['description'] = $description;
    $_SESSION['m_permissions'] = $m_permission;

    // Make sure the fields aren't blank
    // for free user type on 29-12-2011
    if($allow_payment == '1'){
	    if (empty($category)|| empty($title) || empty($amount)|| empty($description)) {
	        register_error(elgg_echo("category:fields:blank"));
	        forward($_SERVER['HTTP_REFERER']);
	    } else if (!preg_match('/^[0-9.]+$/', $amount)) {
	        register_error(elgg_echo("amount:field:blank"));
	        forward($_SERVER['HTTP_REFERER']);
	    }
    }else{
    	if (empty($category)|| empty($title) || empty($description)) {
	        register_error(elgg_echo("category:fields:blank"));
	        forward($_SERVER['HTTP_REFERER']);
	    }
    }

    $options = array('metadata_name_value_pairs' => array('category'=>$category),
                                     'types' => 'object',
                                     'subtypes' => 'premium_membership',
                                     'limit' => 1,
                                     'offset' => 0);
    $entities = elgg_get_entities_from_metadata($options);
    if($entities) {
            register_error(sprintf(elgg_echo('membership:alreadyexists'), $category));
            forward($CONFIG->wwwroot."membership/add");
    } else {
            // create a new blog object
            $membership = new ElggObject();
            $membership->title = $title;
            $membership->category = $category;
            $membership->description = $description;
            $membership->subtype = "premium_membership";
            $membership->permissions = $m_permission;

            // for now make all blog posts public
            $membership->access_id = ACCESS_PUBLIC;

            // owner is logged in user
            $membership->owner_guid = get_loggedin_userid();

            // save tags as metadata
            //if($amount){
            $membership->amount = $amount;
            // }

            // save to database
            $membership->save();

            // make sure session cache is cleared
            unset($_SESSION['title']);
            unset($_SESSION['category']);
            unset($_SESSION['amount']);
            unset($_SESSION['description']);
            unset($_SESSION['m_permissions']);

            // forward user to a page that displays the post
            forward($CONFIG->wwwroot."membership/settings/premium");
    }
    exit;
?>