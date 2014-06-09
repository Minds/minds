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
     * Retrieve a wallet for a user.
     */
    abstract public function getWallet(\ElggUser $user);
    
    /**
     * Create a wallet for a user.
     */
    abstract public function createWallet(\ElggUser $user);
    
    /**
     * When passed a wallet GUID (as stored in Elgg), will return it's current balance.
     */
    abstract public function getWalletBalance($wallet_guid);
    
    
    

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
 * @return bitcoin
 */
function &bitcoin()
{
    return \minds\plugin\bitcoin\bitcoin::$bitcoin;
}