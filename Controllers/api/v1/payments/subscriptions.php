<?php
/**
 * Minds Payments API - Subscriptions
 *
 * @version 2
 * @author Emi Balbuena
 */

namespace Minds\Controllers\api\v1\payments;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Payments;
use Minds\Entities;
use Minds\Interfaces;

class subscriptions implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        /** @var Payments\Subscriptions\Repository $repository */
        $repository = Core\Di\Di::_()->get('Payments\Subscriptions\Repository');

        $subscriptions = $repository
            ->getList([
                'user_guid' => Core\Session::getLoggedInUser()->guid
            ]);
    
        return Factory::response([
            'subscriptions' => Factory::exportable($subscriptions)
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Subscription ID is required'
            ]);
        }

        /** @var Payments\Subscriptions\Repository $repository */
        $repository = Core\Di\Di::_()->get('Payments\Subscriptions\Repository');

        /** @var Payments\Subscriptions\Manager $manager */
        $manager = Core\Di\Di::_()->get('Payments\Subscriptions\Manager');

        $subscription_id = $pages[0];
        $subscription = $repository->get($subscription_id);

        if (!$subscription) {
            return Factory::response([
                'status' => 'error',
                'message' => 'subscription not found'
            ]);
        }

        if ($subscription->getPaymentMethod() == "money") {
            if (strpos($subscription->getId(), 'sub_', 0) >= -1) {
                if ($subscription->getEntity()->guid) { //if a wire
                    $user = new Entities\User($subscription->getEntity()->guid);
                    $subscription->setMerchant($user->getMerchant());
                } elseif (Core\Session::getLoggedInUser()->referrer){
                    $referrer = new Entities\User(Core\Session::getLoggedInUser()->referrer);
                    $subscription->setMerchant($referrer->getMerchant());
                }
                $stripe = Core\Di\Di::_()->get('StripePayments');
                $stripe->cancelSubscription($subscription);
            } else {
                $braintree = Payments\Factory::build("Braintree", ['gateway'=>'default']);
                $braintree->cancelSubscription($subscription);
            }
        }
        
        $success = $manager
            ->setSubscription($subscription)
            ->cancel();

        if ($subscription->getPlanId() == 'plus') {
            $user = Core\Session::getLoggedInUser();
            $user->plus = false;
            $user->save();
        }

        return Factory::response([
            'done' => (bool) $success
        ]);
    }

}
