<?php
/**
 * Minds Payments Plans
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Core\Entities as CoreEntities;
use Minds\Core\Payments;
use Minds\Interfaces;

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

              $isPlus = Core\Session::getLoggedInUser()->isPlus() && $entity->owner_guid == '730071191229833224';

              if ($plan->getStatus() == 'active' || Core\Session::isAdmin() || $isPlus) {
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
              
              try {
                  $subscription = (new Payments\Subscriptions\Subscription())
                    ->setCustomer($customer)
                    ->setMerchant($entity)
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
                $user = new Entities\User($pages[1]);
                if (!$user || !$user->guid) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'User not found'
                    ]);
                }

                $merchant = (new Payments\Merchant())
                    ->setId($user->getMerchant()['id']);

                $repo = new Payments\Plans\Repository();
                $repo
                    ->setEntityGuid($user->guid)
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
            case 'wire':
                $entity = CoreEntities::get([ 'guids' => [$pages[1]]])[0];
                $user_guid = $entity->type == 'user' ? $entity->guid : $entity->ownerObj['guid'];

                if (!$user_guid) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'User not found'
                    ]);
                }

                /** @var Core\Wire\Manager $manager */
                $manager = Core\Di\Di::_()->get('Wire\Manager');
                $wires = $manager->get(['type' => 'sent', 'user_guid' => $user_guid, 'order' => 'DESC']);

                $wire = null;
                foreach($wires as $w) {
                    if($w->isActive() && $w->isRecurring() && $w->getMethod('usd')){
                        $wire = $w;
                    }
                }
                if(!$wire) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Wire not found'
                    ]);
                }

                $merchant = (new Payments\Merchant())
                    ->setId($wire->getFrom()->getMerchant()['id']);

                $repo = new Payments\Plans\Repository();
                $repo->setEntityGuid($wire->getEntity()->getGuid())
                    ->setUserGuid($wire->getFrom()->guid);

                try {
                    $plan = $repo->getSubscription('wire');

                    $subscription = (new Payments\Subscriptions\Subscription())
                        ->setMerchant($merchant)
                        ->setId($plan->getSubscriptionId());

                    $stripe->cancelSubscription($subscription);

                    $repo->cancel('wire');
                    $wire->setActive(0)->save();
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
