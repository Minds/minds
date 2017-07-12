<?php
/**
 * Minds Webhook: Stripe
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

class stripe implements Interfaces\Api, Interfaces\ApiIgnorePam
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
      error_log("\n [webhooks][stripe]:: hit first entrace point");
      $input = @file_get_contents("php://input");


      $stripe = Core\Di\Di::_()->get('StripePayments');

      $config = Core\Di\Di::_()->get('Config');
      $signingKey = $config->payments['stripe']['webhook_keys']['default'];

      if ($pages[0] == 'connect') {
          $signingKey = $config->payments['stripe']['webhook_keys']['connect'];
      }

      $hooks = new Payments\Stripe\Webhooks((new Payments\Hooks())->loadDefaults(), $stripe);
      $hooks->setSignature($_SERVER["HTTP_STRIPE_SIGNATURE"])
        ->setSigningKey($signingKey)
        ->setPayload($input);

      $hooks->run();


      // Do something with $event_json

      http_response_code(200); // PHP 5.4 or greater
      exit;

      /*$gateway = isset($pages[0]) ? $pages[0] : 'default';

      $bt = Payments\Factory::build('braintree', ['gateway'=>$gateway]);

      $hooks = new Payments\Hooks();
      $hooks->loadDefaults();

      $webhooks = new Payments\Braintree\Webhooks($hooks, $bt);
      $webhooks->setSignature($_POST['bt_signature'])
        ->setPayload($_POST['bt_payload'])
        ->run();*/

      return Factory::response([]);
  }


    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
