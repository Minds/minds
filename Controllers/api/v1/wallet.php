<?php
/**
 * Minds Wallet API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class wallet implements Interfaces\Api
{
    private $ex_rate = 0.01;

    /**
     * Returns the wallet info
     * @param array $pages
     *
     * API:: /v1/wallet/:slug
     */
    public function get($pages)
    {
//        Factory::isLoggedIn();
        $response = [];

        switch ($pages[0]) {

            case "count":
                $count = (int) Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false);

                $satoshi_rate = 1;//@todo make this configurable for admins
                $satoshi = $count * $satoshi_rate;
                $btc = ($satoshi / 1000000000);

                $response['count'] = $count;
                $response['cap'] = 5000;
                $response['min'] = 10;
                $response['boost_rate'] = 1;
                $response['ex'] = array(
                    'usd' => 0.01
                );
                $response['satoshi'] = $satoshi;
                $response['btc'] = sprintf('%.9f', $btc);
                $response['usd'] = round($count / 10000, 2);
                break;

            case "transactions":
                Factory::isLoggedIn();
                $entities = Core\Entities::get(array('subtype'=>'points_transaction', 'owner_guid'=> Core\Session::getLoggedinUser()->guid, 'limit'=>isset($_GET['limit']) ? $_GET['limit'] : 12, 'offset'=>isset($_GET['offset']) ? $_GET['offset'] : ""));
                if (isset($_GET['offset']) && $_GET['offset']) {
                    array_shift($entities);
                }

                if ($entities) {
                    $response['transactions'] = factory::exportable($entities);
                    $response['load-next'] = (string) end($entities)->guid;
                }
                break;
            case "subscription":
                Factory::isLoggedIn();
                $db = new Core\Data\Call("user_index_to_guid");
                $subscriptionIds = $db->getRow(Core\Session::getLoggedinUser()->guid . ":subscriptions:recurring");
                if(!isset($subscriptionIds[0])){
                    return Factory::response([]);
                }

                $braintree = Payments\Factory::build("Braintree", ['gateway'=>'default']);
                $subscription = $braintree->getSubscription($subscriptionIds[0]);
                if($subscription)
                  $response['subscription'] = $subscription->export();
                break;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();
        $response = [];
        switch ($pages[0]) {
            case "quote":
                $ex_rate = $this->ex_rate;
                $points = $_POST['points'];
                $usd = $ex_rate * $points;
                return Factory::response(array('usd'=>$usd));
                break;
            case "charge":

                $ex_rate = $this->ex_rate;
                $points = $_POST['points'];
                $usd = $ex_rate * $points;


                try{
                    $card = \minds\plugin\payments\services\paypal::factory()->createCard([
                        'type' => $_POST['type'],
                        'number' => (int) str_replace(' ', '', $_POST['number']),
                        'month' => $_POST['month'],
                        'year' => $_POST['year'],
                        'sec' => $_POST['sec'],
                        'name' => $_POST['name'],
                        'name2' => $_POST['name2']
                    ]);
                } catch(\Exception $e){
                    return Factory::response(array('status'=>'error'));
                }

                try {
                    $response['id'] = \Minds\plugin\payments\start::createPayment("$points purchase", $usd, $card->getID());
                    if ($response['id']) {
                        Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, $points, null, "purchase");
                    }
                } catch (\Exception $e) {
                    $response['status'] = 'error';
                    $response['message'] = $e->getMessage();
                }

                break;
            case "paypal":
                switch ($pages[1]) {
                    case "confirm":
                        $ex_rate = $this->ex_rate;
                        $points = $_POST['points'];
                        $usd = $ex_rate * $points;

                        $payment = \Minds\plugin\payments\services\paypal::factory()->capture($_POST['id'], $usd);
                        if ($payment->getId()) {
                            //ok, now charge!
                            Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, $points, null, "purchase");
                            Helpers\Wallet::logPurchasedPoints(Core\Session::getLoggedinUser()->guid, $points);
                        } else {
                            $response['status'] = 'error';
                        }

                    break;
                }
                break;
            case "purchase-once":
                $amount = $_POST['amount'];
                $points = $_POST['points'];
                $usd = $this->ex_rate * $points;

                $sale = (new Payments\Sale())
                  ->setAmount($usd)
                  ->setCustomerId(Core\Session::getLoggedInUser()->guid)
                  ->setSettle(true)
                  ->setFee(0)
                  ->setNonce($_POST['nonce']);

                try {
                    $result = Payments\Factory::build('braintree', ['gateway'=>'default'])->setSale($sale);
                    Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, $points, null, "purchase");
                    Helpers\Wallet::logPurchasedPoints(Core\Session::getLoggedinUser()->guid, $points);
                } catch (\Exception $e) {
                    $response['status'] = "error";
                    $response['message'] = $e->getMessage();
                }
                break;
            case "subscription":
                $points = $_POST['points'];
                $usd = $this->ex_rate * $points;

                $payment_service = Core\Payments\Factory::build('Braintree', ['gateway'=>'default']);
                $db = new Core\Data\Call("user_index_to_guid");
                try {

                    $customer = $payment_service->createCustomer(
                        (new Payments\Customer)
                        ->setId(Core\Session::getLoggedInUser()->guid)
                        ->setEmail(Core\Session::getLoggedInUser()->getEmail())
                    );

                    $payment_method = $payment_service->createPaymentMethod(
                        (new Payments\PaymentMethod)
                        ->setCustomer($customer)
                        ->setPaymentMethodNonce($_POST['nonce'])
                    );

                    $subscriptionIds = $db->getRow(Core\Session::getLoggedinUser()->guid . ":subscriptions:recurring");
                    if($subscriptionIds){
                        $payment_service->cancelSubscription(
                            (new Payments\Subscriptions\Subscription)
                            ->setId($subscriptionIds[0])
                        );
                    }
                    $subscription = $payment_service->createSubscription(
                        (new Payments\Subscriptions\Subscription)
                        ->setPaymentMethod($payment_method)
                        ->setPlanId(Core\Config::_()->payments['points_plan_id'])
                        ->setPrice($usd)
                    );

                    $db->insert(Core\Session::getLoggedinUser()->guid . ":subscriptions:recurring", [$subscription->getId()]);
                    $db->insert("subscription:" . $subscription->getId(), [Core\Session::getLoggedinUser()->guid]);

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
            case "withdraw":
                break;
        }


        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        switch($pages[0]){
          case "subscription":
              $payment_service = Core\Payments\Factory::build('Braintree', ['gateway'=>'default']);
              $db = new Core\Data\Call("user_index_to_guid");
              $subscriptionIds = $db->getRow(Core\Session::getLoggedinUser()->guid . ":subscriptions:recurring");

              $result = $payment_service->cancelSubscription(
                  (new Payments\Subscriptions\Subscription)
                  ->setId($subscriptionIds[0])
              );
              //if($result->status == "Canceled"){
                  $db->removeAttributes(Core\Session::getLoggedinUser()->guid . ":subscriptions:recurring", [0]);
              //}
              break;
        }
        return Factory::response(array());
    }
}
