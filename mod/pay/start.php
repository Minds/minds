<?php
/**
 * Elgg Pay - a payment framework
 *
 * @package Pay
 */

elgg_register_event_handler('init', 'system', 'pay_init');

/**
 * initialization functions.
 */
function pay_init() {

	// register a library of helper functions
	elgg_register_library('elgg:pay', elgg_get_plugins_path() . 'pay/lib/pay.php');
        elgg_load_library('elgg:pay');

	// Extend CSS
	elgg_extend_view('css/elgg', 'pay/css');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('pay', 'pay_page_handler');

        // Register a generic Paypal IPN endpoint to handle recurring payments
        elgg_register_page_handler('paypalgenericipn', 'paypal_generic_ipn_handler');
        
	// Register URL handlers
	elgg_register_entity_url_handler('object', 'pay', 'pay_url_override');

	// Register granular notification for this object type
	register_notification_object('object', 'pay', elgg_echo('pay:notification'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'pay_notify_message');

	// add an account link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'pay_user_menu');
	
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'pay_entity_menu_setup');
	
	elgg_register_event_handler('pagesetup', 'system', 'pay_page_setup');
	

	// Register actions
	$action_path = elgg_get_plugins_path() . 'pay/actions/pay';
	elgg_register_action("pay/basket/add", "$action_path/addtobasket.php");
	elgg_register_action("pay/basket/delete", "$action_path/removefrombasket.php");
	elgg_register_action("pay/basket/update_quantity", "$action_path/update_quantity.php");
	
	elgg_register_action("pay/checkout", "$action_path/checkout.php");
	elgg_register_action("pay/confirm", "$action_path/process.php");
	elgg_register_action("pay/appeal", "$action_path/appeal.php");
	
	elgg_register_action("pay/withdraw", "$action_path/withdraw.php");
	elgg_register_action("pay/withdraw/complete", "$action_path/withdraw_complete.php");
	
	elgg_register_action("pay/admin/accept", "$action_path/admin/accept.php");
	elgg_register_action("pay/admin/decline", "$action_path/admin/decline.php");
	elgg_register_action("pay/admin/delete", "$action_path/admin/delete.php");
        
         // Extend public pages
        elgg_register_plugin_hook_handler('public_pages', 'walled_garden', function ($hook, $handler, $return, $params){
            $pages = array('paypalgenericipn');
            return array_merge($pages, $return);
        });
}

/**
 * Pay Pages
 * URLs take the form of
 *  Basket:       	 pay/basket
 *
 *  Callabck:       pay/callback/<guid>/<auth_token>
 *
 *  View account:    pay/account/<username>
 *  View orders:     pay/account/orders
 *  View order:      pay/account/order/<orderid>
 *
 * @param array $page
 * @return bool
 */
function pay_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'account';
	}

	$file_dir = elgg_get_plugins_path() . 'pay/pages/pay';

	$page_type = $page[0];
	switch ($page_type) {
		case 'admin':
			include "$file_dir/admin.php";
			break;
		case 'basket':
			include "$file_dir/basket.php";
			break;
		case 'callback':
			set_input('guid', $page[1]);
			set_input('auth_token', $page[2]);
			set_input('payment_handler', $page[3]);
			include "$file_dir/callback.php";
			break;
		case 'account':
			$sub_dir = $page[1];
			switch($sub_dir) {
				case 'withdraw':
					set_input('username', $page[2]); 
					include "$file_dir/withdraw.php";
					break;
				case 'seller':
					set_input('username', $page[2]); 
					include "$file_dir/seller_account.php";
					break;
				case 'orders':
					include "$file_dir/orders.php";
					break;
				case 'order':
					set_input('guid', $page[2]);
					include "$file_dir/view_order.php";
					break;
				default:
					set_input('username', $page[1]);
					include "$file_dir/account.php";
			}
		default:
			return false;
	}
	return true;
}

