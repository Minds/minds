<?php
/**
 * Payments
 */

namespace minds\plugin\payments;

use Minds\Components;
use Minds\Core;
use Minds\Core\Events;

class start extends Components\Plugin{

	public function init(){

		$link = new Core\Navigation\Item();
		Core\Navigation\Manager::add($link
			->setPriority(7)
			->setIcon('account_balance')
			->setName('Wallet')
			->setTitle('Wallet')
			->setPath('/wallet')
		);

    \elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
        if($row->type == "object" && $row->subtype == 'points_transaction'){
            return new entities\PointsTransaction($row);
        }
    });

		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\payments\\pages";
		core\router::registerRoutes(array(
			'/wallet' => "minds\\plugin\\payments\\pages\\wallet",
			'/api/v1/wallet' => "minds\\plugin\\payments\\api\\v1\\wallet"
		));

	}

	static public function createPayment($details, $amount, $card){

		$transaction = new entities\transaction();
		$transaction->amount = $amount;
		$transaction->description = $details;
		$transaction->card = $card;
		$transaction->save(); //save as pending.

		try{
			$paypal_obj= services\paypal::factory()->payment($amount, $currency = 'USD', $details, $card);
		}catch(\Exception $e){
			var_dump($e); exit;
		}
		$transaction->paypal_id = $paypal_obj->getID();
		$transaction->status = 'complete';

		self::sendConfirmation(array($transaction->getOwnerEntity(false)->getEmail(), 'mark@minds.com', 'bill@minds.com', 'billing@minds.com'), $transaction);

		return $transaction->save();

	}

	static public function sendConfirmation($to, $transaction){
		elgg_set_viewtype('email');
		//\elgg_send_email('mark@minds.com', 'mark@kramnorth.com', 'New Order', '<h1>Thanks for your order..</h1> <p>Your order has been succesfully processed</p>');
		if(core\plugins::isActive('phpmailer')){
			$view = elgg_view('payments/confirmation', array('transaction'=>$transaction));
			\phpmailer_send('mark@minds.com', 'Minds Billing', $to, '', 'Your order: ' . $transaction->guid, $view, NULL, true);
		}
		elgg_set_viewtype('default');
	}

    /**
     * @return void
     */
    static public function createTransaction($user_guid, $points, $entity_guid = NULL, $description = ""){
        $transaction = new entities\PointsTransaction();
        $transaction->setPoints($points)
            ->setOwnerGuid($user_guid)
            ->setDescription($description)
            ->setEntityGuid($entity_guid)
            ->save();
        /**
         * Update the userscount
         */
        \Minds\Helpers\Counters::increment($user_guid, 'points', $points);
    }

}
