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
use Minds\Entities;

class wallet implements Interfaces\Api
{
    private $ex_rate = 0.001;

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
                $config = array_merge([
                    'network' => [
                        'min' => 100,
                        'max' => 5000,
                    ],
                ], (array) Core\Di\Di::_()->get('Config')->get('boost'));

                $response['cap'] = $config['network']['max'];
                $response['min'] = $config['network']['min'];
                $response['boost_rate'] = 1;
                $response['ex'] = array(
                    'usd' => 0.001
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

                /** @var Payments\Points\Manager $pointsManager */
                $pointsManager = Core\Di\Di::_()->get('Payments\Points');
                $pointsManager->setUser(Core\Session::getLoggedinUser());

                $response['subscription'] = $pointsManager->getSubscription();
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
            case "purchase-once":
                /** @var Payments\Points\Manager $pointsManager */
                $pointsManager = Core\Di\Di::_()->get('Payments\Points');
                $pointsManager->setUser(Core\Session::getLoggedinUser());

                try {
                    $response['success'] = $pointsManager->buyOnce($_POST);
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
                break;
            case "subscription":
                /** @var Payments\Points\Manager $pointsManager */
                $pointsManager = Core\Di\Di::_()->get('Payments\Points');
                $pointsManager->setUser(Core\Session::getLoggedinUser());

                try {
                    $response['subscriptionId'] = $pointsManager->create($_POST);
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
        return Factory::response([]);
    }

    public function delete($pages)
    {
        switch ($pages[0]) {
          case "subscription":
              /** @var Payments\Points\Manager $pointsManager */
              $pointsManager = Core\Di\Di::_()->get('Payments\Points');
              $pointsManager->setUser(Core\Session::getLoggedinUser());

              try {
                  $pointsManager->cancel();
              } catch (\Exception $e) {
                  return Factory::response([
                      'status' => 'error',
                      'message' => $e->getMessage()
                  ]);
              }
              break;
        }
        return Factory::response([]);
    }
}