/**
 * Creates the notification message body
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function pay_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'pay')) {
		$descr = $entity->description;
		$title = $entity->title;
		$url = elgg_get_site_url() . "view/" . $entity->guid;
		$owner = $entity->getOwnerEntity();
		return elgg_echo("pay:confimation") . $entity->guid . "\n\n" . $descr . "\n\n" . $entity->getURL();
	}
	return null;
}

/**
 * Add a menu item to the users hover menu
 */
function pay_user_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	
	if (elgg_is_logged_in() && (elgg_get_logged_in_user_guid() == $user->guid || elgg_is_admin_logged_in())) {

		$url = "pay/account/{$user->username}";

		$item = new ElggMenuItem('pay:account', elgg_echo('pay:account'), $url);

		$item->setSection('action');

		$return[] = $item;

	}

	return $return;
}

/**
 * Add / remove links/info to entity menu
 */
function pay_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if (elgg_get_context() == 'pay_basket') {
		$return = NULL;
		
		$options = array(
			'name' => 'quantity',
			'text' => elgg_view_form('pay/basket/update_quantity', '', array('quantity' => $entity->quantity, 'item_guid' => $entity->getGuid())),
			'title' => elgg_echo('pay:price'),
			'href' => '#',
			'priority' => 100,
		);
		$return[] = ElggMenuItem::factory($options);
		
		$currency = pay_get_currency();
		$options = array(
			'name' => 'price',
			'text' => '<b>' . $currency['symbol'] . $entity->price . '</b>',
			'title' => elgg_echo('pay:price'),
			'href' => '#',
			'priority' => 100,
		);
		$return[] = ElggMenuItem::factory($options);
		
		$options = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'title' => elgg_echo('delete:this'),
			'href' => "action/pay/basket/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		);
		$return[] = ElggMenuItem::factory($options);
	} elseif (elgg_get_context() == 'pay'){
		$return = NULL;
		
		//for now, if the seller guid is equal to the page owner, it shows the sellers amount
		$amount =  $entity->seller_guid == elgg_get_page_owner_guid() ? $entity->seller_amount : $entity->amount;
		$currency = pay_get_currency();
		$options = array(
			'name' => 'amount',
			'text' => '<b>' . $currency['symbol'] . $amount. '</b>',
			'title' => elgg_echo('pay:amount'),
			'href' => '#',
			'priority' => 50,
		);
		$return[] = ElggMenuItem::factory($options);
		
		$options = array(
			'name' => 'status',
			'text' => elgg_echo('pay:account:order:status:' . $entity->status),
			'title' => elgg_echo('pay:account:order:status'),
			'href' => '#',
			'priority' => 100,
		);
		$return[] = ElggMenuItem::factory($options);
		
		if($entity->order){
			$options = array(
				'name' => 'view',
				'text' => elgg_echo('pay:account:order:view'),
				'title' => elgg_echo('pay:account:order:view'),
				'href' => $entity->getUrl(),
				'priority' => 150,
			);
			$return[] = ElggMenuItem::factory($options);
		}
		
		
	} elseif (elgg_get_context() == 'pay_admin') {
		$return = NULL;
		$currency = pay_get_currency();
		$options = array(
			'name' => 'amount',
			'text' => '<b>' . $currency['symbol'] . $entity->amount . '</b>',
			'title' => elgg_echo('pay:amount'),
			'href' => '#',
			'priority' => 50,
		);
		$return[] = ElggMenuItem::factory($options);
		
		$options = array(
			'name' => 'status',
			'text' => elgg_echo('pay:account:order:status:' . $entity->status),
			'title' => elgg_echo('pay:account:order:status'),
			'href' => '#',
			'priority' => 100,
		);
		$return[] = ElggMenuItem::factory($options);
		
		$options = array(
			'name' => 'paypal_address',
			'text' => $entity->paypal_address,
			'href' => '#',
			'priority' => 125,
		);
		$return[] = ElggMenuItem::factory($options);
		
		//Mark as sent
		if($entity->status != 'Completed'){
			$options = array(
				'name' => 'change_status',
				'text' => elgg_echo('pay:withdraw:mark:completed'),
				'href' => 'action/pay/withdraw/complete?guid=' . $entity->guid,
				'is_action' => true,
				'priority' => 150,
			);
			$return[] = ElggMenuItem::factory($options);
		}
		
		$options = array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'title' => elgg_echo('delete:this'),
			'href' => "action/pay/basket/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		);
		$return[] = ElggMenuItem::factory($options);
	}
	return $return;
}


