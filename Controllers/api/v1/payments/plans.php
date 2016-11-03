<?php
/**
 * Minds Payments Plans
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class plans implements Interfaces\Api
{
    /**
   * Returns plan information or whether a plan exists
   * @param array $pages
   *
   * API:: /v1/playments/plans/:slug
   */
  public function get($pages)
  {
      $response = [];

      switch ($pages[0]) {
          case "payment-methods":
              //return if the customer has any payment methods
              $customer = (new Payments\Customer())
                ->setUser(Core\Session::getLoggedInUser());

              $response['payment_methods'] = $customer->getPaymentMethods();

              break;
          case "exclusive":
              $stripe = Core\Di\Di::_()->get('StripePayments');
              $plan = $stripe->getPlan("{$pages[1]}:exclusive");

              if ($plan) {
                  $response['amount'] = $plan->amount;
              } else {
                  $response = [
                    'status' => 'error',
                    'message' => "We couldn't find the plan"
                  ];
              }
              break;
      }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = [];

        $stripe = Core\Di\Di::_()->get('StripePayments');

        switch ($pages[0]) {
          case "subscribe":
              $entity = Entities\Factory::build($pages[1]);
              if (!$entity) {
                  return Factory::response([
                    'status' => 'error',
                    'message' => 'Entity not found'
                  ]);
              }

              $customer = (new Payments\Customer())
                ->setUser(Core\Session::getLoggedInUser());

              if (!$customer->getId()) {
                  //create the customer on stripe
                  $customer->setPaymentToken($_POST['token']);
                  $customer = $stipe->createCustomer($customer);
              }

              $subscription = (new Payments\Subscriptions\Subscription())
                ->setCustomer($customer)
                ->setPlanId($pages[1] . ":" . $pages[2]);

              $plan = (new Payments\Plans\Plan)
                ->setName($pages[2])
                ->setEntityGuid($pages[1])
                ->setUserGuid(Core\Session::getLoggedInUser()->guid)
                ->setStatus('pending')
                ->setExpires(-1); //indefinite

              $repo = new Payments\Plans\Repository();
              $repo->add($plan);

              //charge the customer now, to give immediate access
              $sale = (new Payments\Sale())
                ->capture() //charge immediately
                ->setAmount($entity->getMerchant()['exclusive']['amount'])
                ->setMerchant($entity)
                ->setCustomerId($customer->id);
              $id = $stripe->setSale($sale);

              $plan->setPaymentId($id)
                ->setPaymentTs(time())
                ->setStatus('active');
              $repo->add($plan);

              break;

        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
