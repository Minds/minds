<?php

namespace minds\plugin\bitcoin\pages;

use Minds\Core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\bitcoin\entities;

class send extends core\page implements interfaces\page {

    public $context = 'bitcoin';

    /**
     * Get requests
     */
    public function get($pages) {

	$guid = \elgg_get_plugin_user_setting('wallet_guid', elgg_get_logged_in_user_guid(), 'bitcoin');
	$wallet = new entities\wallet($guid);



	$content = \elgg_view_form('bitcoin/send', array('action' => '/bitcoin/send', 'class' => 'bitcoin-form'));

	$unlock = \elgg_view_form('bitcoin/unlock', array('action' => '/bitcoin/wallet/authorise', 'class' => 'bitcoin-form'));


	$body = \elgg_view_layout('content', array('title' => \elgg_echo('bitcoin:send'), 'content' => $content));

	echo $this->render(array('body' => $body));
    }

    /**
     * Post comments
     */
    public function post($pages) {

	try {
	    $to_address = isset($_POST['address']) ? $_POST['address'] : NULL;

	    if ($user = get_user_by_username($to_address)) {
		//check if the user has a wallet
		$to_wallet = \elgg_get_plugin_user_setting('wallet_guid', $user->guid, 'bitcoin');
		if (!$to_wallet) {
		    \register_error('Sorry, this user doesn\'t have a wallet configured.');
		}
		$to_wallet = new entities\wallet($to_wallet);
		$to_address = $to_wallet->getReceivingAddress('bitcoin/reveive/' . $to_wallet->guid);
	    }

	    $guid = \elgg_get_plugin_user_setting('wallet_guid', elgg_get_logged_in_user_guid(), 'bitcoin');
	    $wallet = new entities\wallet($guid);

	    $wallet->send($to_address, \minds\plugin\bitcoin\start::toSatoshi($_POST["amount"]), $_POST['password']);

	} catch (\Exception $e) {
	    register_error($e->getMessage());
	}
	$this->forward('bitcoin/wallet');
    }

    public function put($pages) {
	
    }

    public function delete($pages) {
	
    }

}
