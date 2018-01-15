<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */

namespace Minds\Controllers\api\v1;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers;
use Minds\Helpers\Counters;
use Minds\Interfaces;

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
        $limit = isset($_GET['limit']) && $_GET['limit'] ? (int)$_GET['limit'] : 12;
        $offset = isset($_GET['offset']) && $_GET['offset'] ? $_GET['offset'] : '';

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
//                $pro = Core\Boost\Factory::build('peer', ['destination' => Core\Session::getLoggedInUser()->guid]);
                /** @var Core\Boost\Peer\Review $review */
                $review = Di::_()->get('Boost\Peer\Review');
                $boost = $review->getBoostEntity($pages[0]);
                if ($boost->getState() != 'created') {
                    return Factory::response(['status' => 'error', 'message' => 'entity not in boost queue']);
                }
                $response['entity'] = $boost->getEntity()->export();
                $response['points'] = $boost->getBid();
                break;
            case "rates":
                $response['balance'] = (int)Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false);
                $response['rewardsBalance'] = Di::_()->get('Rewards\Balance')->setUser(Core\Session::getLoggedInUser())->get();
                $response['hasPaymentMethod'] = false;
                $response['rate'] = $this->rate;

                $config = array_merge([
                    'network' => [
                        'min' => 200,
                        'max' => 5000,
                    ],
                ], (array)Core\Di\Di::_()->get('Config')->get('boost'));

                $response['cap'] = $config['network']['max'];
                $response['min'] = $config['network']['min'];
                $response['priority'] = $this->getQueuePriorityRate();
                $response['usd'] = $this->getUSDRate();
                $response['minUsd'] = $this->getMinUSDCharge();
                $response['tokens'] = $this->getTokensRate();
                break;
            case "p2p":
                /** @var Core\Boost\Peer\Review $review */
                $review = Di::_()->get('Boost\Peer\Review');
                $review->setType(Core\Session::getLoggedInUser()->guid);
                $boosts = $review->getReviewQueue($limit, $offset);
                $boost_entities = [];
                //only show 'point boosts and 'created' (not accepted or rejected)
                foreach ($boosts['data'] as $i => $boost) {
                    if ($boost->getType() != 'points' || $boost->getState() != 'created') {
                        unset($boosts[$i]);
                        continue;
                    }
                    $boost_entities[$i] = $boost->getEntity();
                    $boost_entities[$i]->guid = $boost->getGuid();
                    $boost_entities[$i]->points = $boost->getBid();
                }

                $response['boosts'] = factory::exportable($boost_entities, array('points'));
                $response['load-next'] = $boosts['next'];
                break;
            case "newsfeed":
            case "content":
                /** @var Core\Boost\Network\Review $review */
                $review = Di::_()->get('Boost\Network\Review');
                $review->setType($pages[0]);
                $boosts = $review->getOutbox(Core\Session::getLoggedinUser()->guid, $limit, $offset);
                $response['boosts'] = Factory::exportable($boosts['data']);
                $response['load-next'] = $boosts['next'];
                break;
        }

        if (isset($response['boosts']) && $response['boosts']) {
            if ($response['boosts'] && !isset($response['load-next'])) {
                $response['load-next'] = end($response['boosts'])['guid'];
            }
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
        ], (array)Core\Di\Di::_()->get('Config')->get('boost'));

        if ($impressions < $config['network']['min'] || $impressions > $config['network']['max']) {
            return Factory::response([
                'status' => 'error',
                'message' => "You must boost between {$config['network']['min']} and {$config['network']['max']} impressions"
            ]);
        }

        $response = [];
        $entity = Entities\Factory::build($pages[1]);
        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'entity not found']);
        }

        if ($pages[0] == "object" || $pages[0] == "user" || $pages[0] == "suggested" || $pages[0] == 'group') {
            $pages[0] = "content";
        }

        if ($pages[0] == "activity") {
            $pages[0] = "newsfeed";
        }

        try {
            switch (ucfirst($pages[0])) {
                case "Newsfeed":
                case "Content":
                    $priority = false;
                    $priorityRate = 0;
                    if (isset($_POST['priority']) && $_POST['priority']) {
                        $priority = true;
                        $priorityRate = $this->getQueuePriorityRate();
                    }
                    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
                    $bidType = isset($_POST['bidType']) ? $_POST['bidType'] : 'points';
                    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

                    $amount = $impressions / $this->rate;
                    if ($priority) {
                        $amount *= $priorityRate + 1;
                    }

                    switch ($bidType) {
                        case 'usd':
                            $amount = round($amount / $this->getUSDRate(), 2) * 100;

                            if (($amount / 100) < $this->getMinUSDCharge()) {
                                return Factory::response([
                                    'status' => 'error',
                                    'message' => 'You must spend at least $' . $this->getMinUSDCharge()
                                ]);
                            }
                            break;

                        case 'tokens':
                        case 'rewards':
                            $amount = round($amount / $this->getTokensRate(), 4);
                            break;

                        default:
                            $amount = ceil($amount);
                            break;
                    }

                    $validCategories = array_keys(Di::_()->get('Config')->get('categories') ?: []);
                    if (!is_array($categories)) {
                        $categories = [$categories];
                    }

                    foreach ($categories as $category) {
                        if (!in_array($category, $validCategories)) {
                            return Factory::response([
                                'status' => 'error',
                                'message' => 'Invalid category ID: ' . $category
                            ]);
                        }
                    }

                    $state = 'created';

                    if ($bidType == 'tokens') {
                        $state = 'pending';
                    }

                    $boost = (new Entities\Boost\Network())
                        ->setEntity($entity)
                        ->setBid($amount)
                        ->setBidType($bidType)
                        ->setImpressions($impressions)
                        ->setOwner(Core\Session::getLoggedInUser())
                        ->setState($state)
                        ->setHandler(lcfirst($pages[0]))
                        ->setPriorityRate($priorityRate)
                        ->setCategories($categories);

                    if ($bidType == 'tokens' && isset($_POST['guid'])) {
                        $guid = $_POST['guid'];

                        if (!is_numeric($guid) || $guid < 1) {
                            return Factory::response([
                                'status' => 'error',
                                'stage' => 'transaction',
                                'message' => 'Provided GUID is invalid'
                            ]);
                        }

                        /** @var Core\Boost\Repository $repository */
                        $repository = Di::_()->get('Boost\Repository');

                        $existingBoost = $repository->getEntity($boost->getHandler(), $guid);

                        if ($existingBoost) {
                            return Factory::response([
                                'status' => 'error',
                                'stage' => 'transaction',
                                'message' => 'Provided GUID already exists'
                            ]);
                        }

                        $boost->setGuid($guid);
                    }

                    $result = Core\Boost\Factory::build(ucfirst($pages[0]))->boost($boost, $impressions);
                    if ($result) {
                        if (isset($_POST['newUserPromo']) && $_POST['newUserPromo'] && $impressions == 200 && !$priority) {
                            $transactionId = "free";
                        } else {
                            $transactionId = Di::_()->get('Boost\Payment')->pay($boost, $paymentMethod);
                        }
                        $boost->setId((string)$result)
                            ->setTransactionId($transactionId)
                            ->save();
                    } else {
                        $response['status'] = 'error';
                    }
                    break;
                case "Channel": //this is a polyfill for the new boost PRO
                    $result = Core\Boost\Factory::build("Channel", [
                        'destination' => isset($_POST['destination']) ? $_POST['destination'] : null
                    ])->boost($entity, $impressions);
                    Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, -$impressions, $pages[1], "P2P Boost");
                    if ($result) {
                        Core\Events\Dispatcher::trigger('notification', 'boost', [
                            'to' => [$pages[2]],
                            'entity' => $pages[1],
                            'notification_view' => 'boost_gift',
                            'params' => ['impressions' => $impressions],
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
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response($response);
    }

    /**
     * Called when a boost is to be accepted (assume channels only right now)
     * @param array $pages
     */
    public function put($pages)
    {
        Factory::isLoggedIn();

        $response = [];
        /** @var Core\Boost\Peer\Review $review */
        $review = Di::_()->get('Boost\Peer\Review');
        $boost = $review->getBoostEntity($pages[0]);
        $review->setBoost($boost);

        //double check status before issuing points
        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and can not be approved'
            ]);
        }

        //now add to the newsfeed
        $embeded = Entities\Factory::build($boost->getEntity()->guid); //more accurate, as entity doesn't do this @todo maybe it should in the future
        Counters::increment($boost->getEntity()->guid, 'remind');

        $activity = new Entities\Activity();
        $activity->p2p_boosted = true;
        if ($embeded->remind_object) {
            $activity->setRemind($embeded->remind_object)->save();
        } else {
            $activity->setRemind($embeded->export())->save();
        }

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to' => array($boost->getOwner()->guid),
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_peer_accepted',
            'params' => ['bid' => $boost->getBid(), 'type' => $boost->getType()]
        ]);

        $review->accept();

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
     * Called when a network boost is revoked
     * @param array $pages
     */
    public function delete($pages)
    {
        Factory::isLoggedIn();

        $response = [];

        $type = $pages[0];
        $guid = $pages[1];
        $action = $pages[2];

        if (!$guid) {
            return Factory::response([
                'status' => 'error',
                'message' => 'We couldn\'t find that boost'
            ]);
        }

        if (!$action) {
            return Factory::response([
                'status' => 'error',
                'message' => "You must provide an action: revoke"
            ]);
        }

        /** @var Core\Boost\Network\Review|Core\Boost\Peer\Review $review */
        $review = $type == 'peer' ? Di::_()->get('Boost\Peer\Review') : Di::_()->get('Boost\Network\Review');
        $review->setType($type);
        $boost = $review->getBoostEntity($guid);
        if (!$boost) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Boost not found'
            ]);
        }

        if ($boost->getOwner()->guid != Core\Session::getLoggedInUserGuid()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You cannot revoke that boost'
            ]);
        }

        if ($action == 'revoke') {
            $review->setBoost($boost);
            try {
                $success = $review->revoke();

                if ($success) {
                    Di::_()->get('Boost\Payment')->refund($boost);
                } else {
                    $response['status'] = 'error';
                }
            } catch (\Exception $e) {
                $response['status'] = 'error';
            }
        }

        return Factory::response($response);
    }

    protected function getQueuePriorityRate()
    {
        // @todo: Calculate based on boost queue
        return 10;
    }

    protected function getUSDRate()
    {
        $config = (array)Core\Di\Di::_()->get('Config')->get('boost');

        return isset($config['usd']) ? $config['usd'] : 1000;
    }

    protected function getTokensRate()
    {
        return Core\Di\Di::_()->get('Blockchain\Manager')->getRate();
    }

    protected function getMinUSDCharge()
    {
        return 1.00;
    }
}
