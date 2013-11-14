<?php
/**
 * Elgg Pay Library
 *
 * @package Pay
 */
/* Puts the referring page to the breadcrumb 
 */
function pay_breadcrumb(){
	$last_url = $_SERVER['HTTP_REFERER'];
	$elgg_route = str_replace(elgg_get_site_url(), '', $last_url);
	$segments = explode('/',$elgg_route);
	
	//this is the refering plugin
	$handler = array_shift($segments);
	
	if($handler == 'pay'){
		return false;
	}
	
	if($last_url){
		elgg_push_breadcrumb(elgg_echo($handler), $elgg_route);
	}
	
	return true;
}
/* Returns more information for the selected currency
 */
function pay_get_currency(){
	//The currency as set by the admin in the symbol eg GBP
	$currency = elgg_get_plugin_setting('currency', 'pay');
	
	$currencies = array( 'GBP' => array('code' => 'GBP',
										'country' => 'UK',
										'symbol' => '&pound;'
										),
						 'USD' => array('code' => 'USD',
						 				'country' => 'USA',
						 				'symbol' => '$'
										),
						 'CNY' => array('code' => 'CNY',
						 				'country' => 'CN',
						 				'symbol' => '&yen'
										),
						 'EUR' => array('code' => 'EUR',
						 				'country' => 'EU',
						 				'symbol' => '&euro;'
										),
						 'DKK' => array('code' => 'DKK',
						 				'country' => 'DK',
						 				'symbol' => 'kr.'
										),
						 'INR' => array('code' => 'INR',
						 				'country' => 'IN',
						 				'symbol' => '&#8377;'
										),
					);
										
	if($currencies[$currency]){
		return $currencies[$currency];
	} else {
		return $currency ? $currency : $currencies['GBP'];
	}
}
/* URLS for return, cancel and callabck 
 */
function pay_urls($order_guid){
	//creates an action token open for an hour
	$user = elgg_get_logged_in_user_entity();
	$order = get_entity($order_guid, 'object');
	
	$action_token = create_user_token($user->username, 527040); // Make tokens last a year and a day (since Paypal seems to be pinging the notify URL rather than the generic payment endpoint, contrary to documented behaviou)
	$urls = array('return' => elgg_get_site_url() . 'pay/',
				  'cancel' => elgg_get_site_url() . 'pay/cancel',
				  'callback' => elgg_get_site_url() . 'pay/callback/' . $order_guid . '/' .$action_token,
				  );

        // Passing order urls through a hook so we can override the return url as necessary (bit of a hack)
        return trigger_plugin_hook('urls', 'pay', array('order' => $order), $urls);

}
		
/********************
 * BASKET
 *
 * Read the Readme file to learn how to add items to the basket
 *
 */
 
/* Return the total value of items in the basket 
 */
function pay_basket_total(){
	
	$items = elgg_get_entities(array(
									'type' => 'object',
									'subtype' => 'pay_basket',
									'owner_guid' => $user_guid,
									));
	$total = 0;								
	foreach($items as $item){
		$total += $item->price;
	}
	
	return  $total;
}
/* Return the basket
 */
function pay_get_basket(){
	
	$basket = elgg_list_entities(array(
									'type' => 'object',
									'subtype' => 'pay_basket',
									'owner_guid' => $user_guid,
									));
									
	if(!$basket){
		return elgg_echo('pay:basket:empty');
	}
	
	$currency = pay_get_currency();
							
	$total = '<p style=\'float:right\'>' . elgg_echo('pay:basket:total') . $currency['symbol'] .  pay_basket_total() . '</p>';
	
	return $basket . $total;
}
/********************
 * Account
 *
 * All orders and transaction are logged and displayed on the accounts page. 
 *
 *
 */
/********************
 * Payment Items
 *
 * Plugins can add items to the basket by using pay_basket_add_button(...); on any pay or view
 * @todo: make this a form rather than a link
 */
