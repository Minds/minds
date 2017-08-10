<?php
/**
 * Minds Payments API - Subscriptions
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Core\Payments;
use Minds\Interfaces;

class subscriptions implements Interfaces\Api
{
    /**
     * Returns user subscriptions
     * @param array $pages
     *
     * API:: /v1/payments/subscriptions
     */
    public function get($pages)
    {
        $response = [];
        $plansIds = [];

        if (isset($_GET['plansIds'])) {
            $plansIds = $_GET['plansIds'];
            if (!is_array($plansIds)) {
                $plansIds = explode(',', $plansIds);
            }
        }

        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
        $repo = new Payments\Plans\Repository();

        $result = $repo
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->getAllSubscriptions($plansIds, [
                'offset' => $offset
            ]);

        $stripe = Core\Di\Di::_()->get('StripePayments');

        foreach ($result as $plan) {

            $s = [
              'status' => $plan->getStatus(),
              'entity_guid' => $plan->getEntityGuid(),
              'expires' => $plan->getExpires(),
              'id' => $plan->getSubscriptionId(),
              'amount' => $plan->getAmount(),
              'plan' => $plan->getName()
            ];


            if ($entity_guid = $plan->getEntityGuid()) {
                $entity = Entities\Factory::build($entity_guid);
                if ($entity) {
                    $s['entity'] = $entity->export();
                }
            }

            $response['subscriptions'][] = $s;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([
              'status' => 'error',
              'message' => 'subscription id must be supplied'
            ]);
        }

        $subscription_id = $pages[0];
        $subscription = (new Payments\Subscriptions\Subscription)->setId($subscription_id);

        $repo = new Payments\Plans\Repository();
        $plan = $repo->getSubscriptionById($subscription_id);

        //detect if the subscription is braintree or stripe
        if (strpos($subscription_id, 'sub_', 0) >= -1) {

            if (Core\Session::getLoggedInUser()->referrer){
                $referrer = new Entities\User(Core\Session::getLoggedInUser()->referrer);
                $subscription->setMerchant($referrer->getMerchant());
            }

            $stripe = Core\Di\Di::_()->get('StripePayments');
            $subscription = $stripe->getSubscription($subscription);
        } else {
            $braintree = Payments\Factory::build("Braintree", ['gateway'=>'default']);
            $subscription = $braintree->cancelSubscription($subscription);
        }

        $repo->cancel($plan);

        return Factory::response([]);
    }
}
