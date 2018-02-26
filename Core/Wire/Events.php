<?php
/**
 * Created by Marcelo.
 * Date: 03/08/2017
 */

namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Util\BigNumber;
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
use Minds\Entities;
use Minds\Entities\User;

class Events
{
    public function register()
    {
        Dispatcher::register('subscriptions:cancel', 'all', function ($event) {
            $params = $event->getParameters();
            $user = $params['user'];
            if ($user->type != 'user') {
                return;
            }

            /** @var Manager $manager */
            $manager = Core\Di\Di::_()->get('Wire\Manager');

            $wires = $manager->get(['user_guid' => $user->guid, 'type' => 'sent', 'order' => 'DESC']);

            // cancel all wires and subscriptions
            foreach ($wires as $wire) {
                if ($wire->isRecurring() && $wire->isActive()) {
                    if ($wire->getMethod() == 'usd') {
                        // cancel all subscriptions from stripe
                        $this->cancelSubscriptions($user);
                    }
                    $wire->setActive(0)
                        ->save();
                }
            }

        });

        /**
         * Legcacy compatability for exclusive content
         */
        Dispatcher::register('export:extender', 'activity', function($event) {
            $params = $event->getParameters();
            $activity = $params['entity'];
            if($activity->type != 'activity'){
                return;
            }
            $export = $event->response() ?: [];
            $currentUser = Session::getLoggedInUserGuid();

            if ($activity->isPaywall() && !$activity->getWireThreshold()) {
                $export['wire_threshold'] = [
                  'type' => 'money',
                  'min' => $activity->getOwnerEntity()->getMerchant()['exclusive']['amount']
                ];
                return $event->setResponse($export);
            }
        });

        // Recurring subscriptions

        Dispatcher::register('subscriptions:process', 'wire', function (Core\Events\Event $event) {
            $params = $event->getParameters();
            /** @var Core\Payments\Subscriptions\Subscription $subscription */
            $subscription = $params['subscription'];

            $user = Entities\Factory::build($subscription->getEntity()->guid);
            $sender = new User($subscription->getUser()->guid);

            $onRecurring = $this->onRecurring($sender, $user,$subscription->getAmount(), $subscription->getId());

            return $event->setResponse($onRecurring);
        });

        Dispatcher::register('subscriptions:process', 'wire', function (Core\Events\Event $event) {
            $params = $event->getParameters();
            /** @var Core\Payments\Subscriptions\Subscription $subscription */
            $subscription = $params['subscription'];

            $user = Entities\Factory::build($subscription->getEntity()->guid);
            $sender = new User($subscription->getUser()->guid);

            $onRecurring = $this->onRecurring($sender, $user,$subscription->getAmount(), $subscription->getId());

            return $event->setResponse($onRecurring);
        });

        Dispatcher::register('wire:email', 'wire', function (Core\Events\Event $event) {
            $params = $event->getParameters();
            /** @var User $receiver */
            $receiver = $params['receiver'];

            $campaign = new Core\Email\Campaigns\WhenWire();

            $campaign->setUser($receiver);

            $campaign->send();

            return $event->setResponse(true);
        });
    }

    /**
     * @param User $actor
     * @param User $owner
     * @param $amount
     * @param string $subscription_id
     * @return mixed
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    private function onRecurring($actor, $owner, $amount, $subscription_id) {
        if (!$actor->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        if (!$actor->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        /** @var Core\Config $config */
        $config = Core\Di\Di::_()->get('Config');

        /** @var Core\Blockchain\Services\Ethereum $client */
        $client = Di::_()->get('Blockchain\Services\Ethereum');

        /** @var Core\Blockchain\Token $token */
        $token = Di::_()->get('Blockchain\Token');

        $txHash = $client->sendRawTransaction($config->get('blockchain')['wallet_pkey'], [
            'from' => $config->get('blockchain')['wallet_address'],
            'to' => $config->get('blockchain')['wire_address'],
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => $client->encodeContractMethod('wireFrom(address,address,uint256)', [
                $actor->getEthWallet(),
                $owner->getEthWallet(),
                BigNumber::_($token->toTokenUnit($amount))->toHex(true)
            ])
        ]);

        if (!$txHash) {
            throw new \Exception('Transaction hash is null');
        }

        /** @var Manager $manager */
        $manager = Di::_()->get('Wire\Manager');
        $manager
            ->setPayload(['nonce' => ['txHash' => $txHash]])
            ->setAmount($amount)
            ->setActor($actor)
            ->setEntity($owner)
            ->setTimestamp(time());
        return $manager->create([
            'subscription_id' => $subscription_id
        ]);
    }

    private function cancelSubscriptions(User $user)
    {
        /** @var Core\Payments\Subscriptions\Repository $repository */
        $repository = Di::_()->get('Payments\Subscriptions\Repository');
        /** @var Core\Payments\Subscriptions\Manager $manager */
        $manager = Di::_()->get('Payments\Subscriptions\Manager');

        $subscriptions = $repository->getList([
            'user_guid' => $user->guid,
            'plan_id' => 'wire'
        ]);

        foreach ($subscriptions as $subscription) {
            if (!$subscription->getId()) {
                continue;
            }

            $subscription->setMerchant($user);

            $stripe = Core\Di\Di::_()->get('StripePayments');
            $stripe->cancelSubscription($subscription);

            //cancel the plan itself
            $manager->setSubscription($subscription);
            $manager->cancel();
        }
    }
}