function pay_basket_add_button($type_guid, $title, $description, $price, $quantity, $recurring = false){
	 $currecy = pay_get_currency();	 
	 
         $query = 'action/pay/basket/add?type_guid=' . $type_guid .'&title=' . $title . '&description=' . $description  . '&price=' . $price . '&quantity=' . $quantity;
         if ($recurring) $query .= "&recurring=y";
	 $link =  elgg_view('output/confirmlink', array('is_action' => true,
	 									  'href' => $query,
										  'text' => $currecy['symbol'] . $price . ' - Buy Now',
										  'confirm' => 'Are you sure you wish to purchase this item?',
										  'class' => 'pay buynow'
									));
	$button = $link;								
	return $button;
}
/********************
 * Orders
 *
 */
/* Updates an orders status 
 */
function pay_update_order_status($order_guid, $status){
	$order = get_entity($order_guid, 'object');
	
	$order->status = $status;
	
	if($order->save()){
		return true;
	} else {
		return false;
	}
}
/*******************
 * Seller Balance
 * 
 * 
 */
function pay_get_user_balance($guid){
	$balance = 0;
	$orders = elgg_get_entities_from_metadata(array(
									'type' => 'object',
									'subtype' => 'pay',
									'metadata_name_value_pairs' => array('name' => 'seller_guid', 'value' => $guid),
									'limit' => 9999999999999
									));
	foreach($orders as $order){
		if($order->status=='Completed'){
			$balance += +$order->seller_amount;
		}
	}
	
	return $balance;
}
/********************
 * Payment Handlers
 *
 * Payment methods can be added into this plugin via other elgg plugins.
 *
 * EXAMPLE: pay_register_payment_handler('paypal', 'paypal_handler'); (CALLBACK SHOULD BE ADDED AS FUNCTION WITH _callback eg. paypal_handler_callback
 * 
 * Only a handler and callback is required. 
 *
 */
function pay_register_payment_handler($handler, $callback){
	 
	 global $CONFIG;
	 
	 if (!isset($CONFIG->pay)) {
		$CONFIG->pay = array(
			'payment_handlers' => array(),
			);
	 }
	 
	 if (!isset($CONFIG->pay['payment_handlers'][$handler])) {
		$CONFIG->pay['payment_handlers'][$handler] = array();
	}

	$info = new stdClass();
	$info->callback = $callback;
	
	$CONFIG->pay['payment_handlers'][$handler] = $info;

	return true;
}

/* Called when the user hits checkout 
 */
function pay_call_payment_handler($handler, $params = array()){
	global $CONFIG;
	
	$info = $CONFIG->pay['payment_handlers'][$handler];
	
	return call_user_func($info->callback,$params);
}

/* Called when the callbacl url is triggered.
 */
function pay_call_payment_handler_callback($handler, $order_guid){
	global $CONFIG;
	
	$info = $CONFIG->pay['payment_handlers'][$handler];
	
	$callback = call_user_func($info->callback.'_callback',$order_guid);
	
	if($callback == true){
		//send messages
		//update order
		$order = get_entity($order_guid, 'object');
		//This gives 98% to the seller.
		$order->seller_amount = ($order->amount * 0.98);
		$order->save();
		if($order->status == 'Completed'){
			//notification to go here
			notification_create(array($order->seller_guid, $order->getOwnerGUID()), 0, $order->guid, array('notification_view'=>'pay_order_paid'));
		}
	} else {
		return false;
	}
}

/* PayPal payment handler
 *
 * Examples of params:
 *	 @param int 	$order_guid
 *	 @param string  $description
 *	 @param int		$user_guid
 *	 @param string	$currency
 *	 @param string  $return_url
 *	 @param string 	$cancel_url
 *	 @param string	$callback_url
 *
 * forwards user to paypal checkout
 */
