<?php
/**
 * Created by Marcelo.
 * Date: 03/07/2017
 */

namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Guid;
use Minds\Core\Util\BigNumber;
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
use Minds\Core\Wire\Subscriptions\Manager as SubscriptionsManager;
use Minds\Entities;
use Minds\Entities\User;

class Manager
{

    /** @var Data\cache\Redis */
    protected $cache;

    /** @var Repository */
    protected $repository;

    /** @var SubscriptionsManager $subscriptionsManager */
    protected $subscriptionsManager;

    /** @var Core\Blockchain\Transactions\Manager */
    protected $txManager;

    /** @var Core\Blockchain\Transactions\Repository */
    protected $txRepo;

    /** @var Entities\User $sender */
    protected $sender;

    /** @var Entities\User $receiver */
    protected $receiver;

    /** @var Entities\Entity $entity */
    protected $entity;

    /** @var double $amount */
    protected $amount;

    /** @var bool $recurring */
    protected $recurring;

    /** @var array $payload */
    protected $payload;

    /** @var Core\Config */
    protected $config;

    /** @var Core\Queue\Client */
    protected $queue;

    /** @var Core\Blockchain\Services\Ethereum */
    protected $client;

    /** @var Core\Blockchain\Token */
    protected $token;

    /** @var Core\Blockchain\Wallets\OffChain\Cap $cap */
    protected $cap;

    /** @var Core\Events\EventsDispatcher */
    protected $dispatcher;

    /** @var Delegates\Plus $plusDelegate */
    protected $plusDelegate;

    public function __construct(
        $cache = null,
        $repository = null,
        $subscriptionsManager = null,
        $txManager = null,
        $txRepo = null,
        $config = null,
        $queue = null,
        $client = null,
        $token = null,
        $cap = null,
        $dispatcher = null,
        $plusDelegate = null
    ) {
        $this->cache = $cache ?: Di::_()->get('Cache');
        $this->repository = $repository ?: Di::_()->get('Wire\Repository');
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Wire\Subscriptions\Manager');
        $this->txManager = $txManager ?: Di::_()->get('Blockchain\Transactions\Manager');
        $this->txRepo = $txRepo ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->config = $config ?: Di::_()->get('Config');
        $this->queue = $queue ?: Core\Queue\Client::build();
        $this->client = $client ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->token = $token ?: Di::_()->get('Blockchain\Token');
        $this->cap = $cap ?: Di::_()->get('Blockchain\Wallets\OffChain\Cap');
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
        $this->plusDelegate = $plusDelegate ?: new Delegates\Plus;
    }

    /**
     * Set the sender of the wire
     * @param User $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Set the entity of the wire - will also set the receiver
     * @param Entity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        if (!is_object($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        $this->receiver = $entity->type != 'user' ?
            Entities\Factory::build($entity->owner_guid) :
            $entity;

        $this->entity = $entity;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
        return $this;
    }

    /**
     * Set the payload of the transaction
     * @param array $payload
     * @return $this
     */
    public function setPayload($payload = [])
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return bool
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    public function create()
    {
        if ($this->payload['method'] == 'onchain' && (!$this->receiver->getEthWallet() || $this->receiver->getEthWallet() != $this->payload['receiver'])) {
            throw new WalletNotSetupException();
        }

        if ($this->recurring) {
            $this->subscriptionsManager
                ->setAmount($this->amount)
                ->setSender($this->sender)
                ->setReceiver($this->receiver);
        }

        switch ($this->payload['method']) {
            case 'onchain':
                //add transaction to the senders transaction log
                $transaction = new Core\Blockchain\Transactions\Transaction();
                $transaction
                    ->setUserGuid($this->sender->guid)
                    ->setWalletAddress($this->payload['address'])
                    ->setContract('wire')
                    ->setTx($this->payload['txHash'])
                    ->setAmount((string) BigNumber::_($this->amount)->neg())
                    ->setTimestamp(time())
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => $this->payload['receiver'],
                        'sender_address' => $this->payload['address'],
                        'receiver_guid' => (string) $this->receiver->guid,
                        'sender_guid' => (string) $this->sender->guid,
                        'entity_guid' => (string) $this->entity->guid,
                    ]);
                $this->txManager->add($transaction);

                if ($this->recurring) {
                    $this->subscriptionsManager
                        ->setAddress($this->payload['address'])
                        ->create();
                }

                break;
            case 'offchain':
                /** @var Core\Blockchain\Wallets\OffChain\Cap $cap */
                $this->cap
                    ->setUser($this->sender)
                    ->setContract('wire');

                if (!$this->cap->isAllowed($this->amount)) {
                    throw new \Exception('You are not allowed to spend that amount of coins.');
                }

                $txData = [
                    'amount' => (string) $this->amount,
                    'sender_guid' => (string) $this->sender->guid,
                    'receiver_guid' => (string) $this->receiver->guid,
                    'entity_guid' => (string) $this->entity->guid,
                ];

