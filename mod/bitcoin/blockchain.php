<?php

namespace minds\plugin\bitcoin;

use minds\core;

class blockchain extends bitcoin 
    implements \minds\plugin\pay\PaymentHandler
{

    private $blockchain_base = "https://blockchain.info/";
    
    public function __construct(){
	    parent::__construct('bitcoin');

	    $this->init();
    }
    
    public function init() {
	parent::init();
	
	// Register action handler
	elgg_register_action('bitcoin/generatewallet', dirname(__FILE__) . '/actions/create_wallet.php');
	
	// Register payment handler
	elgg_load_library('elgg:pay');
	pay_register_payment_handler('bitcoin', '\minds\plugin\bitcoin\blockchain::paymentHandler');
    }

    /**
     * Make an API call.
     */
    private function __make_call($verb, $endpoint, array $params = null, array $headers = null) {
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

	if ($error = curl_error($curl_handle)) {
	    error_log($error);
	}

	curl_close($curl_handle);

	return array('content' => json_decode($buffer) ? json_decode($buffer) : $buffer, 'response' => $http_status, 'error' => $error);
    }

    public static function cancelRecurringPaymentCallback($order_guid) {
	
    }

    public static function paymentCallback($order_guid) {
	
    }

    public static function paymentHandler($params) {
	
	$order = get_entity($params['order_guid'], 'object');
	$user = get_entity($params['user_guid'], 'user');
	$amount = $params['amount'];
	$description = $params['description'];
	
	$return_url = $urls['return'];
	$cancel_url = $urls['cancel'];
	
	$callback_url =  $urls['callback'].'/bitcoin'; // Set bitcoin callback endpoint
	
	if (!$user) throw new \Exception ('No user, sorry');
	if (!$order) throw new \Exception ('No order, sorry');
	
	// Find wallet
	$wallet = $this->getWallet($user);
	if ($wallet) {
	
	
	
	/// Get balance
	
	
	} else 
	    throw new \Exception ('User has no bitcoin wallet defined.');
    }

    public function createWallet(\ElggUser $user) {
	
	$password = md5($user->salt . microtime(true)); // Create a random password
	$api_code = elgg_get_plugin_setting('api_code', 'bitcoin');
	
	if (!$api_code) throw new \Exception ("An API Code needs to be specified before bitcoin transactions can be made.");
	
	$wallet = $this->__make_call('GET', "{$blockchain_base}api/v2/create_wallet", array(
	    'password' => $password,
	    'api_code' => $api_code
	));
	
	if ($wallet['code'] == 500)
	    throw new \Exception($wallet['response']);
	
	$new_wallet = new \ElggObject();
	$new_wallet->subtype = 'bitcoin_wallet';
	$new_wallet->access_id = ACCESS_PRIVATE;
	$new_wallet->owner_guid = $user->guid;
	
	$new_wallet->wallet_password = $password;
	$new_wallet->wallet_raw = serialize($wallet);
	
	$new_wallet->wallet_guid = $wallet->guid;
	$new_wallet->wallet_address = $wallet->address;
	$new_wallet->wallet_link = $wallet->link;
	
	return $new_wallet->save();
	
    }

    public function getWallet(\ElggUser $user) {
	if ($wallets = elgg_get_entities(array(
	    'type' => 'object',
	    'subtype' => 'bitcoin_wallet',
	    'owner_guid' => $user->guid
	))) {
	    return $wallets[0];
	}
	
	return null;
    }

    public function getWalletBalance($wallet_guid) {
	
    }

}
