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
                $entity = entities\Factory::build($pages[0]);
                $response['entity'] = $entity->export();
                //going to assume this is a channel only review for now
                $boost_ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
                $guids = $boost_ctrl->getReviewQueue(1, $pages[0]);
                if (!$guids || key($guids) != $pages[0]) {
                    return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
                }
                $response['points'] = reset($guids);
            break;
            case "rates":
              $response['balance'] = (int) Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false);
              $response['rate'] = $this->rate;
              $response['cap'] = 800;
              $response['min'] = 5;
            break;
            case "p2p":
              $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
              $boosts = $pro->getReviewQueue(100);
              //only show 'point boosts and 'created' (not accepted or rejected)
              foreach($boosts as $i => $boost){
                if($boost->getType() != 'points' || $boost->getState() != 'created')
                    unset($boosts[$i]);
              }
              $response['boosts'] = Factory::exportable($boosts);
              $response['load-next'] = (string) end($boosts)->getGuid();

                /*$db = new Core\Data\Call('entities_by_time');
                $queue_guids = $db->getRow("boost:channel:" . Core\Session::getLoggedinUser()->guid  . ":review");
                if ($queue_guids) {
                    $entities =  Core\Entities::get(array('guids'=>array_keys($queue_guids)));
                    foreach ($entities as $guid =>$entity) {
                        $entities[$guid]->points = $queue_guids[$entity->guid];
                    }
                    $response['boosts'] = factory::exportable($entities, array('points'));
                }*/
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

        $response = array();
        if (Core\Boost\Factory::build(ucfirst($pages[0]), array('destination'=>isset($_POST['destination']) ? $_POST['destination'] : null))->boost($pages[1], $impressions)) {
            //dont use rate for p2p boosts
            if (isset($_POST['destination']) && $_POST['destination']) {
                $points = 0 - $impressions;
            } else {
                $points = 0 - ($impressions / $this->rate);
            } //make it negative

            Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, $points, $pages[1], "boost");
            //a boost gift
            if (isset($pages[2]) && $pages[2] != Core\Session::getLoggedinUser()->guid) {
                Core\Events\Dispatcher::trigger('notification', 'boost', array(
                'to'=>array($pages[2]),
                'entity' => $pages[1],
                'notification_view' => 'boost_gift',
                'params' => array('impressions'=>$impressions),
                'impressions' => $impressions
                ));
            } elseif ($pages[0] != 'channel') {
                Core\Events\Dispatcher::trigger('notification', 'boost', array(
                'to'=>array(Core\Session::getLoggedinUser()->guid),
                'entity' => $pages[1],
                'notification_view' => 'boost_submitted',
                'params' => array('impressions'=>$impressions),
                'impressions' => $impressions
                ));
            }
        } else {
            $response['status'] = 'error';
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

        Helpers\Wallet::createTransaction($boost->getDestination()->guid, $boost->getBid(), $boost->getGuid(), "Peer Boost");

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
