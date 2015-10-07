<?php

namespace minds\plugin\bitcoin\pages;

use Minds\Core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\bitcoin\entities;

class wallet extends core\page implements Interfaces\page {

    public $context = 'bitcoin';

    /**
     * Get requests
     */
    public function get($pages) {

	if (!elgg_is_logged_in())
	    $this->forward('/register');

	$guid = \elgg_get_plugin_user_setting('wallet_guid', elgg_get_logged_in_user_guid(), 'bitcoin');

	$wallet = new entities\wallet($guid);

	if (!$wallet->guid) {
	    $content = \elgg_view_form('bitcoin/create', array('action' => '/bitcoin/wallet/create', 'class' => 'bitcoin-form'));
	} else {

	    $unlock = \elgg_view_form('bitcoin/unlock', array('action' => '/bitcoin/wallet/authorise', 'class' => 'bitcoin-form'));

	    //ok, so the wallet exists. now do we need a password to access?
	    if (!isset($_COOKIE['bitcoin_pswd'])) {

		$content = $unlock;
	    } else {

		//no, we need to ask for a password
		try {

		    if ($_COOKIE['bitcoin_pswd'] == 'void')
			throw new \Exception('Bitcoin password error');

		    $wallet->password = $_COOKIE['bitcoin_pswd'];
		    $content = \elgg_view_entity($wallet);

		    $transactions = elgg_list_entities(array('subtype' => 'bitcoin_transaction', 'owner_guid' => $wallet->owner_guid, 'full_view' => false));
		    $content .= $transactions;
		} catch (\Exception $e) {
		    $content = $unlock;
		}
	    }
	}

	$sidebar = '<div class="bitcoin-disclaimer">We encourage you to continually backup your Bitcoin on your own devices and paper wallets!</div>';

	$body = \elgg_view_layout('content', array('title' => \elgg_echo('bitcoin:wallet'), 'content' => $content, 'sidebar' => $sidebar));

	echo $this->render(array('body' => $body));
    }

    /**
     * Post comments
     */
    public function post($pages) {
	switch ($pages[0]) {
	    case 'create':
		$guid = \elgg_get_plugin_user_setting('wallet_guid', elgg_get_logged_in_user_guid(), 'bitcoin');
		$wallet = new entities\wallet($guid);

		if (!$_POST['password']) {
		    \register_error('You must supply a password');
		    $this->forward('bitcoin/wallet');
		    return false;
		}
		try {
		    $wallet->create($_POST['password']);
		    setcookie('bitcoin_pswd', $_POST['password'], time() + 120, '/');
		    $this->forward('bitcoin/wallet');
		} catch (\Exception $e) {
		    \register_error($e->getMessage());
		    $this->forward('bitcoin/wallet');
		}
		break;
	    case 'import':
		break;
	    case 'authorise':
		setcookie('bitcoin_pswd', $_POST['password'], time() + 120, '/');
		$this->forward('bitcoin/wallet');
		break;
	}
    }

    public function put($pages) {
	
    }

    public function delete($pages) {
	
    }

}
