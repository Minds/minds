<?php
/**
 * Minds Payments API:: stripe
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Core;
use Minds\Core\Payments\Customer;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class stripe implements Interfaces\Api
{
    /**
   * Returns merchant information
   * @param array $pages
   *
   * API:: /v1/merchant/:slug
   */
  public function get($pages)
  {
      $response = [];

      switch ($pages[0]) {
        case "token":
          $response['token'] = Core\Config::_()->get('payments')['stripe']['public_key'];
          break;
        case "cards":
          $stripe = Core\Di\Di::_()->get('StripePayments');

          try {
              $customer = (new Customer())->setUser(Core\Session::getLoggedInUser());
              $customerObj = $stripe->getCustomer($customer);
              if ($customerObj) {
                  $cards = $customerObj->getPaymentMethods();
              }
          } catch (\Exception $e) {
          }

          $response['cards'] = $cards ?: [];
          break;
      }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = [];

        return Factory::response($response);
    }

    public function put($pages)
    {
        $response = [];

        switch ($pages[0]) {
          case "card":
            $stripe = Core\Di\Di::_()->get('StripePayments');

            $customer = (new Customer())->setUser(Core\Session::getLoggedInUser());
            if (!$stripe->addCardToCustomer($customer, $pages[1])) {
                $response['status'] = 'error';
            }
            break;
        }
        return Factory::response($response);
    }

    public function delete($pages)
    {
        $response = [];

        switch ($pages[0]) {
          case "card":
            $stripe = Core\Di\Di::_()->get('StripePayments');

            $customer = (new Customer())->setUser(Core\Session::getLoggedInUser());
            if (!$stripe->removeCardFromCustomer($customer, $pages[1])) {
                $response['status'] = 'error';
            }
            break;
        }

        return Factory::response($response);
    }
}
