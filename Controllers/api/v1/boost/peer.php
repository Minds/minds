<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\boost;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class peer implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    private $rate = 1;

    /**
     * Return a list of boosts that a user needs to review
     * @param array $pages
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        $response = [];
        $limit = isset($_GET['limit']) && $_GET['limit'] ? (int) $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) && $_GET['offset'] ? $_GET['offset'] : '';
        /** @var Core\Boost\Peer\Review $review */
        $review = Core\Di\Di::_()->get('Boost\Peer\Review');

        switch ($pages[0]) {
            case 'outbox':
                $boosts = $review->getOutbox(Core\Session::getLoggedinUser()->guid, $limit, $offset);

                $response['boosts'] = Factory::exportable($boosts['data']);

                if ($boosts) {
                    $response['load-next'] = $boosts['next'];
                }
                break;
            case 'inbox':
            default:
                $review->setType(Core\Session::getLoggedinUser()->guid);
                $boosts = $review->getReviewQueue(isset($_GET['limit']) ? $_GET['limit'] : 12,
                    isset($_GET['offset']) ? $_GET['offset'] : "");

                $response['boosts'] = Factory::exportable($boosts['data']);

                if ($boosts) {
                    $response['load-next'] = (string) $boosts['next'];
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

        $entity = Entities\Factory::build($pages[0]);
        $destination = Entities\Factory::build($_POST['destination']);
        $bid = intval($_POST['bid']);
        $type = $_POST['type'];
        $currency = $_POST['currency'];

        if (!$entity) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => 'We couldn\'t find the entity you wanted boost. Please try again.'
            ]);
        }

        if (!$destination) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => 'We couldn\'t find the user you wish to boost to. Please try another user.'
            ]);
        }

        if (!in_array($currency, [ 'money', 'tokens' ])) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => 'Unknown currency'
            ]);
        }

        if ($type == "pro") {
            if ($currency == 'tokens' && !$destination->getEthWallet()) {
                return Factory::response([
                    'status' => 'error',
                    'stage' => 'initial',
                    'message' => "@$destination->username is not a wallet holder and can not accept MindsCoin Pro Boosts"
                ]);
            } elseif ($currency == 'money' && !$destination->merchant) {
                return Factory::response([
                    'status' => 'error',
                    'stage' => 'initial',
                    'message' => "@$destination->username is not a merchant and can not accept Pro Boosts"
                ]);
            }
        }

        if (Core\Security\ACL\Block::_()->isBlocked(Core\Session::getLoggedinUser(), $destination)) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => "You are not allowed to boost to @{$destination->username}'s channel"
            ]);
        }

        $state = 'created';

        if ($currency == 'tokens') {
            $state = 'pending';
        }

        $boost = (new Entities\Boost\Peer())
          ->setEntity($entity)
          ->setType($_POST['type'])
          ->setMethod($currency)
          ->setBid($bid)
          ->setDestination($destination)
          ->setOwner(Core\Session::getLoggedInUser())
          ->postToFacebook($_POST['postToFacebook'])
          ->setScheduledTs($_POST['scheduledTs'])
          ->setState($state);
          //->save();

        try {
            if ($type == 'pro') {
                switch ($currency) {
                    case 'money':
                        $sale = (new Payments\Sale)
                            ->setOrderId('boost-' . $boost->getGuid())
                            ->setAmount($boost->getBid() * 100)//cents to $
                            ->setMerchant($boost->getDestination())
                            ->setCustomerId($boost->getOwner()->guid)
                            ->setSource($_POST['nonce']);

                        try {
                            $stripe = Core\Di\Di::_()->get('StripePayments');
                            $transaction_id = $stripe->setSale($sale);
                        } catch (\Exception $e) {
                            return Factory::response([
                                'status' => 'error',
                                'stage' => 'transaction',
                                'message' => $e->getMessage()
                            ]);
                        }
                        break;

                    case 'tokens':
                        if (!isset($_POST['nonce']['txHash']) || !$_POST['nonce']['txHash']) {
                            return Factory::response([
                                'status' => 'error',
                                'stage' => 'transaction',
                                'message' => 'Missing blockchain transaction'
                            ]);
                        }

                        if (isset($_POST['guid'])) {
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

                            $existingBoost = $repository->getEntity('peer', $guid);

                            if ($existingBoost) {
                                return Factory::response([
                                    'status' => 'error',
                                    'stage' => 'transaction',
                                    'message' => 'Provided GUID already exists'
                                ]);
                            }

                            $boost->setGuid($guid);
                        }

                        $transaction_id = $_POST['nonce']['txHash'];

                        Di::_()->get('Boost\Pending')
                            ->add($transaction_id, $boost);
                        break;
                }
            } else {
                throw new \Exception('Points boost are no longer supported');
            }

            $boost->setTransactionId($transaction_id)
                ->save();

        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to'=> [$boost->getDestination()->guid],
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_peer_request',
            'params' => ['bid'=>$boost->getBid(), 'type'=>$boost->getType(), 'currency' => $boost->getMethod()]
        ]);

        $response['boost_guid'] = $boost->getGuid();

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        Factory::isLoggedIn();

        $response = [];
        /** @var Core\Boost\Peer\Review $review */
        $review = Core\Di\Di::_()->get('Boost\Peer\Review');
        $boost = $review->getBoostEntity($pages[0]);

        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and can not be approved'
            ]);
        }

        //now add to the newsfeed
        $embeded = Entities\Factory::build($boost->getEntity()->guid); //more accurate, as entity doesn't do this @todo maybe it should in the future
        if (!$embeded) {
            return Factory::response([
                'status' => 'error',
                'message' => 'The original post was deleted'
            ]);
        }
        \Minds\Helpers\Counters::increment($boost->getEntity()->guid, 'remind');

        $activity = new Entities\Activity();
        $activity->p2p_boosted = true;
        if ($embeded->remind_object) {
            $activity->setRemind($embeded->remind_object)->save();
        } else {
            $activity->setRemind($embeded->export())->save();
        }

        if ($boost->getType() == "pro" && $boost->getMethod() == 'money') {
            try {
                $stripe = Core\Di\Di::_()->get('StripePayments');
                $sale = (new Payments\Sale)
                    ->setId($boost->getTransactionId())
                    ->setMerchant($boost->getDestination());
                $stripe->chargeSale($sale);
            } catch (\Exception $e) {
                return Factory::response([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
                return false;
            }
        } else if ($boost->getType() == 'pro' && $boost->getMethod() == 'tokens') {
            // Already charged
        } else {
            Helpers\Wallet::createTransaction($boost->getDestination()->guid, $boost->getBid(), $boost->getGuid(), "Peer Boost");
        }

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to'=>array($boost->getOwner()->guid),
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_peer_accepted',
            'params' => ['bid'=>$boost->getBid(), 'type'=>$boost->getType()]
        ]);

        //Now forward through to social networks if selected
        if ($boost->shouldPostToFacebook()) {
            $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');
            $facebook->getApiCredentials();
            if ($boost->getScheduledTs() > time()) {
                $facebook->schedule($boost->getScheduledTs());
            }
            $facebook->post($embeded);
        }

        $review->setBoost($boost);
        try {
            $review->accept();
        } catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }
        $response['status'] = 'success';
        return Factory::response($response);
    }

    /**
     */
    public function delete($pages)
    {
        Factory::isLoggedIn();

        $response = [];

        $revoked = isset($pages[1]) && $pages[1] == 'revoke';

        /** @var Core\Boost\Peer\Review $review */
        $review = Core\Di\Di::_()->get('Boost\Peer\Review');
        $boost = $review->getBoostEntity($pages[0]);
        $review->setBoost($boost);

        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and can not be approved'
            ]);
        }

        try {
            if ($boost->getType() == 'pro') {
                switch ($boost->getMethod()) {
                    case 'money':
                        $stripe = Core\Di\Di::_()->get('StripePayments');
                        $sale = (new Payments\Sale)
                            ->setId($boost->getTransactionId())
                            ->setMerchant($boost->getDestination());

                        $stripe->voidSale($sale);
                        break;

                    case 'tokens':
                        // Already refunded
                        break;
                }
            } else {
                throw new \Exception('Point boosts are no longer supported');
            }
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        try {
            if ($revoked) {
                $review->revoke();
            } else {
                Core\Events\Dispatcher::trigger('notification', 'boost', [
                    'to' => array($boost->getOwner()->guid),
                    'entity' => $boost->getEntity(),
                    'title' => $boost->getEntity()->title,
                    'notification_view' => 'boost_peer_rejected',
                    'params' => ['bid' => $boost->getBid(), 'type' => $boost->getType()]
                ]);
                $review->reject();
            }
        } catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }

        $response['status'] = 'success';
        return Factory::response($response);
    }
}
