<?php

namespace Minds\Core\Wire\Methods;

use Minds\Core;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Wire\Counter;
use Minds\Entities;
use Minds\Entities\User;

class Tokens implements MethodInterface
{

    protected $amount;
    /** @var Entities\User $actor */
    protected $actor;
    protected $entity;
    /** @var Entities\User $owner */
    protected $owner;
    protected $nonce;
    protected $recurring;
    protected $timestamp;
    protected $manager;
    protected $repository;
    protected $cache;
    protected $config;

    /** @var Core\Payments\Subscriptions\Manager $subscriptionsManager */
    protected $subscriptionsManager;

    /** @var Core\Payments\Subscriptions\Repository $subscriptionsRepository */
    protected $subscriptionsRepository;

    /** @var Core\Blockchain\Transactions\Manager */
    protected $blockchainTx;

    /** @var Core\Blockchain\Pending $pendingManager */
    protected $pendingManager;

    public function __construct(
        $stripe = null,
        $manager = null,
        $repository = null,
        $cache = null,
        $config = null,
        $pendingManager = null,
        $subscriptionsManager = null,
        $subscriptionsRepository = null,
        $blockchainTx = null
    )
    {
        $this->manager = $manager ?: Core\Di\Di::_()->get('Wire\Manager');
        $this->repository = $repository ?: Core\Di\Di::_()->get('Wire\Repository');
        $this->cache = $cache ?: Core\Di\Di::_()->get('Cache');
        $this->config = $config ?: Core\Di\Di::_()->get('Config');
        $this->pendingManager = $pendingManager ?: Di::_()->get('Blockchain\Pending');
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Payments\Subscriptions\Manager');
        $this->subscriptionsRepository = $subscriptionsRepository ?: Di::_()->get('Payments\Subscriptions\Repository');
        $this->blockchainTx = $blockchainTx ?: Di::_()->get('Blockchain\Transactions\Manager');
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setActor(User $user)
    {
        $this->actor = $user;
        return $this;
    }

    public function setEntity($entity)
    {
        if (!is_object($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        $this->owner = $entity->type != 'user' ?
            Entities\Factory::build($entity->owner_guid) :
            $entity;

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

    /**
     * @return mixed
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    public function create()
    {
        if ($this->recurring) {
            return $this->createSubscription();
        }

        return $this->createWire();
    }

    /**
     * @throws \Exception
     */
    public function refund()
    {
        throw new \Exception('Cannot refund a tokens operation');
    }

    /**
     * @return mixed
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    protected function createSubscription()
    {
        if (!$this->owner->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        $this->cancelSubscription();

        $subscription = (new Core\Payments\Subscriptions\Subscription())
            ->setPlanId('wire')
            ->setPaymentMethod('tokens')
            ->setAmount($this->amount)
            ->setUser($this->actor)
            ->setEntity($this->owner)
            ->setFee($this->amount)
            ->setMerchant($this->owner);

        $this->subscriptionsManager->setSubscription($subscription);
        $this->subscriptionsManager->create();
    
        return $subscription->getId();
    }

    protected function cancelSubscription()
    {
        $subscriptions = $this->subscriptionsRepository->getList([
            'plan_id' => 'wire',
            'payment_method' => 'tokens',
            'entity_guid' => $this->owner->guid,
            'user_guid' => $this->actor->guid
        ]);

        if (!$subscriptions) {
            return false;
        }

        $subscription = $subscriptions[0];

        $this->subscriptionsManager->setSubscription($subscription);

        // Cancel old subscription first
        $this->subscriptionsManager->cancel();
    }

    /**
     * @return bool
     * @throws WalletNotSetupException
     */
    protected function createWire(array $options = [])
    {
        $options = array_merge([
            'subscription_id' => null
        ], $options);

        if (!$this->owner->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        $result = $this->pendingManager->add([
            'type' => 'wire',
            'tx_id' => $this->nonce['txHash'],
            'sender_guid' => $this->actor->guid,
            'data' => [
                'amount' => (string) $this->amount,
                'receiver_guid' => $this->owner->guid,
                'entity_guid' => $this->entity->guid,
            ]
        ]);

        $transaction = new Transaction();
        $transaction
            ->setTx($this->nonce['txHash'])
            ->setContract('wire')
            ->setWalletAddress($this->owner->getEthWallet())
            ->setAmount($this->amount)
            ->setTimestamp(time())
            ->setUserGuid($this->actor->guid)
            ->setData([
                'amount' => (string) $this->amount,
                'receiver_guid' => $this->owner->guid,
                'entity_guid' => $this->entity->guid,
            ]);

        $this->blockchainTx->add($transaction);

        if ($result) {
            /** @var Core\Payments\Manager $paymentsManager */
            $paymentsManager = Di::_()->get('Payments\Manager');

            $paymentData = [
                'payment_method' => 'tokens',
                'amount' => $this->amount,
                'description' => 'Wire @' . $this->owner->username,
                'status' => 'pending'
            ];

            if ($options['subscription_id']) {
                $paymentData['subscription_id'] = $options['subscription_id'];
            }

            return $paymentsManager
                ->setType('wire')
                ->setPaymentId('ethereum:' . $this->nonce['txHash'])
                ->setUserGuid($this->actor->guid)
                ->setTimeCreated($this->timestamp ?: time())
                ->create($paymentData);
        }

        return $result;
    }

    /**
     * @param $subscription_id
     * @return bool
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    public function onRecurring($subscription_id)
    {
        if (!$this->actor->getEthWallet()) {
            throw new WalletNotSetupException();
        }

        if (!$this->owner->getEthWallet()) {
            throw new WalletNotSetupException();
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
                $this->actor->getEthWallet(),
                $this->owner->getEthWallet(),
                Core\Blockchain\Util::toHex($token->toTokenUnit($this->amount))
            ])
        ]);

        if (!$txHash) {
            throw new \Exception('Transaction hash is null');
        }

        $this->setPayload([ 'nonce' => [ 'txHash' => $txHash ]]);

        return $this->createWire([
            'subscription_id' => $subscription_id
        ]);
    }

    // -- Token exclusive

    /**
     * @param $tx_id
     * @param $sender
     * @param $receiver
     * @param $amount
     * @return bool
     * @throws \Exception
     * @throws \Minds\Exceptions\StopEventException
     */
    public function confirmWire($tx_id, $sender, $receiver, $amount)
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

        /** @var Core\Payments\Manager $paymentsManager */
        $paymentsManager = Di::_()->get('Payments\Manager');

        $paymentsManager
            ->setPaymentId('ethereum:' . $tx_id)
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

        return true;
    }
}
