<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 2
 * @author Mark Harding
 *
 */

namespace Minds\Controllers\api\v2;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities;
use Minds\Helpers;
use Minds\Helpers\Counters;
use Minds\Interfaces;

class boost implements Interfaces\Api
{
    private $rate = 1;

    /**
     * Return impressions for a request
     * @param array $pages
     *
     * @SWG\GET(
     *     tags={"boost"},
     *     summary="Returns information regarding a boost, or the current boost rates",
     *     path="/v2/boost/{guid}",
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
     *     path="/v2/boost/rate",
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
        Factory::isLoggedIn();

        $response = [];
        $limit = isset($_GET['limit']) && $_GET['limit'] ? (int)$_GET['limit'] : 12;
        $offset = isset($_GET['offset']) && $_GET['offset'] ? $_GET['offset'] : '';

        switch ($pages[0]) {
            case is_numeric($pages[0]):
                $review = Di::_()->get('Boost\Peer\Review');
                $boost = $review->getBoostEntity($pages[0]);
                if ($boost->getState() != 'created') {
                    return Factory::response(['status' => 'error', 'message' => 'entity not in boost queue']);
                }
                $response['entity'] = $boost->getEntity()->export();
                $response['bid'] = $boost->getBid();
                break;
            case "rates":
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
                foreach ($boosts['data'] as $i => $boost) {
                    if ($boost->getState() != 'created') {
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
     * API:: /v2/boost/:type/:guid
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

        $impressions = (int) $_POST['impressions'];

        if (!isset($impressions)) {
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be sent in post body'));
        }

        if ($impressions <= 0) {
            return Factory::response(array('status' => 'error', 'message' => 'impressions must be a positive whole number'));
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

                    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : [];
                    $bidType = isset($_POST['bidType']) ? $_POST['bidType'] : null;
                    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];
                    $checksum =  isset($_POST['checksum']) ? $_POST['checksum'] : '';

                    $amount = $impressions / $this->rate;
                    if ($priority) {
                        $amount *= $priorityRate + 1;
                    }

                    if (!in_array($bidType, [ 'usd', 'tokens' ])) {
                        return Factory::response([
                            'status' => 'error',
                            'stage' => 'initial',
                            'message' => 'Unknown currency'
                        ]);
                    }

                    // Amount normalizing

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
                            $amount = BigNumber::toPlain(round($amount / $this->getTokensRate(), 4), 18);
                            break;
                    }

                    // Categories

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

                    // Generate Boost entity

                    $state = 'created';

                    if ($bidType == 'tokens' && $paymentMethod['method'] === 'onchain') {
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

                    // Pre-set GUID

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

                        $calculatedChecksum = (new Core\Boost\Checksum())
                            ->setGuid($guid)
                            ->setEntity($entity)
                            ->generate();

                        if ($checksum !== $calculatedChecksum) {
                            return Factory::response([
                                'status' => 'error',
                                'stage' => 'transaction',
                                'message' => 'Checksum does not match. Expected: ' . $calculatedChecksum
                            ]);
                        }
                        $boost->setChecksum($checksum);
                    }

                    // Payment

                    if (isset($_POST['newUserPromo']) && $_POST['newUserPromo'] && $impressions == 200 && !$priority) {
                        $transactionId = "free";
                    } else {
                        $transactionId = Di::_()->get('Boost\Payment')->pay($boost, $paymentMethod);
                    }

                    // Run boost

                    $boostId = Core\Boost\Factory::build(ucfirst($pages[0]))->boost($boost, $impressions);

                    if ($boostId) {
                        $boost
                            ->setId((string) $boostId)
                            ->setTransactionId($transactionId)
                            ->save();
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
     * @param array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
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

        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and cannot be refunded'
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
                // $response['message'] = $e->getMessage();
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
