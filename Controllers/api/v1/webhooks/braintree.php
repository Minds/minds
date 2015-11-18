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

use Braintree_WebhookNotification;
use Braintree_WebhookNotification;

class braintree implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
   * NOT AVAILABLE
   */
  public function get($pages)
  {
      return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
  }

  /**
   */
  public function post($pages)
  {
      $notification = Braintree_WebhookNotification::parse($_POST['btSignature'], $_POST['btPayload']);
      error_log(print_r($notification, true));

      switch ($notification->kind) {
        case Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED:
            break;
        case Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED:
            break;
    }

      return Factory::response($response);
  }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
