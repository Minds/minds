<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class boost implements Interfaces\Api
{
    private $rate = 1;

    /**
     * Return impressions/points for a request
     * @param array $pages
     *
     * @SWG\GET(
     *     tags={"boost"},
     *     summary="Returns information regarding a boost, or the current boost rates",
     *     path="/v1/boost/{guid}",
     *     @SWG\Parameter(
     *      name="guid",
     *      in="path",
     *      description="the guid",
     *      required=true,
     *      type="string"
     *     ),
     *     @SWG\Response(name="200", description="Array")
     * )
     * @SWG\GET(
     *     tags={"boost"},
     *     summary="Returns  the current boost rates",
     *     path="/v1/boost/rate",
     *     @SWG\Response(name="200", description="Array"),
     *     security={
     *         {
     *             "minds_oauth2": {}
     *         }
     *     }
     * )
     */
    public function get($pages)
    {
        $response = array();

        switch ($pages[0]) {
            case is_numeric($pages[0]):
                /*$entity = entities\Factory::build($pages[0]);
                $response['entity'] = $entity->export();
                //going to assume this is a channel only review for now
                $boost_ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
                $guids = $boost_ctrl->getReviewQueue(1, $pages[0]);
                if (!$guids || key($guids) != $pages[0]) {
                    return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
                }
                $response['points'] = reset($guids);*/
                $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
                $boost = $pro->getBoostEntity($pages[0]);
                if ($boost->getState() != 'created') {
                    return Factory::response(['status'=>'error', 'message'=>'entity not in boost queue']);
                }
                $response['entity'] = $boost->getEntity()->export();
                $response['points'] = $boost->getBid();
            break;
            case "rates":
              $response['balance'] = (int) Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false);
              $response['rate'] = $this->rate;

              $config = array_merge([
                  'network' => [
                      'min' => 100,
                      'max' => 5000,
                  ],
              ], (array) Core\Di\Di::_()->get('Config')->get('boost'));

              $response['cap'] = $config['network']['max'];
              $response['min'] = $config['network']['min'];
            break;
            case "p2p":
              $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
              $boosts = $pro->getReviewQueue(100);
              $boost_entities = [];
              //only show 'point boosts and 'created' (not accepted or rejected)
              foreach ($boosts as $i => $boost) {
                  if ($boost->getType() != 'points' || $boost->getState() != 'created') {
                      unset($boosts[$i]);
                      continue;
                  }
                  $boost_entities[$i] = $boost->getEntity();
                  $boost_entities[$i]->guid = $boost->getGuid();
                  $boost_entities[$i]->points = $boost->getBid();
              }

              $response['boosts'] = factory::exportable($boost_entities, array('points'));
              break;
            case "newsfeed":
            case "content":
              $pro = Core\Boost\Factory::build(ucfirst($pages[0]));
              $boosts = $pro->getOutbox(isset($_GET['limit']) ? $_GET['limit'] : 12, isset($_GET['offset']) ? $_GET['offset'] : "");
              $response['boosts'] = Factory::exportable($boosts);
              break;
        }

        return Factory::response($response);
    }

    /**
     * Boost an entity
     * @param array $pages
     *
     * API:: /v1/boost/:type/:guid
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        if (!isset($pages[0])) {
            return Factory::response(array('status' => 'error', 'message' => ':type must be passed in uri'));
        }

        if (!isset($pages[1])) {
            return Factory::response(array('status' => 'error', 'message' => ':guid must be passed in uri'));
        }

        $impressions = isset($_POST['impressions']) ? $_POST['impressions'] : $_POST['points'];

        if (!isset($impressions)) {
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be sent in post body'));
        }

        //if($impressions != round($impressions))
        //    return Factory::response(array('status' => 'error', 'message' => 'impressions must be a whole number'));

        $impressions = round($impressions);
        if ((!isset($_POST['destination']) || $_POST['destination'] == '') && round($impressions) == 0) {
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be a whole number'));
        }

        $config = array_merge([
            'network' => [
                'min' => 100,
                'max' => 5000,
            ],
        ], (array) Core\Di\Di::_()->get('Config')->get('boost'));

        if ($impressions < $config['network']['min'] || $impressions > $config['network']['max']) {
            return Factory::response([
                'status' => 'error',
                'message' => "You must boost between {$config['network']['min']} and {$config['network']['max']} points"
            ]);
        }

        $response = [];
        $entity = Entities\Factory::build($pages[1]);
        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'entity not found']);
        }

        if ($pages[0] == "object" || $pages[0] == "user" || $pages[0] == "suggested") {
            $pages[0] = "content";
        }

        if ($pages[0] == "activity") {
            $pages[0] = "newsfeed";
        }

        switch (ucfirst($pages[0])) {
          case "Newsfeed":
          case "Content":

            $points = ($impressions / $this->rate);

            $boost = (new Entities\Boost\Network())
              ->setEntity($entity)
              ->setBid($points)
              ->setOwner(Core\Session::getLoggedInUser())
              ->setState('created')
              ->setHandler(lcfirst($pages[0]));

            $result = Core\Boost\Factory::build(ucfirst($pages[0]))->boost($boost);
            if ($result) {
                if (isset($_POST['newUserPromo']) && $_POST['newUserPromo'] && $impressions == 200) {
                    $transactionId = "free";
                } else {
                    $transactionId = Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, 0 - $points, $boost->getGuid(), "boost");
                }
                $boost->setId((string) $result)
                  ->setTransactionId($transactionId)
                  ->save();
                Core\Events\Dispatcher::trigger('notification', 'boost', [
                  'to'=> [ Core\Session::getLoggedinUser()->guid ],
                  'entity' => $pages[1],
                  'notification_view' => 'boost_submitted',
                  'params' => [ 'impressions' => $impressions ],
                  'impressions' => $impressions
                ]);
            } else {
                $response['status'] = 'error';
            }
            break;
          case "Channel": //this is a polyfill for the new boost PRO
            $result = Core\Boost\Factory::build("Channel", [
              'destination'=>isset($_POST['destination']) ? $_POST['destination'] : null
            ])->boost($entity, $impressions);
            Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, -$impressions, $pages[1], "p2p boost");
            if ($result) {
                Core\Events\Dispatcher::trigger('notification', 'boost', [
                  'to'=> [ $pages[2] ],
                  'entity' => $pages[1],
                  'notification_view' => 'boost_gift',
                  'params' => [ 'impressions'=>$impressions ],
                  'impressions' => $impressions
                ]);
            } else {
                $response['status'] = 'error';
            }
            break;
          default:
              $response['status'] = 'error';
              $response['message'] = "boost handler not found";
        }

        return Factory::response($response);
    }

    /**
     * Called when a boost is to be accepted (assume channels only right now
     * @param array $pages
     */
    public function put($pages)
    {
        Factory::isLoggedIn();

        $response = [];
        $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
        $boost = $pro->getBoostEntity($pages[0]);

        //double check status before issuing points
        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and can not be approved'
            ]);
        }

        //now add to the newsfeed
        $embeded = Entities\Factory::build($boost->getEntity()->guid); //more accurate, as entity doesn't do this @todo maybe it should in the future
        \Minds\Helpers\Counters::increment($boost->getEntity()->guid, 'remind');

        $activity = new Entities\Activity();
        $activity->p2p_boosted = true;
        if ($embeded->remind_object) {
            $activity->setRemind($embeded->remind_object)->save();
        } else {
            $activity->setRemind($embeded->export())->save();
        }

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to'=>array($boost->getOwner()->guid),
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_peer_accepted',
            'params' => ['bid'=>$boost->getBid(), 'type'=>$boost->getType()]
        ]);

        $pro->accept($pages[0]);

        Helpers\Wallet::createTransaction($boost->getDestination()->guid, $boost->getBid(), $boost->getGuid(), "Peer Boost");

        $response['status'] = 'success';

        return Factory::response($response);

        //validate the points
        /*$ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
        $guids = $ctrl->getReviewQueue(1, $pages[0]);
        if (!$guids) {
            return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        }
        $points = reset($guids);
        Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, $points, $pages[0], "boost (remind)");
        $accept = $ctrl->accept($pages[0], $points);
        return Factory::response(array());*/
    }

    /**
     * Called when a boost is rejected (assume channels only right now)
     */
    public function delete($pages)
    {
        Factory::isLoggedIn();

        $response = [];
        $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
        $boost = $pro->getBoostEntity($pages[0]);

        //double check status before issuing points
        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and can not be approved'
            ]);
        }

        Helpers\Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Rejected Peer Boost");

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to'=>array($boost->getOwner()->guid),
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_peer_rejected',
            'params' => ['bid'=>$boost->getBid(), 'type'=>$boost->getType()]
        ]);

        $pro->reject($pages[0]);
        $response['status'] = 'success';

        return Factory::response($response);

        /*$ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
        $guids = $ctrl->getReviewQueue(1, $pages[0]);
        if (!$guids) {
            return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        }
        $points = reset($guids);
        $entity = new \Minds\Entities\Activity($pages[0]);
        Helpers\Wallet::createTransaction($entity->owner_guid, $points, $pages[0], "boost refund");
        $ctrl->reject($pages[0]);*/
    }
}
