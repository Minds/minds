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
	$order = get_entity($order_guid);
	
	$action_token = create_user_token($user->username, 60);
	$urls = array('return' => elgg_get_site_url() . 'pay/',
				  'cancel' => elgg_get_site_url() . 'pay/cancel',
				  'callback' => elgg_get_site_url() . 'pay/callback/' . $order_guid . '/' .$action_token,
				  );
	return $urls;
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
function pay_basket_add_button($type_guid, $title, $description, $price, $quantity){
	 $currecy = pay_get_currency();	 
	 
	 $link =  elgg_view('output/confirmlink', array('is_action' => true,
	 									  'href' => 'action/pay/basket/add?type_guid=' . $type_guid .'&title=' . $title . '&description=' . $description  . '&price=' . $price . '&quantity=' . $quantity,
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
	$order = get_entity($order_guid);
	
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
		$order = get_entity($order_guid);
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
	$order = get_entity($params['order_guid']);
	$user = get_entity($params['user_guid']);
	$amount = $params['amount'];
	$description = $params['description'];
	
	$currency = pay_get_currency();
	
	$urls = pay_urls($params['order_guid']);
	
	$return_url = $urls['return'];
	$cancel_url = $urls['cancel'];
	//for callback we should add a '/paypal' so we know the callback should point the the paypal callback handler
	$callback_url =  $urls['callback'].'/paypal';
	
	$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
	
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
	
	
	//update to process
	pay_update_order_status($order->guid, 'awaitingpayment');
	
	forward($paypal_url . '?' . http_build_query($variables));

	//forward to checkout
	return;
}

/* PayPal callback handler 
 * 
 * @returns true if successful
 */
function paypal_handler_callback($order_guid){
	
	$order = get_entity($order_guid);
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
		
	$payment_status = $_REQUEST['payment_status'];
	//We can now assume that the response is legit so we can update the payment status
	pay_update_order_status($order_guid, $payment_status);
	
	return true;	
}

//register paypal
pay_register_payment_handler('paypal', 'paypal_handler');
