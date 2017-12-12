<?php

namespace Minds\Core\Wire\Methods;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Wire\Counter;
use Minds\Entities;
use Minds\Entities\User;

class Tokens implements MethodInterface
{

    private $amount;
    private $entity;
    private $nonce;
    private $recurring; // monthly
    private $timestamp;
    private $manager;
    private $repository;
    private $cache;
    private $config;

    /** @var Core\Blockchain\Pending $pendingManager */
    private $pendingManager;

    public function __construct($stripe = null, $manager = null, $repository = null, $cache = null, $config = null, $pendingManager = null)
    {
        $this->manager = $manager ?: Core\Di\Di::_()->get('Wire\Manager');
        $this->repository = $repository ?: Core\Di\Di::_()->get('Wire\Repository');
        $this->cache = $cache ?: Core\Di\Di::_()->get('Cache');
        $this->config = $config ?: Core\Di\Di::_()->get('Config');
        $this->pendingManager = $pendingManager ?: Di::_()->get('Blockchain\Pending');
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function setPayload($payload = [])
    {
        if (!isset($payload['nonce'])) {
            $this->nonce = null;
        }

        $this->nonce = $payload['nonce'];
        return $this;
    }

    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
        return $this;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function create()
    {
        $user = $this->entity->type == 'user' ?
            $this->entity :
            Entities\Factory::build($this->entity->owner_guid);

        if ($this->recurring) {
            return $this->createSubscription($user);
        }

        return $this->createPendingWire($user);
    }

    /**
     * @return mixed
     */
    public function refund() {

    }

    private function createSubscription(User $user)
    {
        if (!$user->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        $firstWire = $this->createPendingWire($user);

        if (!$firstWire) {
            throw new \Exception('Error creating Wire');
        }

        /** @var Payments\RecurringSubscriptions\Manager $recurringSubscriptionsManager */
        $recurringSubscriptionsManager = Di::_()->get('Payments\RecurringSubscriptions\Manager');

        $recurringSubscriptionsManager
            ->setType('wire')
            ->setPaymentMethod('tokens')
            ->setEntityGuid($user->guid)
            ->setUserGuid(Core\Session::getLoggedInUserGuid());

        $recurringSubscriptionsManager->cancel();

        $now = time();

        $subscription_id = $recurringSubscriptionsManager->create([
            'recurring' => 'monthly',
            'amount' => $this->amount,
            'last_billing' => $now
        ]);

        if ($subscription_id) {
            $recurringSubscriptionsManager->setSubscriptionId($subscription_id);

            $recurringSubscriptionsManager->createPayment([
                'payment_id' => 'blockchain:' . $this->nonce['txHash'],
                'time_created' => $now,
                'amount' => $this->amount,
                'description' => 'Wire',
                'status' => 'pending'
            ]);
        }

        return $subscription_id;
    }

    public function chargeRecurringAndCreate(User $user, User $sender)
    {
        if (!$sender->getEthWallet()) {
            throw new WalletNotSetupException('Sender does not have a wallet');
        }

        if (!$user->getEthWallet()) {
            throw new WalletNotSetupException('User does not have a wallet');
        }

        /** @var Core\Blockchain\Services\Ethereum $client */
        $client = Di::_()->get('Blockchain\Services\Ethereum');
        
        /** @var Core\Blockchain\Token $token */
        $token = Di::_()->get('Blockchain\Token');

        $txHash = $client->sendRawTransaction($this->config->get('blockchain')['wallet_pkey'], [
            'from' => $this->config->get('blockchain')['wallet_address'],
            'to' => $this->config->get('blockchain')['wire_address'],
            'gasLimit' => Core\Blockchain\Util::toHex(200000),
            'data' => $client->encodeContractMethod('wireFrom(address,address,uint256)', [
                $sender->getEthWallet(),
                $user->getEthWallet(),
                Core\Blockchain\Util::toHex($token->toTokenUnit($this->amount))
            ])
        ]);

        if (!$txHash) {
            throw new \Exception('Transaction hash is null');
        }

        $this->setPayload([ 'nonce' => [ 'txHash' => $txHash ]]);

        return $this->createPendingWire($user, $sender->guid);
    }

    private function createPendingWire(User $user, $sender_guid = null)
    {
        if (!$user->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        if (!$sender_guid) {
            $sender_guid = Core\Session::getLoggedInUserGuid();
        }

        return $this->pendingManager->add([
            'type' => 'wire',
            'tx_id' => $this->nonce['txHash'],
            'sender_guid' => $sender_guid,
            'data' => [
                'amount' => (string) $this->amount,
                'receiver_guid' => $user->guid,
                'entity_guid' => is_object($this->entity) ? $this->entity->guid : $this->entity,
            ]
        ]);
    }

    /**
     * @param $tx_id
     * @param $sender
     * @param $receiver
     * @param $amount
     * @return bool
     * @throws \Exception
     * @throws \Minds\Exceptions\StopEventException
     */
    function checkAndSaveWire($tx_id, $sender, $receiver, $amount)
    {
        /** @var Core\Wire\Repository $repo */
        $repo = Di::_()->get('Wire\Repository');

        $pending = $this->pendingManager->get('wire', $tx_id);

        // Check!

        if (!$pending) {
            // TODO: Log? Probably race condition.
            throw new \Exception("No pending Wire entry with hash {$tx_id}");
        }

        $pendingSender = new User($pending['sender_guid']);
        $pendingReceiver = new User($pending['data']['receiver_guid']);

        if (
            (!$pendingReceiver || (strtolower($pendingReceiver->getEthWallet()) != strtolower($receiver))) ||
            (!$pendingSender || (strtolower($pendingSender->getEthWallet()) != strtolower($sender))) ||
            (string) $pending['data']['amount'] !== (string) $amount
        ) {
            // TODO: Log?
            return false;
        }

        // Store

        $wire = (new Entities\Wire)
            ->setAmount((string) $amount)
            ->setRecurring(false)
            ->setFrom($pendingSender)
            ->setTo($pendingReceiver)
            ->setTimeCreated(time())
            ->setEntity($pending['data']['entity_guid'])
            ->setMethod('tokens');

        $wire->save();

        $repo->add($wire);

        $this->pendingManager->delete('wire', $tx_id);

        // send email to receiver
        $description = 'Wire';

        // if ($this->recurring) {
        //     $description .= ' Subscription';
        // }

        /** @var Payments\Manager $paymentsManager */
        $paymentsManager = Di::_()->get('Payments\Manager');

        $paymentsManager
            ->setPaymentId('blockchain:' . $tx_id)
            ->updatePaymentById([
                'status' => 'paid'
            ]);

        Dispatcher::trigger('wire-payment-email', 'object', [
            'charged' => false,
            'amount' => $amount,
            'unit' => 'tokens',
            'description' => $description,
            'user' => $pendingReceiver,
        ]);

        //$this->cache->destroy("counter:wire:sums:" . $merchant->getGUID() . ":*");
        $this->cache->destroy(Counter::getIndexName($pending['data']['entity_guid'], null, 'tokens', null, true));
        //$this->cache->destroy("counter:wire:sums:" . Core\Session::getLoggedInUser()->getGUID() . ":*");
    }
}
