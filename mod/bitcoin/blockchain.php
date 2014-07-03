<?php

namespace minds\plugin\bitcoin;

use minds\core;

class blockchain extends bitcoin 
    implements \minds\plugin\pay\PaymentHandler
{
    
    public function __construct(){
	    parent::__construct('bitcoin');

	    $this->init();
    }
    
    public function init() {
	parent::init();
	
	// Create a wallet for every new user
	elgg_register_plugin_hook_handler('register', 'user', function($hook, $type, $value, $params) {
	    try {
		$user = elgg_extract('user', $params);
		
		$new_wallet = bitcoin()->createWallet($user);
		if (!$new_wallet) throw new \Exception("Could not generate a wallet for the new user...");
		
		// grant new user some bitcoins
		if ($satoshi = elgg_get_plugin_setting('satoshi_to_new_user', 'bitcoin')) {
		    if ($wallet_guid = elgg_get_plugin_setting('central_bitcoin_wallet_guid', 'bitcoin')) {
			if (!bitcoin()->sendPayment($wallet_guid, $new_wallet->wallet_address, $satoshi))
				throw new \Exception("There was a problem granting satoshi to {$new_wallet->wallet_address}");
		    } else 
			error_log("BITCOIN: No system bitcoin address!");
		} else 
		    error_log("BITCOIN: No satoshi value set!");
	    } catch (\Exception $ex) {
		error_log("BITCOIN: " . $ex->getMessage());
	    }
	});
	
	// Register action handler
	elgg_register_action('bitcoin/generatewallet', dirname(__FILE__) . '/actions/create_wallet.php');
	elgg_register_action('bitcoin/generatesystemwallet', dirname(__FILE__) . '/actions/create_system_wallet.php');
	
	// Register payment handler
	elgg_load_library('elgg:pay');
	pay_register_payment_handler('bitcoin', '\minds\plugin\bitcoin\blockchain::paymentHandler');
	
	// Handle recurring payments (DIY, until blockchain support this natively)
	elgg_register_plugin_hook_handler('cron', 'daily', function(){
	    
	    $now = time();
	    $offset = 0;
	    $limit = 50;
	    $processed = array();
	    $new_indexes = array();
	    $current_user = null;
	    $minds_address = elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin');
	    
	    $ia = elgg_set_ignore_access();
	    
	    // Retrieve all recurring payments which are outstanding and not being processed
	    while ($results = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'blockchain_subscription',
		'limit' => $limit,
		'offset' => $offset
	    ))) {
		foreach ($results as $r) {
		    if (
			    ($now > $r->due_ts) && // Due
			    (!$r->locked) && // not locked
			    (!$r->cancelled) // cancelled
			    ){
			
			$r->locked = time(); // Lock it to prevent reprocessing
			
			// Try and process payment
			try {
			    $order = get_entity($r->order_guid);
			    if (!$order) throw new \Exception("No order was found attached to this subscription!");

			    $current_user = $user = get_user($order->owner_guid); 
			    if (!$user) throw new \Exception("No user was found attached to this subscription!");
			
			    $wallet = $this->getWallet($user);
			    if (!$wallet) throw new \Exception ('User has no bitcoin wallet defined.');
			    
			    $amount = bitcoin()->convertToBTC($amount, $currency);
			    if (!$amount) throw new \Exception("Problem converting $currency into BTC");
			    
			    if (!$minds_address) throw new \Exception("Minds BTC account is not configured, you should not be seeing this!");
			    
			    // Attempt to put through the payment
			    if (!bitcoin()->sendPayment($wallet->guid, $minds_address, $amount))
				throw new \Exception('Sorry, your bitcoin transaction couldn\'t be sent');
			    
			    // Got here, so payment was successful - schedule for garbage collection and create a receipt
			    
			    $processed[] = $r->guid; // we have processed it, so schedule it for garbage collection.
			    
			    // Create a receipt for this payment
			    $receipt = new \ElggObject();
			    $receipt->subtype = 'blockchain_subscription_receipt';
			    $receipt->owner_guid = $r->owner_guid;
			    $receipt->order_guid = $r->order_guid;
			    $receipt->renew_period = $r->renew_period;
			    $receipt->due_ts = $r->due_ts;
			    $receipt->amount = $r->amount;
			    $receipt->currency = $r->currency;
			    $receipt->processed = time();
			    $receipt->save();
			    
			    // Schedule the next payment
			    $subscription = new \ElggObject();
			    $subscription->subtype = 'blockchain_subscription';
			    $subscription->owner_guid = $r->owner_guid;
			    $subscription->order_guid = $r->order_guid;
			    $subscription->renew_period = $r->renew_period;
			    $subscription->due_ts = $now + $r->renew_period;
			    $subscription->amount = $r->amount;
			    $subscription->currency = $r->currency;

			    $guid = $subscription->save();
			    
			    // Create a lookup, so we can easily cancel this order in future
			    $new_indexes[$order->guid] = $guid;
			   
			    
			    notify_user($current_user->guid, elgg_get_site_entity()->guid, 'Minds subscription renewed', "You minds subscription has been renewed for $amount bitcoins ({$r->amount} {$r->currency}}).");
			    
			    $current_user = null;
			} catch (\Exception $e) {
			    error_log("BITCOIN SUBSCRIPTION: " . $e->getMessage());
			    
			    // Unlock and try again next time
			    $r->locked = 0;
			    $r->failures++;
			    
			    // Notify the user that something went wrong
			    notify_user($current_user->guid, elgg_get_site_entity()->guid, 'Problem with your Minds subscription', 'There was a problem processing your subscription: \n\n' . $e->getMessage());
			    
			    // TODO: Deactivate subscribed services (or better, those services should probably know about orders)
			    $current_user = null;
			}
		    }
		}
	    }
	    
	    // Delete all processed 
	    foreach ($processed as $guid){
		$e = get_entity($guid);
		$e->delete();
	    }
	    
	    // Now, update the indexes
	    $db = new minds\core\data\call('entities_by_time');
	    foreach ($new_indexes as $order_guid => $guid)
		$db->insert('object:pay:blockchain:subscription', array($order_guid => $guid));
	    
	    $ia = elgg_set_ignore_access($ia);
	    
	});

	// Endpoints
	elgg_register_page_handler('blockchain', function($pages){
	    
	    switch ($pages[0]) {
		case 'endpoint':
		default:
		    switch ($pages[1]) {
			case 'receivingaddress' :
				$user = false;
				if (isset($pages[2])) {
				    $user = get_user_by_username ($pages[2]);
				}
				
				// Payment received
				bitcoin()->logReceived($_GET['input_address'], $user, $_GET['value']);
				
				// Trigger a hook
				if (elgg_trigger_plugin_hook('payment-received', 'blockchain', array(
				    'user' => $user,
				    'username' => $pages[2],
				    'get_variables' => $_GET,
				    
				    'value' =>  $_GET['value'],
				    'value_in_btc' => $_GET['value'] / 100000000,
					
				    'input_address' => $_GET['input_address'],
				    'destination_address' => $_GET['destination_address'],
				    
				    'confirmations' => $_GET['confirmations'],
				    
				    'transaction_hash' => $_GET['transaction_hash'],
				    'input_transaction_hash' => $_GET['input_transaction_hash'],
				),false))
					echo "*ok*";
				else
				    echo "ERROR";
			    break;
		    }
	    }
	    
	    return true;
	});
    }

    /**
     * Make an API call.
     */
    private function __make_call($verb, $endpoint, array $params = null, array $headers = null) {
	
	if (!preg_match('/https?:\/\//', $endpoint))
		$endpoint = $this->getAPIBase () . ltrim($endpoint, '/');
	
	$req = "";
	if ($params) {
	    $req = http_build_query($params);
	}

	$curl_handle = curl_init();
	
	switch (strtolower($verb)) {
	    case 'post':
		curl_setopt($curl_handle, CURLOPT_POST, 1);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $req);
		break;
	    case 'delete':
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Override request type
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $req);
		break;
	    case 'put':
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT'); // Override request type
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $req);
		break;
	    case 'get':
	    default:
		curl_setopt($curl_handle, CURLOPT_HTTPGET, true);
		if (strpos($endpoint, '?') !== false) {
		    $endpoint .= '&' . $req;
		} else {
		    $endpoint .= '?' . $req;
		}
		break;
	}

	
	error_log("Bitcoin: Making a $verb call to $endpoint");
	
	curl_setopt($curl_handle, CURLOPT_URL, $endpoint);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, "Minds Bitcoin Agent");
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);

	// Allow plugins and other services to extend headers, allowing for plugable authentication methods on calls
	if (!empty($new_headers) && (is_array($new_headers))) {
	    if (empty($headers))
		$headers = array();
	    $headers = array_merge($headers, $new_headers);
	}

	if (!empty($headers) && is_array($headers)) {
	    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	}

	$buffer = curl_exec($curl_handle);
	$http_status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
	
	error_log("Bitcoin: Call $endpoint returned code $http_status"); 
	
	if (!$http_status)
	    throw new \Exception("Bitcoin: There was a problem executing the curl call...");

	if ($error = curl_error($curl_handle)) 
	    throw \Exception("Bitcoin: $error");

	curl_close($curl_handle);
	
	if (json_decode($buffer))
	    $buffer = json_decode($buffer, true); 

	$return = array();
	$return['content'] = $buffer;
	$return['response'] = $http_status;
	$return['error'] = $error;
	
	if ($return['response'] == 500)
	    error_log("Bitcoin: Returned blockchain error '{$return['content']}'");
	
	return $return;
    }
    
    /**
     * Store a password for a wallet.
     * @param type $wallet
     * @param type $password
     */
    protected function storeWalletPassword($wallet, $password) {
	
	if (!$password) throw new \Exception("Attempt to set a null password on a wallet.");
	
	// TODO: Find out a better way of storing passwords, perhaps against some sort of central password storage tool
	
	error_log("Bitcoin: Storing password $password");
	
	$wallet->wallet_password = $password; 
	
	return true;
    }
    
    /**
     * Retrieve password for a wallet.
     * @param type $wallet
     */
    protected function getWalletPassword($wallet) {
	error_log("Bitcoin: Wallet {$wallet->guid} password is {$wallet->wallet_password}");
	return $wallet->wallet_password;
    }

    public static function cancelRecurringPaymentCallback($order_guid) {
	
	
	// Look for any future subscriptions and delete
	$db = new \minds\core\data\call('entities_by_time');
	if ($guids = $db->getRow('object:pay:blockchain:subscription', array('offset'=> $order_guid, 'limit'=>1))) {
	    $subscription = get_entity($guids[0], 'object');
	
	    if (elgg_instanceof($subscription, 'object', 'blockchain_subscription')) // Belts and braces
	    {
		$subscription->cancelled = time();
		pay_update_order_status($order_guid, 'Cancelled');
	    }
	    else
		throw new \Exception("Returned guid is not an order subscription, you shouldn't see this.");
	}
	
	
    }

    public static function paymentHandler_callback($order_guid) {
	
	try {
	    // Get order
	    $order = get_entity($order_guid, 'object');
	    if (!$order) throw new \Exception("Sorry, no order could be found.");
	    
	    // Verify security markers 
	    if ($_GET['minds_tid']!=$order->pay_transaction_id)
		throw new \Exception('Sorry, but the security markers do not match up!');
	    
	    // Attach a payment history to the order.
	    $order->annotate('order_details', serialize($_GET));
	    
	    // Update the order status
	    pay_update_order_status($order_guid, 'Completed');
	    
	    echo "*ok*";
	    
	} catch (\Exception $e) {
	    error_log("BITCOIN CALLBACK: " . $e->getMessage());
	    
	    echo "ERROR.";
	}
	
    }

    public static function paymentHandler($params) {
	
	global $CONFIG;
	
	$order = get_entity($params['order_guid'], 'object');
	$user = get_entity($params['user_guid'], 'user');
	$amount = $params['amount'];
	$description = $params['description'];
	
	$minds_address = elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin');
	
	if (!$user) throw new \Exception ('No user, sorry');
	if (!$order) throw new \Exception ('No order, sorry');
	if (!$minds_address) throw new \Exception('Minds bitcoin address not configured, sorry!');
	
	// Find wallet
	$wallet = bitcoin()->getWallet($user);
	if ($wallet) {
	
	    // Generate return address, register callback
	    $urls = pay_urls($params['order_guid']);
	
	    $return_url = $urls['return'];
	    $cancel_url = $urls['cancel'];
	    $callback_url =  $urls['callback'].'/bitcoin?minds_tid=' . $order->pay_transaction_id; // Set bitcoin callback endpoint
	
	    if (bitcoin()->blockchainGenerateReceivingAddress($wallet->wallet_address, $callback_url)) {
	
		// Convert amount into bitcoins
		$currency = unserialize($order->currency);
		if (!$currency) $currency = pay_get_currency();
		if (is_array($currency)) $currency = $currency['code'];
		
		error_log("BITCOIN: Converting $amount to " . print_r($currency, true));
		
		$amount = bitcoin()->convertToBTC($amount, $currency);
		
		error_log("BITCOIN: Payment pay being sent for $amount Bitcoins from {$params['amount']}");
		
		$amount = self::toSatoshi($amount);
		
		error_log("BITCOIN: Converted value to $amount satoshi");
		
		if ($CONFIG->debug) {
		    $amount = 0.0005;
		    error_log("BITCOIN: We're in debug mode, so we're squishing the result to $amount");
		}
		
		/**
		 * Handle recurring payments.
		 * Currently blockchain doesn't do this for us, so we have to fudge it.
		 */
		if ($params['recurring'])
		{
            
		    // Set recurring period based on expiry (default 1 year)
		    $ia = elgg_set_ignore_access($ia);
		    $item = get_entity($order->object_guid, 'object');
		    $expires = $item->expires;
		    if (!$expires) $expires = MINDS_EXPIRES_YEAR;
		    $ia = elgg_set_ignore_access($ia);
            
		    // Create a future dated subscription marker to re-order this subscription after a certain date (picked up by our cron tracker)
		    $subscription = new \ElggObject();
		    $subscription->subtype = 'blockchain_subscription';
		    $subscription->owner_guid = $order->owner_guid;
		    $subscription->order_guid = $order->guid;
		    $subscription->renew_period = $expires;
		    $subscription->due_ts = time() + $expires;
		    $subscription->amount = $params['amount'];
		    $subscription->currency = $currency;
		    
		    $guid = $subscription->save();
		    if (!$guid)
			throw new \Exception ("There was a problem creating your subscription, you have not been charged. Please try again, or contact Minds for help.");
		
		    // Create a lookup, so we can easily cancel this order in future
		    $db = new \minds\core\data\call('entities_by_time');
		    $db->insert('object:pay:blockchain:subscription', array($order->guid => $guid));
		}
		
		
		// Then use wallet to send payment
		if (!bitcoin()->sendPayment($wallet->guid, $minds_address, $amount))
			throw new \Exception('Sorry, your bitcoin transaction couldn\'t be sent');
		
		forward($return_url);

	    } else 
		throw new \Exception('Could not create a bitcoin callback address for ' . $wallet->wallet_address);
	
	} else 
	    throw new \Exception ('User has no bitcoin wallet defined.');
    }

    /**
     * Low level wallet creation
     */
    protected function blockchainCreateWallet($password) {
	
	$api_code = elgg_get_plugin_setting('api_code', 'bitcoin');
	
	if (!$password) throw new \Exception("Bitcoin: Attempting to create a wallet with a blank password");
	if (!$api_code) throw new \Exception ("Bitcoin: An API Code needs to be specified before bitcoin transactions can be made.");
	
	$wallet = $this->__make_call('GET', "api/v2/create_wallet", array(
	    'api_code' => $api_code,
	    'password' => $password,
	    'email' => $user->email
	));
	
	if ($wallet['response'] == 500)
	    throw new \Exception("Bitcoin: "  . $wallet['content']);
	
	$wallet = $wallet['content'];
	
	error_log("Bitcoin: Wallet response is " . var_export($wallet, true));
	
	// Belts and braces
	if (empty($wallet['address'])) throw new \Exception("Bitcoin: Wallet call seemed to work, but no address was found");
	
	return $wallet;
    }
    
    public function createWallet(\ElggUser $user) {
	
	error_log("Bitcoin: Attempting to create a wallet for {$user->name}");
	
	$password = md5($user->salt . microtime(true));
	$wallet = $this->blockchainCreateWallet($password);

	$new_wallet = new \ElggObject();

	$new_wallet->subtype = 'bitcoin_wallet';
	$new_wallet->access_id = ACCESS_PRIVATE;
	$new_wallet->owner_guid = $user->guid;	
	$this->storeWalletPassword($new_wallet, $password); // Create a random password);

	$new_wallet->wallet_raw = serialize($wallet);
	$new_wallet->wallet_guid = $wallet['guid'];
	$new_wallet->wallet_address = $wallet['address'];
	$new_wallet->wallet_link = $wallet['link'];
	
	$new_wallet->wallet_handler = 'blockchain';

	if ($guid = $new_wallet->save()) {
	    // Save the address to user settings
	    elgg_set_plugin_user_setting('bitcoin_address', $wallet['address'], $new_wallet->owner_guid, 'bitcoin');
	    elgg_set_plugin_user_setting('bitcoin_wallet', $wallet['guid'], $new_wallet->owner_guid, 'bitcoin');
	    elgg_set_plugin_user_setting('bitcoin_wallet_object', $guid, $new_wallet->owner_guid, 'bitcoin');

	    error_log("Bitcoin: Wallet created");
	
	    return $guid;
	}
	
	return false;
    }
    
    public function createSystemWallet() {
	error_log("Bitcoin: Attempting to create a wallet for {$user->name}");
	
	$password = md5($user->salt . microtime(true));
	$wallet = $this->blockchainCreateWallet($password);

	$new_wallet = new \ElggObject();

	$ia = elgg_set_ignore_access();
	
	$new_wallet->subtype = 'bitcoin_wallet';
	$new_wallet->access_id = ACCESS_PRIVATE;
	$new_wallet->owner_guid = 0;	
	$this->storeWalletPassword($new_wallet, $password);

	$new_wallet->wallet_raw = serialize($wallet);
	$new_wallet->wallet_guid = $wallet['guid'];
	$new_wallet->wallet_address = $wallet['address'];
	$new_wallet->wallet_link = $wallet['link'];
	
	$new_wallet->wallet_handler = 'blockchain';

	$ia = elgg_set_ignore_access($ia);
	
	if ($guid = $new_wallet->save()) {
	
	    // Save the address to user settings
	    elgg_set_plugin_setting('central_bitcoin_account', $wallet['address'], 'bitcoin');
	    elgg_set_plugin_setting('central_bitcoin_wallet_guid', $wallet['guid'], 'bitcoin'); // Shortcut for wallet guid
	    elgg_set_plugin_setting('central_bitcoin_wallet_object_guid', $guid, 'bitcoin'); // Shortcut for wallet guid
	
	    error_log("Bitcoin: System wallet created");
	    
	    return $guid;
	} 
	
	return false;
    }

    public function importWallet($wallet_guid, $address, $password = null, \ElggUser $user = null, $system = false)
    {		
	error_log("Bitcoin: Importing $wallet_guid -> $address with password $password");
	
	if (!$wallet_guid) throw new \Exception("No wallet uuid provided");
	if (!$user) $user = elgg_get_logged_in_user_entity ();
	if (!$user) throw new \Exception("No user provided to import");
	
	if (!$system)
	    $wallet_obj = $this->getWallet($user);
	if (!$wallet_obj) {
	    // No wallet already set, create new one
	    $wallet_obj = new \ElggObject();
	    
	    $wallet_obj->subtype = 'bitcoin_wallet';
	    $wallet_obj->access_id = ACCESS_PRIVATE;
	    if ($system)
		$wallet_obj->owner_guid = 0;
	    else
		$wallet_obj->owner_guid = $user->guid;
	}
	
	if (!$password) $password = $this->getWalletPassword ($wallet_obj);
	if (!$password) $password = md5($user->salt . microtime(true));
	$this->storeWalletPassword($wallet_obj, $password);
		
	$wallet_obj->wallet_guid = $wallet_guid;
	$wallet_obj->wallet_address = $address;
	$wallet_obj->wallet_link = "https://blockchain.info/wallet/{$wallet_guid}";
	
	$wallet_obj->wallet_handler = 'blockchain';
	
	// Fudge to prevent chicken and egg on tipjar...
	if (!$system) elgg_set_plugin_user_setting('tmp_bitcoin_address', $address, $wallet_obj->owner_guid, 'bitcoin');

	if ($guid = $wallet_obj->save()) {
	    
	    // Attempt to retrieve an address, if none specified
	    if (!$address) {
		$addresses = $this->getAddressesFromWallet($guid);
		if ($addresses) {
		    $address = $addresses[0]->address;
		    $wallet_obj->wallet_address = $address;
		}
	    }
	    
	    // Save the address to user settings
	    if ($system) {
		elgg_set_plugin_setting('central_bitcoin_account', $address, 'bitcoin');
		elgg_set_plugin_setting('central_bitcoin_wallet_guid', $wallet_guid, 'bitcoin'); // Shortcut for wallet guid
		elgg_set_plugin_setting('central_bitcoin_wallet_object_guid', $guid, 'bitcoin'); // Shortcut for wallet guid

		error_log("Bitcoin: System wallet imported");
	    }
	    else {
		elgg_set_plugin_user_setting('bitcoin_address', $address, $wallet_obj->owner_guid, 'bitcoin');
		elgg_set_plugin_user_setting('bitcoin_wallet', $wallet_guid, $wallet_obj->owner_guid, 'bitcoin');
		elgg_set_plugin_user_setting('bitcoin_wallet_object', $guid, $wallet_obj->owner_guid, 'bitcoin');

		error_log("Bitcoin: Wallet imported");
	    }
	
	    return $guid;
	}
	
	return false;
    }
    
    public function getWallet(\ElggUser $user) {
	error_log("Bitcoin: Getting wallet for {$user->name}");
	
	if ($wallets = elgg_get_entities(array(
	    'type' => 'object',
	    'subtype' => 'bitcoin_wallet',
	    'owner_guid' => $user->guid
	))) {
	    error_log("Bitcoin: Found wallets: " . print_r($wallets, true));
	    return $wallets[0];
	}
	else
	    error_log("Bitcoin: No wallet found");
	
	return null;
    }

    public function getWalletBalance($wallet_guid) {
	
	if ($wallet = get_entity($wallet_guid)) {
	 
	    if (elgg_instanceof($wallet, 'object', 'bitcoin_wallet'))
	    {
		$wallet_guid = $wallet->wallet_guid;
		$result = $this->__make_call('GET', "merchant/$wallet_guid/balance", array(
		    'password' => $this->getWalletPassword($wallet),
		));
		
		if ($result['response'] == 500)
		    throw new \Exception("Bitcoin: "  . $result['content']);
		 
		$result = $result['content'];
		
		if (isset($result['balance']))
		    return self::toBTC ($result['balance']);
		else
		    throw new \Exception("Bitcoin: " . $result['error']);
		
	    }
	} 
	
	return false;
    }
    
    public function getAddressesFromWallet($wallet_guid)
    {
	if ($wallet = get_entity($wallet_guid)) {
	 
	    if (elgg_instanceof($wallet, 'object', 'bitcoin_wallet'))
	    {
		$wallet_guid = $wallet->wallet_guid;
		$result = $this->__make_call('GET', "merchant/$wallet_guid/list", array(
		    'password' => $this->getWalletPassword($wallet),
		));
		
		if ($result['response'] == 500)
		    throw new \Exception("Bitcoin: "  . $result['content']);
		 
		$result = $result['content'];
		
		if (isset($result['addresses']))
		    return $result['addresses'];
		else
		    throw new \Exception("Bitcoin: " . $result['error']);
		
	    }
	} 
	
	return false;
    }
    
    public function sendPayment($from_wallet_guid, $to_address, $amount_in_satoshi) {
		
	global $CONFIG;
	
	error_log("BITCOIN: Sending from $from_wallet_guid");
	
	if ($wallet = get_entity($from_wallet_guid)) {
	    
	    if (elgg_instanceof($wallet, 'object', 'bitcoin_wallet'))
	    {
		error_log("BITCOIN: Got a wallet, making a call.");
		
		$wallet_guid = $wallet->wallet_guid;
		$result = $this->__make_call('GET', "merchant/$wallet_guid/payment", array(
		    'main_password' => $this->getWalletPassword($wallet),
		    
		    'to' => $to_address,
		    'amount' => $amount_in_satoshi
		));
		
		if ($result['response'] == 500)
		    throw new \Exception("Bitcoin: "  . $result['content']);
		
		$result = $result['content'];
		
		system_message($result['message']);
	
		error_log("BITCOIN: Transaction hash is {$result['tx_hash']}");
		
		if ($CONFIG->debug) {
		    $amount_in_satoshi = 0.0005;
		    error_log("BITCOIN: We're in debug mode, so we're squishing the result to $amount_in_satoshi");
		}
		
		// Log the transaction
		$this->sendPayment(get_user($wallet->owner_guid, $to_address, $amount_in_satoshi));
		
		return $result['tx_hash'];
	    }
	    else error_log("BITCOIN: Wallet $wallet_guid is not a bitcoin_wallet");
	}
	else error_log("BITCOIN: Couldn't get wallet");
	
	return false;
    }

    protected function getAPIBase() {
	return "https://blockchain.info/";
    }
    
    /**
     * Low level function for generating a receive address for a given callback.
     * @param type $callback
     */
    protected function blockchainGenerateReceivingAddress($bitcoin_address, $callback = "") {
	$result = $this->__make_call('get', 'api/receive', array(
	    'method' => 'create',
	    'address' => $bitcoin_address,
	    'callback_url' => $callback
	));
	
	if ($result['response'] == 500)
	    throw new \Exception("Bitcoin: "  . $result['content']);
	
	$result = $result['content'];
	
	return $result['input_address'];
    }

    public function createReceiveAddressForUser(\ElggUser $user, array $params = null) {
	$ra = $this->getReceiveAddressForUser($user);
	
	if (!$ra) {
	    
	    $gets = "";
	    if ($params)
		$gets = '?' . http_build_query($params);
	    
	    $ra = $user->blockchain_receive_address = $this->blockchainGenerateReceivingAddress(
		    elgg_get_plugin_user_setting('bitcoin_address', $user->guid, 'bitcoin') ? 
			elgg_get_plugin_user_setting('bitcoin_address', $user->guid, 'bitcoin') : elgg_get_plugin_user_setting('tmp_bitcoin_address', $user->guid, 'bitcoin'), // Fudge to prevent chicken and egg on tipjar
		    elgg_get_site_url() . 'blockchain/endpoint/receivingaddress/' . $user->username . $gets
		    );
	}
	return $ra;
    }

    public function getReceiveAddressForUser(\ElggUser $user) {
	if ($user->blockchain_receive_address)
	    return $user->blockchain_receive_address;
	
	return false;
    }

    public function createSystemReceiveAddress() {
	$ra = $this->getSystemReceiveAddress();
	
	if (!$ra) 
	    $ra = $this->blockchainGenerateReceivingAddress(
		    elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin'), 
		    elgg_get_site_url() . 'blockchain/endpoint/receivingaddress/'
		    );
	
	return $ra;
    }

    public function getSystemReceiveAddress() {
	return elgg_get_plugin_setting('central_bitcoin_receive_address', 'bitcoin');
    }

    public function convertToBTC($amount, $currency = 'USD') {
	
	if ($result = $this->__make_call('get', 'tobtc', array('currency' => $currency, 'value' => $amount))) {
	    if ($result['response'] == 500)
		throw new \Exception("Bitcoin: "  . $result['content']);
	
	    return $result['content'];
	
	}
	
	return false;
    }


}
