<?php
/**
 * Minds Webhook: Braintree
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\webhooks;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Helpers;
use Minds\Core\Payments;

use Braintree_WebhookNotification;

class braintree implements Interfaces\Api, Interfaces\ApiIgnorePam{

  /**
   * NOT AVAILABLE
   */
  public function get($pages){

      return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));

  }

  /**
   */
  public function post($pages){

    Payments\Factory::build('braintree');
   

    $notification = Braintree_WebhookNotification::parse($_POST['bt_signature'], $_POST['bt_payload']);
    error_log(print_r($notification, true));

    switch($notification->kind){
      case Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED:
        //send a notification to the user letting them know we just approved them
        $message = "Congrats, you are now a Minds Merchant";
        Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
              'to'=>[$notification->merchantAccount->id],
              'from' => 100000000000000519,
              'notification_view' => 'custom_message',
              'params' => array('message'=>$message),
              'message'=>$message
              ));
        break;
      case Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED:
        $reason = $notification->message;  
        $message = "Sorry, we could not approve your Merchant Account: $reason";
        Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', array(
              'to'=>[$notification->merchantAccount->id],
              'from' => 100000000000000519,
              'notification_view' => 'custom_message',
              'params' => array('message'=>$message),
              'message'=>$message
              ));
        break;
    }


  }

  public function put($pages){}

  public function delete($pages){}

}
