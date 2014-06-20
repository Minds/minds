<?php
/**
 * Minds Bitcoin support
 */
 
namespace minds\plugin\bitcoin;

use minds\core;

abstract class bitcoin extends \ElggPlugin     
{
    public static $bitcoin;
    
    public function __construct($plugin) {
	parent::__construct($plugin);
	
	bitcoin::$bitcoin = $this;
    }
    
    /**
     * Get the base for the API Call
     */
    abstract protected function getAPIBase();
    
    /**
     * Retrieve a wallet for a user.
     */
    abstract public function getWallet(\ElggUser $user);
    
    /**
     * Create a wallet for a user.
     * @return guid|false
     */
    abstract public function createWallet(\ElggUser $user);
    
    /**
     * When passed a wallet GUID (as stored in Elgg), will return it's current balance.
     */
    abstract public function getWalletBalance($wallet_guid);
    
    /**
     * Send a payment from a wallet to a bitcoin address.
     */
    abstract public function sendPayment($from_wallet_guid, $to_address, $amount_in_satoshi);




    /**
     * Create receive address for user
     * 
     * @param $user the user
     * @param $params Optional parameters added on to an address to identify it (e.g. handlers or labels)
     */
    abstract public function createReceiveAddressForUser(\ElggUser $user, array $params = null);
    
    /**
     * Get the receive address for user.
     */
    abstract public function getReceiveAddressForUser(\ElggUser $user);
    
    /**
     * Create receive address for the system
     */
    abstract public function createSystemReceiveAddress();
    
    /**
     * Get the receive address for the system.
     */
    abstract public function getSystemReceiveAddress();
    
    /**
     * Convert a value into bitcoins
     */
    abstract public function convertToBTC($amount, $currency = 'USD');
    
    
    

    // get wallet for user
    
    // Create receive address for user
    
    
    // Create receive handler for user
    
    
    
    
    
    
    
    // create wallet
    // Fetch wallet balance
    // Pay with wallet
    
    
    
    // INIT create core wallet
    
    
    
    
    /**
     * Initialise
     */
    public function init() {
	// TODO: Create per user bitcoin receive handler
    }
    
    
    
    
}

/**
 * Helper function to retrieve current bitcoin handler
 * @return \minds\plugin\bitcoin\bitcoin
 */
function &bitcoin()
{
    return \minds\plugin\bitcoin\bitcoin::$bitcoin;
}