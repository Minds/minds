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
		
		bitcoin()->createWallet($user);
		
		
		// TODO: Transfer some bitcoins to new users
		
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

	error_log("Bitcoin: Making a call to $endpoint");
	
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
	
	return $return;
    }
    
    /**
     * Store a password for a wallet.
     * @param type $wallet
     * @param type $password
     */
    protected function storeWalletPassword($wallet, $password) {
	
	// TODO: Find out a better way of storing passwords, perhaps against some sort of central password storage tool
	
	$wallet->wallet_password = $password; 
	
	return true;
    }
    
    /**
     * Retrieve password for a wallet.
     * @param type $wallet
     */
    protected function getWalletPassword($wallet) {
	return $wallet->wallet_password;
    }

    public static function cancelRecurringPaymentCallback($order_guid) {
	
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
	    
	    
	} catch (\Exception $e) {
	    error_log("BITCOIN CALLBACK: " . $e->getMessage());
	    
	    echo "ERROR.";
	}
	
    }

    public static function paymentHandler($params) {
	
	$order = get_entity($params['order_guid'], 'object');
	$user = get_entity($params['user_guid'], 'user');
	$amount = $params['amount'];
	$description = $params['description'];
	
	$minds_address = elgg_get_plugin_setting('central_bitcoin_account', 'bitcoin');
	
	if (!$user) throw new \Exception ('No user, sorry');
	if (!$order) throw new \Exception ('No order, sorry');
	if (!$minds_address) throw new \Exception('Minds bitcoin address not configured, sorry!');
	
	// Find wallet
	$wallet = $this->getWallet($user);
	if ($wallet) {
	
	    // Generate return address, register callback
	    $urls = pay_urls($params['order_guid']);
	
	    $return_url = $urls['return'];
	    $cancel_url = $urls['cancel'];
	    $callback_url =  $urls['callback'].'/bitcoin?minds_tid=' . $order->pay_transaction_id; // Set bitcoin callback endpoint
	
	    if ($this->blockchainGenerateReceivingAddress($wallet->wallet_address, $callback_url)) {
	
		// Convert amount into bitcoins
		$currency = unserialize($order->currency);
		if (is_array($currency)) $currency = $currency['code'];
		
		$amount = $this->convertToBTC($amount, $currency);
		
		error_log("BITCOIN: Payment pay being sent for $amount Bitcoins from {$params['amount']}");
		
		// Then use wallet to send payment
		if (!$this->sendPayment($wallet->wallet_address, $minds_address, $amount))
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
    protected function blockchainCreateWallet() {
	$password = md5($user->salt . microtime(true)); // Create a random password
	$api_code = elgg_get_plugin_setting('api_code', 'bitcoin');
	
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
	
	$wallet = $this->blockchainCreateWallet();

	$new_wallet = new \ElggObject();

	$new_wallet->subtype = 'bitcoin_wallet';
	$new_wallet->access_id = ACCESS_PRIVATE;
	$new_wallet->owner_guid = $user->guid;	
	$this->storeWalletPassword($new_wallet, $password);

	$new_wallet->wallet_raw = serialize($wallet);
	$new_wallet->wallet_guid = $wallet['guid'];
	$new_wallet->wallet_address = $wallet['address'];
	$new_wallet->wallet_link = $wallet['link'];
	
	$new_wallet->wallet_handler = 'blockchain';

	// Save the address to user settings
	elgg_set_plugin_user_setting('bitcoin_address', $wallet['address'], $new_wallet->owner_guid, 'bitcoin');
	
	error_log("Bitcoin: Wallet created");
	
	return $new_wallet->save();
	
    }
    
    public function createSystemWallet() {
	error_log("Bitcoin: Attempting to create a wallet for {$user->name}");
	
	$wallet = $this->blockchainCreateWallet();

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
	
	// Save the address to user settings
	elgg_set_plugin_setting('central_bitcoin_account', $wallet['address'], 'bitcoin');
	
	error_log("Bitcoin: System wallet created");
	
	return $new_wallet->save();
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
		
		return $result['balance'];
	    }
	}
	
	return false;
    }
    
    public function sendPayment($from_wallet_guid, $to_address, $amount_in_satoshi) {
	
	if ($wallet = get_entity($wallet_guid)) {
	    
	    if (elgg_instanceof($wallet, 'object', 'bitcoin_wallet'))
	    {
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
		
		return $result['tx_hash'];
	    }
	}
	
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
	$ra = $this->getReceiveAddress($user);
	
	if (!$ra) {
	    
	    $gets = "";
	    if ($params)
		$gets = '?' . http_build_query($params);
	    
	    $ra = $user->blockchain_receive_address = $this->blockchainGenerateReceivingAddress(
		    elgg_get_plugin_user_setting('bitcoin_address', $user->guid, 'bitcoin'), 
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
