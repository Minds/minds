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

        switch ($pages[0]) {
          case 'outbox':
            $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
            $boosts = $pro->getOutbox($limit, $offset);
            
            if ($boosts && $offset) {
                array_shift($boosts);
            }

            $response['boosts'] = Factory::exportable($boosts);

            if ($boosts) {
                $response['load-next'] = (string) end($boosts)->getGuid();
            }
            break;
          case 'inbox':
          default:
            $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
            $boosts = $pro->getReviewQueue(isset($_GET['limit']) ? $_GET['limit'] : 12, isset($_GET['offset']) ? $_GET['offset'] : "");

            if ($boosts && $offset) {
                array_shift($boosts);
            }

            $response['boosts'] = Factory::exportable($boosts);

            if ($boosts) {
                $response['load-next'] = (string) end($boosts)->getGuid();
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
        $bid = $_POST['bid'];
        $type = $_POST['type'];

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

        if ($type == "pro" && !$destination->merchant) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => "@$destination->username is not a merchant and can not accept Pro Boosts"
            ]);
        }

        if (Core\Security\ACL\Block::_()->isBlocked(Core\Session::getLoggedinUser(), $destination)) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => "You are not allowed to boost to @{$destination->username}'s channel"
            ]);
        }

        $bid = intval($_POST['bid']);
        $config = array_merge([
            'peer' => [
                'min' => 100,
                'max' => 5000000,
            ]
        ], (array) Core\Di\Di::_()->get('Config')->get('boost'));

        if ($bid < $config['peer']['min'] || $bid > $config['peer']['max']) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => "You must boost between {$config['peer']['min']} and {$config['peer']['max']} points"
            ]);
        }

        $boost = (new Entities\Boost\Peer())
          ->setEntity($entity)
          ->setType($_POST['type'])
          ->setBid($bid)
          ->setDestination($destination)
          ->setOwner(Core\Session::getLoggedInUser())
          ->postToFacebook($_POST['postToFacebook'])
          ->setScheduledTs($_POST['scheduledTs'])
          ->setState('created');
          //->save();

        if ($type == 'pro') {
            $sale = (new Payments\Sale)
            ->setOrderId('boost-' . $boost->getGuid())
            ->setAmount($boost->getBid())
            ->setMerchant($boost->getDestination())
            ->setCustomerId($boost->getOwner()->guid)
            ->setNonce($_POST['nonce']);

            try {
                $transaction_id = Payments\Factory::build('braintree', ['gateway'=>'merchants'])->setSale($sale);
            } catch (\Exception $e) {
                return Factory::response([
                    'status' => 'error',
                    'stage' => 'transaction',
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            if ((int) Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false) < $boost->getBid()) {
                return Factory::response([
                    'status' => 'error',
                    'stage' => 'transaction',
                    'message' => "You don't have enough points"
                ]);
            }
            $transactions_id = Helpers\Wallet::createTransaction($boost->getOwner()->guid, -$boost->getBid(), $boost->getGuid(), "Boost");
        }

        $boost->setTransactionId($transaction_id)
          ->save();

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to'=> [$boost->getDestination()->guid],
            'entity' => $boost->getEntity(),
            'title' => $boost->getEntity()->title,
            'notification_view' => 'boost_peer_request',
            'params' => ['bid'=>$boost->getBid(), 'type'=>$boost->getType()]
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
        $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
        $boost = $pro->getBoostEntity($pages[0]);

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

        if ($boost->getType() == "pro") {
            try {
                Payments\Factory::build('braintree', ['gateway'=>'merchants'])->chargeSale((new Payments\Sale)->setId($boost->getTransactionId()));
            } catch (\Exception $e) {
                return Factory::response([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
                return false;
            }
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

        $pro->accept($pages[0]);
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
    
        $pro = Core\Boost\Factory::build('peer', ['destination'=> $revoked ? "requested:" . Core\Session::getLoggedInUser()->guid : Core\Session::getLoggedInUser()->guid]);
        $boost = $pro->getBoostEntity($pages[0]);

        if ($boost->getState() != 'created') {
            return Factory::response([
                'status' => 'error',
                'message' => 'This boost is in the ' . $boost->getState() . ' state and can not be approved'
            ]);
        }

        if ($boost->getType() == "pro") {
            try {
                Payments\Factory::build('braintree', ['gateway'=>'merchants'])->voidSale((new Payments\Sale)->setId($boost->getTransactionId()));
            } catch (\Exception $e) {
                return Factory::response([
                  'status' => 'error',
                  'message' => $e->getMessage()
                ]);
                return false;
            }
        } else {
            $message = "Rejected Peer Boost";
            if ($revoked) {
                $message = "Revoked Peer Boost";
            }
            Helpers\Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), $message);
        }

        if ($revoked) {
            $pro->revoke($pages[0]);
        } else {
            Core\Events\Dispatcher::trigger('notification', 'boost', [
                'to'=>array($boost->getOwner()->guid),
                'entity' => $boost->getEntity(),
                'title' => $boost->getEntity()->title,
                'notification_view' => 'boost_peer_rejected',
                'params' => ['bid'=>$boost->getBid(), 'type'=>$boost->getType()]
            ]);
            $pro->reject($pages[0]);
        }

        $response['status'] = 'success';
        return Factory::response($response);
    }
}