                $sendersTx = new Core\Blockchain\Wallets\OffChain\Transactions();
                $sendersTx
                    ->setAmount((string) BigNumber::_($this->amount)->neg())
                    ->setType('wire')
                    ->setUser($this->sender)
                    ->setData($txData)
                    ->create();

                $receiversTx = new Core\Blockchain\Wallets\OffChain\Transactions();
                $receiversTx
                    ->setAmount($this->amount)
                    ->setType('wire')
                    ->setUser($this->receiver)
                    ->setData($txData)
                    ->create();

                $wire = new Wire();
                $wire
                    ->setSender($this->sender)
                    ->setReceiver($this->receiver)
                    ->setEntity($this->entity)
                    ->setAmount($this->amount)
                    ->setTimestamp(time());
                $this->repository->add($wire);

                $this->plusDelegate
                    ->onWire($wire, 'offchain');

                $this->sendNotification($wire);

                $this->clearWireCache($wire);

                //is this a subscription?
                if ($this->recurring) {
                    $this->subscriptionsManager
                        ->setAddress('offchain')
                        ->create();
                }

                break;
        }

        // send wire email
        $this->dispatcher->trigger('wire:email', 'wire', [
            'receiver' => $this->receiver,
            'method' => $this->payload['method']
        ]);

        return true;
    }

    /**
     * Confirmationof wire from the blockchain
     * @param Wire $wire
     * @param Transaction $transaction - the transaction from the blockchain
     */
    public function confirm($wire, $transaction)
    {
        if ($wire->getSender()->guid != $transaction->getUserGuid()) {
            throw new \Exception('The user who requested this operation does not match the transaction');
        }

        if ($wire->getAmount() != $transaction->getData()['amount']) {
            throw new \Exception('The amount request does not match the transaction');
        }

        $wire->setGuid(Guid::build());
        $success = $this->repository->add($wire);

        //create a new transaction for receiver
        $data = $transaction->getData();
        $transaction
            ->setUserGuid($wire->getReceiver()->guid)
            ->setWalletAddress($data['receiver_address'])
            ->setAmount($wire->getAmount())
            ->setCompleted(true);
        $this->txRepo->add($transaction);

        /*Dispatcher::trigger('wire-payment-email', 'object', [
            'charged' => false,
            'amount' => $wire->getAmount,
            'unit' => 'tokens',
            'description' => 'Wire',
            'user' => $wire->getReceiver(),
        ]);*/

        $this->plusDelegate
            ->onWire($wire, $data['receiver_address']);

        $this->sendNotification($wire);

        $this->clearWireCache($wire);

        return $success;
    }

    /**
     * Call when a recurring wire is triggered
     * @param Core\Payments\Subscriptions\Subscription $subscription
     * @return void
     */
    public function onRecurring($subscription)
    {
        $sender = $subscription->getUser();
        $receiver = new User($subscription->getEntity()->guid);
        $amount = $subscription->getAmount();

        if ($subscription->getId() === 'offchain') {
            $this->setPayload([
                'method' => 'offchain',
            ]);
        } else { //onchain
            $txHash = $this->client->sendRawTransaction($this->config->get('blockchain')['contracts']['wire']['wallet_pkey'],
                [
                    'from' => $this->config->get('blockchain')['contracts']['wire']['wallet_address'],
                    'to' => $this->config->get('blockchain')['contracts']['wire']['contract_address'],
                    'gasLimit' => BigNumber::_(200000)->toHex(true),
                    'data' => $this->client->encodeContractMethod('wireFromDelegate(address,address,uint256)', [
                        $subscription->getId(),
                        $receiver->getEthWallet(),
                        BigNumber::_($this->token->toTokenUnit($amount))->toHex(true)
                    ])
                ]);
            $this->setPayload([
                'method' => 'onchain',
                'address' => $subscription->getId(), //sender address
                'receiver' => $receiver->getEthWallet(),
                'txHash' => $txHash,
            ]);
        }

        $this->setSender($sender)
            ->setEntity($receiver)
            ->setAmount($subscription->getAmount());

        $this->create();
    }

    /**
     * @param Wire $wire
     */
    protected function clearWireCache(Wire $wire)
    {
        $this->cache->destroy(Counter::getIndexName($wire->getEntity()->guid, null, 'tokens', null, true));
    }

    protected function sendNotification(Wire $wire = null)
    {
        $this->queue->setQueue("WireNotification")
            ->send([
                "amount" => $wire ?
                    (float) BigNumber::_($wire->getAmount(), 18)->div(10 ** 18)->toString()
                    : (float) BigNumber::_($this->amount, 18)->div(10 ** 18)->toString(),
                "sender" => serialize($wire ? $wire->getSender() : $this->sender),
                "entity" => serialize($wire ? $wire->getEntity() : $this->entity),
                "subscribed" => $wire ? $wire->isRecurring() : $this->recurring,
            ]);
    }

}
