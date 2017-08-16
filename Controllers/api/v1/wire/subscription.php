<?php
/**
 * Created by Marcelo.
 * Date: 27/07/2017
 */

namespace Minds\Controllers\api\v1\wire;

use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Payments;
use Minds\Entities;
use Minds\Entities\User;

class subscription implements Interfaces\Api
{
    public function get($pages)
    {
        $response = [];

        return Factory::response($response);
    }

    /**
     * Creates a recurring wire
     * @param $pages
     * @return mixed
     */
    public function post($pages)
    {
        $response = [];
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => ':guid must be passed in uri']);
        }

        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'Entity not found']);
        }

        $user = new User($entity->owner_guid); // merchant

        $method = $_POST['method'];
        $amount = $_POST['amount'];

        switch ($method) {
            case 'points':
                // todo
                break;
            case 'money':
                if (!$user->getMerchant()['id']) {
                    $this->sendNotification();
                }
                $source = $_POST['source'];

                $customer = (new Payments\Customer())
                    ->setUser(Core\Session::getLoggedInUser());

                $stripe = Core\Di\Di::_()->get('StripePayments');

                if (!$stripe->getCustomer($customer) || !$customer->getId()) {
                    //create the customer on stripe
                    $customer->setPaymentToken($source);
                    $customer = $stripe->createCustomer($customer);
                }

                //look for current subscription
                $this->cancelSubscription($user);

                $plan = $stripe->getPlan('wire', $user->getMerchant()['id']);

                if(!$plan) {
                    $stripe->createPlan((object) [
                        'id' => 'wire',
                        'amount' => $amount,
                        'merchantId' => $user->getMerchant()['id']
                    ]);
                }

                $subscription = (new Payments\Subscriptions\Subscription())
                    ->setPlanId('wire')
                    ->setQuantity(1)
                    ->setCustomer($customer)
                    ->setMerchant($user);

                try {

                    try {
                        $subscription_id = $stripe->createSubscription($subscription);
                    } catch (\Exception $e) {
                        return Factory::response([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ]);
                    }

                    /**
                     * Save the subscription to our user subscriptions list
                     */
                    $plan = (new Payments\Plans\Plan)
                        ->setName('wire')
                        ->setEntityGuid(0)
                        ->setUserGuid(Core\Session::getLoggedInUser()->guid)
                        ->setSubscriptionId($subscription_id)
                        ->setStatus('active')
                        ->setExpires(-1); //indefinite
                    $repo = new Payments\Plans\Repository();
                    $repo->add($plan);

                    return Factory::response([
                        'subscriptionId' => $subscription_id
                    ]);
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }

                break;
            case 'btc':
                // todo
                break;
        }


        /*        $service = Methods\Factory::build($method);

        try{
            $service->setAmount($amount)
              ->setEntity($entity)
              ->setPayload((array) $_POST['payload'])
              ->execute();
        */

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $this->cancelSubscription();

        return Factory::response([]);
    }


    private function cancelSubscription(User $user)
    {
        $repo = new Payments\Plans\Repository();
        $plan = $repo->setEntityGuid(0)
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->getSubscription('wire');

        $subscription = (new Payments\Subscriptions\Subscription)
            ->setId($plan->getSubscriptionId());

        if (Core\Session::getLoggedInUser()->referrer) {
            $referrer = new Entities\User(Core\Session::getLoggedInUser()->referrer);
            $subscription->setMerchant($referrer->getMerchant());
        }

        $stripe = Core\Di\Di::_()->get('StripePayments');

        try {
            $result = $stripe->cancelSubscription($subscription, ['stripe_account' => $user->getMerchant()->getMerchant()['id']]);
            $repo->cancel('wire');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function sendNotification()
    {
        $message = 'Somebody wanted to send you a money wire, but you need to setup your merchant account first! You can monetize your account in your Wallet.';
        Core\Events\Dispatcher::trigger('notification', 'wire', [
            'to' => [$this->entity->owner_guid],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => ['message' => $message],
            'message' => $message,
        ]);

        throw new \Exception('Sorry, this user cannot receive USD.');
    }
}