function paypal_handler($params){
    
        global $CONFIG;
    
	$order = get_entity($params['order_guid'], 'object');
	$user = get_entity($params['user_guid'], 'user');
	$amount = $params['amount'];
	$description = $params['description'];
	
	$currency = pay_get_currency();
	
	$urls = pay_urls($params['order_guid']);
	
	$return_url = $urls['return'];
	$cancel_url = $urls['cancel'];
	//for callback we should add a '/paypal' so we know the callback should point the the paypal callback handler
	$callback_url =  $urls['callback'].'/paypal';
	
	$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        if ($CONFIG->debug)
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // If we're in debug mode, then use the debug sandbox endpoint.
	
	$variables = array ( 'cmd' => '_xclick',
					'business' => elgg_get_plugin_setting('paypal_business', 'pay'),
					'item_name' => 'Order: ' . $order->guid,
					'currency_code' => $currency['code'],
					'amount' => $amount,
					'notify_url' => $callback_url,
					'return' => $return_url,
					'cancel' => $cancelurl,
					
					//USER PARAMS
					'email' => $user->email
				);
	
        error_log('PAYPAL Variables: ' . print_r($variables, true));
        
        /**
         * Support for recurring payments.
         * See https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/subscribe_buttons/
         */
        if ($params['recurring'])
        {
            // Set the correct command
            $variables['cmd'] = '_xclick-subscriptions';
            
            // Get rid of amount, since this is handled by the subscription
            unset($variables['amount']);
            
            // Set recurring payment info
            $variables['a3'] = $amount;
            $variables['p3'] = 1;
            
            // Set recurring period based on expiry (default 1 year)
            $ia = elgg_set_ignore_access($ia);
            $item = get_entity($order->object_guid, 'object');
            $expires = $item->expires;
            if (!$expires) $expires = MINDS_EXPIRES_YEAR;
            $ia = elgg_set_ignore_access($ia);
            
            switch ($expires) {
                case MINDS_EXPIRES_DAY: $variables['t3'] = 'D'; break;
                case MINDS_EXPIRES_WEEK: $variables['t3'] = 'W'; break;
                case MINDS_EXPIRES_MONTH: $variables['t3'] = 'M'; break;
                case MINDS_EXPIRES_YEAR:
                default: $variables['t3'] = 'Y';
            }
            
            // Now, continue until cancelled
            $variables['src'] = 1;
        }
	

	//update to process
	pay_update_order_status($order->guid, 'awaitingpayment');
	
	forward($paypal_url . '?' . http_build_query($variables));

	//forward to checkout
	return;
}



/**
 * This must be configured in your seller account -> profile -> ipn settings and set to http://yoursite/paypalgenericipn.
 * This handles generic notifications from paypal, most pertinantly, subscriptions
 * @param type $page
 */
