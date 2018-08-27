<?php
/**
 * Minds Plus API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities\User;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;
use Minds\Entities;

class plus implements Interfaces\Api
{

    /**
     * Returns plus info
     * @param array $pages
     *
     * API:: /v1/plust/:slug
     */
    public function get($pages)
    {
        $response = [];

        $plus = new Core\Plus\Subscription();
        $plus->setUser(Core\Session::getLoggedInUser());
        $response['active'] = $plus->isActive();

        return Factory::response($response);
    }

    public function post($pages)
    {
        $response = [];

        $plus = new Core\Plus\Subscription();
        $plus->setUser(Core\Session::getLoggedInUser());

        switch ($pages[0]) {
            case "verify":
                $user = Core\Session::getLoggedInUser();
                $request = [
                    'guid' => (string) $user->guid,
                    'link1' => $_POST['link1'],
                    'link2' => $_POST['link2'],
                    'description' => $_POST['description']
                ];
                $db = new Core\Data\Call('entities_by_time');
                $db->insert('verify:requests', [ $user->guid => json_encode($request) ]);
                break;

            case "subscription":

                $stripe = Core\Di\Di::_()->get('StripePayments');
                $source = $_POST['source'];

                $customer = (new Payments\Customer())
                  ->setUser(Core\Session::getLoggedInUser());

                if (!$stripe->getCustomer($customer) || !$customer->getId()) {
                    //create the customer on stripe
                    try {
                        $customer->setPaymentToken($_POST['source']);
                        $customer = $stripe->createCustomer($customer);
                    } catch (\Exception $e) {
                        return Factory::response([
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ]);
                    }
                }

                $subscription = (new Payments\Subscriptions\Subscription())
                  ->setPlanId('plus')
                  ->setPaymentMethod('money')
                  ->setQuantity(1)
                  //->setCoupon('EZH8eAZy') //temporary $1 crypto onboarding
                  ->setStatus('active')
                  ->setUser(Core\Session::getLoggedInUser());


                if (Core\Session::getLoggedInUser()->referrer) {
                    $referrer = new Entities\User(Core\Session::getLoggedInUser()->referrer);
                    $subscription->setMerchant($referrer)
                      ->setFee(0.75); //payout 25% to referrer

                    try{
                        $stripe->createPlan((object) [
                          'id' => 'plus',
                          'amount' => 5,
                          'merchantId' => $referrer->getMerchant()['id']
                        ]);
                    } catch(\Exception $e){}
                }

                try {

                    try {
                        $subscription->setId($stripe->createSubscription($subscription));
                    } catch (\Exception $e) {
                        return Factory::response([
                          'status' => 'error',
                          'message' => $e->getMessage()
                        ]);
                    }

                    /**
                     * Save the subscription to our user subscriptions list
                     */
                    $plus
                        ->setUser(Core\Session::getLoggedInUser())
                        ->create($subscription);

                    $user = Core\Session::getLoggedInUser();
                    $user->plus = true;
                    $user->save();

                    $plusGuid = "730071191229833224";
                    $user->subscribe($plusGuid);

                    return Factory::response([
                        'subscriptionId' => $subscription->getId()
                    ]);
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

    public function put($pages)
    {
        $user = Core\Session::getLoggedInUser();

        switch ($pages[0]) {
            case "boost":
                $user->disabled_boost = true;
                $user->save();
                break;
        }
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $user = Core\Session::getLoggedInUser();
        $plus = new Core\Plus\Subscription();
        $plus->setUser($user);

        $stripe = Core\Di\Di::_()->get('StripePayments');

        switch ($pages[0]) {
            case "subscription":
                $subscription = $plus->getSubscription();

                if ($user->referrer){
                    $referrer = new User($user->referrer);
                    $subscription->setMerchant($referrer->getMerchant());
                }

                if ($subscription) {
                    $subscription = $stripe->cancelSubscription($subscription);
                    $plus->cancel();
                }

                $user->plus_expires = 0;
                $user->save();
                break;
            case "boost":
                $user->disabled_boost = false;
                $user->save();
                break;
        }
        return Factory::response([]);
    }

}
