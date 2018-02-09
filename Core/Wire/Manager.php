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
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
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

    /** @var int $sender */
    protected $sender;

    /** @var Entities\User $receiver */
    protected $receiver;

    /** @var Entity $entity */
    protected $entity;

    /** @var double $amount */
    protected $amount;

    /** @var bool $recurring */
    protected $recurring;

    /** @var string $payload */
    protected $payload;


    public function __construct(
        $cache = null,
        $repository = null,
        $subscriptionsManager = null,
        $txManager = null,
        $txRepo = null,
        $stripe = null
    ) {
        $this->cache = $cache ?: Di::_()->get('Cache');
        $this->repository = $repository ?: Di::_()->get('Wire\Repository');
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Wire\Subscriptions\Manager');
        $this->txManager = $txManager ?: Di::_()->get('Blockchain\Transactions\Manager');
        $this->txRepo = $txRepo ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
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
        if (!$this->receiver->getEthWallet() || $this->receiver->getEthWallet() != $this->payload['receiver']) {
            throw new WalletNotSetupException();
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
                    ->setAmount(-$this->amount)
                    ->setTimestamp(time())
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => $this->payload['receiver'],
                        'sender_address' => $this->payload['address'],
                        'receiver_guid' => $this->receiver->guid,
                        'sender_guid' => $this->sender->guid,
                        'entity_guid' => $this->entity->guid,
                    ]);
                $this->txManager->add($transaction);
                break;
            case 'creditcard':
                //charge the card
                $customer = (new Core\Payments\Customer())
                    ->setUser($this->sender);
                    
                $nonce = $this->payload['token'];
                // if customer doesn't exist on Stripe, create it
                if (!$this->stripe->getCustomer($customer) || !$this->customer->getId()) { 
                    $customer->setPaymentToken($nonce);
                    $customer = $stripe->createCustomer($customer);
                    $nonce = $customer->getId();
                }

                $exRate = 0.25 * 10 ** 18;
                $usd = $this->amount * $exRate * 100; //*100 for $ -> cents

                $sale = new Core\Payments\Sale();
                $sale->setOrderId('wire-' . $this->entity->guid)
                    ->setAmount($usd)
                    ->setCustomer($customer)
                    ->setSource($nonce)
                    ->capture();

                $tx = 'creditcard:' + $this->stripe->setSale($sale);

                $sendersTx = new Core\Blockchain\Transactions\Transaction();
                $sendersTx
                    ->setTx($tx)
                    ->setContract('wire')
                    ->setWalletAddress('creditcard')
                    ->setAmount(-$this->amount)
                    ->setTimestamp(time())
                    ->setUserGuid($this->owner->guid)
                    ->setCompleted(true)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => 'offchain',
                        'sender_address' => 'creditcard',
                        'receiver_guid' => $this->receiver->guid,
                        'sender_guid' => $this->sender->guid,
                        'entity_guid' => $this->entity->guid,
                    ]);

                // what the receiver gets
                $receiversTx = new Core\Blockchain\Transactions\Transaction();
                $receiversTx
                    ->setTx($tx)
                    ->setContract('wire')
                    ->setWalletAddress('offchain')
                    ->setAmount($this->amount)
                    ->setTimestamp(time())
                    ->setUserGuid($this->owner->guid)
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => 'offchain',
                        'sender_address' => 'creditcard',
                        'receiver_guid' => $this->receiver->guid,
                        'sender_guid' => $this->sender->guid,
                        'entity_guid' => $this->entity->guid,
                    ]);
                $this->txManager->add([ $sendersTx, $receiversTx ]);

                $wire = new Wire();
                $wire->setSender($this->sender)
                    ->setReceiver($this->receiver)
                    ->setEntity($this->entity)
                    ->setAmount($this->amount)
                    ->setTimestamp(time());
                $this->repo->add($wire);

                break;
        }
        
        //is this a subscription?
        if ($this->recurring) {
            $this->subscriptionsManager
                ->setAmount($this->amount)
                ->setSender($this->sender)
                ->setReceiver($this->receiver)
                ->create();
        }

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

        //$this->cache->destroy("counter:wire:sums:" . $merchant->getGUID() . ":*");
        $this->cache->destroy(Counter::getIndexName($wire->getEntity()->guid, null, 'tokens', null, true));
        //$this->cache->destroy("counter:wire:sums:" . Core\Session::getLoggedInUser()->getGUID() . ":*");

        return $success;
    }

}