function paypal_generic_ipn_handler($page) {
    
    global $CONFIG;
    
    $ia = elgg_set_ignore_access();
    
    elgg_log('PAYPAL: ********* Paypal GENERIC IPN triggered **********');
    
    // Try and get order we're referring to
    if ($orders = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'pay',
        'limit' => 1,
        'metadata_name' => 'subscr_id',
        'metadata_value' => $_POST['subscr_id'],
    )))
            $order = $orders[0];
    
    
    // TODO: Other methods of pulling order out
    
    
    // If we have an order
    if (($_POST['subscr_id']) && ($order)) {
        
        $order_guid = $order->guid;
    
        // Validate the request
        // Read the post from PayPal and add 'cmd' 
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
        // Handle escape characters, which depends on setting of magic quotes 
            $value = urlencode($value);
            $req .= "&$key=$value";
        }

        // Post back to PayPal to validate 
        elgg_log("PAYPAL: Request received, posting to paypal");

        $connect = $CONFIG->debug ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com';
        $ch = curl_init($connect . '/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // In wamp like environments that do not come bundled with root authority certificates,
        // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
        // of the certificate as shown below.
        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        if (!($res = curl_exec($ch))) {
            elgg_log("PAYPAL: Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);
        
        
        // Handle request 
        elgg_log("PAYPAL: Response: $res");

        if (strcmp($res, "VERIFIED") == 0) {

            elgg_log("PAYPAL: POST data is " . print_r($_POST, true));
            
            // Attach a payment history to the order.
            $order->annotate('order_details', serialize($_POST));

            elgg_log("PAYPAL: Transaction type is {$_POST['txn_type']}");

            
            switch ($_POST['txn_type']) {
                
                case 'subscr_signup': // Not handled here
                    break;
                case 'subscr_cancel': // Cancel the subscription

                    pay_update_order_status($order_guid, 'Cancelled');
                    break;
                case 'subscr_payment': // Subscription regular payment.
    

                        $payment_status = $_REQUEST['payment_status'];
                        //We can now assume that the response is legit so we can update the payment status
                        pay_update_order_status($order_guid, $payment_status);
                    
                    
                    break;
                default:
                    elgg_log('PAYPAL: Unsupported transaction type hit generic IPN');
            }
            
            return true;
            
        } else if (strcmp($res, "INVALID") == 0) {
            elgg_log("PAYPAL: IPN Query is invalid");
            foreach ($_POST as $key => $value) {
                $debugtxt .= $key . " = " . $value . "\n\n";
            }
            throw new Exception("PAYPAL: Invalid IPN query! " . $CONFIG->debug ? $debugtxt : '');
        }
        
    }
    
    return true;
}


/* PayPal callback handler 
 * 
 * @returns true if successful
 */

function paypal_handler_callback($order_guid) {

    global $CONFIG;

    $order = get_entity($order_guid, 'object');

    // We need to actually do some validation in an IPN... MP 


    elgg_log('PAYPAL: ********* Paypal IPN triggered **********');


    // Read the post from PayPal and add 'cmd' 
    $req = 'cmd=_notify-validate';

    foreach ($_POST as $key => $value) {
    // Handle escape characters, which depends on setting of magic quotes 
        $value = urlencode($value);
        $req .= "&$key=$value";
    }

    // Post back to PayPal to validate 
    elgg_log("PAYPAL: Request received, posting to paypal");

    $connect = $CONFIG->debug ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com';
    $ch = curl_init($connect . '/cgi-bin/webscr');
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

    // In wamp like environments that do not come bundled with root authority certificates,
    // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
    // of the certificate as shown below.
    // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
    if (!($res = curl_exec($ch))) {
        elgg_log("PAYPAL: Got " . curl_error($ch) . " when processing IPN data");
        curl_close($ch);
        exit;
    }
    curl_close($ch);



    elgg_log("PAYPAL: Response: $res");

    if (strcmp($res, "VERIFIED") == 0) {

        elgg_log("PAYPAL: POST data is " . print_r($_POST, true));

        switch ($_POST['payment_status']) {
            case 'Completed' :
                elgg_echo('PAYPAL: Payment status: completed');


                // Attach a payment history to the order.
                $order->annotate('order_details', serialize($_POST));
                
                
                //TODO: More validation - e.g. check currency and gross etc...


                
                // If this is a recurring payment, then we need to link the order to a subscription profile so we can manage the order from its generic IPN
                if (isset($_POST['subscr_id']))
                    $order->subscr_id = $_POST['subscr_id'];



                if ($_POST['txn_type'] == 'subscr_cancel') // Quickly handle subscription cancellations.
                    pay_update_order_status($order_guid, 'Cancelled');
                else {
                    $payment_status = $_REQUEST['payment_status'];
                    //We can now assume that the response is legit so we can update the payment status
                    pay_update_order_status($order_guid, $payment_status);
                }

                return true;



                break;

            default: elgg_log("PAYPAL: Payment status unknown : {$_POST['payment_status']}");
        }
    } else if (strcmp($res, "INVALID") == 0) {
        elgg_log("PAYPAL: IPN Query is invalid");
        foreach ($_POST as $key => $value) {
            $debugtxt .= $key . " = " . $value . "\n\n";
        }
        throw new Exception("PAYPAL: Invalid IPN query! " . $CONFIG->debug ? $debugtxt : '');
    }




    /*
	
	$reciever_address = get_input('receiver_email');
		if($reciever_address != elgg_get_plugin_setting('paypal_business', 'pay')){
			return false;
		}
	
	$amount = get_input('mc_gross');
		if($amount != $order->amount){
			return false;
		}
		
	$txn_id = get_input('txn_id');
		if($txn_id == $order->txn_id){
			return false;
		}
		*/
		/*
	$payment_status = $_REQUEST['payment_status'];
	//We can now assume that the response is legit so we can update the payment status
	pay_update_order_status($order_guid, $payment_status);
	
	return true;	*/
}

//register paypal
pay_register_payment_handler('paypal', 'paypal_handler');
