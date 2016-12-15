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
use Minds\Entities;
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
              $entity = Entities\Factory::build($pages[1]);

              if (!$entity) {
                  return Factory::response([
                      'status' => 'error',
                      'message' => 'Post not found'
                      ]);
              }
              $owner = $entity->getOwnerEntity();

              $repo = new Payments\Plans\Repository();
              $plan = $repo->setEntityGuid($entity->owner_guid)
                ->setUserGuid(Core\Session::getLoggedInUser()->guid)
                ->getSubscription('exclusive');

              if ($plan->getStatus() == 'active' || Core\Session::isAdmin()) {
                  $response['subscribed'] = true;
                  $entity->paywall = false;
                  $response['entity'] = $entity->export();
              } else {
                  $owner = new Entities\User($entity->owner_guid, false);
                  $response['subscribed'] = false;

                  $plan = $stripe->getPlan("exclusive", $owner->getMerchant()['id']);

                  if ($plan) {
                      $response['amount'] = $plan->amount / 100;
                  } else {
                      $response = [
                        'status' => 'error',
                        'message' => "We couldn't find the plan"
                      ];
                  }
              }

              break;
      }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = [];

        $stripe = Core\Di\Di::_()->get('StripePayments');
        $lu = Core\Di\Di::_()->get('Database\Cassandra\Lookup');

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

              if (!$stripe->getCustomer($customer) || !$customer->getId()) {
                  //create the customer on stripe
                  $customer->setPaymentToken($_POST['nonce']);
                  $customer = $stripe->createCustomer($customer);
              }
              
              $merchant = (new Payments\Merchant)
                ->setId($entity->getMerchant()['id']);

              try {
                  $subscription = (new Payments\Subscriptions\Subscription())
                    ->setCustomer($customer)
                    ->setMerchant($merchant)
                    ->setPlanId($pages[2]);

                  $subscription_id = $stripe->createSubscription($subscription);
              } catch (\Exception $e) {
                  return Factory::response([
                    'status' => 'error',
                    'message' => $e->getMessage()
                  ]);
              }

              $plan = (new Payments\Plans\Plan)
                ->setName($pages[2])
                ->setEntityGuid($pages[1])
                ->setUserGuid(Core\Session::getLoggedInUser()->guid)
                ->setSubscriptionId($subscription_id)
                ->setStatus('active')
                ->setExpires(-1); //indefinite

              $repo = new Payments\Plans\Repository();
              $repo->add($plan);

              $response['subscribed'] = true;
              $entity->paywall = false;
              $response['entity'] = $entity->export();

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
        $response = [];

        $stripe = Core\Di\Di::_()->get('StripePayments');

        switch ($pages[0]) {
            case 'exclusive':
                $entity = new Entities\User($pages[1]);
                if (!$entity || !$entity->guid) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'User not found'
                    ]);
                }

                $merchant = (new Payments\Merchant())
                    ->setId($entity->getMerchant()['id']);

                $repo = new Payments\Plans\Repository();
                $repo
                    ->setEntityGuid($entity->guid)
                    ->setUserGuid(Core\Session::getLoggedInUser()->guid);

                try {
                    $plan = $repo->getSubscription('exclusive');

                    $subscription = (new Payments\Subscriptions\Subscription())
                        ->setMerchant($merchant)
                        ->setId($plan->getSubscriptionId());

                    $stripe->cancelSubscription($subscription);

                    $repo->cancel('exclusive');
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }

                break;
        }

        return Factory::response($response);
    }
}