/**
 * Populates the ->getUrl() method for file objects
 *
 * @param ElggEntity $entity File entity
 * @return string File URL
 */
function pay_url_override($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "pay/account/order/" . $entity->getGUID();
}

/**
 * Display notification of new messages in topbar
 */
function pay_page_setup() {
	elgg_load_library('elgg:pay');
	
	//TOPBAR
	if (elgg_is_logged_in()) {
/*
		$user_guid = elgg_get_logged_in_user_guid();
		$class = "elgg-icon elgg-icon-shop-cart";
		$text = "<span class='$class'></span>";
		$tooltip = elgg_echo("pay:basket");
		
		$basket = elgg_get_entities(array(
									'type' => 'object',
									'subtype' => 'pay_basket',
									'owner_guid' => $user_guid,
									));
																		
		// get unread messages
		$num_items = count($basket);
		if ($num_items != 0) {
			$text .= "<span class=\"messages-new\">$num_items</span>";
			$tooltip .= " (" . elgg_echo("pay:topbar:count", array(count($basket))) . ")";
		}
*/
		/*elgg_register_menu_item('topbar', array(
			'name' => 'pay_cart',
			'href' => 'pay/basket',
			'text' => $text,
			'priority' => 650,
			'title' => $tooltip,
		));*/
	}
	
	//USER SETTINGS
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => 'pay_account',
			'text' => elgg_echo('pay:account'),
			'href' => "pay/account",
		);
		elgg_register_menu_item('page', $params);
	}
	
	//Pay pages -- add the market into there as well
	if ((elgg_get_context() == "pay" || elgg_get_context() == "market" || elgg_get_context() == "pay_basket") && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();
		
		/*//BASKET
		$class = "elgg-icon elgg-icon-shop-cart";
		$tooltip = elgg_echo("pay:basket");
		
		$basket = elgg_get_entities(array(
									'type' => 'object',
									'subtype' => 'pay_basket',
									'owner_guid' => elgg_get_logged_in_user_guid(),
									));
																		
		// get unread messages
		$num_items = count($basket);
		if ($num_items != 0) {
			$text .= "<span class=\"messages-new\">$num_items</span>";
			$tooltip .= " (" . elgg_echo("pay:topbar:count", array(count($basket))) . ")";
		}

		$params = array(
			'name' => 'pay_cart',
			'href' => 'pay/basket',
			'text' => $tooltip,
			'section' => 'payment',
			'title' => $tooltip,
		);
		elgg_register_menu_item('page', $params);*/
		
		//SELLER ACCOUNT
		$params = array(
			'name' => 'pay_account_seller',
			'href' => 'pay/account/seller/'.elgg_get_page_owner_entity()->username,
			'text' => elgg_echo('pay:account:seller'),
			'section' => 'payment',
		);
		elgg_register_menu_item('page', $params);
		
		//Purchaces
		$params = array(
			'name' => 'pay_account',
			'href' => 'pay/account/'.elgg_get_page_owner_entity()->username,
			'text' => elgg_echo('pay:account'),
			'section' => 'payment',
		);
		elgg_register_menu_item('page', $params);
		
		if(elgg_is_admin_logged_in()){
			//Admin link
			$params = array(
				'name' => 'pay_admin',
				'href' => 'pay/admin',
				'text' => elgg_echo('pay:admin:withdraw'),
				'section' => 'payment',
			);
			elgg_register_menu_item('page', $params);
		}
		
	}
}
