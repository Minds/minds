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

        switch ($pages[0]) {
          case 'outbox':
            $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
            $boosts = $pro->getOutbox(100);
            $response['boosts'] = Factory::exportable($boosts);
            break;
          case 'inbox':
          default:
            $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
            $boosts = $pro->getReviewQueue(100);
            $response['boosts'] = Factory::exportable($boosts);
            $response['load-next'] = (string) end($boosts)->getGuid();
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

        $boost = (new Entities\Boost\Peer())
          ->setEntity($entity)
          ->setType($_POST['type'])
          ->setBid($_POST['bid'])
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
                $transaction_id = Payments\Factory::build('braintree')->setSale($sale);
            } catch (\Exception $e) {
                return Factory::response([
                    'status' => 'error',
                    'stage' => 'transaction',
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            if((int) Helpers\Counters::get(Core\Session::getLoggedinUser()->guid, 'points', false) < $boost->getBid()){
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

        if ($boost->getType() == "pro") {
            try {
                Payments\Factory::build('braintree')->chargeSale((new Payments\Sale)->setId($boost->getTransactionId()));
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

        //Now forward through to social networks if selected
        if($pro->shouldPostToFacebook()){
            $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');
            $facebook->getApiCredentials();
            if($pro->getScheduledTs() > time()){
                $facebook->schedule($pro->getScheduledTs());
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
        $pro = Core\Boost\Factory::build('peer', ['destination'=>Core\Session::getLoggedInUser()->guid]);
        $boost = $pro->getBoostEntity($pages[0]);

        if ($boost->getType() == "pro") {
            try {
                Payments\Factory::build('braintree')->voidSale((new Payments\Sale)->setId($boost->getTransactionId()));
            } catch (\Exception $e) {
                return Factory::response([
            'status' => 'error',
            'message' => $e->getMessage()
          ]);
                return false;
            }
        } else {
            Helpers\Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Rejected Peer Boost");
        }

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
    }
}
